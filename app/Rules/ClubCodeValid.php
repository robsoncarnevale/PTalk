<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;

/**
 * Verify if club code is valid
 * 
 * @author Davi Souto
 * @since 05/08/2020
 */
class ClubCodeValid implements Rule
{
    private $attribute = 'club_code';

    /**
     * Create a new rule instance.
     *
     * @return void
     */
    public function __construct($attribute = 'club_code')
    {
        $this->attribute = $attribute;
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
        return $value === getClubCode();
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        $trans_attr = __('validation.attributes.'.$this->attribute);

        return __('rules.not-found', [ 'attribute' => $trans_attr ]);
    }
}
