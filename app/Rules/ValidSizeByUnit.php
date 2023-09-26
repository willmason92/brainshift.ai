<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\DataAwareRule;
use Illuminate\Contracts\Validation\InvokableRule;
use Illuminate\Contracts\Validation\ValidatorAwareRule;
use Illuminate\Support\Facades\Validator;

class ValidSizeByUnit implements DataAwareRule, ValidatorAwareRule, InvokableRule
{
    /**
     * All of the data under validation.
     *
     * @var array
     */
    protected $data = [];

    /**
     * The validator performing the validation.
     *
     * @var \Illuminate\Validation\Validator
     */
    protected $validator;

    protected $dimention = '';

    /**
     * Set the current validator.
     *
     * @param  \Illuminate\Contracts\Validation\Validator  $validator
     * @return $this
     */
    public function setValidator($validator)
    {
        $this->validator = $validator;

        return $this;
    }

    /**
     * Set the data under validation.
     *
     * @param  array  $data
     * @return $this
     */
    public function setData($data)
    {
        $this->data = $data;

        return $this;
    }

    public function __construct(string $dimention)
    {
        $this->dimention = $dimention;
    }

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
        try {
            if ($this->dimention === 'length') {
                $validator = Validator::make(
                    $this->data,
                    [
                        'length' => [
                            'required',
                            'numeric',
                            'between:'.($this->data['unit'] ? '65.62,328.08' : '20,100'),
                        ],
                    ]
                );
            } elseif ($this->dimention === 'width') {
                $validator = Validator::make(
                    $this->data,
                    [
                        'width' => [
                            'required',
                            'numeric',
                            'between:'.($this->data['unit'] ? '58.40,196.65' : '17.8,60'),
                        ],
                    ]
                );
            } else {
                $fail('Not a recognised size field.');
            }

            if ($validator->stopOnFirstFailure()->fails()) {
                $fail($validator->errors()->first());
            }
        } catch (\Exception $x) {
            $fail($x->getMessage());
        }
    }
}
