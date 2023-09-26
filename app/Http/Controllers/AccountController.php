<?php

namespace App\Http\Controllers;

use App\Contracts\Adb2cServiceInterface;
use App\Http\Requests\Profile\InstallerProjectRequest;
use App\Http\Requests\Profile\UpdateGeneralRequest;
use App\Http\Requests\Profile\UpdateProfileRequest;
use App\Http\Requests\Users\UpdateContactRequest;
use App\Mail\SendGridMailer;
use App\Models\Expert;
use App\Models\ExpertType;
use App\Models\File;
use App\Models\Location;
use App\Models\Material;
use App\Models\Post;
use App\Models\PostcodeMap;
use App\Models\PostMeta;
use App\Models\PostType;
use App\Models\Sector;
use App\Models\Speciality;
use App\Models\User;
use App\Repositories\UserRepository;
use App\Scopes\PostTypeScope;
use App\Services\PostMetaService;
use App\Services\SendEmailToAdminService;
use ESolution\DBEncryption\Encrypter;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Inertia\Inertia;
use Inertia\Response;

class AccountController extends Controller
{
    private $_successMsg = 'Your project has been <strong>successfully submitted</strong>. You can always edit and add new projects from your Profile section.';

    /**
     * @var UserRepository
     */
    private $userRepository;

    /**
     * Reirect back to login if user is unauthenticated
     *
     * RequestController constructor.
     */
    public function __construct(
        UserRepository $userRepository,
        protected Adb2cServiceInterface $adb2cService,
        protected Request $request,
        PostMetaService $postMetaService
    ) {
        $this->middleware('auth');
        $this->postMetaService = $postMetaService;
    }

    private function getUserProfile()
    {
        $user = Auth::user();

        return [
            'farmName' => $user->location?->name,
            'farmLocation' => $user->location,
            'farmSector' => $user->sectors?->first(),
        ];
    }

    //
    public function index()
    {
        $profile = session('userProfile', $this->getUserProfile());

        return Inertia::render('MyAccount/Index', array_merge(
            ['sectors' => Sector::all()],
            $profile
        ));
    }

//    public function deleteInstallerProject()
//    {
//        $user = Auth::user();
//        //validate Delete Key
//        if ($request->has('_deleteKey')) {
//            $dKey = Encrypter::decrypt($request->post('_deleteKey'));
//            if ($dKey) {
//                $dKey = json_decode($dKey, true);
//                if ($dKey && is_array($dKey)) {
//                    if (empty(array_diff_assoc($dKey, [$user->id, $user->name, $user->email]))) {
//                        // Auth::logout();
//                        Auth::guard('web')->logout();
//                        $this->request->session()->flush();
//                        $this->request->session()->regenerate();
//                        $user->delete();
//
//                        //return redirect('/templogout');
//
//                        return redirect()->to($this->adb2cService->logout());
//                    }
//                }
//            }
//        }
//    }

    /**
     * Method for the installer to save their Installer Project data
     *
     * @param InstallerProjectRequest $request
     * @return RedirectResponse
     */
    public function saveInstallerProject(InstallerProjectRequest $request)
    {
        $user = Auth::user();
        if (! $user->hasRole('Installer')) {
            abort(403);
        }

        $validated = $request->validated();
        $msg = '';

        if ($request->has('_deleteKey')) {
            $dKey = Encrypter::decrypt($request->post('_deleteKey'));
            if ($dKey) {
                $dKey = json_decode($dKey, true);
                if ($dKey && is_array($dKey)) {
                    $post = Post::where('author', $user->id)->where('id', $request->post('id'))->first();
                    if ($post && empty(array_diff_assoc($dKey, [$post->id, $post->author, $post->created_at->toDateTimeString()]))) {
                        $post->postMeta()->delete();
                        $post->delete();
                        $msg = 'Your project has been <strong>successfully deleted</strong>.';
                    }
                }
            }
        } else {
            $postType = PostType::withoutGlobalScope(PostTypeScope::class)->where('name', '_installer_project')->pluck('id')->firstOrFail();

            $slug = str_replace(' ', '-', preg_replace('/[^a-z0-9 ]/', '', strtolower($validated['title'])));
            $existing = Post::where('post_title', $validated['title'])->get();
            if ($existing->count() > 0) {
                $slug .= '-'.$existing->count();
            }

            //gallery move new uploads to storage/.../public/installer-projects with File
            $newFiles = $existingFiles = [];
            if ($request->has('id')) {
                $galleryMeta = PostMeta::where('fields_id', 1)->where('posts_id', $request->post('id'))->first();
                if ($galleryMeta) {
                    $existingIds = json_decode($galleryMeta->value, true);
                    if ($existingIds) {
                        foreach (File::whereIn('id', $existingIds)->get() as $file) {
                            $existingFiles[$file->id] = $file->path.'/'.$file->filename;
                        }
                    }
                }
            }
            foreach ($validated['gallery'] as $postFile) {
                $path = pathinfo($postFile);
                if ($path['dirname'] === 'upload') {
                    $file = File::moveUploadedFile($path['basename'], File::FILE_IMAGE, 'installer-projects');
                    $file->save();
                    $newFiles[] = $file->id;
                } else {
                    $p = array_search($path['dirname'].'/'.$path['basename'], $existingFiles);
                    if ($p) {
                        $newFiles[] = $p;
                        unset($existingFiles[$p]);
                    }
                }
            }

            $post = Post::updateOrCreate(
                ['id' => $request->post('id')], [
                    'post_title' => $validated['title'],
                    'post_content' => $validated['description'],
                    'post_type' => $postType,
                    'author' => $user->id,
                    'cover_image' => $newFiles[0],
                    'project_published_status' => POST::PROJECT_IS_DRAFT,
                    'uri_slug' => $slug,
                ]
            );

            //remove unused
            foreach (File::whereIn('id', array_keys($existingFiles))->get() as $file) {
                $path = "/storage/{$file->path}/{$file->filename}";
                if (file_exists(public_path().$path)) {
                    unlink(public_path().$path);
                }
                $file->delete();
            }

            //Gallery (reuse field 1))
            PostMeta::updateOrCreate(
                ['posts_id' => $post->id, 'fields_id' => 1], [
                    'posts_id' => $post->id,
                    'fields_id' => 1,
                    'value' => json_encode($newFiles),
                ]
            );

            $this->postMetaService->updateInstallerProjectPostMeta($post, $validated);

            $emailService = new SendEmailToAdminService();
            $emailService->sendEmailToAdmins(env('SG_EDIT_PROJECT_ID'), [
                'installer_id' => $user->id,
                'installer_name' => $user->name,
                'project_title' => $post->post_title,
                'review_url' => route('home'),
            ], true);

            $msg = $this->_successMsg;
        }

        return redirect()->back()->with('message', ['type' => 'success', 'msg' => $msg]);
    }

