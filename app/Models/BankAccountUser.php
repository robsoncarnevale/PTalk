<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BankAccountUser extends Model
{
    use HasFactory;

    protected $fillable = [
        'bank_account_id',
        'user_id'
    ];

    public function account()
    {
        return $this->hasOne(BankAccount::class, 'id', 'bank_account_id');
    }

    public function user()
    {
        return $this->hasOne(User::class, 'id', 'user_id');
    }
}
