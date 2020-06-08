<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;
// use Illuminate\Validation\Validator;
use Illuminate\Http\Request;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    /**
     * Make validation
     *
     * @author Davi Souto
     * @since 08/06/2020
     */
    protected function validate(Request $request, $rules)
    {
        $validator = \Validator::make($request->all(),$rules);

        if ($validator->fails())
        {
            $errors = $validator->errors()->all();
            $first_error = $errors[0];

            return response()->json([ 'status' => 'error', 'message' => $first_error, 'data' => [ 'errors' => $errors ], 'code' => '900' ]);
        }

        return false;
    }

    /**
     * Format validation
     *
     * {@inheritdoc}
     * @author Davi Souto
     * @since  08/06/2020
     */
    // protected function formatValidationErrors(Validator $validator)
    // {
    //     $errors = $validator->errors()->all();
    //     $first_error = $errors[0];

    //     return [ 'status' => 'error', 'message' => $first_error, 'data' => $errors, 'code' => '900' ];
    // }
}
