<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

use App\Models\User;
use App\Models\Vehicle;

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
        $users = User::select('id', 'name', 'email', 'photo', 'privilege_id', 'created_at', 'updated_at', 'active')
            // ->with('privilege_group')
            ->with('privilege_group:id,name')
            ->withCount('vehicles')
            ->where('club_code', getClubCode())
            ->where('deleted', false)
            ->where('approval_status', 'approved')
            ->where('type', $type)
            ->orderBy('name')
            ->jsonPaginate($per_page, 3);

        return response()->json([ 'status' => 'success', 'data' => $users ]);
    }

    public function ListAll(Request $request)
    {
        $users = User::select('id', 'name', 'photo', 'privilege_id', 'company', 'company_activities', 'created_at', 'updated_at')
            // ->with('privilege_group')
            ->with('privilege_group:id,name')
            ->where('club_code', getClubCode())
            ->where('deleted', false)
            ->where('active', true)
            ->where('approval_status', 'approved')
            ->where('id', '<>', Auth::guard()->user()->id)
            ->orderBy('name')
            ->jsonPaginate(25, 3);

        return response()->json([ 'status' => 'success', 'data' => $users ]);
    }

    /**
     * Create
     *
     * @author Davi Souto
     * @since 08/06/2020
     */
    private static function Create(Request $request, $type = 'member')
    {
        if ($validator = self::validate($request, [
            'document_cpf'  =>  'required|size:11',
            'name'  =>  'required',
            'cell_phone'  =>  'required|min:8|max:11',
            'email'  =>  'required|email',
            'privilege_id'  =>  'required|integer',
        ])) return $validator;

        $user = new User();

        try
        {
            DB::beginTransaction();

            $user->club_code = getClubCode();

            $user->document_cpf = preg_replace("#[^0-9]*#is", "", $request->get('document_cpf'));
            $user->name = $request->get('name');
            $user->cell_phone = preg_replace("#[^0-9]*#is", "", $request->get('cell_phone'));
            $user->email = $request->get('email');
            $user->type = $type;

            $user->approval_status = 'approved';
            $user->approval_status_date = date('Y-m-d H:i:s');

            $user->password = Hash::make('123456');
            $user->privilege_id = $request->get('privilege_id');
            $user->active = true;
            
            if ($request->has('document_rg')) $user->document_rg = $request->get('document_rg');
            if ($request->has('phone')) $user->phone = preg_replace("#[^0-9]*#is", "", $request->get('phone'));
            if ($request->has('home_address')) $user->home_address = $request->get('home_address');
            if ($request->has('comercial_address')) $user->comercial_address = $request->get('comercial_address');
            if ($request->has('company')) $user->company = $request->get('company');
            if ($request->has('company_activities')) $user->company_activities = $request->get('company_activities');

            // Photo upload
            if ($request->has('photo'))
            {
                $upload_photo = Storage::disk('images')->putFile('photos', $request->file('photo'));

                if ($upload_photo)
                    $user->photo = $upload_photo;
            }

            $user->save();

            // Create Vehicle
            if ($request->has('vehicle'))
            {
                $vehicle = new Vehicle($request->get('vehicle'));
                $vehicle->user_id = $user->id;
                $vehicle->club_code = $user->club_code;
                $vehicle->save();

                $user['vehicle'] = $vehicle;
            }

            DB::commit();

            return response()->json([ 'status' => 'success', 'data' => $user, 'message' => __(self::$type_name . '.success-create') ]);
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
    private static function Update(Request $request, $user_id, $type = 'member')
    {
        $user = User::select()
            ->where('id', $user_id)
            ->where('club_code', getClubCode())
            ->where('type', $type)
            ->first();

        try
        {
            if (! $user)
                return response()->json([ 'status' => 'error', 'message' => 'User not found' ]);

            if ($request->has('privilege_id')) $user->privilege_id = $request->get('privilege_id');
            if ($request->has('document_cpf')) $user->document_cpf = preg_replace("#[^0-9]*#is", "", $request->get('document_cpf'));
            if ($request->has('name')) $user->name = $request->get('name');
            if ($request->has('cell_phone')) $user->cell_phone = preg_replace("#[^0-9]*#is", "", $request->get('cell_phone'));
            if ($request->has('email')) $user->email = $request->get('email');
            if ($request->has('document_rg')) $user->document_rg = $request->get('document_rg');
            if ($request->has('phone')) $user->phone = preg_replace("#[^0-9]*#is", "", $request->get('phone'));
            if ($request->has('home_address')) $user->home_address = $request->get('home_address');
            if ($request->has('comercial_address')) $user->comercial_address = $request->get('comercial_address');
            if ($request->has('company')) $user->company = $request->get('company');
            if ($request->has('company_activities')) $user->company_activities = $request->get('company_activities');

            // Photo remove and upload
            if ($request->has('remove_photo') && $request->get('remove_photo') == 'true')
            {
                if (! empty($user->photo) && Storage::disk('images')->exists($user->photo))
                    Storage::disk('images')->delete($user->photo);

                $user->photo = null;
            } else if ($request->has('photo'))
            {
                $upload_photo = Storage::disk('images')->putFile('photos', $request->file('photo'));

                if ($upload_photo)
                {
                    if (! empty($user->photo) && Storage::disk('images')->exists($user->photo))
                        Storage::disk('images')->delete($user->photo);

                    $user->photo = $upload_photo;
                }
            }

            $user->save();
        } catch(\Exception $e) {
            return response()->json([ 'status' => 'error', 'message' => __(self::$type_name . '.error-update', [ 'error' => $e->getMessage() ]) ]);
        }

        $vehicles = Vehicle::select()
            ->with('car_model:id,name,car_brand_id', 'car_model.car_brand:id,name', 'car_color:id,name')
            ->where('user_id', $user->id)
            ->get();

        $user->vehicles = $vehicles;

        return response()->json([ 'status' => 'success', 'data' => $user, 'message' => __(self::$type_name . '.success-update') ]);
    }

    /**
     * Delete
     *
     * @author Davi Souto
     * @since 07/06/2020
     */
    private static function Delete(Request $request, $user_id, $type = 'member')
    {
        $user = User::select('id', 'deleted')
            ->where('id', $user_id)
            ->where('club_code', getClubCode())
            ->where('type', $type)
            ->first();

        if (! $user)
            return response()->json([ 'status' => 'error', 'message' => __(self::$type_name . '.not-found') ]);

        $user->deleted = true;
        $user->save();

        return response()->json([ 'status' => 'success', 'data' => $user ]);
    }

    /**
     * Get
     * 
     * @author Davi Souto
     * @since 07/06/2020
     */
    private static function Get(Request $request, $user_id, $type = 'members')
    {
        $user = User::select('id', 'name', 'email', 'privilege_id', 'photo', 'document_cpf', 'document_rg', 'cell_phone', 'phone', 'home_address', 'comercial_address', 'company', 'company_activities', 'created_at', 'updated_at', 'active')
            ->with('vehicles', 'vehicles.car_model:id,name,car_brand_id', 'vehicles.car_model.car_brand:id,name', 'vehicles.car_color:id,name')
            ->where('id', $user_id)
            ->where('club_code', getClubCode())
            ->where('deleted', false)
            ->where('type', $type)
            ->first();

        if (! $user)
            return response()->json([ 'status' => 'error', 'message' => __(self::$type_name . '.not-found') ]);

        return response()->json([ 'status' => 'success', 'data' => $user ]);
    }

    /**
     * Returns user logged data
     *
     * @author Davi Souto
     * @since 23/05/2020
     */
    private static function Me(Request $request)
    {
        $user = Auth::guard()->user();

        return response()->json([ 'status' => 'success', 'data' => $user  ]);
    }
    
    /**
     * Returns users profile data
     *
     * @author Davi Souto
     * @since 17/06/2020
     */
    public function ViewProfile(Request $request, $user_id)
    {
        $user = User::select('id', 'name', 'privilege_id', 'photo', 'company', 'company_activities', 'created_at', 'updated_at', 'active', 'type')
            ->where('id', $user_id)
            ->where('club_code', getClubCode())
            ->where('deleted', false)
            ->where('active', true)
            ->where('approval_status', 'approved')
            ->first();

        if (! $user)
            return response()->json([ 'status' => 'error', 'message' => __('members.not-found') ]);

        return response()->json([ 'status' => 'success', 'data' => $user ]);
    }
}
