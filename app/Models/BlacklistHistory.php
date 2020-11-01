<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;

/**
 * 
 * Blacklist History Model
 *
 * @author Davi Souto
 * @since 31/10/2020
 */
class BlacklistHistory extends Model
{
    protected $table = 'blacklist_history';

    protected $fillable = [
        'blacklist_id',
        'status',
        'description',
    ];

    protected $hidden = [
        'club_code',
    ];

    public function blacklist()
    {
        return $this->belongstTo('App\Models\Blacklist');
    }

    public function getChangedBy()
    {
        return $this->hasOne('App\Models\User', 'id', 'changed_by');
    }

    public function getCreatedAtFormattedAttribute()
    {
        return (new Carbon($this->created_at))->format('d/m/Y H:i:s');
    }
}
