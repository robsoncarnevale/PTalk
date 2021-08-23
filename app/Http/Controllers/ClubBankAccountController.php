<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use DB;

use App\Models\ClubLaunch;
use App\Models\ClubBankAccount;
use App\Models\User;
use App\Models\Config;

use App\Http\Resources\BankAccount as BankAccountResource;
use App\Http\Resources\ClubBankAccountResume as ClubBankAccountResumeResource;
use App\Http\Resources\ClubExtract as ClubExtractResource;
use App\Http\Resources\BankAccountCollection;
use App\Http\Resources\Launch as LaunchResource;

use App\Http\Requests\LaunchRequest;

/**
 * Club Bank Account Controller
 *
 * @author Davi Souto
 * @since 09/08/2021
 */
class ClubBankAccountController extends Controller
{
    protected $only_admin = false;

    /**
     * Extract club account
     *
     * @author Davi Souto
     * @since 09/08/2021
     */
    function ExtractAccount(Request $request)
    {
        $bank_account = ClubBankAccount::select()
            ->where('club_code', getClubCode())
            ->first();

        if (! $bank_account) {
            $bank_account = new ClubBankAccount();
            $bank_account->club_code = getClubCode();
            $bank_account->balance = 0;
            $bank_account->save();
        }

        return response()->json([ 'status' => 'success', 'data' => (new ClubExtractResource($bank_account)) ]);
    }

    /**
     * Find bank account
     * @author Davi Souto
     * @since 06/02/2021
     */
    public function GetData(Request $request)
    {
        $bank_account = ClubBankAccount::select()
            ->where('club_code', getClubCode())
            ->first();

        if (! $bank_account) {
            $bank_account = new ClubBankAccount();
            $bank_account->club_code = getClubCode();
            $bank_account->balance = 0;
            $bank_account->save();
        }

        return response()->json([ 'status' => 'success', 'data' => (new ClubBankAccountResumeResource($bank_account)) ]);
    }

    /**
     * Launch debit on club account
     * @author Davi Souto
     * @since 09/08/2021
     */
    public function LaunchDebit(LaunchRequest $request)
    {
        $bank_account = ClubBankAccount::select()
            ->where('club_code', getClubCode())
            ->first();

        if (! $bank_account) {
            $bank_account = new ClubBankAccount();
            $bank_account->club_code = getClubCode();
            $bank_account->balance = 0;
            $bank_account->save();
        }

        DB::beginTransaction();

        try {
            $config = Config::Get();

            $launch = new ClubLaunch();
            $launch->club_code = getClubCode();
            $launch->created_by = User::getAuthenticatedUserId();
            $launch->amount = $request->get('value');
            $launch->type = ClubLaunch::DEBIT_TYPE;
            $launch->description = ClubLaunch::DEBIT_DESCRIPTION;
            $launch->mode = ClubLaunch::MANUAL_MODE;
            $launch->user_description = $request->get('user_description');
            
            $bank_account->balance -= $request->get('value');
            
            if (! $config->allow_negative_balance && $bank_account->balance < 0) {
                return response()->json([ 'status' => 'error', 'message' => __('bank_account.error-negative-balance')]);
            }
            
            $bank_account->save();
            $launch->save();

            DB::commit();
        } catch (\Exception $e) {
            DB::rollback();

            return response()->json([ 'status' => 'error', 'message' => __('bank_account.error-launch-debit')]);
        }

        return response()->json([ 'status' => 'success', 'data' => (new LaunchResource($launch)) ]);
    }

    /**
     * Launch credit on club account
     * @author Davi Souto
     * @since 09/08/2021
     */
    public function LaunchCredit(LaunchRequest $request)
    {
        $bank_account = ClubBankAccount::select()
            ->where('club_code', getClubCode())
            ->first();

        if (! $bank_account) {
            $bank_account = new ClubBankAccount();
            $bank_account->club_code = getClubCode();
            $bank_account->balance = 0;
            $bank_account->save();
        }

        DB::beginTransaction();

        try {
            $launch = new ClubLaunch();
            $launch->club_code = getClubCode();
            $launch->created_by = User::getAuthenticatedUserId();
            $launch->amount = $request->get('value');
            $launch->type = ClubLaunch::CREDIT_TYPE;
            $launch->description = ClubLaunch::CREDIT_DESCRIPTION;
            $launch->mode = ClubLaunch::MANUAL_MODE;
            $launch->user_description = $request->get('user_description');
            $launch->save();

            $bank_account->balance += $request->get('value');
            $bank_account->save();

            DB::commit();
        } catch (\Exception $e) {
            DB::rollback();

            return response()->json([ 'status' => 'error', 'message' => __('bank_account.error-launch-credit')]);
        }

        return response()->json([ 'status' => 'success', 'data' => (new LaunchResource($launch)) ]);
    }
}