    /**
     * Method for the farmer to update their profile
     *
     * @param UpdateProfileRequest $request
     * @return RedirectResponse
     */
    public function updateProfile(UpdateProfileRequest $request)
    {
        $validated = $request->validated();
        $user = Auth::user();
        $msg = '';
        if ($user->hasRole('Farmer')) {
            $msg = 'Your Farm details have been updated.';
            //create location for upsert
            Location::updateOrCreate(
                [
                    'entity_id' => $user->id,
                    'entity_type' => $user::class,
                ],
                [
                    'entity_id' => $user->id,
                    'entity_type' => $user::class,
                    'name' => $validated['farmName'],
                    'street' => $validated['farmLocation'],
                ]
            );
            $user->sectors()->sync($validated['farmSector']);
        } elseif ($user->hasRole('Installer')) {
            $msg = 'Your Installer Profile has been updated and will be visible in Find an Expert.';

            $location = json_decode($validated['location']);
            $postcode = $location->postcode;
            $parts = explode(" ", $postcode);
            $first_part = $parts[0];
            $postcodeMap = PostcodeMap::where('outcode', $first_part)->first();

            //create location for upsert
            $location = Location::updateOrCreate(
                [
                    'entity_id' => $user->id,
                    'entity_type' => $user::class,
                ], array_merge([
                    'entity_id' => $user->id,
                    'entity_type' => $user::class,
                    'name' => $validated['company_name'],
                    'sizeradius' => empty($validated['nationwide']) ? $validated['radius'] : null,
                    'sizeradiustype' => empty($validated['nationwide']) ? $validated['radiusType'] : null,
                    'postcode_map_id' => $postcodeMap->id,
                ], json_decode($validated['location'], true))
            );

            //create expert for upsert
            $expert = Expert::updateOrCreate(
                [
                    'user_id' => $user->id,
                ], [
                    'user_id' => $user->id,
                    'company_name' => $validated['company_name'],
                    'location_id' => 1,
                    'nationwide' => $validated['nationwide'],
                    'description' => $validated['description'],
                    'expert_type_id' => ExpertType::where('name', 'Installers')->pluck('id')->first(),
                ]
            );
            //handle logo
            $path = pathinfo($validated['company_logo']);
            if ($path['dirname'] === 'upload') {
                //move uploaded
                $file = File::moveUploadedFile($path['basename'], File::FILE_IMAGE, 'experts/company-logo');
                $file->save();
                $fId = null;
                //unlink old if applicable
                if (! empty($expert->expertLogo)) {
                    $path = "/storage/{$expert->expertLogo->path}/{$expert->expertLogo->filename}";
                    if (file_exists(public_path().$path)) {
                        unlink(public_path().$path);
                    }
                    $fId = $expert->logo;
                }
                //reassign new logo
                $expert->logo = $file->id;
                $expert->save();
                if (! empty($fId)) {
                    File::where('id', $fId)->first()->delete();
                }
            }

            //specialities
            $expert->specialities()->sync($validated['specialities']);
            //sectors
            $expert->sectors()->sync($validated['sector']);
            if ($request->firstTime) {
                return redirect()->back();
            }
        }

        return redirect()->back()->with('message', ['type' => 'success', 'msg' => $msg]);
    }

