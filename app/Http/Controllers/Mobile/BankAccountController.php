<?php

namespace App\Http\Controllers\Mobile;

use Illuminate\Http\Request;

use App\Models\CarBrand;

/**
 * Mobile Bank Account Controller
 *
 * @author Davi Souto
 * @since 21/06/2021
 */
class BankAccountController extends Controller
{
    /**
     * Extract my account
     *
     * @author Davi Souto
     * @since 21/06/2021
     */
    function ExtractMyAccount(Request $request)
    {
        return (new \App\Http\Controllers\BankAccountController())->ExtractMyAccount($request);
    }
}
