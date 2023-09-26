<?php

namespace App\Http\Requests\Admin\Settings;

use Illuminate\Foundation\Http\FormRequest;

class UpdateSettingsRequest extends FormRequest
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
        return [
            'contact.company_name' => ['required', 'max:255'],
            'contact.company_group' => ['required', 'max:255'],
            'contact.address1' => ['required', 'max:255'],
            'contact.city' => ['required', 'max:255'],
            'contact.country' => ['required', 'max:255'],
            'contact.postcode' => ['required', 'max:12'],
            'contact.vat_number' => ['required', 'max:25'],
            'contact.phone' => ['required'],
            'contact.footer_text' => ['required'],
            'contact.contact_form_url' => ['required', 'url'],
            'shop.materials_cta_link' => ['required', 'url'],
        ];
    }
}
