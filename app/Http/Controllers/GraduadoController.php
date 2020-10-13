<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use App\Http\Requests\DatosPersonalesRequest;
use App\Http\Requests\EstudioRequest;
use App\Http\Requests\ExperienciaRequest;
use App\Http\Requests\IdiomaRequest;
use App\Http\Requests\PerfilRequest;
use App\Http\Requests\ReferenciaRequest;

use App\Models\Competencia;
use App\Models\Departamento;
use App\Models\Discapacidad;
use App\Models\Distinciones;
use App\Models\Duracion;
use App\Models\EstadoCivil;
use App\Models\EstadoOferta;
use App\Models\EstadoPostulado;
use App\Models\EstadoPostuladoEst;
use App\Models\Estudio;
use App\Models\Empresa;
use App\Models\Experiencia;
use App\Models\Genero;
use App\Models\HojaCompetencia;
use App\Models\HojaIdioma;
use App\Models\HojaDiscapacidad;
use App\Models\Hojavida;
use App\Models\Idioma;
use App\Models\Municipio;
use App\Models\NivelIdioma;
use App\Models\NivelCargo;
use App\Models\Oferta;
use App\Models\Pais;
use App\Models\Parentesco;
use App\Models\Postulado;
use App\Models\Persona;
use App\Models\PersonaTipocorreo;
use App\Models\Referencia;
use App\Models\Salario;
use App\Models\Tipocorreo;
use App\Models\Tipooferta;
use App\Models\TipoVinculacion;
use App\Models\TipoEstudiante;
use App\Models\User;

use App\ModelS3\IdentificacionS3;
use App\ModelS3\PersonaS3;

use Carbon\Carbon;

use Auth;

class GraduadoController extends Controller
{
    public function __construct()
    {
        $this->middleware("auth");
        $this->middleware("cambiocontrasena");
    }

    public function getIndex()
    {
        return view('graduado.index');
    }

    ///////Hoja de vida/////////////////

    public function getHojavida()
    {
        return view('graduado.hojavida');
    }

    public function getDepartamentos($idPais)
    {
        return Departamento::where('idPais', $idPais)->with('getpais')->get();
    }

    public function getCiudades($idDepartamento)
    {
        return Municipio::with('getdepartamento.getpais')->where('idDepartamento', $idDepartamento)->get();
    }

