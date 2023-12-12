<?php

namespace App\Http\Middleware\urls;

use Closure;
use Illuminate\Http\Request;

class DashboardMiddleware
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
        // $this->update_currency(); // TODO: ????
        if (!is_admin() && !helper_is_user()) {
            return redirect(url(''));
        }
        return $next($request);
    }
}
