<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

/**
 * Services Controller
 *
 * @author Davi Souto
 * @since 15/11/2020
 */
class ServicesController extends Controller
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
        $service = new \App\Http\Services\CepService();

        return response()->json($service->getAddressByCep($cep)); exit;
    }
}
