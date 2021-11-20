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
        $accounts = BankAccount::select(
                                    'id',
                                    DB::raw('SUM(balance) AS `balance_resume`'),
                                    'uuid',
                                    'account_number',
                                    'balance',
                                    'bank_account_type_id',
                                    'status_id',
                                    'created_at',
                                    'updated_at'
                                )
                                ->orderBy('id', 'desc')
                                ->orderBy('bank_account_type_id', 'asc')
                                ->jsonPaginate(20);

        // dd($accounts);

        return [
            'data' => BankAccountResource::collection($accounts['data']),
            'paginator' => $accounts['paginator']
        ];
    }
}
