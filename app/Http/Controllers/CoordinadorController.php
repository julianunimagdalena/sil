<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use App\Models\ActaRenovacion;
use App\Models\Asistencia;
use App\Models\Carta;
use App\Models\Conferencia;
use App\Models\ConferenciaPeriodo;
use App\Models\CoordinadorDependencia;
use App\Models\Contrato;
use App\Models\Convenio;
use App\Models\Dependencia;
use App\Models\Empresa;
use App\Models\EstadoCarta;
use App\Models\EstadoConvenio;
use App\Models\EstadoEmpresas;
use App\Models\EstadoOferta;
use App\Models\EstadoPostulado;
use App\Models\EstadoPostuladoEst;
use App\Models\EstadoPractica;
use App\Models\Estudiante;
use App\Models\Evaluacion;
use App\Models\Modalidad;
use App\Models\ModalidadEstudiante;
use App\Models\ModalidadEvaluacion;
use App\Models\Oferta;
use App\Models\OfertaPrograma;
use App\Models\Periodo;
use App\Models\Persona;
use App\Models\PosibleRespuesta;
use App\Models\Postulado;
use App\Models\PracticaTutor;
use App\Models\Pregunta;
use App\Models\PreguntaRespuesta;
use App\Models\Rol;
use App\Models\Salario;
use App\Models\Seccion;
use App\Models\Sede;
use App\Models\TipoEstudiante;
use App\Models\Tipooferta;
use App\Models\TipoPregunta;
use App\Models\User;

use Auth;
use Mail;
use DB;
use App\Providers\WebServiceSieg;
use App\Providers\WebService;

use App\Egresados\Core\DPDFTemplates;
use App\Egresados\Helpers\Pdf;

use App\Http\Requests\ActaRequest;
use App\Http\Requests\AddHorarioRequest;
use App\Http\Requests\AsistenciaRequest;
use App\Http\Requests\CartaRevisadaRequest;
use App\Http\Requests\CharlaRequest;
use App\Http\Requests\ConvenioRevisadoRequest;
use App\Http\Requests\PracticaRequest;
use App\Http\Requests\RechazarRequest;
use App\Http\Requests\RenovarRequest;
use App\Http\Requests\SuscribirRequest;
use App\Http\Requests\UsuarioRequest;

use Storage;

use Carbon\Carbon;

