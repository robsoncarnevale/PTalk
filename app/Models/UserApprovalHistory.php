<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;

/**
 * UserApprovalHistory Model
 *
 * @author Davi Souto
 * @since 31/10/2020
 */
class UserApprovalHistory extends Model
{
    protected $table = 'user_approval_history';

    protected $appends = ['created_at_formatted', 'updated_at_formatted'];

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
     * Get the created by user
     */
    public function get_created_by()
    {
        return $this->created_by();
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
}
