<?php

namespace App\Http\Middleware;

use Closure;

use App\Models\Estudiante;
use App\Models\TipoEstudiante;

class RechazarPracticas
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
        if(isset($request->id))
        {
            $estudiante = Estudiante::where('idTipo', TipoEstudiante::where('nombre', 'Solicitó prácticas')->first()->id)->where('id', $request->id)->first()->count();
            if($estudiante == 0)
            {
                return ['title'=>'Error', 'content'=>'No se encontró el estudiante', 'type'=>'error'];
            }
        }
        return $next($request);
    }
}
