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
use App\Services\Paynet;
use GuzzleHttp\Exception\RequestException;
use App\Http\Requests\BankAccountLoadRequest;
use App\Models\Transaction;
use App\Models\PaymentMethod;
use App\Models\TransactionType;
use App\Models\TransactionStatus;
use App\Models\Brand;

class BankAccountController extends Controller
{
    protected $only_admin = false;
    protected Request $request;

    public function index(Request $request)
    {
        $accounts = BankAccount::filter($request)
                                ->orderBy('bank_account_type_id', 'asc')
                                ->orderBy('id', 'desc')
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

            $bank = new BankAccount();

            $bank->setOrigin($bank->getAccountUserLogged());
            $bank->setDestiny($account);
            $bank->setAmount($request->value);
            $bank->setDescription($request->description);

            $bank->transfer();

            DB::commit();

            return response()->json([
                'status' => 'success',
                'message' => __('bank_account.success-transfer')
            ]);
        }
        catch(\Exception $e)
        {
            DB::rollback();

            \Log::error($e);

            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage()
            ], 500);
        }
    }

    public function detail($number, Request $request)
    {
        try
        {
            $account = BankAccount::where('account_number', $number)->first();

            if(!$account)
                throw new \Exception(__('bank_account.errors.bank-account-not-found'));

            $data = new BankAccountResource($account);

            unset($data['balance']);

            return response()->json([
                'status' => 'success',
                'data' => $data
            ], 200);
        }
        catch(\Exception $e)
        {
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage()
            ], 500);
        }
    }

    public function load(BankAccountLoadRequest $request)
    {
        try
        {
            $bank = new BankAccount();

            $api = new Paynet();

            $api->login();

            $dtv = explode('/', $request->expiry_date);

            $tokenization = $api->tokenization([
                'cardNumber' => $request->credit_card,
                'cardHolder' => $request->name,
                'expirationMonth' => $dtv[0],
                'expirationYear' => $dtv[1],
                'customerName' => $request->name,
                'securityCode' => $request->cvv
            ]);

            $brand = $api->brand($request->credit_card);

            if(!Brand::find($brand))
                throw new \Exception(__('brand.not-found'));

            $transaction = Transaction::create([
                'bank_account_id' => $bank->getAccountUserLogged()->id,
                'payment_method_id' => PaymentMethod::CREDIT_CASH,
                'brand_id' => $brand,
                'installments' => 1,
                'card_name' => $request->name,
                'card_number' => $request->number(),
                'amount' => $request->amount,
                'order_number' => Transaction::order(),
                'transaction_type_id' => TransactionType::FINANCIAL,
                'transaction_status_id' => TransactionStatus::NO_REPLY
            ]);

            $api->payment($tokenization, $brand, $transaction);

            if($transaction->transaction_status_id == TransactionStatus::DENIED)
                throw new \Exception(__('transaction.not-end'));

            //Gerar o dado para o extrato bancário

            dd('Chegou ao final');
        }
        catch(\Exception $e)
        {
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
                                        ->orderBy('id', 'desc');

        $modalities = $history->get()->toArray();
        $history = $history->jsonPaginate(10);

        $modalities = array_map(function($value){ return json_decode($value['data'], true); }, $modalities);

        $credit = array_filter($modalities, function($object){ return($object['operation_type'] == 'credit'); });
        $debit = array_filter($modalities, function($object){ return($object['operation_type'] == 'debit'); });

        $credit = array_sum(array_column($credit, 'amount'));
        $debit = array_sum(array_column($debit, 'amount'));

        return [
            'status' => 'success',
            'detail' => [
                'id' => $account->id,
                'account_number' => $account->account_number,
                'account_holder' => $account->through?->user?->name,
                'balance' => (float) $account->balance
            ],
            'resume' => [
                'credit' => (float) $credit,
                'debit' => (float) $debit
            ],
            'data' => BankAccountHistoryResource::collection($history['data']),
            'paginator' => $history['paginator']
        ];
    }
}
