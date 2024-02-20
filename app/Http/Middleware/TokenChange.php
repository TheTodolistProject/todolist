<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Log;

class TokenChange
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    { Log::emergency("request in test api: " .$request);
        if($request->headers->has('token')) {
            $request->headers->set('Authorization', "Bearer " . $request->headers->get("token"));
            $request->headers->remove('token');
        }

        info("request in test api: " .$request);
        return $next($request);
    }
}
