<?php

namespace App\Services;

use App\Models\File;
use App\Models\Location;
use App\Models\Post;
use App\Models\PostMeta;
use App\Models\PostTag;
use App\Models\Tag;
use Illuminate\Support\Facades\Auth;

class BlogManagementService
{
    protected $postMetaService;

    public function __construct(PostMetaService $postMetaService)
    {
        $this->postMetaService = $postMetaService;
    }

    /**
     * Create or update an article.
     *
     * @param array $validated
     * @param int|null $postId
     * @return Post
     */
    public function createOrUpdateArticle(array $validated, ?int $postId = null): Post
    {
        $author = Auth::user()->id;
        $currentPost = $postId ? Post::find($postId) : null;

        $validated = $this->processCoverImage($validated, $currentPost);

        $slug = $this->generateSlug($validated['title']);

        $post = Post::updateOrCreate(
            [
                'id' => $postId,
            ],
            [
                'id' => $postId,
                'post_title' => $validated['title'],
                'post_content' => $validated['mainContent'],
                'post_type' => $validated['post_type'],
                'cover_image' => $validated['coverImage'],
                'uri_slug' => $slug,
                'author' => $author,
            ]
        );

        $this->updatePostTags($post->id, $validated['tags']);

        $this->updateSector($post->id, 11, $validated['sector'][0]);

        return $post;
    }

