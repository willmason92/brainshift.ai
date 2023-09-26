<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\InvokableRule;
use Illuminate\Support\Facades\Auth;

class EncryptedUnique implements InvokableRule
{
    private $_resourceType = '';

    private $_columnName = '';

    /**
     * Create a new rule instance.
     *
     * @param $param
     */
    public function __construct($resourceType, $columnName)
    {
        $this->_resourceType = $resourceType;
        $this->_columnName = $columnName;
    }

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
        $model = app($this->_resourceType);
        $user = Auth::user();
        if ($user && $model && get_class($model) === ($this->_resourceType)) {
            if ($model::whereEncrypted($this->_columnName, $value)->where('id', '!=', $user->id)->first() === null) {
                return;
            }
        }
        $fail('The :attribute is not available.');
    }
}
