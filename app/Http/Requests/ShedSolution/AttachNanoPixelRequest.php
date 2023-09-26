<?php

namespace App\Http\Requests\ShedSolution;

use App\Models\File;
use App\Rules\SolutionBelongsTo;
use App\Rules\StorageExists;
use Illuminate\Support\Facades\Auth;

class AttachNanoPixelRequest extends SaveShedSolutionRequest
{
    /**
     * Prepare the data for validation, here we save the base64 image to the
     * uploads directory for validation and processing
     *
     * @return void
     */
    protected function prepareForValidation()
    {
        $b64image = $this->get('np_image');
        if (! empty($b64image)) {
            $file = File::uploadBase64($b64image);
            if (! empty($file)) {
                $this->merge([
                    'np_image' => $file,
                ]);
            }
        }
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules()
    {
        $user = Auth::user();

        return array_merge(
            parent::rules(),
            [
                'solutionId' => ['nullable', 'exists:shed_solutions,id', new SolutionBelongsTo()],
                'np_name' => 'required|string|min:1|max:50',
                'np_image' => ['required', 'string', new StorageExists],
                'np_json_config' => 'required|json',
            ]
        );
    }
}
