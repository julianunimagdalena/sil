<?php

namespace App\Http\Controllers;

use Mail;
use DB;
use App\Providers\WebServiceSieg;
use App\Providers\WebService;
use Illuminate\Http\Request;

use App\Egresados\Core\DPDFTemplates;
use App\Egresados\Helpers\Pdf;

use App\Http\Requests\ActaRequest;
use App\Http\Requests\AddHorarioRequest;
use App\Http\Requests\AsistenciaRequest;
use App\Http\Requests\CartaRevisadaRequest;
use App\Http\Requests\CharlaRequest;
use App\Http\Requests\ConferenciaRequest;
use App\Http\Requests\ConvenioRevisadoRequest;
use App\Http\Requests\CorreoRequest;
use App\Http\Requests\PracticaRequest;
use App\Http\Requests\RechazarRequest;
use App\Http\Requests\RenovarRequest;
use App\Http\Requests\SuscribirRequest;
use App\Http\Requests\UsuarioRequest;

use App\Models\ActaRenovacion;
use App\Models\Asistencia;
use App\Models\Carta;
use App\Models\Conferencia;
use App\Models\ConferenciaPrograma;
use App\Models\ConferenciaPeriodo;
use App\Models\CoordinadorDependencia;
use App\Models\Convenio;
use App\Models\Dependencia;
use App\Models\Empresa;
use App\Models\EstadoCarta;
use App\Models\EstadoConvenio;
use App\Models\EstadoPostulado;
use App\Models\EstadoPostuladoEst;
use App\Models\EstadoPractica;
use App\Models\Estudiante;
use App\Models\Modalidad;
use App\Models\ModalidadEstudiante;
use App\Models\Oferta;
use App\Models\Pais;
use App\Models\Periodo;
use App\Models\Persona;
use App\Models\Postulado;
use App\Models\PracticaTutor;
use App\Models\Pregunta;
use App\Models\Respuesta;
use App\Models\Rol;
use App\Models\TipoDependencia;
use App\Models\TipoEstudiante;
use App\Models\User;

use Storage;

use Carbon\Carbon;

use Excel;

//use iio\libmergepdf\Merger;

class AdminController extends Controller
{

    public function __construct()
    {
        //2670 y 2698 esta textualmente 25 para municipio santa marta
        $this->middleware('auth');
        $this->middleware('admin', ['except'=>['getDescargarinforme', 'getCorreomasivo', 'getRolescorreomasivo', 'getProgramasjson',
                                               'postUsuarioscorreomasivo', 'postEnviocorreo', 'getExteriorjson', 'getExterior', 'getPeriodosmejson',
                                               'getExteriorexcel', 'getVinculacion', 'getVinculacionexcel', 'getVinculacionjson',
                                               'getFsantamarta', 'getFsantamartajson', 'getFsantamartaexcel',
                                               'getUbicacion', 'getUbicacionjson', 'getUbicacionexcel',
                                               'getLaborando', 'getLaborandojson', 'getLaborandoexcel',
                                               'getImpacto', 'getImpactojson', 'getImpactoexcel']]);
        $this->middleware('admincdn',['only'=>['getDescargarinforme', 'getExteriorjson', 'getExterior', 'getPeriodosmejson',
                                               'getExteriorexcel', 'getVinculacion', 'getVinculacionexcel', 'getVinculacionjson',
                                               'getFsantamarta', 'getFsantamartajson', 'getFsantamartaexcel',
                                               'getUbicacion', 'getUbicacionjson', 'getUbicacionexcel',
                                               'getLaborando', 'getLaborandojson', 'getLaborandoexcel',
                                               'getCorreomasivo', 'getRolescorreomasivo', 'getProgramasjson', 'postUsuarioscorreomasivo',
                                               'postEnviocorreo']]);
        $this->middleware('rechazar', ['only'=>['postRechazarpracticas']]);
    }

    //Usuarios
    public function getUsuarios()
    {
        return view('admin.usuarios');
    }

    public function getUsuariosjson()
    {
        $roles = ['Estudiante', 'Jefe inmediato', 'Empresa', 'Administrador Egresados'];
        $usuarios = User::with('getdependencias')
                        ->with('getuser')
                        ->with('getrol')
                        ->with('getsede.getempresa')
                        ->with('getsede.getmunicipio')
                        ->whereIn('idRol', Rol::whereNotIn('nombre', $roles)->select('id')->get()->toArray())
                        ->get();

        return $usuarios;
    }

    public function getFormulariousuario()
    {
        $datos = array();

        $roles = Rol::whereIn('nombre', ['Coordinador', 'Tutor', 'Jefe inmediato', 'Ori', 'Coordinador de programa', 'Juridica', 'Administrador Dippro'])->select('id','nombre')->get();

        $programas = Dependencia::where('idTipo', 1)->select('id', 'nombre')->get();

        $datos['roles'] = $roles;
        $datos['programas'] = $programas;

        return $datos;
    }

    public function getUsuario($id)
    {
        $roles = ['Estudiante', 'Jefe inmediato', 'Empresa', 'Administrador Egresados'];
        $usuario = User::with('getdependencias')
                        ->with('getuser')
                        ->with('getrol')
                        ->with('getsede.getempresa')
                        ->with('getsede.getmunicipio')
                        ->whereIn('idRol', Rol::whereNotIn('nombre', $roles)->select('id')->get()->toArray())
                        ->where('usuarios.id', $id)
                        ->first();
        if($usuario->getrol->nombre == 'Coordinador de programa')
        {
            $usuario->dependencias = $usuario->getdependencias[0];
        }
        else
        {
            $usuario->dependencias = $usuario->getdependencias;
        }
        // $usuario = DB::table('usuarios')
        //                 ->join('personas', 'personas.id', '=', 'idPersona')
        //                 ->join('roles', 'roles.id', '=', 'idRol')
        //                 ->leftJoin('dependencias', 'dependencias.id', '=', 'idDependencia')
        //                 ->leftJoin('sedes', 'sedes.id', '=', 'idSede')
        //                 ->leftJoin('empresas', 'empresas.id', '=', 'idEmpresa')
        //                 ->leftJoin('municipios', 'municipios.id', '=', 'idMunicipio')
        //                 ->selectRaw('personas.nombres, personas.apellidos, personas.identificacion,personas.correo, usuarios.id,
        //                              roles.nombre as rol, dependencias.nombre as dependencia, concat(empresas.nombre, " - ", municipios.nombre) as sede,
        //                              personas.celular')
        //                 ->where('usuarios.id', $id)
        //                 ->first();

        // $user['nombres'] = $usuario->nombres;
        // $user['apellidos'] = $usuario->apellidos;
        // $user['identificacion'] = $usuario->identificacion;
        // $user['rol'] = Rol::where('nombre',$usuario->rol)->first();
        // $user['dependencia'] = Dependencia::where('nombre',$usuario->dependencia)->first();
        // $user['correo'] = $usuario->correo;
        // $user['id']=$usuario->id;
        // $user['celular']=$usuario->celular;

        return $usuario;

    }


    public function postSaveusuario(UsuarioRequest $request)
    {
        if(isset($request->id))
        {
            $user = User::find($request->id);

            $persona = Persona::find($user->idPersona);

            $persona->nombres = $request->getuser['nombres'];
            $persona->apellidos = $request->getuser['apellidos'];
            $persona->correo = $request->getuser['correo'];
            $persona->celular = $request->getuser['celular'];
            $persona->identificacion = $request->identificacion;
            $persona->save();

            $user->idRol = $request->getrol['id'];
            $user->identificacion = $request->identificacion;
            $user->save();
        }
        else
        {
            $persona = Persona::where('identificacion', $request->identificacion)->first();
            if(sizeof($persona)==0)
            {
                $persona = new Persona();
            }

            $persona->nombres = $request->getuser['nombres'];
            $persona->apellidos = $request->getuser['apellidos'];
            $persona->correo = $request->getuser['correo'];
            $persona->celular = $request->getuser['celular'];
            $persona->identificacion = $request->identificacion;
            $persona->save();

            $user = new User();
            $user->idPersona = $persona->id;
            $user->idRol = $request->getrol['id'];
            $user->identificacion = $request->identificacion;
            $user->password = \Hash::make($request->identificacion);
            $user->activo=true;
            $user->save();
        }

        CoordinadorDependencia::where('idCoordinador', $user->id)->delete();
        if($request->getrol['nombre']=='Ori')
        {
            $idDependencia = Dependencia::where('nombre', 'OFICINA DE RELACIONES INTERNACIONALES')->first()->id;
            CoordinadorDependencia::create(['idCoordinador'=> $user->id, 'idPrograma'=>$idDependencia]);
        }
        else if($request->getrol['nombre']=='Jurídica')
        {
            $idDependencia = Dependencia::where('nombre', 'JURIDICA')->first()->id;
            CoordinadorDependencia::create(['idCoordinador'=> $user->id, 'idPrograma'=>$idDependencia]);
        }
        else if($request->getrol['nombre']=='Coordinador de programa')
        {
            $idDependencia = $request->dependencias['id'];
            CoordinadorDependencia::create(['idCoordinador'=> $user->id, 'idPrograma'=>$idDependencia]);
        }
        else if($request->getrol['nombre']=='Coordinador')
        {
            foreach($request->dependencias as $dependencia)
            {
                $idDependencia =  $dependencia['id'];
                CoordinadorDependencia::create(['idCoordinador'=> $user->id, 'idPrograma'=>$idDependencia]);
            }
        }

        return '1';
    }

    public function getSolicitantesjson()
    {
        // $ws = new WebServiceSieg();
        // $data = [
        //     'codigo' => '2009114042',
        //     'token'   => strtoupper(md5($ws->token('2009114042'))),
        // ];
        // $estudiante = json_decode(json_encode($ws->call('getInfoEstudianteByCodigo',[$data])), true);

        // dd($estudiante);
        $solicitantes = Estudiante::with('getpersona')
                                  ->with('getprograma')
                                  ->with('getmodalidades')
                                  ->where('idTipo', TipoEstudiante::where('nombre', 'Solicitó prácticas')->first()->id)
                                  ->orWhere('idTipo', TipoEstudiante::where('nombre', 'Solicitó prácticas y preprácticas')->first()->id)
                                  ->get();
        return $solicitantes;
    }

    public function getSolicitantes()
    {
        return view('admin.solicitantes');
    }

    public function postAprobarpracticasmultiple(Request $request)
    {
        foreach($request->seleccionados as $seleccionado)
        {
            $this->getAprobarpracticas($seleccionado);
        }

        return ['title'=>'Solicitud aceptada', 'content'=>'Solicitud aceptada con exito', 'type'=>'success'];
    }

    public function getAprobarpracticas($id)
    {
        $estudiante = Estudiante::find($id);

        if($estudiante->gettipo->nombre == 'Solicitó prácticas y preprácticas')
        {
            $estudiante->idTipo = TipoEstudiante::where('nombre', 'Prácticas y preprácticas')->first()->id;
        }
        else if($estudiante->gettipo->nombre == 'Solicitó prácticas')
        {
            $estudiante->idTipo = TipoEstudiante::where('nombre', 'Prácticas')->first()->id;
        }

        $estudiante->save();

        $usuario = User::where('idPersona', $estudiante->getpersona->id)->first();
        $usuario->activo=true;
        $usuario->save();

        // if($estudiante->getmodalidades[sizeof($estudiante->getmodalidades) - 1]->nombre != 'Vinculación laboral')
        // {
        //     $practica = ModalidadEstudiante::find($estudiante->getpracticas[sizeof($estudiante->getpracticas) - 1]->id);
        //     $practica->estado = EstadoPractica::where('nombre', 'Aprobada')->first()->id;
        //     $practica->save();
        // }

        Mail::send('emails.aceptarpracticas', ['user' => $estudiante], function ($m) use ($estudiante) {
            $m->from('hello@app.com', env('MAIL_FROM'));

            $m->to($estudiante->getpersona->correo, $estudiante->getpersona->nombres.' '.$estudiante->getpersona->apellidos)->subject('Solicitud aceptada');
        });


        return ['title'=>'Solicitud aceptada', 'content'=>'Solicitud aceptada con exito', 'type'=>'success'];
    }

    public function postRechazarpracticas(RechazarRequest $request)
    {
        $estudiante = Estudiante::find($request->id);

        $estudiante->idTipo = TipoEstudiante::where('nombre', 'Preprácticas')->first()->id;

        $estudiante->save();

        $usuario = User::where('idPersona', $estudiante->getpersona->id)->first();
        $usuario->activo=true;
        $usuario->save();

        $texto = 'Su solicitud para iniciar su proceso de prácticas no fue aprobada. Motivo: '."\n".$request->motivo."\n\n";
        Mail::raw($texto, function ($message) use ($estudiante) {
            $message->from('hello@app.com', env('MAIL_FROM'));

            $message->to($estudiante->getpersona->correo, $estudiante->getpersona->nombres.' '.$estudiante->getpersona->apellidos)->subject('Solicitud no aprobada');

        });

        return ['title'=>'Solicitud no aprobada', 'content'=>'Solicitud denegada exitosamente', 'type'=>'success'];
    }

    public function getActas()
    {
        return view('admin.actas');
    }

    public function getActasjson()
    {
        $pos = ModalidadEstudiante::with('getmodalidad')
                                  ->with('getestudiante.getpersona')
                                  ->with('getestudiante.getpersona')
                                  ->with('getestudiante.getprograma')
                                  ->with('getestudiante.getpostulaciones.getoferta.getsede.getempresa')
                                  ->with('getestado')
                                  ->where('aprobacion_estudiante', true)
                                  ->where('idModalidad', '!=', Modalidad::where('nombre', 'Vinculación laboral')->first()->id);

        $postulados = ModalidadEstudiante::with('getmodalidad')
                                         ->with('getestudiante.getpersona')
                                         ->with('getestudiante.getpersona')
                                         ->with('getestudiante.getprograma')
                                         ->with('getestudiante.getpostulaciones.getoferta.getsede.getempresa')
                                         ->with('getestado')
                                         ->where('aprobacion_jefe', true)
                                         ->where('aprobacion_estudiante', true)
                                         ->union($pos)
                                         ->get();
        return $postulados;
    }

    public function getVeracta($id)
    {
        $acta = ModalidadEstudiante::find($id);

        if($acta->getmodalidad->nombre == 'Vinculación laboral')
        {
            $acta = ModalidadEstudiante::where('aprobacion_jefe', true)
                                       ->where('aprobacion_estudiante', true)
                                       ->where('id', $id)
                                       ->first();
        }
        else
        {
            $acta = ModalidadEstudiante::where('aprobacion_estudiante', true)
                                       ->where('id', $id)
                                       ->first();
        }

        $postulado=null;
        $idEstadoEmpresa = EstadoPostulado::where('nombre', 'Seleccionado')->first()->id;
        $idEstadoEstudiante = EstadoPostuladoEst::where('nombre', 'Aceptó')->first()->id;

        if($acta->getestudiante->getpostulaciones != null)
        {
            foreach($acta->getestudiante->getpostulaciones as $pos)
            {
                if($pos->idEstatoEmpresa == $idEstadoEmpresa && $pos->idEstadoEstudiante = $idEstadoEstudiante)
                {
                    $postulado = $pos;
                    break;
                }
            }
        }

        $acta->postulado = $postulado;

        return view('admin.veracta', compact('acta'));
    }

    public function getDatosactajson()
    {
        $tutor = User::where('idRol', Rol::where('nombre', 'Tutor')->first()->id)
                     ->get();
        $tutores = array();
        $con = 0;
        foreach($tutor as $t)
        {
            $tutores[$con]['nombre'] = $t->getuser->nombres.' '.$t->getuser->apellidos;
            $tutores[$con]['id'] = $t->id;
            $con++;
        }

        $data['tutores'] = $tutores;

        $data['estados'] = EstadoPractica::all();

        return $data;
    }

    public function getCertificadoarl($id)
    {
        $acta = ModalidadEstudiante::find($id);

        $nombre = $acta->certificado_arl;

        $path = storage_path('app/legalizacion/'.$nombre);

        return \Response::download($path);

    }

    public function getCertificadosalud($id)
    {
        $acta = ModalidadEstudiante::find($id);

        $nombre = $acta->certificado_salud;

        $path = storage_path('app/legalizacion/'.$nombre);

        return \Response::download($path);

    }

