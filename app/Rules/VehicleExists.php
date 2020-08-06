<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;

/**
 * Verify if vehicle exists
 * 
 * @author Davi Souto
 * @since 05/08/2020
 */
class VehicleExists implements Rule
{
    private $vehicle_id = false;

    /**
     * Create a new rule instance.
     *
     * @return void
     */
    public function __construct($vehicle_id = false)
    {
        $this->vehicle_id = $vehicle_id;
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
        if ($this->vehicle_id)
            $value = $vehicle_id;

        return \App\Models\Vehicle::select('id')
            ->where('id', $value)
            ->where('club_code', getClubCode())
            ->first();
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return __('rules.not-found');
    }
}
