<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\InstallerProjects\UpdateInstallerProjectRequest;
use App\Http\Requests\Profile\InstallerProjectRequest;
use App\Models\File;
use App\Models\Location;
use App\Models\Material;
use App\Models\Post;
use App\Models\PostMeta;
use App\Models\PostType;
use App\Models\Sector;
use App\Models\User;
use App\Scopes\PostTypeScope;
use App\Services\PostMetaService;
use App\Services\SendEmailToAdminService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;
use Inertia\Response;

class InstallerProjectController extends Controller
{
    /**
     * Redirect back to login if user is unauthenticated
     *
     * InstallerProjectController constructor.
     */
    public function __construct(PostMetaService $postMetaService)
    {
        $this->middleware('auth');
        $this->postMetaService = $postMetaService;
    }

    /**
     * List all installer projects
     *
     * @return Response
     */
    public function index()
    {
        $user = Auth::user();

        if ($user->hasRole('Admin')) {
            $projects = Post::getProjectsForAdminInRegion($user);
        } elseif ($user->hasRole('SuperAdmin')) {
            $projects = Post::getInstallerProjects()
                ->orderByDesc('project_published_status')
                ->with('postMeta', 'postType', 'author')
                ->get();
        }

        // transform projects array publish status into string
        foreach ($projects as $project) {
            $project['project_published_status'] = $project['project_published_status'] == 0 ? 'Unpublished' : 'Published';
        }

        return Inertia::render('Admin/InstallerProjects/Index', [
            'data' => $projects,
        ]);
    }

    /**
     * Display/edit the project details based on the Project ID
     *
     * @param $id
     * @return Response
     */
    public function detail($projectId)
    {
        $project = Post::getInstallerProject($projectId)
            ->with('postMeta', 'postType', 'author')
            ->firstOrFail();

        $flash = session('flash', []);
        if (!isset($flash['file-uploads'])) {
            $flash['file-uploads'] = [
                'gallery' => [
                    'id' => null,
                    'url' => null,
                    'delete_key' => null,
                ],
            ];
        }
        if (!isset($flash['file-removed'])) {
            $flash['file-removed'] = [];
        }

        return Inertia::render('Admin/InstallerProjects/Detail', [
            'project' => $project,
            'edit' => false,
            'materials' => Material::all(),
            'sectors' => Sector::all(),
            'fileUploads' => $flash['file-uploads'],
            'fileRemoved' => $flash['file-removed'],
        ]);
    }

    /**
     * Function to post the edit for the chosen Installer Project
     *
     * @param InstallerProjectRequest $request
     * @param $id
     * @return RedirectResponse
     */
    public function editInstallerProject(InstallerProjectRequest $request)
    {
        $user = Auth::user();

        $validated = $request->validated();

        $postType = PostType::withoutGlobalScope(PostTypeScope::class)->where('name', '_installer_project')->pluck('id')->firstOrFail();
        $slug = str_replace(' ', '-', preg_replace('/[^a-z0-9 ]/', '', strtolower($validated['title'])));

        //gallery move new uploads to storage/.../public/installer-projects with File
        $newFiles = $existingFiles = [];
        if ($request->has('id')) {
            $galleryMeta = PostMeta::where('fields_id', 1)->where('posts_id', $request->post('id'))->first();
            if ($galleryMeta) {
                $existingIds = json_decode($galleryMeta->value, true);
                if ($existingIds) {
                    foreach (File::whereIn('id', $existingIds)->get() as $file) {
                        $existingFiles[$file->id] = $file->path . '/' . $file->filename;
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
                $p = array_search($path['dirname'] . '/' . $path['basename'], $existingFiles);
                if ($p) {
                    $newFiles[] = $p;
                    unset($existingFiles[$p]);
                }
            }
        }

        $post = Post::updateOrCreate(
            ['id' => $request->post('id')],
            [
                'post_title' => $validated['title'],
                //                'post_content' => $validated['description'],
                'post_type' => $postType,
                'author' => $user->id,
                'uri_slug' => $slug,
            ]
        );

        //Gallery (reuse field 1))
        PostMeta::updateOrCreate(
            ['posts_id' => $post->id, 'fields_id' => 1],
            [
                'posts_id' => $post->id,
                'fields_id' => 1,
                'value' => json_encode($newFiles),
            ]
        );

        $this->postMetaService->updatePostMeta($post, $validated);

        $emailService = new SendEmailToAdminService();
        $emailService->sendEmailToAdmins(env('SG_EDIT_PROJECT_ID'), [
            'installer_id' => $user->id,
            'installer_name' => $user->name,
            'project_title' => $post->post_title,
            'review_url' => route('home'),
        ], true);

        return redirect()->route('admin.installer-projects.index')->with('message', [
            'type' => 'success',
            'msg' => 'Installer project edited successfully'
        ]);
    }

    /**
     * Update the status of the Project between draft/published
     *
     * @param $projectId
     * @return void
     */
    public function updatePublishedStatus($projectId)
    {
        $post = Post::find($projectId);

        if ($post->project_published_status === 0) {
            $post->project_published_status = 1;
        } else {
            $post->project_published_status = 0;
        }

        $post->save();
    }

    /**
     * Permanently delete the Installer Project as well as associated data
     *
     * @param $projectId
     * @return RedirectResponse
     */
    public function permDeleteProject($projectId)
    {
        $project = Post::whereId($projectId)
            ->firstOrFail();

        if ($project) {
            $project->coverImage()->forceDelete();
            $project->forceDelete();
            $type = 'success';
            $msg = "Project successfully deleted";
        } else {
            $type = 'error';
            $msg = "Project not deleted";
        }

        return redirect()->route('admin.installer-projects.index')->with('message', [
            'type' => $type,
            'msg' => $msg
        ]);
    }
}
