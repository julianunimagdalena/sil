<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use DB;
use Mail;

use App\Http\Requests\CambiarEstadoEmpresaRequest;
use App\Http\Requests\EstadoOfertaRequest;
use App\Http\Requests\EvaluacionRequest;
use App\Http\Requests\OfertaRequest;
use App\Http\Requests\OfertaEgresadosRequest;
use App\Http\Requests\OpcionRequest;
use App\Http\Requests\PreguntaRequest;
use App\Http\Requests\SeccionRequest;

use App\Models\Contrato;
use App\Models\Departamento;
use App\Models\Dependencia;
use App\Models\Empresa;
use App\Models\EstadoEmpresas;
use App\Models\EstadoOferta;
use App\Models\EstadoPostulado;
use App\Models\EstadoPostuladoEst;
use App\Models\EstadoPractica;
use App\Models\Evaluacion;
use App\Models\Modalidad;
use App\Models\ModalidadEvaluacion;
use App\Models\Oferta;
use App\Models\OfertaPrograma;
use App\Models\Persona;
use App\Models\PosibleRespuesta;
use App\Models\Postulado;
use App\Models\Pregunta;
use App\Models\PreguntaRespuesta;
use App\Models\Rol;
use App\Models\Salario;
use App\Models\Seccion;
use App\Models\Sede;
use App\Models\TExperiencia;
use App\Models\Tipocorreo;
use App\Models\TipoEstudiante;
use App\Models\TipoNit;
use App\Models\Tipooferta;
use App\Models\TipoPregunta;
use App\Models\User;

use Auth;
use Storage;

