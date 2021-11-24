<?php

namespace App\Http\Controllers;

use App\Models\BankAccount;
use Illuminate\Http\Request;
use App\Models\Config;
use App\Models\User;
use App\Models\Status;
use App\Http\Resources\BankAccount as BankAccountResource;
use DB;

class BankAccountController extends Controller
{
    protected $only_admin = false;

    public function index()
    {
        $accounts = BankAccount::orderBy('id', 'desc')
                                ->orderBy('bank_account_type_id', 'asc')
                                ->jsonPaginate(20);

        $resume = BankAccount::sum('balance');

        return [
            'status' => 'success',
            'resume' => [
                'total_balances' => $resume,
                'total_accounts' => count($accounts['data'])
            ],
            'data' => BankAccountResource::collection($accounts['data']),
            'paginator' => $accounts['paginator']
        ];
    }

    public function my()
    {
        try
        {
            $user = auth()->user();

            if(!$user)
                $user = User::getMobileSession();

            if(!$user->bank && !isset($user->bank->account))
                throw new \Exception('Você não possuí uma conta bancária!');

            $account = $user->bank->account;

            //

            return [
                'status' => 'success',
                'detail' => [
                    'id' => $account->id,
                    'account_number' => $account->account_number,
                    'account_holder' => $user->name,
                    'balance' => (float) $account->balance
                ],
                'resume' => [
                    'credit' => 0,
                    'debit' => 0
                ],
                'data' => [],
                'paginator' => []
            ];
        }
        catch(\Exception $e)
        {
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage()
            ], 500);
        }
    }
}
