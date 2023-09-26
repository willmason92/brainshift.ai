<?php

namespace App\Http\Requests\Admin\FarmerManagement;

use App\Rules\Location;
use App\Rules\SectionRoleCheck;
use App\Rules\UserExists;
use Illuminate\Foundation\Http\FormRequest;

class UpdateFarmerRequest extends FormRequest
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
                new SectionRoleCheck('Farmer', $id)
            ],
            //            'name' => 'required|string|max:255',
            'farmName' => 'required|string|max:255',
            'sectors' => 'required|array',
            'sectors.*' => 'required|exists:sectors,id',
            'location' => ['required', new Location()],
        ];
    }

    protected function prepareForValidation()
    {
        $this->merge(['id' => $this->route('id')]);
    }
}
