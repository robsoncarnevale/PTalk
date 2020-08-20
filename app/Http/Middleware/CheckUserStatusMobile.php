<?php

namespace App\Http\Middleware;

use Closure;

class CheckUserStatusMobile
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $session = \App\Models\User::getMobileSession();
        $user = \App\Models\User::select()
            ->where('id', $session->id)
            ->first();
        
        if ($user->status != \App\Models\User::ACTIVE_STATUS)
            return response()->json([ 'status' => 'error', 'message' => "Unauthorized", 'code' => 401 ], 401);

        if ($user->approval_status != \App\Models\User::APPROVED_STATUS_APPROVAL)
            return response()->json([ 'status' => 'error', 'message' => "Unauthorized", 'code' => 401 ], 401);
        
        return $next($request);
    }
}
