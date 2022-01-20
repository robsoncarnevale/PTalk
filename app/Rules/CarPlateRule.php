<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;

class CarPlateRule implements Rule
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
        if(strlen($value) < 8)
            return false;

        if(!strpos($value, '-'))
            return false;

        if(!is_string(substr($value, 0, 3)))
            return false;

        if(!is_numeric(substr($value, 4, 1)) || !is_numeric(substr($value, 6)))
            return false;

        return true;
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return __('validation.vehicle_carplate');
    }
}
