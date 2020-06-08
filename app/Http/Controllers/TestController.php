<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

/**
 * Test Controller
 *
 * @author Davi Souto
 * @since 01/06/2020
 */
class TestController extends Controller
{
    /**
     * Returns user logged data
     *
     * @author Davi Souto
     * @since 23/05/2020
     */
    public function MakeTest(Request $request)
    {
        $result = [
            'api_state' =>  'on',
            'method'    =>  $request->method(),    
            'logged'    =>  false,
            'input'     =>  $request->all(),
        ];

        if (Auth::guard()->user())
            $result['logged'] = true;

        return response()->json([ 'status' => 'success', 'data' => $result  ]);
    }
}
