<?php

namespace App\Http\Middleware;

use Closure;

use Auth;

class Cambiarclave
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
        if(Auth::user()->codigo_verificacion !=  null && $request->nueva == null)
        {
            if(Auth::user()->codigo_verificacion == 'Gener')
            {
                return view('home.cambiarclave');
            }
            // else if(Auth::user()->getrol->nombre != 'Administrador Egresados')
            else if(session('rol')->nombre != 'Administrador Egresados')
            {
                return view('home.cambiarclave'); 
            }
            
        }
        // else if(Auth::user()->getrol->nombre!='Jefe inmediato' && Auth::user()->getrol->nombre!='Empresa' && Auth::user()->getrol->nombre!='Graduado')
        else if(session('rol')->nombre!='Jefe inmediato' && session('rol')->nombre!='Empresa' && session('rol')->nombre!='Graduado')
        {
            return redirect('/home');
        }
        return $next($request);
    }
}
