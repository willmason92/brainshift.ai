<?php

namespace App\Http\Requests\Admin\ManageExperts;

use App\Models\Expert;
use App\Rules\Location;
use App\Rules\StorageExists;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ExpertRequest extends FormRequest
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

    public function rules()
    {
        $id = $this->route('id');

        $rules = [
            'company_name' => 'required|string|min:1|max:255',
            'company_logo' => ['required', new StorageExists],
            'location' => ['required', new Location()],
            'nationwide' => 'boolean',
            'radius' => 'exclude_if:nationwide,true|required',
            'radiusType' => 'exclude_if:nationwide,true|required|digits_between:0,1',
            'sectors' => 'required|array',
            'sectors.*' => 'required|exists:sectors,id',
            'description' => 'required|string|min:1|max:4000',
            'expert_url' => 'string',
            'expert_type' => 'required|exists:expert_type,id',
        ];

        if (!is_null($id)) {
            $rules['id'] = [
                'required',
                'exists:experts,id,user_id,NULL',
            ];
        }

        return $rules;
    }

    protected function prepareForValidation()
    {
        $this->merge(['id' => $this->route('id')]);
    }
}
