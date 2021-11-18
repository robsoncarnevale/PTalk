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
    protected $fillable = [
        'account_number',
        'balance',
        'bank_account_type_id',
        'status_id'
    ];

    public function type()
    {
        return $this->hasOne(BankAccountType::class, 'id', 'bank_account_type_id');
    }

    public function status()
    {
        return $this->hasOne(Status::class, 'id', 'status_id');
    }
}
