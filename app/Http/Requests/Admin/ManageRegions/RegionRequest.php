<?php

namespace App\Http\Requests\Admin\ManageRegions;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Route;

class RegionRequest extends FormRequest
{
    public function rules()
    {
        $rules = [
            'name' => 'required',
            'postcodes' => 'required|array',
        ];

        if (Route::currentRouteName() === 'admin.edit-region') {
            $rules['oldPostcodes'] = 'required|array';
        }

        return $rules;
    }

    public function authorize()
    {
        return true;
    }
}