<?php

namespace App\Http\Middleware;

use App\Models\Oferta;
use App\Models\Tipooferta;

use Closure;
use Illuminate\Contracts\Auth\Guard;
use Illuminate\Routing\Route;

use Carbon\Carbon;


class ActivarOferta
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    
    private $auth; 
    
    private $route;
    
    public function __construct(Guard $auth, Route $route)
    {
        $this->auth = $auth;
        $this->route = $route;
    }
    
    public function handle($request, Closure $next)
    {
        $oferta = Oferta::find($this->route->one);
        
        if(substr($request->path(), 0, 20) == 'adminsil/ofertajson/')
        {
            if(sizeof($oferta)==0)
            {
                return redirect('/adminsil/ofertas')->with(['error'=>'Esta oferta no existe']);
            }
            else if( (session('rol')->nombre =='Administrador Egresados' && $oferta->idTipo == Tipooferta::where('nombre', 'Practicantes')->first()->id) || (session('rol')->nombre =='Administrador Dippro' && $oferta->idTipo == Tipooferta::where('nombre', 'Egresados')->first()->id) )
            {
                return redirect('/adminsil/ofertas')->with(['error'=>'Usted no tiene permiso para ver esta oferta']);
            }
        }
        else
        {
            if(sizeof($oferta)==0)
            {
                return redirect('/adminsil/ofertas')->with(['error'=>'Esta oferta no existe']);
            }
            else if( (session('rol')->nombre =='Administrador Egresados' && $oferta->idTipo == Tipooferta::where('nombre', 'Practicantes')->first()->id) || (session('rol')->nombre =='Administrador Dippro' && $oferta->idTipo == Tipooferta::where('nombre', 'Egresados')->first()->id) )
            {
                return redirect('/adminsil/ofertas')->with(['error'=>'Usted no tiene permiso para modificar esta oferta']);
            }
            else if( !$oferta->estado && Carbon::now() >= $oferta->fechaCierre )
            {
                return redirect('/adminsil/ofertas')->with(['error'=>'Para activar una oferta la fecha de cierre debe ser posterior a la fecha actual']);
            }
        }
            
        
        return $next($request);
    }
}
