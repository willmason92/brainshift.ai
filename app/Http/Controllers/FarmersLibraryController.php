<?php

namespace App\Http\Controllers;

use App\Models\Post;
use App\Models\PostType;
use App\Models\Tag;
use Facades\App\Models\Settings;
use Illuminate\Http\Request;
use Inertia\Inertia;

class FarmersLibraryController extends Controller
{
    /**
     * Posts index page
     *
     * @param  Request  $request
     * @return \Inertia\Response
     */
    public function index(Request $request)
    {
        return Inertia::render('FarmersLibrary/Index', [
            'posttypes' => PostType::get(),
            'activeType' => $request->type,
        ]);
    }

    public function list(Request $request)
    {
        //get the posts
        $posts = null;
        if (! $request->type) {
            $groupedPosts = [];
            $curType = null;
            $posts = Post::with('postMeta')->where(function ($q) {
                $q->selectRaw('COUNT(*)')
                    ->from('posts AS p2')
                    ->whereColumn('p2.post_type', 'posts.post_type')
                    ->whereColumn('p2.updated_at', '>=', 'posts.updated_at');
            }, '<=', 2)
                ->where('posts.post_type', '>', '1')
                ->orderBy('posts.post_type')
                ->orderByDesc('posts.updated_at');
        } else {
            $posts = Post::with('postMeta')->filter($request->type);
        }

        //join tags
        $p = clone $posts;
        $availableTags = Tag::select('blog_tags.*')
            ->join('post_tags', 'blog_tags.id', '=', 'post_tags.blog_tag_id')
            ->whereIn('post_tags.post_id', $p->pluck('id'))
            ->groupBy('blog_tags.id')
            ->get();

        //get the filters
        $filters = ['tags' => $availableTags];
        $validatedData = $request->validate([
            'tags.*' => ['exists:blog_tags,id'],
        ]);
        if (! empty($validatedData['tags'])) {
            $posts = $posts->select('posts.*')->leftJoin('post_tags', function ($join) {
                $join->on('posts.id', '=', 'post_tags.post_id');
            })->whereIn('post_tags.blog_tag_id', $validatedData['tags'])->distinct();
        }

        //iterate and group the posts if applicable
        if (! $request->type) {
            foreach ($posts->get() as $post) {
                if ($post->postType !== $curType) {
                    $curType = $post->postType;
                }
                $groupedPosts[$curType['id']]['category'] = $curType;
                $groupedPosts[$curType['id']]['posts'][] = $post;
            }
        }

        return Inertia::render('FarmersLibrary/List', [
            'posttypes' => PostType::get(),
            'activeType' => $request->type,
            'posts' => ($request->type ? $posts->get() : null),
            'typePosts' => ($request->type ? null : $groupedPosts),
            'shopUrl' => Settings::get('shop.url'),
            'filters' => $filters,
        ]);
    }

    public function viewAll($postTypeID)
    {
        if ($postTypeID === 'All') {
            $posts = Post::get();
        } else {
            $posts = Post::where('post_type', $postTypeID)->get();
        }

        return Inertia::render('FarmersLibrary/ViewAll', [
            'posts' => $posts,
        ]);
    }

    public function view($id)
    {
        $post = Post::with(['PostMeta', 'coverImage'])->where('id', $id)->firstOrFail();

        $related = Post::where([
            ['post_type', $post->post_type],
            ['id', '!=', $post->id],
        ])
            ->orderByDesc('posts.updated_at')
            ->limit(2)
            ->get();

        return Inertia::render('FarmersLibrary/View', [
            'post' => $post,
            'relatedPosts' => $related,
        ]);
    }
}
