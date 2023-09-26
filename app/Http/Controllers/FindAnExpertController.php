<?php

namespace App\Http\Controllers;

use App\Http\Requests\Expert\ContactRequest;
use App\Http\Requests\Expert\FilterExpertRequest;
use App\Mail\SendGridMailer;
use App\Models\Expert;
use App\Models\ExpertType;
use App\Models\Post;
use App\Models\Request as InstallerRequest;
use App\Models\RequestShareMetrics;
use App\Models\Sector;
use App\Models\ShedSolution;
use App\Models\ShedSolutionMetrics;
use App\Models\Speciality;
use App\Models\User;
use ESolution\DBEncryption\Encrypter;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Inertia\Response;
use Inertia\ResponseFactory;
use function inertia;
use Inertia\Inertia;

class FindAnExpertController extends Controller
{
    private $_successMsg = 'Your message has been <strong>successfully sent</strong>. %s will contact you soon.';

    public function index()
    {
        $expertTypes = ExpertType::all();

        return Inertia::render('FindAnExpert/FindAnExpert', [
            'expertTypes' => $expertTypes,

        ]);
    }

    public function details(FilterExpertRequest $request, ExpertType $expertType)
    {
        $farmer = Auth::user();

        $activeSectors = $request->get('sector');
        $activeSpecialities = $request->get('speciality');

        $experts = Expert::with('specialities')
            ->where('expert_type_id', $expertType->id);

        if (! empty($activeSectors)) {
            $experts = $experts->whereHas('sectors', function ($q) use ($activeSectors) {
                $q->whereIn('sector_user_expert.sector_id', $activeSectors);
            });
        }
        if (! empty($activeSpecialities)) {
            $experts = $experts->whereHas('specialities', function ($q) use ($activeSpecialities) {
                $q->whereIn('speciality_id', $activeSpecialities);
            });
        }

        $experts = $experts->where(function ($q) {
            $q->whereNull('user_id')
                ->orWhereHas('user');
        });

        return inertia('FindAnExpert/Index', [
            'experts' => $experts->get(),
            'filters' => [
                'speciality' => Speciality::all(),
                'sector' => Sector::all(),
            ],
            'expertType' => $expertType,
            'activeSpeciality' => $request->speciality,
            'activeSector' => $request->sector,
        ]);
    }

    /**
     * Page to list all requests
     *
     * @param  Request  $request
     * @return \Inertia\Response
     */
    public function viewInstallerProject(Request $request, int $projectId)
    {
        $project = Post::where('id', $projectId)->firstOrFail();

        return inertia('InstallerProject', [
            'project' => $project,
        ]);
    }

    /**
     * Method to populate the Installer Projects on the contact installer page
     *
     * @param Expert $expert
     * @return Response|ResponseFactory
     */
    public function show(Expert $expert)
    {
        $expert = Expert::with(['installerProjects' => function($query) {
            if ($user = Auth::user() && Auth::user()->hasRole('Installer')  ) {
                $query->whereIn('project_published_status', [0, 1]);
            } else {
                $query->where('project_published_status', 1);
            }
        }])
            ->with('specialities')
            ->whereId($expert->id)->find($expert->id);

        $sectors = Sector::get();
        if ($expert->expert_type_id === 1) {
            if (Auth::check()) {
                $expert->shedSolution = Auth::user()?->shedSolution()->orderBy('created_at', 'desc')->get();
                $expert->defaultEmail = Auth::user()?->email;
                $expert->defaultPhone = Auth::user()?->phone;
            }
        }

        return inertia('FindAnExpert/Show', [
            'expert' => $expert,
            'sectors' => $sectors,
            'userShedSolutions' => $expert->shedSolution,
            'defaultEmail' => $expert->defaultEmail,
            'defaultPhone' => $expert->defaultPhone,
        ]);
    }

    /**
     * Method to fire an email and track share metrics of contacted installers
     *
     * @param ContactRequest $request
     * @param Expert $expert
     * @return RedirectResponse
     */
    public function sendContactRequests(ContactRequest $request, Expert $expert)
    {
        $user = Auth::user();
        $validated = $request->validated();

        //create Request and gather email variables
        $emailVars = [
            'installer_name' => $expert->company_name,
            'farmer_name' => $user->name ?? 'Guest',
            'farmer_email' => $validated['email'],
            'farmer_phone' => $validated['phone'] ?? '',
        ];
        $newRequest = [
            'request_status' => InstallerRequest::NEW_REQUEST,
            'message' => $validated['message'],
            'user_id' => $user->id ?? null,
            'installer_id' => $expert->id,
            'contact_phone' => $validated['phone'],
            'contact_email' => $validated['email'],
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

        if (empty($validated['project'])) {
            //projectless
            $newRequest['sector_id'] = $validated['sector'];
            $newRequest['project_type'] = $validated['project_type'];
            //email vars
            $emailVars = $emailVars + [
                'has_project' => 0,
                'project_situation' => empty($validated['project_type']) ? 'New Build' : 'Refurbishment',
                'project_sector' => Sector::where('id', $newRequest['sector_id'])->pluck('name')->first(),
            ];
        } else {
            //with project
            $shedSolution = ShedSolution::with('goals', 'reasons')->where('id', $validated['project'])->first();
            //Todo load project and get sector, type
            $newRequest['shed_solution_id'] = $shedSolution->id;
            //set  email vars
            $emailVars = $emailVars + [
                'has_project' => 1,
                'project_situation' => $shedSolution->project_type_id === ShedSolution::TYPE_NEW_BUILD
                    ? 'New Build'
                    : 'Refurbishment',
                'project_sector' => $shedSolution->sector->name,
                'project_size' => sprintf(
                    "{$shedSolution->length}%1\$s x {$shedSolution->width}%1\$s",
                    $shedSolution->size_type_id === 1 ? "'" : 'm'),
                'project_goal' => $shedSolution->project_type_id === ShedSolution::TYPE_NEW_BUILD
                    ? implode(',', $shedSolution->goals->pluck('name')->toArray())
                    : implode(',', $shedSolution->reasons->pluck('name')->toArray()),
            ];
        }

        $requestId = InstallerRequest::create($newRequest);

        $emailVars['review_url'] = route('requests.view', ['requestId' => $requestId]);

        //Send email to expert
        if ($expert->user->contact_by_email == true) { // Check the installer has opted in to receive emails
            Mail::to([$expert->user->email]) // Check if email sent
                ->send(new SendGridMailer(env('SG_NEW_REQUEST_ID'), $emailVars));
        }

        if (!empty($shedSolution)) {
            RequestShareMetrics::logSendFindAnExpert($user->id, $shedSolution->id, $expert->id);
        } elseif (empty($shedSolution)) {
            RequestShareMetrics::logSendFindAnExpertNoProject($user->id, $expert->id);
        }

        return redirect()
            ->back()
            ->with(
                'message', [
                    'type' => 'success',
                    'msg' => sprintf($this->_successMsg, $expert->company_name),
                ]);
    }
}
