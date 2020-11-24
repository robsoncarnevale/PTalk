<?php

namespace App\Http\Controllers\Mobile;

use Illuminate\Http\Request;

use Storage;

/**
 * Services Controller
 *
 * @author Davi Souto
 * @since 15/11/2020
 */
class ServiceControler extends Controller
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
        return (new \App\Http\Controllers\ServiceControler())->GetAddressByCep($request, $cep);
    }
}
