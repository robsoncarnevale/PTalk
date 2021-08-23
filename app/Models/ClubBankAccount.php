<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * 
 * ClubBankAccount Model
 *
 * @author Davi Souto
 * @since 09/08/2021
 */
class ClubBankAccount extends Model
{
    protected $table = 'club_bank_account';

    protected $fillable = [
    ];

    protected $hidden = [
        'club_code',
    ];

    public static function Get()
    {
        $club_account = ClubBankAccount::select()
            ->where('club_code', getClubCode())
            ->first();

        if (! $club_account) {
            $club_account = new ClubBankAccount();
            $club_account->club_code = getClubCode();
            $club_account->balance = 0;
            $club_account->save();
        }

        return $club_account;
    }
}
