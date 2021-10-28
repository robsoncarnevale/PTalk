<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Symfony\Component\Translation\Translator;

class StatusController extends Controller
{
    protected $ignore_routes = ['statuses.list'];

    public function List(Request $request)
    {
        $statuses = [
            User::ACTIVE_STATUS,
            User::INACTIVE_STATUS,
            User::SUSPENDED_STATUS,
            User::BLOCKED_STATUS,
            User::BANNED_STATUS
        ];

        $data = [];

        foreach($statuses as $status)
            $data[] = [
                'code' => $status,
                'description' => __('users.status.' . $status)
            ];

        return response()->json(['data' => $data], 200);
    }
}
