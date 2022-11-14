<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Filterable\Filterable;
use App\Models\BankAccountHistory;
use App\Services\BankAccountOperation;

class BankAccount extends Model
{
    use Filterable, BankAccountOperation;

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

    //Pega os usuarios devedores
    public static function owes() {
        return BankAccount::
                  join('bank_account_users as bau','bank_accounts.id','bau.bank_account_id')
                ->join('users as u','bau.user_id','u.id')
                ->select('u.name', 'bank_accounts.balance', 'u.phone', 'u.id as user_id')
                ->where('bank_accounts.balance', '<', 0)
                ->get()->toArray();
    }
}