    /**
     * Method for users to edit their general info
     *
     * @param UpdateGeneralRequest $request
     * @return RedirectResponse
     */
    public function editGeneralSubmit(UpdateGeneralRequest $request)
    {
        //send email
        $validated = $request->validated();

        $user = Auth::user();

        //Email Update notification
        $emailService = new SendEmailToAdminService();
        $emailService->sendEmailToAdmins(env('SG_EDIT_USER_ID'), [
            'user_id' => $user->id,
            'old_name' => $user->name,
            'old_email' => $user->email,
            'old_phone' => $user->phone,
            'new_email' => strtolower($validated['generalEmail']),
            'new_name' => $validated['generalName'],
            'new_phone' => $validated['generalPhone'],
            'review_url' => route('home'),
        ]);

        //MYET-514
        $route = 'my-account.index';
        $msg = "Your request to change details has been sent. These details are not updated automatically, " .
            "ETEX will contact you once applied.";

        if ( strtolower($validated['generalEmail']) !== strtolower($user->email) ) {
            $user->email = strtolower($validated['generalEmail']);
            $user->save();

            $msg = "Your request to change details has been sent and as you have changed your email you have been " .
                "<strong>logged out</strong>. These details are not updated automatically, ETEX will contact you " .
                "once you can use your new email address.";
            $route = 'auth.logout';
        }

        return redirect()->route($route)->with('message', ['type' => 'success', 'msg' => $msg]);

    }

    /**
     * Page to display the user edit form
     *
     * @return Response
     */
    public function editContact()
    {
        return Inertia::render('MyAccount/EditContact', []);
    }

    /**
     * @param Request $request
     * @return RedirectResponse
     */
    public function saveContactPreferences(UpdateContactRequest $request){

        $user = Auth::user();

        $validated = $request->validated();

        $user->contact_by_email = $validated['canContact'];
        $user->save();

        $msg = 'Your preference to receive email notifications for new requests has been saved.';

        return redirect()->route('my-account.index')->with('message', ['type' => 'success', 'msg' => $msg]);
    }

    /**
     * Page to display the user profile
     *
     * @return Response
     */
    public function viewProfile()
    {
        $user = Auth::user();
        if (! $user->hasRole('Installer')) {
            abort(403);
        }
        if ($user->expert) {
            $user->expert->load('specialities', 'expertLogo');
        }

        $projects = Post::withoutGlobalScope(PostTypeScope::class)
            ->with('postMeta', 'postType', 'author')
            ->where('author', $user->id)
            ->get();

        $flash = session('flash', []);
        if (! isset($flash['file-uploads'])) {
            $flash['file-uploads'] = [
                'gallery' => [
                    'id' => null,
                    'url' => null,
                    'delete_key' => null,
                ],
            ];
        }
        if (! isset($flash['file-removed'])) {
            $flash['file-removed'] = [];
        }

        return Inertia::render('MyAccount/InstallerProfile', [
            'user' => $user,
            'sectors' => Sector::all(),
            'specialities' => Speciality::all(),
            'materials' => Material::all(),
            'projects' => $projects,
            'fileUploads' => $flash['file-uploads'],
            'fileRemoved' => $flash['file-removed'],
        ]);
    }

    /**
     * Page to display the general edit form
     *
     * @return Response
     */
    public function editGeneral()
    {
        $user = Auth::user();
        $dKey = Encrypter::encrypt(json_encode([
            $user->id, $user->name, $user->email,
        ]));

        return Inertia::render('MyAccount/EditGeneral', [
            'userId' => Auth::user()->id,
            'delete_key' => $dKey,
        ]);
    }

    /**
     * Delete the user.
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function deleteUser(Request $request)
    {
        $user = Auth::user();
        //validate Delete Key
        if ($request->has('_deleteKey')) {
            $dKey = Encrypter::decrypt($request->post('_deleteKey'));
            if ($dKey) {
                $dKey = json_decode($dKey, true);
                if ($dKey && is_array($dKey)) {
                    if (empty(array_diff_assoc($dKey, [$user->id, $user->name, $user->email]))) {
                        //send email to admins
                        $emailService = new SendEmailToAdminService();
                        $emailService->sendEmailToAdmins(env('SG_DELETE_USER_ID'), [
                            'review_url' => route('home'),
                            'user_name' => $user->name,
                            'user_email' => $user->email,
                            'user_phone' => $user->phone,
                            'user_id' => $user->id,
                        ]);

                        $user->expert()->delete();
                        $user->location()->delete();
                        $user->delete();
                        $msg = "Your account has been disabled, queued for deletion and you have been logged out. " .
                            "Your details are not deleted automatically, ETEX will contact you once removed.";
                        //return Inertia::location('/logout');
                        return redirect()->route('auth.logout')->with('message', ['type' => 'error', 'msg' => $msg]);
                    }
                }
            }
        }

        return redirect()->back();
    }
}
