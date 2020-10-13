<?php

namespace App\Http\Middleware;

use Closure;

use Auth;



class Prepracticas
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
        $idPersona = Auth::user()->idPersona;
        $estudiante = \App\Models\Estudiante::where('idPersona', $idPersona)->first();
        if( $estudiante->gettipo->nombre != 'Preprácticas' && $estudiante->gettipo->nombre != 'Prácticas y preprácticas' )
        {
            Auth::logout();
            return redirect('/');
        }
        return $next($request);
    }
}
