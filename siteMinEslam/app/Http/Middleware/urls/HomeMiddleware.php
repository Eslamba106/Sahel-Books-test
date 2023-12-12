<?php

namespace App\Http\Middleware\urls;

use Closure;
use Illuminate\Http\Request;

class HomeMiddleware
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
        // load_settings_data(); // ???
        // get_header_info(); // ???
        if (settings()->enable_frontend == 0 && !in_array('switch_lang', $request->segments())) {
            return redirect(url('login'));
        }
        return $next($request);
    }
}
