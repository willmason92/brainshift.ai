<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\InvokableRule;

class StorageExists implements InvokableRule
{
    /**
     * Run the validation rule.
     *
     * @param  string  $attribute
     * @param  mixed  $value
     * @param  \Closure  $fail
     * @return void
     */
    public function __invoke($attribute, $value, $fail)
    {
        if (! file_exists(public_path()."/storage/$value")) {
            $fail('The :attribute file is not uploaded.');
        }
    }
}
