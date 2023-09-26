<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Blog\StoreOrUpdatePostRequest;
use App\Models\File;
use App\Models\Post;
use App\Models\Material;
use App\Models\PostMeta;
use App\Models\Sector;
use App\Models\Tag;
use App\Scopes\PostTypeScope;
use App\Services\BlogManagementService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;
use Inertia\Response;

class BlogManagementController extends Controller
{
    /**
     * Redirect back to login if user is unauthenticated
     *
     * BlogManagementController constructor.
     */
    public function __construct(BlogManagementService $blogManagementService)
    {
        $this->middleware('auth');
        $this->blogManagementService = $blogManagementService;
    }

    /**
     * Page to list all blog details
     *
     * @return Response
     */
    public function index()
    {
        $posts = Post::with('author')->get();

        // build object to store column info
        $meta = [
            [
                'id' => 0,
                'column' => 'id',
                'name' => 'ID',
                'type' => 'id',
                'filterable' => false,
                'sortable' => false,
                'searchable' => false,
            ],
            [
                'id' => 1,
                'column' => 'post_title',
                'name' => 'Title',
                'type' => 'link',
                'filterable' => false,
                'sortable' => false,
                'searchable' => false,
            ],
            [
                'id' => 2,
                'column' => 'post_type.name',
                'name' => 'Type',
                'type' => 'string',
                'filterable' => true,
                'filters' => ['Articles', 'Case Studies', 'Technical Documents'],
                'sortable' => true,
                'searchable' => false,
            ],
            [
                'id' => 3,
                'column' => 'created_at',
                'name' => 'Date published',
                'type' => 'date',
                'filterable' => false,
                'sortable' => true,
                'searchable' => false,
            ],
            [
                'id' => 4,
                'column' => 'author.name',
                'name' => 'Author',
                'filterable' => true,
                'filters' => ['Dan Walker'],
                'sortable' => true,
                'searchable' => false,
                'type' => 'tag',
                'tagColors' => [
                    'Dan Walker' => 'blue',
                ],
            ],
        ];

        return Inertia::render('Admin/BlogManagement/Index', [
            'data' => $posts,
            'meta' => $meta,
        ]);
    }

    /**
     * Page to display/edit the specific post
     *
     * @param $postId
     * @return Response
     */
    public function detail($postId = null)
    {
        $post = $postId ? Post::with('author', 'postMeta')->find($postId) : null;

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

        return Inertia::render('Admin/BlogManagement/Detail', [
            'post' => $post,
            'materials' => Material::all(),
            'sectors' => Sector::all(),
            'tags' => Tag::all(),
            'fileUploads' => $flash['file-uploads'],
            'fileRemoved' => $flash['file-removed'],
        ]);
    }

    /**
     * Page to display/edit the specific post
     *
     * @param $postId
     * @return Response
     */
    public function add()
    {
        return Inertia::render('Admin/BlogManagement/Detail', [
            'new' => true,
            'materials' => Material::all(),
            'sectors' => Sector::all(),
            'tags' => Tag::all(),
        ]);
    }

    public function store(StoreOrUpdatePostRequest $request, $postId = null)
    {
        $validated = $request->validated();

        if ($validated['post_type'] === 2) { // Article
            $post = $this->blogManagementService->createOrUpdateArticle($validated, $postId);
        }

        if ($validated['post_type'] === 3) { // Case Study
            $post = $this->blogManagementService->createOrUpdateCaseStudy($validated, $postId);
        }

        if ($validated['post_type'] === 4) { // Technical Document
            $post = $this->blogManagementService->createOrUpdateTechnicalDocument($validated, $postId);
        }

        if ($validated['post_type'] === 5) { // Installation Video
            $post = $this->blogManagementService->createOrUpdateInstallationVideo($validated, $postId);
        }

        if ($validated['post_type'] === 6) { // Social Media Account
            $post = $this->blogManagementService->createOrUpdateSocialMediaAccount($validated, $postId);
        }

        return redirect()->route('admin.blog.index')->with('message', [
            'type' => 'success',
            'msg' => 'The <strong>post</strong> has been saved successfully',
        ]);
    }

    /**
     * Method to permanently delete the post and associated data
     *
     * @param $postId
     * @return bool
     */
    public function permDeletePost($postId)
    {
        $post = Post::find($postId);

        if ($post) {
            $post->coverImage()->forceDelete();
            $post->forceDelete();
            return true;
        } else {
            return false;
        }
    }
}
