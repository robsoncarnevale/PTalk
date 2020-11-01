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

use App\Http\Resources\Profile as ProfileResource;
use App\Http\Resources\User as UserResource;
use App\Http\Resources\UserCollection;

use DB;
use Exception;

/**
 * Main Users Controller
 *
 * @author Davi Souto
 * @since 15/06/2020
 */
class UsersController extends Controller
{
    protected $only_admin = false;

    /**
     * @var string
     */
    public static $type_name = 'member';

    /**
     * Magic Method __callStatic
     * @author Davi Souto
     * @since  15/06/2019
     * @return function
     */
    public static function __callStatic($name, $params)
    {
        if (count($params) > 0)
        {
            if (is_string($params[count($params)-1]))
                self::$type_name = $params[count($params)-1];
        }

        self::$type_name = (self::$type_name == 'admin') ? 'administrator' : self::$type_name;
        self::$type_name .= "s";

        if(method_exists(__CLASS__, $name)) 
            return call_user_func_array(array(__CLASS__, $name), $params);

        throw new Exception("{$name} não é uma função válida da classe Api");
    }

    /**
     * List
     *
     * @author Davi Souto
     */
    private static function List(Request $request, $type = 'member', $per_page = 25)
    {
        $users = User::select()
            // ->with('privilege_group')
            ->with('privilege_group:id,name')
            ->withCount(['vehicles' => function($q){
                $q->where('deleted', false);
            }])
            ->with(['vehicles' => function($q){
                $q->where('deleted', false);
            }])
            ->where('club_code', getClubCode())
            ->where('deleted', false)
            ->where('approval_status', 'approved')
            ->where('type', $type)
            ->orderBy('name')
            ->jsonPaginate($per_page, 3);

        return response()->json([ 'status' => 'success', 'data' => (new UserCollection($users)) ]);
    }

    public function ListAll(Request $request)
    {
        $users = User::select()
            // ->with('privilege_group')
            ->with('privilege_group:id,name')
            ->where('club_code', getClubCode())
            ->where('deleted', false)
            ->where('status', '<>', User::INACTIVE_STATUS)
            ->where('status', '<>', User::BANNED_STATUS)
            ->where('approval_status', 'approved')
            ->where('id', '<>', Auth::guard()->user()->id)
            ->orderBy('name')
            ->jsonPaginate(25, 3);

        return response()->json([ 'status' => 'success', 'data' => (new UserCollection($users)) ]);
    }

