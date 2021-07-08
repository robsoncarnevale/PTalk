<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\UserRequest;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Mail;

use App\Models\User;
use App\Models\Vehicle;
use App\Models\UserStatusHistory;
use App\Models\UserApprovalHistory;
use App\Models\MemberClass;
use App\Http\Resources\User as UserResource;
use App\Http\Resources\UserCollection;
use App\Http\Resources\UserWaitingApproval as UserWaitingApprovalResource;
use App\Http\Resources\UserWaitingApprovalCollection;
use App\Http\Resources\UserHistoryApproval as UserHistoryApprovalResource;
use App\Http\Resources\UserHistoryApprovalCollection;

use DB;
use Exception;

/**
 * Member Users Controller
 *
 * @author Davi Souto
 * @since 15/06/2020
 */
class MemberUsersController extends Controller
{
    protected $only_admin = false;

    /**
     * List Members
     *
     * @author Davi Souto
     */
    public function List(Request $request)
    {
        return UsersController::List($request, 'member');
    }

    /**
     * Create Members
     *
     * @author Davi Souto
     * @since 15/06/2020
     */
    public function Create(UserRequest $request)
    {
        return UsersController::Create($request, 'member');
    }

    /**
     * Update Members
     *
     * @author Davi Souto
     * @since 15/06/2020
     */
    public function Update(UserRequest $request, $user_id)
    {
        return UsersController::Update($request, $user_id, 'member');
    }

    /**
     * Delete Members
     *
     * @author Davi Souto
     */
    public function Delete(Request $request, $user_id)
    {
        return UsersController::Delete($request, $user_id, 'member');
    }

    /**
     * Get Members
     * 
     * @author Davi Souto
     * @since 15/06/2020
     */
    public function Get(Request $request, $user_id)
    {
        return UsersController::Get($request, $user_id, 'member');
    }

    ////////////////////////

    /**
     * Returns members waiting approval
     * 
     * @author Davi Souto
     * @since 18/06/2020
     */
    public function WaitingApproval(Request $request)
    {
        $users = User::select()
            // ->with('privilege_group')
            ->with('member_class')
            ->with('privilege_group:id,name')
            ->with('participation_request_information')
            ->with('indicator:id,name,photo,email,phone,nickname')
            ->with('approval_history')
            ->with('approval_history.created_by')
            ->where('club_code', getClubCode())
            ->where('deleted', false)
            ->where('approval_status', User::WAITING_STATUS_APPROVAL)
            ->where('type', 'member')
            ->orderBy('created_at');

        $search = trim($request->get('search'));

        if (! empty($search)){
            $users->where(function($q) use ($search) {
                $search_numbers = $search;
                $search_numbers = preg_replace('#[^0-9]#is', '', $search_numbers);

                $q->whereRaw('LOWER(name) like ?', strtolower("%{$search}%"))
                  ->orWhereRaw('LOWER(email) like ?', strtolower("%{$search}%"));

                  if (! empty($search_numbers)) {
                    $q->orWhereRaw('LOWER(phone) like ?', strtolower("%{$search_numbers}%"))
                      ->orWhereRaw('LOWER(document_cpf) like ?', strtolower("%{$search_numbers}%"));
                  }

                  $q->orWhere(function($q) use ($search){
                      $q->whereHas('participation_request_information', function($q) use ($search){
                        $search_carplate = $search;

                        if (strlen($search) >= 4 && strpos($search, '-') === false) {
                            $search_carplate = substr($search, 0, 3) . '-' . substr($search, 3);
                        }
                        
                        $q->whereRaw('LOWER(vehicle_carplate) like ?', strtolower("%{$search_carplate}%"));

                        
                      });
                  });
            });
        }

        $count_indicators = clone $users;
        $count_voluntary = clone $users;
        $count_total = clone $users;

        $count_indicators = $count_indicators
            ->whereHas('indicator')
            ->count();

        $count_voluntary = $count_voluntary
            ->whereDoesntHave('indicator')
            ->count();

        $count_total = $count_total->count();

        $users = $users->jsonPaginate(25, 3);

        $result = [
            'history' => (new UserWaitingApprovalCollection($users)),
            'count' => [ 
                'indicators' => $count_indicators,
                'voluntary' => $count_voluntary,
                'total' => $count_total
            ]
        ];

        return response()->json([ 'status' => 'success', 'data' => $result ]);
    }

    /**
     * Returns members history approval
     * 
     * @author Davi Souto
     * @since 18/01/2021
     */
    public function HistoryApproval(Request $request)
    {
        $history = UserApprovalHistory::select()
            ->where('club_code', getClubCode())
            ->whereHas('user', function($q){
                $q->where('deleted', false);
            })
            ->orderBy('created_at', 'desc')
            ->jsonPaginate(50, 3);

        return response()->json([ 'status' => 'success', 'data' => (new UserHistoryApprovalCollection($history)) ]);
    }

    /**
     * Set member approval status
     * 
     * @author Davi Souto
     * @since 04/08/2020
     * @param int $user_id
     * @param string $status
     */
    public function SetApprovalStatus(Request $request, $user_id, $status)
    {
        if (! in_array($status, [ User::APPROVED_STATUS_APPROVAL, User::WAITING_STATUS_APPROVAL, User::REFUSED_STATUS_APPROVAL ]))
            return response()->json([ 'status' => 'error', 'message' => __('members.error.status-unavailable') ]);

        $user = User::select()
            ->with('club:code,name,primary_color,contact_mail')
            ->with('approval_history')
            ->with('approval_history.created_by')
            ->where('id', $user_id)
            ->where('club_code', getClubCode())
            ->where('deleted', false)
            ->where('type', 'member')
            ->first();

        $user->approval_status = $status;
        $user->approval_status_date = date('Y-m-d H:i:s');

        if ($request->has('refused_reason')) {
            $user->refused_reason = $request->get('refused_reason');
        }

        if ($request->has('member_class')) {
            $member_class = MemberClass::select()
                ->where('club_code', getClubCode())
                ->where('label', $request->get('member_class'))
                ->first();

            $user->member_class_id = $member_class->id;
        }

        $user->save();
        $user->saveApprovalHistory();

        if ($user->approval_status == User::APPROVED_STATUS_APPROVAL) {
            $user->createBankAccount();

            if (! $user->member_class_id) {
                $user->member_class_id = MemberClass::select()
                    ->where('default', true)
                    ->first()['id'];

                $user->save();
            }
        }

        // Send register mail
        if ($user->approval_status == User::APPROVED_STATUS_APPROVAL && ! empty($user->email))
        {
            try
            {
                Mail::to($user->email)
                    ->send(new \App\Mail\RegisterMail($user));
            } catch(\Exception $e) {
            }
        } else {
            try
            {
                Mail::to($user->email)
                    ->send(new \App\Mail\RepprovalMail($user));
            } catch(\Exception $e) {
            }
        }

        return response()->json([ 'status' => 'success', 'data' => (new UserWaitingApprovalResource($user)) ]);
    }
}
