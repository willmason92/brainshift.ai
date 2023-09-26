<?php

namespace App\Rules;

use App\Models\ShedSolution;
use Illuminate\Contracts\Validation\InvokableRule;
use Illuminate\Support\Facades\Auth;

class SolutionBelongsTo implements InvokableRule
{
    /**
     * Run the validation rule.
     *
     * @param  string  $attribute
     * @param  mixed  $value
     * @param  \Closure(string): \Illuminate\Translation\PotentiallyTranslatedString  $fail
     * @return void
     */
    public function __invoke($attribute, $value, $fail)
    {
        $user = Auth::user();
        if (ShedSolution::where('id', $value)->where('user_id', $user->id)->first()) {
            return;
        }
        $fail('The Shed Solution is not available.');
    }
}
