<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Event Model
 *
 * @author Davi Souto
 * @since 20/08/2020
 */
class Event extends Model
{
    protected $table = 'events';

    const ACTIVE_STATUS = 'active';
    const INACTIVE_STATUS = 'inactive';

    protected $fillable = [
        'name',
        'description',
        'address',
        'meeting_point',
        'date',
        'start_time',
        'end_time',
        'max_vehicles',
        'max_participants',
        'max_companions',
        'status',
    ];

    protected $hidden = [
        'club_code',
    ];

    /**
     * Get the user
     */
    public function user()
    {
        return $this->hasOne('App\Models\User', 'id', 'created_by');
    }
}
