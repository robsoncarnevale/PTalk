<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * EventSubscription Model
 *
 * @author Davi Souto
 * @since 20/08/2020
 */
class EventSubscription extends Model
{
    protected $table = 'events_subscriptions';

    protected $fillable = [
        'club_code',
        'event_id',
        'user_id',
        'status',
        'vehicle',
        'companions',
        'amount',
        'reason'
    ];

    const ACTIVE_STATUS = 'active';
    const INACTIVE_STATUS = 'inactive';

    /**
     * Get event
     */
    public function event()
    {
        return $this->belongsTo('App\Models\Event');
    }

    /**
     * Get user
     */
    public function user()
    {
        return $this->belongsTo('App\Models\User');
    }
}
