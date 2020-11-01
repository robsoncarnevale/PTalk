<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * UserApprovalHistory Model
 *
 * @author Davi Souto
 * @since 31/10/2020
 */
class UserApprovalHistory extends Model
{
    protected $table = 'user_approval_history';

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
