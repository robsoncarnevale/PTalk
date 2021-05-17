<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Event Class Data Model
 *
 * @author Davi Souto
 * @since 23/23/2021
 */
class EventClassData extends Model
{
    protected $table = 'event_class_data';

    protected $fillable = [
        'start_subscription_date',
        'vehicle_value',
        'participant_value',
        'companion_value',
    ];

    protected $hidden = [
        'club_code',
    ];

    /**
     * Get the event
     */
    public function event()
    {
        return $this->hasOne('App\Models\Event');
    }
}
