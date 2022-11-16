<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Charge;

class ChargeController extends Controller
{
    public function getAll($id) {
        
        $charge = Charge::join('users as u','u.id','charges.user_id')
                        ->select('u.name', 'charges.value', 'charges.created_at')
                        ->where('user_id', $id)
                        ->get()->toArray();

        for ($i = 0; $i < count($charge); $i++) {
            $charge[$i]['description'] = "";
        }

        return response()->json(['status' => 'success', 'data' => $charge]);

    }
}