    public function getHojavidajson()
    {
        $te_graduado = TipoEstudiante::where('nombre', 'Graduado')->first()->id;
        $persona = Persona::with('gethojadevida.getcompetencias')
            ->with('gethojadevida.getestudios.getmunicipio.getdepartamento.getpais')
            ->with('gethojadevida.getexperiencias.duracion')
            ->with('gethojadevida.getexperiencias.municipio.getdepartamento.getpais')
            ->with('gethojadevida.getidiomashv.getidioma')
            ->with('gethojadevida.getidiomashv.getnivellectura')
            ->with('gethojadevida.getidiomashv.getnivelescritura')
            ->with('gethojadevida.getidiomashv.getnivelhabla')
            ->with('gethojadevida.getdiscapacidades')
            ->with('gethojadevida.getdistinciones')
            ->with('getestudiantes.getprograma.getprograma')
            ->with('getciudad.getdepartamento.getpais')
            ->with('getciudadres.getdepartamento.getpais')
            ->with('getgenero')
            ->with('getestadocivil')
            ->whereHas('getestudiantes', function ($estudiante) use ($te_graduado) {
                $estudiante->where('idTipo', $te_graduado);
            })
            ->find(\Auth::user()->getuser->id);

        $persona->paises = Pais::all();
        $persona->paisess = Pais::with('departamentos.municipios')->get();
        $persona->niveles_cargo = NivelCargo::all();
        $persona->salarios = Salario::all();
        $persona->tipos_vinculacion = TipoVinculacion::all();
        $persona->duraciones = Duracion::all();
        $persona->discapacidades = Discapacidad::all();

        if (isset($persona->ciudadResidencia)) {
            $departamento = $persona->getciudadres->getdepartamento;
            $persona->departamentos = Departamento::with('getpais')->where('idPais', $departamento->idPais)->get();
            $persona->ciudades = Municipio::with('getdepartamento.getpais')->where('idDepartamento', $departamento->id)->get();
        }

        // return $persona->getciudadres;

        $persona->generos = Genero::all();
        $persona->estadosciviles = EstadoCivil::all();
        $persona->competencias = Competencia::all();
        $anios = array();
        $fecha = \Carbon\Carbon::now();
        $fecha = $fecha->year;
        for ($i = $fecha; $i >= 1950; $i--) {
            $anio['id'] = $i;
            $anio['nombre'] = $i;
            array_push($anios, $anio);
        }
        $persona->anios = $anios;

        $data['getidiomas'] = [];

        if (sizeof($persona->gethojadevida) > 0 && sizeof($persona->gethojadevida[0]->getidiomashv) > 0) {
            $data['getidiomas'] = HojaIdioma::with('getidioma')
                ->where('idHoja', $persona->gethojadevida[0]->id)
                ->get();
        }

        $con = 0;
        $idiomas = [];
        foreach ($data['getidiomas'] as $idioma) {
            $idiomas[$con] = $idioma->idIdioma;
            $con++;
        }

        $persona->idiomasdb = Idioma::whereNotIn('id', $idiomas)->get();

        $persona->niveles = NivelIdioma::get();
        $persona->parentescos = Parentesco::get();


        $persona->gethojadevida[0]->getreferenciasp = Referencia::where('idHoja', $persona->gethojadevida[0]->id)
            ->whereNull('parentesco')
            ->get();

        $persona->gethojadevida[0]->getreferenciasf = Referencia::with('getparentesco')
            ->where('idHoja', $persona->gethojadevida[0]->id)
            ->whereNotNull('parentesco')
            ->get();

        return $persona;
    }

    public function postSavedatospersonales(DatosPersonalesRequest $request)
    {
        $persona = Persona::find(Auth::user()->idPersona);

        $persona->ciudadOrigen = $request->getciudad['id'];
        $persona->ciudadResidencia = $request->getciudadres['id'];

        $persona->fechaNacimiento = $request->fechaNacimiento;

        $persona->celular = $request->celular;
        $persona->celular2 = $request->celular2;
        $persona->correo = $request->correo;
        $persona->correo2 = $request->correo2;
        $persona->direccion = $request->direccion;
        $persona->telefono_fijo = $request->telefono_fijo;
        $persona->estrato = $request->estrato;
        $persona->idEstadoCivil = $request->getestadocivil['id'];
        $persona->idGenero = $request->getgenero['id'];
        $persona->recibir_mails = $request->recibir_mails;
        $persona->save();

        // $ip = IdentificacionS3::where('NumeroDocumento', $persona->identificacion)->first();

        // if(isset($ip))
        // {
        //     $ps3 = PersonaS3::where('idPersona', $ip->idPersona)->first();
        //     $ps3->FechaNacimiento = $request->fechaNacimiento;
        //     $ps3->Celular = $request->celular;
        //     $ps3->Email = $request->correo;
        //     $ps3->Direccion = $request->direccion;
        //     $ps3->IdTEstadoCivil = $request->getestadocivil['id'];
        //     $ps3->IdGenero = $request->getgenero['id'];
        //     $ps3->save();

        // }

        return ['title' => 'Datos actualizados', 'content' => 'Sus datos fueron actualizados exitosamente', 'type' => 'success'];
    }

    public function getPrueba()
    {
        $ip = IdentificacionS3::where('NumeroDocumento', Auth::user()->getuser->identificacion)->first();

        $ps3 = PersonaS3::where('IdPersona', $ip->IdPersona)->first();

        dd($ip, $ps3);
    }

