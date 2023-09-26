<?php

namespace App\Http\Requests\Expert;

use App\Rules\InternationalPhone;
use Illuminate\Foundation\Http\FormRequest;

class ContactRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules()
    {
        return [
            'project' => ['nullable', 'exists:shed_solutions,id'],
            'project_type' => ['required_if:project,null', 'digits_between:0,1'],
            'sector' => ['nullable', 'required_if:project,null', 'exists:sectors,id'],
            'message' => 'required|string|max:1000',
            'phone' => [
                'nullable',
                'numeric',
                new InternationalPhone(),
            ],
            'email' => 'required|email',
            'permission' => 'required|boolean',
        ];
    }
}
