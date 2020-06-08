<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

use App\Models\User;

use Exception;

/**
 * Admin Users Controller
 *
 * @author Davi Souto
 * @since 23/05/2020
 */
class AdminUsersController extends Controller
{    
    /**
     * List Administrator
     *
     * @author Davi Souto
     */
    public function List(Request $request)
    {
        $users = User::select('id', 'name', 'email', 'privilege_id', 'created_at', 'updated_at')
            // ->with('privilege_group')
            ->with('privilege_group:id,name')
            ->where('club_code', getClubCode())
            ->jsonPaginate(25, 3);

        return response()->json([ 'status' => 'success', 'data' => $users ]);
    }

    /**
     * Create Administrator
     *
     * @author Davi Souto
     * @since 08/06/2020
     */
    public function Create(Request $request)
    {
        if ($validator = $this->validate($request, [
            'document_cpf'  =>  'required|size:11',
            'name'  =>  'required',
            'cell_phone'  =>  'required|min:8|max:11',
            'email'  =>  'required|email',
            'privilege_id'  =>  'required|integer',
        ])) return $validator;

        $user = new User();

        try
        {
            $user->club_code = getClubCode();

            $user->document_cpf = $request->get('document_cpf');
            $user->name = $request->get('name');
            $user->cell_phone = $request->get('cell_phone');
            $user->email = $request->get('email');

            $user->password = Hash::make('123456');
            $user->privilege_id = $request->get('privilege_id');
            
            if ($request->get('rg')) $user->rg = $request->get('rg');
            if ($request->get('phone')) $user->phone = $request->get('phone');
            if ($request->get('home_address')) $user->home_address = $request->get('home_address');
            if ($request->get('comercial_address')) $user->comercial_address = $request->get('comercial_address');
            if ($request->get('company')) $user->company = $request->get('company');
            if ($request->get('company_activities')) $user->company_activities = $request->get('company_activities');

            $user->save();

            return response()->json([ 'status' => 'success', 'data' => $user, 'message' => __('administrators.success-create') ]);
        } catch (Exception $e) {
            return response()->json([ 'status' => 'error', 'message' => __('administrators.error-create', [ 'error' => $e->getMessage() ]) ]);
        }

    }

    /**
     * Update Administrator
     *
     * @author Davi Souto
     * @since 07/06/2020
     */
    public function Update(Request $request, $user_id)
    {
        $user = User::select()
            ->where('id', $user_id)
            ->where('club_code', getClubCode())
            ->first();

        if (! $user)
            return response()->json([ 'status' => 'error', 'message' => 'User not found' ]);

        if ($request->get('privilege_id')) $user->privilege_id = $request->get('privilege_id');
        if ($request->get('document_cpf')) $user->document_cpf = $request->get('document_cpf');
        if ($request->get('name')) $user->name = $request->get('name');
        if ($request->get('cell_phone')) $user->cell_phone = $request->get('cell_phone');
        if ($request->get('email')) $user->email = $request->get('email');
        if ($request->get('rg')) $user->rg = $request->get('rg');
        if ($request->get('phone')) $user->phone = $request->get('phone');
        if ($request->get('home_address')) $user->home_address = $request->get('home_address');
        if ($request->get('comercial_address')) $user->comercial_address = $request->get('comercial_address');
        if ($request->get('company')) $user->company = $request->get('company');
        if ($request->get('company_activities')) $user->company_activities = $request->get('company_activities');

        $user->save();

        return response()->json([ 'status' => 'success', 'data' => $user, 'message' => __('administrators.success-update') ]);
    }

    /**
     * Delete Administrator
     *
     * @author Davi Souto
     */
    public function Delete(Request $request)
    {

    }

    /**
     * Get Administrator
     * 
     * @author Davi Souto
     * @since 07/06/2020
     */
    public function Get(Request $request, $user_id)
    {
        $user = User::select('id', 'name', 'email', 'privilege_id', 'photo', 'document_cpf', 'document_rg', 'cell_phone', 'phone', 'home_address', 'comercial_address', 'company', 'company_activities', 'created_at', 'updated_at')
            ->where('id', $user_id)
            ->where('club_code', getClubCode())
            ->first();

        if (! $user)
            return response()->json([ 'status' => 'error', 'message' => __('administrators.not-found') ]);

        return response()->json([ 'status' => 'success', 'data' => $user ]);
    }

    /**
     * Returns user logged data
     *
     * @author Davi Souto
     * @since 23/05/2020
     */
    public function Me(Request $request)
    {
        $user = Auth::guard()->user();

        return response()->json([ 'status' => 'success', 'data' => $user  ]);
    }
}
