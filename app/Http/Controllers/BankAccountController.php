<?php

namespace App\Http\Controllers;

use App\Models\BankAccount;
use Illuminate\Http\Request;
use App\Models\Config;
use App\Models\User;
use App\Models\Status;
use App\Http\Resources\BankAccount as BankAccountResource;
use DB;
use App\Models\BankAccountHistory;
use App\Http\Resources\BankAccountHistory as BankAccountHistoryResource;
use Carbon\Carbon;

class BankAccountController extends Controller
{
    protected $only_admin = false;
    protected Request $request;

    public function index(Request $request)
    {
        $accounts = BankAccount::filter($request)
                                ->orderBy('id', 'desc')
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

    public function my(Request $request)
    {
        try
        {
            $user = User::find(User::getAuthenticatedUserId());

            if(!$user->bank && !isset($user->bank->account))
                throw new \Exception(__('bank_account.errors.no-have-account'));

            $this->request = $request;

            return $this->getBankAccountHistory($user->bank->account);
        }
        catch(\Exception $e)
        {
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage()
            ], 500);
        }
    }

    public function show(Request $request, $id)
    {
        try
        {
            $account = BankAccount::where('bank_account_type_id', 2) //member
                                    ->where('id', $id)
                                    ->first();

            if(!$account)
                throw new \Exception(__('bank_account.errors.bank-account-not-found'));

            $this->request = $request;

            return $this->getBankAccountHistory($account);
        }
        catch(\Exception $e)
        {
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage()
            ], 500);
        }
    }

    public function club(Request $request)
    {
        try
        {
            $account = BankAccount::where('bank_account_type_id', 1)->first();

            if(!$account)
                throw new \Exception(__('bank_account.errors.bank-account-not-found'));

            $this->request = $request;

            return $this->getBankAccountHistory($account);
        }
        catch(\Exception $e)
        {
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage()
            ], 500);
        }
    }

    public function transfer(Request $request)
    {
        DB::beginTransaction();

        try
        {
            $account = BankAccount::where('account_number', $request->account_number)->first();

            if(!$account)
                throw new \Exception(__('bank_account.errors.bank-account-not-found'));

            $transfer = $account->transfer($request->amount, $request->description);

            if(!$transfer)
                throw new \Exception(__('bank_account.error-transfer'));

            //
        }
        catch(\Exception $e)
        {
            DB::rollback();

            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage()
            ], 500);
        }
    }

    /* FUNCTIONS PRIVATE */

    private function getDate()
    {
        if(!$this->request)
            return null;

        $deadline = 30;

        if($this->request->get('deadline'))
        {
            $deadline = (int) $this->request->get('deadline');

            if($deadline > 90)
                $deadline = 90;

            if($deadline < 30)
                $deadline = 30;
        }

        $start = Carbon::now()->subDays($deadline)->format('Y-m-d 00:00:00');
        $end = Carbon::now()->format('Y-m-d 23:59:59');

        return (object) [
            'start' => $start,
            'end' => $end
        ];
    }

    private function getBankAccountHistory(BankAccount $account)
    {
        $deadline = $this->getDate();

        $history = BankAccountHistory::where('bank_account_id', $account->id)
                                        ->whereBetween('created_at', [$deadline->start, $deadline->end])
                                        ->orderBy('id', 'desc')
                                        ->jsonPaginate(10);

        $credit = \DB::select('
            SELECT SUM(CAST(JSON_EXTRACT(`data`, "$.amount") AS DECIMAL(12, 2))) AS `total`
            FROM bank_account_histories
            WHERE created_at >= "' . $deadline->start . '"
            AND created_at <= "' . $deadline->end . '"
            AND JSON_EXTRACT(`data`, "$.operation_type") = "credit"
            AND bank_account_id = ' . $account->id
        );

        $debit = \DB::select('
            SELECT SUM(CAST(JSON_EXTRACT(`data`, "$.amount") AS DECIMAL(12, 2))) AS `total`
            FROM bank_account_histories
            WHERE created_at >= "' . $deadline->start . '"
            AND created_at <= "' . $deadline->end . '"
            AND JSON_EXTRACT(`data`, "$.operation_type") = "debit"
            AND bank_account_id = ' . $account->id
        );

        $credit = !isset($credit[0]) ? 0 : $credit[0]->total ;
        $debit = !isset($debit[0]) ? 0 : $debit[0]->total ;

        return [
            'status' => 'success',
            'detail' => [
                'id' => $account->id,
                'account_number' => $account->account_number,
                'account_holder' => $account->through?->user?->name,
                'balance' => (float) $account->balance
            ],
            'resume' => [
                'credit' => is_null($credit) ? 0 : (float) $credit ,
                'debit' => is_null($debit) ? 0 : (float) $debit
            ],
            'data' => BankAccountHistoryResource::collection($history['data']),
            'paginator' => $history['paginator']
        ];
    }
}
