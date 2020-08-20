<?php

namespace App\Http\Controllers\Mobile;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

use App\Models\User;
use App\Models\Vehicle;
use App\Http\Resources\User as UserResource;
use App\Http\Resources\UserCollection;;

use DB;
use Exception;

/**
 * Main Users Controller
 *
 * @author Davi Souto
 * @since 15/06/2020
 */
class MembersController extends Controller
{
    /**
     * Get member logged data
     * 
     * @author Davi Souto
     * @since 01/08/2020
     */
    public function Me(Request $request)
    {
        $session = User::getMobileSession();

        $user = User::select()
            ->where('id', $session->id)
            ->first();

        return response()->json([ 'status' => 'success', 'data' => (new UserResource($user))  ]);
    }

    /**
     * Update member profile
     * 
     * @author Davi Souto
     * @since 03/08/2020
     */
    public function UpdateProfile(Request $request)
    {
        $session = User::getMobileSession();

        $user = User::select()
            ->where('id', $session->id)
            ->first();

        $user->fill($request->all());
        $user->document_cpf = preg_replace("#[^0-9]*#is", "", $user->document_cpf);
        $user->phone = preg_replace("#[^0-9]*#is", "", $user->phone);
        
        if ($request->has('photo'))
            $user->upload($request->file('photo'));

        $user->update();

        return response()->json([ 'status' => 'success', 'data' => (new UserResource($user)) ]);
    }

     /**
     * Create
     *
     * @author Davi Souto
     * @since 08/06/2020
     */
    public function CreateFromReference(Request $request, $type = 'member')
    {
        if ($validator = self::validate($request, [
            'name'  =>  'required',
            'phone'  =>  'required|min:8|max:11',
        ])) return $validator;
        
        $phone = preg_replace("#[^0-9]*#is", "", $request->get('phone'));

        // Check if phone is already registered
        if (User::select('id')->where('phone', $phone)->first())
            return response()->json([ 'status' => 'error', 'message' => __('members.error-phone-already-registered') ]);

        // Check if email is already registered
        if ($request->has('email') && ! empty($request->get('email')) && User::select('id')->where('email', $request->get('email'))->first())
            return response()->json([ 'status' => 'error', 'message' => __('members.error-email-already-registered') ]);
        
        $user = new User();

        try
        {
            DB::beginTransaction();

            $user->club_code = getClubCode();

            $user->document_cpf = preg_replace("#[^0-9]*#is", "", $request->get('document_cpf'));
            $user->name = $request->get('name');
            $user->phone = $phone;
            $user->email = $request->has('email') ? $request->get('email') : null;
            $user->type = User::TYPE_MEMBER;
            $user->status = User::ACTIVE_STATUS;
            $user->approval_status = User::APPROVED_STATUS_APPROVAL;
            
            if ($request->has('document_rg')) $user->document_rg = $request->get('document_rg');
            if ($request->has('phone')) $user->phone = preg_replace("#[^0-9]*#is", "", $request->get('phone'));
            if ($request->has('home_address')) $user->home_address = $request->get('home_address');
            if ($request->has('comercial_address')) $user->comercial_address = $request->get('comercial_address');
            if ($request->has('company')) $user->company = $request->get('company');
            if ($request->has('company_activities')) $user->company_activities = $request->get('company_activities');

            $user->generatePassword();
            $user->getMobilePrivilege();

            $user->save();

            // Create Vehicle
            if ($request->has('vehicle') && $request->has('vehicle.car_model_id'))
            {
                // Test vehicle
                $vehicle = new Vehicle($request->get('vehicle'));
                $vehicle->carplate = $vehicle->carplate ? strtoupper($vehicle->carplate) : '';
                $vehicle->user_id = $user->id;
                $vehicle->club_code = $user->club_code;
                $vehicle->save();

                $user['vehicle'] = $vehicle;
            }

            DB::commit();

            $sms = new \App\Http\Services\SmsService('aws_sns');
            $sms->send(55, $phone, 'Você foi indicado para fazer parte do ' . $user->club->name . '! Complete sua solicitação baixando o app [link-do-app]');

            return response()->json([ 'status' => 'success', 'data' => (new UserResource($user)), 'message' => __('members.success-create') ]);
        } catch (Exception $e) {
            DB::rollback();

            return response()->json([ 'status' => 'error', 'message' => __('members.error-create', [ 'error' => $e->getMessage() ]) ]);
        }
    }
}
