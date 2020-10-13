<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Auth;
use DB;
use Mail;

use App\Models\EstadoPostulado;
use App\Models\EstadoPostuladoEst;
use App\Models\EstadoPractica;
use App\Models\Estudiante;
use App\Models\ModalidadEstudiante;
use App\Models\Novedad;
use App\Models\Rol;
use App\Models\User;
use App\Models\UsuarioNovedad;

class NovedadController extends Controller
{
    
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware("cambiocontrasena");
        $this->middleware('admincdn', ['only'=>['getNovedadesrecibidasbyestudiantejson']]);
    }
    
    public function getIndex()
    {
        $estudiante = null;
        if(session('rol')->nombre == 'Estudiante')
        {
            $estudiante = Estudiante::where('codigo', Auth::user()->identificacion)->first();
        }
        return view('novedad.index', compact('estudiante'));
    }
    
    public function getNovedadesrecibidasjson()
    {
        // $novedad = Auth::user()->getnovedadesrecibidas;//->with('getusuario');
        
        $idUsuario = Auth::user()->id;
        
        $novedad = DB::table('novedades')
                      ->join('usuario_novedad', function($join) use ($idUsuario){
                          $join->on('usuario_novedad.idNovedad', '=', 'novedades.id')
                               ->where('usuario_novedad.idUsuario', '=', $idUsuario);
                      })
                      ->where('novedades.activa',1)
                      ->take(50)
                      ->selectRaw('novedades.id, usuario_novedad.leida, novedades.fecha, novedades.asunto, novedades.contenido')
                      ->orderBy('novedades.id', 'desc')
                      ->get();
        
        return $novedad;
    }
    
    public function getNovedadesrecibidasbyestudiantejson($codigo)
    {
        // $novedad = Auth::user()->getnovedadesrecibidas;//->with('getusuario');
        
        $idUsuario = User::where('idRol', Rol::where('nombre', 'Administrador Dippro')->first()->id)->first()->id;
        
        $usuarios  = $this->getUsuariosbycodigoestudiante($codigo);
        $ids=[];
        $con=0;
        foreach($usuarios as $item)
        {
            if($item != null)
            {
                $ids[$con] = $item['id'];
                $con++;
            }
        }
        // return $ids;
        
        $novedad = DB::table('novedades')
                      ->join('usuario_novedad', function($join) use ($idUsuario){
                          $join->on('usuario_novedad.idNovedad', '=', 'novedades.id')
                               ->where('usuario_novedad.idUsuario', '=', $idUsuario);
                      })
                      ->where('novedades.activa',true)
                      ->whereIn('novedades.idUsuario',$ids)
                      ->take(50)
                      ->selectRaw('novedades.id, usuario_novedad.leida, novedades.fecha, novedades.asunto, novedades.contenido, true as recibida')
                      ->orderBy('novedades.id', 'desc')
                      ->get();
        $novedad = Novedad::with('getusuario.getuser')
                           ->whereHas('getusuarios', function($q) use($idUsuario){
                               $q->where('idUsuario', $idUsuario);
                           })
                           ->whereIn('novedades.idUsuario',$ids)
                           ->orderBy('novedades.id', 'desc')
                           ->get();
                        
        
        return $novedad;
    }
    
    public function getNovedadesenviadasjson()
    {
        $rol = session('rol')->nombre;

        
        
        // if($rol == 'Administrador Egresados')
        // {
        //   return [];
        // }
        $novedad = Auth::user()->getnovedadesenviadas;//->with('getusuario');
        
        $novedades=null;
        $con=0;
        for($i = (sizeof($novedad) - 1) ; $i >= 0 ; $i--)
        {
            if($con == 50)
            {
                break;
            }
            if($novedad[$i]->activa == 1)
            {
                $novedad[$i]->recibida=false;
                $novedades[$con] = $novedad[$i];
                $con++;
            }
        }
        return $novedades;
    }
    
    public function getNovedadjson($id)
    {
        // 
        $idUsuario = Auth::user()->id;
        $novedad = DB::table('novedades')
                      ->join('usuario_novedad', function($join) use ($idUsuario){
                          $join->on('usuario_novedad.idNovedad', '=', 'novedades.id')
                               ->where('usuario_novedad.idUsuario', '=', $idUsuario);
                      })
                      ->where('novedades.id', $id)
                      ->where('novedades.asunto', env('contacto'))
                      ->selectRaw("novedades.id, novedades.correo, novedades.celular, usuario_novedad.leida, novedades.fecha, novedades.asunto, novedades.contenido, 
                                   concat(novedades.nombres,' ', novedades.apellidos) as sender")
                      ->first();

        if($novedad != null)
        {
            UsuarioNovedad::where('idUsuario', $idUsuario)->where('idNovedad', $id)->update(['leida'=>true]);
            $novedad->recibida=true;
            return json_encode($novedad);
        }

        
        $novedad = Novedad::with('getusuarios.getuser')
                          ->where('idUsuario', $idUsuario)
                          ->where('id',$id)
                          ->first();
        
        if($novedad != null)
        {
            $novedad->recibida=false;
            return $novedad;
        }
            
                                  
        $novedad = DB::table('novedades')
                      ->join('usuario_novedad', function($join) use ($idUsuario){
                          $join->on('usuario_novedad.idNovedad', '=', 'novedades.id')
                               ->where('usuario_novedad.idUsuario', '=', $idUsuario);
                      })
                      ->join('usuarios', function($join){
                          $join->on('novedades.idUsuario', '=', 'usuarios.id');
                      })
                      ->join('personas', function($join){
                          $join->on('personas.id', '=', 'usuarios.idPersona');
                      })
                      ->where('novedades.id', $id)
                      ->selectRaw("novedades.id, personas.correo, personas.celular, usuario_novedad.leida, novedades.fecha, novedades.asunto, novedades.contenido, 
                                   concat(personas.nombres,' ', personas.apellidos) as sender")
                      ->first();

        
        if($novedad != null)
        {
            UsuarioNovedad::where('idUsuario', $idUsuario)->where('idNovedad', $id)->update(['leida'=>true]);
            $novedad->recibida=true;
            return json_encode($novedad);
        }
        
        
        
        return "Esta novedad no existe";
    }
    
    public function postEliminiar(Request $request)
    {
        $idUsuario = Auth::user()->id;
        $enviadas = Novedad::where('idUsuario', $idUsuario)
                          ->whereIn('id', $request->all() )
                          ->get();
        
        $recibidas = DB::table('novedades')
                       ->join('usuario_novedad', function($join) use ($idUsuario){
                          $join->on('usuario_novedad.idNovedad', '=', 'novedades.id')
                               ->where('usuario_novedad.idUsuario', '=', $idUsuario);
                       })
                       ->whereIn('novedades.id', $request->all())
                       ->get();
        
        if( ( sizeof($enviadas) + sizeof($recibidas) ) == sizeof($request->all()))
        {
            Novedad::whereIn('id', $request->all())
                   ->update(['activa' => false]);
            
            return ['title'=>'Éxito', 'content'=>'Novedades eliminadas con éxito', 'type'=>'success'];
        }
        
        return ['title'=>'Error', 'content'=>'Hubo un error al eliminar las novedades', 'type'=>'error'];
    }
    
    public function getUsuariosjson()
    {
        $rol = session('rol')->nombre;

        $usuario = Auth::user();
        $usuarios= null;
        $estudiantes = null;
        $con = 0;
        if($rol == 'Estudiante')
        {
            $usuarios = $this->getUsuariosbycodigoestudiante($usuario->identificacion);
        }
        else if($rol == 'Administrador Egresados')
        {
          return [];
        }
        else if($rol == 'Administrador Dippro')
        {
            $estudiantes = ModalidadEstudiante::where('estado', EstadoPractica::where('nombre', 'Aprobada')->first()->id)
                                              ->get();
            foreach($estudiantes as $est)
            {
               $usuarios['estudiantes'][$con]['id']=$est->getestudiante->codigo;
               $usuarios['estudiantes'][$con]['nombre']=$est->getestudiante->codigo.' - '.$est->getestudiante->getpersona->nombres.' '.$est->getestudiante->getpersona->apellidos;
               $con++;
            }
        }
        else if($rol == 'Empresa')
        {
            $estudiantes = DB::table('estudiantes_modalidades')
                             ->join('estudiantes', 'estudiantes_modalidades.idEstudiante', '=', 'estudiantes.id')
                             ->join('personas', 'personas.id', '=', 'estudiantes.idPersona')
                             ->join('postulados', function ($join) {
                                // $join->on('estudiantes.id', '=', 'postulados.idEstudiante')
                                $join->on('personas.id', '=', 'postulados.idPersona')
                                     ->where('postulados.idEstatoEmpresa','=', EstadoPostulado::where('nombre', 'Seleccionado')->first()->id)
                                     ->where('postulados.idEstadoEstudiante','=', EstadoPostuladoEst::where('nombre', 'Aceptó')->first()->id);
                               })
                             ->join('ofertas', 'ofertas.id', '=', 'postulados.idOferta')
                             ->join('sedes', 'sedes.id', '=', 'ofertas.idSede')
                             ->join('empresas', 'empresas.id', '=', 'sedes.idEmpresa')
                             ->where('empresas.id', Auth::user()->getsede->getempresa->id)
                             ->where('estudiantes_modalidades.estado', EstadoPractica::where('nombre', 'Aprobada')->first()->id)
                             // ->selectRaw('estudiantes.codigo as id, concat(estudiantes.codigo, " - ", personas.nombres, " ",personas.apellidos) as nombre')
                             ->selectRaw('estudiantes.codigo as id, concat(estudiantes.codigo,  personas.nombres, personas.apellidos) as nombre')
                             ->get();
            
            foreach($estudiantes as $est)
            {
               $usuarios['estudiantes'][$con]['id']=$est->id;
               $usuarios['estudiantes'][$con]['nombre']=$est->nombre;
               $con++;
            }
        }
        else if($rol == 'Jefe inmediato')
        {
            $estudiantes = DB::table('estudiantes_modalidades')
                             ->join('estudiantes', 'estudiantes_modalidades.idEstudiante', '=', 'estudiantes.id')
                             ->join('personas', 'personas.id', '=', 'estudiantes.idPersona')
                             ->join('postulados', function ($join) {
                                $join->on('estudiantes.id', '=', 'postulados.idEstudiante')
                                     ->where('postulados.idEstatoEmpresa','=', EstadoPostulado::where('nombre', 'Seleccionado')->first()->id)
                                     ->where('postulados.idEstadoEstudiante','=', EstadoPostuladoEst::where('nombre', 'Aceptó')->first()->id);
                               })
                             ->join('ofertas', 'ofertas.id', '=', 'postulados.idOferta')
                             ->where('ofertas.idJefe', Auth::user()->id)
                             ->where('estudiantes_modalidades.estado', EstadoPractica::where('nombre', 'Aprobada')->first()->id)
                             ->selectRaw('estudiantes.codigo as id, concat(estudiantes.codigo, " - ", personas.nombres, " ",personas.apellidos) as nombre')
                             ->get();
            
            foreach($estudiantes as $est)
            {
               $usuarios['estudiantes'][$con]['id']=$est->id;
               $usuarios['estudiantes'][$con]['nombre']=$est->nombre;
               $con++;
            }
        }
        else if($rol == 'Tutor')
        {
            $estudiantes = DB::table('estudiantes_modalidades')
                             ->join('estudiantes', 'estudiantes_modalidades.idEstudiante', '=', 'estudiantes.id')
                             ->join('personas', 'personas.id', '=', 'estudiantes.idPersona')
                             ->join('practicas_tutores', function ($join) {
                                $join->on('practicas_tutores.idPracticas', '=', 'estudiantes_modalidades.id')
                                     ->where('practicas_tutores.idTutor','=', Auth::user()->id)
                                     ->where('practicas_tutores.activo','=', true);
                               })
                             ->where('estudiantes_modalidades.estado', EstadoPractica::where('nombre', 'Aprobada')->first()->id)
                             ->selectRaw('estudiantes.codigo as id, concat(estudiantes.codigo, " - ", personas.nombres, " ",personas.apellidos) as nombre')
                             ->get();
            
            foreach($estudiantes as $est)
            {
               $usuarios['estudiantes'][$con]['id']=$est->id;
               $usuarios['estudiantes'][$con]['nombre']=$est->nombre;
               $con++;
            }
        }
        
        
        
        return $usuarios;
    }
    
    public function getUsuariosbycodigoestudiante($codigo)
    {
        $jefe = null;
        $empresa = null;
        $tutor = null;
        $con=0;
        $estudiante = Estudiante::where('codigo', $codigo)->first();
        foreach($estudiante->getpostulaciones as $pos)
        {
            if($pos->getestadoempresa->nombre=='Seleccionado' && $pos->getestadoestudiante->nombre=='Aceptó')
            {
                $jefe['id'] = $pos->getoferta->getjefe->id;
                $jefe['nombre'] ='Jefe inmediato - '.$pos->getoferta->getjefe->getuser->nombres.' '.$pos->getoferta->getjefe->getuser->apellidos;
                
                foreach($pos->getoferta->getsede->getusuarios as $u)
                {
                    if($u->getrol->nombre='Empresa')
                    {
                        $empresa['id'] = $u->id;
                        $empresa['nombre'] ='Empresa - '.$u->getuser->nombres.' '.$u->getuser->apellidos;
                        break;
                    }
                }
                
                break;
            }
        }
        
        foreach($estudiante->getpracticas as $p)
        {
            if($p->getestado->nombre=='Aprobada' && sizeof($p->gettutores)>0)
            {
                $tutor['id'] = $p->gettutores[sizeof($p->gettutores) - 1]->id;
                $tutor['nombre'] = 'Tutor - '.$p->gettutores[sizeof($p->gettutores) - 1]->getuser->nombres.' '.$p->gettutores[sizeof($p->gettutores) - 1]->getuser->apellidos;
                break;
                
            }
        }
        if(session('rol')->nombre != 'Administrador Dippro')
        {
            $dippro = User::where('idRol', Rol::where('nombre', 'Administrador Dippro')->first()->id)->first();
            $dipro['id']= $dippro->id;
            $dipro['nombre']= 'Dippro - '.$dippro->getuser->nombres.' '.$dippro->getuser->apellidos;
            $usuarios[$con] = $dipro;$con++;
        }
        if(session('rol')->nombre != 'Estudiante')
        {
            
            $est['id']= User::where('identificacion', $codigo)->first()->id;
            $est['nombre']= $estudiante->codigo.' - '.$estudiante->getpersona->nombres.' '.$estudiante->getpersona->apellidos;
            $usuarios[$con] = $est;$con++;
        }
        if(session('rol')->nombre != 'Empresa')
        {
            $usuarios[$con] = $empresa;$con++;
        }
        if(session('rol')->nombre != 'Tutor')
        {
            $usuarios[$con] = $tutor;$con++;
        }
        if(session('rol')->nombre != 'Jefe inmediato')
        {
            $usuarios[$con] = $jefe;$con++;
        }
        
        return $usuarios;
    }
    
    public function postEnviarnovedad(Request $request)
    {
        if( ($request->destinatarios == null || sizeof($request->destinatarios) == 0) && session('rol')->nombre != "Graduado" && $request->idRespuesta == null && session('rol')->nombre != "Empresa") 
        {
            return ['title'=>'Error', 'content'=>'Debe seleccionar al menos un destinatario', 'type'=>'error'];
        }
        //return ['title'=>'Error', 'content'=>'Llega aca pero si me sirve', 'type'=>'error'];
        if($request->asunto == null || $request->asunto == '')
        {
            return ['title'=>'Error', 'content'=>'Debe especificar un asunto para su novedad', 'type'=>'error'];
        }
        
        if($request->contenido == null || $request->contenido == '')
        {
            return ['title'=>'Error', 'content'=>'Debe escribir el contenido de su novedad', 'type'=>'error'];
        }        
        
        $novedad = Novedad::create(['idUsuario'=>Auth::user()->id, 
                         'asunto'=>$request->asunto,
                         'contenido'=>$request->contenido,
                         'fecha'=>\Carbon\Carbon::now()]);
                         
        if($request->idRespuesta != null)
        {            
            $novedad->idRespuesta= $request->idRespuesta;
            $novedad->save();
            
            $respuesta = Novedad::find($request->idRespuesta);

            if($respuesta->asunto == env('contacto'))
            {
              $contenido = 'Buen dia,
                          <br>
                          <br>
                          <div style="font-size:1.2em;">
                          Hemos respondido tu solicitud de contacto: <br>
                          <br>'.$request->contenido.'
                          </div>                          
                          <br><br>
                          Gracias por atender a nuestros mensajes
                          <br><br>
                          Atentamente,
                          <br><br>'.env('firma');

              //dd($new->getuser);
              Mail::send('emails.correomasivo', ['dependencia' => 'Centro de Egresados', 'contenido'=>$contenido], function ($m) use ($respuesta) {
                  $m->from('sil2@unimagdalena.edu.co', env('MAIL_FROM'));
                  $m->bcc($respuesta->correo, $respuesta->nombres.' '.$respuesta->apellidos)->subject('Re: '.$respuesta->asunto);
              });
            }
            else
            {
              UsuarioNovedad::create(['idUsuario'=>$respuesta->idUsuario,
                                    'idNovedad'=>$novedad->id]);
            }
        }
        else {
          if(session('rol')->nombre!="Graduado" && session('rol')->nombre!="Empresa")
          {
            foreach($respuesta->getusuarios as $usu)
            {
                if($usu->id != Auth::user()->id)
                {
                    UsuarioNovedad::create(['idUsuario'=>$usu->id,
                                            'idNovedad'=>$novedad->id]);
                    
                }
            }
          }
          else
          {
            UsuarioNovedad::create(['idUsuario'=>User::where('idRol', Rol::where('nombre', 'Administrador Egresados')->first()->id )->first()->id,
                                    'idNovedad'=>$novedad->id]);
          }
        }
          
        return ['title'=>'Éxito', 'content'=>'Novedad enviada con éxito', 'type'=>'success'];
    }
}

