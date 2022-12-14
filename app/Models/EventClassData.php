<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;

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
        'active',
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

    /**
     * Get the event
     */
    public function member_class()
    {
        return $this->belongsTo('App\Models\MemberClass');
    }

    ///////////////////////////////////

    function getStartSubscriptionDateAttribute($date)
    {
        if ($date) {
            return (new Carbon($date))->format('Y-m-d');
        }
        
        return null;
    }

    function getStartSubscriptionDateBrAttribute()
    {
        if ($this->start_subscription_date) {
            return (new Carbon($this->start_subscription_date))->format('d/m/Y');
        }
        
        return null;
    }
}
