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
    public function Me(Request $request)
    {
        $session = User::getMobileSession();

        $user = User::select()
            ->where('id', $session->id)
            ->first();

        return response()->json([ 'status' => 'success', 'data' => $user  ]);
    }
}
