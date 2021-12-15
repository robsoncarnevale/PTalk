<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Club;

class ContactController extends Controller
{
    protected $ignore_routes = [
        'contacts'
    ];

    public function index()
    {
        try
        {
            $club = Club::first();

            if(!$club)
                throw new \Exception(__('general.generic.message'));

            return response()->json([
                'status' => 'success',
                'data' => $club
            ]);
        }
        catch(\Exception $e)
        {
            return response()->json([
                'status' => 'success',
                'message' => $e->getMessage()
            ], 500);
        }
    }
}
