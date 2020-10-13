<?php

namespace App\Http\Middleware;

use Closure;

use Auth;
use App\Models\Estudiante;

class Hojadevida
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
        // dd($request->getestudios);
        if($request->path() == 'estudiante/saveperfil')
        {
            if(sizeof($request->getestudios)==0)
            {
                if($estudiante->gettipo->nombre=='Prácticas' || $estudiante->gettipo->nombre=='Preprácticas' || $estudiante->gettipo->nombre=='Solicitó prácticas')
                    return ['title'=>'Error', 'content'=>'Debe registrar al menos un estudio realizado', 'type'=>'error']; 
                    
                if($estudiante->gettipo->nombre=='Egresado')
                    return ['title'=>'Error', 'content'=>'Debe registrar al menos dos estudio realizado', 'type'=>'error']; 
            }
            
            for($i = 0; $i < sizeof($request->getidiomas); $i++)
            {
                for($j = ($i + 1); $j < sizeof($request->getidiomas); $j++)
                {
                    if($request->getidiomas[$i]['getidioma']['nombre'] == $request->getidiomas[$j]['getidioma']['nombre'])
                        return ['title'=>'Error', 'content'=>'No se pueden repetir idiomas', 'type'=>'error']; 
                }
            }
            
        }
        else if($request->path() == 'estudiante/savereferencia')
        {
            for($i = 0; $i < sizeof($request->getreferenciasp); $i++)
            {
                for($j = $i + 1; $j < sizeof($request->getreferenciasp); $j++)
                {
                    if($request->getreferenciasp[$i]['telefono'] == $request->getreferenciasp[$j]['telefono'])
                    {
                        return ['title'=>'Error', 'content'=>'Sus referencias no pueden tener el mismo número de teléfono', 'type'=>'error'];
                    }
                }
                
                for($j = 0; $j < sizeof($request->getreferenciasf); $j++)
                {
                    if($request->getreferenciasp[$i]['telefono'] == $request->getreferenciasf[$j]['telefono'])
                    {
                        return ['title'=>'Error', 'content'=>'Sus referencias no pueden tener el mismo número de teléfono', 'type'=>'error'];
                    }
                }
            }
            
            for($i = 0; $i < sizeof($request->getreferenciasf); $i++)
            {
                for($j = $i + 1; $j < sizeof($request->getreferenciasf); $j++)
                {
                    if($request->getreferenciasf[$i]['telefono'] == $request->getreferenciasf[$j]['telefono'])
                    {
                        return ['title'=>'Error', 'content'=>'Sus referencias no pueden tener el mismo número de teléfono', 'type'=>'error'];
                    }
                }
            }
        }
        return $next($request);
    }
}
