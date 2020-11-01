<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;

/**
 * Verify if blacklist exists
 * 
 * @author Davi Souto
 * @since 05/08/2020
 */
class NotBlacklistExists implements Rule
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
        $exists = \App\Models\Blacklist::select('id')
            ->where('phone', $value)
            ->where('club_code', getClubCode())
            ->first();

        return ! $exists;
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return __('rules.already-registered');
    }
}
