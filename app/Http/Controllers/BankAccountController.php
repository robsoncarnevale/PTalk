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
}
