<?php

namespace App\Http\Middleware;

use Closure;
use Auth;

class AdminCoordinador
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
        if (Auth::user()->getrol->nombre != 'Administrador Dippro' && Auth::user()->getrol->nombre != 'Coordinador') {
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
