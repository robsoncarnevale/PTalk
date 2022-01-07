<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use App\Services\Paynet;
use App\Models\BankAccount;
use App\Models\TransactionStatus;
use App\Models\TransactionType;
use App\Models\Transaction;
use App\Models\PaymentMethod;
use Illuminate\Support\Facades\DB;

class JobReversalTransaction implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $transaction;
    protected $cancel;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($transaction)
    {
        $this->transaction = $transaction;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        try
        {
            \Log::info('== JOB DE CANCELAMENTO ==');

            if(!$this->transaction)
                return;

            \Log::info('Cancelando transação: ' . $this->transaction->id);

            if($this->transaction->transaction_status_id == TransactionStatus::DENIED)
            {
                \Log::info('Transação já está negada: ' . $this->transaction->id);
                return;
            }

            if(!isset($this->transaction->payment_token))
                throw new \Exception('Payment Token não localizado para a transação (' . $this->transaction->id . ')');

            $bank = new BankAccount();

            $account = BankAccount::find($this->transaction->bank_account_id);

            $bank->setDestiny($account);

            $this->cancel = Transaction::create([
                'bank_account_id' => $account->id,
                'payment_method_id' => PaymentMethod::CREDIT_CASH,
                'brand_id' => $this->transaction->brand_id,
                'installments' => $this->transaction->installments,
                'card_name' => $this->transaction->card_name,
                'card_number' => $this->transaction->card_number,
                'amount' => $this->transaction->amount,
                'order_number' => Transaction::order(),
                'transaction_type_id' => TransactionType::REVERSAL,
                'transaction_status_id' => TransactionStatus::NO_REPLY
            ]);

            $api = new Paynet();

            $api->login();

            $api->cancel($this->cancel, $this->transaction->payment_token, $this->transaction->amount);

            if($this->cancel->transaction_status_id == TransactionStatus::APPROVED)
            {
                DB::beginTransaction();

                $bank->setOperationType('debit');
                $bank->setAmount($this->transaction->amount);
                $bank->setDescription(__('bank_account.errors.reversal-charge'));

                $bank->charge();

                DB::commit();
                
                \Log::info('Transação cancelada com sucesso! (' . $this->transaction->id . ')');

                return;
            }

            \Log::info('Não sei o que aconteceu com a transação: ' . $this->transaction->id);
        }
        catch(\Exception $e)
        {
            DB::rollback();

            \Log::info($e);
        }
    }
}
