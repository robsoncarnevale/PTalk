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
            // 'indicated_by' => 'required',
        ])) return $validator;
        
        $phone = preg_replace("#[^0-9]*#is", "", $request->get('phone'));

        // Check if phone is already registered
        $check_phone = User::select('id')
            ->where('phone', $phone)
            ->where('deleted', false)
            ->where('club_code', getClubCode())
            ->first();

        if ($check_phone) {
            return response()->json([ 'status' => 'error', 'message' => __('members.error-phone-already-registered') ]);
        }

        // Check if email is already registered
        if ($request->has('email') && ! empty($request->get('email'))) {
            $check_email = User::select('id')
                ->where('email', $request->get('email'))
                ->where('deleted', false)
                ->where('club_code', getClubCode())
                ->first();
        
            if ($check_email) {
                return response()->json([ 'status' => 'error', 'message' => __('members.error-email-already-registered') ]);
            }
        }

        // Get the indicator
        if ($request->has('indicated_by')) {
            $indicator = User::select()
                ->where('id', $request->get('indicated_by'))
                ->where('status', '<>', User::INACTIVE_STATUS)
                ->where('approval_status', User::APPROVED_STATUS_APPROVAL)
                ->where('deleted', false)
                ->where('club_code', getClubCode())
                ->first();
    
            if (! $indicator) {
                return response()->json([ 'status' => 'error', 'message' => __('members.error-indicator-not-found') ]);
            }
        }
        
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
            $user->password = '';
            // $user->approval_status = User::MEMBER_STEP_STATUS_APPROVAL;
            $user->approval_status = User::WAITING_STATUS_APPROVAL;

            if ($request->has('indicated_by')) {
                $user->indicated_by = $indicator->id;
            }
            
            if ($request->has('document_rg')) $user->document_rg = $request->get('document_rg');
            if ($request->has('phone')) $user->phone = preg_replace("#[^0-9]*#is", "", $request->get('phone'));
            if ($request->has('home_address')) $user->home_address = $request->get('home_address');
            if ($request->has('comercial_address')) $user->comercial_address = $request->get('comercial_address');
            if ($request->has('company')) $user->company = $request->get('company');
            if ($request->has('company_activities')) $user->company_activities = $request->get('company_activities');

            $user->getMobilePrivilege();

            $user->save();

            // Create Vehicle
            if ($request->has('vehicle'))
            {
                $vehicle = new Vehicle($request->get('vehicle'));
                $vehicle->carplate = $vehicle->carplate ? strtoupper($vehicle->carplate) : '';
                $vehicle->user_id = $user->id;
                $vehicle->club_code = $user->club_code;
                $vehicle->save();

                $user['vehicle'] = $vehicle;
            }

            if ($request->has('information')) {
                $information = new ParticipationRequestInformation();
                $information->fill($request->get('information'));
                $information->user_id = $user->id;
                $information->club_code = getClubCode();

                if ($request->has('information.vehicle_photo')) {
                    $file = $request->file('information.vehicle_photo');

                    $upload_photo = Storage::disk('images')->putFile(getClubCode().'/request-vehicle-photos', $file);

                    $information->vehicle_photo = $upload_photo;
                }

                $information->save();
            }

            DB::commit();

            if ($request->has('indicated_by')) {
                $sms = new \App\Http\Services\SmsService('aws_sns');
                $sms->send(55, $phone, 'Você foi indicado para fazer parte do ' . $user->club->name . '! Após aprovação acesse o clube baixando o app em [link-do-app]');
            }

            return response()->json([ 'status' => 'success', 'data' => (new UserResource($user)), 'message' => __('members.success-create') ]);
        } catch (Exception $e) {
            DB::rollback();

            return response()->json([ 'status' => 'error', 'message' => __('members.error-create', [ 'error' => $e->getMessage() ]) ]);
        }
    }

    /**
     * @since 30/08/2020
     */
    public function GetCodeToContinueRequest(Request $request) {
        $validator = self::validate($request, [
            'phone'  =>  'required|min:8|max:11',
            'club_code' => 'required',
        ]);

        if ($validator) {
            return $validator;
        }

        $phone = preg_replace("#[^0-9]*#is", "", $request->get('phone'));

        $user = User::select()
            ->with('indicator:id,name,photo')
            ->where('club_code', $request->get('club_code'))
            ->where('phone', $phone)
            ->where('approval_status', User::MEMBER_STEP_STATUS_APPROVAL)
            ->first();

        // Check if refer exists
        if (! $user) {
            return response()->json([ 'status' => 'error', 'message' => __('members.not-found') ], 404);
        }

        $user = $user->generateNewAccessCode();
        $user->save();

        $sms = new \App\Http\Services\SmsService('aws_sns');
        $sms->send(55, $phone, 'Seu código para continuar a solicitação para participar do ' . $user->club->name . ': ' . $user->getAccessCode());

        return response()->json([ 'status' => 'success', 'data' => (new UserResource($user)), 'message' => __('members.success-get-code') ]);
    }

    /**
     * @since 30/08/2020
     */
    public function ContinueReference(Request $request){
        $validator = self::validate($request, [
            'phone'  =>  'required|min:8|max:11',
            'club_code' => 'required',
            'code' => 'required|size:6',

            'name'  =>  'required',
            'email' => 'required',
            'cpf' => 'required',
            'home_address' => 'required',
        ]);

        if ($validator) {
            return $validator;
        }

        $phone = preg_replace('#[^0-9]#is', '', $request->get('phone'));

        if (empty($phone)) {
            return response()->json(['message' => __('auth.phone-invalid'), 'status' => 'error', 'code' => 'phone.invalid' ], 404);
        }

        $user = User::select()
            ->with('indicator:id,name,photo')
            ->where('club_code', $request->get('club_code'))
            ->where('phone', $phone)
            ->where('approval_status', User::MEMBER_STEP_STATUS_APPROVAL)
            ->first();

        if (! $user) {
            return response()->json([ 'status' => 'error', 'message' => __('members.not-found') ], 404);
        }

        if (! $user->testAccessCode($request->get('code'))) {
            return response()->json(['message' => __('auth.code-invalid'), 'status' => 'error', 'code' => 'code.invalid' ], 401);
        }

        try {
            $user->fill($request->all());
            $user->approval_status = User::WAITING_STATUS_APPROVAL;
            $user->access_code = null; // Delete last access code
            $user->save();

            DB::commit();

            return response()->json([ 'status' => 'success', 'data' => (new UserResource($user)), 'message' => __('members.success-continue') ]);
        } catch (\Exception $e) {
            DB::rollback();

            return response()->json([ 'status' => 'error', 'message' => __('members.error-continue', [ 'error' => $e->getMessage() ]) ]);
        }
    }
}
