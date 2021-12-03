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

    public function scopeTransfer($query, float $amount, $description)
    {
        if($amount < 1)
            throw new \Exception(__('bank_account.errors.min-transfer'));

        $user = User::find(User::getAuthenticatedUserId());

        if(!$user)
            throw new \Exception(__('users.not-found'));

        if($user->type == User::TYPE_ADMIN)
            $origin = BankAccount::where('bank_account_type_id', BankAccount::CLUB)->first();

        if($user->type == User::TYPE_MEMBER)
            $origin = $user->through?->account;

        if(!isset($origin) || !$origin)
            throw new \Exception(__('bank_account.errors.bank-account-not-found-origin'));

        if($origin->id == $this->id)
            throw new \Exception(__('bank_account.errors.transfer-my'));

        if($amount > (float) $origin->balance)
            throw new \Exception(__('bank_account.errors.insufficient-fund'));

        $origin->balance -= $amount;
        $origin->save();

        $historyOrigin = BankAccountHistory::create([
            'bank_account_id' => $origin->id,
            'data' => BankAccountHistory::makeJson(
                $user,
                'transfer',
                'debit',
                $amount,
                $description,
                null, //origin
                $this //destiny
            )
        ]);

        if(!$historyOrigin)
            throw new \Exception(__('bank_account.error-transfer'));

        $this->balance += $amount;
        $this->save();

        $historyDestiny = BankAccountHistory::create([
            'bank_account_id' => $this->id,
            'data' => BankAccountHistory::makeJson(
                $user,
                'transfer',
                'credit',
                $amount,
                $description,
                $origin
            )
        ]);

        return true;
    }
}
