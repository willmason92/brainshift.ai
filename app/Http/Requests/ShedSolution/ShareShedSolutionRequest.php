<?php

namespace App\Http\Requests\ShedSolution;

use App\Rules\SolutionBelongsTo;
use Illuminate\Foundation\Http\FormRequest;

class ShareShedSolutionRequest extends FormRequest
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
            'projectId' => ['exists:shed_solutions,id', new SolutionBelongsTo()],
            'email' => 'required|email',
        ];
    }
}
