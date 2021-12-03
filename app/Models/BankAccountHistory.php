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

    public static function makeJson(
        User $user,
        $operation,
        $type,
        $amount,
        $description = null,
        BankAccount $origin = null,
        BankAccount $destiny = null
    ) : string
    {
        $object = [
            'user' => [
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
                'document_cpf' => $user->document_cpf,
                'document_rg' => $user->document_rg,
                'created_at' => $user->created_at,
                'updated_at' => $user->updated_at
            ],
            'operation' => $operation,
            'operation_type' => $type,
            'amount' => (float) $amount,
            'description' => $description
        ];

        if($origin)
            $object['origin'] = [
                'id' => $origin->id,
                'account_number' => $origin->account_number,
                'account_holder' => $origin->through?->user?->name,
                'type' => [
                    'id' => $origin->bank_account_type_id,
                    'description' => $origin->type->description
                ],
                'status' => [
                    'id' => $origin->status_id,
                    'description' => $origin->status->description
                ]
            ];

        if($destiny)
            $object['destiny'] = [
                'id' => $destiny->id,
                'account_number' => $destiny->account_number,
                'account_holder' => $destiny->through?->user?->name,
                'type' => [
                    'id' => $destiny->bank_account_type_id,
                    'description' => $destiny->type->description
                ],
                'status' => [
                    'id' => $destiny->status_id,
                    'description' => $destiny->status->description
                ]
            ];

        return json_encode($object);
    }
}
