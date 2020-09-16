<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

/** This middleware is purely for the sack of feature testing. would use session ID
 *  directly otherwise.
 */
class SessionToken
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
        if ($request->session()->has('sessionToken') === false) {
            $request->session()->put('sessionToken', $request->session()->getId());
        }

        return $next($request);
    }
}
