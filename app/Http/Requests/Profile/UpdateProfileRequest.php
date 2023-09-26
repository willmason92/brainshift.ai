<?php

namespace App\Http\Requests\Profile;

use App\Rules\Location;
use App\Rules\StorageExists;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class UpdateProfileRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules()
    {
        $user = Auth::user();
        if ($user->hasRole('Installer')) {
            return [
                'company_name' => 'required|string|min:1|max:255',
                'company_logo' => ['required', new StorageExists],
                'location' => ['required', new Location()],
                'nationwide' => 'required|boolean',
                'radius' => 'exclude_if:nationwide,true|required|digits_between:1,100',
                'radiusType' => 'exclude_if:nationwide,true|required|digits_between:0,1',
                'sector' => 'required|array',
                'sector.*' => 'required|exists:sectors,id',
                'specialities' => 'required|array',
                'specialities.*' => 'required|exists:specialities,id',
                'description' => 'required|string|min:1|max:4000',
            ];
        } else {
            return [
                'farmName' => 'required|string|min:1|max:255',
                'farmLocation' => 'required',
                'farmSector' => 'required|exists:sectors,id',
            ];
        }
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