    public function postAprobaracta(ActaRequest $request)
    {
        if($request->estado['nombre'] == 'Esperando respuesta')
        {
            return ['title'=>'Error', 'content'=>'Debe escoger un estado diferente de Esperando respuesta', 'type'=>'error'];
        }
        else if($request->estado['nombre'] == 'Aprobada')
        {
            $vector = explode('-', $request->fecha_fin);
            $periodo = $vector[0];
            // if($vector[1] >= 1 && $vector[1] <=5)
            // {
                // falta que nos digan como se define el periodo
            // }
            $tutor = PracticaTutor::where('idPracticas', $request->id)
                                  ->where('idTutor', $request->tutor['id'])
                                  ->first();

            if(sizeof($tutor) == 0)
            {
                $tutor = PracticaTutor::create(['idPracticas'=>$request->id, 'idTutor'=>$request->tutor['id']]);
            }

            PracticaTutor::where('idPracticas', $request->id)
                         ->update(['activo'=>0]);

            PracticaTutor::where('idPracticas', $request->id)
                         ->where('idTutor', $request->tutor['id'])
                         ->update(['activo'=>1]);


            $acta = ModalidadEstudiante::find($request->id);

            $postulado = Postulado::where('idEstudiante', $acta->idEstudiante)
                                  ->where('idEstatoEmpresa', EstadoPostulado::where('nombre', 'Seleccionado')->first()->id)
                                  ->where('idEstadoEstudiante', EstadoPostuladoEst::where('nombre', 'Aceptó')->first()->id)
                                  ->first();

            $estudiante = $postulado->getestudiante;

            $oferta = $postulado->getoferta;

            $cambio_fecha = $acta->fecha_fin != null && $acta->fecha_fin != $request->fecha_fin;

            $acta->fecha_inicio = $request->fecha_inicio;
            $acta->fecha_fin = $request->fecha_fin;

            $acta->aprobacion_dippro = true;
            $acta->fecha_aprobacion_dippro = \Carbon\Carbon::now();
            $acta->estado = $request->estado['id'];

            if(!$oferta->arl && $acta->certificado_arl == null)
            {
                $arl = $request->file('file');
                if($arl->getError() > 0)
                {
                    return ['title'=>'Error', 'content'=>'El certificado de arl debe ser un archivo PDF y debe pesar maximo 1MB', 'type'=>'error'];
                }

                if($arl->getMimeType() == 'application/pdf' && $arl->getSize() <= 1048576)
                {
                    if(Storage::disk('legalizacion')->has($acta->certificado_arl))
                    {
                        Storage::disk('legalizacion')->delete($acta->certificado_arl);
                    }
                    $nombre = 'ARL_'.$estudiante->codigo.'_'.\Carbon\Carbon::now().'.pdf';
                    $acta->certificado_arl = $nombre;
                    Storage::disk('legalizacion')->put($nombre, $arl = \File::get($arl));
                }
                $acta->nombre_arl = $request->nombre_arl;

            }

            $acta->save();


            $data = ['title'=>'Éxito', 'content'=>'Acta aprobada con éxito', 'type'=>'success'];

            $usuarioEmpresa = $oferta->getsede->getusuarios[0];

            if(!$cambio_fecha)
            {
                $texto = 'Su acta de legalización ha cambiado aprobada.';
                $estudiante = $acta->getestudiante;
                Mail::raw($texto, function ($message) use ($estudiante) {
                    $message->from('hello@app.com', env('MAIL_FROM'));

                    $message->to($estudiante->getpersona->correo, $estudiante->getpersona->nombres.' '.$estudiante->getpersona->apellidos)->subject('Acta de legalización');

                });

            }
            else
            {
                $texto = 'Hubo un cambio en la fecha final de la práctica del estudiante '.$estudiante->getpersona->nombres.' '.$estudiante->getpersona->apellidos.
                         ' la nueva fecha final de la práctica es: '.$request->fecha_fin;

                Mail::raw($texto, function ($message) use ($usuarioEmpresa, $tutor) {
                    $message->from('hello@app.com', env('MAIL_FROM'));

                    $message->to($usuarioEmpresa->getuser->correo, $usuarioEmpresa->getuser->nombres.' '.$usuarioEmpresa->getuser->apellidos)->subject('Cambio de fecha de prácticas');
                    $message->to($tutor->gettutor->getuser->correo, $tutor->gettutor->getuser->nombres.' '.$tutor->gettutor->getuser->apellidos)->subject('Cambio de fecha de prácticas');

                });

                $texto = 'Hubo un cambio en la fecha final de su práctica'.
                         ' la nueva fecha final de la práctica es: '.$request->fecha_fin;

                $estudiante = $acta->getestudiante;
                Mail::raw($texto, function ($message) use ($estudiante) {
                    $message->from('hello@app.com', env('MAIL_FROM'));

                    $message->to($estudiante->getpersona->correo, $estudiante->getpersona->nombres.' '.$estudiante->getpersona->apellidos)->subject('Acta de legalización');

                });
            }
        }
        else
        {
            $acta = ModalidadEstudiante::find($request->id);
            $estudiante = $acta->getestudiante;
            $acta->observaciones = $request->observaciones;
            $acta->estado = $request->estado['id'];
            $acta->save();
            $data = ['title'=>'Éxito', 'content'=>'Cambio de estado del acta exitoso', 'type'=>'success'];

            $texto = 'Su acta de legalización ha cambiado de estado a: '.$request->estado['nombre'].', con las siguientes observaciones: '."\n".$request->observaciones."\n\n";
            Mail::raw($texto, function ($message) use ($estudiante) {
                $message->from('hello@app.com', env('MAIL_FROM'));

                $message->to($estudiante->getpersona->correo, $estudiante->getpersona->nombres.' '.$estudiante->getpersona->apellidos)->subject('Acta de legalización');

            });

            $seleccionado = Postulado::where('idEstudiante', $acta->idEstudiante)
                                     ->where('idEstatoEmpresa', EstadoPostulado::where('nombre', 'Seleccionado')->first()->id)
                                     ->where('idEstadoEstudiante', EstadoPostuladoEst::where('nombre', 'Aceptó')->first()->id)
                                     ->first();

            if($request->estado['nombre']=='No aprobada')
            {
                $seleccionado->idEstadoEstudiante = EstadoPostuladoEst::where('nombre', 'Rechazada por DIPPRO')->first()->id;
            }
            else if($request->estado['nombre']=='Cancelada')
            {
                $seleccionado->idEstadoEstudiante = EstadoPostuladoEst::where('nombre', 'Cancelada por DIPPRO')->first()->id;
            }

            $seleccionado->save();
        }



        return $data;

    }

    public function getInfoacta($id)
    {
        $acta['id'] = $id;
        $practica = ModalidadEstudiante::find($id);
        $acta['tutor']=null;
        if(sizeof($practica->gettutores)>0)
        {
            $tutores = $practica->getpracticastutor;
            foreach($tutores as $item)
            {
                if($item->activo)
                {
                    $tutor['id'] = $item->gettutor->id;
                    $tutor['nombre'] = $item->gettutor->getuser->nombres.' '.$item->gettutor->getuser->apellidos;
                    break;
                }
            }
            $acta['tutor'] = $tutor;
        }
        $acta['fecha_inicio'] = $practica->fecha_inicio;
        $acta['fecha_fin'] = $practica->fecha_fin;
        $acta['aprobacion_dippro'] = $practica->aprobacion_dippro;
        $acta['estado'] = $practica->getestado;
        $acta['observaciones'] = $practica->observaciones;
        return $acta;
    }

    public function getPracticantes()
    {
        return view('admin.practicantes');
    }

