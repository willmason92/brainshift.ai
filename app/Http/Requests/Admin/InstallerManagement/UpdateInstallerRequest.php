<?php

namespace App\Http\Requests\Admin\InstallerManagement;

use App\Rules\Location;
use App\Rules\SectionRoleCheck;
use App\Rules\StorageExists;
use App\Rules\UserExists;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class UpdateInstallerRequest extends FormRequest
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

        return [
            'id' => [
                'required',
                new UserExists(),
                new SectionRoleCheck('Installer', $id)
            ],
            'company_name' => 'required|string|min:1|max:255',
            'company_logo' => ['required', new StorageExists],
            'location' => ['required', new Location()],
            'nationwide' => 'required|boolean',
            'radius' => 'exclude_if:nationwide,true|required|digits_between:1,100',
            'radiusType' => 'exclude_if:nationwide,true|required|digits_between:0,1',
            'sectors' => 'required|array',
            'sectors.*' => 'required|exists:sectors,id',
            'specialities' => 'required|array',
            'specialities.*' => 'required|exists:specialities,id',
            'description' => 'required|string|min:1|max:4000',
        ];
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
