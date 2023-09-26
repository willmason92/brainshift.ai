<?php

namespace App\Http\Requests\Admin\MaterialManagement;

use App\Rules\StorageExists;
use Illuminate\Foundation\Http\FormRequest;

class UpdateOrCreateMaterialRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'name' => 'required|string',
            'productLine' => 'required|integer',
            'image' => ['required', 'string', new StorageExists],
            'colour' => 'required|exists:colours,id',
            'url' => 'required|url',
        ];
    }
}
