<?php

namespace App\Http\Middleware\urls;

use Closure;
use Illuminate\Http\Request;

class BusinessMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        if (!helper_is_user() && !is_admin() ||  user()->role == "sub_user") { // 
            return redirect(url(''));
        }
        return $next($request);
    }
}
