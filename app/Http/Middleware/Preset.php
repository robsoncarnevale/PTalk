<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class Preset
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        /* DEFINIR IDIOMA DA APLICAÇÃO */

        if(isset($request->header()['accept-language']))
        {
            $lang = $request->header()['accept-language'];

            if(isset($lang[0]))
                app('translator')->setlocale($lang[0]);
        }

        return $next($request);
    }
}