class CoordinadorController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('cdn');
    }
    
    private function getIds()
    {
        $id = Auth::user()->id;
        $ids = CoordinadorDependencia::where('idCoordinador', $id)
                                     ->get();
                                     
        $con = 0;
        $array = [];
        foreach($ids as $item)
        {
            $array[$con] = $item->idPrograma;
            $con++;
        }
        return $array;
    }
    
    public function getSolicitantes()
    {
        return view('coordinador.solicitantes');
    }
    
    public function getSolicitantesjson()
    {
        $solicitantes = Estudiante::with('getpersona')
                                  ->with('getprograma')
                                  ->with('getmodalidades')
                                  ->whereIn('idPrograma', $this->getIds())
                                  ->where(function ($query) {
                                      $query->where('idTipo', TipoEstudiante::where('nombre', 'Solicitó prácticas')->first()->id)
                                            ->orWhere('idTipo', TipoEstudiante::where('nombre', 'Solicitó prácticas y preprácticas')->first()->id);
                                  })
                                  ->get();
        return $solicitantes;
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
    
    public function getPracticantes()
    {
        return view('coordinador.practicantes');
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
                              ->selectRaw('distinct estudiantes_modalidades.id,estudiantes_modalidades.informe, estudiantes.codigo, concat(personas.nombres, " ", personas.apellidos) as nombre, dependencias.nombre as programa, 
                                           empresas.nombre as empresa, modalidades.nombre as modalidad, estado_practicas.nombre as estado, postulados.idEstudiante,
                                           estudiantes.id as idEst')
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
                              ->selectRaw('distinct estudiantes_modalidades.id,estudiantes_modalidades.informe, estudiantes.codigo, concat(personas.nombres, " ", personas.apellidos) as nombre, dependencias.nombre as programa, 
                                           empresas.nombre as empresa, modalidades.nombre as modalidad, estado_practicas.nombre as estado, postulados.idEstudiante,
                                           estudiantes.id as idEst')
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
                              ->selectRaw('distinct estudiantes_modalidades.id,estudiantes_modalidades.informe, estudiantes.codigo, concat(personas.nombres, " ", personas.apellidos) as nombre, dependencias.nombre as programa, 
                                           empresas.nombre as empresa, modalidades.nombre as modalidad, estado_practicas.nombre as estado, postulados.idEstudiante,
                                           estudiantes.id as idEst')
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
                              ->selectRaw('distinct estudiantes_modalidades.id,estudiantes_modalidades.informe, estudiantes.codigo, concat(personas.nombres, " ", personas.apellidos) as nombre, dependencias.nombre as programa, 
                                           empresas.nombre as empresa, modalidades.nombre as modalidad, estado_practicas.nombre as estado, postulados.idEstudiante,
                                           estudiantes.id as idEst')
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
                              ->selectRaw('distinct estudiantes_modalidades.id,estudiantes_modalidades.informe, estudiantes.codigo, concat(personas.nombres, " ", personas.apellidos) as nombre, dependencias.nombre as programa, 
                                           empresas.nombre as empresa, modalidades.nombre as modalidad, estado_practicas.nombre as estado, postulados.idEstudiante,
                                           estudiantes.id as idEst')
                              ->where('tipoestudiantes.nombre', 'Prácticas')
                              ->orWhere('tipoestudiantes.nombre', 'Prácticas y preprácticas')
                              ->get();
        }
            
        
        return $practicantes;
    }
    
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
    
    public function getVisitasjson($id)
    {
        $estudiante = Estudiante::find($id);
        
        $practica = $estudiante->getpracticas[sizeof($estudiante->getpracticas) - 1];
        
        $visitas = $practica->getvisitas;
        return $visitas;
    }
    
    public function getVerhojadevida($idEstudiante)
    {
        return view('coordinador.hoja', compact('idEstudiante'));
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
    
    public function getOfertas()
    {
        $soloDipro=1;
        $soloSil=0;
        
        return view('coordinador.ofertas', compact('soloSil', 'soloDipro'));
    }
    
    public function getOfertasjson()
    {
        $ofertas = Oferta::with('gettipo')
                         ->with('getestado')
                         ->with('getprogramas')
                         ->where('idTipo', Tipooferta::where('nombre','Practicantes')->first()->id)
                         ->orderBy('fechaCierre', 'desc')
                         ->get();
        
        
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
    
    public function getOfertajson($id)
    {
        $oferta = Oferta::with('getestado')
                        ->with('getsede')
                        ->with('getjefe.getsede.getempresa')
                        ->where('id', $id)->first();
        
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
        
        $oferta->estudiantes =  DB::table('estudiantes')
                                  ->join('personas', 'estudiantes.idPersona', '=', 'personas.id')
                                  ->join('postulados', 'estudiantes.id', '=', 'postulados.idEstudiante')
                                  ->join('ofertas', 'ofertas.id', '=', 'postulados.idOferta')
                                  ->selectRaw('distinct estudiantes.id, concat(estudiantes.codigo, " - ", personas.nombres, " ", personas.apellidos) as nombre')
                                  ->where('ofertas.id', $id)
                                  ->get();
        
        return $oferta;
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
        $oferta = Oferta::find($request->id);
        $oferta->estado = $request->estado['id'];
        $oferta->save();
        return ['title'=>'Exito', 'content'=>'Oferta '.$request->estado['nombre'].' con exito.', 'type'=>'success'];
    }
    
    public function getActas()
    {
        return view('coordinador.actas');
    }
    
    public function getActasjson()
    {
        $pos = ModalidadEstudiante::with('getmodalidad')
                                  ->with('getestudiante.getpersona')
                                  ->with('getestudiante.getpersona')
                                  ->with('getestudiante.getprograma')
                                  ->with('getestudiante.getpostulaciones.getoferta.getsede.getempresa')
                                  ->where('aprobacion_estudiante', true)
                                  ->where('idModalidad', '!=', Modalidad::where('nombre', 'Vinculación laboral')->first()->id)
                                  ->whereHas('getestudiante', function($q){
                                      $q->whereIn('idPrograma', $this->getIds());
                                  });
        
        $postulados = ModalidadEstudiante::with('getmodalidad')
                                         ->with('getestudiante.getpersona')
                                         ->with('getestudiante.getpersona')
                                         ->with('getestudiante.getprograma')
                                         ->with('getestudiante.getpostulaciones.getoferta.getsede.getempresa')
                                         ->where('aprobacion_jefe', true)
                                         ->where('aprobacion_estudiante', true)
                                         ->whereHas('getestudiante', function($q){
                                            $q->whereIn('idPrograma', $this->getIds());
                                         })
                                         ->union($pos)
                                         ->get();
        return $postulados;
    }
    
    public function getPracticantejson($id)
    {
        $estudiante = Estudiante::with('getpracticas.getmodalidad')
                                ->with('getpracticas.getestado')
                                ->with('getpersona')
                                ->where('id', $id)->first();
        return $estudiante;
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
    
    public function getVeracta($id)
    {
        $acta = ModalidadEstudiante::where('aprobacion_jefe', true)
                                   ->where('aprobacion_estudiante', true)
                                   ->where('id', $id)
                                   ->first();
                                   
        $postulado=null;
        $idEstadoEmpresa = EstadoPostulado::where('nombre', 'Seleccionado')->first()->id;
        $idEstadoEstudiante = EstadoPostuladoEst::where('nombre', 'Aceptó')->first()->id;
        foreach($acta->getestudiante->getpostulaciones as $pos)
        {
            if($pos->idEstatoEmpresa == $idEstadoEmpresa && $pos->idEstadoEstudiante = $idEstadoEstudiante)
            {
                $postulado = $pos;
                break;
            }
        }
        $acta->postulado = $postulado;
                                         
        return view('coordinador.veracta', compact('acta'));
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
    
    public function getCartas()
    {
        return view('coordinador.cartas');
    }
    
    public function getCartasjson()
    {
        return Carta::with('getestudiante.getpersona')
                    ->with('getestado')
                    ->whereHas('getestudiante', function($q){
                        $q->whereIn('idPrograma', $this->getIds());
                    })
                    ->get();
    }
    
    public function getEstadoscartajson()
    {
        return EstadoCarta::all();
    }
    
    public function postCambiarestadocarta(CartaRevisadaRequest $request)//
    {
        $carta = Carta::find($request->id);
        
        if($request->getestado['nombre'] == 'Aprobada')
        {
            $fecha = Carbon::now()->toDateString();
            $radicado = 'DPP-'.$request->radicado.'-'.substr(explode('-', $fecha)[0] ,2,2);
            $empresa = $carta->empresa;
            $ciudad = $carta->ciudad;
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
            
            $image = "data:image/jpeg;base64,".base64_encode(Storage::disk('firma')->get('firma.jpg'));
            $codigo_verificacion = $carta->getestudiante->codigo.Carbon::now();
            $vowels = array("-", " ", ":");
            $codigo_verificacion = str_replace($vowels, "", $codigo_verificacion);
            
            $data = [
                'fecha'=>Carbon::now()->toFormattedDateString(),
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
                'image'=>$image,
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
            
            
            Mail::raw("Su carta de presentación fue aprobada y se encuentra adjunta en este correo", function ($message) use ($path, $estudiante) {
                $message->from('hello@app.com', env('MAIL_FROM'));
                $message->attach($path);
                $message->to($estudiante->getpersona->correo, $estudiante->getpersona->nombres.' '.$estudiante->getpersona->apellidos)->subject('Carta de presentación');
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
    
    public function getPrepracticas()
    {
        return view('coordinador.prepracticas');
    }
    
    public function getPrepracticasjson()
    {
        $estudiante = Estudiante::with('getpersona')
                                ->with('getprograma')
                                ->whereIn('idPrograma', $this->getIds())
                                ->where(function ($query) {
                                    $query->where('idTipo', TipoEstudiante::where('nombre', 'Preprácticas')->first()->id)
                                    ->orWhere('idTipo', TipoEstudiante::where('nombre', 'Prácticas y preprácticas')->first()->id);
                                })
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
    
    public function getCharlas()
    {
        return view('coordinador.charlas');
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
    
    public function getProgramasjson()
    {
        return Dependencia::all();
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
    
    public function getPrepracticantesjson()
    {
        $estudiantes = Estudiante::with('getpersona')
                                 ->where('idTipo', TipoEstudiante::where('nombre', 'Preprácticas')->first()->id)
                                 ->orWhere('idTipo', TipoEstudiante::where('nombre', 'Prácticas y preprácticas')->first()->id)
                                 ->get();
        return $estudiantes;                                 
    }
    
    public function postSavecharla(CharlaRequest $request)
    {
        $conferencia = new Conferencia();
        $conferencia->nombre = $request->nombre;
        $conferencia->save();
        
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
        $horario = new ConferenciaPeriodo();
        // }
        
        
        $horario->idPeriodo = $periodo->id;
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
    
    public function postGuardarasistencia(AsistenciaRequest $request)
    {
        $nombre_periodo = $this->nombrePeriodo2();
        $periodo = Periodo::where('nombre', $nombre_periodo)->first();
        $conferenciasPeriodo = ConferenciaPeriodo::where('idConferencia', $request->conferencia['id'])
                                                 ->where('idPeriodo', $periodo->id)
                                                 ->select('id')
                                                 ->get();
        // dd($conferenciasPeriodo);
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
        // $asistencias = Asistencia::whereIn('idConferenciaPeriodo', $ids_horarios)
        //                          ->whereIn('idEstudiante', $ids_estudiantes)
        //                          ->get();
        // dd($asistencias, $ids_estudiantes, $ids_horarios);
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
    
    public function getConvenios()
    {
        return view('coordinador.convenios');
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
}