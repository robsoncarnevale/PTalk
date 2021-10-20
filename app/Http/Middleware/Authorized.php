<?php

namespace App\Http\Middleware;

use Tymon\JWTAuth\Http\Middleware\BaseMiddleware;
use Tymon\JWTAuth\Facades\JWTAuth;

use Closure;
use Exception;

/**
 * Check if user has authorized
 *
 * @author Davi Souto
 * @since 23/05/2020
 */
class Authorized extends BaseMiddleware
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
        try
        {
            $user = JWTAuth::parseToken()->authenticate();        
        }
        catch(Exception $e)
        {
            return response()->json([ 'status' => 'error', 'message' => "Unauthorized", 'code' => 401 ], 401);
        }

        return $next($request);
    }
}
