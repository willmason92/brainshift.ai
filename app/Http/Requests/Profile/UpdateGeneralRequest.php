<?php

namespace App\Http\Requests\Profile;

use App\Rules\EncryptedUnique;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class UpdateGeneralRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules()
    {
        $user = Auth::user();

        return [
            'generalName' => 'required',
            'generalEmail' => [
                'required',
                Rule::unique('users', 'email')->ignore(Auth::id()),
                new EncryptedUnique('App\Models\User', 'email')
            ],
            'generalPhone' => 'required',

        ];
    }

    /**
     * Configure the validator instance.
     *
     * @param  \Illuminate\Validation\Validator  $validator
     * @return void
     */
    public function withValidator($validator)
    {
        $validator->after(function ($validator) {
            if (! $this->checkForUserChanges()) {
                $validator->errors()->add('general', 'There are no changes to update.');
            }
        });
    }

    private function checkForUserChanges()
    {
        $user = Auth::user();

        return ! empty(array_diff_assoc(
            $this->validated(), [
                'generalName' => $user->name,
                'generalEmail' => $user->email,
                'generalPhone' => $user->phone,
            ]
        ));
    }

    /**
     * @return array
     */
    public function validationData(): array
    {
        if (method_exists($this->route(), 'parameters')) {
            $this->request->add($this->route()->parameters());
            $this->query->add($this->route()->parameters());

            return array_merge($this->route()->parameters(), $this->all());
        }

        return $this->all();
    }
}