    /**
     * Create
     *
     * @author Davi Souto
     * @since 08/06/2020
     */
    private static function Create(UserRequest $request, $type = 'member')
    {
        $phone = preg_replace("#[^0-9]*#is", "", $request->get('phone'));

        // Check if number is in blacklist
        $blacklist = \App\Models\Blacklist::select()
            ->where('club_code', $club_code)
            ->where('phone', $phone)
            ->where('status', \App\Models\Blacklist::BLOCKED_STATUS)
            ->first();

        if ($blacklist) {
            return response()->json([ 'status' => 'error', 'message' => __('members.error-number-in-blacklist') ]);
        }

        // Check if phone is already registered
        $check_phone = User::select('id')
            ->where('phone', $phone)
            ->where('deleted', false)
            ->where(function($q){
                $q->where('approval_status', User::WAITING_STATUS_APPROVAL)
                  ->orWhere('approval_status', User::APPROVED_STATUS_APPROVAL);
          })
            ->where('club_code', getClubCode())
            ->first();

        if ($check_phone) {
            return response()->json([ 'status' => 'error', 'message' => __('members.error-phone-already-registered') ]);
        }

        // Check if email is already registered
        $check_email = User::select('id')
            ->where('email', $request->get('email'))
            ->where('deleted', false)
            ->where('club_code', getClubCode())
            ->first();
    
        if ($check_email) {
            return response()->json([ 'status' => 'error', 'message' => __('members.error-email-already-registered') ]);
        }

        $user = new User();

        try
        {
            DB::beginTransaction();

            $user->club_code = getClubCode();

            $user->document_cpf = preg_replace("#[^0-9]*#is", "", $request->get('document_cpf'));
            $user->name = $request->get('name');
            $user->phone = $phone;
            $user->email = $request->get('email');
            $user->type = $type;

            $user->approval_status = 'approved';
            $user->approval_status_date = date('Y-m-d H:i:s');

            $user->password = Hash::make('123456');
            $user->privilege_id = $request->get('privilege_id');
            $user->status = User::ACTIVE_STATUS;

            $user->new_password_token = md5(uniqid(rand(), true));
            $user->new_password_token_duration = date('Y-m-d H:i:s', strtotime("+1 day"));
            
            if ($request->has('document_rg')) $user->document_rg = $request->get('document_rg');
            if ($request->has('phone')) $user->phone = preg_replace("#[^0-9]*#is", "", $request->get('phone'));
            if ($request->has('home_address')) $user->home_address = $request->get('home_address');
            if ($request->has('comercial_address')) $user->comercial_address = $request->get('comercial_address');
            if ($request->has('company')) $user->company = $request->get('company');
            if ($request->has('company_activities')) $user->company_activities = $request->get('company_activities');
            if ($request->has('nickname')) $user->nickname = $request->get('nickname');
            if ($request->has('member_class_id')) $user->member_class_id = $request->get('member_class_id');

            // Photo upload
            if ($request->has('photo'))
                $user->upload($request->file('photo'));

            $user->save();
            $user->saveStatusHistory();

            // Create Vehicle
            // if ($request->has('vehicle'))
            // {
            //     $vehicle = new Vehicle($request->get('vehicle'));
            //     $vehicle->carplate = strtoupper($vehicle->carplate);
            //     $vehicle->user_id = $user->id;
            //     $vehicle->club_code = $user->club_code;
            //     $vehicle->save();

            //     $user['vehicle'] = $vehicle;
            // }

            DB::commit();

            try
            {
                Mail::to($user->email)
                    ->send(new \App\Mail\RegisterMail($user));
            } catch(\Exception $e) {

            }

            return response()->json([ 'status' => 'success', 'data' => (new UserResource($user)), 'message' => __(self::$type_name . '.success-create') ]);
        } catch (Exception $e) {
            DB::rollback();

            return response()->json([ 'status' => 'error', 'message' => __(self::$type_name . '.error-create', [ 'error' => $e->getMessage() ]) ]);
        }

    }

    /**
     * Update
     *
     * @author Davi Souto
     * @since 07/06/2020
     */
    private static function Update(UserRequest $request, $user_id, $type = 'member')
    {
        $user = User::select()
            ->where('id', $user_id)
            ->where('club_code', getClubCode())
            ->where('type', $type)
            ->first();

        // Check if phone is already registered
        if ($request->has('phone')) {
            $check_phone = User::select('id')
                ->where('phone', preg_replace("#[^0-9]*#is", "", $request->get('phone')))
                ->where('deleted', false)
                ->where('club_code', getClubCode())
                ->where('id', '<>', $user_id)
                ->first();
    
            if ($check_phone) {
                return response()->json([ 'status' => 'error', 'message' => __('members.error-phone-already-registered') ]);
            }
        }

        // Check if email is already registered
        if ($request->has('email')) {
            $check_email = User::select('id')
                ->where('email', $request->get('email'))
                ->where('deleted', false)
                ->where('club_code', getClubCode())
                ->where('id', '<>', $user_id)
                ->first();
        
            if ($check_email) {
                return response()->json([ 'status' => 'error', 'message' => __('members.error-email-already-registered') ]);
            }
        }

        try
        {
            DB::beginTransaction();

            if (! $user)
                return response()->json([ 'status' => 'error', 'message' => 'User not found' ]);

            if ($request->has('privilege_id')) $user->privilege_id = $request->get('privilege_id');
            if ($request->has('document_cpf')) $user->document_cpf = preg_replace("#[^0-9]*#is", "", $request->get('document_cpf'));
            if ($request->has('name')) $user->name = $request->get('name');
            if ($request->has('phone')) $user->phone = preg_replace("#[^0-9]*#is", "", $request->get('phone'));
            if ($request->has('email')) $user->email = $request->get('email');
            if ($request->has('document_rg')) $user->document_rg = $request->get('document_rg');
            if ($request->has('home_address')) $user->home_address = $request->get('home_address');
            if ($request->has('comercial_address')) $user->comercial_address = $request->get('comercial_address');
            if ($request->has('company')) $user->company = $request->get('company');
            if ($request->has('company_activities')) $user->company_activities = $request->get('company_activities');
            if ($request->has('nickname')) $user->nickname = $request->get('nickname');
            if ($request->has('member_class_id')) $user->member_class_id = $request->get('member_class_id');

            if ($request->has('status'))
            {
                $user->status = $request->get('status');

                if ($request->has('status_reason'))
                    $user->status_reason = $request->get('status_reason');

                if ($user->status == User::SUSPENDED_STATUS && $request->has('suspended_time'))
                {
                    $user->suspended_time = $request->get('suspended_time');
                } else {
                    $user->suspended_time = null;
                }

                if ($user->status == User::ACTIVE_STATUS)
                    $user->status_reason = null;
            }

            // Photo remove and upload
            if ($request->has('remove_photo') && $request->get('remove_photo') == 'true')
            {
                if (! empty($user->photo) && Storage::disk('images')->exists($user->photo))
                    Storage::disk('images')->delete($user->photo);

                $user->photo = null;
            } else if ($request->has('photo'))
            {
                if ($request->has('photo'))
                    $user->upload($request->file('photo'));
            }

            $user->save();
            $user->saveStatusHistory();

            DB::commit();
        } catch(\Exception $e) {
            DB::rollback();

            return response()->json([ 'status' => 'error', 'message' => __(self::$type_name . '.error-update', [ 'error' => $e->getMessage() ]) ]);
        }

        $vehicles = Vehicle::select()
            ->with('car_model:id,name,car_brand_id,picture', 'car_model.car_brand:id,name', 'car_color:id,name')
            ->where('user_id', $user->id)
            ->where('deleted', false)
            ->get();

        $user->vehicles = $vehicles;

        return response()->json([ 'status' => 'success', 'data' => (new UserResource($user)), 'message' => __(self::$type_name . '.success-update') ]);
    }

