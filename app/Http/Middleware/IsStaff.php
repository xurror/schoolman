<?php

namespace App\Http\Middleware;

use Closure;

class IsStaff
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
        if(auth()->user()->role == 'staff'){
            return $next($request);
        }
        return response()->json(['message' => 'You don\'t have proper rights to access this resource'], 401);
    }
}
