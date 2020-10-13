<?php

namespace App\Http\Middleware;

use Closure;
use Auth;
use App\Models\Estudiante;
use App\Providers\WebService;

class Practicas
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
        // dd('hola');
        $idPersona = Auth::user()->idPersona;
        $estudiante = Estudiante::where('idPersona', $idPersona)->first();
        if( $estudiante->gettipo->nombre != 'Prácticas' && $estudiante->gettipo->nombre != 'Prácticas y preprácticas' )
        {
            Auth::logout();
            return redirect('/');
        }
        
        return $next($request);
    }
}
