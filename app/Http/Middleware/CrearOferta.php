<?php

namespace App\Http\Middleware;

use App\Models\Dependencia;
use App\Models\Oferta;
use App\Models\Sede;
use App\Models\User;

use Closure;
use Illuminate\Contracts\Auth\Guard;
use Illuminate\Routing\Route;

use Redirect;

use Session;



class CrearOferta
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
        //dd($request->all());
        $idSede = null;
        if(session('rol')->nombre == 'Administrador Dippro' || session('rol')->nombre == 'Administrador Egresados')
        {
            if(isset($request->empresa) || $request->empresa==null)
            {
                $idSede = $request->empresa['id'];
            }
        }
        else if(session('rol')->nombre == 'Empresa')
        {
            $idSede = $this->auth->user()->idSede;
        }
        
        $sede = Sede::find($idSede);
        
        if($request->jefe != null)
        {
            $usuario = User::where('id', $request->jefe['id'])
                           ->where('idSede', $idSede)
                           ->first();
           if(sizeof($usuario)==0)
           {
               return ['title'=>'Error', 'content'=>'Este jefe no existe', 'type'=>'error'];
           }
        }
        
        if($this->route->one != null)
        {
            if(session('rol')->nombre == 'Empresa')
                $oferta = Oferta::where('id', $this->route->one)->where('idSede', $idSede)->first();
            else if(session('rol')->nombre == 'Administrador Dippro')
                $oferta = Oferta::where('id', $this->route->one)->where('creada_por_dipro', true)->first();
            else
                return Redirect::to('/');
            
            if(sizeof($oferta)==0)
            {
                $msj = ['content'=>'Esta oferta no existe'];
                if(session('rol')->nombre == 'Empresa')
                    return Redirect::to('/empresa/ofertas')->with($msj);
                else if(session('rol')->nombre == 'Administrador Dippro')
                    return Redirect::to('/adminsil/ofertas')->with($msj);
                else
                    return Redirect::to('/');
            }
            else if($oferta->getestado->nombre == 'Rechazada' || $oferta->getestado->nombre == 'Cancelada' || $oferta->getestado->nombre == 'Finalizada')//
            {
                $msj = ['content'=>'No se puede editar esta oferta.'];
                if(session('rol')->nombre == 'Empresa')
                    return Redirect::to('/empresa/ofertas')->with($msj);
                else if(session('rol')->nombre == 'Administrador Dippro')
                    return Redirect::to('/adminsil/ofertas')->with($msj);
                else
                    return Redirect::to('/');
            }
            else if( (session('rol')->nombre=='Administrador Dippro' && !$oferta->creada_por_dipro) || (session('rol')->nombre=='Empresa' && ($this->auth->user()->getsede->getempresa->getestadodipro->nombre == 'ACEPTADA' && $this->auth->user()->getsede->getempresa->getestadosil->nombre != 'ACEPTADA') && $oferta->gettipo->nombre=='Graduados') )
            {
                //dd(session('rol')->nombre=='Administrador Dippro' && !$oferta->creada_por_dipro);
                $msj = ['content'=>'Usted está desabilitado para editar ofertas para egresados'];
                if(session('rol')->nombre == 'Empresa')
                    return Redirect::to('/empresa/ofertas')->with($msj);
                else if(session('rol')->nombre == 'Administrador Dippro')
                    return Redirect::to('/adminsil/ofertas')->with($msj);
                else
                    return Redirect::to('/');
            }
            else if((session('rol')->nombre=='Administrador Dippro' && !$oferta->creada_por_dipro) || (session('rol')->nombre=='Empresa' && ($this->auth->user()->getsede->getempresa->getestadodipro->nombre != 'ACEPTADA' && $this->auth->user()->getsede->getempresa->getestadosil->nombre == 'ACEPTADA') && $oferta->gettipo->nombre=='Practicantes'))
            {
                $msj = ['content'=>'Usted está desabilitado para editar ofertas para practicantes'];
                if(session('rol')->nombre == 'Empresa')
                    return Redirect::to('/empresa/ofertas')->with($msj);
                else if(session('rol')->nombre == 'Administrador Dippro')
                    return Redirect::to('/adminsil/ofertas')->with($msj);
                else
                    return Redirect::to('/');
            }
            else
            {
                Session::put('idOferta', $this->route->one);
            }
        }
        else
        {
            if($request->id != null)
            {
                if($request->id != Session::get('idOferta'))
                {
                    return ['title'=>'Error', 'content'=>'Error al editar la oferta.', 'type'=>'error'];
                }
            }
            
            if( $request->programas == null && !(request()->path()=='empresa/crearoferta' || request()->path()=='adminsil/crearoferta'))
            {
                return ['title'=>'Error', 'content'=>'Debe seleccionar al menos un programa', 'type'=>'error'];
            }
            
            if( $request->arl != null && $request->arl=="0")
            {
                //dd($request->file, request()->path(), $request->carta);
                if( ($request->file == null || $request->file == 'undefined') && !(request()->path()=='empresa/crearoferta' || request()->path()=='adminsil/crearoferta') && $request->gettipo['nombre'] == 'Practicantes')
                {
                    return ['title'=>'Error', 'content'=>'Debe adjuntar una carta donde explique porque no se paga ARL', 'type'=>'error'];
                }
            }
            
            else if( request()->path()!='empresa/crearoferta' && request()->path()!='adminsil/crearoferta')
            {
                $array = [];
                $con=0;
                foreach($request->programas as $p)
                {
                    $array[$con]=$p['id'];
                    $con++;
                }
                
                $programas = Dependencia::whereIn('id', $array)->get();
                if(  sizeof($programas) != sizeof($request->programas) )
                {
                    return ['title'=>'Error', 'content'=>'Uno o varios de los programas seleccionados no existe', 'type'=>'error'];
                }
            }
        }
        
        return $next($request);
    }
}
