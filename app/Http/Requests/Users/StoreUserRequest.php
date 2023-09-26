<?php

namespace App\Http\Requests\Users;

use App\Rules\InternationalPhone;
use Illuminate\Foundation\Http\FormRequest;

class StoreUserRequest extends FormRequest
{
    public function __construct(array $payload = null)
    {
        parent::__construct();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules()
    {
        return [
            'name' => 'required',
            'token' => 'sometimes',
            'email' => 'required|email',
            'phone' => [
                'sometimes',
                new InternationalPhone(),
            ],
            'contact_by_email' => 'required|boolean',
        ];
    }
}
