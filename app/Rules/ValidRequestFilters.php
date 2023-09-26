<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\InvokableRule;

class ValidRequestFilters implements InvokableRule
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
        if (! in_array($attribute, [
            'contact_phone',
            'contact_email',
        ])) {
            $fail('Cannot filter by this column');
        }
    }
}
