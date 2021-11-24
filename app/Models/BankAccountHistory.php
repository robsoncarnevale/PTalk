<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BankAccountHistory extends Model
{
    use HasFactory;

    protected $fillable = ['bank_account_id', 'data'];

    public function account()
    {
        return $this->hasOne(BankAccount::class, 'id', 'bank_account_id');
    }

    public function json()
    {
        return json_decode($this->data);
    }
}
