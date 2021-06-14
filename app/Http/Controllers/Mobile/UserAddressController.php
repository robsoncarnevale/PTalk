<?php

namespace App\Http\Controllers\Mobile;

use Illuminate\Http\Request;

use App\Models\User;
use App\Models\UserAddress;

use App\Http\Resources\UserAddress as UserAddressResource;

use DB;
use Exception;

/**
 * User Address Mobile Controller
 *
 * @author Davi Souto
 * @since 02/03/2020
 */
class UserAddressController extends Controller
{
    protected $only_admin = false;

    public function List(Request $request, User $user)
    {
        return (new \App\Http\Controllers\UserAddressController)->List($request, $user);
    }

    public function Get(Request $request, User $user, UserAddress $address)
    {
        return (new \App\Http\Controllers\UserAddressController)->Get($request, $user, $address);
    }

    public function Create(Request $request, User $user)
    {
        return (new \App\Http\Controllers\UserAddressController)->Create($request, $user);
    }

    public function Update(Request $request, User $user, UserAddress $address)
    {
        return (new \App\Http\Controllers\UserAddressController)->Update($request, $user, $address);
    }

    public function Delete(Request $request, User $user, UserAddress $address)
    {
        return (new \App\Http\Controllers\UserAddressController)->Delete($request, $user, $address);
    }

    //////////////////

    public function ListMyAddresses(Request $request)
    {
        return (new \App\Http\Controllers\UserAddressController)->ListMyAddresses($request);
    }

    public function GetMyAddress(Request $request, UserAddress $address)
    {
        return (new \App\Http\Controllers\UserAddressController)->GetMyAddress($request, $address);
    }

    public function CreateMyAddress(Request $request)
    {
        return (new \App\Http\Controllers\UserAddressController)->CreateMyAddress($request);
    }

    public function UpdateMyAddress(Request $request)
    {
        DB::beginTransaction();
        
        try {
            $type = $request->get('type');

            if (! $type) {
                return response()->json([ 'status' => 'error', 'message' => 'Informe o tipo do endereço' ]);   
            }

            // Remove old address
            UserAddress::select()
                ->where('club_code', getClubCode())
                ->where('user_id', User::getAuthenticatedUserId())
                ->where('address_type', $type)
                ->delete();

            $address = new UserAddress();
            
            $address->fill($request->all());
            $address->address_type = $type;

            $address->save();

            DB::commit();
        } catch (\Exception $e){
            DB::rollback();

            return response()->json([ 'status' => 'error', 'message' => 'Erro ao atualizar endereço: ' . $e->getMessage() ]);
        }

        $address = $address->fill($request->all());
        $address->save();

        return response()->json([ 'status' => 'success', 'data' => new UserAddressResource($address) ]);
    }

    public function DeleteMyddress(Request $request, UserAddress $address)
    {
        return (new \App\Http\Controllers\UserAddressController)->DeleteMyAddress($request, $address);
    }

}
