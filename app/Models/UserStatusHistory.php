<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * UserStatusHistory Model
 *
 * @author Davi Souto
 * @since 19/08/2020
 */
class UserStatusHistory extends Model
{
    protected $table = 'user_status_history';

    /**
     * Get the user
     */
    public function user()
    {
        return $this->belongsTo('App\Models\User');
    }

    /**
     * Get the club
     */
    public function club()
    {
        return $this->belongsTo('App\Models\Club');
    }
}
