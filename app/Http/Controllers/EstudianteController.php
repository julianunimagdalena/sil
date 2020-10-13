<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;

use App\Http\Requests\CalificacionRequest;
use App\Http\Requests\CartaRequest;
use App\Http\Requests\DatosPersonalesRequest;
use App\Http\Requests\EstudioRequest;
use App\Http\Requests\ExperienciaRequest;
use App\Http\Requests\IdiomaRequest;
use App\Http\Requests\LegalizarRequest;
use App\Http\Requests\ModalidadRequest;
use App\Http\Requests\OtrasLegalizarRequest;
use App\Http\Requests\PerfilRequest;
use App\Http\Requests\ReferenciaRequest;

use App\Http\Controllers\Controller;

use Auth;
use Storage;
use Response;
use Mail;

use App\Providers\WebServiceSieg;

use App\Models\Asistencia;
use App\Models\Carta;
use App\Models\Competencia;
use App\Models\Conferencia;
use App\Models\ConferenciaPeriodo;
use App\Models\Empresa;
use App\Models\EstadoCarta;
use App\Models\EstadoCivil;
use App\Models\EstadoEmpresas;
use App\Models\EstadoPostulado;
use App\Models\EstadoPostuladoEst;
use App\Models\EstadoPractica;
use App\Models\Estudiante;
use App\Models\Estudio;
use App\Models\Experiencia;
use App\Models\Genero;
use App\Models\HojaCompetencia;
use App\Models\HojaIdioma;
use App\Models\Hojavida;
use App\Models\Idioma;
use App\Models\Modalidad;
use App\Models\ModalidadEstudiante;
use App\Models\Municipio;
use App\Models\NivelIdioma;
use App\Models\Oferta;
use App\Models\Pais;
use App\Models\Parentesco;
use App\Models\Periodo;
use App\Models\Persona;
use App\Models\Postulado;
use App\Models\Practica;
use App\Models\Referencia;
use App\Models\Rol;
use App\Models\TipoEstudiante;
use App\Models\User;
use App\Models\Visita;

use Carbon\Carbon;

class EstudianteController extends Controller
{
    
    public function __construct()
    {
        //5 padilla
        //19 frank
        //32 pater
        //10 jhon
        $usuario = User::find(19);
        Auth::login($usuario);
        $this->middleware('auth');
        $this->middleware('estudiante');
        $this->middleware('prepracticas', ['only'=>['getConferencias', 'getConferenciasjson', 'getAddconferencia', 'getHorario','getAsistenciasjson', 'postCalificarconferencia' ]]);
        $this->middleware('practicas', ['only'=> ['getOfertas', 'getOfertasjson', 'getOfertajson', 'getPostularse', 'getPracticas','getPracticasjson', 'postLegalizar']]);
        $this->middleware('vinculacion', ['only'=> ['getOfertas', 'getOfertasjson', 'getOfertajson', 'getPostularse']]);
        $this->middleware('ofertaEstudiante', ['only'=> ['getOfertajson', 'getPostularse', 'getNopostularse']]);
        $this->middleware('hoja', ['only'=> ['postSaveperfil', 'postSavereferencia']]);
    }
    
    public function getIndex()
    {
        // $ws = new WebServiceSieg();
            
        // $data =[
        //     'codigo'=>Auth::user()->identificacion,
        //     'token'=>strtoupper(md5($ws->token(Auth::user()->identificacion))),
        // ];
        
        // $image = $ws->call('getFotoByCodigo',[$data]);
        
        if(isset($image->return))
        {
            $image = $ws->call('getFotoByCodigo',[$data])->return->bytesFoto;
        }
        else
        {
            $image = null;
        }
        
        $codigo = Auth::user()->identificacion;
        
        $estudiante = Estudiante::where('codigo', $codigo)->first();
        
        return view('estudiante.index', compact('image', 'estudiante'));
    }
    
    public function getInfoestudiante()
    {
        $codigo = Auth::user()->identificacion;
        
        $estudiante = Estudiante::with('getpersona')
                                ->with('gettipo')
                                ->with('getprograma')
                                ->with('getcartas.getestado')
                                ->where('codigo', $codigo)
                                ->first();
        return $estudiante;
    }
    
    public function postSolicitarpracticas(ModalidadRequest $request)
    {
        
        $codigo = Auth::user()->identificacion;
        
        $estudiante = Estudiante::where('codigo', $codigo)->first();
        
        $hoja = $estudiante->gethojadevida;
        
        if( sizeof($hoja) == 0 )
        {
            return ['title'=>'Exito', 'content'=>'Usted no tiene hoja de vida registrada, por lo tanto no puede solicitar hacer practicas', 'type'=>'error'];
        }
        
        
        $cambio = "false";
        
        if($estudiante->gettipo->nombre == 'Preprácticas')
        {
            ModalidadEstudiante::create(['idEstudiante'=>$estudiante->id, 'idModalidad'=>$request->tipo['id']]);
            if($request->simultaneo)
            {
                $estudiante->idTipo = TipoEstudiante::where('nombre', 'Solicitó prácticas y preprácticas')->first()->id;
            }
            else
            {
                $estudiante->idTipo = TipoEstudiante::where('nombre', 'Solicitó prácticas')->first()->id;
            }
            $estudiante->save();
            $cambio = "true";
        }
        
        return $cambio;
    }
    
    /////////////////ofertas
    
    public function getOfertas()
    {   
        $codigo = Auth::user()->identificacion;
        
        $estudiante = Estudiante::where('codigo', $codigo)->first();
        
        if($estudiante->gettipo->nombre=='Prácticas')
        {
            $tipo = true;
        }
        else if($estudiante->gettipo->nombre=='Egresado')
        {
            $tipo = false;
        }
        
        return view('estudiante.ofertas', compact('tipo', 'estudiante'));
    }
    
    public function getOfertasjson()
    {
        $codigo = Auth::user()->identificacion;
        $estudiante = Estudiante::where('codigo', $codigo)->first();
        
        $idPrograma = $estudiante->idPrograma;
        $idEstudiante = $estudiante->id;
        
        $postulado = Postulado::where('idEstudiante', $estudiante->id)
                              ->where('idEstadoEstudiante', EstadoPostuladoEst::where('nombre', 'Aceptó')->first()->id)
                              ->first();
        
        if(sizeof($postulado)>0)
        {
            $ofertas = Oferta::getOfertasById($postulado->idOferta, $idEstudiante);
        }
        else
        {
            if($estudiante->gettipo->nombre == 'Prácticas')
            {
                $ofertas = Oferta::getOfertasByEstudiante($idPrograma, $idEstudiante);
            }
            else if($estudiante->gettipo->nombre == 'Egresado')
            {
                $ofertas = Oferta::getOfertasByEgresado($idPrograma, $idEstudiante);
            }
        }
        
            
                         
        return $ofertas;
    }
    
