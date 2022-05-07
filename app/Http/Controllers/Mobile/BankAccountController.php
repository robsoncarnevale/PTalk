<?php

namespace App\Http\Controllers\Mobile;

use Illuminate\Http\Request;
use App\Http\Requests\BankAccountLoadRequest;
use App\Models\CarBrand;

/**
 * Mobile Bank Account Controller
 *
 * @author Davi Souto
 * @since 21/06/2021
 */
class BankAccountController extends Controller
{
    public function my(Request $request)
    {
        return (new \App\Http\Controllers\BankAccountController())->my($request);
    }

    public function load(BankAccountLoadRequest $request)
    {
        return (new \App\Http\Controllers\BankAccountController())->load($request);
    }
}
