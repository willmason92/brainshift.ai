<?php

namespace App\Http\Requests\Admin;

use App\Rules\ValidRequestFilters;
use App\Rules\ValidRequestSortColumns;
use Illuminate\Foundation\Http\FormRequest;

class RequestManagementRequest extends FormRequest
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
            'filters' => ['nullable', 'array', new ValidRequestFilters()],
            'sort.column' => ['nullable', 'string', new ValidRequestSortColumns],
            'paging' => ['nullable', 'numeric'],
        ];
    }
}
