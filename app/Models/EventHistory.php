<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;

/**
 * 
 * Event History Model
 *
 * @author Davi Souto
 * @since 17/05/2021
 */
class EventHistory extends Model
{
    protected $table = 'events_history';

    protected $fillable = [
        'event_id',
        'effected_by',
        'resume',
        'status',
    ];

    protected $hidden = [
        'club_code',
    ];


    public function getEffectedBy()
    {
        return $this->hasOne('App\Models\User', 'id', 'effected_by');
    }

    public function getCreatedAtFormattedAttribute()
    {
        return (new Carbon($this->created_at))->format('d/m/Y H:i:s');
    }
}
