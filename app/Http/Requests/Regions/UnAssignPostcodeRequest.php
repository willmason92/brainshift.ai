<?php

namespace App\Http\Requests\Regions;

use Illuminate\Foundation\Http\FormRequest;

class UnAssignPostcodeRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return false;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules()
    {
        return [
            'outcode' => 'required|exists:postcode_map,outcode',
        ];
    }
}
