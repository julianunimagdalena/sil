<?php

namespace App\Http\Middleware;

use Closure;

use App\Models\Oferta;
use App\Models\Persona;
use App\Models\Postulado;

use Auth;

class HojaPrivada
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
        if($request->one != null)
        {
            $persona = Persona::find($request->one);

            $idSede = Auth::user()->getsede->id;            
            $idOfertas = Oferta::where('idSede', $idSede)->select('id')->get()->toArray();            
            $postulado = Postulado::whereIn('idOferta', $idOfertas)->where('idPersona', $persona->id)->get();            

            if($persona->gethojadevida[0]->activa != 1 && sizeof($postulado)==0 )
            {                  
                return redirect('/empresa/hojasdevida');
            }
        }
        else 
        {
            return redirect('/empresa/hojasdevida');
        }
        return $next($request);
    }
}
