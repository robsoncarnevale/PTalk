<?php

namespace App\Http\Controllers\Mobile;

use Illuminate\Http\Request;

use DB;
use Exception;

/**
 * Services Controller
 *
 * @author Davi Souto
 * @since 05/03/2021
 */
class ServiceController extends Controller
{
    protected $only_admin = false;
    protected $ignore_privileges = true;

    /**
     * Returns the address by cep
     * 
     * @param string cep
     */
    public function GetAddressByCep(Request $request, $cep)
    {
        return (new \App\Http\Controllers\ServiceController)->GetAddressByCep($request, $user);
    }
}
