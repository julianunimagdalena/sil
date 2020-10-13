<?php

namespace App\Http\Middleware;

use Closure;
use Auth;

class Jefe
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
        if (Auth::user()->getrol->nombre != 'Jefe inmediato') {
            
            Auth::logout();
            
            if ($request->ajax()) {
                return response('Unauthorized.', 401);
            } else {
                return redirect()->to('/');
            }
        }
        return $next($request);
    }
}
