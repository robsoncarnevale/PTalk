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

    public function ListMyAddress(Request $request)
    {
        return (new \App\Http\Controllers\UserAddressController)->ListMyAddress($request);
    }

    public function GetMyAddress(Request $request, UserAddress $address)
    {
        return (new \App\Http\Controllers\UserAddressController)->GetMyAddress($request, $address);
    }

    public function CreateMyAddress(Request $request)
    {
        return (new \App\Http\Controllers\UserAddressController)->CreateMyAddress($request, $user);
    }

    public function UpdateMyAddress(Request $request, UserAddress $address)
    {
        return (new \App\Http\Controllers\UserAddressController)->UpdateMyAddress($request, $address);
    }

    public function DeleteMyddress(Request $request, UserAddress $address)
    {
        return (new \App\Http\Controllers\UserAddressController)->DeleteMyAddress($request, $address);
    }

}