    public function postSaveperfil(PerfilRequest $request)
    {
        $persona = \Auth::user()->getuser;
        $hoja = $persona->gethojadevida;

        if (sizeof($hoja) == 0) {
            $hoja = new Hojavida();
            $hoja->idPersona = $persona->id;
        } else {
            $hoja = $hoja[0];
        }

        $hoja->perfil = $request->perfil;
        $hoja->save();

        HojaCompetencia::where('idHoja', $hoja->id)->delete();
        if (isset($request->getcompetencias)) {
            foreach ($request->getcompetencias as $competencia) {
                HojaCompetencia::create(['idHoja' => $hoja->id, 'idCompetencia' => $competencia['id']]);
            }
        }

        Distinciones::where('idHoja', $hoja->id)->delete();
        foreach ($request->getdistinciones as $key => $distincion) {
            $d = new Distinciones();
            $d->nombre = $distincion['nombre'];
            $d->idHoja = $hoja->id;
            $d->save();
        }

        HojaDiscapacidad::where('idHoja', $hoja->id)->delete();
        foreach ($request->getdiscapacidades as $key => $disc) {
            $hd = new HojaDiscapacidad();
            $hd->idHoja = $hoja->id;
            $hd->idDiscapacidad = $disc['id'];
            $hd->save();
        }

        Estudio::where('idHoja', $hoja->id)->delete();

        foreach ($request->getestudios as $estudio) {
            if (isset($estudio['observaciones'])) {
                $observaciones = $estudio['observaciones'];
            } else {
                $observaciones = null;
            }
            // dd($observaciones);
            Estudio::create(['idHoja' => $hoja->id, 'institucion' => $estudio['institucion'], 'titulo' => $estudio['titulo'], 'idMunicipio' => $estudio['getmunicipio']['id'], 'anioGrado' => $estudio['anioGrado'], 'observaciones' => $observaciones]);
        }

        // EXPERIENCIA LABORAL
        Experiencia::where('idHoja', $hoja->id)->delete();

        foreach ($request->getexperiencias as $experiencia) {
            $exp = new Experiencia();
            $exp->empresa = $experiencia['empresa'];
            $exp->cargo = $experiencia['cargo'];
            $exp->duracion = $experiencia['duracion']['id'];
            $exp->funcioneslogros = $experiencia['funcioneslogros'];
            $exp->nivel_cargo_id = $experiencia['nivel_cargo_id'];
            $exp->tipo_vinculacion_id = $experiencia['tipo_vinculacion_id'];
            $exp->municipio_id = $experiencia['municipio_id'];
            $exp->email = $experiencia['email'];
            $exp->telefono = $experiencia['telefono'];
            $exp->salario_id = $experiencia['salario_id'];
            $exp->idHoja = $hoja->id;
            $exp->save();
        }

        HojaIdioma::where('idHoja', $hoja->id)->delete();

        foreach ($request->getidiomashv as $idioma) {
            HojaIdioma::create(['idHoja' => $hoja->id, 'idIdioma' => $idioma['getidioma']['id'], 'lectura' => $idioma['getnivellectura']['id'], 'escritura' => $idioma['getnivelescritura']['id'], 'habla' => $idioma['getnivelhabla']['id']]);
        }

        return ['title' => 'Perfil guardado', 'content' => 'El perfil fue guardado exitosamente', 'type' => 'success'];
    }


