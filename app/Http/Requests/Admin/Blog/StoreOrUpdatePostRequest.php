<?php

namespace App\Http\Requests\Admin\Blog;

use App\Rules\Location;
use App\Rules\StorageExists;
use Illuminate\Foundation\Http\FormRequest;
use App\Models\Post;

class StoreOrUpdatePostRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        $post_type = $this->request->get('post_type');

        $common_rules = [
            'title' => 'required|string|max:255',
            'mainContent' => 'required|string|max:16000',
            'post_type' => 'required|numeric',
            'tags' => 'array',
            'coverImage' => ['required', 'string', new StorageExists],
            'sector' => 'required|exists:sectors,id',
        ];

        if ($post_type == 2) { // Article
            $rules = array_merge($common_rules, []);
        } elseif ($post_type == 3) { // Case Study
            $rules = array_merge($common_rules, [
                'gallery' => 'required|array',
                'gallery.*' => ['required', 'string', new StorageExists],
                'projectCompletion' => 'nullable|date',
                'projectType' => 'required|digits_between:0,1',
                'location' => ['required', new Location],
                'materials' => 'required|array',
                'materials.*' => 'exists:materials,id',]);
        } elseif ($post_type == 4) { // Technical Document
            $rules = array_merge($common_rules, [
                'file' => ['required', 'string', new StorageExists],
            ]);
        } elseif ($post_type == 5) { // Training Video
            $rules = [
                'title' => 'required|string|max:255',
                'mainContent' => 'required|string|max:16000',
                'post_type' => 'required|numeric',
                'tags' => 'array',
                'sector' => 'required|exists:sectors,id',
                'videoUrl' => ['required', 'url'],
                'videoCustomThumbnail' => ['boolean', 'nullable'],
                'videoThumbnail' => $this->request->get('videoCustomThumbnail') ? ['required', 'string', new StorageExists] : [],
            ];
        } elseif ($post_type == 6) { // Social Media Accounts
            $rules = array_merge($common_rules, [
                'social_media_link' => ['required', 'url'],
            ]);
        } elseif ($post_type == 7) { // Social Media Accounts
            $rules = array_merge($common_rules, [
                'social_media_link' => ['required', 'url'],
            ]);
        }

        return $rules;
    }
}
