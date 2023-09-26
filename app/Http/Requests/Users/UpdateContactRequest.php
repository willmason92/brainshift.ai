<?php

namespace App\Http\Requests\Users;

use App\Rules\InternationalPhone;
use Illuminate\Foundation\Http\FormRequest;

class UpdateContactRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules()
    {
        return [
            'canContact' => 'required|boolean',
        ];
    }

}
