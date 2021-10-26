<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use DB;

use App\Models\AccountLaunch;
use App\Models\BankAccount;
use App\Models\ClubBankAccount;
use App\Models\ClubLaunch;
use App\Models\Config;
use App\Models\User;

use App\Http\Resources\BankAccount as BankAccountResource;
use App\Http\Resources\BankAccountResume as BankAccountResumeResource;
use App\Http\Resources\Extract as ExtractResource;
use App\Http\Resources\BankAccountCollection;
use App\Http\Resources\Launch as LaunchResource;

use App\Http\Requests\LaunchRequest;

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
        $search = $request->get('search', '');

        $accounts = BankAccount::select()
            ->orderBy('balance', 'desc')
            ->orderBy('account_holder')
            ->whereHas('user', function($q){
                $q->where('deleted', false)
                  ->where('approval_status', \App\Models\User::APPROVED_STATUS_APPROVAL);
            });

        if (! empty($search)) {
            $accounts->where(function($q) use ($search){
                $search_numbers = $search;
                $search_numbers = preg_replace('#[^0-9]#is', '', $search_numbers);

                $q->orWhereRaw('LOWER(account_holder) like ?', strtolower("%{$search}%"));

                $q->whereHas('user', function($q) use ($search, $search_numbers){
                    $q->whereRaw('LOWER(name) like ?', strtolower("%{$search}%"))
                      ->orWhereRaw('LOWER(nickname) like ?', strtolower("%{$search}%"));

                      if (! empty($search_numbers)) {
                        $q->orWhereRaw('LOWER(phone) like ?', strtolower("%{$search_numbers}%"))
                          ->orWhereRaw('LOWER(document_cpf) like ?', strtolower("%{$search_numbers}%"));
                      }
                });

                if (! empty($search_numbers)) {
                    $q->orWhereRaw('LOWER(account_number) like ?', strtolower("%{$search_numbers}%"));
                }
            });
        }

        $accounts = $accounts->jsonPaginate(50);

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

        return response()->json([ 'status' => 'success', 'data' => (new ExtractResource($bank_account)) ]);
    }

    /**
     * Extract my account
     *
     * @author Davi Souto
     * @since 25/11/2020
     */
    function ExtractMyAccount(Request $request)
    {
        $user = auth()->user();

        if($user->type == 'admin')
            return response()->json(['status' => 'error', 'message' => __('bank_account.error-extract-administrator')], 403);

        $bank_account = BankAccount::select()
            ->where('user_id', $user->id)
            ->first();

        return response()->json([ 'status' => 'success', 'data' => (new ExtractResource($bank_account)) ]);
    }

    /**
     * Find bank account
     * @author Davi Souto
     * @since 06/02/2021
     */
    public function Find($bank_account)
    {
        $bank_account = BankAccount::select()
            ->where('account_number', $bank_account)
            ->where('club_code', getClubCode())
            ->first();

        if (! $bank_account) {
            return response()->json([ 'status' => 'error', 'message' => __('bank_account.not-found') ], 404);
        }

        return response()->json([ 'status' => 'success', 'data' => (new BankAccountResumeResource($bank_account)) ]);
    }

    /**
     * Launch debit on bank account
     * @author Davi Souto
     * @since 06/02/2021
     */
    public function LaunchDebit($bank_account, LaunchRequest $request)
    {
        $bank_account = BankAccount::select()
            ->where('account_number', $bank_account)
            ->where('club_code', getClubCode())
            ->first();

        if (! $bank_account) {
            return response()->json([ 'status' => 'error', 'message' => __('bank_account.not-found') ], 404);
        }

        $club_account = ClubBankAccount::Get();

        DB::beginTransaction();

        try {
            $launch = new AccountLaunch();
            $launch->club_code = getClubCode();
            $launch->account_number = $bank_account->account_number;
            $launch->created_by = User::getAuthenticatedUserId();
            $launch->amount = $request->get('value');
            $launch->type = AccountLaunch::DEBIT_TYPE;
            $launch->description = AccountLaunch::DEBIT_DESCRIPTION;
            $launch->mode = AccountLaunch::MANUAL_MODE;
            $launch->user_description = $request->get('user_description');
            $launch->save();

            $bank_account->balance -= $request->get('value');
            $bank_account->save();

            $club_launch = new ClubLaunch();
            $club_launch->club_code = getClubCode();
            $club_launch->created_by = User::getAuthenticatedUserId();
            $club_launch->amount = $request->get('value');
            $club_launch->type = ClubLaunch::CREDIT_TYPE;
            $club_launch->description = ClubLaunch::USER_DEBIT_DESCRIPTION;
            $club_launch->mode = ClubLaunch::AUTOMATIC_MODE;
            $club_launch->user_description = '-';
            $club_launch->save();

            $club_account->balance += $request->get('value');
            $club_account->save();

            DB::commit();
        } catch (\Exception $e) {
            DB::rollback();

            return response()->json([ 'status' => 'error', 'message' => __('bank_account.error-launch-debit')]);
        }

        return response()->json([ 'status' => 'success', 'data' => (new LaunchResource($launch)) ]);
    }

    /**
     * Launch credit on bank account
     * @author Davi Souto
     * @since 06/02/2021
     */
    public function LaunchCredit($bank_account, LaunchRequest $request)
    {
        $bank_account = BankAccount::select()
            ->where('account_number', $bank_account)
            ->where('club_code', getClubCode())
            ->first();

        if (! $bank_account) {
            return response()->json([ 'status' => 'error', 'message' => __('bank_account.not-found') ], 404);
        }

        $club_account = ClubBankAccount::Get();

        DB::beginTransaction();

        try {
            $config = Config::Get();

            $launch = new AccountLaunch();
            $launch->club_code = getClubCode();
            $launch->account_number = $bank_account->account_number;
            $launch->created_by = User::getAuthenticatedUserId();
            $launch->amount = $request->get('value');
            $launch->type = AccountLaunch::CREDIT_TYPE;
            $launch->description = AccountLaunch::CREDIT_DESCRIPTION;
            $launch->mode = AccountLaunch::MANUAL_MODE;
            $launch->user_description = $request->get('user_description');
            $launch->save();

            $bank_account->balance += $request->get('value');
            $bank_account->save();

            $club_launch = new ClubLaunch();
            $club_launch->club_code = getClubCode();
            $club_launch->created_by = User::getAuthenticatedUserId();
            $club_launch->amount = $request->get('value');
            $club_launch->type = ClubLaunch::DEBIT_TYPE;
            $club_launch->description = ClubLaunch::USER_CREDIT_DESCRIPTION;
            $club_launch->mode = ClubLaunch::AUTOMATIC_MODE;
            $club_launch->user_description = '-';
            $club_launch->save();

            $club_account->balance -= $request->get('value');
            $club_account->save();

            if (! $config->allow_negative_balance && $club_account->balance < 0) {
                DB::rollback();

                return response()->json([ 'status' => 'error', 'message' => __('bank_account.error-negative-balance-2') ]);
            }

            DB::commit();
        } catch (\Exception $e) {
            DB::rollback();

            return response()->json([ 'status' => 'error', 'message' => __('bank_account.error-launch-credit')]);
        }

        return response()->json([ 'status' => 'success', 'data' => (new LaunchResource($launch)) ]);
    }
}