    public function getPracticantesjson($filtro=null)
    {
        if($filtro == '1')// los que se encuentran realizando sus practicas
        {
            $practicantes = DB::table('estudiantes')
                              ->join('tipoestudiantes', 'estudiantes.idTipo', '=', 'tipoestudiantes.id')
                              ->join('personas', 'estudiantes.idPersona', '=', 'personas.id')
                              ->join('dependencias', 'estudiantes.idPrograma', '=', 'dependencias.id')
                              ->join('estudiantes_modalidades', function($join){
                                  $join->on('estudiantes.id', '=', 'estudiantes_modalidades.idEstudiante')
                                       ->where('estado','=', EstadoPractica::where('nombre', 'Aprobada')->first()->id);
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
                              ->selectRaw("distinct estudiantes_modalidades.id,estudiantes_modalidades.informe, estudiantes.codigo, concat(personas.nombres, ' ', personas.apellidos) as nombre, dependencias.nombre as programa,
                                           empresas.nombre as empresa, modalidades.nombre as modalidad, estado_practicas.nombre as estado, postulados.idEstudiante,
                                           estudiantes.id as idEst")
                              ->where('tipoestudiantes.nombre', 'Prácticas')
                              ->orWhere('tipoestudiantes.nombre', 'Prácticas y preprácticas')
                              ->get();
        }
        else if($filtro == '2')// los que no se encuentran realizando sus practicas
        {
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
                              ->selectRaw("distinct estudiantes_modalidades.id,estudiantes_modalidades.informe, estudiantes.codigo, concat(personas.nombres, ' ', personas.apellidos) as nombre, dependencias.nombre as programa,
                                           empresas.nombre as empresa, modalidades.nombre as modalidad, estado_practicas.nombre as estado, postulados.idEstudiante,
                                           estudiantes.id as idEst")
                              ->where('tipoestudiantes.nombre', 'Prácticas')
                              ->orWhere('tipoestudiantes.nombre', 'Prácticas y preprácticas')
                              ->get();
        }
        else if($filtro == '3')// los que son proyectos de impacto
        {
            $practicantes = DB::table('estudiantes')
                              ->join('tipoestudiantes', 'estudiantes.idTipo', '=', 'tipoestudiantes.id')
                              ->join('personas', 'estudiantes.idPersona', '=', 'personas.id')
                              ->join('dependencias', 'estudiantes.idPrograma', '=', 'dependencias.id')
                              ->join('estudiantes_modalidades', function($join){
                                  $join->on('estudiantes.id', '=', 'estudiantes_modalidades.idEstudiante')
                                       ->where('proyecto_impacto','=', true);
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
                              ->selectRaw("distinct estudiantes_modalidades.id,estudiantes_modalidades.informe, estudiantes.codigo, concat(personas.nombres, ' ', personas.apellidos) as nombre, dependencias.nombre as programa,
                                           empresas.nombre as empresa, modalidades.nombre as modalidad, estado_practicas.nombre as estado, postulados.idEstudiante,
                                           estudiantes.id as idEst")
                              ->where('tipoestudiantes.nombre', 'Prácticas')
                              ->orWhere('tipoestudiantes.nombre', 'Prácticas y preprácticas')
                              ->get();
        }
        else if($filtro == '4')// los que no son proyectos de impacto
        {
            $practicantes = DB::table('estudiantes')
                              ->join('tipoestudiantes', 'estudiantes.idTipo', '=', 'tipoestudiantes.id')
                              ->join('personas', 'estudiantes.idPersona', '=', 'personas.id')
                              ->join('dependencias', 'estudiantes.idPrograma', '=', 'dependencias.id')
                              ->join('estudiantes_modalidades', function($join){
                                  $join->on('estudiantes.id', '=', 'estudiantes_modalidades.idEstudiante')
                                       ->whereNull('estudiantes_modalidades.proyecto_impacto')
                                       ->orWhere('estudiantes_modalidades.proyecto_impacto','!=', true);
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
                              ->selectRaw("distinct estudiantes_modalidades.id,estudiantes_modalidades.informe, estudiantes.codigo, concat(personas.nombres, ' ', personas.apellidos) as nombre, dependencias.nombre as programa,
                                           empresas.nombre as empresa, modalidades.nombre as modalidad, estado_practicas.nombre as estado, postulados.idEstudiante,
                                           estudiantes.id as idEst")
                              ->where('tipoestudiantes.nombre', 'Prácticas')
                              ->orWhere('tipoestudiantes.nombre', 'Prácticas y preprácticas')
                              ->get();
        }
        else
        {
            $practicantes = DB::table('estudiantes')
                              ->join('tipoestudiantes', 'estudiantes.idTipo', '=', 'tipoestudiantes.id')
                              ->join('personas', 'estudiantes.idPersona', '=', 'personas.id')
                              ->join('dependencias', 'estudiantes.idPrograma', '=', 'dependencias.id')
                              ->join('estudiantes_modalidades', function($join){
                                  $join->on('estudiantes.id', '=', 'estudiantes_modalidades.idEstudiante');
                                    //   ->where('estado','!=', EstadoPractica::where('nombre', 'Aprobada')->first()->id);
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
                              ->selectRaw("distinct estudiantes_modalidades.id,estudiantes_modalidades.informe, estudiantes.codigo, concat(personas.nombres, ' ', personas.apellidos) as nombre, dependencias.nombre as programa,
                                           empresas.nombre as empresa, modalidades.nombre as modalidad, estado_practicas.nombre as estado, postulados.idEstudiante,
                                           estudiantes.id as idEst")
                              ->where('tipoestudiantes.nombre', 'Prácticas')
                              ->orWhere('tipoestudiantes.nombre', 'Prácticas y preprácticas')
                              ->get();
        }


        return $practicantes;
    }

    public function getVerhojadevida($idEstudiante)
    {
        return view('admin.hoja', compact('idEstudiante'));
    }

    public function getHojajson($idEstudiante)
    {
        $estudiante = Estudiante::with('gethojadevida')
                                ->with('getpersona.getciudad')
                                ->with('getpersona.getgenero')
                                ->with('getpersona.getestadocivil')
                                ->with('getprograma')
                                ->with('gethojadevida.getestudios.getmunicipio')
                                ->with('gethojadevida.getexperiencias')
                                ->with('gethojadevida.getcompetencias')
                                ->with('gethojadevida.getreferencias.getparentesco')
                                ->with('gethojadevida.getnivelidiomas.getidioma')
                                ->with('gethojadevida.getnivelidiomas.getnivelescritura')
                                ->with('gethojadevida.getnivelidiomas.getnivellectura')
                                ->with('gethojadevida.getnivelidiomas.getnivelhabla')
                                ->where('id', $idEstudiante)
                                ->first();

        return $estudiante;
    }

    // public function getPrueba()
    // {
    //     $ws = new WebService();//2011161026  2009114042
    //     $data = [
    //         'codEstudiante' => '2011161026',
    //         'perAcad'=>'2016I',
    //         'token'   => strtoupper(md5("s3rvW3bd1ppro@2*16_-".\Carbon\Carbon::now()->format('d/m/Y')."_-".'2011161026')),
    //     ];
    //     // $estudiante = json_decode(json_encode($ws->call('getEstAprobaronPrePracticas',[$data])), true);
    //     if( isset($ws->call('getEstAprobaronPrePracticas',[$data])->return))
    //     {
    //         dd(sizeof($estudiante));
    //     }
    //     else
    //     {
    //         return 'hola';
    //     }

    //     // dd($estudiante);

    // }

    public function getOfertasbyestudiante($idEstudiante)
    {

        $estudiante = Estudiante::find($idEstudiante);
        $idPrograma = $estudiante->idPrograma;

        $ofertas = Oferta::getOfertasByEstudiante($idPrograma, $idEstudiante);

        return $ofertas;
    }

    public function postPostularestudiante(Request $request)
    {
        $estudiante = Estudiante::find($request->idEstudiante);

        $postulado = new Postulado();
        $postulado->idEstudiante = $estudiante->id;
        $postulado->idOferta = $request->idOferta;
        $postulado->idEstatoEmpresa = EstadoPostulado::where('nombre', 'Postulado')->first()->id;
        $postulado->idEstadoEstudiante = EstadoPostuladoEst::where('nombre', 'Esperando respuesta')->first()->id;
        $postulado->save();

        return ['title'=>'Éxito', 'content'=>'Estudiante postulado con exito', 'type'=>'success'];
    }

    public function getConvenios()
    {
        return view('admin.convenios');
    }

    public function getConveniosjson( $id = null)
    {
        $convenios = null;
        if($id == null)
        {
            $convenios = Convenio::with('getempresa')
                                 ->with('getestado')
                                 ->with('getactasrenovacion')
                                 ->get();

            foreach($convenios as $convenio)
            {
                $fecha_actual = Carbon::now();
                // dd( $convenio);
                if($convenio->fecha_inicio != null && $convenio->fecha_fin != null)
                {
                    $fecha_fin = Carbon::create(explode('-', $convenio->fecha_fin)[0],explode('-', $convenio->fecha_fin)[1],explode('-', $convenio->fecha_fin)[2]);
                    $diferencia = $fecha_fin->diffInDays($fecha_actual);
                    if($diferencia <= 30 && $fecha_fin >= $fecha_actual)
                    {
                        $convenio->mostrar= true;
                    }
                    else
                    {
                        $convenio->mostrar=false;
                    }
                }
                else
                {
                    $convenio->mostrar=false;
                }

            }
        }
        else
        {
            $convenios = Convenio::with('getempresa')
                                 ->with('getestado')
                                 ->with('getactasrenovacion')
                                 ->where('id', $id)
                                 ->first();
        }
        // dd($convenios);
        return $convenios;
    }

    public function getAprobarconvenio($id)
    {
        $convenio = Convenio::find($id);
        if($convenio->getestado->nombre == "Esperando aprobación")
        {
            $convenio->estado = EstadoConvenio::where('nombre', 'Aprobado')->first()->id;
            $convenio->save();

            $usuario = User::where('identificacion', $convenio->getempresa->nit)->first();

            Mail::send('emails.convenioaprobado', ['user' => $usuario], function ($m) use ($usuario) {
                $m->from('hello@app.com', env('MAIL_FROM'));

                $m->to($usuario->getuser->correo, $usuario->getuser->nombres.' '.$usuario->getuser->apellidos)->subject('Solicitud de convenio aprobada');
            });

            return ['title'=>'Éxito', 'content'=>'Convenio aprobado con éxito', 'type'=>'success'];
        }

        return ['title'=>'Error', 'content'=>'No es posible aprobar el convenio', 'type'=>'error'];

    }

    public function getNoaprobarconvenio($id)
    {
        $convenio = Convenio::find($id);
        if($convenio->getestado->nombre == "Esperando aprobación")
        {
            $convenio->estado = EstadoConvenio::where('nombre', 'No aprobado')->first()->id;
            $convenio->save();

            $texto = "El convenio que usted solicitó no ha sido aprobado.";

            $usuario = User::where('identificacion', $convenio->getempresa->nit)->first();

            Mail::raw($texto, function ($message) use ($usuario) {
                $message->from('hello@app.com', env('MAIL_FROM'));

                $message->to($usuario->getuser->correo, $usuario->getuser->nombres.' '.$usuario->getuser->apellidos)->subject('Solicitud de convenio no aprobada');

            });
            return ['title'=>'Éxito', 'content'=>'El convenio no fue aprobado.', 'type'=>'success'];
        }

        return ['title'=>'Error', 'content'=>'No es posible desaprobar el convenio', 'type'=>'error'];

    }

    public function getCertificadoexistencia($id)
    {

        $convenio = Convenio::find($id);

        if(sizeof($convenio) == 0)
        {
            $data = ['error'=>'Usted no puede descargar este documento'];
            return redirect('/admin/convenios')->with($data);
        }

        $nombre = $convenio->certificado_existencia;

        $path = storage_path('app/convenios/'.$nombre);

        return \Response::download($path);
    }

    public function getCedula($id)
    {
        $convenio = Convenio::find($id);

        if(sizeof($convenio) == 0)
        {
            $data = ['error'=>'Usted no puede descargar este documento'];
            return redirect('/admin/convenios')->with($data);
        }

        $nombre = $convenio->cedula_representante;

        $path = storage_path('app/convenios/'.$nombre);

        return \Response::download($path);
    }

    public function getProcuraduria($id)
    {
        $convenio = Convenio::find($id);

        if(sizeof($convenio) == 0)
        {
            $data = ['error'=>'Usted no puede descargar este documento'];
            return redirect('/admin/convenios')->with($data);
        }

        $nombre = $convenio->procuraduria;

        $path = storage_path('app/convenios/'.$nombre);

        return \Response::download($path);
    }

    public function getContraloria($id)
    {
        $convenio = Convenio::find($id);

        if(sizeof($convenio) == 0)
        {
            $data = ['error'=>'Usted no puede descargar este documento'];
            return redirect('/admin/convenios')->with($data);
        }

        $nombre = $convenio->contraloria;

        $path = storage_path('app/convenios/'.$nombre);

        return \Response::download($path);
    }

    public function getRut($id)
    {
        $convenio = Convenio::find($id);

        if(sizeof($convenio) == 0)
        {
            $data = ['error'=>'Usted no puede descargar este documento'];
            return redirect('/admin/convenios')->with($data);
        }

        $nombre = $convenio->rut;

        $path = storage_path('app/convenios/'.$nombre);

        return \Response::download($path);
    }

    public function getActoadministrativo($id)
    {
        $convenio = Convenio::find($id);

        if(sizeof($convenio) == 0)
        {
            $data = ['error'=>'Usted no puede descargar este documento'];
            return redirect('/admin/convenios')->with($data);
        }

        $nombre = $convenio->acto_administrativo;

        $path = storage_path('app/convenios/'.$nombre);

        return \Response::download($path);
    }

    public function getActaposesion($id)
    {
        $convenio = Convenio::find($id);

        if(sizeof($convenio) == 0)
        {
            $data = ['error'=>'Usted no puede descargar este documento'];
            return redirect('/admin/convenios')->with($data);
        }

        $nombre = $convenio->acta_posesion;

        $path = storage_path('app/convenios/'.$nombre);

        return \Response::download($path);
    }

    public function getMilitar($id)
    {
        $convenio = Convenio::find($id);

        if(sizeof($convenio) == 0)
        {
            $data = ['error'=>'Usted no puede descargar este documento'];
            return redirect('/admin/convenios')->with($data);
        }

        $nombre = $convenio->certificado_militar;

        $path = storage_path('app/convenios/'.$nombre);

        return \Response::download($path);
    }

    public function postConveniorevisado(ConvenioRevisadoRequest $request)
    {
        $convenio = Convenio::find($request->id);

        if($convenio->getestado->nombre != "En revisión por Dippro")
        {
            return ['title'=>'Error', 'content'=>'No es posible procesar los datos', 'type'=>'error'];
        }

        $empresa = $convenio->getempresa;
        $usuario = User::where('idSede', $empresa->getsedes[0]->id)
                       ->where('idRol', Rol::where('nombre', 'Empresa')->first()->id)
                       ->first();

        $data['user'] = $usuario;
        if ($request->observaciones == null || (is_string($request->observaciones) && trim($request->observaciones) === '')) {
            $data['observaciones'] = 'No hay observaciones';
        }
        else
        {
            $data['observaciones'] = $request->observacion;
            $convenio->observaciones = $convenio->observaciones.' @ '.$request->observacion;
        }

        if($request->estado == '1')
        {
            $convenio->estado = EstadoConvenio::where('nombre', 'Aprobado')->first()->id;
            $data['estado'] = 'Aprobado';
        }
        else if($request->estado == '2')
        {
            $data['estado'] = 'En revisión por la oficina jurídica';
            $convenio->estado = EstadoConvenio::where('nombre', 'En revisión por la oficina jurídica')->first()->id;
            $file = $request->file('file_minuta');
            if($file->getError() > 0)
            {
                return ['title'=>'Error', 'content'=>'La minuta debe ser un archivo PDF o docx y debe pesar maximo 1MB', 'type'=>'error'];
            }

            if(($file->getMimeType() == 'application/pdf' || $file->getMimeType() == 'application/vnd.openxmlformats-officedocument.wordprocessingml.document') && $file->getSize() <= 1048576)
            {
                if(Storage::disk('convenios')->has($convenio->minuta))
                {
                    Storage::disk('convenios')->delete($convenio->minuta);
                }
                $nombre = 'MINUTA_'.$usuario->identificacion.'_'.\Carbon\Carbon::now().'.pdf';
                $convenio->minuta = $nombre;
                Storage::disk('convenios')->put($nombre, $file = \File::get($file));
            }
        }
        else
        {
            return ['title'=>'Error', 'content'=>'No es posible trabajar con el estado que usted envío', 'type'=>'error'];
        }

        $convenio->save();

        Mail::send('emails.devolverconvenio', $data, function ($m) use ($usuario) {
            $m->from('hello@app.com', env('MAIL_FROM'));

            $m->to($usuario->getuser->correo, $usuario->getuser->nombres.' '.$usuario->getuser->apellidos)->subject('Estado del convenio');
        });

        return ['title'=>'Éxito', 'content'=>'Cambio de estado realizado con éxito', 'type'=>'success'];
    }

    public function getEnviarafirma($id)
    {
        $convenio = Convenio::find($id);
        if($convenio->getestado->nombre == "Aprobado por la oficina jurídica")
        {
            $convenio->estado = EstadoConvenio::where('nombre', 'Firma por parte de la empresa')->first()->id;
            $convenio->save();

            $texto = "El convenio que usted solicitó ha sido enviado a su empresa para que sea debidamente firmado por el representante legal.";

            $usuario = User::where('identificacion', $convenio->getempresa->nit)->first();

            Mail::raw($texto, function ($message) use ($usuario) {
                $message->from('hello@app.com', env('MAIL_FROM'));

                $message->to($usuario->getuser->correo, $usuario->getuser->nombres.' '.$usuario->getuser->apellidos)->subject('Solicitud de convenio');

            });
            return ['title'=>'Éxito', 'content'=>'El convenio enviado exitosamente.', 'type'=>'success'];
        }

        return ['title'=>'Error', 'content'=>'No es posible enviar el convenio', 'type'=>'error'];

    }

    public function getRecepciondippro($id)
    {
        $convenio = Convenio::find($id);
        if($convenio->getestado->nombre == "Firma por parte de la empresa")
        {
            $convenio->estado = EstadoConvenio::where('nombre', 'Recepción en Dippro')->first()->id;
            $convenio->save();

            $texto = "El convenio que usted tiene en proceso ha sido recibido por la dirección de prácticas profesionales.";

            $usuario = User::where('identificacion', $convenio->getempresa->nit)->first();

            Mail::raw($texto, function ($message) use ($usuario) {
                $message->from('hello@app.com', env('MAIL_FROM'));

                $message->to($usuario->getuser->correo, $usuario->getuser->nombres.' '.$usuario->getuser->apellidos)->subject('Solicitud de convenio');

            });
            return ['title'=>'Éxito', 'content'=>'El convenio se recibió exitosamente.', 'type'=>'success'];
        }

        return ['title'=>'Error', 'content'=>'No es posible cambiar el estado del convenio', 'type'=>'error'];
    }

    public function postSuscribirconvenio(SuscribirRequest $request)
    {
        $convenio = Convenio::find($request->id);
        if($convenio->getestado->nombre == "Recepción en Dippro")
        {
            $convenio->estado = EstadoConvenio::where('nombre', 'Suscrito')->first()->id;
            $convenio->fecha_inicio = $request->fecha_inicial;
            $convenio->fecha_fin = $request->fecha_final;

            $file = $request->file('file_convenio');
            if($file->getError() > 0)
            {
                return ['title'=>'Error', 'content'=>'El convenio debe ser un archivo PDF y debe pesar máximo 1MB', 'type'=>'error'];
            }

            if($file->getMimeType() == 'application/pdf' && $file->getSize() <= 1048576)
            {
                $nombre = 'CONVENIO_'.$convenio->getempresa->nit.'_'.Carbon::now().'.pdf';

                $convenio->convenio = $nombre;

                Storage::disk('convenios')->put($nombre, $file = \File::get($file));
            }
            else
            {
                return ['title'=>'Error', 'content'=>'El acta de renovación debe ser un archivo PDF y debe pesar máximo 1MB', 'type'=>'error'];
            }
            $convenio->save();

            $texto = "El convenio que usted solicitó ha sido suscrito con éxito.";

            $usuario = User::where('identificacion', $convenio->getempresa->nit)->first();

            Mail::raw($texto, function ($message) use ($usuario) {
                $message->from('hello@app.com', env('MAIL_FROM'));

                $message->to($usuario->getuser->correo, $usuario->getuser->nombres.' '.$usuario->getuser->apellidos)->subject('Solicitud de convenio');

            });
            return ['title'=>'Éxito', 'content'=>'El convenio ha sido suscrito exitosamente.', 'type'=>'success'];
        }

        return ['title'=>'Error', 'content'=>'No es posible suscribir el convenio', 'type'=>'error'];
    }

    public function postRenovarconvenio(RenovarRequest $request)
    {
        $convenio = Convenio::find($request->id);
        $fecha_actual = Carbon::now();
        // dd( $convenio);
        $fecha_fin = Carbon::create(explode('-', $convenio->fecha_fin)[0],explode('-', $convenio->fecha_fin)[1],explode('-', $convenio->fecha_fin)[2]);
        $diferencia = $fecha_fin->diffInDays($fecha_actual);
        if(!($diferencia <= 30 && $fecha_fin >= $fecha_actual))
        {
            return ['title'=>'Error', 'content'=>'No es posible renovar el convenio', 'type'=>'error'];
        }

        if($convenio->getestado->nombre == "Suscrito")
        {
            $convenio->estado = EstadoConvenio::where('nombre', 'Suscrito')->first()->id;
            $convenio->fecha_fin = $request->fecha;

            $file = $request->file('file_renovacion');
            if($file->getError() > 0)
            {
                return ['title'=>'Error', 'content'=>'El acta de renovación debe ser un archivo PDF y debe pesar máximo 1MB', 'type'=>'error'];
            }

            if($file->getMimeType() == 'application/pdf' && $file->getSize() <= 1048576)
            {
                $nombre = 'RENOVACION_'.$convenio->getempresa->nit.'_'.Carbon::now().'.pdf';
                $acta_renovacion = new ActaRenovacion();
                $acta_renovacion->nombre = $nombre;
                $acta_renovacion->descripcion = $request->descripcion;
                $acta_renovacion->idConvenio = $convenio->id;
                $acta_renovacion->save();
                Storage::disk('convenios')->put($nombre, $file = \File::get($file));
            }
            else
            {
                return ['title'=>'Error', 'content'=>'El acta de renovación debe ser un archivo PDF y debe pesar máximo 1MB', 'type'=>'error'];
            }

            $convenio->save();

            $texto = "La renovación de su convenio se realizo con éxito.";

            $usuario = User::where('identificacion', $convenio->getempresa->nit)->first();

            Mail::raw($texto, function ($message) use ($usuario) {
                $message->from('hello@app.com', env('MAIL_FROM'));

                $message->to($usuario->getuser->correo, $usuario->getuser->nombres.' '.$usuario->getuser->apellidos)->subject('Renovación de convenio');

            });
            return ['title'=>'Éxito', 'content'=>'El convenio ha sido renovado exitosamente.', 'type'=>'success'];
        }

        return ['title'=>'Error', 'content'=>'No es posible renovar el convenio', 'type'=>'error'];
    }


    public function getActarenovacion($id)
    {

        $convenio = Convenio::find($id);

        if(sizeof($convenio) == 0)
        {
            $data = ['error'=>'Usted no puede descargar este documento'];
            return redirect('/admin/convenios')->with($data);
        }

        if(sizeof($convenio->getactasrenovacion) == 0)
        {
            $data = ['error'=>'Usted no puede descargar este documento'];
            return redirect('/admin/convenios')->with($data);
        }

        $nombre = $convenio->getactasrenovacion[sizeof($convenio->getactasrenovacion) - 1]->nombre;

        $path = storage_path('app/convenios/'.$nombre);

        return \Response::download($path);
    }

    public function getFileconvenio($id)
    {

        $convenio = Convenio::find($id);

        if(sizeof($convenio) == 0)
        {
            $data = ['error'=>'Usted no puede descargar este documento'];
            return redirect('/admin/convenios')->with($data);
        }

        $nombre = $convenio->convenio;

        $path = storage_path('app/convenios/'.$nombre);

        return \Response::download($path);
    }

    public function getPracticantejson($id)
    {
        $estudiante = Estudiante::with('getpracticas.getmodalidad')
                                ->with('getpracticas.getestado')
                                ->with('getpersona')
                                ->where('id', $id)->first();
        return $estudiante;
    }

    public function getCartasolicitud($id)
    {
        $practica = ModalidadEstudiante::find($id);

        $nombre = $practica->file_carta_solicitud;

        $path = storage_path('app/legalizacion/'.$nombre);

        return \Response::download($path);
    }

    public function getCertificadolaboral($id)
    {
        $practica = ModalidadEstudiante::find($id);

        if($practica->getmodalidad->nombre == 'Validación' && $practica->file_certificado_laboral != null)
        {
            $nombre = $practica->file_certificado_laboral;

            $path = storage_path('app/legalizacion/'.$nombre);

            return \Response::download($path);
        }
        else
        {
            $data = ['error'=>'Usted no puede descargar este documento'];
            return redirect('/admin/actas')->with($data);
        }
    }

    public function getContrato($id)
    {
        $practica = ModalidadEstudiante::find($id);

        if($practica->getmodalidad->nombre == 'Validación' && $practica->file_contrato != null)
        {
            $nombre = $practica->file_contrato;

            $path = storage_path('app/legalizacion/'.$nombre);

            return \Response::download($path);
        }
        else
        {
            $data = ['error'=>'Usted no puede descargar este documento'];
            return redirect('/admin/actas')->with($data);
        }
    }

    public function getCertificadoexistenciaprac($id)
    {
        $practica = ModalidadEstudiante::find($id);

        if(($practica->getmodalidad->nombre == 'Validación' || $practica->getmodalidad->nombre == 'Asesorías pymes'
            || $practica->getmodalidad->nombre == 'Prácticas internacionales' || $practica->getmodalidad->nombre == 'Prácticas de empresarismo')
            && $practica->file_existencia_empresa != null)
        {
            $nombre = $practica->file_existencia_empresa;

            $path = storage_path('app/legalizacion/'.$nombre);

            return \Response::download($path);
        }
        else
        {
            $data = ['error'=>'Usted no puede descargar este documento'];
            return redirect('/admin/actas')->with($data);
        }
    }

    public function getCertificadoss($id)
    {
        $practica = ModalidadEstudiante::find($id);

        if(($practica->getmodalidad->nombre == 'Validación' || $practica->getmodalidad->nombre == 'Prácticas de empresarismo')
            && $practica->file_afiliacion_ss != null)
        {
            $nombre = $practica->file_afiliacion_ss;

            $path = storage_path('app/legalizacion/'.$nombre);

            return \Response::download($path);
        }
        else
        {
            $data = ['error'=>'Usted no puede descargar este documento'];
            return redirect('/admin/actas')->with($data);
        }
    }

    public function getCartacolaboracion($id)
    {
        $practica = ModalidadEstudiante::find($id);

        if(($practica->getmodalidad->nombre == 'Asesorías pymes'
            || $practica->getmodalidad->nombre == 'Prácticas internacionales' || $practica->getmodalidad->nombre == 'Semestre en el exterior')
            && $practica->file_carta_colaboracion != null)
        {
            $nombre = $practica->file_carta_colaboracion;

            $path = storage_path('app/legalizacion/'.$nombre);

            return \Response::download($path);
        }
        else
        {
            $data = ['error'=>'Usted no puede descargar este documento'];
            return redirect('/admin/actas')->with($data);
        }
    }

    public function getCedularelegal($id)
    {
        $practica = ModalidadEstudiante::find($id);

        if($practica->getmodalidad->nombre == 'Asesorías pymes' && $practica->file_cedula_relegal != null)
        {
            $nombre = $practica->file_cedula_relegal;

            $path = storage_path('app/legalizacion/'.$nombre);

            return \Response::download($path);
        }
        else
        {
            $data = ['error'=>'Usted no puede descargar este documento'];
            return redirect('/admin/actas')->with($data);
        }
    }

    public function getCartadirector($id)
    {
        $practica = ModalidadEstudiante::find($id);

        if(($practica->getmodalidad->nombre == 'Prácticas internacionales' || $practica->getmodalidad->nombre == 'Semestre en el exterior')
            && $practica->file_carta_director_programa != null)
        {
            $nombre = $practica->file_carta_director_programa;

            $path = storage_path('app/legalizacion/'.$nombre);

            return \Response::download($path);
        }
        else
        {
            $data = ['error'=>'Usted no puede descargar este documento'];
            return redirect('/admin/actas')->with($data);
        }
    }

    public function getFormatomovilidad($id)
    {
        $practica = ModalidadEstudiante::find($id);

        if(($practica->getmodalidad->nombre == 'Prácticas internacionales' || $practica->getmodalidad->nombre == 'Semestre en el exterior')
            && $practica->file_formato_movilidad != null)
        {
            $nombre = $practica->file_formato_movilidad;

            $path = storage_path('app/legalizacion/'.$nombre);

            return \Response::download($path);
        }
        else
        {
            $data = ['error'=>'Usted no puede descargar este documento'];
            return redirect('/admin/actas')->with($data);
        }
    }

    public function getPasaporte($id)
    {
        $practica = ModalidadEstudiante::find($id);

        if(($practica->getmodalidad->nombre == 'Prácticas internacionales' || $practica->getmodalidad->nombre == 'Semestre en el exterior')
            && $practica->file_pasaporte != null)
        {
            $nombre = $practica->file_pasaporte;

            $path = storage_path('app/legalizacion/'.$nombre);

            return \Response::download($path);
        }
        else
        {
            $data = ['error'=>'Usted no puede descargar este documento'];
            return redirect('/admin/actas')->with($data);
        }
    }

    public function getVisa($id)
    {
        $practica = ModalidadEstudiante::find($id);

        if(($practica->getmodalidad->nombre == 'Prácticas internacionales' || $practica->getmodalidad->nombre == 'Semestre en el exterior')
            && $practica->file_visa != null)
        {
            $nombre = $practica->file_visa;

            $path = storage_path('app/legalizacion/'.$nombre);

            return \Response::download($path);
        }
        else
        {
            $data = ['error'=>'Usted no puede descargar este documento'];
            return redirect('/admin/actas')->with($data);
        }
    }

    public function getCedulaestudiante($id)
    {
        $practica = ModalidadEstudiante::find($id);

        if(($practica->getmodalidad->nombre == 'Prácticas internacionales' || $practica->getmodalidad->nombre == 'Semestre en el exterior')
            && $practica->file_cedula != null)
        {
            $nombre = $practica->file_cedula;

            $path = storage_path('app/legalizacion/'.$nombre);

            return \Response::download($path);
        }
        else
        {
            $data = ['error'=>'Usted no puede descargar este documento'];
            return redirect('/admin/actas')->with($data);
        }
    }

    public function getCarnet($id)
    {
        $practica = ModalidadEstudiante::find($id);

        if(($practica->getmodalidad->nombre == 'Prácticas internacionales' || $practica->getmodalidad->nombre == 'Semestre en el exterior')
            && $practica->file_carnet != null)
        {
            $nombre = $practica->file_carnet;

            $path = storage_path('app/legalizacion/'.$nombre);

            return \Response::download($path);
        }
        else
        {
            $data = ['error'=>'Usted no puede descargar este documento'];
            return redirect('/admin/actas')->with($data);
        }
    }

    public function getPadres($id)
    {
        $practica = ModalidadEstudiante::find($id);

        if(($practica->getmodalidad->nombre == 'Prácticas internacionales' || $practica->getmodalidad->nombre == 'Semestre en el exterior')
            && $practica->file_padres != null)
        {
            $nombre = $practica->file_padres;

            $path = storage_path('app/legalizacion/'.$nombre);

            return \Response::download($path);
        }
        else
        {
            $data = ['error'=>'Usted no puede descargar este documento'];
            return redirect('/admin/actas')->with($data);
        }
    }

    public function getExtraestudiante($id)
    {
        $practica = ModalidadEstudiante::find($id);

        if(($practica->getmodalidad->nombre == 'Prácticas internacionales' || $practica->getmodalidad->nombre == 'Semestre en el exterior')
            && $practica->file_estudiante != null)
        {
            $nombre = $practica->file_estudiante;

            $path = storage_path('app/legalizacion/'.$nombre);

            return \Response::download($path);
        }
        else
        {
            $data = ['error'=>'Usted no puede descargar este documento'];
            return redirect('/admin/actas')->with($data);
        }
    }

    public function getItinerario($id)
    {
        $practica = ModalidadEstudiante::find($id);

        if(($practica->getmodalidad->nombre == 'Prácticas internacionales' || $practica->getmodalidad->nombre == 'Semestre en el exterior')
            && $practica->file_itinerario != null)
        {
            $nombre = $practica->file_itinerario;

            $path = storage_path('app/legalizacion/'.$nombre);

            return \Response::download($path);
        }
        else
        {
            $data = ['error'=>'Usted no puede descargar este documento'];
            return redirect('/admin/actas')->with($data);
        }
    }

    public function getSeguro($id)
    {
        $practica = ModalidadEstudiante::find($id);

        if(($practica->getmodalidad->nombre == 'Prácticas internacionales' || $practica->getmodalidad->nombre == 'Semestre en el exterior')
            && $practica->file_seguro != null)
        {
            $nombre = $practica->file_seguro;

            $path = storage_path('app/legalizacion/'.$nombre);

            return \Response::download($path);
        }
        else
        {
            $data = ['error'=>'Usted no puede descargar este documento'];
            return redirect('/admin/actas')->with($data);
        }
    }

    public function getEstadospracticasjson()
    {
        return EstadoPractica::where('nombre', '!=', 'Esperando respuesta')->get();
    }

    public function postAprobarpractica(PracticaRequest $request)
    {
        $estudiante = Estudiante::find($request->id);
        // $practicas = ModalidadEstudiante::find();
        $estados = EstadoPractica::where('nombre', '!=', 'Aprobada')->select('id')->get()->toArray();
        $estado['id'] = $request->practica['estado']['id'];

        $practica = $estudiante->getpracticas[sizeof($estudiante->getpracticas) - 1];
        if(($practica->estado_ori== null || !$practica->estado_ori) && ($practica->getmodalidad->nombre == 'Prácticas internacionales' || $practica->getmodalidad->nombre == 'Semestre en el exterior'))
        {
            return ['title'=>'Error', 'content'=>'No se puede cambiar el estado, falta la validación de la ORI', 'type'=>'error'];
        }
        $practica->estado = $estado['id'];
        $practica->periodo = $this->nombrePeriodo2();
        $practica->save();

        if(in_array($estado, $estados))
        {
            $texto = 'Su práctica cambio de estado a: '.$request->practica['estado']['nombre'].'.'."\n".'Motivo: '."\n".$request->practica['observaciones']."\n\n";
        }
        else
        {
            $texto = 'Su práctica cambio de estado a: '.$request->practica['estado']['nombre'];
        }

        Mail::raw($texto, function ($message) use ($estudiante) {
            $message->from('hello@app.com', env('MAIL_FROM'));

            $message->to($estudiante->getpersona->correo, $estudiante->getpersona->nombres.' '.$estudiante->getpersona->apellidos)->subject('Estado práctica');

        });

        return['title'=>'Éxito', 'content'=>'Cambio de estado realizado con éxito', 'type'=>'success'];
    }

    public function getVisitasjson($id)
    {
        $estudiante = Estudiante::find($id);

        $practica = $estudiante->getpracticas[sizeof($estudiante->getpracticas) - 1];

        $visitas = $practica->getvisitas;
        return $visitas;
    }

    public function getPrepracticas()
    {
        return view('admin.prepracticas');
    }

    public function getPrepracticasjson()
    {
        $estudiante = Estudiante::with('getpersona')
                                ->with('getprograma')
                                ->where('idTipo', TipoEstudiante::where('nombre', 'Preprácticas')->first()->id)
                                ->orWhere('idTipo', TipoEstudiante::where('nombre', 'Prácticas y preprácticas')->first()->id)
                                ->get();

        return $estudiante;
    }

    public function postAprobarprepracticas(Request $request)
    {
        $estudiantes = [];
        $contador=0;
        foreach($request->all() as $id)
        {
            $estudiante = Estudiante::find($id);
            $asistencias = $estudiante->getasistencias;
            $con=0;
            foreach($asistencias as $item)
            {
                if($item->asistio == null || !$item->asistio)
                {
                    $con++;
                }
            }

            if($con < 2)
            {
                $estudiante->idTipo = TipoEstudiante::where('nombre','Prácticas')->first()->id;
                $estudiante->aprobo_prepracticas = true;
                $estudiante->periodo_prepracticas = $this->nombrePeriodo2();
                $estudiante->save();
            }
            else
            {
                $estudiantes[$contador] = $estudiante;
                $contador++;
            }

        }

        if(sizeof($estudiantes) > 0)
        {
            $data['title'] = 'Información';
            $data['content'] = 'Los estudiantes identificados con los siguientes códigos: ';
            for ($i=0; $i < sizeof($estudiantes) ; $i++)
            {
                $data['content'] = $data['content'].$estudiantes[$i]->codigo.', ';
            }
            $data['content'] = $data['content'].'no pueden ser aprobados porque tienen 2 o más fallas.';
            $data['type'] = 'info';

            return $data;
        }

        return [
            'title'=>'Éxito',
            'content'=>'Estudiantes aprobados con éxito',
            'type'=>'success'
        ];
    }

    public function getCharlas()
    {
        return view('admin.conferencias');
    }

    public function getConferenciajson($id)
    {
        $conferencia = Conferencia::with('getprogramas')
                                  ->where('id', $id)
                                  ->first();



        return $conferencia;
    }

    public function postSaveconferencia(ConferenciaRequest $request)
    {
        if($request->id != null)
        {
            $conferencia = Conferencia::find($request->id);
        }
        else
        {
            $conferencia = new Conferencia();
        }

        $conferencia->nombre  = $request->nombre;
        $conferencia->save();
        ConferenciaPrograma::where('idConferencia', $conferencia->id)->delete();

        foreach($request->getprogramas  as $item)
        {
            ConferenciaPrograma::create(['idConferencia'=>$conferencia->id, 'idPrograma'=> $item['id']]);
        }

        return [
            'title'=>'Éxito',
            'content'=>'Conferencia creada con éxito',
            'type'=>'success'
        ];
    }

    public function getHorarios()
    {
        return view('admin.charlas');
    }

    public function postSavecharla(CharlaRequest $request)
    {
        $conferencia = Conferencia::find($request->getconferencia['id']);

        $conferencista = Persona::where('identificacion', $request->getorador['identificacion'])
                                ->first();

        if($conferencista == null)
        {
            $conferencista = new Persona();

            $conferencista->identificacion = $request->getorador['identificacion'];
            $conferencista->nombres = $request->getorador['nombres'];
            $conferencista->apellidos = $request->getorador['apellidos'];
            $conferencista->correo = $request->getorador['correo'];

            $conferencista->save();

        }


        $nombre_periodo = $this->nombrePeriodo();


        $periodo = Periodo::where('nombre', $nombre_periodo)
                          ->first();
        if($periodo == null)
        {
            $periodo = new Periodo();
            $periodo->nombre = $nombre_periodo;
            $periodo->save();
        }


        // $horario = ConferenciaPeriodo::where('idConferencia', $request->getconferencia['id'])
        //                              ->where('idPeriodo', $periodo->id)
        //                              ->first();
        // if($horario == null)
        // {

        // dd($request->all());
        if($request->id == null)
        {
            $horario = new ConferenciaPeriodo();
            $horario->idPeriodo = $periodo->id;
        }
        else
        {
            $horario = ConferenciaPeriodo::find($request->id);
        }

        $horario->idConferencia = $conferencia->id;
        $horario->lugar = $request->lugar;
        $horario->fecha = $request->fecha;
        $horario->horaInicial = $request->str_hora_inicial;
        $horario->horaFinal = $request->str_hora_final;
        $horario->cupo = $request->cupo;
        $horario->orador = $conferencista->id;
        $horario->save();



        return [
            'title'=>'Éxito',
            'content'=>'Charla creada con éxito',
            'type'=>'success',
        ];
    }

    public function getPeriodosjson()
    {
        return Periodo::orderBy('id', 'desc')
                      ->get();
    }

    public function getCharlasjson($idPeriodo = null)
    {
        if($idPeriodo == null)
        {
            $conferencias = ConferenciaPeriodo::with('getconferencia')
                                              ->with('getorador')
                                              ->with('getperiodo')
                                              ->with('getasistencias')
                                              ->orderBy('idPeriodo', 'desc')
                                              ->orderBy('idConferencia')
                                              ->get();
        }
        else
        {
            $conferencias = ConferenciaPeriodo::with('getconferencia')
                                              ->with('getorador')
                                              ->with('getperiodo')
                                              ->with('getasistencias')
                                              ->where('idPeriodo', $idPeriodo)
                                              ->orderBy('idConferencia')
                                              ->get();
        }

        return $conferencias;
    }

    public function getPersona($identificacion)
    {
        return Persona::where('identificacion', $identificacion)->get();
    }

    public function getCharlajson($id)
    {
        $charla = ConferenciaPeriodo::with('getorador')
                                    ->with('getconferencia')
                                    ->where('id', $id)
                                    ->first();

        return $charla;
    }

    public function postAddhorario(AddHorarioRequest $request)
    {
        $conferencista = Persona::where('identificacion', $request->getorador['identificacion'])
                                ->first();

        if($conferencista == null)
        {
            $conferencista = new Persona();

            $conferencista->identificacion = $request->getorador['identificacion'];
            $conferencista->nombres = $request->getorador['nombres'];
            $conferencista->apellidos = $request->getorador['apellidos'];
            $conferencista->correo = $request->getorador['correo'];

            $conferencista->save();

        }


        $nombre_periodo = $this->nombrePeriodo();


        $periodo = Periodo::where('nombre', $nombre_periodo)
                          ->first();
        if($periodo == null)
        {
            $periodo = new Periodo();
            $periodo->nombre = $nombre_periodo;
            $periodo->save();
        }


        if($request->editar)
        {
            $horario = ConferenciaPeriodo::find($request->id);
        }
        else
        {
            $horario = new ConferenciaPeriodo();
        }



        $horario->idPeriodo = $periodo->id;
        $horario->idConferencia = $request->getconferencia['id'];
        $horario->lugar = $request->lugar;
        $horario->fecha = $request->fecha;
        $horario->horaInicial = $request->str_hora_inicial;
        $horario->horaFinal = $request->str_hora_final;
        $horario->cupo = $request->cupo;
        $horario->orador = $conferencista->id;
        $horario->save();

        return [
            'title'=>'Éxito',
            'content'=>'Horario asignado con éxito',
            'type'=>'success',
        ];



    }

    public function nombrePeriodo()
    {
        $fecha_actual = Carbon::now()->toDateString();
        $vector = explode('-', $fecha_actual);
        $nombre_periodo = $vector[0];
        if($vector[1]>= 1 && $vector[1] <= 5)
        {
            $nombre_periodo= $nombre_periodo.'-I';
        }
        else if($vector[1]== 12)
        {
            $nombre_periodo = $vector[0] + 1;
            $nombre_periodo = $nombre_periodo.'-I';
        }
        else if($vector[1]>= 6 && $vector[1] <= 11)
        {
            $nombre_periodo= $nombre_periodo.'-II';
        }


        return $nombre_periodo;

    }

    public function nombrePeriodo2()
    {
        $fecha_actual = Carbon::now()->toDateString();
        $vector = explode('-', $fecha_actual);
        $nombre_periodo = $vector[0];

        if($vector[1]>= 1 && $vector[1] <= 6)
        {
            $nombre_periodo= $nombre_periodo.'-I';
        }
        else if($vector[1]>= 7 && $vector[1] <= 12)
        {
            $nombre_periodo= $nombre_periodo.'-II';
        }

        return $nombre_periodo;
    }

    public function getProgramasjson()
    {
        return Dependencia::where('idTipo', TipoDependencia::where('nombre', 'Dirección de programa')->first()->id)->get();
    }

    public function getGenerarlista($idcharla = null, $idprograma = null)
    {
        if($idcharla == null || $idprograma == null)
        {
            return redirect('/admin/charlas');
        }
        $estudiante = DB::table('estudiantes')
                        ->join('personas', 'estudiantes.idPersona','=','personas.id')
                        ->selectRaw('codigo, concat(nombres, " ", apellidos) as nombre')
                        ->where('idPrograma', $idprograma)
                        ->where(function ($query) {
                            $query->where('idTipo','=', TipoEstudiante::where('nombre', 'Preprácticas')->first()->id)
                            ->orWhere('idTipo','=', TipoEstudiante::where('nombre', 'Prácticas y preprácticas')->first()->id);
                        })
                        ->get();
        $estudiantes =  array();

        $con=0;
        foreach ($estudiante as $item) {
            $estudiantes[$con]['nombre'] = $item->nombre;
            $estudiantes[$con]['codigo'] = $item->codigo;
            $con++;
        }

        $horario = ConferenciaPeriodo::find($idcharla);

        $nombre = $horario->getconferencia->nombre;
        $fecha = $horario->fecha;
        $programa = Dependencia::find($idprograma)->nombre;
        $lugar = $horario->lugar;

        $data = [
            'nombre'=>$nombre,
            'fecha'=>$fecha,
            'programa'=>$programa,
            'lugar'=>$lugar,
            'estudiantes'=>$estudiantes
        ];


        $html = DPDFTemplates::render("lista_programa", $data);

        Pdf::render("Asistencia_".$programa, $html);


        // return view('admin.prueba', compact('estudiantes', 'nombre', 'fecha', 'programa', 'lugar'));
    }

    public function getConferenciasjson()
    {
        $nombre_periodo = $this->nombrePeriodo2();

        $periodo = Periodo::where('nombre', $nombre_periodo)
                          ->first();

        $conferencias = Conferencia::whereHas('gethorarios', function($q) use ($periodo){
                                       $q->where('idPeriodo', $periodo->id);
                                   })
                                   ->get();
        return $conferencias;
    }

    public function getConferenciasjson2()
    {
        $conferencias = Conferencia::all();

        for($i=0; $i < sizeof($conferencias); $i++)
        {
            $programas = $conferencias[$i]->getprogramas;
            $conferencias[$i]->nombreProgramas = '';

            foreach($programas as $item)
            {
                $conferencias[$i]->nombreProgramas = $conferencias[$i]->nombreProgramas.$item->nombre.', ';
            }

            $conferencias[$i]->nombreProgramas = substr($conferencias[$i]->nombreProgramas, 0, -2);

            if(!$conferencias[$i]->nombreProgramas)
                $conferencias[$i]->nombreProgramas="";
        }

        return $conferencias;
    }

    public function getPrepracticantesjson()
    {
        $estudiantes = Estudiante::with('getpersona')
                                 ->where('idTipo', TipoEstudiante::where('nombre', 'Preprácticas')->first()->id)
                                 ->orWhere('idTipo', TipoEstudiante::where('nombre', 'Prácticas y preprácticas')->first()->id)
                                 ->get();
        return $estudiantes;
    }

    public function postGuardarasistencia(AsistenciaRequest $request)
    {
        $nombre_periodo = $this->nombrePeriodo2();
        $periodo = Periodo::where('nombre', $nombre_periodo)->first();
        $conferenciasPeriodo = ConferenciaPeriodo::where('idConferencia', $request->conferencia['id'])
                                                 ->where('idPeriodo', $periodo->id)
                                                 ->select('id')
                                                 ->get();
        $ids_horarios = [];
        $con=0;
        foreach($conferenciasPeriodo as $item)
        {
            $ids_horarios[$con] = $item->id;
            $con++;
        }

        $ids_estudiantes = [];
        $con=0;
        foreach($request->estudiantes as $item)
        {
            $ids_estudiantes[$con] = $item['id'];
            $con++;
        }

        $asistencias = Asistencia::whereIn('idConferenciaPeriodo', $ids_horarios)
                                 ->whereIn('idEstudiante', $ids_estudiantes)
                                 ->update(['asistio'=>false]);

        $inasistencias = Asistencia::whereIn('idConferenciaPeriodo', $ids_horarios)
                                   ->whereNotIn('idEstudiante', $ids_estudiantes)
                                   ->update(['asistio'=>true]);


        return[
            'title'=>'Éxito',
            'content'=>'Asistencia registrada con éxito',
            'type'=>'success',
        ];
    }

    public function getAsistentesjson($id)
    {
        return Asistencia::with('getestudiante.getpersona')
                         ->with('getestudiante.getprograma')
                         ->where('idConferenciaPeriodo', $id)
                         ->get();

    }

    public function getCartas()
    {
        return view('admin.cartas');
    }

    public function getCartasjson()
    {
        return Carta::with('getestudiante.getpersona')
                    ->with('getestado')
                    ->with('getempresa.getsedes.getmunicipio')
                    ->orderBy('id', 'desc')
                    ->get();
    }


    public function postCambiarestadocarta(CartaRevisadaRequest $request)//
    {
        $carta = Carta::find($request->id);

        if($request->getestado['nombre'] == 'Aprobada')
        {
            $fecha = Carbon::now()->format('d/m/Y');
            $radicado = 'DPP-'.$request->radicado.'-'.substr(explode('-', $fecha)[0] ,2,2);
            $empresa = $carta->getempresa->nombre;
            $ciudad = $carta->getempresa->getsedes[0]->getmunicipio->nombre;
            $nombre = $carta->getestudiante->getpersona->nombres.' '.$carta->getestudiante->getpersona->apellidos;
            $tipodoc = $carta->getestudiante->getpersona->tipodoc;
            $identificacion = $carta->getestudiante->getpersona->identificacion;
            $ciudadExpedicion = $carta->ciudadExpedicion;
            $programa = $carta->getestudiante->getprograma->nombre;

            $periodo=$request->periodo;
            if($periodo == 'I')
            {
                $periodo = "primer";
            }
            else if ($periodo == 'II')
            {
                $periodo = "segundo";
            }

            $anio = $request->anio;
            $directorUser = User::where('idRol', Rol::where('nombre', 'Administrador Dippro')->first()->id)->first();

            $director = $directorUser->getuser->nombres.' '.$directorUser->getuser->apellidos;
            $codigo_verificacion = $carta->getestudiante->codigo.Carbon::now();
            $vowels = array("-", " ", ":");
            $codigo_verificacion = str_replace($vowels, "", $codigo_verificacion);

            $data = [
                'fecha'=>$fecha,
                'radicado'=>$radicado,
                'empresa'=>$empresa,
                'ciudad'=>$ciudad,
                'nombre'=>$nombre,
                'tipodoc'=>$tipodoc,
                'identificacion'=>$identificacion,
                'ciudadExpedicion'=>$ciudadExpedicion,
                'programa'=>$programa,
                'periodo'=>$periodo,
                'anio'=>$anio,
                'director'=>$director,
                'codigo_verificacion'=>$codigo_verificacion,
            ];

            if($request->modelo == 1)
            {
                $html = DPDFTemplates::render("carta1", $data);
            }
            else if($request->modelo == 2)
            {
                $data['promedio'] = $request->promedio;
                $html = DPDFTemplates::render("carta2", $data);
            }
            else if($request->modelo == 3)
            {
                $html = DPDFTemplates::render("carta3", $data);
            }
            else if($request->modelo == 4)
            {
                $data['promedio'] = $request->promedio;
                $html = DPDFTemplates::render("carta4", $data);
            }


            $archivo = Pdf::get("Carta_presentacion", $html);
            $nombre_archivo = "Carta_presentacion_".Carbon::now().".pdf";

            if(Storage::disk('carta_presentacion')->has($carta->nombre_archivo))
            {
                Storage::disk('carta_presentacion')->delete($carta->nombre_archivo);
            }

            file_put_contents('/home/ubuntu/workspace/storage/app/carta_presentacion/'.$nombre_archivo, $archivo);

            $path = storage_path('app/carta_presentacion/'.$nombre_archivo);
            $estudiante = $carta->getestudiante;

            if($request->promedio != null)
            {
                $estudiante->promedio = $request->promedio;
                $estudiante->save();
            }

            $persona = $estudiante->getpersona;
            $persona->ciudadExpedicion = $ciudadExpedicion;
            $persona->save();

            $usuario = User::where('identificacion', $carta->getempresa->nit)->first();
            Mail::raw("Su carta de presentación fue aprobada y se encuentra adjunta en este correo", function ($message) use ($path, $estudiante) {
                $message->from('hello@app.com', env('MAIL_FROM'));
                $message->attach($path);
                $message->to($estudiante->getpersona->correo, $estudiante->getpersona->nombres.' '.$estudiante->getpersona->apellidos)->subject('Carta de presentación');
            });

            Mail::raw("Usted ha recibido la carta de presentación del estudiante: ".$estudiante->getpersona->nombres.' '.$estudiante->getpersona->apellidos
                , function ($message) use ($path, $usuario) {
                $message->from('hello@app.com', env('MAIL_FROM'));
                $message->attach($path);
                $message->to($usuario->getuser->correo, $usuario->getuser->nombres.' '.$usuario->getuser->apellidos)->subject('Carta de presentación');
            });

            $carta->estado = $request->getestado['id'];
            $carta->radicado = $radicado;
            $carta->anio = $request->anio;
            $carta->periodo = $request->periodo;
            $carta->fecha = Carbon::now();
            $carta->codigo_verificacion = $codigo_verificacion;
            $carta->nombre_archivo = $nombre_archivo;
            $carta->save();

            return [
                'title'=>'Éxito',
                'content'=>'Carta de presentación aprobada con éxito',
                'type'=>'success',
            ];
        }
        else if($request->getestado['nombre'] == 'Rechazada')
        {
            $carta->estado = $request->getestado['id'];
            $carta->save();

            return [
                'title'=>'Éxito',
                'content'=>'Carta de presentación rechazada con éxito',
                'type'=>'success',
            ];
        }

        return [
            'title'=>'Error',
            'content'=>'No se pudo realizar el cambio',
            'type'=>'error',
        ];

    }

    public function getEstadoscartajson()
    {
        return EstadoCarta::all();
    }

    public function getDescargarinforme($id)
    {
        $acta = ModalidadEstudiante::find($id);

        $nombre = $acta->informe;

        $path = storage_path('app/informes_practicas/'.$nombre);

        return \Response::download($path);

    }

    public function getCorreomasivo()
    {
        return view('admin.correomasivo');
    }

    public function getRolescorreomasivo()
    {
        $roles = Rol::whereIn('nombre', ['Estudiante', 'Tutor', 'Empresa'])->get();

        return $roles;
    }

    public function postUsuarioscorreomasivo(Request $request = null)
    {

        if(sizeof($request->roles) == 0)
        {
            $roles_ = $this->getRolescorreomasivo();
            $con=0;
            $roles = [];

            foreach($roles_ as $item)
            {
                $roles[$con] = $item->id;
                $con++;
            }

            return User::with('getuser')
                       ->whereIn('idRol', $roles)
                       ->get();
        }
        else if(sizeof($request->programas) == 0)
        {
            $con=0;
            $roles = [];

            foreach($request->roles as $item)
            {
                $roles[$con] = $item['id'];
                $con++;
            }

            return User::with('getuser')
                       ->whereIn('idRol', $roles)
                       ->get();
        }
        else
        {
            $con=0;
            $roles = [];

            foreach($request->roles as $item)
            {
                if($item['nombre'] != 'Estudiante')
                {
                    $roles[$con] = $item['id'];
                    $con++;
                }
            }

            $con=0;
            $programas = [];

            foreach($request->programas as $item)
            {
                $programas[$con] = $item['id'];
                $con++;
            }

            $estudiantes = Estudiante::whereIn('idPrograma', $programas)->get();

            $con=0;
            $codigos = [];

            foreach($estudiantes as $item)
            {
                $codigos[$con] = $item->codigo;
                $con++;
            }

            return User::with('getuser')
                       ->whereIn('idRol', $roles)
                       ->orWhereIn('identificacion', $codigos)
                       ->get();
        }
    }

    public function postEnviocorreo(CorreoRequest $request)
    {
        $texto = $request->contenido;
        $path = null;

        if($request->file_archivo != null && $request->file_archivo != 'undefined')
        {
            $file = $request->file('file_archivo');

            if($file->getError() > 0)
            {
                return ['title'=>'Error', 'content'=>'El archivo debe pesar maximo 1MB', 'type'=>'error'];
            }

            if($file->getSize() <= 1048576)
            {
                $nombre = 'MAILFILE_'.'_'.\Carbon\Carbon::now().'.pdf';
                Storage::disk('mailfile')->put($nombre, $file = \File::get($file));
                $path = storage_path('app/mailfile/'.$nombre);
            }
            else
            {
                return ['title'=>'Error', 'content'=>'El archivo debe pesar maximo 1MB', 'type'=>'error'];
            }
        }

        if($path == null)
        {
            Mail::send('emails.correomasivo', ['html' => $texto], function ($message) use ($request) {
                $message->from('hello@app.com', env('MAIL_FROM'));
                foreach($request->usuarios as $item)
                {
                    $message->to($item['getuser']['correo'], $item['getuser']['nombres'].' '.$item['getuser']['apellidos'])->subject($request->asunto);
                }
            });
        }
        else
        {
            Mail::send('emails.correomasivo', ['html' => $texto], function ($message) use ($request, $path) {
                $message->from('hello@app.com', env('MAIL_FROM'));
                $message->attach($path);
                foreach($request->usuarios as $item)
                {
                    // dd($item);
                    $message->to($item['getuser']['correo'], $item['getuser']['nombres'].' '.$item['getuser']['apellidos'])->subject($request->asunto);
                }
            });
        }


        return [
            'title'=>'Éxito',
            'content'=>'Correo enviado con éxito',
            'type'=>'success',
        ];

    }

    public function getConveniosexcel()
    {
        // $convenios = DB::table('convenios')
        $convenios = Convenio::join('empresas', 'convenios.idEmpresa', '=', 'empresas.id')
                             ->selectRaw('empresas.nombre as Empresa, fecha_inicio as "Fecha de inicio", fecha_fin as "Fecha final"')
                             ->get();

        Excel::create('Convenios_vigentes', function($excel) use ($convenios){
            $excel->sheet('hoja_1', function($sheet) use($convenios){
                $sheet->fromArray($convenios);
            });
        })->export('xlsx');
    }

    public function getExterior()
    {
        return view('admin.exterior');
    }

    public function getExteriorjson($periodo = null)
    {
        if($periodo == null || $periodo == 0)
        {
            $exterior = ModalidadEstudiante::with('getmodalidad')
                                      ->with('getestudiante.getpersona')
                                      ->with('getestudiante.getpersona')
                                      ->with('getestudiante.getprograma')
                                      ->with('getciudad.getdepartamento.getpais')
                                      ->with('getestado')
                                      ->where('estado', EstadoPractica::where('nombre', 'Aprobada')->first()->id)
                                      ->where(function($query){
                                          $query->where('idModalidad', Modalidad::where('nombre', 'Prácticas internacionales')->first()->id)
                                                ->orWhere('idModalidad', Modalidad::where('nombre', 'Semestre en el exterior')->first()->id);
                                      })
                                      ->get();
        }
        else
        {
            $exterior = ModalidadEstudiante::with('getmodalidad')
                                      ->with('getestudiante.getpersona')
                                      ->with('getestudiante.getpersona')
                                      ->with('getestudiante.getprograma')
                                      ->with('getciudad.getdepartamento.getpais')
                                      ->with('getestado')
                                      ->where('estado', EstadoPractica::where('nombre', 'Aprobada')->first()->id)
                                      ->where(function($query){
                                          $query->where('idModalidad', Modalidad::where('nombre', 'Prácticas internacionales')->first()->id)
                                                ->orWhere('idModalidad', Modalidad::where('nombre', 'Semestre en el exterior')->first()->id);
                                      })
                                      ->where('periodo', $periodo)
                                      ->get();
        }


        return $exterior;
    }

    public function getExteriorexcel($periodo = null)
    {
        $vector = explode('-', $periodo);
        // dd($vector);
        if(sizeof($vector)==2)
        {
            $exterior = ModalidadEstudiante::join('estudiantes', 'estudiantes.id', '=', 'idEstudiante')
                                           ->join('personas', 'estudiantes.idPersona', '=', 'personas.id')
                                           ->join('modalidades', 'modalidades.id', '=', 'idModalidad')
                                           ->join('municipios', 'municipios.id', '=', 'idCiudad')
                                           ->join('departamentos', 'municipios.idDepartamento', '=', 'departamentos.id')
                                           ->join('paises', 'paises.id', '=', 'departamentos.idPais')
                                           ->join('dependencias', 'dependencias.id', '=', 'idPrograma')
                                           ->where('estado', EstadoPractica::where('nombre', 'Aprobada')->first()->id)
                                           ->where(function($query){
                                               $query->where('idModalidad', Modalidad::where('nombre', 'Prácticas internacionales')->first()->id)
                                                     ->orWhere('idModalidad', Modalidad::where('nombre', 'Semestre en el exterior')->first()->id);
                                           })
                                           ->selectRaw("estudiantes.codigo as Código, concat(personas.nombres, ' ', personas.apellidos) as Nombre,
                                                       modalidades.nombre as Modalidad, empresa as Organización, concat(municipios.nombre, ', ', paises.nombre) as Ciudad,
                                                       dependencias.nombre as Programa, periodo as Periodo")
                                           ->where('periodo', $periodo)
                                           ->get();
        }
        else
        {
            $exterior = ModalidadEstudiante::join('estudiantes', 'estudiantes.id', '=', 'idEstudiante')
                                           ->join('personas', 'estudiantes.idPersona', '=', 'personas.id')
                                           ->join('modalidades', 'modalidades.id', '=', 'idModalidad')
                                           ->join('municipios', 'municipios.id', '=', 'idCiudad')
                                           ->join('departamentos', 'municipios.idDepartamento', '=', 'departamentos.id')
                                           ->join('paises', 'paises.id', '=', 'departamentos.idPais')
                                           ->join('dependencias', 'dependencias.id', '=', 'idPrograma')
                                           ->where('estado', EstadoPractica::where('nombre', 'Aprobada')->first()->id)
                                           ->where(function($query){
                                               $query->where('idModalidad', Modalidad::where('nombre', 'Prácticas internacionales')->first()->id)
                                                     ->orWhere('idModalidad', Modalidad::where('nombre', 'Semestre en el exterior')->first()->id);
                                           })
                                           ->selectRaw("estudiantes.codigo as Código, concat(personas.nombres, ' ', personas.apellidos) as Nombre,
                                                       modalidades.nombre as Modalidad, empresa as Organización, concat(municipios.nombre, ', ', paises.nombre) as Ciudad,
                                                       dependencias.nombre as Programa, periodo as Periodo")
                                           ->get();
        }

        Excel::create('Estudiantes_en_el_exterior', function($excel) use ($exterior){
            $excel->sheet('hoja_1', function($sheet) use($exterior){
                $sheet->fromArray($exterior);
            });
        })->export('xlsx');

        // return $exterior;
    }

    public function getPeriodosmejson()
    {
        return ModalidadEstudiante::selectRaw('distinct periodo')
                                  ->whereNotNull('periodo')
                                  ->orderBy('periodo', 'desc')
                                  ->get();
    }

    public function getVinculacion()
    {
        return view('admin.vinculacion');
    }

    public function getVinculacionjson($periodo = null)
    {
        if($periodo == null || $periodo == 0)
        {
            $vinculacion = ModalidadEstudiante::with('getmodalidad')
                                      ->with('getestudiante.getpersona')
                                      ->with('getestudiante.getpersona')
                                      ->with('getestudiante.getprograma')
                                      ->with('getestudiante.getpostulaciones.getoferta.getsede.getempresa')
                                      ->with('getciudad')
                                      ->with('getestado')
                                      ->where('estado', EstadoPractica::where('nombre', 'Aprobada')->first()->id)
                                      ->where('idModalidad', Modalidad::where('nombre', 'Vinculación laboral')->first()->id)
                                      ->get();
        }
        else
        {
            $vinculacion = ModalidadEstudiante::with('getmodalidad')
                                      ->with('getestudiante.getpersona')
                                      ->with('getestudiante.getpersona')
                                      ->with('getestudiante.getprograma')
                                      ->with('getestudiante.getpostulaciones.getoferta.getsede.getempresa')
                                      ->with('getciudad')
                                      ->with('getestado')
                                      ->where('estado', EstadoPractica::where('nombre', 'Aprobada')->first()->id)
                                      ->where('idModalidad', Modalidad::where('nombre', 'Vinculación laboral')->first()->id)
                                      ->where('periodo', $periodo)
                                      ->get();
        }


        return $vinculacion;
    }

    public function getVinculacionexcel($periodo = null)
    {
        $vector = explode('-', $periodo);
        // dd($vector);
        if(sizeof($vector)==2)
        {
            $vinculacion = ModalidadEstudiante::join('estudiantes', 'estudiantes.id', '=', 'idEstudiante')
                                              ->join('personas', 'estudiantes.idPersona', '=', 'personas.id')
                                              ->join('dependencias', 'dependencias.id', '=', 'idPrograma')
                                              ->join('postulados', function($query){
                                                  $query->on('postulados.idEstudiante', '=', 'estudiantes.id')
                                                        ->where('idEstatoEmpresa', '=',EstadoPostulado::where('nombre', 'Seleccionado')->first()->id)
                                                        ->where('idEstadoEstudiante', '=',EstadoPostuladoEst::where('nombre', 'Aceptó')->first()->id);
                                              })
                                              ->join('ofertas', 'ofertas.id', '=', 'postulados.idOferta')
                                              ->join('sedes', 'ofertas.idSede', '=', 'sedes.id')
                                              ->join('empresas', 'empresas.id', '=', 'sedes.idEmpresa')
                                              ->where('estudiantes_modalidades.estado', EstadoPractica::where('nombre', 'Aprobada')->first()->id)
                                              ->where('idModalidad', Modalidad::where('nombre', 'Vinculación laboral')->first()->id)
                                              ->selectRaw('estudiantes.codigo as Código, concat(personas.nombres, " ", personas.apellidos) as Nombre,
                                                           empresas.nombre as Organizacion, DATE_FORMAT(fecha_inicio, "%d/%m/%Y") as "Fecha inicio",
                                                           DATE_FORMAT(fecha_fin, "%d/%m/%Y") as "Fecha finalización",
                                                           dependencias.nombre as Programa, periodo as Periodo')
                                              ->where('periodo', $periodo)
                                              ->get()->toArray();
        }
        else
        {
            $vinculacion = ModalidadEstudiante::join('estudiantes', 'estudiantes.id', '=', 'idEstudiante')
                                              ->join('personas', 'estudiantes.idPersona', '=', 'personas.id')
                                              ->join('dependencias', 'dependencias.id', '=', 'idPrograma')
                                              ->join('postulados', function($query){
                                                  $query->on('postulados.idEstudiante', '=', 'estudiantes.id')
                                                        ->where('idEstatoEmpresa', '=',EstadoPostulado::where('nombre', 'Seleccionado')->first()->id)
                                                        ->where('idEstadoEstudiante', '=',EstadoPostuladoEst::where('nombre', 'Aceptó')->first()->id);
                                              })
                                              ->join('ofertas', 'ofertas.id', '=', 'postulados.idOferta')
                                              ->join('sedes', 'ofertas.idSede', '=', 'sedes.id')
                                              ->join('empresas', 'empresas.id', '=', 'sedes.idEmpresa')
                                              ->where('estudiantes_modalidades.estado', EstadoPractica::where('nombre', 'Aprobada')->first()->id)
                                              ->where('idModalidad', Modalidad::where('nombre', 'Vinculación laboral')->first()->id)
                                              ->selectRaw('estudiantes.codigo as Código, concat(personas.nombres, " ", personas.apellidos) as Nombre,
                                                           empresas.nombre as Organizacion, DATE_FORMAT(fecha_inicio, "%d/%m/%Y") as "Fecha inicio",
                                                           DATE_FORMAT(fecha_fin, "%d/%m/%Y") as "Fecha finalización",
                                                           dependencias.nombre as Programa, periodo as Periodo')
                                              ->get()->toArray();
        }

        // dd($vinculacion);

        Excel::create('Vinculación_laboral', function($excel) use ($vinculacion){
            $excel->sheet('hoja_1', function($sheet) use($vinculacion){
                $sheet->fromArray($vinculacion);
            });
        })->export('xlsx');

        // return $exterior;
    }


    public function getFsantamarta()
    {
        return view('admin.fsantamarta');
    }

    public function getFsantamartajson($periodo = null)
    {
        if($periodo == null || $periodo == 0)
        {
            $practicas = ModalidadEstudiante::with('getmodalidad')
                                              ->with('getestudiante.getpersona')
                                              ->with('getestudiante.getpersona')
                                              ->with('getestudiante.getprograma')
                                              ->with('getestudiante.getpostulaciones.getoferta.getsede.getempresa')
                                              ->with('getestudiante.getpostulaciones.getoferta.getsede.getmunicipio')
                                              ->with('getciudad')
                                              ->with('getestado')
                                              ->where('estado', EstadoPractica::where('nombre', 'Aprobada')->first()->id)
                                              ->where('idModalidad', Modalidad::where('nombre', 'Vinculación laboral')->first()->id)
                                              ->whereHas('getestudiante', function($q){
                                                  $q->whereHas('getpostulaciones', function($q){
                                                      $q->whereHas('getoferta', function($q){
                                                          $q->whereHas('getsede', function($q){
                                                              $q->whereHas('getmunicipio', function($q){
                                                                  $q->where('id','=', 25)
                                                                    ->whereHas('getdepartamento', function($q){
                                                                        $q->where('idPais', Pais::where('nombre', 'COLOMBIA')->first()->id);
                                                                    });
                                                              });
                                                          });
                                                      });
                                                  });
                                              })
                                              ->get();
        }
        else
        {
            $practicas = ModalidadEstudiante::with('getmodalidad')
                                              ->with('getestudiante.getpersona')
                                              ->with('getestudiante.getpersona')
                                              ->with('getestudiante.getprograma')
                                              ->with('getestudiante.getpostulaciones.getoferta.getsede.getempresa')
                                              ->with('getciudad')
                                              ->with('getestado')
                                              ->where('estado', EstadoPractica::where('nombre', 'Aprobada')->first()->id)
                                              ->where('idModalidad', Modalidad::where('nombre', 'Vinculación laboral')->first()->id)
                                              ->where('periodo', $periodo)
                                              ->whereHas('getestudiante', function($q){
                                                  $q->whereHas('getpostulaciones', function($q){
                                                      $q->whereHas('getoferta', function($q){
                                                          $q->whereHas('getsede', function($q){
                                                              $q->whereHas('getmunicipio', function($q){
                                                                  $q->where('id','=', 25)
                                                                    ->whereHas('getdepartamento', function($q){
                                                                        $q->where('idPais', Pais::where('nombre', 'COLOMBIA')->first()->id);
                                                                    });
                                                              });
                                                          });
                                                      });
                                                  });
                                              })
                                              ->get();
        }


        return $practicas;
    }

    public function getFsantamartaexcel($periodo = null)
    {
        $vector = explode('-', $periodo);
        // dd($vector);
        if(sizeof($vector)==2)
        {
            $practicas = ModalidadEstudiante::join('estudiantes', 'estudiantes.id', '=', 'idEstudiante')
                ->join('personas', 'estudiantes.idPersona', '=', 'personas.id')
                ->join('dependencias', 'dependencias.id', '=', 'idPrograma')
                ->join('postulados', function($query){
                    $query->on('postulados.idEstudiante', '=', 'estudiantes.id')
                        ->where('idEstatoEmpresa', '=',EstadoPostulado::where('nombre', 'Seleccionado')->first()->id)
                        ->where('idEstadoEstudiante', '=',EstadoPostuladoEst::where('nombre', 'Aceptó')->first()->id);
                })
                ->join('ofertas', 'ofertas.id', '=', 'postulados.idOferta')
                ->join('sedes', 'ofertas.idSede', '=', 'sedes.id')
                ->join('empresas', 'empresas.id', '=', 'sedes.idEmpresa')
                ->join('municipios', 'municipios.id', '=', 'sedes.idMunicipio')
                ->join('usuarios', 'usuarios.id', '=', 'ofertas.idJefe')
                ->join('personas as p', 'p.id', '=', 'usuarios.idPersona')
                ->join('practicas_tutores', function($join){
                    $join->on('practicas_tutores.idPracticas', '=', 'estudiantes_modalidades.id')
                         ->where('practicas_tutores.activo','=', true);
                })
                ->join('usuarios as t', 't.id', '=', 'practicas_tutores.idTutor')
                ->join('personas as tutor', 'tutor.id', '=', 't.idPersona')
                ->where('estudiantes_modalidades.estado', EstadoPractica::where('nombre', 'Aprobada')->first()->id)
                ->where('idModalidad', Modalidad::where('nombre', 'Vinculación laboral')->first()->id)
                ->whereHas('getestudiante', function($q){
                    $q->whereHas('getpostulaciones', function($q){
                        $q->whereHas('getoferta', function($q){
                            $q->whereHas('getsede', function($q){
                                $q->whereHas('getmunicipio', function($q){
                                    $q->where('id','!=', 25)
                                      ->whereHas('getdepartamento', function($q){
                                          $q->where('idPais', Pais::where('nombre', 'COLOMBIA')->first()->id);
                                    });
                                });
                            });
                        });
                    });
                })
                ->selectRaw("estudiantes.codigo as 'Codigo', concat(personas.nombres, ' ', personas.apellidos) as Nombre,
                    personas.celular as 'Telefono', personas.correo as 'E-Mail',
                    empresas.nombre as Organizacion, sedes.direccion as 'Direccion', municipios.nombre as Ciudad,
                    concat(p.nombres, ' ', p.apellidos) as 'Jefe inmediato', p.celular as 'Telefono', p.correo as 'E-Mail',
                    usuarios.cargo as Cargo,
                    convert(varchar, fecha_inicio, 103) as 'Fecha de inicio',
                    convert(varchar, fecha_fin, 103) as 'Fecha de finalizacion',
                    concat(tutor.nombres, ' ', tutor.apellidos) as Tutor,
                    dependencias.nombre as Programa, periodo as Periodo")
                ->where('periodo', $periodo)
                ->get()->toArray();

        }
        else
        {
            $practicas = ModalidadEstudiante::join('estudiantes', 'estudiantes.id', '=', 'idEstudiante')
                ->join('personas', 'estudiantes.idPersona', '=', 'personas.id')
                ->join('dependencias', 'dependencias.id', '=', 'idPrograma')
                ->join('postulados', function($query){
                    $query->on('postulados.idEstudiante', '=', 'estudiantes.id')
                        ->where('idEstatoEmpresa', '=',EstadoPostulado::where('nombre', 'Seleccionado')->first()->id)
                        ->where('idEstadoEstudiante', '=',EstadoPostuladoEst::where('nombre', 'Aceptó')->first()->id);
                })
                ->join('ofertas', 'ofertas.id', '=', 'postulados.idOferta')
                ->join('sedes', 'ofertas.idSede', '=', 'sedes.id')
                ->join('empresas', 'empresas.id', '=', 'sedes.idEmpresa')
                ->join('municipios', 'municipios.id', '=', 'sedes.idMunicipio')
                ->join('usuarios', 'usuarios.id', '=', 'ofertas.idJefe')
                ->join('personas as p', 'p.id', '=', 'usuarios.idPersona')
                ->join('practicas_tutores', function($join){
                    $join->on('practicas_tutores.idPracticas', '=', 'estudiantes_modalidades.id')
                         ->where('practicas_tutores.activo','=', true);
                })
                ->join('usuarios as t', 't.id', '=', 'practicas_tutores.idTutor')
                ->join('personas as tutor', 'tutor.id', '=', 't.idPersona')
                ->where('estudiantes_modalidades.estado', EstadoPractica::where('nombre', 'Aprobada')->first()->id)
                ->where('idModalidad', Modalidad::where('nombre', 'Vinculación laboral')->first()->id)
                ->whereHas('getestudiante', function($q){
                    $q->whereHas('getpostulaciones', function($q){
                        $q->whereHas('getoferta', function($q){
                            $q->whereHas('getsede', function($q){
                                $q->whereHas('getmunicipio', function($q){
                                    $q->where('id','!=', 25)
                                      ->whereHas('getdepartamento', function($q){
                                          $q->where('idPais', Pais::where('nombre', 'COLOMBIA')->first()->id);
                                    });
                                });
                            });
                        });
                    });
                })
                ->selectRaw("estudiantes.codigo as 'Codigo', concat(personas.nombres, ' ', personas.apellidos) as Nombre,
                    personas.celular as 'Telefono', personas.correo as 'E-Mail',
                    empresas.nombre as Organizacion, sedes.direccion as 'Direccion', municipios.nombre as Ciudad,
                    concat(p.nombres, ' ', p.apellidos) as 'Jefe inmediato', p.celular as 'Telefono', p.correo as 'E-Mail',
                    usuarios.cargo as Cargo,
                    convert(varchar, fecha_inicio, 103) as 'Fecha de inicio',
                    convert(varchar, fecha_fin, 103) as 'Fecha de finalizacion',
                    concat(tutor.nombres, ' ', tutor.apellidos) as Tutor,
                    dependencias.nombre as Programa, periodo as Periodo")
                ->get()->toArray();
                    // -- DATE_FORMAT(fecha_inicio, "%d/%m/%Y") as "Fecha inicio",
                    // -- sDATE_FORMAT(fecha_fin, "%d/%m/%Y") as "Fecha finalización",
        }

         //dd($practicas);

        Excel::create('Prácticas_nacionales', function($excel) use ($practicas){
            $excel->sheet('hoja_1', function($sheet) use($practicas){
                $sheet->fromArray($practicas);
            });
        })->export('xlsx');

        // return $exterior;
    }

    public function getUbicacion()
    {
        return view('admin.ubicacion');
    }

    public function getUbicacionjson($periodo = null)
    {
        if($periodo == null || $periodo == 0)
        {
            $ubicacion = DB::select(DB::raw($this->consultaUbicacion()));
        }
        else
        {
            $ubicacion = DB::select(DB::raw($this->consultaUbicacion($periodo)));
        }

        return $ubicacion;
    }

    public function getUbicacionexcel($periodo = null)
    {
        $vector = explode('-', $periodo);
        // dd($vector);
        if(sizeof($vector)==2)
        {
            $ubicacion = DB::select(DB::raw($this->consultaUbicacion($periodo)));
        }
        else
        {
            $ubicacion = DB::select(DB::raw($this->consultaUbicacion()));
        }
        $con=0;
        $ubi = [];
        foreach($ubicacion as $item)
        {
            $ubi[$con]['Programa'] = $item->Programa;
            $ubi[$con]['Internacional'] = $item->Internacional;
            $ubi[$con]['Empresarismo'] = $item->Empresarismo;
            $ubi[$con]['PYMES'] = $item->PYMES;
            $ubi[$con]['Validacion'] = $item->Validacion;
            $ubi[$con]['Vinculacion'] = $item->Vinculacion;
            $ubi[$con]['Exterior'] = $item->Exterior;
            $ubi[$con]['Social'] = $item->Social;
            $ubi[$con]['Total'] = $item->Total;
            $con++;
        }
        // dd($practicas);

        Excel::create('Ubicación_practicantes', function($excel) use ($ubi){
            $excel->sheet('hoja_1', function($sheet) use($ubi){
                $sheet->fromArray($ubi);
            });
        })->export('xlsx');

        // return $exterior;
    }

    private function consultaUbicacion($periodo = null)
    {
        if($periodo != null)
        {
            return "SELECT 	dependencias.nombre AS Programa
                    		,COALESCE(total.total, '0') AS Total
                    		,COALESCE(internacional.total, '0') AS Internacional
                    		,COALESCE(empresarismo.total, '0') AS Empresarismo
                    		,COALESCE(pymes.total, '0') AS PYMES
                    		,COALESCE(validacion.total, '0') AS Validacion
                    		,COALESCE(vinculacion.total,'0') AS Vinculacion
                    		,COALESCE(semestre.total,'0') AS Exterior
                    		,COALESCE(social.total,'0') AS 'Social'
                    FROM	dependencias
                    LEFT JOIN
                    (
                    	SELECT 	dependencias.nombre AS programa
                    			,COUNT(em.idEstudiante) AS total

                    	FROM	estudiantes_modalidades AS em
                    	JOIN	estudiantes ON estudiantes.id = em.idEstudiante
                    	JOIN 	dependencias ON dependencias.id = estudiantes.idPrograma
                    	WHERE 	estado = (
                    				SELECT id FROM estado_practicas WHERE nombre ='Aprobada'
                    			)
                    			AND periodo = '".$periodo."'
                    	GROUP BY dependencias.nombre
                    ) AS total
                    ON total.programa = dependencias.nombre
                    LEFT JOIN
                    (
                    	SELECT 	dependencias.nombre AS programa
                    			,COUNT(em.idModalidad) AS total
                    	FROM	estudiantes_modalidades AS em
                    	JOIN	estudiantes ON estudiantes.id = em.idEstudiante
                    	JOIN 	dependencias ON dependencias.id = estudiantes.idPrograma
                    	JOIN 	modalidades md ON md.id = em.idmodalidad
                    			AND md.nombre = 'Vinculación laboral'
                    	WHERE 	estado = (
                    				SELECT id FROM estado_practicas WHERE nombre ='Aprobada'
                    			)
                    			AND periodo = '".$periodo."'
                    	GROUP BY dependencias.nombre
                    ) AS vinculacion
                    ON total.programa = vinculacion.programa
                    LEFT JOIN
                    (
                    	SELECT 	dependencias.nombre AS programa
                    			,COUNT(em.idModalidad) AS total
                    	FROM	estudiantes_modalidades AS em
                    	JOIN	estudiantes ON estudiantes.id = em.idEstudiante
                    	JOIN 	dependencias ON dependencias.id = estudiantes.idPrograma
                    	JOIN 	modalidades md ON md.id = em.idmodalidad
                    			AND md.nombre = 'Prácticas internacionales'
                    	WHERE 	estado = (
                    				SELECT id FROM estado_practicas WHERE nombre ='Aprobada'
                    			)
                    			AND periodo = '".$periodo."'
                    	GROUP BY dependencias.nombre
                    ) AS internacional
                    ON total.programa = internacional.programa
                    LEFT JOIN
                    (
                    	SELECT 	dependencias.nombre AS programa
                    			,COUNT(em.idModalidad) AS total
                    	FROM	estudiantes_modalidades AS em
                    	JOIN	estudiantes ON estudiantes.id = em.idEstudiante
                    	JOIN 	dependencias ON dependencias.id = estudiantes.idPrograma
                    	JOIN 	modalidades md ON md.id = em.idmodalidad
                    		AND md.nombre = 'Prácticas de empresarismo'
                    	WHERE 	estado = (
                    				SELECT id FROM estado_practicas WHERE nombre ='Aprobada'
                    			)
                    			AND periodo = '".$periodo."'
                    	GROUP BY dependencias.nombre
                    ) AS empresarismo
                    ON total.programa = empresarismo.programa
                    LEFT JOIN
                    (
                    	SELECT 	dependencias.nombre AS programa
                    			,COUNT(em.idModalidad) AS total
                    	FROM	estudiantes_modalidades AS em
                    	JOIN	estudiantes ON estudiantes.id = em.idEstudiante
                    	JOIN 	dependencias ON dependencias.id = estudiantes.idPrograma
                    	JOIN 	modalidades md ON md.id = em.idmodalidad
                    			AND md.nombre = 'Asesorías pymes'
                    	WHERE 	estado = (
                    				SELECT id FROM estado_practicas WHERE nombre ='Aprobada'
                    			)
                    			AND periodo = '".$periodo."'
                    	GROUP BY dependencias.nombre
                    ) AS pymes
                    ON total.programa = pymes.programa
                    LEFT JOIN
                    (
                    	SELECT 	dependencias.nombre AS programa
                    			,COUNT(em.idModalidad) AS total
                    	FROM	estudiantes_modalidades AS em
                    	JOIN	estudiantes ON estudiantes.id = em.idEstudiante
                    	JOIN 	dependencias ON dependencias.id = estudiantes.idPrograma
                    	JOIN 	modalidades md ON md.id = em.idmodalidad
                    			AND md.nombre = 'Validación'
                    	WHERE 	estado = (
                    				SELECT id FROM estado_practicas WHERE nombre ='Aprobada'
                    			)
                    			AND periodo = '".$periodo."'
                    	GROUP BY dependencias.nombre
                    ) AS validacion
                    ON total.programa = validacion.programa
                    LEFT JOIN
                    (
                    	SELECT 	dependencias.nombre AS programa
                    			,COUNT(em.idModalidad) AS total
                    	FROM	estudiantes_modalidades AS em
                    	JOIN	estudiantes ON estudiantes.id = em.idEstudiante
                    	JOIN 	dependencias ON dependencias.id = estudiantes.idPrograma
                    	JOIN 	modalidades md ON md.id = em.idmodalidad
                    			AND md.nombre = 'Semestre en el exterior'
                    	WHERE 	estado = (
                    				SELECT id FROM estado_practicas WHERE nombre ='Aprobada'
                    			)
                    			AND periodo = '".$periodo."'
                    	GROUP BY dependencias.nombre
                    ) AS semestre
                    ON total.programa = semestre.programa
                    LEFT JOIN
                    (
                    	SELECT 	dependencias.nombre AS programa
                    			,COUNT(em.idModalidad) AS total
                    	FROM	estudiantes_modalidades AS em
                    	JOIN	estudiantes ON estudiantes.id = em.idEstudiante
                    	JOIN 	dependencias ON dependencias.id = estudiantes.idPrograma
                    	JOIN 	modalidades md ON md.id = em.idmodalidad
                    			AND md.nombre = 'Práctica social'
                    	WHERE 	estado = (
                    				SELECT id FROM estado_practicas WHERE nombre ='Aprobada'
                    			)
                    			AND periodo = '".$periodo."'
                    	GROUP BY dependencias.nombre
                    ) AS social
                    ON total.programa = social.programa
                    WHERE   dependencias.idTipo = (select id from tipodependencias where nombre = 'Dirección de programa')
                    order by Total desc
            ";
        }
        else
        {
            return "SELECT  dependencias.nombre AS Programa
                            ,COALESCE(total.total, '0') AS Total
                            ,COALESCE(internacional.total, '0') AS Internacional
                            ,COALESCE(empresarismo.total, '0') AS Empresarismo
                            ,COALESCE(pymes.total, '0') AS PYMES
                            ,COALESCE(validacion.total, '0') AS Validacion
                            ,COALESCE(vinculacion.total,'0') AS Vinculacion
                            ,COALESCE(semestre.total,'0') AS Exterior
                            ,COALESCE(social.total,'0') AS 'Social'
                    FROM    dependencias
                    LEFT JOIN
                    (
                        SELECT  dependencias.nombre AS programa
                                ,COUNT(em.idEstudiante) AS total

                        FROM    estudiantes_modalidades AS em
                        JOIN    estudiantes ON estudiantes.id = em.idEstudiante
                        JOIN    dependencias ON dependencias.id = estudiantes.idPrograma
                        WHERE   estado = (
                                    SELECT id FROM estado_practicas WHERE nombre ='Aprobada'
                                )

                        GROUP BY dependencias.nombre
                    ) AS total
                    ON total.programa = dependencias.nombre
                    LEFT JOIN
                    (
                        SELECT  dependencias.nombre AS programa
                                ,COUNT(em.idModalidad) AS total
                        FROM    estudiantes_modalidades AS em
                        JOIN    estudiantes ON estudiantes.id = em.idEstudiante
                        JOIN    dependencias ON dependencias.id = estudiantes.idPrograma
                        JOIN    modalidades md ON md.id = em.idmodalidad
                                AND md.nombre = 'Vinculación laboral'
                        WHERE   estado = (
                                    SELECT id FROM estado_practicas WHERE nombre ='Aprobada'
                                )

                        GROUP BY dependencias.nombre
                    ) AS vinculacion
                    ON total.programa = vinculacion.programa
                    LEFT JOIN
                    (
                        SELECT  dependencias.nombre AS programa
                                ,COUNT(em.idModalidad) AS total
                        FROM    estudiantes_modalidades AS em
                        JOIN    estudiantes ON estudiantes.id = em.idEstudiante
                        JOIN    dependencias ON dependencias.id = estudiantes.idPrograma
                        JOIN    modalidades md ON md.id = em.idmodalidad
                                AND md.nombre = 'Prácticas internacionales'
                        WHERE   estado = (
                                    SELECT id FROM estado_practicas WHERE nombre ='Aprobada'
                                )

                        GROUP BY dependencias.nombre
                    ) AS internacional
                    ON total.programa = internacional.programa
                    LEFT JOIN
                    (
                        SELECT  dependencias.nombre AS programa
                                ,COUNT(em.idModalidad) AS total
                        FROM    estudiantes_modalidades AS em
                        JOIN    estudiantes ON estudiantes.id = em.idEstudiante
                        JOIN    dependencias ON dependencias.id = estudiantes.idPrograma
                        JOIN    modalidades md ON md.id = em.idmodalidad
                            AND md.nombre = 'Prácticas de empresarismo'
                        WHERE   estado = (
                                    SELECT id FROM estado_practicas WHERE nombre ='Aprobada'
                                )

                        GROUP BY dependencias.nombre
                    ) AS empresarismo
                    ON total.programa = empresarismo.programa
                    LEFT JOIN
                    (
                        SELECT  dependencias.nombre AS programa
                                ,COUNT(em.idModalidad) AS total
                        FROM    estudiantes_modalidades AS em
                        JOIN    estudiantes ON estudiantes.id = em.idEstudiante
                        JOIN    dependencias ON dependencias.id = estudiantes.idPrograma
                        JOIN    modalidades md ON md.id = em.idmodalidad
                                AND md.nombre = 'Asesorías pymes'
                        WHERE   estado = (
                                    SELECT id FROM estado_practicas WHERE nombre ='Aprobada'
                                )

                        GROUP BY dependencias.nombre
                    ) AS pymes
                    ON total.programa = pymes.programa
                    LEFT JOIN
                    (
                        SELECT  dependencias.nombre AS programa
                                ,COUNT(em.idModalidad) AS total
                        FROM    estudiantes_modalidades AS em
                        JOIN    estudiantes ON estudiantes.id = em.idEstudiante
                        JOIN    dependencias ON dependencias.id = estudiantes.idPrograma
                        JOIN    modalidades md ON md.id = em.idmodalidad
                                AND md.nombre = 'Validación'
                        WHERE   estado = (
                                    SELECT id FROM estado_practicas WHERE nombre ='Aprobada'
                                )

                        GROUP BY dependencias.nombre
                    ) AS validacion
                    ON total.programa = validacion.programa
                    LEFT JOIN
                    (
                        SELECT  dependencias.nombre AS programa
                                ,COUNT(em.idModalidad) AS total
                        FROM    estudiantes_modalidades AS em
                        JOIN    estudiantes ON estudiantes.id = em.idEstudiante
                        JOIN    dependencias ON dependencias.id = estudiantes.idPrograma
                        JOIN    modalidades md ON md.id = em.idmodalidad
                                AND md.nombre = 'Semestre en el exterior'
                        WHERE   estado = (
                                    SELECT id FROM estado_practicas WHERE nombre ='Aprobada'
                                )

                        GROUP BY dependencias.nombre
                    ) AS semestre
                    ON total.programa = semestre.programa
                    LEFT JOIN
                    (
                        SELECT  dependencias.nombre AS programa
                                ,COUNT(em.idModalidad) AS total
                        FROM    estudiantes_modalidades AS em
                        JOIN    estudiantes ON estudiantes.id = em.idEstudiante
                        JOIN    dependencias ON dependencias.id = estudiantes.idPrograma
                        JOIN    modalidades md ON md.id = em.idmodalidad
                                AND md.nombre = 'Práctica social'
                        WHERE   estado = (
                                    SELECT id FROM estado_practicas WHERE nombre ='Aprobada'
                                )

                        GROUP BY dependencias.nombre
                    ) AS social
                    ON total.programa = social.programa
                    WHERE   dependencias.idTipo = (select id from tipodependencias where nombre = 'Dirección de programa')
                    order by Total desc
            ";
        }


    }

    public function getLaborando()
    {
        return view('admin.laborando');
    }

    public function getLaborandojson($periodo = null)
    {
        $autoevaluacion = Pregunta::where('enunciado', 'Como consecuencia de su práctica, ¿quedó laborando en la empresa?')->first()->id;

        $evaluacion = Pregunta::where('enunciado', 'Como consecuencia de su práctica, ¿el estudiante quedó laborando en la empresa?')->first()->id;

        $respuestasauto = Respuesta::where('respuestaBooleana', true)
                               ->whereHas('getpregunta', function($q) use ($autoevaluacion){
                                   $q->where('preguntas.id',$autoevaluacion);
                               })
                               ->select('idEvaluado')
                               ->get();

        $respuestas = Respuesta::where('respuestaBooleana', true)
                               ->whereHas('getpregunta', function($q) use ($evaluacion){
                                   $q->where('preguntas.id',$evaluacion);
                               })
                               ->select('idEvaluado')
                               ->get();
        $estudiantes = [];
        $con = 0;
        foreach($respuestasauto as $item1)
        {
            foreach($respuestas as $item2)
            {
                if($item1->idEvaluado == $item2->idEvaluado)
                {
                    $estudiantes[$con] = $item1->idEvaluado;
                    $con++;
                    break;
                }
            }
        }

        $usuarios = User::whereIn('id', $estudiantes)
                        ->select('identificacion')
                        ->get()->toArray();

        $ests = Estudiante::whereIn('codigo', $usuarios)
                          ->select('id')
                          ->get()->toArray();

        //codigo, nombre, empresa, programa, periodo
        if($periodo == null || $periodo == 0)
        {
            $practicas = ModalidadEstudiante::with('getestudiante.getpersona')
                                            ->with('getestudiante.getprograma')
                                            ->with('getestudiante.getpostulaciones.getoferta.getsede.getempresa')
                                            ->where('estado', EstadoPractica::where('nombre', 'Aprobada')->first()->id)
                                            ->where('idModalidad', Modalidad::where('nombre', 'Vinculación laboral')->first()->id)
                                            ->whereIn('idEstudiante', $ests)
                                            ->get();
        }
        else
        {
            $practicas = ModalidadEstudiante::with('getestudiante.getpersona')
                                            ->with('getestudiante.getprograma')
                                            ->with('getestudiante.getpostulaciones.getoferta.getsede.getempresa')
                                            ->where('estado', EstadoPractica::where('nombre', 'Aprobada')->first()->id)
                                            ->where('idModalidad', Modalidad::where('nombre', 'Vinculación laboral')->first()->id)
                                            ->where('periodo', $periodo)
                                            ->whereIn('idEstudiante', $ests)
                                            ->get();
        }
        return $practicas;
    }

    public function getLaborandoexcel($periodo=null)
    {
        $vector = explode('-', $periodo);
        // dd($vector);

        $autoevaluacion = Pregunta::where('enunciado', 'Como consecuencia de su práctica, ¿quedó laborando en la empresa?')->first()->id;

        $evaluacion = Pregunta::where('enunciado', 'Como consecuencia de su práctica, ¿el estudiante quedó laborando en la empresa?')->first()->id;

        $respuestasauto = Respuesta::where('respuestaBooleana', true)
                               ->whereHas('getpregunta', function($q) use ($autoevaluacion){
                                   $q->where('preguntas.id',$autoevaluacion);
                               })
                               ->select('idEvaluado')
                               ->get();

        $respuestas = Respuesta::where('respuestaBooleana', true)
                               ->whereHas('getpregunta', function($q) use ($evaluacion){
                                   $q->where('preguntas.id',$evaluacion);
                               })
                               ->select('idEvaluado')
                               ->get();
        $estudiantes = [];
        $con = 0;
        foreach($respuestasauto as $item1)
        {
            foreach($respuestas as $item2)
            {
                if($item1->idEvaluado == $item2->idEvaluado)
                {
                    $estudiantes[$con] = $item1->idEvaluado;
                    $con++;
                    break;
                }
            }
        }

        $usuarios = User::whereIn('id', $estudiantes)
                        ->select('identificacion')
                        ->get()->toArray();

        $ests = Estudiante::whereIn('codigo', $usuarios)
                          ->select('id')
                          ->get()->toArray();

        if(sizeof($vector)==2)
        {
            $laborando = ModalidadEstudiante::join('estudiantes', 'estudiantes.id', '=', 'idEstudiante')
                                              ->join('personas', 'estudiantes.idPersona', '=', 'personas.id')
                                              ->join('dependencias', 'dependencias.id', '=', 'idPrograma')
                                              ->join('postulados', function($query){
                                                  $query->on('postulados.idEstudiante', '=', 'estudiantes.id')
                                                        ->where('idEstatoEmpresa', '=',EstadoPostulado::where('nombre', 'Seleccionado')->first()->id)
                                                        ->where('idEstadoEstudiante', '=',EstadoPostuladoEst::where('nombre', 'Aceptó')->first()->id);
                                              })
                                              ->join('ofertas', 'ofertas.id', '=', 'postulados.idOferta')
                                              ->join('sedes', 'ofertas.idSede', '=', 'sedes.id')
                                              ->join('empresas', 'empresas.id', '=', 'sedes.idEmpresa')
                                              ->where('estudiantes_modalidades.estado', EstadoPractica::where('nombre', 'Aprobada')->first()->id)
                                              ->where('idModalidad', Modalidad::where('nombre', 'Vinculación laboral')->first()->id)
                                              ->selectRaw("estudiantes.codigo as Código, concat(personas.nombres, ' ', personas.apellidos) as Nombre,
                                                           empresas.nombre as Organizacion,
                                                           dependencias.nombre as Programa, periodo as Periodo")
                                              ->where('periodo', $periodo)
                                              ->whereIn('estudiantes_modalidades.idEstudiante', $ests)
                                              ->get()->toArray();
        }
        else
        {
            $laborando = ModalidadEstudiante::join('estudiantes', 'estudiantes.id', '=', 'idEstudiante')
                                              ->join('personas', 'estudiantes.idPersona', '=', 'personas.id')
                                              ->join('dependencias', 'dependencias.id', '=', 'idPrograma')
                                              ->join('postulados', function($query){
                                                  $query->on('postulados.idEstudiante', '=', 'estudiantes.id')
                                                        ->where('idEstatoEmpresa', '=',EstadoPostulado::where('nombre', 'Seleccionado')->first()->id)
                                                        ->where('idEstadoEstudiante', '=',EstadoPostuladoEst::where('nombre', 'Aceptó')->first()->id);
                                              })
                                              ->join('ofertas', 'ofertas.id', '=', 'postulados.idOferta')
                                              ->join('sedes', 'ofertas.idSede', '=', 'sedes.id')
                                              ->join('empresas', 'empresas.id', '=', 'sedes.idEmpresa')
                                              ->where('estudiantes_modalidades.estado', EstadoPractica::where('nombre', 'Aprobada')->first()->id)
                                              ->whereIn('estudiantes_modalidades.idEstudiante', $ests)
                                              ->where('idModalidad', Modalidad::where('nombre', 'Vinculación laboral')->first()->id)
                                              ->selectRaw("estudiantes.codigo as Código, concat(personas.nombres, ' ', personas.apellidos) as Nombre,
                                                           empresas.nombre as Organizacion,
                                                           dependencias.nombre as Programa, periodo as Periodo")
                                              ->get()->toArray();
        }

        // dd($vinculacion);

        Excel::create('Estudiantes_que_quedaron_laborando', function($excel) use ($laborando){
            $excel->sheet('hoja_1', function($sheet) use($laborando){
                $sheet->fromArray($laborando);
            });
        })->export('xlsx');
    }

    public function getImpacto()
    {
        return view('admin.impacto');
    }

    public function getImpactojson($periodo=null)
    {
        if($periodo == null || $periodo == 0)
        {
            $vinculacion = ModalidadEstudiante::with('getestudiante.getpersona')
                                      ->with('getestudiante.getprograma')
                                      ->with('getestudiante.getpostulaciones.getoferta.getsede.getempresa')
                                      ->where('estado', EstadoPractica::where('nombre', 'Aprobada')->first()->id)
                                      ->where('proyecto_impacto', true)
                                      ->get();
        }
        else
        {
            $vinculacion = ModalidadEstudiante::with('getestudiante.getpersona')
                                      ->with('getestudiante.getprograma')
                                      ->with('getestudiante.getpostulaciones.getoferta.getsede.getempresa')
                                      ->where('estado', EstadoPractica::where('nombre', 'Aprobada')->first()->id)
                                      ->where('periodo', $periodo)
                                      ->where('proyecto_impacto', true)
                                      ->get();
        }


        return $vinculacion;
    }

    public function getImpactoexcel($periodo = null)
    {
        $vector = explode('-', $periodo);

        if(sizeof($vector)==2)
        {
            $impacto = ModalidadEstudiante::join('estudiantes', 'estudiantes.id', '=', 'idEstudiante')
                                              ->join('personas', 'estudiantes.idPersona', '=', 'personas.id')
                                              ->join('dependencias', 'dependencias.id', '=', 'idPrograma')
                                              ->join('postulados', function($query){
                                                  $query->on('postulados.idEstudiante', '=', 'estudiantes.id')
                                                        ->where('idEstatoEmpresa', '=',EstadoPostulado::where('nombre', 'Seleccionado')->first()->id)
                                                        ->where('idEstadoEstudiante', '=',EstadoPostuladoEst::where('nombre', 'Aceptó')->first()->id);
                                              })
                                              ->join('ofertas', 'ofertas.id', '=', 'postulados.idOferta')
                                              ->join('sedes', 'ofertas.idSede', '=', 'sedes.id')
                                              ->join('empresas', 'empresas.id', '=', 'sedes.idEmpresa')
                                              ->where('estudiantes_modalidades.estado', EstadoPractica::where('nombre', 'Aprobada')->first()->id)
                                              ->selectRaw("estudiantes.codigo as Código, concat(personas.nombres, ' ', personas.apellidos) as Nombre,
                                                           COALESCE(estudiantes_modalidades.empresa, empresas.nombre) as Organizacion,
                                                           dependencias.nombre as Programa, nombre_impacto as Proyecto, periodo as Periodo")
                                              ->where('periodo', $periodo)
                                              ->where('proyecto_impacto', true)
                                              ->get()->toArray();
        }
        else
        {
            $impacto = ModalidadEstudiante::join('estudiantes', 'estudiantes.id', '=', 'idEstudiante')
                                              ->join('personas', 'estudiantes.idPersona', '=', 'personas.id')
                                              ->join('dependencias', 'dependencias.id', '=', 'idPrograma')
                                              ->join('postulados', function($query){
                                                  $query->on('postulados.idEstudiante', '=', 'estudiantes.id')
                                                        ->where('idEstatoEmpresa', '=',EstadoPostulado::where('nombre', 'Seleccionado')->first()->id)
                                                        ->where('idEstadoEstudiante', '=',EstadoPostuladoEst::where('nombre', 'Aceptó')->first()->id);
                                              })
                                              ->join('ofertas', 'ofertas.id', '=', 'postulados.idOferta')
                                              ->join('sedes', 'ofertas.idSede', '=', 'sedes.id')
                                              ->join('empresas', 'empresas.id', '=', 'sedes.idEmpresa')
                                              ->where('estudiantes_modalidades.estado', EstadoPractica::where('nombre', 'Aprobada')->first()->id)
                                              ->where('proyecto_impacto', true)
                                              ->selectRaw("estudiantes.codigo as Código, concat(personas.nombres, ' ', personas.apellidos) as Nombre,
                                                           COALESCE(estudiantes_modalidades.empresa, empresas.nombre) as Organizacion,
                                                           dependencias.nombre as Programa, nombre_impacto as Proyecto, periodo as Periodo")
                                              ->get()->toArray();
        }

        // dd($vinculacion);

        Excel::create('Proyectos_de_impacto', function($excel) use ($impacto){
            $excel->sheet('hoja_1', function($sheet) use($impacto){
                $sheet->fromArray($impacto);
            });
        })->export('xlsx');
    }

    public function getPrueba()
    {
        $pdf = new \LynX39\LaraPdfMerger\PdfManage;
        $one = "Carta_presentacion_2016-12-04 11_47_34.pdf";
        $two = "Carta_presentacion_2016-12-12 14_57_19.pdf";
        $path = storage_path('app/carta_presentacion/'.$one);
        $path2 = storage_path('app/carta_presentacion/'.$two);

        $pdf->addPDF($path, 'all');
        $pdf->addPDF($path2, 'all');
        //dd($path, $path2, $pdf);
        //$dir = "home/ubuntu/workspace/public/";

        $nombre_archivo = '2009114042_Final_'.\Carbon\Carbon::now().'.pdf';

        $archivo = $pdf->merge('browser', $nombre_archivo);

        if(Storage::disk('carta_presentacion')->has($nombre_archivo))
        {
            Storage::disk('carta_presentacion')->delete($nombre_archivo);
        }

        Storage::disk('carta_presentacion')->put($nombre_archivo, $archivo = \File::get($archivo));



        //file_put_contents('C:\xampp\htdocs\egresados\storage\app/carta_presentacion/'.$nombre_archivo, $archivo);



    }


}