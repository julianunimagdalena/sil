<?php

namespace App\Http\Middleware;

use Closure;

use Illuminate\Routing\Route;
use DB;
use Auth;

use App\Models\EstadoOferta;
use App\Models\Estudiante;
use App\Models\Postulado;


class OfertaEstudiante
{
    private $route;
    
    public function __construct(Route $r)
    {
        $this->route = $r;
    }
    
    public function handle($request, Closure $next)
    {
        $codigo = Auth::user()->identificacion;
        $estudiante = Estudiante::where('codigo', $codigo)->first();
        
        $method = explode('/', $request->path())[1];
        
        $idOferta = $this->route->one;
        $fecha = \Carbon\Carbon::now()->toDateString();
        $idPrograma = $estudiante->idPrograma;
        $oferta = DB::table('ofertas')
                     ->join('ofertas_programas', function($join) use ($idPrograma, $idOferta){
                         $join->on('ofertas_programas.idOferta', '=', 'ofertas.id')
                              ->where('ofertas_programas.idDependencia','=', $idPrograma);
                     })
                     ->where('ofertas.id',$idOferta)
                     ->where('estado', EstadoOferta::where('nombre', 'Publicada')->first()->id)
                     ->where('fechaCierre', '>', $fecha)
                     ->get();
        
        if(sizeof($oferta) == 0)
        {
            if($method == 'postularse')
            {
                $msj = array('error'=>'Esta oferta no exite');
                return redirect('/estudiante/ofertas')->with($msj);
            }
            else if($method == 'ofertajson')
            {
                return ['title'=>'Error', 'content'=>'Esta oferta no existe', 'type'=>'error'];
            }
            else if($method == 'nopostularse')
            {
                $postulado = Postulado::where('idOferta', $idOferta)->where('idEstudiante', $estudiante->id)->first();
                if(sizeof($postulado)==0)
                {
                    $msj = array('error'=>'Usted no se encuentra postulado a esta oferta');
                    return redirect('/estudiante/ofertas')->with($msj);
                }
            }
            
        }
        
        return $next($request);
    }
}
