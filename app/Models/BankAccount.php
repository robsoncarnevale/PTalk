<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Filterable\Filterable;
use App\Models\BankAccountHistory;

class BankAccount extends Model
{
    use Filterable;

    const ACTIVE = 1;
    const BLOCKED = 2;
    const INACTIVE = 3;

    const CLUB = 1;
    const MEMBER = 2;

    protected $fillable = [
        'uuid',
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

    public function scopeTransfer($query, $amount)
    {
        $user = User::find(User::getAuthenticatedUserId());

        if(!$user)
            throw new \Exception(__('users.not-found'));

        $origin = BankAccount::where('bank_account_type_id', BankAccount::CLUB)->first();

        if($user->type == User::TYPE_MEMBER)
        {
            $origin = $user->through->account;

            if(!$origin)
                throw new \Exception(__('bankaccount.errors.member-not-have-account'));
        }

        $object = json_encode([
            'user' => [
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
                'document_cpf' => $user->document_cpf,
                'document_rg' => $user->document_rg,
                'created_at' => $user->created_at,
                'updated_at' => $user->updated_at
            ],
            'operation' => 'transfer',
            'operation_type' => 'credit',
            'amount' => $amount,
            'description' => $description
        ]);

        $historyOrigin = BankAccountHistory::create([
            'bank_account_id' => $origin->id,
            'data' => $object
        ]);

        if(!$historyOrigin)
            throw new \Exception(__('bankaccount.error-transfer'));

        //Continuar de casa
    }
}
