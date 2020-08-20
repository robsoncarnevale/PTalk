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
}
