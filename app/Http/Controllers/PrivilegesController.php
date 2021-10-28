<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Privilege;
use App\Http\Resources\PrivilegesResource;

class PrivilegesController extends Controller
{
    public function List(Request $request)
    {
        $only = auth()->user()->privileges->pluck('action');

        $privileges = Privilege::whereIn('action', $only)->get();

        return PrivilegesResource::collection($privileges);
    }
}