    /**
     * Delete
     *
     * @author Davi Souto
     * @since 07/06/2020
     */
    private static function Delete(Request $request, $user_id, $type = 'member')
    {
        $user = User::select()
            ->where('id', $user_id)
            ->where('club_code', getClubCode())
            ->where('type', $type)
            ->first();

        if (! $user)
            return response()->json([ 'status' => 'error', 'message' => __(self::$type_name . '.not-found') ]);

        $user->deleted = true;
        $user->email = null;
        $user->phone = null;
        $user->save();

        return response()->json([ 'status' => 'success', 'data' => (new UserResource($user)) ]);
    }

    /**
     * Get
     * 
     * @author Davi Souto
     * @since 07/06/2020
     */
    private static function Get(Request $request, $user_id, $type = 'members')
    {
        $user = User::select()
            ->with(['vehicles' => function($q){
                $q->where('deleted', false);
            }, 'vehicles.car_model:id,name,car_brand_id,picture', 'vehicles.car_model.car_brand:id,name', 'vehicles.car_color:id,name,value'])
            ->where('id', $user_id)
            ->where('club_code', getClubCode())
            ->where('deleted', false)
            ->where('type', $type)
            ->first();

        if (! $user)
            return response()->json([ 'status' => 'error', 'message' => __(self::$type_name . '.not-found') ]);

        return response()->json([ 'status' => 'success', 'data' => (new UserResource($user)) ]);
    }

    /**
     * Returns user logged data
     *
     * @author Davi Souto
     * @since 23/05/2020
     */
    public static function Me(Request $request)
    {
        $user = User::select()
            ->where('club_code', getClubCode())
            ->where('id', Auth::guard()->user()->id)
            ->first();

        return response()->json([ 'status' => 'success', 'data' => (new UserResource($user))  ]);
    }
    
    /**
     * Returns users profile data
     *
     * @author Davi Souto
     * @since 17/06/2020
     */
    public function ViewProfile(Request $request, $user_id)
    {
        $user = User::select()
            ->with(['vehicles' => function($q){
                $q->where('deleted', false);
            }])
            ->where('id', $user_id)
            ->where('club_code', getClubCode())
            ->where('deleted', false)
            ->where('status', '<>', User::INACTIVE_STATUS)
            ->where('status', '<>', User::BANNED_STATUS)
            ->where('approval_status', User::APPROVED_STATUS_APPROVAL)
            ->first();

        if (! $user)
            return response()->json([ 'status' => 'error', 'message' => __('members.not-found') ]);

        return response()->json([ 'status' => 'success', 'data' => (new ProfileResource($user)) ]);
    }
}
