<?php

namespace App\Http\Requests\InstallerRequests;

use App\Models\Request as InstallerRequest;
use Illuminate\Foundation\Http\FormRequest;

class UpdateStatusRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules()
    {
//        dd($this->status);
        if ($this->status == InstallerRequest::CONTACTED || $this->status == InstallerRequest::NOT_CONVERTED) {
            return [
                'status' => 'required|in:1,4',
            ];
        } elseif ($this->status == InstallerRequest::CONVERTED || $this->status == InstallerRequest::NOT_CONVERTED) {
            return [
                'status' => 'required|in:2,4',
            ];
        } elseif ($this->status == InstallerRequest::PROJECT_FINISHED || $this->status == InstallerRequest::PROJECT_FAILED) {
            return [
                'status' => 'required|in:3,5',
            ];
        } elseif ($this->status == InstallerRequest::PROJECT_FAILED) {
            return [
                'status' => 'required|in:5',
            ];
        }

        return [];
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
