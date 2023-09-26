<?php

namespace App\Http\Requests\Expert;

use App\Rules\Location;
use Illuminate\Foundation\Http\FormRequest;

class FilterExpertRequest extends FormRequest
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
            'sector' => 'nullable|array',
            'sector.*' => 'nullable|exists:sectors,id',
            'speciality' => 'nullable|array',
            'speciality.*' => 'nullable|exists:specialities,id',
            'location' => ['nullable', new Location()],
        ];
    }
}