class AdminSilController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('adminsil');
        $this->middleware('admin', ['only'=>['getCrearoferta', 'getFormulariocrearofertajson', 'getJefesbyempresa', 'getOfertaeditarjson', 'postSaveoferta', 'CrearOEditar', 'OfertaSil', 'OfertaDipro']]);
        $this->middleware('activaroferta', ['only'=>['getCambiarestadooferta', 'getOfertajson']]);
        $this->middleware('crearoferta', ['only'=>['postSaveoferta', 'getCrearoferta']]);
    }

    //Empresas

    public function getEmpresas()
    {
        return view('admin.empresas');
    }

    public function getEmpresasjson()
    {
        $empresas_por_aprobar = Sede::with('getempresa.getconvenios.getestado')
                                    ->with('getempresa.getestadodipro')
                                    ->with('getempresa.getestadosil')
                                    ->with('getmunicipio')
                                    ->with('getmunicipio.getdepartamento')
                                    ->with('getmunicipio.getdepartamento.getpais')
                                    ->whereHas('getempresa.getestadosil', function ($est) {
                                        $est->where('nombre', 'POR APROBAR');
                                    })
                                    ->get();

        $empresas_aprobadas = Sede::with('getempresa.getconvenios.getestado')
                                    ->with('getempresa.getestadodipro')
                                    ->with('getempresa.getestadosil')
                                    ->with('getmunicipio')
                                    ->with('getmunicipio.getdepartamento')
                                    ->with('getmunicipio.getdepartamento.getpais')
                                    ->whereHas('getempresa.getestadosil', function ($est) {
                                        $est->where('nombre', '<>', 'POR APROBAR');
                                    })
                                    ->get()
                                    ->sortBy('getempresa.getestadosil.nombre')->values()->all();

        $res = [];
        foreach ($empresas_por_aprobar as $key => $value) array_push($res, $value);
        foreach ($empresas_aprobadas as $key => $value) array_push($res, $value);

        return $res;
    }

    public function getEstadosempresas($estado)
    {
        if($estado=='POR APROBAR')
        {
            return EstadoEmpresas::whereIn('nombre', ['ACEPTADA', 'RECHAZADA'])->get();
        }
        else if($estado=='ACEPTADA')
        {
            return EstadoEmpresas::where('nombre', 'CANCELADA')->get();
        }
        else if($estado=='RECHAZADA' || $estado=='CANCELADA')
        {
            return EstadoEmpresas::where('nombre', 'ACEPTADA')->get();
        }
    }


    public function postCambiarestadoempresas(CambiarEstadoEmpresaRequest $request)
    {
        $empresas = Empresa::with('getsedes.getusuarios.getuser')->find($request->id);
        $empresa = $empresas->nombre;
        $personaEmail = $empresas->getsedes[0]->getusuarios[0]->getuser;
        $email = $empresas->getsedes[0]->correo;

        if(session('rol')->nombre=='Administrador Dippro')
        {
            $empresas->estadoDipro = $request->estado['id'];
            $empresas->save();
            $estado = 'Aceptada por Dippro';
        }
        else if(session('rol')->nombre=='Administrador Egresados')
        {
            $empresas->estadoSil = $request->estado['id'];
            $empresas->save();
            $estado =  '<b>Aceptada.</b>
                        <br>
                        Puede empezar a utilizar los servicios de la platadorma SIL Unimagdalena.';
        }

        if($empresas->getestadosil->nombre == 'RECHAZADA' || $empresas->getestadodipro->nombre == 'RECHAZADA')
        {
            // $sede = Sede::where('idEmpresa', $empresas->id)->first();
            // $usuario = User::where('idSede', $sede->id)->first();
            // $persona = Persona::find($usuario->idPersona);
            // $representante = Persona::find($empresas->idPersona);

            // $representante->delete();
            // $usuario->delete();
            // $persona->delete();
            // $sede->delete();
            // $empresas->delete();
            $empresas->motivo_cancelacion = $request->motivo_cancelacion;
            $empresas->save();
            $estado =  '<b>Rechazada</b> debido a:
                        <br><br>
                        '.$request->motivo_cancelacion;
        }

        $contenido =   'Buen dia,
                        <br>
                        <br>
                        <div style="font-size:1.2em;">
                            Su empresa <b>'.$empresa.'</b> ha sido '.$estado.'
                        </div>
                        <br><br>
                        <a href="http://sil.unimagdalena.edu.co">Para mas información visite: sil.unimagdalena.edu.co</a>
                        <br><br>
                        Gracias por atender a nuestros mensajes
                        <br><br>
                        Atentamente,
                        <br><br>'.env('firma');

        Mail::send('emails.correomasivo', ['dependencia' => 'Centro de Egresados', 'contenido'=>$contenido], function ($m) use ($personaEmail, $email) {
            $m->from('sil2@unimagdalena.edu.co', env('MAIL_FROM'));
            $m->to($email, $personaEmail->nombres.' '.$personaEmail->apellidos)->subject('Situacion Empresa - SIL Unimagdalena');
        });

        return ['title'=>'Cambio exitoso', 'content'=>'El cambio de estado se realizo con exito', 'type'=>'success'];
        return ['title'=>'Error', 'content'=>'No se pudo realizar el cambio de estado', 'type'=>'error'];
    }

    // Ofertas

    public function getOfertas()
    {
        if(session('rol')->nombre == 'Administrador Dippro' )
        {
            $soloDipro=1;
            $soloSil=0;
        }
        else if(session('rol')->nombre == 'Administrador Egresados' )
        {
            $soloDipro=0;
            $soloSil=1;
        }

        return view('admin.ofertas', compact('soloSil', 'soloDipro'));
    }

    public function getOfertasjson()
    {
        if(session('rol')->nombre == 'Administrador Egresados')
        {
            $ofertas = Oferta::with('gettipo')
                             ->with('getestado')
                             ->with('getprogramas')
                             ->where('idTipo', Tipooferta::where('nombre','Graduados')->first()->id)
                             ->orderBy('estado', 'asc')
                             ->orderBy('fechaCierre', 'desc')
                             ->get();

            foreach($ofertas as $oferta)
            {
                if($oferta->gettipo->nombre=='Graduados')
                {
                    $oferta->salario = $oferta->getsalario->rango;
                }
            }
        }
        else if(session('rol')->nombre == 'Administrador Dippro')
        {
            $ofertas = Oferta::with('gettipo')
                             ->with('getestado')
                             ->with('getprogramas')
                             ->where('idTipo', Tipooferta::where('nombre','Practicantes')->first()->id)
                             ->orderBy('fechaCierre', 'desc')
                             ->get();
        }

        foreach($ofertas as $oferta)
        {
            $programas = '';
            foreach($oferta->getprogramas as $programa)
            {
                $programas = $programas.$programa->nombre.', ';
            }
            // $programas = substr($programas, 0, strlen($programas) - 2);
            // dd($programas);
            $oferta->programas = substr($programas, 0, strlen($programas) - 2);
        }


        return $ofertas;
    }

    public function getCartaarl($id)
    {
        $oferta = Oferta::find($id);

        $nombre = $oferta->carta;

        $path = storage_path('app/oferta/'.$nombre);

        return \Response::download($path);
    }

    public function getEstadosoferta($id)
    {
        $oferta = Oferta::find($id);

        if($oferta->getestado->nombre == 'Por aprobar')
        {
            $nombres = ['Publicada', 'Rechazada', 'Errada'];
            return EstadoOferta::whereIn('nombre', $nombres )->get();
        }
        else if($oferta->getestado->nombre == 'Publicada')
        {
            $nombres = ['Finalizada', 'Cancelada'];
            return EstadoOferta::whereIn('nombre', $nombres )->get();
        }
        else if($oferta->getestado->nombre == 'Errada')
        {
            $nombres = ['Publicada', 'Rechazada', 'Finalizada', 'Cancelada'];
            return EstadoOferta::whereIn('nombre', $nombres )->get();
        }
        else
        {
            return EstadoOferta::where('nombre', $oferta->getestado->nombre )->get();
        }
    }

    public function postCambiarestadooferta(EstadoOfertaRequest $request)
    {
        $id = $request->id;
        $oferta = Oferta::find($request->id);
        $oferta->estado = $request->estado['id'];
        if($request->mensaje) {
            $oferta->mensaje_estado = $request->mensaje;
        }
        $oferta->save();

        if($request->estado['nombre']=='Rechazada' || $request->estado['nombre']=='Errada') {

            $estado = EstadoOferta::find($request->estado['id']);
            $contrato = Contrato::find($oferta->idContrato);
            $u = $oferta->getsede;
            $u['estado'] = $estado->nombre;
            $contenido = 'Buen dia,
                          <br>
                          <br>
                          <div style="font-size:1.2em;">
                              Su oferta para el cargo de <b>'.$oferta->nombre.'</b>
                              Con contrato de <b>'.$contrato->nombre.'</b>
                              ha sido <b>'.$estado->nombre.'</b> debido a:
                              <br>
                              <br>
                              <b>'.$request->mensaje.'</b>
                          </div>
                          <br><br>
                          <a href="http://sil.unimagdalena.edu.co">Para mas información visite: sil.unimagdalena.edu.co</a>
                          <br><br>
                          Gracias por atender a nuestros mensajes
                          <br><br>
                          Atentamente,
                          <br><br>'.env('firma');

            // return $contenido;
            //dd($new->getuser);
            Mail::send('emails.correomasivo', ['dependencia' => 'Centro de Egresados', 'contenido'=>$contenido], function ($m) use ($u) {
                $m->from('sil2@unimagdalena.edu.co', env('MAIL_FROM'));
                $m->to($u->correo, $u->getusuarios[0]->getuser->nombres.' '.$u->getusuarios[0]->getuser->apellidos)->subject('Oferta '.$u->estado);
            });
        }
        else if($request->estado['nombre']=='Publicada') {
            $estado = EstadoOferta::find($request->estado['id']);
            $contrato = Contrato::find($oferta->idContrato);
            $u = $oferta->getsede->getusuarios[0]->getuser;
            $correo = $oferta->getsede->correo;
            $u['estado'] = $estado->nombre;
            $contenido = 'Buen dia,
                          <br>
                          <br>
                          <div style="font-size:1.2em;">
                              Su oferta para el cargo de <b>'.$oferta->nombre.'</b>
                              Con contrato de <b>'.$contrato->nombre.'</b>
                              ha sido <b>'.$estado->nombre.'</b>
                          </div>
                          <br><br>
                          <a href="http://sil.unimagdalena.edu.co">Para mas información visite: sil.unimagdalena.edu.co</a>
                          <br><br>
                          Gracias por atender a nuestros mensajes
                          <br><br>
                          Atentamente,
                          <br><br>'.env('firma');

            //dd($new->getuser);
            Mail::send('emails.correomasivo', ['dependencia' => 'Centro de Egresados', 'contenido'=>$contenido], function ($m) use ($u, $correo) {
                $m->from('sil2@unimagdalena.edu.co', env('MAIL_FROM'));
                $m->to($correo, $u->nombres.' '.$u->apellidos)->subject('Oferta '.$u->estado);
            });
        }


        if($oferta->idTipo == Tipooferta::where('nombre', 'Graduados')->first()->id && $oferta->estado == EstadoOferta::where('nombre', 'Publicada')->first()->id)
        {

            $personas = Persona::whereHas('getestudiantes.getprograma.getprograma.getofertaprograma', function ($op) use ($oferta) {
                $op->where('idOferta', $oferta->id);
            })
            ->select('correo', 'nombres', 'apellidos')
            ->where('recibir_mails', 1)
            ->get();

            // $personas = Persona::whereHas('getestudiantes', function($q)use ($id){
            //     $q->where('idTipo', '=',TipoEstudiante::where('nombre', 'Graduado')->first()->id)
            //       ->whereHas('getprograma', function($qu)use ($id){
            //         $qu->whereHas('getprograma', function($que)use ($id){
            //             $que->whereHas('getofertaprograma', function($quer)use ($id){
            //                 $quer->whereHas('getoferta', function($query)use ($id){
            //                     $query->where('id', '=', $id);
            //                 });
            //             });
            //         });
            //     });
            // })
            // ->select('correo', 'nombres', 'apellidos')
            // ->where('recibir_mails', 1)
            // ->whereHas('getptipocorreo', function($q){
            //     $q->where('idTipocorreo','=',Tipocorreo::where('nombre', 'Convocatorias')->first()->id);
            // })
            // ->get();

            // return compact('oferta', 'personas');
            $contenido = 'Buen dia,
                          <br>
                          <br>
                          <div style="font-size:1.2em;">
                          Apreciado graduado hay una oferta laboral disponible para ti. <br>
                          <br>Cargo: <b>'.$oferta->nombre.'</b>
                          <br>Rango salarial: <b>'.$oferta->getsalario->rango.'</b>
                          <br>Fecha de cierre: <b>'.$oferta->fechaCierre.'</b>
                          </div>
                          <br><br>
                          <a href="http://sil.unimagdalena.edu.co">Para mas información visite: sil.unimagdalena.edu.co</a>
                          <br><br>
                          Gracias por atender a nuestros mensajes
                          <br><br>
                          Atentamente,
                          <br><br>'.env('firma');

            //dd($new->getuser);
            Mail::send('emails.correomasivo', ['dependencia' => 'Centro de Egresados', 'contenido'=>$contenido], function ($m) use ($personas) {
                $m->from('sil2@unimagdalena.edu.co', env('MAIL_FROM'));
                foreach ($personas as $p)
                {
                    $m->bcc($p->correo, $p->nombres.' '.$p->apellidos)->subject('Oferta laboral');
                }

            });

        }

        return ['title'=>'Exito', 'content'=>'Oferta '.$request->estado['nombre'].' con exito.', 'type'=>'success'];
    }

    // public function getPrueba()
    // {
    //     $id = 26;
    //     $oferta = Oferta::find($id);
    //     $oferta->estado = 2;


    //     return "Hola mundo";//['title'=>'Exito', 'content'=>'Oferta '.$request->estado['nombre'].' con exito.', 'type'=>'success'];
    // }

    public function getOfertajson($id)
    {
        $oferta = Oferta::find($id);

        $postulados = [];
        foreach ($oferta->getpostulados as $kpos => $pos) {
            $programas = [];
            foreach ($pos->getpersona->getestudiantes as $kest => $est) array_push($programas, $est->getprograma->getprograma->nombre);

            array_push($postulados, [
                'identificacion' => $pos->getpersona->identificacion,
                'nombre' => $pos->getpersona->nombres.' '.$pos->getpersona->apellidos,
                'estado_empresa' => $pos->getestadoempresa->nombre,
                'estado' => $pos->getestadoestudiante->nombre,
                'programas' => implode(', ', $programas)
            ]);
        }

        return [
            'nombre' => $oferta->nombre,
            'salario' => $oferta->getsalario->rango,
            'gettipo' => [ 'nombre' => $oferta->gettipo->nombre ],
            'tipo_contrato' => $oferta->getcontrato->nombre,
            'experiencia' => $oferta->getexperiencia ? $oferta->getexperiencia->nombre : $oferta->experiencia,
            'lugar' => $oferta->getmunicipio->nombre.' - '.$oferta->getmunicipio->getdepartamento->getpais->nombre,
            'vacantes' => $oferta->vacantes,
            'fecha_cierre' => $oferta->fechaCierre,
            'programas' => $oferta->getprogramas->implode('nombre', ', '),
            'herramientas' => $oferta->herramientasInformaticas,
            'perfil' => $oferta->perfil,
            'funciones' => $oferta->funciones,
            'observaciones' => $oferta->observaciones,
            'empresa' => $oferta->getsede->getempresa->nombre,
            'postulados' => $postulados
        ];
    }

    public function getEvaluaciones()
    {
        return view('admin.evaluaciones');
    }

    public function getCrearevaluacion($id=null)
    {
        if($id == null)
        {
            $id=0;
        }

        return view('admin.crearevaluacion', compact('id'));
    }

    public function getDatosevaluacion()
    {
        if(session('rol')->nombre=="Administrador Dippro")
        {
            $data['roles'] = Rol::whereIn('nombre', ['Tutor', 'Jefe inmediato', 'Estudiante', 'Empresa'])->get();
        }
        else if(session('rol')->nombre=="Administrador Egresados")
        {
            $data['roles'] = Rol::whereIn('nombre', ['Estudiante', 'Empresa', 'Egresado'])->get();
        }

        $data['tipoPreguntas'] = TipoPregunta::all();
        $data['posiblesRespuestas'] = PosibleRespuesta::all();
        $data['modalidades'] = Modalidad::all();

        return $data;
    }

    public function postSaveeval(EvaluacionRequest $request)
    {
        if($request->id != null)
        {
            $evaluacion = Evaluacion::find($request->id);
            ModalidadEvaluacion::where('idEvaluacion', $evaluacion->id)->delete();
        }
        else
        {
            if($request->getrolevaluado['nombre'] !='Estudiante' || ($request->getrolevaluador['nombre']=='Estudiante' && $request->getrolevaluado['nombre']=='Estudiante'))
            {
                $evaluacion = Evaluacion::where('idRolevaluador', $request->getrolevaluador['id'])
                                        ->where('idRolevaluado', $request->getrolevaluado['id'])
                                        ->first();
                if(sizeof($evaluacion)>0)
                {
                    $msj=[
                            'title'=>'Error',
                            'content'=>'Ya existe una evaluación con el mismo rol evaluador y rol evaluado',
                            'type'=>'error',
                        ];

                    $data['msj'] = $msj;
                    $data['evaluacion']=$this->getEvaluacion($evaluacion->id);
                    return $data;
                }

            }
            $evaluacion = new Evaluacion();
        }

        $evaluacion->nombre = $request->nombre;
        $evaluacion->descripcion = $request->descripcion;
        $evaluacion->idRolevaluador = $request->getrolevaluador['id'];
        $evaluacion->idRolevaluado = $request->getrolevaluado['id'];
        $evaluacion->save();

        if($request->getmodalidades != null)
        {
            foreach($request->getmodalidades as $modalidad)
            {
                ModalidadEvaluacion::create(['idModalidad'=>$modalidad['id'], 'idEvaluacion'=>$evaluacion->id]);
            }
        }


        $msj=[
                'title'=>'Éxito',
                'content'=>'Evaluación guardada con éxito',
                'type'=>'success',
            ];

        $data['msj'] = $msj;
        $data['evaluacion']=$this->getEvaluacion($evaluacion->id);
        return $data;
    }

    public function getEvaluacion($id=null)
    {
        if($id==null)
        {
            return Evaluacion::with('getrolevaluador')
                             ->with('getrolevaluado')
                             ->with('getmodalidades')
                             ->with('getsecciones.getsecciones.getpreguntas.gettipo')
                             ->with('getsecciones.getsecciones.getpreguntas.getpivoterespuesta.getrespuesta')
                             ->with('getsecciones.getpreguntas.gettipo')
                             ->with('getsecciones.getpreguntas.getpivoterespuesta.getrespuesta')
                            //  ->where('id', $id)
                             ->get();

        }
        else
        {
            return Evaluacion::with('getrolevaluador')
                             ->with('getrolevaluado')
                             ->with('getmodalidades')
                             ->with('getsecciones.getsecciones.getpreguntas.gettipo')
                             ->with('getsecciones.getsecciones.getpreguntas.getpivoterespuesta.getrespuesta')
                             ->with('getsecciones.getpreguntas.gettipo')
                             ->with('getsecciones.getpreguntas.getpivoterespuesta.getrespuesta')
                             ->where('id', $id)
                             ->first();
        }

    }

    public function postSaveseccion(SeccionRequest $request)
    {
        if($request->id != null)
        {
            $seccion = Seccion::find($request->id);
        }
        else
        {
            $seccion = new Seccion();
        }

        $seccionPadre = Seccion::whereNull('idPadre')
                               ->where('id', $request->getpadre['id'])
                               ->first();
        // dd($seccionPadre);
        if(sizeof($seccionPadre) == 0 && $request->getpadre != null)
        {
            $msj=[
                    'title'=>'Error',
                    'content'=>'Esta sección no puede tener secciones internas',
                    'type'=>'error',
                ];
        }
        else if(sizeof($seccionPadre) > 0 && $request->id != null && sizeof(Seccion::find($request->id)->getsecciones) > 0)
        {
            $msj=[
                    'title'=>'Error',
                    'content'=>'Esta sección no puede ser contenida por otra sección',
                    'type'=>'error',
                ];
        }
        else if( $request->getpadre == null || sizeof($seccionPadre) > 0)
        {
            if($request->getpadre == null)
            {
                $seccion->idEvaluacion = $request->idEvaluacion;
            }
            else
            {
                $seccion->idEvaluacion = null;
            }

            $seccion->idPadre = $request->getpadre['id'];
            $seccion->enunciado = $request->enunciado;
            $seccion->save();

            $msj=[
                    'title'=>'Éxito',
                    'content'=>'Sección guardada con éxito',
                    'type'=>'success',
                ];
        }


        $data['msj'] = $msj;
        $data['evaluacion']=$this->getEvaluacion($request->idEvaluacion);
        return $data;
    }

    public function getSeccionesbyeval($id)
    {
        $idSecciones = Seccion::where('idEvaluacion', $id)
                              ->select('id')
                              ->get()
                              ->toArray();

        $seccionesHijas = DB::table('secciones')
                            ->whereIn('idPadre', $idSecciones);


        $secciones = DB::table('secciones')
                       ->where('idEvaluacion', $id)
                       ->union($seccionesHijas)
                       ->get();

        return $secciones;
    }

    public function getSeccion($id = null)
    {
        $seccion = Seccion::with('getpadre')
                          ->where('id', $id)
                          ->first();

        return $seccion;
    }

    public function getPregunta($id)
    {
        $pregunta = Pregunta::with('getseccion')
                            ->with('gettipo')
                            ->with('getposiblesrespuestas')
                            ->where('id', $id)
                            ->first();

        $respuestas = array();
        foreach($pregunta->getpivoterespuesta as $respuesta)
        {
            if($respuesta->estado)
            {
                array_push($respuestas, $respuesta->getrespuesta);
            }
        }
        $pregunta->respuestas = $respuestas;
        return $pregunta;
    }

    public function getEliminarseccion($id)
    {
        $seccion = Seccion::find($id);
        $estado = !$seccion->estado;
        $idEvaluacion=0;
        if($seccion->idEvaluacion == null)
        {
            $idEvaluacion = $seccion->getpadre->idEvaluacion;
        }
        else
        {
            $idEvaluacion = $seccion->idEvaluacion;
        }
        $idPreguntas = Pregunta::where('idSeccion', $id)->select('id');
        $idHijas = Seccion::where('idPadre', $id)->select('id')->get()->toArray();
        $idPre = Pregunta::whereIn('idSeccion', $idHijas)->select('id')->union($idPreguntas)->get()->toArray();
        PreguntaRespuesta::whereIn('idPregunta', $idPre)->update(['estado'=>$estado]);
        Pregunta::where('idSeccion', $id)->update(['estado'=>$estado]);

        Pregunta::whereIn('idSeccion', $idHijas)->update(['estado'=>$estado]);
        Seccion::where('idPadre', $id)->update(['estado'=>$estado]);
        $seccion->estado=$estado;
        $seccion->save();

        $msj=[
            'title'=>'Éxito',
            'content'=>'Sección eliminada con éxito',
            'type'=>'success',
        ];

        $data['msj'] = $msj;
        $data['evaluacion']=$this->getEvaluacion($idEvaluacion);
        return $data;
    }

    public function postSavepregunta(PreguntaRequest $request)
    {
        if($request->id != null)
        {
            $pregunta = Pregunta::find($request->id);
            PreguntaRespuesta::where('idPregunta', $request->id)->update(['estado'=>false]);
        }
        else
        {
            $pregunta = new Pregunta();
        }
        $seccion = Seccion::find($request->getseccion['id']);
        if($seccion->idEvaluacion == null)
        {
            $idEvaluacion = $seccion->getpadre->idEvaluacion;
        }
        else
        {
            $idEvaluacion = $seccion->idEvaluacion;
        }

        $pregunta->idSeccion = $request->getseccion['id'];
        $pregunta->idTipoPregunta = $request->gettipo['id'];
        $pregunta->enunciado = $request->enunciado;
        $pregunta->minimo = $request->minimo;
        $pregunta->maximo = $request->maximo;
        $pregunta->save();

        if($request->gettipo['nombre']=='Cualitativa')
        {
            foreach($request->respuestas as $pos)
            {
                $preguntaRespuesa = PreguntaRespuesta::where('idPregunta', $request->id)->where('idRespuesta', $pos['id'])->first();
                if(sizeof($preguntaRespuesa) > 0)
                {
                    $preguntaRespuesa->estado = true;
                    $preguntaRespuesa->save();
                }
                else
                {
                    PreguntaRespuesta::create(['idPregunta'=>$pregunta->id,'idRespuesta'=>$pos['id']]);
                }
            }
        }

        $msj=[
            'title'=>'Éxito',
            'content'=>'Pregunta guardada con éxito',
            'type'=>'success',
        ];

        $data['msj'] = $msj;
        $data['evaluacion']=$this->getEvaluacion($idEvaluacion);
        return $data;

    }


    public function getEliminarpregunta($id)
    {
        $pregunta = Pregunta::find($id);
        $estado = !$pregunta->estado;

        $seccion = $pregunta->getseccion;

        if($seccion->idEvaluacion == null)
        {
            $idEvaluacion = $seccion->getpadre->idEvaluacion;
        }
        else
        {
            $idEvaluacion = $seccion->idEvaluacion;
        }

        PreguntaRespuesta::where('idPregunta', $id)->update(['estado'=>$estado]);

        $pregunta->estado = $estado;
        $pregunta->save();

        $msj=[
            'title'=>'Éxito',
            'content'=>'Pregunta eliminada con éxito',
            'type'=>'success',
        ];

        $data['msj'] = $msj;
        $data['evaluacion']=$this->getEvaluacion($idEvaluacion);
        return $data;
    }

    public function postSaveopcionrespuesta(OpcionRequest $request)
    {
        $respuestas = $request->respuestas;
        $opcion = $request->opcion;
        $opcion['nombre'] = str_replace('ú', 'Ú', str_replace('ó', 'Ó', str_replace('í', 'Í', str_replace('é', 'É', str_replace('á', 'Á', strtoupper($opcion['nombre']))))));

        $pos_res = PosibleRespuesta::where('nombre', $opcion['nombre'])->first();

        if(sizeof($pos_res) == 0)
        {
            $pos_res = PosibleRespuesta::create(['nombre'=> $opcion['nombre']]);
        }
        $bool = false;
        foreach($respuestas as $respuesta)
        {
            if($respuesta['nombre'] == $opcion['nombre'])
            {
                $bool=true;
                break;
            }
        }
        if(!$bool)
        {
            array_push($respuestas, $pos_res);
        }

        $data['respuestas'] = $respuestas;
        $data['posiblesRespuestas'] = PosibleRespuesta::all();
        return $data;
    }

    public function getCambiarestadoevaluacion($id)
    {
        $evaluacion = Evaluacion::find($id);
        $evaluacion->estado = !$evaluacion->estado;
        $evaluacion->save();
    }

    public function getCrearoferta($id = null)
    {
        $usuario = Auth::user();
        return view('admin.crearoferta', compact('id', 'usuario'));
    }

    public function getFormulariocrearofertajson()
    {
        $data['empresas'] = DB::table('empresas')
                              ->join('sedes', 'sedes.idEmpresa', '=', 'empresas.id')
                              ->join('municipios', 'municipios.id', '=', 'sedes.idMunicipio')
                              ->selectRaw('concat(empresas.nombre, " - ", municipios.nombre) as nombre, sedes.id')
                              ->where('estadoDipro', EstadoEmpresas::where('nombre', 'ACEPTADA')->first()->id)
                              ->get();

        $data['programas'] = Dependencia::where('idTipo', 1)->whereNotNull('codigoPrograma')->get();
        $data['salarios'] = Salario::get();
        $data['contratos'] = Contrato::get();
        $data['tipooferta'] = Tipooferta::get();

        return $data;
    }

    public function getJefesbyempresa($id)
    {
        return User::getJefes($id);
    }

    public function getOfertaeditarjson($id)
    {
        $oferta = Oferta::with('getestado')
                        ->with('getmunicipio')
                        ->with('getsede')
                        ->where('id', $id)
                        ->first();

        $oferta->fechacierre = $oferta->fechaCierre;

        $oferta->tipo = $oferta->gettipo;
        if($oferta->gettipo->nombre=='Practicantes')
        {
            $oferta->jefe = User::getjefe($oferta->idJefe)[0];
        }
        else
        {
            $oferta->salario = $oferta->getsalario;
            $oferta->contrato = $oferta->getcontrato;
        }
        $oferta->informaticas = $oferta->herramientasInformaticas;
        $oferta->programas = $oferta->getprogramas;
        return $oferta;
    }

    public function postSaveoferta(OfertaRequest $request)
    {
        $sede = Sede::find($request->empresa['id']);
        if($sede->getempresa->getestadodipro->nombre == 'ACEPTADA')
        {
            $this->OfertaDipro($request, $sede);
        }
        else
        {
            return ['title'=>'Error', 'content'=>'La empresa no está aceptada', 'type'=>'Error'];
        }

        return ['title'=>'Registro exitoso', 'content'=>'Oferta guardada con exito', 'type'=>'success'];
    }

    public function CrearOEditar(OfertaRequest $request, $sede)
    {
        if($request->id == null)
        {
            $oferta = new Oferta();
            $oferta->idSede = $sede->id;
        }
        else
        {
            $oferta = Oferta::find($request->id);
        }

        return $oferta;
    }

    public function OfertaDipro(OfertaRequest $request, $sede)
    {
        $oferta = $this->CrearOEditar($request, $sede);

        $oferta->idTipo = Tipooferta::where('nombre','Practicantes')->first()->id;
        $oferta->idJefe = $request->jefe['id'];
        $oferta->nombre = $request->nombre;
        $oferta->vacantes = $request->vacantes;
        $oferta->salario = $request->salario;
        $oferta->salud = $request->salud;
        $oferta->arl = $request->arl;
        $oferta->perfil = $request->perfil;
        $oferta->observaciones = $request->observaciones;
        $oferta->funciones = $request->funciones;
        $oferta->creada_por_dipro = true;
        $oferta->fechaCierre = \Carbon\Carbon::now()->subDay();
        $oferta->estado = EstadoOferta::where('nombre', 'Finalizada')->first()->id;
        $oferta->save();

        OfertaPrograma::where('idOferta', $request->id)->delete();
        foreach($request->programas as $programa)
        {
            OfertaPrograma::create(['idOferta'=>$oferta->id, 'idDependencia'=>$programa['id']]);
        }

        foreach($request->estudiantes as $est)
        {
            $postulado = new Postulado();
            $postulado->idEstudiante = $est['id'];
            $postulado->idOferta = $oferta->id;
            $postulado->idEstatoEmpresa = EstadoPostulado::where('nombre', 'Seleccionado')->first()->id;
            $postulado->idEstadoEstudiante = EstadoPostuladoEst::where('nombre', 'Aceptó')->first()->id;
            $postulado->save();
        }
    }

    public function postBuscarestudiantesbyprogramas(Request $request)
    {
        $programas = array();

        for($i=0; $i < sizeof($request->all()) ; $i++)
        {
            array_push($programas, $request[$i]['id']);
        }
        $practicantes = DB::table('estudiantes')
                          ->join('tipoestudiantes', 'estudiantes.idTipo', '=', 'tipoestudiantes.id')
                          ->join('personas', 'estudiantes.idPersona', '=', 'personas.id')
                          ->join('dependencias', 'estudiantes.idPrograma', '=', 'dependencias.id')
                          ->join('estudiantes_modalidades', function($join){
                              $join->on('estudiantes.id', '=', 'estudiantes_modalidades.idEstudiante')
                                   ->where('estado','!=', EstadoPractica::where('nombre', 'Aprobada')->first()->id);
                          })
                          ->join('modalidades', 'estudiantes_modalidades.idModalidad', '=', 'modalidades.id')
                          ->join('estado_practicas', 'estudiantes_modalidades.estado', '=', 'estado_practicas.id')
                          ->leftJoin('postulados', 'estudiantes.id', '=', 'postulados.idEstudiante')
                          ->leftJoin('estadopostulados', function($join){
                              $join->on('estadopostulados.id', '=', 'postulados.idEstatoEmpresa')
                                   ->where('estadopostulados.nombre','=', 'Seleccionado');
                          })
                          ->leftJoin('estadopostuladosestudiante', function($join){
                              $join->on('estadopostuladosestudiante.id', '=', 'postulados.idEstadoEstudiante')
                                   ->where('estadopostuladosestudiante.nombre','=', 'Aceptó');
                          })
                          ->leftJoin('ofertas', 'ofertas.id', '=', 'postulados.idOferta')
                          ->leftJoin('sedes', 'sedes.id', '=', 'ofertas.idSede')
                          ->leftJoin('empresas', 'empresas.id', '=', 'sedes.idEmpresa')
                          ->selectRaw('distinct estudiantes.id, concat(estudiantes.codigo," - ",personas.nombres, " ", personas.apellidos) as nombre')
                          ->where('tipoestudiantes.nombre', 'Prácticas')
                          ->whereIn('estudiantes.idPrograma', $programas)
                          ->get();

        return $practicantes;

    }

    public function getDatosempresa($id)
    {
        $empresa = Empresa::with('gettiponit')
                          ->with('getsedes.getusuarios.getuser')
                          ->with('getsedes.getmunicipio.getdepartamento.getpais')
                          ->with('gettipoempleador')
                          ->with('getrepresentante')
                          ->with('getactividadeconomica')
                          ->where('id', $id)
                          ->first();

        return $empresa;
    }

    public function getDescargarnit($id)
    {
        $empresa = Empresa::find($id);

        if(sizeof($empresa) == 0)
        {
            $data = ['error'=>'Usted no descargar este documento'];
            return redirect('/adminsil/empresas')->with($data);
        }

        $nombre = $empresa->file_nit;

        $path = storage_path('app/empresas/'.$nombre);

        return \Response::download($path);

    }

    public function getIndicadores () {
        return view('admin.indicadores');
    }

    public function getDatosIndicadores () {
        $empresas = Empresa::with('gettiponit')->with('getsedes.getmunicipio.getdepartamento')->where('estadoSil', 2)->get();

        $graduados = Persona::with('getgenero')
                            ->whereHas('getusuario.roles', function ($query) {
                                $query->where('nombre', 'Graduado');
                            })->get();

        $ofertas = Oferta::with('getestado')->get();

        $res['graduadosMasculinos'] = 0;
        $res['graduadosFemeninos'] = 0;
        foreach ($graduados as $key => $value) {
            if($value->getgenero['nombre'] == 'MASCULINO') $res['graduadosMasculinos']++;
            if($value->getgenero['nombre'] == 'FEMENINO') $res['graduadosFemeninos']++;
        }

        $res['empresas'] = $empresas;
        $res['numeroGraduados'] = $graduados->count();
        $res['ofertas'] = $ofertas;

        return $res;
    }

    public function getReporteEmpresas () {
        return \Excel::create('EmpresasSil', function ($excel) {
            $excel->sheet('Empresas', function ($sheet) {
                $empresas = Empresa::with('getrepresentante')
                                    ->with('gettiponit')
                                    ->with('gettipoempleador')
                                    ->with('getactividadeconomica')
                                    ->where('estadoSil', 2)
                                    ->get();

                $sheet->row(1, [
                    'Nombre',
                    'Pagina Web',
                    'Representante Legal',
                    'Tipo NIT',
                    'NIT',
                    'Tipo empleador',
                    'Actividad economica'
                ]);

                $sheet->row(1, function ($row) {
                    $row->setBackground('#BDBDBD');
                });

                foreach ($empresas as $key => $empresa) {
                    $sheet->row($key+2, [
                        $empresa->nombre,
                        $empresa->paginaWeb,
                        $empresa->getrepresentante->nombres.' '.$empresa->getrepresentante->apellidos,
                        $empresa->gettiponit->nombre,
                        $empresa->nit,
                        $empresa->gettipoempleador->nombre,
                        $empresa->getactividadeconomica->nombre
                    ]);
                }
            });
        })->download('xlsx');
    }

    public function postOfertas (Request $request) {
        $deps = Departamento::whereHas('getpais', function ($query) {
                                $query->where('nombre', 'COLOMBIA');
                            })->with('municipios')
                            ->orderBy('nombre')->get();

        $res_deps = [];
        foreach ($deps as $kdep => $dep) {
            array_push($res_deps, [
                'id' => $dep->id,
                'nombre' => $dep->nombre,
                'municipios' => $dep->municipios
            ]);
        }

        $progs = Dependencia::whereHas('tipo', function ($query) {
            $query->where('nombre', 'Dirección de programa');
        })->orderBy('nombre')->get();

        $res_pros = [];
        foreach ($progs as $kprog => $prog) {
            array_push($res_pros, [
                'nombre' => $prog->nombre,
                'id' => $prog->id
            ]);
        }

        return [
            'departamentos' => $res_deps,
            'salarios' => Salario::all(),
            'tipos_contrato' => Contrato::all(),
            'experiencias' => TExperiencia::all(),
            'programas' => $res_pros
        ];
    }

    public function getOfertaEgresados ($id) {
        $ofr = Oferta::with('getprogramas', 'getmunicipio')->find($id);

        $progs = [];
        foreach ($ofr->getprogramas as $kprog => $prog) {
            array_push($progs, [
                'id' => $prog->id,
                'nombre' => $prog->nombre
            ]);
        }

        $res = [
            'id' => $ofr->id,
            'empresa' => $ofr->empresa_egresados,
            'correo_empresa' => $ofr->correo_egresados,
            'nombre' => $ofr->nombre,
            'vacantes' => $ofr->vacantes,
            'fecha_cierre' => $ofr->fechaCierre,
            'salario' => $ofr->idSalario,
            'tipo_contrato' => $ofr->idContrato,
            'experiencia' => $ofr->idExperiencia,
            'perfil' => $ofr->perfil,
            'funciones' => $ofr->funciones,
            'observaciones' => $ofr->observaciones,
            'herramientas' => $ofr->herramientasInformaticas,
            'programas' => $progs,
            'municipio' => $ofr->getmunicipio->id,
            'departamento_id' => $ofr->getmunicipio->idDepartamento
        ];

        return $res;
    }

    public function postGuardarOferta (OfertaEgresadosRequest $request) {
        if (!$request->id) $ofr = new Oferta();
        else $ofr = Oferta::find($request->id);

        $to_graduados = Tipooferta::where('nombre', 'Graduados')->first();
        $eo_publicada = EstadoOferta::where('nombre', 'Publicada')->first();

        $ofr->idTipo = $to_graduados->id;
        $ofr->nombre = $request->nombre;
        $ofr->vacantes = $request->vacantes;
        $ofr->estado = $eo_publicada->id;
        $ofr->perfil = $request->perfil;
        $ofr->funciones = $request->funciones;
        $ofr->observaciones = $request->observaciones ? $request->observaciones:null;
        $ofr->fechaCierre = $request->fecha_cierre;
        $ofr->idSalario = $request->salario ? $request->salario:null;
        $ofr->idContrato = $request->tipo_contrato ? $request->tipo_contrato:null;
        $ofr->herramientasInformaticas = $request->herramientas ? $request->herramientas:null;
        $ofr->creada_por_dipro = 0;
        $ofr->idMunicipio = $request->municipio;
        $ofr->idExperiencia = $request->experiencia ? $request->experiencia:null;
        $ofr->oferta_egresados = 1;
        $ofr->correo_egresados = $request->correo_empresa;
        $ofr->empresa_egresados = $request->empresa;
        $ofr->save();

        $ids = [];
        $ofr->getprogramas()->detach();
        foreach ($request->programas as $kprog => $prog) { array_push($ids, $prog['id']); }
        $ofr->getprogramas()->attach($ids);
    }
}
