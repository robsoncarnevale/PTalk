<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * 
 * Blacklist Model
 *
 * @author Davi Souto
 * @since 31/10/2020
 */
class Blacklist extends Model
{
    protected $table = 'blacklist';

    const BLOCKED_STATUS = 'blocked';
    const RELEASED_STATUS = 'released';

    protected $fillable = [
        'phone',
        'description',
        'status',
    ];

    protected $hidden = [
        'club_code',
    ];

    /**
     * vehicle.user_id => users.id
     */
    public function history()
    {
        return $this->hasMany('App\Models\BlacklistHistory');
    }

    public function saveHistory()
    { 
        $blacklist_history = new \App\Models\BlacklistHistory();
        $blacklist_history->club_code = $this->club_code;
        $blacklist_history->blacklist_id = $this->id;
        $blacklist_history->status = $this->status;
        $blacklist_history->description = $this->description;
        $blacklist_history->changed_by = $this->updated_by;
        $blacklist_history->save();

        return $blacklist_history;
    }

    /**
     * Get formatted phone number
     * @author Davi Souto
     * @since 31/10/2020
     */
    public function getPhoneFormattedAttribute()
    {
        $formatedPhone = preg_replace('/[^0-9]/', '', $this->phone);

        $matches = [];
        preg_match('/^([0-9]{2})([0-9]{4,5})([0-9]{4})$/', $formatedPhone, $matches);

        if ($matches) {
            return '('.$matches[1].') '.$matches[2].'-'.$matches[3];
        }
    
        return $this->phone; // return number without format
    }
}
