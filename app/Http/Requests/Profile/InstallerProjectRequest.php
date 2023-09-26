<?php

namespace App\Http\Requests\Profile;

use App\Models\Post;
use App\Models\User;
use App\Rules\Location;
use App\Rules\StorageExists;
use Illuminate\Foundation\Http\FormRequest;

class InstallerProjectRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize(User $user, Post $post)
    {
        if ($this->user()->hasRole('SuperAdmin')) {
            return true;
        }

        $region = $post->region;

        if ($region !== null && property_exists($region, 'postcodeMap')) {
            $postcodesInRegion = $region->postcodeMap->pluck('outcode');

            $adminsInRegion = User::whereHas('location', function ($query) use ($postcodesInRegion) {
                $query->whereIn('postcode_map_id', function ($query) use ($postcodesInRegion) {
                    $query->select('id')
                        ->from('postcode_map')
                        ->whereIn('outcode', $postcodesInRegion);
                });
            })
                ->where('is_admin', true)
                ->pluck('id');

            return $adminsInRegion->contains($user->id);
        }

        if ($this->route()->getName() == 'my-account.installer-project.save' && $this->user()->hasRole('Installer')) {
            return true;
        }

        return false;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules()
    {
        $rules = [
            'id' => 'required_unless:_deleteKey,null|nullable|exists:posts,id',
            '_deleteKey' => 'nullable|string',
            'title' => 'required',
            'gallery' => 'required|array',
            'gallery.*' => ['required', 'string', new StorageExists],
            //"required|{$mimes}|max:{$this->_fileSize}"
            'sector' => 'required|exists:sectors,id',
            'projectType' => 'required|digits_between:0,1',
            'sizeLength' => 'required|digits_between:1,100',
            'sizeWidth' => 'required|digits_between:1,100',
            'sizeUnit' => 'required|digits_between:0,1',
            'dateComplete' => 'nullable|date',
            'materials' => 'required|array',
            'materials.*' => 'exists:materials,id',
        ];

//        Rules for frontend only
        if ($this->route()->getName() == 'my-account.installer-project.save') {
            $rules['description'] = 'required';
            $rules['includeLocation'] = 'required|boolean';
            $rules['location'] = ['exclude_if:includeLocation,false', new Location];
        } elseif ($this->route()->getName() == 'admin.edit-project') {
            // Rules for admin section only
            $rules['location'] = ['nullable', new Location];
        }

        return $rules;
    }

    /**
     * Get the error messages for the defined validation rules.
     *
     * @return array
     */
    public function messages()
    {
        return [
            'projectType' => 'No valid :attribute selected.',
            'gallery.*' => 'Image :position is invalid.',
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
}
