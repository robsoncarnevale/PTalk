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
