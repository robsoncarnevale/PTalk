<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;

/**
 * UserStatusHistory Model
 *
 * @author Davi Souto
 * @since 19/08/2020
 */
class UserStatusHistory extends Model
{
    protected $table = 'user_status_history';

    protected $appends = ['created_at_formatted', 'updated_at_formatted', 'suspended_time_formatted'];

    /**
     * Get the user
     */
    public function user()
    {
        return $this->belongsTo('App\Models\User');
    }

    /**
     * Get the created by user
     */
    public function created_by()
    {
        return $this->belongsTo('App\Models\User', 'created_by');
    }

    /**
     * Get the club
     */
    public function club()
    {
        return $this->belongsTo('App\Models\Club');
    }

    //////////////////////

    public function getCreatedAtFormattedAttribute()
    {
        return (new Carbon($this->created_at))->format('d/m/Y H:i:s');
    }

    public function getUpdatedAtFormattedAttribute()
    {
        return (new Carbon($this->updated_at))->format('d/m/Y H:i:s');
    }

    public function getSuspendedTimeFormattedAttribute()
    {
        if (! $this->suspended_time) {
            return $this->suspended_time;
        }

        return (new Carbon($this->suspended_time))->format('d/m/Y H:i:s');
    }
}
