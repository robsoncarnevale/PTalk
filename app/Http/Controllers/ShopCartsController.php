<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ShopCartsController extends Controller
{
    public function getall() {
        return response()->json([
            'status' => 'success',
            'data' => 1
        ]);
    }
}
