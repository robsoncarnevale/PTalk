<?php

namespace App\Http\Controllers\Mobile;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

use App\Models\User;
use App\Models\Vehicle;

use DB;
use Exception;

/**
 * Main Users Controller
 *
 * @author Davi Souto
 * @since 15/06/2020
 */
class MembersController extends Controller
{
    /**
     * Get member logged data
     * 
     * @author Davi Souto
     * @since 01/08/2020
     */
    public function Me(Request $request)
    {
        $session = User::getMobileSession();

        $user = User::select()
            ->where('id', $session->id)
            ->first();

        return response()->json([ 'status' => 'success', 'data' => $user  ]);
    }

    /**
     * Update member profile
     * 
     * @author Davi Souto
     * @since 03/08/2020
     */
    public function UpdateProfile(Request $request)
    {
        $session = User::getMobileSession();

        $user = User::select()
            ->where('id', $session->id)
            ->first();

        $user->update($request->all());

        return response()->json([ 'status' => 'success', 'data' => $user ]);
    }
}
