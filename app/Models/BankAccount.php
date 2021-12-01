<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Filterable\Filterable;

class BankAccount extends Model
{
    use Filterable;

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

    public function through()
    {
        return $this->hasOne(BankAccountUser::class, 'bank_account_id', 'id');
    }

    public function history()
    {
        return $this->hasMany(BankAccountHistory::class, 'bank_account_id', 'id');
    }
}
