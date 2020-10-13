<?php

namespace App\Http\Middleware;

use Closure;
use Auth;

class Estudiante
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
        if(Auth::user()->getrol->nombre!='Estudiante')
        {
            Auth::logout();
            return redirect('/');
        }
        
        return $next($request);
    }
}
