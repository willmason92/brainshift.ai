<?php

namespace App\Http\Requests\Admin\AdminManagement;

use App\Rules\Location;
use App\Rules\SectionRoleCheck;
use App\Rules\StorageExists;
use App\Rules\UserExists;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class UpdateAdminRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return $this->user()->hasRole('SuperAdmin');
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules()
    {
        $id = $this->id;

        // Rules for add new Admin
        $rules = [
            'name' => ['required', 'string', 'max:255'],
            'region' => ['required', 'integer'],
            'email' => ['required', 'email'],
        ];

        // Rules included for Editing
        if (!is_null($id)) {
            $rules['id'] = [
                'required',
                Rule::exists('users')->where(function ($query) use ($id) {
                    $query->where('id', $id);
                }),
                new SectionRoleCheck('Admin', $id),
            ];
        }

        return $rules;
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

    protected function prepareForValidation()
    {
        $this->merge(['id' => $this->route('id')]);
    }
}
