<?php

namespace App\Http\Controllers;

use Auth;
use Mail;
use Storage;

use App\Models\Contrato;
use App\Models\Convenio;
use App\Models\Departamento;
use App\Models\Dependencia;
use App\Models\Empresa;
use App\Models\EstadoConvenio;
use App\Models\EstadoEmpresas;
use App\Models\EstadoOferta;
use App\Models\EstadoPostulado;
use App\Models\EstadoPostuladoEst;
use App\Models\Estudiante;
use App\Models\Hojavida;
use App\Models\Municipio;
use App\Models\Oferta;
use App\Models\OfertaPrograma;
use App\Models\Pais;
use App\Models\Persona;
use App\Models\Postulado;
use App\Models\Referencia;
use App\Models\Salario;
use App\Models\Sede;
use App\Models\Rol;
use App\Models\Tipocorreo;
use App\Models\TipoEstudiante;
use App\Models\TExperiencia;
use App\Models\Tipooferta;
use App\Models\User;

use App\Http\Requests\DocumentosRequest;
use App\Http\Requests\JefeRequest;
use App\Http\Requests\OfertaRequest;
use Illuminate\Http\Request;

use Carbon\Carbon;

class EmpresaController extends Controller 
{
    
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('empresa');
        $this->middleware("cambiocontrasena");
        $this->middleware('empresadippro', ['only'=>['getUsuarios', 'getConvenio']]);
        $this->middleware('empresa_postulados', ['only'=>['getVerhojadevida']]);
        $this->middleware('Hojaprivada', ['only'=>['getVerperfil', 'getHoja2json']]);
        $this->middleware('crearoferta', ['only'=>['postSaveoferta', 'getCrearoferta']]);
    }

    public function getIndex()
    {
        $ambas = false;
        $dipro = false;
        $sil = false;
        if(Auth::user()->getsede->getempresa->getestadodipro->nombre == 'ACEPTADA' && Auth::user()->getsede->getempresa->getestadosil->nombre == 'ACEPTADA')
        {
            $ambas = true;
        }
        else if(Auth::user()->getsede->getempresa->getestadodipro->nombre == 'ACEPTADA' )
        {
            $dipro = true;
        }
        else if(Auth::user()->getsede->getempresa->getestadosil->nombre == 'ACEPTADA' )
        {
            $sil = true;
        }
        
        return view('empresa.index', compact('ambas', 'dipro', 'sil'));
        
    }
    
    public function getUsuarios()
    {
        return view('empresa.usuarios');
    }
    
    public function getUsuariosbyempresajson()
    {
        $id = Auth::user()->getsede->getempresa->id;
        
        $usuarios =  User::usuariosbyempresa($id);
        
        return $usuarios;
    }
    
    public function getUsuariojson($id)
    {
        $usuario = User::getUsuario($id);
                       
        return json_encode($usuario);
    }
    
    public function postSaveusuario(JefeRequest $request)
    {
        if(isset($request->id))
        {
            $usuario = User::find($request->id);
            $usuario->area = $request->area;
            $usuario->cargo = $request->cargo;
            $usuario->identificacion = $request->identificacion;
            $usuario->save();
            
            $persona = Persona::find($usuario->idPersona);
            $persona->nombres = $request->nombres;
            $persona->apellidos = $request->apellidos;
            $persona->identificacion = $request->identificacion;
            $persona->correo = $request->correo;
            $persona->celular = $request->celular;
            $persona->save();
            
            return ['title'=>'Registro exitoso', 'content'=>'Datos guardados correctamente', 'type'=>'success'];
        }
        else
        {
            $persona = new Persona();
            $persona->nombres = $request->nombres;
            $persona->apellidos = $request->apellidos;
            $persona->identificacion = $request->identificacion;
            $persona->correo = $request->correo;
            $persona->celular = $request->celular;
            $persona->save();
            
            $usuario = new User();
            $usuario->idPersona = $persona->id;
            $usuario->idRol = Rol::where('nombre', 'Jefe inmediato')->first()->id;
            $usuario->idSede = Auth::user()->idSede;
            $usuario->area = $request->area;
            $usuario->cargo = $request->cargo;
            $usuario->activo = true;
            $usuario->identificacion = $request->identificacion;
            $usuario->password = \Hash::make($request->identificacion);
            $usuario->save();
            
            return ['title'=>'Registro exitoso', 'content'=>'Datos guardados correctamente', 'type'=>'success'];
        }
    }
    
    public function getOfertas()
    {
        $ambas = false;
        $dipro = false;
        $sil = false;
        if(Auth::user()->getsede->getempresa->getestadodipro->nombre == 'ACEPTADA' && Auth::user()->getsede->getempresa->getestadosil->nombre == 'ACEPTADA')
        {
            $ambas = true;
        }
        else if(Auth::user()->getsede->getempresa->getestadodipro->nombre == 'ACEPTADA' )
        {
            $dipro = true;
        }
        else if(Auth::user()->getsede->getempresa->getestadosil->nombre == 'ACEPTADA' )
        {
            $sil = true;
        }
        
        return view('empresa.ofertas', compact('ambas', 'dipro', 'sil'));
    }
    
    public function getFormularioofertajson()
    {
        $idSede = Auth::user()->idSede;
        
        $data['jefes'] = User::getJefes($idSede);
        $data['tipooferta'] = Tipooferta::get();
        $data['programas'] = Dependencia::where('idTipo', 1)->whereNotNull('codigoPrograma')->get();
        $data['salarios'] = Salario::get();
        $data['contratos'] = Contrato::get();
        $data['experiencias'] = TExperiencia::get();
        $data['paises'] = Pais::with('departamentos.municipios')->where('nombre', 'Colombia')->get();
        
        return $data;
    }
    
    public function getCrearoferta($id = null)
    {
        if(Auth::user()->getsede->getempresa->getestadodipro->nombre == 'ACEPTADA' && Auth::user()->getsede->getempresa->getestadosil->nombre != 'ACEPTADA')
        {
            $soloDipro=1;
        }
        else 
        {
            $soloDipro=0;
        }
        
        if(Auth::user()->getsede->getempresa->getestadodipro->nombre != 'ACEPTADA' && Auth::user()->getsede->getempresa->getestadosil->nombre == 'ACEPTADA')
        {
            $soloSil=1;
        }
        else 
        {
            $soloSil=0;
        }
        return view('empresa.crearoferta', compact('id', 'soloDipro', 'soloSil'));
    }
    
    public function getOfertasjson()
    {
        $ofertas = Oferta::with('gettipo')
                         ->with('getpostulados')
                         ->with('getestado')
                         ->where('idSede', Auth::user()->idSede)
                         ->orderBy('fechaCierre', 'desc')
                         ->get();

        foreach($ofertas as $oferta)
        {
            if($oferta->gettipo->nombre=='Graduados')
            {
                $oferta->salario = $oferta->getsalario->rango;
            }

            // $hoy = new Carbon();
            // $fechaCierre = new Carbon($oferta->fechaCierre);
            // $fechaCierre->day++;
            // if($oferta->getestado->nombre == 'Publicada' && $hoy >= $fechaCierre) {
            //     $oferta->estado = EstadoOferta::where('nombre','Finalizada')->first()->id;
            //     $ofertas->save();
            // }

            // $oferta['getestado'] = $oferta->getestado;
            
            $postulados = $oferta->getpostulados;
            $oferta->seleccionado = false;
            foreach($postulados as $postulado)
            {
                if($postulado->getestadoempresa->nombre == 'Seleccionado')
                {
                    $oferta->seleccionado = true;
                    break;
                }
            }
            
            
        }                 
        
        return $ofertas;
    }
    
    public function getOfertajson($id)
    {
        $oferta = Oferta::with('getestado')
                        ->with('getmunicipio.getdepartamento.getpais')
                        ->with('getexperiencia')
                        ->where('id', $id)->first();
        // dd();
        $oferta->fecha_cierre = $oferta->fechaCierre;//Carbon::create(explode('-',$oferta->fechaCierre)[0], explode('-',$oferta->fechaCierre)[1], explode('-',$oferta->fechaCierre)[2])->toDateString();
        // dd($oferta->fechacierre);
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

    // public function getDepartamentopais($id) {
    //     $departamento = Departamento::find($id);
    //     $data['departamento'] = $departamento;
    //     $data['pais'] = $departamento->getpais;

    //     return $data;
    // }

    public function getDetallesofertajson($idOferta)
    {
        $te_graduado = TipoEstudiante::where('nombre', 'Graduado')->first()->id;
        $oferta = Oferta::with('gettipo')
                        ->with('getmunicipio.getdepartamento.getpais')
                        ->with('getsalario')
                        ->with('getcontrato')
                        ->with(['getpostulados.getpersona.getestudiantes' => function ($estudiante) use ($te_graduado) {
                            $estudiante->with('getprograma.getprograma')
                                        ->where('idTipo', $te_graduado);
                        }])
                        ->with('getprogramas')
                        ->with('getexperiencia')
                        ->with('getpostulados.getestadoempresa')
                        ->with('getpostulados.getestadoestudiante')
                        ->find($idOferta);
        
        foreach ($oferta->getpostulados as $p) 
        {
            $p->programas ='';
            foreach ($p->getpersona->getestudiantes as $e) {
                $p->programas = $p->programas.$e->getprograma->getprograma->nombre.'; ';
            }
            $p->programas = substr($p->programas, 0, -2);
        }

        return $oferta;
    }
    
    public function postSaveoferta(OfertaRequest $request)
    {
        // dd($request->all());
        if(Auth::user()->getsede->getempresa->getestadodipro->nombre == 'ACEPTADA' && Auth::user()->getsede->getempresa->getestadosil->nombre == 'ACEPTADA')
        {
            if($request->tipo['nombre']=='Graduados')
                $this->OfertaSil($request);
            
            else if($request->tipo['nombre']=='Practicantes')
                $this->OfertaDipro($request);
        }
        else if(Auth::user()->getsede->getempresa->getestadodipro->nombre == 'ACEPTADA')
        {
            $this->OfertaDipro($request);
        }
        else if(Auth::user()->getsede->getempresa->getestadosil->nombre == 'ACEPTADA')
        {
            $this->OfertaSil($request);
        }
        
        return ['title'=>'Registro exitoso', 'content'=>'Oferta guardada con exito', 'type'=>'success'];
    }
    
    public function CrearOEditar(OfertaRequest $request)
    {
        if($request->id == null)
        {
            $oferta = new Oferta();
            $oferta->idSede = Auth::user()->idSede;
        }
        else
        {
            $oferta = Oferta::find($request->id);
            if($oferta->getestado->nombre == 'Errada')
            {
                $oferta->estado = EstadoOferta::where('nombre', 'Por aprobar')->first()->id;
                $oferta->save();
            }
        }
        
        return $oferta;
    }
    
    public function OfertaSil(OfertaRequest $request)
    {
        $oferta = $this->CrearOEditar($request);
        //dd($request-> fecha_cierre);
        if(isset($oferta->getestado->nombre) && $oferta->getestado->nombre == 'Publicada')
        {
            $oferta->vacantes = $request->vacantes;
            $oferta->fechaCierre = $request->fecha_cierre;
            $oferta->save();
        }
        else 
        {
            $oferta->idTipo = Tipooferta::where('nombre','Graduados')->first()->id;
            $oferta->nombre = $request->nombre;
            $oferta->vacantes = $request->vacantes;
            $oferta->herramientasInformaticas = $request->informaticas;
            $oferta->idExperiencia = $request->getexperiencia['id'];
            $oferta->idSalario = $request->salario['id'];
            $oferta->idContrato = $request->contrato['id'];
            $oferta->perfil = $request->perfil;
            $oferta->funciones = $request->funciones;
            $oferta->observaciones = $request->observaciones;
            $oferta->creada_por_dipro = 0;
            $oferta->fechaCierre = $request->fecha_cierre;
            $oferta->idMunicipio = $request->getmunicipio['id'];
            if(isset($oferta->getestado->nombre) && $oferta->getestado->nombre == 'Finalizada')
            {
                $oferta->estado = EstadoOferta::where('nombre', 'Por aprobar')->first()->id;
            }
            $oferta->save();
            
            OfertaPrograma::where('idOferta', $request->id)->delete();
            foreach($request->programas as $programa)
            {
                OfertaPrograma::create(['idOferta'=>$oferta->id, 'idDependencia'=>$programa['id']]);
            }            
        }
    }
    
    public function OfertaDipro(OfertaRequest $request)
    {
        $oferta = $this->CrearOEditar($request);
        
        if(isset($oferta->getestado->nombre) && $oferta->getestado->nombre == 'Publicada')
        {
            $oferta->vacantes = $request->vacantes;
            $oferta->fechaCierre = $request->fecha_cierre;
            $oferta->save();
        }
        else
        {
            $oferta->idTipo = Tipooferta::where('nombre','Practicantes')->first()->id;
            $oferta->idJefe = $request->jefe['id'];
            $oferta->nombre = $request->nombre;
            $oferta->vacantes = $request->vacantes;
            if(isset($request->pordefinir) && $request->pordefinir)
            {
                $oferta->salario = 'Por definir';
            }
            else 
            {
                $oferta->salario = $request->salario;
            }
            $oferta->salud = $request->salud;
            $oferta->arl = $request->arl;
            $oferta->perfil = $request->perfil;
            $oferta->observaciones = $request->observaciones;
            $oferta->idMunicipio = $request->getmunicipio['id'];
            $oferta->funciones = $request->funciones;
            $oferta->fechaCierre = $request->fecha_cierre;
            if(isset($oferta->getestado->nombre) && $oferta->getestado->nombre == 'Finalizada')
            {
                $oferta->estado = EstadoOferta::where('nombre', 'Por aprobar')->first()->id;
            }
            if(!$oferta->arl)
            {
                $carta = $request->file('file');

                if($carta->getError() > 0)
                {
                    return ['title'=>'Error', 'content'=>'El certificado de salud debe ser un archivo PDF y debe pesar maximo 1MB', 'type'=>'error'];
                }
                
                if($carta->getMimeType() == 'application/pdf' && $carta->getSize() <= 1048576)
                {

                    if(Storage::disk('oferta')->has($oferta->carta))
                    {                        
                        Storage::disk('oferta')->delete($oferta->carta);
                    }
                    $caracteres = array("-", " ", ":");
                    $fecha = str_replace($caracteres, "", \Carbon\Carbon::now()->toDateTimeString());
                    $nombre = 'CARTA_'.Auth::user()->identificacion.'_'.$fecha.'.pdf';
                    $oferta->carta = $nombre;
                    //dd(Storage::disk('oferta'));
                    Storage::disk('oferta')->put($nombre, $carta = \File::get($carta));

                }

            }
            $oferta->save();
            
            OfertaPrograma::where('idOferta', $request->id)->delete();
            foreach($request->programas as $programa)
            {
                OfertaPrograma::create(['idOferta'=>$oferta->id, 'idDependencia'=>$programa['id']]);
            }
        }
        
            
    }

    public function getEliminaroferta($idOferta)
    {        
        $oferta = Oferta::find($idOferta);
        if($oferta->getestado->nombre != 'Por aprobar')
        {
            return ['title'=>'Error', 'content'=>'No se puede eliminar esta oferta', 'type'=>'error'];
        }
        if(Storage::disk('oferta')->has($oferta->carta))
        {                        
            Storage::disk('oferta')->delete($oferta->carta);
        }
        OfertaPrograma::where('idOferta', $oferta->id)->delete();
        $oferta->delete();
        return ['title'=>'Éxito', 'content'=>'Oferta eliminada con éxito', 'type'=>'success'];
    }
    
    public function getPostuladosjson($idOferta)
    {
        $postulados = Postulado::with('getpersona.getestudiantes.getprograma.getprograma')
                               ->with('getestadoempresa')
                               ->with('getestadoestudiante')                               
                               ->where('idOferta', $idOferta)
                               ->get();

        foreach ($postulados as $p) 
        {
            $p->programas ='';
            foreach ($p->getpersona->getestudiantes as $e) {
                $p->programas = $p->programas.$e->getprograma->getprograma->nombre.'; ';
            }
            $p->programas = substr($p->programas, 0, -2);
        }

        return $postulados;
    }
    
    public function getVerhojadevida($idEstudiante)
    {
        return view('empresa.hoja', compact('idEstudiante'));
    }
    
    public function getHojajson($idEstudiante)
    {
        $estudiante = Estudiante::with('getpersona.gethojadevida')
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
    
    public function getSeleccionarpostulado($id)
    {
        $postulado = Postulado::find($id);
        
        //Si las vacantes de la oferta estan llenas, no dejar postular mas
        $oferta = Oferta::find($postulado->idOferta);
        $seleccionados = Postulado::where('idOferta', $postulado->idOferta)->where('idEstatoEmpresa', EstadoPostulado::where('nombre', 'Elegido')->first()->id)->get();
        if($oferta->vacantes == sizeof($seleccionados))
        {
            return ['title'=>'Error', 'content'=>'Usted ya ha seleccionado el maximo de vacantes', 'type'=>'error'];
        }
        
        // Seleccionar postulado
        $postulado->idEstatoEmpresa = EstadoPostulado::where('nombre', 'Seleccionado')->first()->id;
        $postulado->save();
        
        $oferta = Oferta::find($postulado->idOferta);
        $persona = Persona::find($postulado->idPersona);
        if($oferta->gettipo->nombre == 'Graduados')
        {
            $tipo='laboral';
            $continuacion = 'Usted deberá ingresar al Sistema de Intermediación Laboral para aceptar seguir en el proceso';
        }
        else
        {
            $tipo='de prácticas';
            $continuacion = 'Usted deberá ingresar a la plataforma de dirección de prácticas profesionales para aceptar y registrar sus prácticas';
        }
        
        $texto = 'Sr. '.$persona->nombres.' '.$persona->apellidos.'
                <br><br>
                Usted ha sido seleccionado para la siguiente fase de la convocatoria '.$tipo.':
                <br>
                Cargo: <strong>'.$oferta->nombre.'</strong>
                <br>
                Empresa: <strong>'.$oferta->getsede->getempresa->nombre.'</strong>
                <br><br>
                '.$continuacion;
        
        Mail::send('emails.correomasivo', ['dependencia' => 'Centro de Egresados', 'contenido'=>$texto], function ($m) use ($persona) {
            $m->from('sil2@unimagdalena.edu.co', env('MAIL_FROM'));
            
            $m->bcc($persona->correo, $persona->nombres.' '.$persona->apellidos)->subject('Usted ha sido seleccionado para una oferta laboral');
        });
        
        return ['title'=>'Exito', 'content'=>'Postulado seleccionado con éxito. Se ha notificado por correo electrónico al seleccionado', 'type'=>'success'];
    }

    public function postAceptarPostulado(Request $request) {
        $postulado = Postulado::find($request->id);
        $postulado->idEstatoEmpresa = EstadoPostulado::where('nombre','Elegido')->first()->id;
        $postulado->save();

        $hv = Hojavida::find($postulado->getpersona->gethojadevida[0]->id);
        $hv->laborando = 1;
        $hv->save();

        //Si el postulado ya estaba postulado en otra oferta de la misma empresa, establecer estas efertas como "no seleccionado"
        $empresa = $postulado->getoferta->getsede->getempresa->id;
        $ofertasEmpresa = Oferta::whereHas('getsede.getempresa', function($query) use($empresa) {
                                        $query->where('id', $empresa);
                                    })
                                    ->select('id')
                                    ->get();
        $postuladosEmpresa = Postulado::where('id','!=',$postulado->id)
                                        ->where('idPersona', $postulado->getpersona->id)
                                        ->whereIn('idOferta', $ofertasEmpresa)
                                        ->get();
        
        foreach ($postuladosEmpresa as $key => $value) {
            $value->idEstatoEmpresa = EstadoPostulado::where('nombre','No seleccionado')->first()->id;
            $value->save();
        }

        //Si los elegidos ya alcanzaron el numero maximo de vacantes de la oferta, establecer el resto de postulados para la oferta en estado "no seleccionado"
        $oferta = Oferta::find($request->idOferta);
        $seleccionados = Postulado::where('idOferta', $postulado->idOferta)->where('idEstatoEmpresa', EstadoPostulado::where('nombre', 'Elegido')->first()->id)->get();
        if($oferta->vacantes == $seleccionados->count())
        {
            $postulados = Postulado::where('idOferta', $postulado->idOferta)
                            ->where('idEstatoEmpresa','!=', EstadoPostulado::where('nombre', 'Elegido')->first()->id)
                            ->get();
            foreach($postulados as $p)
            {
                $p->idEstatoEmpresa = EstadoPostulado::where('nombre', 'No seleccionado')->first()->id;
                $p->save();
            }
        }

        //notificar por correo
        $persona = Persona::find($postulado->idPersona);
        $oferta = Oferta::find($postulado->idOferta);
        if($oferta->gettipo->nombre == 'Graduados')
        {
            $tipo='laboral';
        }
        else
        {
            $tipo='de prácticas';
        }
        
        $texto = 'Sr. '.$persona->nombres.' '.$persona->apellidos.'
                    <br><br>
                    Felicidades! ha sido elegido en la siguiente oferta '.$tipo.':
                    <br>'.
                    'Cargo: <strong>'.$oferta->nombre.'</strong>
                    <br>
                    Empresa: <strong>'.$oferta->getsede->getempresa->nombre.'</strong>';
        
        Mail::send('emails.correomasivo', ['dependencia' => 'Centro de Egresados', 'contenido'=>$texto], function ($m) use ($persona) {
            $m->from('sil2@unimagdalena.edu.co', env('MAIL_FROM'));
            
            $m->bcc($persona->correo, $persona->nombres.' '.$persona->apellidos)->subject('Usted ha sido seleccionado para una oferta laboral');
        });

        return ['title'=>'Exito', 'content'=>'Postulado elegido con éxito. Se ha notificado por correo electrónico a la persona', 'type'=>'success'];
    }
    
    // public function getDesseleccionarpostulado($id)
    // {
    //     $postulado = Postulado::find($id);
        
    //     $postulados = Postulado::where('idOferta', $postulado->idOferta)->get();
        
    //     foreach($postulados as $p)
    //     {
    //         $p->idEstatoEmpresa = EstadoPostulado::where('nombre', 'Postulado')->first()->id;
    //         $p->save();
    //     }
        
    //     return ['title'=>'Exito', 'content'=>'Seleccionado eliminado con éxito', 'type'=>'success'];
        
    // }
    
    public function getNotificarseleccionado($idOferta)
    {
        $oferta = Oferta::find($idOferta);
        
        
        $postulado = Postulado::where('idOferta', $idOferta)->where('idEstatoEmpresa', EstadoPostulado::where('nombre', 'Seleccionado')->first()->id)->first();
        
        $estudiante = Estudiante::find($postulado->idEstudiante);
        if($oferta->gettipo->nombre == 'Egresados')
        {
            $tipo='laboral';
            $continuacion = 'Usted deberá ingresar al sistema de intermediación laboral para aceptar la oferta';
        }
        else
        {
            $tipo='de prácticas';
            $continuacion = 'Usted deberá ingresar a la plataforma de dirección de prácticas profesionales para aceptar y registrar sus prácticas';
        }
        
        $texto = 'Sr. '.$estudiante->getpersona->nombres.' '.$estudiante->getpersona->apellidos."\n\n".
                'Usted ha sido seleccionado para la siguiente convocatoria '.$tipo.':'."\n".
                'Cargo: '.$oferta->nombre."\n".
                'Empresa: '.$oferta->getsede->getempresa->nombre."\n\n".
                $continuacion;
        
        
        Mail::raw($texto, function ($message) use ($estudiante) {
            $message->from('hello@app.com', env('MAIL_FROM'));

            $message->to($estudiante->getpersona->correo, $estudiante->getpersona->nombres.' '.$estudiante->getpersona->apellidos)->subject('Usted ha sido seleccionado');
            
        });
        
        
        return ['title'=>'Notificación exitosa', 'content'=>'La notificación se realizó con éxito', 'type'=>'success'];
        
    }
    
    public function getConvenio()
    {
        return view('empresa.convenio.index');
    }
    
    public function getUltimoconvenio()
    {
        $empresa = Auth::user()->getsede->getempresa;
        
        $convenio = [];
        if(sizeof($empresa->getconvenios) > 0)
        {
            $convenio = Convenio::with('getestado')
                                ->with('getactasrenovacion')
                                ->where('id', $empresa->getconvenios[ sizeof($empresa->getconvenios) - 1 ]->id)
                                ->first();
        }
        return $convenio;
    }
    
    public function getConveniosjson($id=null)
    {
        $empresa = Auth::user()->getsede->getempresa;
        
        if($id==null)
        {
            $convenios = Convenio::with('getestado')
                                ->with('getactasrenovacion')
                                ->where('idEmpresa', $empresa->id)
                                ->get();
            foreach($convenios as $convenio)
            {
                if(($convenio->cedula_representante != null && $convenio->procuraduria != null && $convenio->contraloria != null && $convenio->rut != null) && ($convenio->certificado_existencia != null || $convenio->acta_posesion != null || $convenio->acto_administrativo != null || $convenio->certificado_militar != null))
                {
                    $convenio->mostrar = true;
                }
            }
        }
        else
        {
            $convenios = Convenio::with('getestado')
                                ->with('getactasrenovacion')
                                ->where('idEmpresa', $empresa->id)
                                ->where('id', $id)
                                ->first();
        }
        
            
        
        return $convenios;
    }
    
    public function getEnviaradipro($id)
    {
        $cambiarEstado = false;
        
        $convenio = Convenio::find($id);
        
        if(($convenio->cedula_representante != null && $convenio->procuraduria != null && $convenio->contraloria != null && $convenio->rut != null) && ($convenio->certificado_existencia != null || $convenio->acta_posesion != null || $convenio->acto_administrativo != null || $convenio->certificado_militar != null))
        {
            $cambiarEstado = true;
        }
        
        if($cambiarEstado && $convenio->getestado->nombre == 'Aprobado')
        {
            $convenio->estado = EstadoConvenio::where('nombre', 'En revisión por Dippro')->first()->id;
            $convenio->save();
            return ['title'=>'Éxito', 'content'=>'Se ha enviado a Dippro exitosamente.', 'type'=>'success'];
        }
        
        return ['title'=>'Error', 'content'=>'Aun le faltan documentos por subir o ya se ha enviado con anterioridad.', 'type'=>'error'];
    }
    
    public function getSolicitarconvenio()
    {
        $ultimoConvenio = $this->getUltimoconvenio();
        
        if($ultimoConvenio != null && $ultimoConvenio->getestado->nombre != 'No aprobado')
        {
            return ['title'=>'Error', 'content'=>'Su colicitud de convenio no se pudo realizar porque ya tiene uno en proceso o aprobado', 'type'=>'error'];
        }
        
        $empresa = Auth::user()->getsede->getempresa;
        
        
        $convenio = new Convenio();
        $convenio->idEmpresa = $empresa->id;
        
        $convenio->estado = EstadoConvenio::where('nombre', 'Esperando aprobación')->first()->id;
        
        $convenio->save();
        
        return ['title'=>'Éxito', 'content'=>'Su colicitud de convenio se realizó con éxito. Se le estará enviando un correo que incluye las instrucciones para seguir con el proceso', 'type'=>'success'];
    }
    
    public function getSubirdocs($id)
    {
        return view('empresa.convenio.subirdocs', compact('id'));
    }
    
    public function postSubirdocs(DocumentosRequest $request)
    {
        $convenio = Convenio::find($request->id);
        
        if($convenio->getestado->nombre == "Aprobado")
        {
            if($request->file_existencia != null)
            {
                $existencia = $request->file('file_existencia');
                if($existencia->getError() > 0)
                {
                    return ['title'=>'Error', 'content'=>'Todos los documentos deben ser formato PDF y pesar máximo 1MB', 'type'=>'error'];
                }
                
                if($existencia->getMimeType() == 'application/pdf' && $existencia->getSize() <= 1048576)
                {
                    if(Storage::disk('convenios')->has($convenio->certificado_existencia))
                    {
                        Storage::disk('convenios')->delete($convenio->certificado_existencia);
                    }
                    $nombre = 'EXISTENCIA_'.Auth::user()->identificacion.'_'.\Carbon\Carbon::now().'.pdf';
                    $convenio->certificado_existencia = $nombre;
                    Storage::disk('convenios')->put($nombre, $existencia = \File::get($existencia));
                }
            }
            
            if($request->file_cedula != null)
            {
                $file = $request->file('file_cedula');
                if($file->getError() > 0)
                {
                    return ['title'=>'Error', 'content'=>'Todos los documentos deben ser formato PDF y pesar máximo 1MB', 'type'=>'error'];
                }
                
                if($file->getMimeType() == 'application/pdf' && $file->getSize() <= 1048576)
                {
                    if(Storage::disk('convenios')->has($convenio->cedula_representante))
                    {
                        Storage::disk('convenios')->delete($convenio->cedula_representante);
                    }
                    $nombre = 'CEDULA_'.Auth::user()->identificacion.'_'.\Carbon\Carbon::now().'.pdf';
                    $convenio->cedula_representante = $nombre;
                    Storage::disk('convenios')->put($nombre, $file = \File::get($file));
                }
            }
            
            if($request->file_procuraduria != null)
            {
                $file = $request->file('file_procuraduria');
                if($file->getError() > 0)
                {
                    return ['title'=>'Error', 'content'=>'Todos los documentos deben ser formato PDF y pesar máximo 1MB', 'type'=>'error'];
                }
                
                if($file->getMimeType() == 'application/pdf' && $file->getSize() <= 1048576)
                {
                    if(Storage::disk('convenios')->has($convenio->procuraduria))
                    {
                        Storage::disk('convenios')->delete($convenio->procuraduria);
                    }
                    $nombre = 'PROCURADURIA_'.Auth::user()->identificacion.'_'.\Carbon\Carbon::now().'.pdf';
                    $convenio->procuraduria = $nombre;
                    Storage::disk('convenios')->put($nombre, $file = \File::get($file));
                }
            }
            
            if($request->file_contraloria != null)
            {
                $file = $request->file('file_contraloria');
                if($file->getError() > 0)
                {
                    return ['title'=>'Error', 'content'=>'Todos los documentos deben ser formato PDF y pesar máximo 1MB', 'type'=>'error'];
                }
                
                if($file->getMimeType() == 'application/pdf' && $file->getSize() <= 1048576)
                {
                    if(Storage::disk('convenios')->has($convenio->contraloria))
                    {
                        Storage::disk('convenios')->delete($convenio->contraloria);
                    }
                    $nombre = 'CONTRALORIA_'.Auth::user()->identificacion.'_'.\Carbon\Carbon::now().'.pdf';
                    $convenio->contraloria = $nombre;
                    Storage::disk('convenios')->put($nombre, $file = \File::get($file));
                }
            }
            
            if($request->file_rut != null)
            {
                $file = $request->file('file_rut');
                if($file->getError() > 0)
                {
                    return ['title'=>'Error', 'content'=>'Todos los documentos deben ser formato PDF y pesar máximo 1MB', 'type'=>'error'];
                }
                
                if($file->getMimeType() == 'application/pdf' && $file->getSize() <= 1048576)
                {
                    if(Storage::disk('convenios')->has($convenio->rut))
                    {
                        Storage::disk('convenios')->delete($convenio->rut);
                    }
                    $nombre = 'RUT_'.Auth::user()->identificacion.'_'.\Carbon\Carbon::now().'.pdf';
                    $convenio->rut = $nombre;
                    Storage::disk('convenios')->put($nombre, $file = \File::get($file));
                }
            }
            
            if($request->file_posesion != null)
            {
                $file = $request->file('file_posesion');
                if($file->getError() > 0)
                {
                    return ['title'=>'Error', 'content'=>'Todos los documentos deben ser formato PDF y pesar máximo 1MB', 'type'=>'error'];
                }
                
                if($file->getMimeType() == 'application/pdf' && $file->getSize() <= 1048576)
                {
                    if(Storage::disk('convenios')->has($convenio->acta_posesion))
                    {
                        Storage::disk('convenios')->delete($convenio->acta_posesion);
                    }
                    $nombre = 'POSESION_'.Auth::user()->identificacion.'_'.\Carbon\Carbon::now().'.pdf';
                    $convenio->acta_posesion = $nombre;
                    Storage::disk('convenios')->put($nombre, $file = \File::get($file));
                }
            }
            
            if($request->file_acto_administrativo != null)
            {
                $file = $request->file('file_acto_administrativo');
                if($file->getError() > 0)
                {
                    return ['title'=>'Error', 'content'=>'Todos los documentos deben ser formato PDF y pesar máximo 1MB', 'type'=>'error'];
                }
                
                if($file->getMimeType() == 'application/pdf' && $file->getSize() <= 1048576)
                {
                    if(Storage::disk('convenios')->has($convenio->acto_administrativo))
                    {
                        Storage::disk('convenios')->delete($convenio->acto_administrativo);
                    }
                    $nombre = 'ADMINISTRATIVO_'.Auth::user()->identificacion.'_'.\Carbon\Carbon::now().'.pdf';
                    $convenio->acto_administrativo = $nombre;
                    Storage::disk('convenios')->put($nombre, $file = \File::get($file));
                }
            }
            
            if($request->file_militar != null)
            {
                $file = $request->file('file_militar');
                if($file->getError() > 0)
                {
                    return ['title'=>'Error', 'content'=>'Todos los documentos deben ser formato PDF y pesar máximo 1MB', 'type'=>'error'];
                }
                
                if($file->getMimeType() == 'application/pdf' && $file->getSize() <= 1048576)
                {
                    if(Storage::disk('convenios')->has($convenio->certificado_militar))
                    {
                        Storage::disk('convenios')->delete($convenio->certificado_militar);
                    }
                    $nombre = 'MILITAR_'.Auth::user()->identificacion.'_'.\Carbon\Carbon::now().'.pdf';
                    $convenio->certificado_militar = $nombre;
                    Storage::disk('convenios')->put($nombre, $file = \File::get($file));
                }
            }
            
            $convenio->save();    
            return ['title'=>'Éxito', 'content'=>'Documentos cargados con éxito', 'type'=>'success'];
        }
        else 
        {
            return ['title'=>'Error', 'content'=>'Usted no puede subir documentos para este convenio', 'type'=>'error'];
        }
        // dd($request->all());
    }
    
    public function getCertificadoexistencia($id)
    {
        $empresa = Auth::user()->getsede->getempresa;
        $convenio = Convenio::where('idEmpresa', $empresa->id)
                            ->where('id', $id)
                            ->first();
                            
        if(sizeof($convenio) == 0)
        {
            $data = ['error'=>'Usted no descargar este documento'];
            return redirect('/empresa/convenio')->with($data);
        }
        
        $nombre = $convenio->certificado_existencia;
        
        $path = storage_path('app/convenios/'.$nombre);
        
        return \Response::download($path);
    }
    
    public function getCedula($id)
    {
        $empresa = Auth::user()->getsede->getempresa;
        $convenio = Convenio::where('idEmpresa', $empresa->id)
                            ->where('id', $id)
                            ->first();
                            
        if(sizeof($convenio) == 0)
        {
            $data = ['error'=>'Usted no descargar este documento'];
            return redirect('/empresa/convenio')->with($data);
        }
        
        $nombre = $convenio->cedula_representante;
        
        $path = storage_path('app/convenios/'.$nombre);
        
        return \Response::download($path);
    }
    
    public function getProcuraduria($id)
    {
        $empresa = Auth::user()->getsede->getempresa;
        $convenio = Convenio::where('idEmpresa', $empresa->id)
                            ->where('id', $id)
                            ->first();
                            
        if(sizeof($convenio) == 0)
        {
            $data = ['error'=>'Usted no descargar este documento'];
            return redirect('/empresa/convenio')->with($data);
        }
        
        $nombre = $convenio->procuraduria;
        
        $path = storage_path('app/convenios/'.$nombre);
        
        return \Response::download($path);
    }
    
    public function getContraloria($id)
    {
        $empresa = Auth::user()->getsede->getempresa;
        $convenio = Convenio::where('idEmpresa', $empresa->id)
                            ->where('id', $id)
                            ->first();
                            
        if(sizeof($convenio) == 0)
        {
            $data = ['error'=>'Usted no descargar este documento'];
            return redirect('/empresa/convenio')->with($data);
        }
        
        $nombre = $convenio->contraloria;
        
        $path = storage_path('app/convenios/'.$nombre);
        
        return \Response::download($path);
    }
    
    public function getRut($id)
    {
        $empresa = Auth::user()->getsede->getempresa;
        $convenio = Convenio::where('idEmpresa', $empresa->id)
                            ->where('id', $id)
                            ->first();
                            
        if(sizeof($convenio) == 0)
        {
            $data = ['error'=>'Usted no descargar este documento'];
            return redirect('/empresa/convenio')->with($data);
        }
        
        $nombre = $convenio->rut;
        
        $path = storage_path('app/convenios/'.$nombre);
        
        return \Response::download($path);
    }
    
    public function getActoadministrativo($id)
    {
        $empresa = Auth::user()->getsede->getempresa;
        $convenio = Convenio::where('idEmpresa', $empresa->id)
                            ->where('id', $id)
                            ->first();
                            
        if(sizeof($convenio) == 0)
        {
            $data = ['error'=>'Usted no descargar este documento'];
            return redirect('/empresa/convenio')->with($data);
        }
        
        $nombre = $convenio->acto_administrativo;
        
        $path = storage_path('app/convenios/'.$nombre);
        
        return \Response::download($path);
    }
    
    public function getActaposesion($id)
    {
        $empresa = Auth::user()->getsede->getempresa;
        $convenio = Convenio::where('idEmpresa', $empresa->id)
                            ->where('id', $id)
                            ->first();
                            
        if(sizeof($convenio) == 0)
        {
            $data = ['error'=>'Usted no descargar este documento'];
            return redirect('/empresa/convenio')->with($data);
        }
        
        $nombre = $convenio->acta_posesion;
        
        $path = storage_path('app/convenios/'.$nombre);
        
        return \Response::download($path);
    }
    
    public function getMilitar($id)
    {
        $empresa = Auth::user()->getsede->getempresa;
        $convenio = Convenio::where('idEmpresa', $empresa->id)
                            ->where('id', $id)
                            ->first();
                            
        if(sizeof($convenio) == 0)
        {
            $data = ['error'=>'Usted no descargar este documento'];
            return redirect('/empresa/convenio')->with($data);
        }
        
        $nombre = $convenio->certificado_militar;
        
        $path = storage_path('app/convenios/'.$nombre);
        
        return \Response::download($path);
    }
    
    public function getActarenovacion($id)
    {
        $empresa = Auth::user()->getsede->getempresa;
        $convenio = Convenio::where('idEmpresa', $empresa->id)
                            ->where('id', $id)
                            ->first();
        
                            
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
        $empresa = Auth::user()->getsede->getempresa;
        $convenio = Convenio::where('idEmpresa', $empresa->id)
                            ->where('id', $id)
                            ->first();
                            
        if(sizeof($convenio) == 0)
        {
            $data = ['error'=>'Usted no puede descargar este documento'];
            return redirect('/admin/convenios')->with($data);
        }
        
        $nombre = $convenio->convenio;
        
        $path = storage_path('app/convenios/'.$nombre);
        
        return \Response::download($path);
    }

    public function getHojasdevida()
    {
        return view('empresa.hojasdevida');
    }

    public function getHojasdevidajson()
    {
        $to_graduados = Tipooferta::where('nombre', 'Graduados')->first()->id;
        $eo_publicada = EstadoOferta::where('nombre', 'Publicada')->first()->id;
        $ofertas = Oferta::where('estado', $eo_publicada)
                        ->where('idTipo', $to_graduados)
                        ->where('idSede', Auth::user()->getsede->id)
                        ->get()->pluck('id');
        
        $programas = Dependencia::whereHas('getofertaprograma', function ($op) use ($ofertas) {
                                    $op->whereIn('idOferta', $ofertas);
                                })
                                ->get()->pluck('id');
        
        $te_graduado = TipoEstudiante::where('nombre', 'Graduado')->first()->id;
        $graduados = Persona::whereHas('getestudiantes', function ($estudiante) use ($te_graduado) {
                                $estudiante->where('idTipo', $te_graduado);
                            })
                            ->whereHas('getestudiantes.getprograma', function ($estudio) use ($programas) {
                                $estudio->whereIn('idPrograma', $programas);
                            })
                            ->whereHas('gethojadevida', function ($hv) {
                                $hv->where('activa', 1);
                            })->whereHas('getusuario.roles', function ($query) {
                                $query->where('nombre', 'Graduado');
                            })->get();

        $res = [];
        foreach ($graduados as $key => $graduado) {
            $mostrar = true;
            $programas = '';

            foreach ($graduado->getestudiantes as $key => $estudiante) {
                $programas .= $estudiante->getprograma->getprograma->nombre;
                if ($key != $graduado->getestudiantes->count()-1) $programas .= ', ';
            }

            if(!$graduado->recibir_mails) $mostrar = false;

            array_push($res, [
                'nombres' => $graduado->nombres,
                'apellidos' => $graduado->apellidos,
                'programas' => $programas,
                'mostrar' => $mostrar,
                'id' => $graduado->id
            ]);
        }

        return $res;
    }

    public function getInvitar($id)
    {
        $empresa = \Auth::user()->getsede->getempresa;
        $graduado = Persona::where('recibir_mails', 1)
                            ->whereHas('gethojadevida', function ($hv) {
                                $hv->where('activa', 1);
                            })
                            ->find($id);
        
        $contenido = 'Buen dia,
                      <br>
                      <br>
                      <div style="font-size:1.2em;">
                      Apreciado graduado, la empresa <b>'.$empresa->nombre.'</b> te invita a ver las ofertas laborales de esta empresa publicadas en el Sistema de Intermediación Laboral SIL Unimagdalena.
                      <br>                          
                      <br>
                      <a href="http://sil.unimagdalena.edu.co">Para mas información visite: sil.unimagdalena.edu.co</a>
                      <br><br>
                      Gracias por atender a nuestros mensajes
                      <br><br>
                      Atentamente,
                      <br><br>
                      <b>Econ. Esp. IVIS ALVARADO MONTENEGRO </b><br>
                      Directora Centro de Egresados <br>
                      Universidad del Magdalena';

        //dd($new->getuser);
        Mail::send('emails.correomasivo', ['dependencia' => 'Centro de Egresados', 'contenido'=>$contenido], function ($m) use ($graduado) {
            $m->from('sil2@unimagdalena.edu.co', env('MAIL_FROM'));
            
            $m->bcc($graduado->correo, $graduado->nombres.' '.$graduado->apellidos)->subject('Oferta laboral');
        });

        return [
            'title' => 'Exito',
            'content' => 'El graduado ha sido invitado a ver las ofertas laborales.',
            'type' => 'success'
        ];
    }

    public function getVerperfil($idPersona, $idOferta=-1)
    {
        $res['idPersona'] = $idPersona;
        $res['mostrarDatos'] = false;
        $postulado = Postulado::where('idPersona', $idPersona)->where('idOferta',$idOferta)->first();
        
        if($postulado) {
            $estadoEstudiante = $postulado->getestadoestudiante->nombre;
            if($estadoEstudiante == 'Aceptó') $res['mostrarDatos'] = true;
        }

        return view('empresa.hoja2', $res);
    }

    public function getHoja2json($idEstudiante)
    {
        $te_graduado = TipoEstudiante::where('nombre', 'Graduado')->first()->id;
        $persona = Persona::with('gethojadevida.getcompetencias')
                            ->with('gethojadevida.getestudios.getmunicipio.getdepartamento.getpais')
                            ->with('gethojadevida.getexperiencias.municipio')
                            ->with('gethojadevida.getexperiencias.duracion')
                            ->with('gethojadevida.getidiomashv.getidioma')
                            ->with('gethojadevida.getidiomashv.getnivellectura')
                            ->with('gethojadevida.getidiomashv.getnivelescritura')
                            ->with('gethojadevida.getidiomashv.getnivelhabla')
                            ->with('gethojadevida.getdiscapacidades')                          
                            ->with('gethojadevida.getdistinciones')                            
                            ->with('getestudiantes.getprograma.getprograma')
                            ->with('getciudad.getdepartamento.getpais')
                            ->with('getgenero')
                            ->with('getestadocivil')
                            ->where('id', $idEstudiante)
                            ->whereHas('getestudiantes', function ($estudiante) use ($te_graduado) {
                                $estudiante->where('idTipo', $te_graduado);
                            })
                            ->first();
        
        $persona->gethojadevida[0]->getreferenciasp = Referencia::where('idHoja', $persona->gethojadevida[0]->id)
                                                                ->whereNull('parentesco')
                                                                ->get();
        $persona->gethojadevida[0]->getreferenciasf = Referencia::with('getparentesco')
                                                                ->where('idHoja', $persona->gethojadevida[0]->id)
                                                                ->whereNotNull('parentesco')
                                                                ->get();


        return $persona;        
        
    }
}