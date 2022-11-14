<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

use App\Models\User;
use App\Models\UserPayment;
use App\Models\MonthlyPayment;
use App\Models\Charge;
use App\Models\BankAccount;
use App\Models\BankAccountUser;

class DailyFareCron extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'dailyFare:cron';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Fare launch'; //Apurar diariamente tarifas a serem lançadas

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $usersDebit = [];
        
        $users = User::select('id','name','status','type','approval_status','approval_status_date')
                    ->where('status', 'active')
                    ->where('type', 'member')
                    ->where('approval_status', 'approved')
                    ->get();

        $now_year = date('Y');
        $now_month = date('m');
        $now_day = (int)date('d');
        $data = null;

        foreach($users as $user) {
            $users_payment = UserPayment::select()
                                ->where('user_id', $user['id'])
                                ->get();
            if (count($users_payment) > 0) {
                $day_payment = $users_payment[0]['day_payment'];
                if ($now_day >= $day_payment) {
                    $user_charge = Charge::select()
                                    ->where('user_id', $user['id'])
                                    ->where('done', 1)
                                    ->whereYear('created_at', $now_year)
                                    ->whereMonth('created_at', $now_month)
                                    ->get();
                    if (count($user_charge) < 1) {
                        $charge = new Charge();
                        //['user_id', 'monthly_payment_id', 'done', 'value'];
                        $charge->user_id = $user['id'];
                        $charge->monthly_payment_id = $users_payment[0]['monthly_payment_id'];
                        $charge->done = 1;
                        $monthly_payment = MonthlyPayment::select()
                                                ->where('id', $users_payment[0]['monthly_payment_id'])
                                                ->get();
                        $charge->value = $monthly_payment[0]['value'];
                        $charge->save();

                        $bancAccountUser = BankAccountUser::select()
                                                ->where('user_id', $user['id'] )
                                                ->first();
                        $bankAccount = BankAccount::select()
                                    ->where( 'id', $bancAccountUser->bank_account_id)
                                    ->first();
                        $bankAccount->balance -= $monthly_payment[0]['value'];
                        $bankAccount->update();
                        array_push($usersDebit, $user['id']);

                        
                    }
                }
            }
        }       

        //$retorno = response()->json([ 'usersDebit' => $usersDebit, 'quant' => count($usersDebit)]);
        echo "Cobranças feitas: ".count($usersDebit);
        
        //Retorna quantas cobranças foram feitas
        return count($usersDebit);
        
    }
}