    public function getEstudiantejson()
    {
        $codigo = Auth::user()->identificacion;
        
        
        $estudiante = Estudiante::with('gettipo')
                                ->with('getpersona')
                                ->with('getpersona.getgenero')
                                ->with('getpersona.getciudad.getdepartamento.getpais')
                                ->with('getpersona.getestadocivil')
                                ->with('getprograma')
                                ->where('codigo', $codigo)
                                ->first();
        
        $datos['estudiante'] = $estudiante;
        
        
        return $datos;
    }
    
    public function getOfertajson($id)
    {
        $oferta = Oferta::find($id);
        
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
    
    public function getPostularse($idOferta)
    {
        $codigo = Auth::user()->identificacion;
        $estudiante = Estudiante::where('codigo', $codigo)->first();
        
        $postulado = new Postulado();
        $postulado->idEstudiante = $estudiante->id;
        $postulado->idOferta = $idOferta;
        $postulado->idEstatoEmpresa = EstadoPostulado::where('nombre', 'Postulado')->first()->id;
        $postulado->idEstadoEstudiante = EstadoPostuladoEst::where('nombre', 'Esperando respuesta')->first()->id;
        $postulado->save();
        
        $msj = array(
                'content'=>'Usted se ha postulado con exito'
            );
        return redirect('/estudiante/ofertas')->with($msj);
    }
    
    public function getNopostularse($idOferta)
    {
        $codigo = Auth::user()->identificacion;
        $estudiante = Estudiante::where('codigo', $codigo)->first();
        $postulado = Postulado::where('idOferta', $idOferta)->where('idEstudiante', $estudiante->id)->first();
        if($postulado->getestadoempresa->nombre != 'Seleccionado')
        {
            $postulado->delete();
            
            $msj = array(
                    'content'=>'Cancelación exitosa'
                );
            return redirect('/estudiante/ofertas')->with($msj);
        }
        
        
    }
    
    public function getHojadevida()
    {
        $codigo = Auth::user()->identificacion;
        $estudiante = Estudiante::where('codigo', $codigo)->first();
        
        return view('estudiante.hoja', compact('estudiante'));
    }
    
    public function getHojajson()
    {
        $codigo = Auth::user()->identificacion;
        $estudiante = Estudiante::where('codigo', $codigo)->first();        
        $hoja = $estudiante->gethojadevida;
        $idiomas = [];
        $competencias = [];
        if(sizeof($hoja)>0)
        {
            $data['perfil'] = $hoja[0]->perfil;
            $data['getestudios'] = Estudio::with('getmunicipio')
                                          ->where('idHoja', $hoja[0]->id)
                                          ->get();
            $data['getexperiencias'] = Experiencia::where('idHoja', $hoja[0]->id)
                                                  ->get();
            $data['getidiomas'] = HojaIdioma::with('getnivelescritura')
                                             ->with('getnivellectura')
                                             ->with('getnivelhabla')
                                             ->with('getidioma')
                                             ->where('idHoja', $hoja[0]->id)
                                             ->get();
                                             
            $data['getreferenciasf'] = Referencia::with('getparentesco')
                                                 ->where('idHoja', $hoja[0]->id)
                                                 ->whereNotNull('parentesco')
                                                 ->get();
                                                 
            $data['getreferenciasp'] = Referencia::with('getparentesco')
                                                 ->where('idHoja', $hoja[0]->id)
                                                 ->whereNull('parentesco')
                                                 ->get();
            
            $con=0;
            foreach($data['getidiomas'] as $idioma)
            {
                $idiomas[$con] = $idioma->idIdioma;
                $con++;
            } 
            
            $data['getcompetencias'] = $hoja[0]->getcompetencias;
            
            $con=0;
            foreach($data['getcompetencias'] as $competencia)
            {
                $competencias[$con] = $competencia->id;
                $con++;
            }
        }
        
        $data['estudiante'] = $this->getEstudiantejson()['estudiante'];
        // $data['estudianteAyre'] = $this->getEstudiantejson()['estudianteAyre'];
        $data['ciudades'] = Municipio::all();
        $anios = array();
        $fecha = \Carbon\Carbon::now();
        $fecha = $fecha->year;
        for($i = $fecha; $i >= 1950; $i--)
        {
            $anio['id'] = $i;
            $anio['nombre'] = $i;
            array_push($anios, $anio);
        }
        $data['anios'] = $anios;
        $data['generos'] = Genero::get();
        $data['estadocivil'] = EstadoCivil::get();
        $data['idiomasdb'] = Idioma::whereNotIn('id', $idiomas)->get();
        $data['nivelesidiomasdb'] = NivelIdioma::get();
        $data['competenciasdb'] = Competencia::get();
        $data['parentescosdb'] = Parentesco::get();
        $data['paises'] = Pais::all();
        
        
        return $data;
    }
    
    public function postEstudiorealizado(EstudioRequest $request)
    {
        
    }
    
    public function postExperiencialaboral(ExperienciaRequest $request)
    {
        
    }
    
    public function postIdioma(IdiomaRequest $request)
    {
        
    }
    
    public function postReferenciapersonal(ReferenciaRequest $request)
    {
        
    }
    
    public function postReferenciafamiliar(ReferenciaRequest $request)
    {
        
    }
    
    public function postActualizardatos(DatosPersonalesRequest $request)
    {
        $persona = Persona::find(Auth::user()->idPersona);
        
        $persona->ciudadOrigen = $request->getciudad['id'];
        
        $persona->fechaNacimiento = $request->fechaNacimiento;
        
        $persona->celular = $request->celular;
        $persona->correo = $request->correo;
        $persona->direccion = $request->direccion;
        $persona->idEstadoCivil = $request->getestadocivil['id'];
        $persona->idGenero = $request->getgenero['id'];
        $persona->save();
        
        return ['title'=>'Datos actualizados', 'content'=>'Sus datos fueron actualizados exitosamente', 'type'=>'success'];
    }
    
    public function postSaveperfil(PerfilRequest $request)
    {
        $codigo = Auth::user()->identificacion;
        $estudiante = Estudiante::where('codigo', $codigo)->first();
        $hoja = $estudiante->gethojadevida;
        
        if( sizeof($hoja) == 0 )
        {
            $hoja = new Hojavida();
            $hoja->idEstudiate = $estudiante->id;
        }
        else
        {
            $hoja = $hoja[0];
        }
        
        $hoja->perfil = $request->perfil;
        $hoja->save();
        
        HojaCompetencia::where('idHoja', $hoja->id)->delete();
        if(isset($request->getcompetencias))
        {
            foreach ($request->getcompetencias as $competencia) 
            {
                HojaCompetencia::create(['idHoja'=>$hoja->id, 'idCompetencia'=>$competencia['id']]);
            }
        }
            
        
        Estudio::where('idHoja', $hoja->id)->delete();
        
        foreach ($request->getestudios as $estudio) 
        {
            if(isset($estudio['observaciones']))
            {
                $observaciones = $estudio['observaciones'];
            }
            else
            {
                $observaciones = null;
            }
            // dd($observaciones);
            Estudio::create(['idHoja'=>$hoja->id, 'institucion'=>$estudio['institucion'], 'titulo'=>$estudio['titulo'], 'idMunicipio'=>$estudio['getmunicipio']['id'], 'anioGrado'=>$estudio['anioGrado'], 'observaciones'=>$observaciones]);
        }
        
        Experiencia::where('idHoja', $hoja->id)->delete();
        
        foreach ($request->getexperiencias as $experiencia) 
        {
            Experiencia::create(['idHoja'=>$hoja->id, 'empresa'=>$experiencia['empresa'], 'cargo'=>$experiencia['cargo'], 'duracion'=>$experiencia['duracion'], 'funcioneslogros'=>$experiencia['funcioneslogros']]);
        }
        
        HojaIdioma::where('idHoja', $hoja->id)->delete();
        
        foreach ($request->getidiomas as $idioma) 
        {
            HojaIdioma::create(['idHoja'=>$hoja->id, 'idIdioma'=>$idioma['getidioma']['id'], 'lectura'=>$idioma['getnivellectura']['id'], 'escritura'=>$idioma['getnivelescritura']['id'], 'habla'=>$idioma['getnivelhabla']['id']]);
        }
        
        return ['title'=>'Perfil guardado', 'content'=>'El perfil fue guardado exitosamente', 'type'=>'success']; 
    }
    
    public function postSavereferencia(Request $request)
    {
        $codigo = Auth::user()->identificacion;
        $estudiante = Estudiante::where('codigo', $codigo)->first();
        $hoja = $estudiante->gethojadevida;
        
        if( sizeof($hoja) == 0 )
        {
            $hoja = new Hojavida();
            $hoja->perfil = '';
            $hoja->idEstudiate = $estudiante->id;
            $hoja->save();
        }
        else
        {
            $hoja = $hoja[0];
        }
        
        Referencia::where('idHoja', $hoja->id)->delete();
        
        foreach($request->getreferenciasf as $r)
        {
            Referencia::create(['idHoja'=> $hoja->id, 'nombre'=>$r['nombre'], 'ocupacion'=>$r['ocupacion'], 'telefono'=>$r['telefono'], 'parentesco'=>$r['getparentesco']['id']]);
        }
        
        foreach($request->getreferenciasp as $r)
        {
            Referencia::create(['idHoja'=> $hoja->id, 'nombre'=>$r['nombre'], 'ocupacion'=>$r['ocupacion'], 'telefono'=>$r['telefono']]);
        }
        
        return ['title'=>'Exito', 'content'=>'Referencias guardadas exitosamente', 'type'=>'success'];
    }
    
    public function getAceptaroferta($idOferta)
    {
        $codigo = Auth::user()->identificacion;
        $estudiante = Estudiante::where('codigo', $codigo)->first();
        $idEstudiante = $estudiante->id;
        $postulado = Postulado::where('idOferta', $idOferta)
                              ->where('idEstudiante', $idEstudiante)
                              ->first();
                              
        $postulado->idEstadoEstudiante = EstadoPostuladoEst::where('nombre', 'Aceptó')->first()->id;
        $postulado->save();
        
        $postulados = Postulado::where('idOferta','!=', $idOferta)
                               ->where('idEstudiante', $idEstudiante)
                               ->get();
        
        foreach($postulados as $p)
        {
            $p->idEstatoEmpresa = EstadoPostulado::where('nombre', 'Postulado')->first()->id;
            $p->idEstadoEstudiante = EstadoPostuladoEst::where('nombre', 'No aceptó')->first()->id;
            $p->save();
        }
        $data['content'] = 'Usted ha aceptado la oferta';
        return redirect('/estudiante/ofertas')->with($data);
    }
    
    public function getRechazaroferta($idOferta)
    {
        $codigo = Auth::user()->identificacion;
        $estudiante = Estudiante::where('codigo', $codigo)->first();
        
        $idEstudiante = $estudiante->id;
        $postulado = Postulado::where('idOferta', $idOferta)
                              ->where('idEstudiante', $idEstudiante)
                              ->first();
                              
        $postulado->idEstadoEstudiante = EstadoPostuladoEst::where('nombre', 'No aceptó')->first()->id;
        $postulado->save();
        $data['warning'] = 'Usted no ha aceptado la oferta';
        return redirect('/estudiante/ofertas')->with($data);
    }
    
    public function getPracticasjson()
    {
        $codigo = Auth::user()->identificacion;
        $estudiante = Estudiante::where('codigo', $codigo)->first();
        $postulado = Postulado::with('getoferta.getsede.getempresa')
                              ->with('getoferta.getjefe.getuser')
                              ->where('idEstudiante', $estudiante->id)
                              ->where('idEstadoEstudiante', EstadoPostuladoEst::where('nombre', 'Aceptó')->first()->id)
                              ->first();
        $practica = ModalidadEstudiante::with('getestado')
                                       ->with('gettutores.getuser')
                                       ->where('idEstudiante', $estudiante->id)
                                       ->orderBy('id', 'desc')
                                       ->first();
        
        $data['postulado'] = $postulado;
        $data['practica'] = $practica;
        return $data;//['postulado']->getoferta->getjefe;
        
    }
    
    public function getPracticas()
    {
        $codigo = Auth::user()->identificacion;
        $estudiante = Estudiante::with('getpracticas.getmodalidad')
                                ->where('codigo', $codigo)->first();
        
        
        
        $sizeModalidades = sizeof($estudiante->getmodalidades);
        
        if($estudiante->getmodalidades[$sizeModalidades - 1]->nombre == 'Vinculación laboral')
        {
            $seleccionado = Postulado::where('idEstudiante', $estudiante->id)
                                     ->where('idEstatoEmpresa', EstadoPostulado::where('nombre', 'Seleccionado')->first()->id)
                                     ->where('idEstadoEstudiante', EstadoPostuladoEst::where('nombre', 'Aceptó')->first()->id)
                                     ->first();
            if($seleccionado == null)
            {
                return redirect('/estudiante/ofertas')->with(['error'=>'Usted aún no está listo para legalizar sus prácticas porque no ha sido seleccionado en una oferta']);
            }
            // dd($estudiante->getPracticas[$sizeModalidades - 1]->getestado);
            $oferta = $seleccionado->getoferta;
            // dd(($estudiante->getPracticas[$sizeModalidades - 1]->getestado == null || $estudiante->getPracticas[$sizeModalidades - 1]->getestado->nombre == 'Errada') || ( ($estudiante->getPracticas[$sizeModalidades - 1]->certificado_arl == null && $oferta->arl ) || !$estudiante->getPracticas[$sizeModalidades - 1]->aprobacion_estudiante ));
            // dd($oferta);
            if( ($estudiante->getPracticas[$sizeModalidades - 1]->getestado == null || $estudiante->getPracticas[$sizeModalidades - 1]->getestado->nombre == 'Errada') || ( ($estudiante->getPracticas[$sizeModalidades - 1]->certificado_arl == null && $oferta->arl ) || !$estudiante->getPracticas[$sizeModalidades - 1]->aprobacion_estudiante ))
            {
                return view('estudiante.legalizar', compact('estudiante', 'oferta'));
            }
            else if($estudiante->getPracticas[$sizeModalidades - 1]->getestado->nombre == 'Aprobada' || $estudiante->getPracticas[$sizeModalidades - 1]->getestado->nombre == 'Esperando respuesta')
            {
                return view('estudiante.practicas', compact('estudiante'));
                
            }
            
        }
        else
        {
            if( ($estudiante->getPracticas[$sizeModalidades - 1]->getestado == null || $estudiante->getPracticas[$sizeModalidades - 1]->getestado->nombre == 'Errada') ||  !$estudiante->getPracticas[$sizeModalidades - 1]->aprobacion_estudiante )
            {
                return view('estudiante.otraslegalizar', compact('estudiante'));
            }
            else //if($estudiante->getPracticas[$sizeModalidades - 1]->getestado->nombre == 'Aprobada' || $estudiante->getPracticas[$sizeModalidades - 1]->getestado->nombre == 'Esperando respuesta')
            {
                return view('estudiante.otraspracticas', compact('estudiante'));
            }
        }
    }
    
    public function postLegalizar(LegalizarRequest $request)
    {
        
        $codigo = Auth::user()->identificacion;
        $estudiante = Estudiante::where('codigo', $codigo)->first();
        
        $size = sizeof($estudiante->getpracticas);
        $practica = $estudiante->getpracticas[$size - 1];
        if($request->certificado_arl != null)
        {
            $arl = $request->file('certificado_arl');
            if($arl->getError() > 0)
            {
                return redirect('/estudiante/practicas')->with(['error'=>'El certificado de arl debe ser un archivo PDF y debe pesar maximo 1MB']);
            }
            
            if($arl->getMimeType() == 'application/pdf' && $arl->getSize() <= 1048576)
            {
                if(Storage::disk('legalizacion')->has($practica->certificado_arl))
                {
                    Storage::disk('legalizacion')->delete($practica->certificado_arl);
                }
                $nombre = 'ARL_'.Auth::user()->identificacion.'_'.\Carbon\Carbon::now().'.pdf';
                $practica->certificado_arl = $nombre;
                Storage::disk('legalizacion')->put($nombre, $arl = \File::get($arl));
            }
            $practica->nombre_arl = $request->nombre_arl;
        }
        
        $salud = $request->file('certificado_salud');
        if($salud->getError() > 0)
        {
            return redirect('/estudiante/practicas')->with(['error'=>'El certificado de salud debe ser un archivo PDF y debe pesar maximo 1MB']);
        }
        
        if($salud->getMimeType() == 'application/pdf' && $salud->getSize() <= 1048576)
        {
            if(Storage::disk('legalizacion')->has($practica->certificado_salud))
            {
                Storage::disk('legalizacion')->delete($practica->certificado_salud);
            }
            $nombre = 'SALUD_'.Auth::user()->identificacion.'_'.\Carbon\Carbon::now().'.pdf';
            $practica->certificado_salud = $nombre;
            Storage::disk('legalizacion')->put($nombre, $salud = \File::get($salud));
        }
        
        
        $practica->horario = $request->horario;
        $practica->estado = EstadoPractica::where('nombre','Esperando respuesta')->first()->id;
        $practica->aprobacion_estudiante = true;
        $practica->fecha_aprobacion_estudiante = \Carbon\Carbon::now();
        $practica->save();
        return redirect('/estudiante/practicas')->with(['success'=>'Su información ha sido guardada con éxito. Le estará llegando un correo para informarle si la información es correcta o si tiene que ser corregida.']);
    }
    
    // public function getLegalizar2()
    // {
    //     $nombre = 'Acta_legalizacion_'.Auth::user()->identificacion.'.pdf';
        
    //     $path = storage_path('app/legalizacion/'.$nombre);
        
    //     // $file = Storage::disk('legalizacion')->get($path);
        
    //     // return Response::make(base64_decode($path), 200, [
    //     //     'Content-Type' => 'application/pdf',
    //     //     'Content-Disposition' => 'inline; filename="'.$nombre,
    //     // ]);
        
    //     return Response::download($path);
    // }
    
    public function getAptoevaluar()
    {
        $apto = 0;
        $estudiante = Estudiante::where('codigo', Auth::user()->identificacion)->first();
        foreach($estudiante->getpracticas as $p)
        {
            if($p->getestado->nombre=='Aprobada')
            {
                $apto = 1;
                $fecha_fin = \Carbon\Carbon::create(explode('-',$p->fecha_fin)[0], explode('-',$p->fecha_fin)[1], explode('-',$p->fecha_fin)[2]);
                
                $fecha_actual = \Carbon\Carbon::now(); 
                
                if($fecha_fin->diffInDays($fecha_actual) > 7 && $fecha_fin > $fecha_actual)
                {
                    $apto = 0;
                }
                break;
            }
        }
        
        return $apto;
    }
    
    public function postInformepracticas(Request $request)
    {
        
        $codigo = Auth::user()->identificacion;
        $estudiante = Estudiante::where('codigo', $codigo)->first();
        
        $size = sizeof($estudiante->getpracticas);
        $practica = $estudiante->getpracticas[$size - 1];
        $file = $request->file('informe');
        if($file->getError() > 0)
        {
            return redirect('/estudiante/practicas')->with(['error'=>'El archivo debe ser formato pdf o docx y debe pesar menos de 1MB']);
        }
        $extension = explode('.', $file->getClientOriginalName());
        $extension = '.'.$extension[sizeof($extension) - 1];
        
        if(($file->getMimeType() == 'application/pdf' || $file->getMimeType() == 'application/vnd.openxmlformats-officedocument.wordprocessingml.document') && $file->getSize() <= 1048576)
        {
            if(Storage::disk('informes_practicas')->has($practica->informe))
            {
                Storage::disk('informes_practicas')->delete($practica->informe);
            }
            
            $nombre = 'INFORME_'.Auth::user()->identificacion.'_'.\Carbon\Carbon::now().$extension;
            $practica->informe = $nombre;
            Storage::disk('informes_practicas')->put($nombre, $file = \File::get($file));
        }
        else
        {
            return redirect('/estudiante/practicas')->with(['error'=>'El archivo debe ser formato pdf o docx y debe pesar menos de 1MB']);
        }
        
        $practica->save();
        return redirect('/estudiante/practicas')->with(['success'=>'Su informe de prácticas se ha subido con éxito.']);
    }
    
    public function getVisitasjson()
    {
        $idEstudiante = Estudiante::where('codigo', Auth::user()->identificacion)->first()->id;
        $practica = ModalidadEstudiante::where('idEstudiante', $idEstudiante)->where('estado', EstadoPractica::where('nombre', 'Aprobada')->first()->id)->first();
        $visitas = null;
        if($practica != null && sizeof($practica) > 0)
        {
            $visitas = $practica->getvisitas;
        }
        return $visitas;
    }
    
    public function getConfirmarvisita($id)
    {
        $visita = Visita::find($id);
        $visita->firma_estudiante = true;
        $visita->save();
        return ['title'=>'Éxito', 'content'=>'Visita confirmada con éxito', 'type'=>'success'];
    }
    
    public function getNoconfirmarvisita($id)
    {
        $visita = Visita::find($id);
        $visita->firma_estudiante = false;
        $visita->save();
        return ['title'=>'Éxito', 'content'=>'Visita no confirmada con éxito', 'type'=>'success'];
    }
    
    public function getPracticantejson()
    {
        $codigo = Auth::user()->identificacion;
        $estudiante = Estudiante::with('getpracticas.getmodalidad')
                                ->with('getpracticas.getestado')
                                ->with('getpersona')
                                ->where('codigo', $codigo)->first();
                                
        return $estudiante;
    }
    
    public function postOtraslegalizar(OtrasLegalizarRequest $request)
    {
        $codigo = Auth::user()->identificacion;
        $estudiante = Estudiante::where('codigo', $codigo)->first();
        $modalidad = $estudiante->getmodalidades[sizeof($estudiante->getmodalidades) - 1];
        $practica = $estudiante->getpracticas[sizeof($estudiante->getpracticas) - 1];
        
        if($request->file_carta_solicitud != null)
        {
            $file = $request->file('file_carta_solicitud');
            if($file->getError() > 0)
            {
                return ['title'=>'Error', 'content'=>'Todos los archivos deben ser formato PDF y deben pesar máximo 1MB', 'type'=>'error'];
            }
            
            if($file->getMimeType() == 'application/pdf' && $file->getSize() <= 1048576)
            {
                if(Storage::disk('legalizacion')->has($practica->file_carta_solicitud))
                {
                    Storage::disk('legalizacion')->delete($practica->file_carta_solicitud);
                }
                $nombre = 'CARTA_SOLICITUD_'.Auth::user()->identificacion.'_'.\Carbon\Carbon::now().'.pdf';
                $practica->file_carta_solicitud = $nombre;
                Storage::disk('legalizacion')->put($nombre, $file = \File::get($file));
            }
        }
        
        if($modalidad->nombre == 'Validación')
        {
            if($request->file_certificado_laboral != null)
            {
                $file = $request->file('file_certificado_laboral');
                if($file->getError() > 0)
                {
                    return ['title'=>'Error', 'content'=>'Todos los archivos deben ser formato PDF y deben pesar máximo 1MB', 'type'=>'error'];
                }
                
                if($file->getMimeType() == 'application/pdf' && $file->getSize() <= 1048576)
                {
                    if(Storage::disk('legalizacion')->has($practica->file_certificado_laboral))
                    {
                        Storage::disk('legalizacion')->delete($practica->file_certificado_laboral);
                    }
                    $nombre = 'CERTIFICADO_LABORAL_'.Auth::user()->identificacion.'_'.\Carbon\Carbon::now().'.pdf';
                    $practica->file_certificado_laboral = $nombre;
                    Storage::disk('legalizacion')->put($nombre, $file = \File::get($file));
                }
            }
            
            if($request->file_contrato != null)
            {
                $file = $request->file('file_contrato');
                if($file->getError() > 0)
                {
                    return ['title'=>'Error', 'content'=>'Todos los archivos deben ser formato PDF y deben pesar máximo 1MB', 'type'=>'error'];
                }
                
                if($file->getMimeType() == 'application/pdf' && $file->getSize() <= 1048576)
                {
                    if(Storage::disk('legalizacion')->has($practica->file_contrato))
                    {
                        Storage::disk('legalizacion')->delete($practica->file_contrato);
                    }
                    $nombre = 'CONTRATO_'.Auth::user()->identificacion.'_'.\Carbon\Carbon::now().'.pdf';
                    $practica->file_contrato = $nombre;
                    Storage::disk('legalizacion')->put($nombre, $file = \File::get($file));
                }
            }
        }
        
        if($modalidad->nombre == 'Validación' || $modalidad->nombre == 'Asesorías pymes' || $modalidad->nombre == 'Prácticas de empresarismo' || $modalidad->nombre == 'Prácticas internacionales')
        {
            if($request->file_existencia_empresa != null)
            {
                $file = $request->file('file_existencia_empresa');
                if($file->getError() > 0)
                {
                    return ['title'=>'Error', 'content'=>'Todos los archivos deben ser formato PDF y deben pesar máximo 1MB', 'type'=>'error'];
                }
                
                if($file->getMimeType() == 'application/pdf' && $file->getSize() <= 1048576)
                {
                    if(Storage::disk('legalizacion')->has($practica->file_existencia_empresa))
                    {
                        Storage::disk('legalizacion')->delete($practica->file_existencia_empresa);
                    }
                    $nombre = 'EXISTENCIA_EMPRESA_'.Auth::user()->identificacion.'_'.\Carbon\Carbon::now().'.pdf';
                    $practica->file_existencia_empresa = $nombre;
                    Storage::disk('legalizacion')->put($nombre, $file = \File::get($file));
                }
            }
        }
        
        if($modalidad->nombre == 'Validación' || $modalidad->nombre == 'Prácticas de empresarismo')
        {
            if($request->file_afiliacion_ss != null)
            {
                $file = $request->file('file_afiliacion_ss');
                if($file->getError() > 0)
                {
                    return ['title'=>'Error', 'content'=>'Todos los archivos deben ser formato PDF y deben pesar máximo 1MB', 'type'=>'error'];
                }
                
                if($file->getMimeType() == 'application/pdf' && $file->getSize() <= 1048576)
                {
                    if(Storage::disk('legalizacion')->has($practica->file_afiliacion_ss))
                    {
                        Storage::disk('legalizacion')->delete($practica->file_afiliacion_ss);
                    }
                    $nombre = 'AFILIACION_SS_'.Auth::user()->identificacion.'_'.\Carbon\Carbon::now().'.pdf';
                    $practica->file_afiliacion_ss = $nombre;
                    Storage::disk('legalizacion')->put($nombre, $file = \File::get($file));
                }
            }
        }
        
        if($modalidad->nombre == 'Asesorías pymes' || $modalidad->nombre == 'Prácticas internacionales' || $modalidad->nombre == 'Semestre en el exterior')
        {
            if($request->file_carta_colaboracion != null)
            {
                $file = $request->file('file_carta_colaboracion');
                if($file->getError() > 0)
                {
                    return ['title'=>'Error', 'content'=>'Todos los archivos deben ser formato PDF y deben pesar máximo 1MB', 'type'=>'error'];
                }
                
                if($file->getMimeType() == 'application/pdf' && $file->getSize() <= 1048576)
                {
                    if(Storage::disk('legalizacion')->has($practica->file_carta_colaboracion))
                    {
                        Storage::disk('legalizacion')->delete($practica->file_carta_colaboracion);
                    }
                    $nombre = 'CARTA_EMPRESA_'.Auth::user()->identificacion.'_'.\Carbon\Carbon::now().'.pdf';
                    $practica->file_carta_colaboracion = $nombre;
                    Storage::disk('legalizacion')->put($nombre, $file = \File::get($file));
                }
            }
        }
        
        if($modalidad->nombre == 'Asesorías pymes')
        {
            if($request->file_cedula_relegal != null)
            {
                $file = $request->file('file_cedula_relegal');
                if($file->getError() > 0)
                {
                    return ['title'=>'Error', 'content'=>'Todos los archivos deben ser formato PDF y deben pesar máximo 1MB', 'type'=>'error'];
                }
                
                if($file->getMimeType() == 'application/pdf' && $file->getSize() <= 1048576)
                {
                    if(Storage::disk('legalizacion')->has($practica->file_cedula_relegal))
                    {
                        Storage::disk('legalizacion')->delete($practica->file_cedula_relegal);
                    }
                    $nombre = 'CEDULA_REP_LEGAL_'.Auth::user()->identificacion.'_'.\Carbon\Carbon::now().'.pdf';
                    $practica->file_cedula_relegal = $nombre;
                    Storage::disk('legalizacion')->put($nombre, $file = \File::get($file));
                }
            }
        }
        
        if($modalidad->nombre == 'Prácticas internacionales' || $modalidad->nombre == 'Semestre en el exterior')
        {
            $practica->empresa = $request->empresa;
            $practica->idCiudad = $request->ciudad['id'];
            if($request->file_carta_director_programa != null)
            {
                $file = $request->file('file_carta_director_programa');
                if($file->getError() > 0)
                {
                    return ['title'=>'Error', 'content'=>'Todos los archivos deben ser formato PDF y deben pesar máximo 1MB', 'type'=>'error'];
                }
                
                if($file->getMimeType() == 'application/pdf' && $file->getSize() <= 1048576)
                {
                    if(Storage::disk('legalizacion')->has($practica->file_carta_director_programa))
                    {
                        Storage::disk('legalizacion')->delete($practica->file_carta_director_programa);
                    }
                    $nombre = 'CARTA_DIRECTOR_'.Auth::user()->identificacion.'_'.\Carbon\Carbon::now().'.pdf';
                    $practica->file_carta_director_programa = $nombre;
                    Storage::disk('legalizacion')->put($nombre, $file = \File::get($file));
                }
            }
            
            if($request->file_formato_movilidad != null)
            {
                $file = $request->file('file_formato_movilidad');
                if($file->getError() > 0)
                {
                    return ['title'=>'Error', 'content'=>'Todos los archivos deben ser formato PDF y deben pesar máximo 1MB', 'type'=>'error'];
                }
                
                if($file->getMimeType() == 'application/pdf' && $file->getSize() <= 1048576)
                {
                    if(Storage::disk('legalizacion')->has($practica->file_formato_movilidad))
                    {
                        Storage::disk('legalizacion')->delete($practica->file_formato_movilidad);
                    }
                    $nombre = 'FORMATO_MOVILIDAD_'.Auth::user()->identificacion.'_'.\Carbon\Carbon::now().'.pdf';
                    $practica->file_formato_movilidad = $nombre;
                    Storage::disk('legalizacion')->put($nombre, $file = \File::get($file));
                }
            }
            
            if($request->file_pasaporte != null)
            {
                $file = $request->file('file_pasaporte');
                if($file->getError() > 0)
                {
                    return ['title'=>'Error', 'content'=>'Todos los archivos deben ser formato PDF y deben pesar máximo 1MB', 'type'=>'error'];
                }
                
                if($file->getMimeType() == 'application/pdf' && $file->getSize() <= 1048576)
                {
                    if(Storage::disk('legalizacion')->has($practica->file_pasaporte))
                    {
                        Storage::disk('legalizacion')->delete($practica->file_pasaporte);
                    }
                    $nombre = 'PASAPORTE_'.Auth::user()->identificacion.'_'.\Carbon\Carbon::now().'.pdf';
                    $practica->file_pasaporte = $nombre;
                    Storage::disk('legalizacion')->put($nombre, $file = \File::get($file));
                }
            }
            
            if($request->file_visa != null)
            {
                $file = $request->file('file_visa');
                if($file->getError() > 0)
                {
                    return ['title'=>'Error', 'content'=>'Todos los archivos deben ser formato PDF y deben pesar máximo 1MB', 'type'=>'error'];
                }
                
                if($file->getMimeType() == 'application/pdf' && $file->getSize() <= 1048576)
                {
                    if(Storage::disk('legalizacion')->has($practica->file_visa))
                    {
                        Storage::disk('legalizacion')->delete($practica->file_visa);
                    }
                    $nombre = 'VISA_'.Auth::user()->identificacion.'_'.\Carbon\Carbon::now().'.pdf';
                    $practica->file_visa = $nombre;
                    Storage::disk('legalizacion')->put($nombre, $file = \File::get($file));
                }
            }
            
            if($request->file_cedula != null)
            {
                $file = $request->file('file_cedula');
                if($file->getError() > 0)
                {
                    return ['title'=>'Error', 'content'=>'Todos los archivos deben ser formato PDF y deben pesar máximo 1MB', 'type'=>'error'];
                }
                
                if($file->getMimeType() == 'application/pdf' && $file->getSize() <= 1048576)
                {
                    if(Storage::disk('legalizacion')->has($practica->file_cedula))
                    {
                        Storage::disk('legalizacion')->delete($practica->file_cedula);
                    }
                    $nombre = 'CEDULA_'.Auth::user()->identificacion.'_'.\Carbon\Carbon::now().'.pdf';
                    $practica->file_cedula = $nombre;
                    Storage::disk('legalizacion')->put($nombre, $file = \File::get($file));
                }
            }
            
            if($request->file_carnet != null)
            {
                $file = $request->file('file_carnet');
                if($file->getError() > 0)
                {
                    return ['title'=>'Error', 'content'=>'Todos los archivos deben ser formato PDF y deben pesar máximo 1MB', 'type'=>'error'];
                }
                
                if($file->getMimeType() == 'application/pdf' && $file->getSize() <= 1048576)
                {
                    if(Storage::disk('legalizacion')->has($practica->file_carnet))
                    {
                        Storage::disk('legalizacion')->delete($practica->file_carnet);
                    }
                    $nombre = 'CARNET_'.Auth::user()->identificacion.'_'.\Carbon\Carbon::now().'.pdf';
                    $practica->file_carnet = $nombre;
                    Storage::disk('legalizacion')->put($nombre, $file = \File::get($file));
                }
            }
            
            if($request->file_padres != null)
            {
                $file = $request->file('file_padres');
                if($file->getError() > 0)
                {
                    return ['title'=>'Error', 'content'=>'Todos los archivos deben ser formato PDF y deben pesar máximo 1MB', 'type'=>'error'];
                }
                
                if($file->getMimeType() == 'application/pdf' && $file->getSize() <= 1048576)
                {
                    if(Storage::disk('legalizacion')->has($practica->file_padres))
                    {
                        Storage::disk('legalizacion')->delete($practica->file_padres);
                    }
                    $nombre = 'PADRES_'.Auth::user()->identificacion.'_'.\Carbon\Carbon::now().'.pdf';
                    $practica->file_padres = $nombre;
                    Storage::disk('legalizacion')->put($nombre, $file = \File::get($file));
                }
            }
            
            if($request->file_estudiante != null)
            {
                $file = $request->file('file_estudiante');
                if($file->getError() > 0)
                {
                    return ['title'=>'Error', 'content'=>'Todos los archivos deben ser formato PDF y deben pesar máximo 1MB', 'type'=>'error'];
                }
                
                if($file->getMimeType() == 'application/pdf' && $file->getSize() <= 1048576)
                {
                    if(Storage::disk('legalizacion')->has($practica->file_estudiante))
                    {
                        Storage::disk('legalizacion')->delete($practica->file_estudiante);
                    }
                    $nombre = 'ESTUDIANTE_'.Auth::user()->identificacion.'_'.\Carbon\Carbon::now().'.pdf';
                    $practica->file_estudiante = $nombre;
                    Storage::disk('legalizacion')->put($nombre, $file = \File::get($file));
                }
            }
            
            if($request->file_itinerario != null)
            {
                $file = $request->file('file_itinerario');
                if($file->getError() > 0)
                {
                    return ['title'=>'Error', 'content'=>'Todos los archivos deben ser formato PDF y deben pesar máximo 1MB', 'type'=>'error'];
                }
                
                if($file->getMimeType() == 'application/pdf' && $file->getSize() <= 1048576)
                {
                    if(Storage::disk('legalizacion')->has($practica->file_itinerario))
                    {
                        Storage::disk('legalizacion')->delete($practica->file_itinerario);
                    }
                    $nombre = 'ITINERARIO_'.Auth::user()->identificacion.'_'.\Carbon\Carbon::now().'.pdf';
                    $practica->file_itinerario = $nombre;
                    Storage::disk('legalizacion')->put($nombre, $file = \File::get($file));
                }
            }
            
            if($request->file_seguro != null)
            {
                $file = $request->file('file_seguro');
                if($file->getError() > 0)
                {
                    return ['title'=>'Error', 'content'=>'Todos los archivos deben ser formato PDF y deben pesar máximo 1MB', 'type'=>'error'];
                }
                
                if($file->getMimeType() == 'application/pdf' && $file->getSize() <= 1048576)
                {
                    if(Storage::disk('legalizacion')->has($practica->file_seguro))
                    {
                        Storage::disk('legalizacion')->delete($practica->file_seguro);
                    }
                    $nombre = 'SEGURO_'.Auth::user()->identificacion.'_'.\Carbon\Carbon::now().'.pdf';
                    $practica->file_seguro = $nombre;
                    Storage::disk('legalizacion')->put($nombre, $file = \File::get($file));
                }
            }
            
            $texto = 'El estudiante '.$practica->getestudiante->getpersona->nombres.
                     ' '.$practica->getestudiante->getpersona->apellidos.' identificado con código '.$practica->getestudiante->codigo.
                     ',  ha escogido realizar '.$modalidad->nombre.' como modalidad de prácticas profesionales, por favor revise la documentación del estudiante';
            
            $ori = User::where('idRol', Rol::where('nombre', 'Ori')->first()->id)->first();
            
            Mail::raw($texto, function ($message) use ($ori) {
                $message->from('hello@app.com', env('MAIL_FROM'));
    
                $message->to($ori->getuser->correo, $ori->getuser->nombres.' '.$ori->getuser->apellidos)->subject('Prácticas extranjeras');
                
            });
        }
        $practica->estado = EstadoPractica::where('nombre','Esperando respuesta')->first()->id;
        $practica->aprobacion_estudiante = true;
        $practica->fecha_aprobacion_estudiante = \Carbon\Carbon::now();
        $practica->save();
        return ['title'=>'Éxito', 'content'=>'Documentos enviados con éxito', 'type'=>'success'];
    }
    
    public function getConferencias()
    {
        $codigo = Auth::user()->identificacion;
        $estudiante = Estudiante::where('codigo', $codigo)->first();
        
        return view('estudiante.conferencias', compact('estudiante'));
    }
    
    public function conferenciasNecesarias()
    {
        $codigo = Auth::user()->identificacion;
        $estudiante = Estudiante::where('codigo', $codigo)->first();
        
        $conferencias = Conferencia::select('id')
                                   ->whereHas('getprogramas', function($q) use ($estudiante)
                                   {
                                       $q->where('dependencias.id', '=', $estudiante->idPrograma);
                                   })
                                   ->get()->toArray();
        
        
        return $conferencias;
    }
    // $codigo = Auth::user()->identificacion;
    //     $estudiante = Estudiante::where('codigo', $codigo)->first();
        
    //     $conferencias = Conferencia::select('conferencia.id', 'nombre', 'idPrograma')
    //                               ->join('conferenciaprograma as cp', 'cp.idConferencia', '=', 'conferencia.id')
    //                               ->get();
        
    //     return $conferencias;
    
    public function getConferenciasjson()
    {
        $nombre_periodo = $this->nombrePeriodo();
       
        
        $periodo = Periodo::where('nombre', $nombre_periodo)
                          ->first();
                          
        $id_conferencias = $this->conferenciasMatriculadas();
        $id_conf  = $this->conferenciasNecesarias();
        $conferencias = ConferenciaPeriodo::with('getconferencia')
                                          ->with('getorador')
                                          ->with('getperiodo')
                                          ->with('getasistencias')
                                          ->where('idPeriodo', $periodo->id)
                                          ->whereHas('getconferencia', function ($query) use ($id_conferencias, $id_conf) {
                                              $query->whereNotIn('id', $id_conferencias)
                                                    ->whereIn('id', $id_conf);
                                                    
                                          })
                                          ->orderBy('idConferencia')
                                          ->get();
        return $conferencias;
    }
    
    public function getFaltantejson()
    {
        $codigo = Auth::user()->identificacion;
        $estudiante = Estudiante::where('codigo', $codigo)->first();
        
        $nombre_periodo = $this->nombrePeriodo();
       
        
        $periodo = Periodo::where('nombre', $nombre_periodo)
                          ->first();
                          
        $id_conferencias = $this->conferenciasMatriculadas();
        $id_conf  = $this->conferenciasNecesarias();
        $conferencias = ConferenciaPeriodo::selectRaw('distinct idConferencia')
                                          ->where('idPeriodo', $periodo->id)
                                          ->whereHas('getconferencia', function ($query) use ($id_conferencias, $id_conf) {
                                              $query->whereNotIn('id', $id_conferencias)
                                                    ->whereIn('id', $id_conf);
                                          })
                                          ->orderBy('idConferencia')
                                          ->get();
        $cantidad = sizeof($conferencias);
        
        return $cantidad;
    }
    
    public function conferenciasMatriculadas()
    {
        $codigo = Auth::user()->identificacion;
        $estudiante = Estudiante::where('codigo', $codigo)->first();
        
        $nombre_periodo = $this->nombrePeriodo();
       
        
        $periodo = Periodo::where('nombre', $nombre_periodo)
                          ->first();
        
        $asistencias = $estudiante->getasistencias;
        
        $id_conferencias = [];
        
        $con=0;
        
        foreach ($asistencias as $a) 
        {
            if($a->gethorario->getperiodo->nombre == $nombre_periodo)
            {
                $id_conferencias[$con] = $a->gethorario->getconferencia->id;
                $con++;
            }
            
        }
        
        return $id_conferencias;
    }
    
    public function conferenciasMatriculadasHorario()
    {
        $codigo = Auth::user()->identificacion;
        $estudiante = Estudiante::where('codigo', $codigo)->first();
        
        $nombre_periodo = $this->nombrePeriodo();
       
        
        $periodo = Periodo::where('nombre', $nombre_periodo)
                          ->first();
        
        $asistencias = $estudiante->getasistencias;
        
        $id_conferencias = [];
        
        $con=0;
        
        foreach ($asistencias as $a) 
        {
            if($a->gethorario->getperiodo->nombre == $nombre_periodo)
            {
                $id_conferencias[$con] = $a->gethorario->id;
                $con++;
            }
            
        }
        
        return $id_conferencias;
    }
    
    public function getAddconferencia($id)
    {
        $codigo = Auth::user()->identificacion;
        $estudiante = Estudiante::where('codigo', $codigo)->first();
        
        $id_conferencias = $this->conferenciasMatriculadas();
        $horario = ConferenciaPeriodo::where('id',$id)
                                     ->whereIn('id', $id_conferencias)
                                     ->first();
        
        if(sizeof($horario)==0)
        {
            $horario = ConferenciaPeriodo::find($id);
            if($horario->cupo > sizeof($horario->getasistencias))
            {
                Asistencia::create(['idConferenciaPeriodo'=>$id, 'idEstudiante'=>$estudiante->id]);
                
                $data = [
                    'title'=>'Éxito',
                    'content'=>'Horario matriculado con éxito',
                    'type'=>'success',
                ];
            }
            else
            {
                $data = [
                    'title'=>'Sin cupos',
                    'content'=>'Horario lleno',
                    'type'=>'info',
                ];
            }
        }
        else
        {
            $data = [
                    'title'=>'Error',
                    'content'=>'No puede matricular este horario',
                    'type'=>'error',
                ];
        }
        return $data;
    }
    
    public function nombrePeriodo()
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
    
    public function getHorario()
    {
        $codigo = Auth::user()->identificacion;
        $estudiante = Estudiante::where('codigo', $codigo)->first();
        
        return view('estudiante.horario', compact('estudiante'));
    }
    
    public function getAsistenciasjson()
    {
        $nombre_periodo = $this->nombrePeriodo();
       
        
        $periodo = Periodo::where('nombre', $nombre_periodo)
                          ->first();
                          
        $id_conferencias = $this->conferenciasMatriculadasHorario();
        
        // dd($id_conferencias);
        
        $conferencias = ConferenciaPeriodo::with('getconferencia')
                                          ->with('getorador')
                                          ->with('getperiodo')
                                          ->with('getasistencias')
                                          ->where('idPeriodo', $periodo->id)
                                          ->whereIn('id', $id_conferencias)
                                          ->orderBy('idConferencia')
                                          ->get();
        return $conferencias;                                              
    }
    
    public function postCalificarconferencia(CalificacionRequest $request)
    {
        $asistencia = Asistencia::find($request->idAsistencia);
        if($asistencia->asistio)
        {
            $asistencia->valoracion = $request->valor;
            $asistencia->save();
            
            $asistencias = Asistencia::where('idConferenciaPeriodo', $asistencia->idConferenciaPeriodo)
                                     ->select('valoracion')
                                     ->get();
            $suma = 0;
            $contador = 0;
            foreach($asistencias as $a)
            {
                if($a->valoracion != null)
                {
                    $suma+=$a->valoracion;
                    $contador++;
                }
            }
            
            $conferenciaPeriodo = ConferenciaPeriodo::find($asistencia->idConferenciaPeriodo);
            $conferenciaPeriodo->valoracion = $suma / $contador;
            $conferenciaPeriodo->save();
            
            $conferenciasPeriodo = ConferenciaPeriodo::where('idConferencia', $conferenciaPeriodo->idConferencia)
                                                     ->select('valoracion')
                                                     ->get();
            $suma = 0;
            $contador = 0;
            foreach($conferenciasPeriodo as $item)
            {
                if($item->valoracion != null)
                {
                    $suma+=$item->valoracion;
                    $contador++;
                }
            }
            
            $conferencia = Conferencia::find($conferenciaPeriodo->idConferencia);
            $conferencia->valoracion = $suma / $contador;
            $conferencia->save();
            
            return[
                'title'=>'Éxito',
                'content'=>'Conferencia calificada con éxito',
                'type'=>'success',
            ];
        }
        else
        {
            return[
                'title'=>'Error',
                'content'=>'Usted no puede calificar una conferencia a la que no asistio',
                'type'=>'info',
            ];
        }
        
            
    }
    
    public function postSolicitarcarta(CartaRequest $request)
    {
        // dd($request->all());
        $codigo = Auth::user()->identificacion;
        $estudiante = Estudiante::where('codigo', $codigo)->first();
        
        $carta = new Carta();
        $carta->idEmpresa = $request->empresa['id'];
        $carta->ciudadExpedicion = $request->ciudadExpedicion;
        $carta->idEstudiante = $estudiante->id;
        $carta->estado = EstadoCarta::where('nombre', 'Esperando respuesta')->first()->id;
        $carta->save();
        
        return[
            'title'=>'Éxito',
            'content'=>'Solicitud de carta de presentación enviada con éxito',
            'type'=>'success',
        ];
        
        
    }
    
    public function getCiudadesjson()
    {
        $colombia = Pais::where('nombre', 'COLOMBIA')->first();
        
        return Municipio::with('getdepartamento.getpais')
                        // ->whereHas('getdepartamento', function($q) use ($colombia){
                        //     $q->where('idPais', '!=', $colombia->id);
                        // })
                        ->get();
    }
    
    public function getEmpresasjson()
    {
        return Empresa::select('nombre', 'id')
                      ->where('estadoDipro', EstadoEmpresas::where('nombre', 'ACEPTADA')->first()->id)
                      ->get();
    }
    
    public function getCartas()
    {
        $codigo = Auth::user()->identificacion;
        
        $estudiante = Estudiante::where('codigo', $codigo)->first();
        
        return view('estudiante.cartas', compact('estudiante'));
    }
}
