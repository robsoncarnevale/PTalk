<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * 
 * BanckAccunt Model
 *
 * @author Davi Souto
 * @since 26/11/2020
 */
class BankAccount extends Model
{
    protected $table = 'bank_account';

    const ACTIVE_STATUS = 'active';
    const INACTIVE_STATUS = 'inactive';
    const BLOCKED_STATUS = 'blocked';

    protected $fillable = [
    ];

    protected $hidden = [
        'club_code',
    ];
}
