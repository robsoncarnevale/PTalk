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
}