    public function createOrUpdateCaseStudy(array $validated, ?int $postId = null): Post
    {
        $author = Auth::user()->id;
        $currentPost = $postId ? Post::find($postId) : null;

        $validated = $this->processCoverImage($validated, $currentPost);

        $newFiles = $existingFiles = [];
        if ($postId) {
            $galleryMeta = PostMeta::where('fields_id', 1)->where('posts_id', $postId)->first();
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

        $slug = $this->generateSlug($validated['title']);

        $post = Post::updateOrCreate(
            [
                'id' => $postId,
            ],
            [
                'id' => $postId,
                'post_title' => $validated['title'],
                'post_content' => $validated['mainContent'],
                'post_type' => $validated['post_type'],
                'cover_image' => $currentPost ? $currentPost->cover_image : $validated['coverImage'],
                'uri_slug' => $slug,
                'author' => $currentPost ? $currentPost->author : $author,
            ]
        );

        //remove unused
        foreach (File::whereIn('id', array_keys($existingFiles))->get() as $file) {
            $path = "/storage/{$file->path}/{$file->filename}";
            if (file_exists(public_path() . $path)) {
                unlink(public_path() . $path);
            }
            $file->delete();
        }
        $this->updatePostTags($post->id, $validated['tags']);
        $this->updateSector($post->id, 11, $validated['sector'][0]);
        $this->postMetaService->updateCaseStudyPostMeta($post, $validated);

        return $post;
    }

    public function createOrUpdateTechnicalDocument(array $validated, ?int $postId = null): Post
    {
        $author = Auth::user()->id;
        $currentPost = $postId ? Post::find($postId) : null;

        $validated = $this->processCoverImage($validated, $currentPost);
        $slug = $this->generateSlug($validated['title']);

        $post = Post::updateOrCreate(
            [
                'id' => $postId,
            ],
            [
                'id' => $postId,
                'post_title' => $validated['title'],
                'post_content' => $validated['mainContent'],
                'post_type' => $validated['post_type'],
                'cover_image' => $validated['coverImage'],
                'uri_slug' => $slug,
                'author' => $currentPost ? $currentPost->author : $author,
            ]
        );

        // File
        PostMeta::updateOrCreate(
            ['posts_id' => $post->id, 'fields_id' => 3], [
                'posts_id' => $post->id,
                'fields_id' => 3,
                'value' => $validated['file'],
            ]
        );

        $this->updatePostTags($post->id, $validated['tags']);
        $this->updateSector($post->id, 11, $validated['sector'][0]);

        return $post;
    }
    public function createOrUpdateInstallationVideo(array $validated, ?int $postId = null): Post
    {
        $author = Auth::user()->id;
        $currentPost = $postId ? Post::find($postId) : null;

        $slug = $this->generateSlug($validated['title']);

        $post = Post::updateOrCreate(
            [
                'id' => $postId,
            ],
            [
                'id' => $postId,
                'post_title' => $validated['title'],
                'post_content' => $validated['mainContent'],
                'post_type' => $validated['post_type'],
                'uri_slug' => $slug,
                'author' => $currentPost ? $currentPost->author : $author,
            ]
        );

        // Installation Video
        PostMeta::updateOrCreate(
            ['posts_id' => $post->id, 'fields_id' => 7], [
                'posts_id' => $post->id,
                'fields_id' => 7,
                'value' => $validated['videoUrl'],
            ]
        );
        // Installation Video Custom Thumbnail
        if ($validated['videoCustomThumbnail'] == true) {
            $validated = $this->processVideoThumbnail($validated, $currentPost);
            PostMeta::updateOrCreate(
                ['posts_id' => $post->id, 'fields_id' => 8], [
                    'posts_id' => $post->id,
                    'fields_id' => 8,
                    'value' => $validated['videoThumbnail'],
                ]
            );

        }

        $this->updatePostTags($post->id, $validated['tags']);
        $this->updateSector($post->id, 11, $validated['sector'][0]);

        return $post;
    }

    public function createOrUpdateSocialMediaAccount(array $validated, ?int $postId = null): Post
    {
        $author = Auth::user()->id;
        $currentPost = $postId ? Post::find($postId) : null;

        $validated = $this->processCoverImage($validated, $currentPost);
        $slug = $this->generateSlug($validated['title']);

        $post = Post::updateOrCreate(
            [
                'id' => $postId,
            ],
            [
                'id' => $postId,
                'post_title' => $validated['title'],
                'post_content' => $validated['mainContent'],
                'post_type' => $validated['post_type'],
                'cover_image' => $validated['coverImage'],
                'uri_slug' => $slug,
                'author' => $currentPost ? $currentPost->author : $author,
            ]
        );

        // Video Url
        PostMeta::updateOrCreate(
            ['posts_id' => $post->id, 'fields_id' => 9], [
                'posts_id' => $post->id,
                'fields_id' => 9,
                'value' => $validated['social_media_link'],
            ]
        );

        $this->updatePostTags($post->id, $validated['tags']);
        $this->updateSector($post->id, 11, $validated['sector'][0]);

        return $post;
    }

    public function createOrUpdateTrainingVideo(array $validated, ?int $postId = null): Post
    {
        $author = Auth::user()->id;
        $currentPost = $postId ? Post::find($postId) : null;

        $validated = $this->processCoverImage($validated, $currentPost);

        $slug = $this->generateSlug($validated['title']);

        $post = Post::updateOrCreate(
            [
                'id' => $postId,
            ],
            [
                'id' => $postId,
                'post_title' => $validated['title'],
                'post_content' => $validated['mainContent'],
                'post_type' => $validated['post_type'],
                'cover_image' => $validated['coverImage'],
                'uri_slug' => $slug,
                'author' => $currentPost ? $currentPost->author : $author,
            ]
        );

        $this->updatePostTags($post->id, $validated['tags']);

        $this->updateSector($post->id, 11, $validated['sector'][0]);

        return $post;
    }

    public function processCoverImage(array $validated, $currentPost)
    {
        $path = pathinfo($validated['coverImage']);
        if ($path['dirname'] === 'upload') {
            //move uploaded
            $file = File::moveUploadedFile($path['basename'], File::FILE_IMAGE, 'farmers-library/cover');
            $file->save();
            $fId = null;
            //reassign new logo
            $validated['coverImage'] = $file->id;
            if (!empty($fId)) {
                File::where('id', $fId)->first()->delete();
            }
        } elseif ($path['dirname'] === 'farmers-library/cover') {
            $validated['coverImage'] = $currentPost->cover_image;
        }

        return $validated;
    }
    public function processVideoThumbnail(array $validated, $currentPost)
    {
        $path = pathinfo($validated['videoThumbnail']);
        if ($path['dirname'] === 'upload') {
            //move uploaded
            $file = File::moveUploadedFile($path['basename'], File::FILE_IMAGE, 'farmers-library/video-posters');
            $file->save();
            $fId = null;
            //reassign new logo
            $validated['videoThumbnail'] = $file->id;
            if (!empty($fId)) {
                File::where('id', $fId)->first()->delete();
            }
        } elseif ($path['dirname'] === 'farmers-library/video-posters') {
            $thumbnailId = Post::with('postMeta')->find($currentPost['id'])['postMeta'][1]['value'];
            $validated['videoThumbnail'] = $thumbnailId;
        }

        return $validated;
    }

    public function generateSlug(string $title): string
    {
        return str_replace(' ', '-', preg_replace('/[^a-z0-9 ]/', '', strtolower($title)));
    }

    function updateSector($postId, $fieldId, $value) {
        PostMeta::updateOrCreate(
            ['posts_id' => $postId, 'fields_id' => $fieldId],
            [
                'posts_id' => $postId,
                'fields_id' => $fieldId,
                'value' => $value,
            ]
        );
    }

    function updatePostTags($postId, $tagIds) {
        PostTag::where('post_id', $postId)->delete();
        foreach ($tagIds as $tagId) {
            PostTag::create([
                'post_id' => $postId,
                'blog_tag_id' => $tagId
            ]);
        }
    }
}
