<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\User;
use App\Models\BankAccount;

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
        /*
        {
            'user' => {
                'id',
                'name',
                'email',
                'document_cpf',
                'document_rg',
                'created_at',
                'updated_at'
            },
            'operation' => 'transfer|charge|discount',
            'operation_type' => 'credit|debit',
            'amount' => (float),
            'description'
        }
        */

        return json_decode($this->data, true);
    }
}
