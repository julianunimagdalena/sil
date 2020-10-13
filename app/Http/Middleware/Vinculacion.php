<?php

namespace App\Http\Middleware;

use Closure;
use Auth;
use App\Models\Estudiante;

class Vinculacion
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
        $codigo = Auth::user()->identificacion;
        $estudiante = Estudiante::where('codigo', $codigo)->first();
        
        if($estudiante->getmodalidades != null)
        {
            if($estudiante->getmodalidades[sizeof($estudiante->getmodalidades) - 1]->nombre != 'Vinculación laboral')
            {
                Auth::logout();
                return redirect()->to('/');
            }
        }
        else 
        {
            Auth::logout();
            return redirect()->to('/');
        }
            
        
        
        return $next($request);
    }
}
