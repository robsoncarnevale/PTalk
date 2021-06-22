<?php

namespace App\Http\Controllers\Mobile;

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
    // protected function validate(Request $request, $rules)
    public static function validate(Request $request, $rules)
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

    protected function validateClub($club_code, $attr_name)
    {
        \Illuminate\Support\Facades\Validator::make([ 'club_code' => $club_code ], [
            'club_code' => new \App\Rules\ClubCodeValid($attr_name),
        ])->validate();
    }
}
