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
use App\Models\Privilege;

use App\Http\Resources\AdministratorsResource;

use DB;
use Exception;

class AdminUsersController extends Controller
{
    protected $only_admin = true;

    public function List(Request $request)
    {
        return UsersController::List($request, 'admin');
    }

    public function Create(UserRequest $request)
    {
        DB::beginTransaction();

        try
        {
            $password = bcrypt(uniqid());

            $logged = auth()->user();

            $check = User::where('email', $request->email)
                                ->orWhere('document_cpf', $request->document_cpf)
                                ->orWhere('phone', $request->phone)
                                ->first();

            if($check)
            {
                if($check->email == $request->email)
                    throw new \Exception(__('administrators.error-email-already-registered'));

                if($check->document_cpf == $request->document_cpf)
                    throw new \Exception(__('administrators.error-cpf-already-registered'));

                if($check->phone == $request->phone)
                    throw new \Exception(__('administrators.error-phone-already-registered'));
            }

            $request->merge([
                'club_code' => 'porsche_talk',
                'password' => $password,
                'type' => 'admin'
            ]);

            $user = User::create($request->only(
                'club_code',
                'name',
                'nickname',
                'email',
                'phone',
                'document_cpf',
                'status',
                'password',
                'type'
            ));

            if(!$user)
                throw new \Exception(__('general.generic.error.create'));

            $this->applyPrivileges($request, $user, $logged);

            DB::commit();

            return response()->json(['status' => 'success', 'message' => __('general.generic.create', ['attribute' => 'Usuário'])]);
        }
        catch(\Exception $e)
        {
            DB::rollback();

            return response()->json(['status' => 'error', 'message' => $e->getMessage()], 500);
        }
    }

    public function Update(UserRequest $request, $user_id)
    {
        DB::beginTransaction();

        try
        {
            $logged = auth()->user();

            $user = User::where('id', $user_id)
                        ->where('type', 'admin')
                        ->first();

            if(!$user)
                throw new \Exception(__('auth.user-not-found'));

            /* Melhorar essa query */

            $check = User::where('email', $request->email)
                                ->orWhere('document_cpf', $request->document_cpf)
                                ->orWhere('phone', $request->phone)
                                ->where('id', '!=', $user->id)
                                ->first();

            if($check)
            {
                if($check->email == $request->email)
                    throw new \Exception(__('administrators.error-email-already-registered'));

                if($check->document_cpf == $request->document_cpf)
                    throw new \Exception(__('administrators.error-cpf-already-registered'));

                if($check->phone == $request->phone)
                    throw new \Exception(__('administrators.error-phone-already-registered'));
            }

            $update = $user->update($request->only(
                'name',
                'nickname',
                'status',
                'email',
                'phone',
                'document_cpf'
            ));

            if(!$update)
                throw new \Exception(__('general.generic.error.update'));

            $this->applyPrivileges($request, $user, $logged);

            DB::commit();

            return response()->json(['status' => 'success', 'message' => __('general.generic.success.update', ['attribute' => 'Usuário'])]);
        }
        catch(\Exception $e)
        {
            DB::rollback();

            return response()->json(['status' => 'error', 'message' => $e->getMessage()], 500);
        }
    }

    public function Get(Request $request, $user_id)
    {
        $user = User::where('id', $user_id)
                    ->where('type', 'admin')
                    ->first();

        if(!$user)
            return response()->json(['status' => 'error', 'message' => __('auth.user-not-found')], 404);

        return new AdministratorsResource($user);
    }

    /* Funções */

    public function applyPrivileges(Request $request, $user, $logged)
    {
        /* Regra que só permite alterar permissões de outros usuário e não do próprio. */

        if(isset($request->privileges) && $logged->id != $user->id)
        {
            $privileges = Privilege::whereIn('id', $request->privileges)->get();

            if($privileges->count() < count($request->privileges))
                throw new \Exception(__('administrators.invalid-permission'));

            DB::table('user_privileges')
                ->where('user_id', $user->id)
                ->delete();

            $permitted = $logged->privileges->pluck('id')->toArray();

            foreach($request->privileges as $privilege)
            {
                if(!in_array($privilege, $permitted))
                    continue;

                DB::table('user_privileges')
                    ->insert([
                        'user_id' => $user->id,
                        'privilege_id' => $privilege
                    ]);
            }
        }
    }
}
