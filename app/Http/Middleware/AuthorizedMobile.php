<?php

namespace App\Http\Middleware;

use App\Models\User;
use Illuminate\Auth\Middleware\Authenticate as Middleware;

use Closure;
use Exception;

/**
 * Check if user has authorized
 *
 * @author Davi Souto
 * @since 23/05/2020
 */
class AuthorizedMobile extends Middleware
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
        try {
          $explode_token = explode(".", $request->header('Authorization'));
          
          $header = substr($explode_token[0], 7);
          $payload = $explode_token[1];
          $signature = $explode_token[2];
          
          $valid = base64url_encode(hash_hmac('sha256', $header . $payload, env('JWT_SECRET'), true));

          if ($valid !== $signature)
            throw new Exception('Invalid signature');

          User::setMobileSession(json_decode(base64url_decode($payload)));
        } catch (Exception $e) {
            return response()->json([ 'status' => 'error', 'message' => "Unauthorized", 'code' => 401 ], 401);
        }

        return $next($request);
    }
}
