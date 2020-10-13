<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Routing\Route;
use App\Models\Oferta;
use App\Models\Postulado;
use Auth;

class EmpresaPostulados
{
    
    private $route ;
    
    public function __construct(Route $route)
    {
        $this->route = $route;
        
    }
    
    public function handle($request, Closure $next)
    {
        $idPersona = $request->one;
        $idSede = Auth::user()->getsede->id;
        
        $idOfertas = Oferta::where('idSede', $idSede)->select('id')->get()->toArray();
        
        $postulado = Postulado::whereIn('idOferta', $idOfertas)->where('idPersona', $idPersona)->get();
        
        if(sizeof($postulado)==0)
        {
            return redirect('/empresa/ofertas');
        }
        
        
        
        return $next($request);
    }
}
