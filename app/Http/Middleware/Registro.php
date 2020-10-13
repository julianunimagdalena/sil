<?php

namespace App\Http\Middleware;

use Closure;

class Registro
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
        if($request->rol!=null && $request->rol['nombre']!='Estudiante' && $request->rol['nombre']!='Empresa' && $request->rol['nombre']!='Graduado')
        {
            return ['type'=> 'error','title'=>'Error!', 'content'=>'Rol inválido'];
        }
        //
        
        if($request->tipoEstudiante!=null && $request->tipoEstudiante['nombre']!='Prácticas' && $request->tipoEstudiante['nombre']!='Preprácticas'
             && $request->tipoEstudiante['nombre']!='Prácticas y preprácticas')
        {
            return ['type'=> 'error','title'=>'Error!', 'content'=>'Etapa inválida'];
        }
        return $next($request);
    }
}
