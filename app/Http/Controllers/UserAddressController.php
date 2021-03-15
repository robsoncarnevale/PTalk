<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\User;
use App\Models\UserAddress;

use App\Http\Resources\UserAddress as UserAddressResource;

use DB;
use Exception;
use Auth;

/**
 * User Address Controller
 *
 * @author Davi Souto
 * @since 16/11/2020
 */
class UserAddressController extends Controller
{
    protected $only_admin = false;

    public function List(Request $request, User $user)
    {
        $list = UserAddress::select()
            ->where('user_id', $user->id)
            ->where('club_code', getClubCode())
            ->get();

        return response()->json([ 'status' => 'success', 'data' => UserAddressResource::collection($list) ]);
    }

    /**
     * Return user logged addresses
     * 
     * @author Davi Souto
     * @since 14/13/2021
     */
    public function ListMyAddresses(Request $request)
    {
        $list = UserAddress::select()
            ->where('user_id', User::getAuthenticatedUserId())
            ->where('club_code', getClubCode())
            ->get();

        return response()->json([ 'status' => 'success', 'data' => UserAddressResource::collection($list) ]);
    }

    public function Get(Request $request, User $user, UserAddress $address)
    {
        $this->validateClub($address->club_code, 'address');

        return response()->json([ 'status' => 'success', 'data' => new UserAddressResource($address) ]);
    }

    public function Create(Request $request, User $user)
    {
        $address = new UserAddress();
        
        $address->fill($request->all());
        $address->club_code = getClubCode();
        $address->user_id = $user->id;
        
        $address->save();

        return response()->json([ 'status' => 'success', 'data' => new UserAddressResource($address) ]);
    }

    public function Update(Request $request, User $user, UserAddress $address)
    {
        $this->validateClub($address->club_code, 'address');

        $address = $address->fill($request->all());
        $address->save();

        return response()->json([ 'status' => 'success', 'data' => new UserAddressResource($address) ]);
    }

    public function Delete(Request $request, User $user, UserAddress $address)
    {
        $this->validateClub($address->club_code, 'address');

        $address->delete();

        return response()->json([ 'status' => 'success', 'data' => new UserAddressResource($address) ]);
    }

    //////////////////

    public function ListMyAddress(Request $request)
    {
        $list = UserAddress::select()
            ->where('user_id', User::getAuthenticatedUserId())
            ->where('club_code', getClubCode())
            ->get();

        return response()->json([ 'status' => 'success', 'data' => UserAddressResource::collection($list) ]);
    }

    public function GetMyAddress(Request $request, UserAddress $address)
    {
        $this->validateClub($address->club_code, 'address');

        return response()->json([ 'status' => 'success', 'data' => new UserAddressResource($address) ]);
    }

    public function CreateMyAddress(Request $request)
    {
        $address = new UserAddress();
        
        $address->fill($request->all());
        $address->club_code = getClubCode();
        $address->user_id = User::getAuthenticatedUserId();
        
        $address->save();

        return response()->json([ 'status' => 'success', 'data' => new UserAddressResource($address) ]);
    }

    public function UpdateMyAddress(Request $request, UserAddress $address)
    {
        $this->validateClub($address->club_code, 'address');

        $address = $address->fill($request->all());
        $address->save();

        return response()->json([ 'status' => 'success', 'data' => new UserAddressResource($address) ]);
    }

    public function DeleteMyddress(Request $request, UserAddress $address)
    {
        $this->validateClub($address->club_code, 'address');

        if ($address->user_id != User::getAuthenticatedUserId()) {
            abort(401);
        }

        $address->delete();

        return response()->json([ 'status' => 'success', 'data' => new UserAddressResource($address) ]);
    }


}
