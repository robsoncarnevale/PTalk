<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * 
 * AccountLaunch Model
 *
 * @author Davi Souto
 * @since 06/02/2021
 */
class AccountLaunch extends Model
{
    protected $table = 'account_launch';

    const CREDIT_TYPE = 'credit';
    const DEBIT_TYPE = 'debit';

    const DEBIT_DESCRIPTION = 'debit';
    const CREDIT_DESCRIPTION = 'credit';
    const EVENT_SUBSCRIBE_DESCRIPTION = 'event_subscribe';

    const MANUAL_MODE = 'manual';
    const AUTOMATIC_MODE = 'automatic';

    protected $fillable = [
    ];

    protected $hidden = [
        'club_code',
    ];

    /**
     * Get the created by user
     */
    public function get_created_by()
    {
        return $this->belongsTo('App\Models\User', 'created_by');
    }

    /**
     * Get the bank account class
     */
    public function bank_account()
    {
        return $this->belongsTo('App\Models\BankAccount', 'account_number', 'account_number');
    }

    /**
     * Get event
     */
    public function event()
    {
        return $this->belongsTo('App\Models\Event');
    }
}
