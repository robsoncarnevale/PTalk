<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\BankAccount;
use App\Models\User;

use App\Http\Resources\BankAccount as BankAccountResource;
use App\Http\Resources\BankAccountCollection;

/**
 * Car Brands Controller
 *
 * @author Davi Souto
 * @since 25/11/2020
 */
class BankAccountController extends Controller
{
    protected $only_admin = false;

    /**
     * List
     *
     * @author Davi Souto
     * @since 25/11/2020
     */
    function List(Request $request)
    {
        $accounts = BankAccount::select()
            ->orderBy('account_holder')
            ->jsonPaginate(50);

        $resume = [
            'accounts' => BankAccount::count(),
            'total_balance' => BankAccount::select(\DB::raw('SUM(balance) as total'))->first()['total']
        ];

        $collection = [
            'resume' => $resume,
            'accounts' => $accounts
        ];

        return response()->json([ 'status' => 'success', 'data' => (new BankAccountCollection($collection)) ]);
    }

    /**
     * Extract an account
     *
     * @author Davi Souto
     * @since 25/11/2020
     */
    function Extract(Request $request, BankAccount $bank_account)
    {
        $this->validateClub($bank_account->club_code, 'bank_account');

        return response()->json([ 'status' => 'success', 'data' => (new BankAccountResource($bank_account)) ]);
    }

    /**
     * Extract my account
     *
     * @author Davi Souto
     * @since 25/11/2020
     */
    function ExtractMyAccount(Request $request)
    {
        $bank_account = BankAccount::select()
            ->where('user_id', User::getAuthenticatedUserId())
            ->first();

        return response()->json([ 'status' => 'success', 'data' => (new BankAccountResource($bank_account)) ]);
    }
}
