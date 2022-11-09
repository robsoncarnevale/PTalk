<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\User;
use App\Models\UserPayment;
use App\Models\MonthlyPayment;
use App\Models\Charge;
use App\Models\BankAccount;
use App\Models\BankAccountUser;

class CronController extends Controller
{
    //Cron Charge
    //Pegar todos os usuarios ativos, aprovados e que sejam membros
    //Verificar se ele possui uma tarifa a ser lançada hoje
    //Lança-la mesmo que não tenha lançado antes
    public function Charge() {

        //Logica do cron esta aqui. Utilizado para testes
        /*
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

        $retorno = response()->json([ 'usersDebit' => $usersDebit, 'quant' => count($usersDebit)]);

        return $retorno;
        */
        return 0;

    }
}