    public function postSavereferencia(Request $request)
    {
        $persona = \Auth::user()->getuser;
        $hoja = $persona->gethojadevida;

        if (sizeof($hoja) == 0) {
            $hoja = new Hojavida();
            $hoja->perfil = '';
            $hoja->idPersona = $persona->id;
            $hoja->save();
        } else {
            $hoja = $hoja[0];
        }

        Referencia::where('idHoja', $hoja->id)->delete();

        foreach ($request->getreferenciasf as $r) {
            Referencia::create(['idHoja' => $hoja->id, 'nombre' => $r['nombre'], 'ocupacion' => $r['ocupacion'], 'telefono' => $r['telefono'], 'parentesco' => $r['getparentesco']['id']]);
        }

        foreach ($request->getreferenciasp as $r) {
            Referencia::create(['idHoja' => $hoja->id, 'nombre' => $r['nombre'], 'ocupacion' => $r['ocupacion'], 'telefono' => $r['telefono']]);
        }

        return ['title' => 'Exito', 'content' => 'Referencias guardadas exitosamente', 'type' => 'success'];
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

    ///////Ofertas/////////////////

    public function getOfertas()
    {
        return view('graduado.index');
    }

    public function getOfertasjson($id = null)
    {
        $persona = Auth::user()->getuser;

        $ids_programas = [];

        foreach ($persona->getestudiantes as $est) array_push($ids_programas, $est->getprograma->idPrograma);

        if ($id != null) {
            $ofertas = Oferta::with('getsede.getempresa')
                ->with('getsalario')
                ->with('getmunicipio.getdepartamento.getpais')
                ->with('getpostulados.getestadoestudiante')
                ->with('getpostulados.getestadoempresa')
                ->with('getexperiencia')
                ->with('getprogramas')
                ->with('getcontrato')
                ->with('gettipo')
                ->where('idTipo', Tipooferta::where('nombre', 'Graduados')->first()->id)
                ->where('estado', EstadoOferta::where('nombre', 'Publicada')->first()->id)
                ->whereHas('getprogramas', function ($q) use ($ids_programas) {
                    $q->whereIn('dependencias.id', $ids_programas);
                })
                ->where('id', $id)
                ->first();
        } else {


            //dd($ids_programas);

            $postulado = null;
            // $postulado = Postulado::where('idPersona', $persona->id)
            //                       ->where('idEstadoEstudiante', EstadoPostuladoEst::where('nombre', 'Aceptó')->first()->id)
            //                       ->first();

            if (sizeof($postulado) > 0) {
                //$ofertas = Oferta::getOfertasById($postulado->idOferta, $persona->id);

                $ofertas = Oferta::with('getsede.getempresa')
                    ->with('getmunicipio.getdepartamento.getpais')
                    ->with('getsalario')
                    ->with('getpostulados.getestadoestudiante')
                    ->with('getpostulados.getestadoempresa')
                    ->whereHas('getpostulados', function ($q) {
                        $q->where('idEstatoEmpresa', '=', EstadoPostulado::where('nombre', 'Seleccionado')->first()->id)
                            ->where('idEstadoEstudiante', '=', EstadoPostuladoEst::where('nombre', 'Aceptó')->first()->id);
                    })
                    ->where('id', $postulado->idOferta)
                    ->get();
            } else {
                //$ofertas = Oferta::getOfertasByEgresado($ids_programas, $persona->id);  
                $ofertas = Oferta::with('getsede.getempresa')
                    ->with('getmunicipio.getdepartamento.getpais')
                    ->with('getsalario')
                    ->with('getpostulados.getestadoestudiante')
                    ->with('getpostulados.getestadoempresa')
                    ->where('idTipo', Tipooferta::where('nombre', 'Graduados')->first()->id)
                    ->where('estado', EstadoOferta::where('nombre', 'Publicada')->first()->id)
                    // ->where('fechaCierre','<=',)
                    ->whereHas('getprogramas', function ($q) use ($ids_programas) {
                        $q->whereIn('dependencias.id', $ids_programas);
                    })
                    ->get();
            }

            foreach ($ofertas as $o) {
                foreach ($o->getpostulados as $p) {
                    if ($p->idPersona == $persona->id) {
                        $o->getpostulado = $p;
                        break;
                    }
                }
            }
        }

        return $ofertas;
    }

    public function getAceptaroferta($idOferta)
    {
        $identificacion = Auth::user()->getuser->identificacion;
        $persona = Persona::where('identificacion', $identificacion)->first();
        $idPersona = $persona->id;
        $postulado = Postulado::where('idOferta', $idOferta)
            ->where('idPersona', $idPersona)
            ->where('idEstatoEmpresa', EstadoPostulado::where('nombre', 'Seleccionado')->first()->id)
            ->where('idEstadoEstudiante', EstadoPostuladoEst::where('nombre', 'Esperando respuesta')->first()->id)
            ->first();

        //Si no esta seleccionado no se puede aceptar
        if (sizeof($postulado) == 0) {
            $msj = array(
                'error' => 'No es posible aceptar esta oferta'
            );
            return redirect('/graduado/ofertas')->with($msj);
        }

        // algoritmo             
        $postulado->idEstadoEstudiante = EstadoPostuladoEst::where('nombre', 'Aceptó')->first()->id;
        $postulado->save();

        $oferta = Oferta::find($idOferta);
        $empresa = $oferta->getsede->getempresa;
        $usuario = User::where('identificacion', $empresa->nit)->with('getuser')->first();
        $email = User::where('identificacion', $empresa->nit)->first()->getsede->correo;
        $persona = \Auth::user()->getuser;
        $texto = $persona->nombres . ' ' . $persona->apellidos . " ha aceptado pasar a la siguiente fase de la convocatoria.\n\nSe habilitaron los datos de contacto de la persona en la plataforma SIL Unimagdalena.\nEn caso de escoger a alguno de los postulados, recuerde indicar en la plataforma SIL Unimagdalena cual de estos ha sido escogido.";

        //Enviar correo a la empresa
        \Mail::raw($texto, function ($message) use ($usuario, $persona, $email) {
            $message->from('sil2@unimagdalena.edu.co', env('MAIL_FROM'));

            $message->to($email, $usuario->getuser->nombres . ' ' . $usuario->getuser->apellidos)->subject($persona->nombres . ' ' . $persona->apellidos . ' ha aceptado la oferta');
        });

        $data['content'] = 'Usted ha aceptado la oferta. La empresa se pondrá en contacto con usted.';
        return redirect('/graduado/ofertas')->with($data);
    }


    public function getPostularse($idOferta)
    {
        $persona = Persona::where('identificacion', Auth::user()->getuser->identificacion)->first();

        //No dejar postular si la persona esta elegida en otra oferta de la misma empresa
        $flg = false;
        $oferta = Oferta::find($idOferta);
        $empresa = Empresa::find($oferta->getsede->getempresa->id);
        $ofertasEmpresa = Oferta::where('estado', EstadoOferta::where('nombre', 'Publicada')->first()->id)
            ->whereHas('getsede.getempresa', function ($query) use ($empresa) {
                $query->where('id', $empresa->id);
            })->select('id')
            ->get();
        $postuladosEmpresa = Postulado::whereIn('idEstatoEmpresa', EstadoPostulado::where('nombre', '!=', 'No seleccionado')->select('id')->get())
            ->whereIn('idOferta', $ofertasEmpresa)
            ->with('getpersona')
            ->get();
        // return $postuladosEmpresa;
        foreach ($postuladosEmpresa as $key => $value) {
            if ($value->getpersona == $persona) {
                $flg = true;
                // $msj = array('error'=>'No puede postularse a esta oferta, ya ha apuntado a otra oferta de esta empresa');
                $msj = ['status' => 'error', 'content' => 'No puede postularse a esta oferta, ya ha apuntado a otra oferta activa de esta empresa'];
            }
        }

        if (!$flg) {
            $postulado = new Postulado();
            $postulado->idPersona = $persona->id;
            $postulado->idOferta = $idOferta;
            $postulado->idEstatoEmpresa = EstadoPostulado::where('nombre', 'Postulado')->first()->id;
            $postulado->idEstadoEstudiante = EstadoPostuladoEst::where('nombre', 'Esperando respuesta')->first()->id;
            $postulado->save();

            $msj = [
                'status' => 'success',
                'content' => 'Usted se ha postulado con exito'
            ];
        }

        return $msj;
    }

    public function getNopostularse($idOferta)
    {
        $identificacion = Auth::user()->getuser->identificacion;
        $persona = Persona::where('identificacion', $identificacion)->first();
        $postulado = Postulado::where('idOferta', $idOferta)->where('idPersona', $persona->id)->first();
        if ($postulado->getestadoempresa->nombre != 'Seleccionado') {
            $postulado->delete();

            $msj = [
                'status' => 'success',
                'content' => 'Cancelación exitosa'
            ];

            return $msj;
        }
    }

    ///////Reporte/////////////////

    public function getReporte()
    {
        return view('graduado.reporte');
    }

    ///////configuracion/////////////////

    public function getConfig()
    {
        return view('graduado.config');
    }

    public function getConfigjson()
    {
        return User::with('getuser.gethojadevida')->where('id', Auth::user()->id)->first();
    }

    public function getRecibirmails($bool)
    {
        if ($bool || !$bool) {
            $persona = Persona::find(Auth::user()->getuser->id);
            $persona->recibir_mails = $bool;

            // if($bool)
            // {
            //     $ptc = new PersonaTipocorreo();
            //     $ptc->idPersona = $persona->id;
            //     $ptc->idTipocorreo = Tipocorreo::where('nombre', 'Convocatorias')->first()->id;
            //     $ptc->save();
            // }
            // else
            // {
            //     PersonaTipocorreo::where('idPersona', $persona->id)->where('idTipocorreo', Tipocorreo::where('nombre', 'Convocatorias')->first()->id)->delete();
            // }

            $persona->save();
            return [
                'title' => 'Éxito',
                'content' => 'Estado modificado con éxito',
                'type' => 'success'
            ];
        } else {
            return [
                'title' => 'Error',
                'content' => 'No es posible modificar el estado',
                'type' => 'error'
            ];
        }
    }

    public function getVisibilidadhojavida($bool)
    {
        if ($bool || !$bool) {
            if (sizeof(Auth::user()->getuser->gethojadevida) == 0) {
                $hoja = new hojavida();
                $hoja->perfil = ' ';
                $hoja->idPersona = Auth::user()->getuser->id;
            } else {
                $hoja = Hojavida::find(Auth::user()->getuser->gethojadevida[0]->id);
            }

            $hoja->activa = $bool;
            $hoja->save();
            return [
                'title' => 'Éxito',
                'content' => 'Estado modificado con éxito',
                'type' => 'success'
            ];
        } else {
            return [
                'title' => 'Error',
                'content' => 'No es posible modificar el estado',
                'type' => 'error'
            ];
        }
    }

    public function getDesactivar()
    {
        $persona = Persona::find(Auth::user()->getuser->id);
        $persona->recibir_mails = false;
        PersonaTipocorreo::where('idPersona', $persona->id)->where('idTipocorreo', Tipocorreo::where('nombre', 'Convocatorias')->first()->id)->delete();
        $persona->save();

        if (sizeof(Auth::user()->getuser->gethojadevida) == 0) {
            $hoja = new hojavida();
            $hoja->perfil = ' ';
            $hoja->idPersona = Auth::user()->getuser->id;
        } else {
            $hoja = Hojavida::find(Auth::user()->getuser->gethojadevida[0]->id);
        }

        $hoja->activa = false;
        $hoja->save();

        $user = User::find(Auth::user()->id);
        $user->activo = false;
        $user->save();

        return [
            'title' => 'Éxito',
            'content' => 'Cuenta desactivada con éxito',
            'type' => 'success'
        ];
    }
}
