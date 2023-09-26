<?php

namespace App\Http\Requests\ShedSolution;

use App\Rules\ValidSizeByUnit;
use Illuminate\Foundation\Http\FormRequest;

class SaveShedSolutionRequest extends FormRequest
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

            'formPath' => 'required|boolean', //New or Refurb

            'sector' => 'required|exists:sectors,id', //sector both paths

            //Only New 0
            'goals' => 'required_if:formPath,0|array',
            'goals.*' => 'required_if:formPath,0|exists:goals,id',

            //Only Refurb 1
            'unknownAge' => 'required_if:formPath,1|boolean',
            'buildingAge' => 'required_if:unknownAge,0|integer|min:0|max:500',
            'responsibility' => 'required_if:formPath,1',
            'typeOfBuilding' => 'required_if:formPath,1|digits_between:0,2',
            'reasons' => 'required_if:formPath,1|array',
            'reasons.*' => 'required_if:formPath,1|exists:reasons,id',

            'unit' => ['required', 'digits_between:0,1'],
            'length' => ['required', new ValidSizeByUnit('length')],
            'width' => ['required', new ValidSizeByUnit('width')],

            //            'length' => 'required_if:unit,0|numeric|between:20,100',
            //            'width' => 'required_if:unit,0|numeric|between:17.8,60',
            //            'length' => 'required_if:unit,1|numeric|between:65.62,328.08',
            //            'width' => 'required_if:unit,1|numeric|between:58.40,196.65',

            //            'project' => ['nullable', 'exists:shed_solutions,id'],
            //            'project_type' => ['required_if:project,null', 'digits_between:0,1'],
            //            'sector' => ['nullable', 'required_if:project,null', 'exists:sectors,id'],
            //            'message' => 'required|string|max:1000',
            //            'phone' => 'required|numeric', //Todo Inti Phone validation
            //            'email' => 'required|email',
            //            'permission' => 'required|boolean|accepted',
        ];
    }
}
