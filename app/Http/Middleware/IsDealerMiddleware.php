<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class IsDealerMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        if (! auth()->check() || ! in_array(auth()->user()->type, ['admin', 'dealer', 'sales'])) {
            abort(403, 'Forbidden');
        }

        return $next($request);
    }
}
