<?php

namespace App\Http\Requests\Admin\StaticPages;

use Illuminate\Foundation\Http\FormRequest;
use App\Models\Post;

class UpdateStaticPage extends FormRequest
{
    public function rules()
    {
        return [
            'value' => 'required','max:200000',
        ];
    }

    public function authorize()
    {
        return true;
    }
}
