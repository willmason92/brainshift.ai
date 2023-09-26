<?php

namespace App\Http\Controllers;

use App\Http\Requests\ShedSolution\AttachNanoPixelRequest;
use App\Http\Requests\ShedSolution\SaveShedSolutionRequest;
use App\Http\Requests\ShedSolution\ShareShedSolutionRequest;
use App\Mail\SendGridMailer;
use App\Models\File;
use App\Models\Goals;
use App\Models\Reasons;
use App\Models\RequestShareMetrics;
use App\Models\Sector;
use App\Models\ShedSolution;
use App\Models\ShedSolutionMetrics;
use App\Models\Request as InstallerRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Str;
use Inertia\Inertia;

class ShedSolutionController extends Controller
{
    /**
     * Reirect back to login if user is unauthenticated
     *
     * RequestController constructor.
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Page to display questionnaire routes
     *
     * @return \Inertia\Response
     */
    public function index()
    {
        return Inertia::render('ShedSolutions/Questionnaire/Index', [
            'goals' => Goals::all(),
            'reasons' => Reasons::all(),
            'sectors' => Sector::get(),
        ]);
    }

    /**
     * Route to render 2D/3D shed solution based on ID
     *
     * @return \Inertia\Response
     */
    public function renderId($shedSolutionId, Request $request)
    {
        $user = Auth::user();

        $shedSolution = $user->shedSolution()->where('id', $shedSolutionId)->first();
        if (! $shedSolution) {
            abort(403);
        }

        $ssAnswers['solutionId'] = $shedSolution->id;
        $ssAnswers['formPath'] = $shedSolution->project_type_id;
        $ssAnswers['sector'] = $shedSolution->sector_id;

        $ssAnswers['length'] = $shedSolution->length;
        $ssAnswers['width'] = $shedSolution->width;
        $ssAnswers['unit'] = $shedSolution->size_type_id;

        $ssAnswers['buildingAge'] = $shedSolution->age;
        $ssAnswers['unknownAge'] = empty($shedSolution->age);
        $ssAnswers['typeOfBuilding'] = $shedSolution->frame_type ?? null;
        $ssAnswers['responsibility'] = $shedSolution->responsibility
            ? $shedSolution->responsibility
            : null;
        $ssAnswers['goals'] = $shedSolution->goals->pluck('id')->toArray();
        $ssAnswers['reasons'] = $shedSolution->reasons->pluck('id')->toArray();

        $ssAnswers['np_json_config'] = $shedSolution->np_json_config ?? null;

        $ssAnswers['sector_name'] = strtolower(Sector::where('id', $ssAnswers['sector'])->pluck('name')->firstOrFail());

        return Inertia::render('ShedSolutions/Questionnaire/Render', [
            'shedSolution' => $ssAnswers,
        ]);
    }

    /**
     * Route to render 2D/3D shed solution based on session
     *
     * @param  Request  $request
     * @return \Illuminate\Http\RedirectResponse|\Inertia\Response
     */
    public function renderSession(Request $request)
    {
        $ssAnswers = session('shed-solution');
        if (empty($ssAnswers)) {
            return Redirect::route('shed-solution.index')->with('message', [
                'type' => 'error',
                'msg' => 'We are unable to reload your project, please start again.',
            ]);
        }

        if (! empty($ssAnswers['sector'])) {
            $ssAnswers['sector_name'] = strtolower(Sector::where('id', $ssAnswers['sector'])->pluck('name')->firstOrFail());
        }

        return Inertia::render('ShedSolutions/Questionnaire/Render', [
            'shedSolution' => $ssAnswers,
        ])->with(['shed-solution', $ssAnswers]);
    }

    /**
     * List projects for farmer my-account section
     *
     * @return \Inertia\Response
     */
    public function shedSolutions(Request $request)
    {
        $user = Auth::user();

        return Inertia::render('ShedSolutions/Index', [
            'shedSolutions' => ShedSolution::with('shareMetrics')->where('user_id', $user->id)->orderBy('created_at', 'desc')->get(),
        ]);
    }

    /**
     * Project detail page
     *
     * @param $id
     * @return \Inertia\Response
     */
    public function shedSolutionDetail($id)
    {
        $userId = Auth()->user()->id;
        $shedSolution = ShedSolution::with('shareMetrics')->where('id', $id)->firstOrFail();
        $sharedExperts = InstallerRequest::where('user_id', $userId)->where('shed_solution_id', $id)->get();
        $sharedEmails = RequestShareMetrics::where('user_id', $userId)->where('shed_solution_id', $id)->where('email', '!=', 'null')->get();

        return Inertia::render('ShedSolutions/View', [
            'shedSolution' => $shedSolution,
            'sharedExperts' => $sharedExperts,
            'sharedEmails' => $sharedEmails,
        ]);
    }

    /**
     * Store the questionnaire data in a flash message for the render
     *
     * @param SaveShedSolutionRequest $request
     * @return RedirectResponse
     */
    public function store(SaveShedSolutionRequest $request)
    {
        $valid = $request->validated();

        $solution = [
            'formPath' => $valid['formPath'],
            'sector' => $valid['sector'],
            'length' => $valid['length'],
            'width' => $valid['width'],
            'unit' => $valid['unit'],
        ];

        if ($valid['formPath']) {
            //Add refurb fields
            $solution['unknownAge'] = $request->unknownAge;
            $solution['buildingAge'] = $request->formPath
                ? empty($request->unknownAge) ? $request->buildingAge : null
                : null;
            $solution['typeOfBuilding'] = $request->formPath ? $request->typeOfBuilding : null;
            $solution['responsibility'] = $request->formPath ? $request->responsibility : null;
            $solution['reasons'] = $request->reasons ? $request->reasons : [];
        } else {
            //new build
            $solution['goals'] = $request->goals ? $request->goals : [];
        }

        return Redirect::route('shed-solution.render.session')
            ->with('shed-solution', $solution);
    }

