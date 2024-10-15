<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;

class AlphaRul implements Rule
{
    /**
     * Create a new rule instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
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
        //nullをokにする
        if(is_null($value)){
            return true;
        }else{
            //値があればバリデート
            return preg_match('/^[!-~]+$/', $value);
        }
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return '※半角';
    }
}
