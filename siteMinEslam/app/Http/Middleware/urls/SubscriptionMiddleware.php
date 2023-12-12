<?php

namespace App\Http\Middleware\urls;

use Closure;
use Illuminate\Http\Request;

class SubscriptionMiddleware
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
        $arr = ['paytabs_callback', 'update_hubspot_packages', 'get_subscription_payment'];
        if (!helper_is_user() && !in_array($request->segment(2), $arr) && !in_array($request->segment(3), $arr)) {
            return redirect(url(''));
        }
        return $next($request);
    }
}
