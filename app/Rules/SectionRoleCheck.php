<?php

namespace App\Rules;

use App\Models\User;
use Illuminate\Contracts\Validation\Rule;
use Illuminate\Support\Facades\Auth;

class SectionRoleCheck implements Rule
{
    protected $section;
    protected $id;

    /**
     * Create a new rule instance.
     *
     * @return void
     */
    public function __construct($section, $id)
    {
        $this->section = $section;
        $this->id = $id;
    }

    /**
     * Determine if the validation rule passes.
     *
     * @param  string  $attribute
     * @param  mixed  $value
     * @return bool
     */
    public function passes($attribute, $value)
    {
        $user = User::find($this->id);
        if ($user && $user->hasRole($this->section)) {
            return true;
        }
        return false;
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return 'You are not authorized to perform this action.';
    }
}