    /**
     * Store the data from the questionnaire and the render
     * Send email
     * Track create/edit metrics
     *
     * @param AttachNanoPixelRequest $request
     * @param int $id
     * @return RedirectResponse
     */
    public function storeRender(AttachNanoPixelRequest $request, int $id = 0)
    {
        $valid = $request->validated();
        $user = Auth::user();

        //handle image
        $pInfo = pathinfo(public_path().$valid['np_image']);
        $imageName = Str::slug($valid['np_name']).'-'.time().'.'.pathinfo(
            public_path().$valid['np_image'],
            PATHINFO_EXTENSION
        );

        $file = File::moveUploadedFile($pInfo['basename'], File::FILE_IMAGE, 'shed-solutions', $imageName);
        $file->save();

        $data = [
            'id' => $id,
            'project_type_id' => $valid['formPath'],
            'sector_id' => $valid['sector'],
            'length' => $valid['length'],
            'width' => $valid['width'],
            'size_type_id' => $valid['unit'],
            'age' => $valid['formPath']
                ? empty($valid['unknownAge']) ? $valid['buildingAge'] : 0
                : null,
            'frame_type' => $valid['formPath'] ? $valid['typeOfBuilding'] : null,
            'responsibility_id' => $valid['formPath'] ? $valid['responsibility'] : null,
            'title' => $valid['np_name'],
            'np_json_config' => $valid['np_json_config'],
            'project_file_id' => $file?->id ?? null,
            'user_id' => $user?->id,
        ];

        if($shedSolution = ShedSolution::updateOrCreate([
            'id' => $id,
        ], $data)) {
        }

        if ($shedSolution->wasChanged()) {
            ShedSolutionMetrics::logEditShedSolution($user->id, $shedSolution->id);
        } else {
            ShedSolutionMetrics::logCreateShedSolution($user->id, $shedSolution->id);
        }


        $shedSolution->goals()->sync($valid['goals'] ?? []);
        $shedSolution->reasons()->sync($valid['reasons'] ?? []);

        return redirect()->route('my-account.shed-solutions')->with('message', [
            'type' => 'success',
            'msg' => 'Your <strong>shed solution</strong> has been saved successfully',
        ]);
    }

    /**
     * Share the shed solution via email input
     *
     * @param ShareShedSolutionRequest $request
     * @return RedirectResponse
     */
    public function sendShedSolution(ShareShedSolutionRequest $request)
    {
        $valid = $request->validated();

        $user = Auth::user();

        $shedSolution = ShedSolution::whereId($valid['projectId'])->first();

        $emailVars = [
            'destination_name' => $valid['email'],
            'farmer_name' => $user->name,
            'farmer_email' => $user->email,
            'farmer_phone' => $user->phone,
            'solution_name' => $shedSolution->title,
            'solution_sector' => $shedSolution->sector->name,
            'solution_size' => $shedSolution->length.' x '.$shedSolution->width,
            'solution_image' => env('APP_URL').$shedSolution->projectFile->url,
        ];

        $emailVars['farm_address'] = '';
        if ($user?->location) {
            $emailVars['farm_address'] = implode(',<br />',
                array_filter(
                    array_intersect_key(
                        $user->location->toArray(),
                        array_flip(
                            ['name', 'street', 'city', 'postcode', 'country']
                        )
                    )
                )
            );
        }

        $emailVars = $emailVars + [
            'solution_name' => $shedSolution->title,
            'solution_sector' => $shedSolution->sector->name,
            'project_situation' => $shedSolution->project_type_id === ShedSolution::TYPE_NEW_BUILD
                ? 'New Build'
                : 'Refurbishment',
            'project_size' => sprintf(
                "{$shedSolution->length}%1\$s x {$shedSolution->width}%1\$s",
                $shedSolution->size_type_id === 1 ? "'" : 'm'),
            'project_goal' => $shedSolution->project_type_id === ShedSolution::TYPE_NEW_BUILD
                ? implode(',', $shedSolution->goals->pluck('name')->toArray())
                : implode(',', $shedSolution->reasons->pluck('name')->toArray()),
        ];

        if(Mail::to($valid['email'])->send(new SendGridMailer(env('SG_SHARE_SOLUTION_ID'), $emailVars))) {
            RequestShareMetrics::logSendEmail($user->id, $shedSolution->id, $request->email);
        }

        return redirect()->back()
            ->with('message', [
                'type' => 'success',
                'msg' => 'Your shed solution has been sent to '.$valid['email'].' by email',
            ]);
    }

    /**
     * Method to delete shed solutions that haven't been shared
     *
     * @param $id
     * @return RedirectResponse
     */
    public function delete($id)
    {
        $userId = Auth::user()->id;
        $shedSolution = ShedSolution::whereId($id)->firstOrFail();
        $shedSolution->delete();
        $shedSolution->goals()->delete();
        $shedSolution->reasons()->delete();
        $shedSolution->projectFile()->delete();

        ShedSolutionMetrics::logDeleteShedSolution($userId, $id);

        return redirect()->route('my-account.shed-solutions')
            ->with('message', [
                'type' => 'success',
                'msg' => 'The shed solution has been deleted',
            ]);
    }
}
