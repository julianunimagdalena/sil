<?php

namespace App\Http\Controllers;

use App\Providers\WebService;
use App\Providers\WebServiceSieg;
use Illuminate\Http\Request;

use Auth;
use Hash;
use Mail;
use Carbon\Carbon;

use App\Models\ActividadesEconomicas;
use App\Models\Carta;
use App\Models\Departamento;
use App\Models\Dependencia;
use App\Models\DependenciaModalidad;
use App\Models\DistincionEstudiante;
use App\Models\Empresa;
use App\Models\EstadoEmpresas;
use App\Models\Estudiante;
use App\Models\FechasGrado;
use App\Models\Genero;
use App\Models\Jornada;
use App\Models\Hojavida;
use App\Models\Modalidad;
use App\Models\ModalidadEstudiante;
use App\Models\ModalidadEstudio;
use App\Models\Municipio;
use App\Models\Novedad;
use App\Models\Pais;
use App\Models\Persona;
use App\Models\ProcesoGrado;
use App\Models\Rol;
use App\Models\Sede;
use App\Models\TipoDocumento;
use App\Models\TipoEmpleador;
use App\Models\TipoEstudiante;
use App\Models\TipoGrado;
use App\Models\TipoNit;
use App\Models\TipoSedes;
use App\Models\User;
use App\Models\UsuarioNovedad;

use App\Http\Requests\ClaveRequest;
use App\Http\Requests\ContactoRequest;
use App\Http\Requests\LoginRequest;
use App\Http\Requests\RegistoRequest;
use App\Http\Requests\RestablecerRequest;

use GuzzleHttp\Client;

class HomeController extends Controller
{

    public function __construct()
    {
        $this->middleware('registro', ['only' => ['postRegistro']]);
        $this->middleware("cambiocontrasena", ['only' => ['getCambiarclave', 'postCambiarclave']]);
        $this->middleware('auth', ['only' => ['getCambiarclave', 'postCambiarclave']]);
    }

    private function authldap($user, $password)
    {
        $client = new \GuzzleHttp\Client();
        // return $client;
        $res = $client->request('POST', env('url_auth') . 'authic/auth', [
            'form_params' => [
                'user' => $user,
                'password' => $password,
                'token' => strtoupper(md5('@7t3nt1c4c10n' . \Carbon\Carbon::now()->toDateString()))
            ]
        ]);

        $aux = json_decode($res->getBody());
        return $aux;
    }

    public function getIndex()
    {
        if (Auth::check())
            // return redirect(Auth::user()->getrol->home);
            return redirect(Session()->get('rol')->home);
        else
            return redirect('/');


        return view('home.index');
    }

    public function getNormatividad()
    {
        return view('home.normatividad');
    }

    public function getInstitucionalidad()
    {
        return view('home.institucionalidad');
    }

    public function getInscribete()
    {
        return view('home.inscribete');
    }

    public function getLoginnuevo()
    {
        return view('home.loginnuevo');
    }

    public function getContacto()
    {
        return view('home.contacto');
    }

    public function getRegistro()
    {
        return view('home.registro', compact('response'));
    }

    public function getContactojson()
    {
        $codigo = $this->codigo();

        Session()->put('codigo', $codigo);

        return $codigo;
    }

    public function postSavecontacto(ContactoRequest $request)
    {
        if ($request->codigo_de_verificacion != Session()->get('codigo')) {
            return [
                'codigo_de_verificacion' => ['Código de verificación inválido']
            ];
        }

        $novedad = new Novedad();
        $novedad->nombres = $request->nombres;
        $novedad->apellidos = $request->apellidos;
        $novedad->tipo_identificacion = TipoDocumento::where('id', $request->tipo_de_identificacion)->first()->abrv;
        $novedad->identificacion = $request->identificacion;
        $novedad->correo = $request->correo;
        $novedad->celular = $request->celular;
        $novedad->contenido = $request->comentario;
        $novedad->asunto = env('contacto');
        $novedad->fecha = Carbon::now();
        $novedad->save();

        UsuarioNovedad::create(['idUsuario' => User::where('identificacion', env('admin_egresados'))->first()->id, 'idNovedad' => $novedad->id]);
        return [
            'title' => 'Éxito',
            'content' => 'Su solicitud de contacto ha sido enviada con éxito',
            'type' => 'success'
        ];
    }

    private function codigo()
    {
        $array = [
            'A', 'B', 'C', 'D', 'E', 'F', 'G', 'H', 'I', 'J', 'K', 'L', 'M', 'N', 'O', 'P', 'Q', 'R', 'S', 'T', 'U', 'V', 'W', 'X', 'Y', 'Z',
            'a', 'b', 'c', 'd', 'e', 'f', 'g', 'h', 'i', 'j', 'k', 'l', 'm', 'n', 'o', 'p', 'q', 'r', 's', 't', 'u', 'v', 'w', 'x', 'y', 'z',
            '0', '1', '2', '3', '4', '5', '6', '7', '8', '9'
        ];

        $aux = rand(0, 61);
        $text = '' . $array[$aux];
        $aux = rand(0, 61);
        $text = $text . $array[$aux];
        $aux = rand(0, 61);
        $text = $text . $array[$aux];
        $aux = rand(0, 61);
        $text = $text . $array[$aux];
        $aux = rand(0, 61);
        $text = $text . $array[$aux];
        $aux = rand(0, 61);
        $text = $text . $array[$aux];

        return $text;
    }

    public function postRestablecer(RestablecerRequest $request)
    {
        $vector = ['@', '-', '/', '_', '&', '%', '$'];


        $usuario = User::where('identificacion', $request->usuario)->first();
        $persona = Persona::where('id', $usuario->idPersona)
            ->where('correo', $request->correo)
            ->first();

        if (sizeof($persona) == 0) {
            return ['type' => 'error', 'title' => 'Error', 'content' => 'Usuario y correo no coinciden'];
        }


        $num1 = rand(0, 6);
        $num2 = rand(0, 6);
        $num3 = rand(0, 9);
        $num4 = rand(0, 9);
        $num5 = rand(0, 9);
        $num6 = rand(0, 9);



        $usuario->password = Hash::make(explode(' ', $persona->nombres)[0] . $vector[$num1] . $persona->identificacion . $vector[$num2]);
        $usuario->codigo_verificacion = $num3 . $num4 . $num5 . $num6;
        $usuario->save();



        $contenido = 'Buen dia,
                      <br>
                      <br>
                      <div style="font-size:1.2em;">
                      Se ha restablecido su contraseña; para iniciar sesión el usuario es <b>' . $usuario->identificacion . '</b> y la clave es <b>' . explode(' ', $persona->nombres)[0] . $vector[$num1] . $persona->identificacion . $vector[$num2] . '</b>
                      </div>
                      <br><br>
                      Gracias por atender a nuestros mensajes.
                      <br><br>
                      Atentamente,
                      <br>
                      ' . env('firma');

        //dd($new->getuser);
        Mail::send('emails.registrosil', ['dependencia' => 'Sistema Intermediación Laboral', 'contenido' => $contenido], function ($m) use ($usuario) {
            $m->from('sil2@unimagdalena.edu.co', env('MAIL_FROM'));
            $m->to($usuario->getuser->correo, $usuario->getuser->nombres . ' ' . $usuario->getuser->apellidos)->subject('Restablecer contraseña');
        });

        $correo = $this->cifrarCorreo($persona->correo);

        return ['type' => 'success', 'title' => 'Éxito', 'content' => 'Contraseña restablecida exitosamente. Se ha enviado su contraseña al
        correo ' . $correo . '.'];
    }

    public function postRegistro(RegistoRequest $request)
    {
        //'g-recaptcha-response' => 'required|captcha' regla de validacion

        if ($request->rol['nombre'] == 'Estudiante') {
            $ws = new WebServiceSieg();

            $data = [
                'codigo' => $request->codigo,
                'password' => strtoupper(md5($request->password)),
                'token' => strtoupper(md5($ws->token($request->identificacion))),
            ];

            $log = json_decode(json_encode($ws->call('login', [$data])), true)['return'];

            if (!$log) {
                return ['type' => 'error', 'title' => 'Error!', 'content' => 'Código o contraseña errados'];
            }

            if ($request->tipoEstudiante['nombre'] == 'Preprácticas' || $request->tipoEstudiante['nombre'] == 'Prácticas y preprácticas') {

                $ws = new WebService(); //2011161026
                $data = [
                    'codEstudiante' => $request->codigo,
                    'token'   => strtoupper(md5("s3rvW3bd1ppro@2*16_-" . \Carbon\Carbon::now()->format('d/m/Y') . "_-" . $request->codigo)),
                ];
                $activo = true;
                $estudiante = json_decode(json_encode($ws->call('getEstudiantesPrePracticas', [$data])), true);
                $tipo = TipoEstudiante::where('nombre', 'Preprácticas')->first()->id;
                if (!isset($estudiante['return']))
                    return ['type' => 'error', 'title' => 'Error!', 'content' => 'No se encontró el estudiante. El estudiante no existe o no esta cursando prepracticas'];
            } else if ($request->tipoEstudiante['nombre'] == 'Prácticas') {
                $activo = false;
                $tipo = TipoEstudiante::where('nombre', 'Solicitó prácticas')->first()->id;
            }

            $ws = new WebServiceSieg();
            $data = [
                'codigo' => $request->codigo,
                'token'   => strtoupper(md5($ws->token($request->codigo))),
            ];
            $estudiante = json_decode(json_encode($ws->call('getInfoEstudianteByCodigo', [$data])), true);

            if (!isset($estudiante['return']) || $estudiante['return']['estado'] == 'F.B.R.A.' || $estudiante['return']['estado'] == 'GRADUADO')
                return ['type' => 'error', 'title' => 'Error!', 'content' => 'No se encontró el estudiante.'];


            $estudiante = $estudiante['return'];
            $persona = null; //numDocumento

            $documento = $estudiante['numDocumento'];
            $nombres = $estudiante['nombres'];
            $apellidos = $estudiante['apellidos'];
            $celular = $estudiante['celular'];
            $tipodoc = $estudiante['tipoDoc'];

            if (sizeof(Persona::where('identificacion', $documento)->first()) == 0) {
                $persona = new Persona();
                $persona->nombres = $nombres;
                $persona->apellidos = $apellidos;
                $persona->correo = $estudiante['email'];
                $persona->identificacion = $documento;
                $persona->celular = $celular;
                $persona->tipodoc = $tipodoc;
                $persona->save();
            } else {
                $persona = Persona::where('identificacion', $documento)->first();
            }

            if (sizeof(Estudiante::where('codigo', $request->codigo)->first()) != 0)
                return ['type' => 'error', 'title' => 'Error!', 'content' => 'Este estudiante ya se encuentra registrado'];

            $estudiante = new Estudiante();
            $codPrograma = substr($request->codigo, -strlen($request->codigo) + 5, 2);
            $estudiante->idPrograma = Dependencia::where('codigoPrograma', $codPrograma)->first()->id;
            $estudiante->codigo = $request->codigo;
            $estudiante->idTipo = $tipo;
            $estudiante->idPersona = $persona->id;
            $estudiante->save();

            if ($request->tipoEstudiante['nombre'] == 'Prácticas') {
                ModalidadEstudiante::create(['idEstudiante' => $estudiante->id, 'idModalidad' => $request->modalidad['id']]);
            }

            $usuario = new User();
            $usuario->idPersona = $persona->id;
            $usuario->idRol = Rol::where('nombre', 'Estudiante')->first()->id;
            $usuario->password = "";
            $usuario->identificacion = $request->codigo;
            $usuario->activo = $activo;
            $usuario->save();

            return ['type' => 'success', 'title' => 'Registro exitoso!', 'content' => 'Estudiante registrado con éxito'];
        } else if ($request->rol['nombre'] == 'Graduado') {
            // $vector = ['@', '-', '/', '_', '&', '%', '$'];

            // $persona = Persona::where('identificacion', $request->identificacion)->first();

            // if($persona == null) {
            //     //dd($request->all());
            //     $ws = new WebServiceSieg();
            //     $data = [
            //         'tipoDocumento' =>  $request->tipodoc['abrv'],
            //         'numeroDocumento' =>$request->identificacion,
            //         'token' => strtoupper(md5($ws->token($request->identificacion)))
            //     ];
            //     $graduado = json_decode(json_encode($ws->call('getInformacionGraduadoByDocumentoIdentidad',[$data])), true);

            //     if(isset($graduado['return']))
            //     {
            //         $graduado = $graduado['return'];
            //         //return ['type'=> 'success','title'=>'Éxito!', 'content'=>'Se encontraron registros.'];
            //     }
            //     else
            //     {
            //         return ['type'=> 'error','title'=>'Error!', 'content'=>'No se encontró registros con esta identificación.'];
            //     }

            //     // un solo registro de estudio
            //     if(!isset($graduado[0]))
            //     {
            //         if ($graduado['situacionAcademica'] != "GRADUADO")
            //         {
            //             return ['type'=> 'error','title'=>'Error!', 'content'=>'No se encontró registros con esta identificación.'];
            //         }
            //         $codigo = $graduado['codigoEstudiantil'];

            //         $ws = new WebServiceSieg();
            //         $data = [
            //             'codigo' => $codigo,
            //             'token'   => strtoupper(md5($ws->token($codigo))),
            //         ];
            //         $estudiante = json_decode(json_encode($ws->call('getInfoEstudianteByCodigo',[$data])), true);
            //         if(isset($estudiante['return'])) {
            //             $estudiante = $estudiante['return'];
            //         }

            //         $ciudadResidencia = Municipio::where('nombre', ucwords(strtolower($graduado['ciudadResidencia'])))->first();
            //         $ciudadOrigen = Municipio::where('nombre', ucwords(strtolower($graduado['ciudad'])))->first();
            //         $distincion = DistincionEstudiante::where('nombre', $graduado['tipoDistincionGrado'])->first();
            //         $jornada = Jornada::where('nombre', $estudiante['jorPrograma'])->first()->id;
            //         $zonal = Municipio::where('nombre', 'like', $estudiante['zonal'])->first();

            //         if ($distincion == null) {
            //             $distincion = new DistincionEstudiante();
            //             $distincion->nombre = $graduado['tipoDistincionGrado'];
            //             $distincion->save();
            //         }

            //         $persona = new Persona();
            //         $persona->nombres = $graduado['nombres'];
            //         $persona->apellidos = $graduado['apellidos'];
            //         $persona->correo = $estudiante['email'];
            //         // $persona->correo = 'cekijofit@theskymail.com';
            //         $persona->identificacion = $request->identificacion;
            //         $persona->celular = $estudiante['celular'];
            //         $persona->telefono_fijo = $estudiante['telefono'];
            //         $persona->ciudadExpedicion = ucwords(strtolower($graduado['ciudadCedula']));
            //         $persona->ciudadResidencia = $ciudadResidencia ? $ciudadResidencia->id:null;
            //         $persona->ciudadOrigen = $ciudadOrigen ? $ciudadOrigen->id:null;
            //         $persona->fechaNacimiento = (new Carbon(str_replace('/', '-', $estudiante['fecNacimiento'])))->toDateString();
            //         $persona->direccion = $estudiante['direccion'];
            //         $persona->estrato = $estudiante['estrato'];
            //         $persona->idTipoDoc = TipoDocumento::where('abrv', $graduado['tipoDocumento'])->first()->id;
            //         $persona->tipodoc = TipoDocumento::where('abrv', $graduado['tipoDocumento'])->first()->id;
            //         $persona->graduado_sil = 1;
            //         $persona->recibir_mails = 1;
            //         $persona->recibir_mails_sieg = 1;
            //         $persona->estadovida = 1;

            //         if($estudiante['genero'] == 'M') $persona->idGenero = Genero::where('nombre', 'Masculino')->first()->id;
            //         else $persona->idGenero = Genero::where('nombre', 'Femenino')->first()->id;

            //         $persona->save();

            //         $est = new Estudiante();
            //         $est->idPersona = $persona->id;
            //         $paux = DependenciaModalidad::where('idPrograma', Dependencia::where('nombre', $estudiante['nombrePrograma'])->first()->id)
            //                                                 ->where('idFacultad', Dependencia::where('nombre', $estudiante['facultad'])->first()->id)
            //                                                 ->where('idJornada', $jornada)
            //                                                 ->first();

            //         if ($paux == null) {
            //             $paux = new DependenciaModalidad();
            //             $paux->idPrograma = Dependencia::where('nombre', $estudiante['nombrePrograma'])->first()->id;
            //             $paux->idFacultad = Dependencia::where('nombre', $estudiante['facultad'])->first()->id;
            //             $paux->idModalidad = ModalidadEstudio::where('abrv', $estudiante['modalidad'])->first()->id;
            //             $paux->idJornada = $jornada;
            //             $paux->save();
            //         }

            //         $est->idPrograma = $paux->id;
            //         $est->codigo = $estudiante['codigo'];
            //         $est->idTipo = TipoEstudiante::where('nombre', 'Graduado')->first()->id;
            //         $est->distincion = $distincion->id;
            //         $est->acta = $graduado['acta'];
            //         $est->folio = $graduado['folio'];
            //         $est->libro = $graduado['libro'];
            //         $est->idZonal = $zonal ? $zonal->id:null;
            //         $est->censo = 1;
            //         $est->save();

            //         $fgrado = FechasGrado::where('fecha_grado', Carbon::createFromFormat('d/m/Y', $graduado['fechaGrado'])->toDateString())
            //                             ->first();
            //         if (!$fgrado) {
            //             $fecha = Carbon::createFromFormat('d/m/Y', $graduado['fechaGrado']);
            //             // $modalidad = ucwords(strtolower($graduado['modalidad']));
            //             $modalidad = $graduado['modalidad'];

            //             $mg = ModalidadEstudio::where('nombre', $modalidad)->first()->id;

            //             $fgrado = new FechasGrado();
            //             $fgrado->anio = $fecha->year;
            //             $fgrado->fecha_grado = $fecha->toDateString();
            //             $fgrado->estado = 0;
            //             $fgrado->nombre = $fecha->englishMonth.' '.$fecha->day.' '.$fecha->year;
            //             $fgrado->tipo_grado = TipoGrado::where('nombre', ucwords(strtolower($graduado['tipoDeGruadiacion'])))->first()->id;

            //             $fgrado->save();
            //             $fgrado->modalidadesEstudio()->attach($mg);
            //         }
            //         else {
            //             $fme = $fgrado->modalidadesEstudio;
            //             // $modalidad = ucwords(strtolower($graduado['modalidad']));
            //             $modalidad = $graduado['modalidad'];
            //             $b = false;

            //             foreach ($fme as $key => $mod) {
            //                 if ($mod->nombre == $modalidad) $b = true;
            //             }

            //             if (!$b) $fgrado->modalidadesEstudio()->attach(ModalidadEstudio::where('nombre', $modalidad)->first()->id);
            //         }

            //         $pgrado = new ProcesoGrado();
            //         $pgrado->idEstudiante = $est->id;
            //         $pgrado->idFecha = $fgrado->id;
            //         $pgrado->estado_ficha = 1;
            //         $pgrado->estado_encuesta = 1;
            //         $pgrado->estado_secretaria = 1;
            //         $pgrado->estado_programa = 1;
            //         $pgrado->save();

            //         $usuario = new User();
            //         $usuario->idPersona = $persona->id;
            //         $usuario->idRol = Rol::where('nombre', 'Graduado')->first()->id;
            //         $num1 = rand(0, 6);
            //         $num2 = rand(0, 6);
            //         $usuario->password = Hash::make(explode(' ', $persona->nombres)[0].$vector[$num1].$persona->identificacion.$vector[$num2]);
            //         $usuario->identificacion = $persona->identificacion;
            //         $usuario->activo = true;
            //         $usuario->codigo_verificacion='Gener';
            //         $usuario->save();

            //         $hoja = new Hojavida();
            //         $hoja->idPersona = $persona->id;
            //         $hoja->perfil = 'Perfil profesional';
            //         $hoja->activa = 1;
            //         $hoja->save();

            //         // Auth::login($usuario);

            //         $contenido = 'Buen dia,
            //                       <br>
            //                       <br>
            //                       <div style="font-size:1.2em;">
            //                       Se asoció un usuario al graduado '.$persona->nombres.' '.$persona->apellidos.' para el estudio de '.$estudiante['nombrePrograma'].'.
            //                       <br>
            //                       <br>
            //                       Para iniciar sesión el usuario es <b>'.$persona->identificacion.'</b> y la clave es <b>'.explode(' ', $persona->nombres)[0].$vector[$num1].$persona->identificacion.$vector[$num2].'</b>
            //                       </div>
            //                       <br><br>
            //                       <a href="http://sil.unimagdalena.edu.co">Más detalle en: sil.unimagdalena.edu.co</a>
            //                       <br><br>
            //                       Gracias por atender a nuestros mensajes
            //                       <br><br>
            //                       Atentamente,
            //                       <br><br>
            //                       <b>Econ. Esp. IVIS ALVARADO MONTENEGRO </b><br>
            //                       Directora Centro de Egresados <br>
            //                       Universidad del Magdalena';

            //         //dd($new->getuser);
            //         Mail::send('emails.registrosil', ['dependencia' => 'Centro de Egresados', 'contenido'=>$contenido], function ($m) use ($persona) {
            //             $m->from('sil2@unimagdalena.edu.co', env('MAIL_FROM'));
            //             $m->to($persona->correo, $persona->nombres.' '.$persona->apellidos)->subject('Registro en plataforma SIL');
            //         });

            //         return [
            //             'type'=>'success',
            //             'title'=>'Exito',
            //             'content'=>'Se ha creado un usuario para '.$persona->nombres.' '.$persona->apellidos.'.'
            //         ];
            //     }

            //     // dos o mas registros de estudio
            //     else
            //     {
            //         $estudiantes = [];
            //         $estudios = '';
            //         foreach ($graduado as $key => $g) {
            //             if ($g['situacionAcademica'] == "GRADUADO")
            //             {
            //                 $codigo = $g['codigoEstudiantil'];

            //                 $ws = new WebServiceSieg();
            //                 $data = [
            //                     'codigo' => $codigo,
            //                     'token'   => strtoupper(md5($ws->token($codigo))),
            //                 ];
            //                 $estudiante = json_decode(json_encode($ws->call('getInfoEstudianteByCodigo',[$data])), true);
            //                 if(isset($estudiante['return'])) {
            //                     $estudiante = $estudiante['return'];
            //                 }
            //                 array_push($estudiantes, $estudiante);
            //             }
            //         }
            //         // return compact('graduado', 'estudiantes');
            //         if (count($estudiantes) > 0) {
            //             $celulares = explode('-', $estudiantes[0]['celular']);
            //             $ciudadResidencia = Municipio::where('nombre', ucwords(strtolower($graduado[0]['ciudadResidencia'])))->first();
            //             $ciudadOrigen = Municipio::where('nombre', ucwords(strtolower($graduado[0]['ciudad'])))->first();

            //             $persona = new Persona();
            //             $persona->nombres = $graduado[0]['nombres'];
            //             $persona->apellidos = $graduado[0]['apellidos'];
            //             // $persona->correo = 'lelunulete@mail-2-you.com';
            //             $persona->correo = $estudiantes[0]['email'];
            //             $persona->identificacion = $request->identificacion;
            //             $persona->celular = $celulares[0];
            //             $persona->celular2 = isset($celulares[1])? $celulares[1] : null;
            //             $persona->telefono_fijo = $estudiantes[0]['telefono'];
            //             $persona->ciudadExpedicion = ucwords(strtolower($graduado[0]['ciudadCedula']));
            //             $persona->ciudadResidencia = $ciudadResidencia == null ? null:$ciudadResidencia->id;
            //             $persona->ciudadOrigen = $ciudadOrigen == null ? null:$ciudadOrigen->id;
            //             $persona->direccion = $estudiantes[0]['direccion'];
            //             $persona->estrato = $estudiantes[0]['estrato'];
            //             $persona->idTipoDoc = TipoDocumento::where('abrv', $graduado[0]['tipoDocumento'])->first()->id;
            //             $persona->tipodoc = TipoDocumento::where('abrv', $graduado[0]['tipoDocumento'])->first()->id;
            //             $persona->graduado_sil = 1;
            //             $persona->recibir_mails = 1;
            //             $persona->recibir_mails_sieg = 1;
            //             $persona->estadovida = 1;
            //             $persona->fechaNacimiento = (new Carbon(str_replace('/', '-', $estudiantes[0]['fecNacimiento'])))->toDateString();
            //             $estudiantes[0]['genero'] == 'M' ? $persona->idGenero = Genero::where('nombre', 'Masculino')->first()->id : $persona->idGenero = Genero::where('nombre', 'Femenino')->first()->id;
            //             $persona->save();

            //             $usuario = new User();
            //             $usuario->idPersona = $persona->id;
            //             $usuario->idRol = Rol::where('nombre', 'Graduado')->first()->id;
            //             $num1 = rand(0, 6);
            //             $num2 = rand(0, 6);
            //             $usuario->password = Hash::make(explode(' ', $persona->nombres)[0].$vector[$num1].$persona->identificacion.$vector[$num2]);
            //             $usuario->identificacion = $persona->identificacion;
            //             $usuario->activo = true;
            //             $usuario->codigo_verificacion='Gener';
            //             $usuario->save();

            //             $hoja = new Hojavida();
            //             $hoja->idPersona = $persona->id;
            //             $hoja->perfil = '';
            //             $hoja->activa = 1;
            //             $hoja->save();

            //             foreach ($estudiantes as $key => $estudiante) {
            //                 $distincion = DistincionEstudiante::where('nombre', $graduado[$key]['tipoDistincionGrado'])->first();
            //                 $jornada = Jornada::where('nombre', $estudiante['jorPrograma'])->first()->id;
            //                 $zonal = Municipio::where('nombre', 'like', $estudiante['zonal'])->first();

            //                 if ($distincion == null) {
            //                     $distincion = new DistincionEstudiante();
            //                     $distincion->nombre = $graduado[$key]['tipoDistincionGrado'];
            //                     $distincion->save();
            //                 }

            //                 $paux = DependenciaModalidad::where('idPrograma', Dependencia::where('nombre', $estudiante['nombrePrograma'])->first()->id)
            //                                             ->where('idFacultad', Dependencia::where('nombre', $estudiante['facultad'])->first()->id)
            //                                             ->where('idModalidad',ModalidadEstudio::where('abrv', $estudiante['modalidad'])->first()->id)
            //                                             ->where('idJornada', $jornada)
            //                                             ->first();

            //                 if ($paux == null) {
            //                     $paux = new DependenciaModalidad();
            //                     $paux->idPrograma = Dependencia::where('nombre', $estudiante['nombrePrograma'])->first()->id;
            //                     $paux->idFacultad = Dependencia::where('nombre', $estudiante['facultad'])->first()->id;
            //                     $paux->idModalidad = ModalidadEstudio::where('abrv', $estudiante['modalidad'])->first()->id;
            //                     $paux->idJornada = $jornada;
            //                     $paux->save();
            //                 }

            //                 $est = new Estudiante();
            //                 $est->idPersona = $persona->id;
            //                 $est->idPrograma = $paux->id;
            //                 $est->codigo = $estudiante['codigo'];
            //                 $est->idTipo = TipoEstudiante::where('nombre', 'Graduado')->first()->id;
            //                 $est->distincion = $distincion->id;
            //                 $est->acta = $graduado[$key]['acta'];
            //                 $est->folio = $graduado[$key]['folio'];
            //                 $est->libro = $graduado[$key]['libro'];
            //                 $est->idZonal = $zonal ? $zonal->id:null;
            //                 $est->censo = 0;
            //                 $est->save();

            //                 $fgrado = FechasGrado::where('fecha_grado', Carbon::createFromFormat('d/m/Y', $graduado[$key]['fechaGrado'])->toDateString())
            //                                     ->first();

            //                 if (!$fgrado) {
            //                     // $modalidad = ucwords(strtolower($graduado[$key]['modalidad']));
            //                     $modalidad = $graduado[$key]['modalidad'];
            //                     $fecha = Carbon::createFromFormat('d/m/Y', $graduado[$key]['fechaGrado']);

            //                     $mg = ModalidadEstudio::where('nombre', $modalidad)->first()->id;

            //                     $fgrado = new FechasGrado();
            //                     $fgrado->anio = $fecha->year;
            //                     $fgrado->fecha_grado = $fecha->toDateString();
            //                     $fgrado->estado = 0;
            //                     $fgrado->nombre = $fecha->englishMonth.' '.$fecha->day.' '.$fecha->year;
            //                     $fgrado->tipo_grado = TipoGrado::where('nombre', ucwords(strtolower($graduado[$key]['tipoDeGruadiacion'])))->first()->id;
            //                     $fgrado->save();
            //                     $fgrado->modalidadesEstudio()->attach($mg);
            //                 }

            //                 else {
            //                     $fme = $fgrado->modalidadesEstudio;
            //                     // $modalidad = ucwords(strtolower($graduado[$key]['modalidad']));
            //                     $modalidad = $graduado[$key]['modalidad'];
            //                     $b = false;

            //                     foreach ($fme as $key => $mod) {
            //                         if ($mod->nombre == $modalidad) $b = true;
            //                     }

            //                     if (!$b) $fgrado->modalidadesEstudio()->attach(ModalidadEstudio::where('nombre', $modalidad)->first()->id);
            //                 }

            //                 $pgrado = new ProcesoGrado();
            //                 $pgrado->idEstudiante = $est->id;
            //                 $pgrado->idFecha = $fgrado->id;
            //                 $pgrado->estado_ficha = 1;
            //                 $pgrado->estado_encuesta = 1;
            //                 $pgrado->estado_secretaria = 1;
            //                 $pgrado->estado_programa = 1;
            //                 $pgrado->save();

            //                 if ($key != 0) $estudios .= ', ';
            //                 $estudios .= $estudiante['nombrePrograma'];
            //             }

            //             $contenido = 'Buen dia,
            //                           <br>
            //                           <br>
            //                           <div style="font-size:1.2em;">
            //                           Se asoció un usuario al graduado '.$persona->nombres.' '.$persona->apellidos.' para el/los estudio(s) de '.$estudios.'.
            //                           <br>
            //                           <br>
            //                           Para iniciar sesión, el usuario es <b>'.$persona->identificacion.'</b> y la clave es <b>'.explode(' ', $persona->nombres)[0].$vector[$num1].$persona->identificacion.$vector[$num2].'</b>
            //                           </div>
            //                           <br><br>
            //                           <a href="http://sil.unimagdalena.edu.co">Más detalle en: sil.unimagdalena.edu.co</a>
            //                           <br><br>
            //                           Gracias por atender a nuestros mensajes
            //                           <br><br>
            //                           Atentamente,
            //                           <br><br>
            //                           <b>Econ. Esp. IVIS ALVARADO MONTENEGRO </b><br>
            //                           Directora Centro de Egresados <br>
            //                           Universidad del Magdalena';

            //             Mail::send('emails.registrosil', ['dependencia' => 'Centro de Egresados', 'contenido'=>$contenido],function ($m) use ($persona, $estudios) {
            //                 $m->from('sil2@unimagdalena.edu.co', env('MAIL_FROM'));
            //                 $m->to($persona->correo, $persona->nombres.' '.$persona->apellidos)->subject('Registro en plataforma SIL');
            //             });

            //             return [
            //                 'type'=>'success',
            //                 'title'=>'Exito',
            //                 'content'=>'Se ha creado un usuario para '.$persona->nombres.' '.$persona->apellidos.'.'
            //             ];
            //         }
            //         else {
            //             return ['type'=> 'error','title'=>'Error', 'content'=>'No se encontró registros con esta identificación.'];
            //         }
            //     }
            // }

            // $estudiantes = Estudiante::where('idPersona', $persona->id)
            //                          ->whereHas('gettipo', function ($query) {
            //                                 $query->where('idTipo', '=', TipoEstudiante::where('nombre', 'Graduado')->first()->id);
            //                            })->get();
            // if(sizeof($estudiantes) == 0)
            // {
            //     return ['type'=> 'error','title'=>'Error!', 'content'=>'Usted no es graduado'];
            // }
            // else
            // {
            //     foreach ($estudiantes as $item)
            //     {
            //         $usuario = User::where('identificacion', $item->codigo)->first();
            //         $usuario->activo = false;
            //         $usuario->save();
            //     }

            //     $usuario = User::where('identificacion', $request->identificacion)->first();
            //     if(sizeof($usuario) == 0)
            //     {
            //         $num1 = rand(0, 6);
            //         $num2 = rand(0, 6);
            //         $num3 = rand(0, 9);
            //         $num4 = rand(0, 9);
            //         $num5 = rand(0, 9);
            //         $num6 = rand(0, 9);

            //         $new = new User();
            //         $new->idPersona = $persona->id;
            //         $new->idRol = Rol::where('nombre', 'Graduado')->first()->id;
            //         $new->password = Hash::make(explode(' ', $persona->nombres)[0].$vector[$num1].$persona->identificacion.$vector[$num2]);
            //         $new->identificacion = $persona->identificacion;
            //         $new->activo = true;
            //         $new->codigo_verificacion = $num3.$num4.$num5.$num6;
            //         $new->save();
            //     }
            // }

            // $contenido = 'Buen dia,
            //               <br>
            //               <br>
            //               <div style="font-size:1.2em;">
            //               Se asoció un usuario al graduado; para iniciar sesión el usuario es <b>'.$new->identificacion.'</b> y la clave es <b>'.explode(' ', $persona->nombres)[0].$vector[$num1].$persona->identificacion.$vector[$num2].'</b>
            //               </div>
            //               <br><br>
            //               <a href="http://sil.unimagdalena.edu.co">Más detalle en: sil.unimagdalena.edu.co</a>
            //               <br><br>
            //               Gracias por atender a nuestros mensajes
            //               <br><br>
            //               Atentamente,
            //               <br><br>
            //               <b>Econ. Esp. IVIS ALVARADO MONTENEGRO </b><br>
            //               Directora Centro de Egresados <br>
            //               Universidad del Magdalena';

            // //dd($new->getuser);
            // Mail::send('emails.registrosil', ['dependencia' => 'Centro de Egresados', 'contenido'=>$contenido], function ($m) use ($new) {
            //     $m->from('sil2@unimagdalena.edu.co', env('MAIL_FROM'));
            //     $m->to($new->getuser->correo, $new->getuser->nombres.' '.$new->getuser->apellidos)->subject('Registro exitoso');
            // });

            // $correo = $this->cifrarCorreo($persona->correo);

            // return ['type'=> 'success','title'=>'Éxito', 'content'=>'Graduado registrado exitosamente. Se envió su usuario y contraseña al
            // correo '.$correo.'.'];

            // $ws = new WebServiceSieg();
            // $data = [
            //     'tipoDocumento' =>  $request->tipodoc['abrv'],
            //     'numeroDocumento' =>$request->identificacion,
            //     'token' => strtoupper(md5($ws->token($request->identificacion)))
            // ];

            // $graduado = json_decode(json_encode($ws->call('getInformacionGraduadoByDocumentoIdentidad',[$data])), true)['return'];
            // if (!isset($graduado[0])) $graduado = [$graduado];

            // $ws = new WebServiceSieg();
            // $data = [
            //     'codigo' => $graduado[0]['codigoEstudiantil'],
            //     'token'   => strtoupper(md5($ws->token($graduado[0]['codigoEstudiantil']))),
            // ];
            // $estudiante = json_decode(json_encode($ws->call('getInfoEstudianteByCodigo',[$data])), true)['return'];

            // return compact('graduado', 'estudiante');

            $insert = false;
            $rol_graduado = Rol::where('nombre', 'Graduado')->first();
            $usuario = User::whereHas('getuser', function ($per) use ($request) {
                $per->where('identificacion', $request->identificacion);
            })->first();

            if ($usuario) {
                if (!$usuario->roles()->where('nombre', 'Graduado')->first()) $usuario->roles()->attach($rol_graduado->id, ['activo' => 1]);
                $insert = true;
            } else {
                $persona = Persona::where('identificacion', $request->identificacion)->first();

                if ($persona && !$persona->correo_institucional) {
                    // NO TIENE CORREO INSTITUCIONAL
                } else if (!$persona) {
                    $ws = new WebServiceSieg();
                    $data = [
                        'tipoDocumento' => $request->tipodoc['abrv'],
                        'numeroDocumento' => $request->identificacion,
                        'token' => strtoupper(md5($ws->token($request->identificacion)))
                    ];

                    $res = json_decode(json_encode($ws->call('getInformacionGraduadoByDocumentoIdentidad', [$data])), true)['return'];
                    if (!isset($res[0])) $res = [$res];

                    foreach ($res as $kgrad => $grad) {
                        if ($grad['situacionAcademica'] == 'GRADUADO') {
                            $ws = new WebServiceSieg();
                            $data = [
                                'codigo' => $grad['codigoEstudiantil'],
                                'token'   => strtoupper(md5($ws->token($grad['codigoEstudiantil']))),
                            ];

                            $est = json_decode(json_encode($ws->call('getInfoEstudianteByCodigo', [$data])), true)['return'];
                            $persona = Persona::where('identificacion', $grad['numeroDocumento'])->first();

                            if (!$persona) {
                                $ciud_nacimiento = Municipio::where('nombre', $grad['ciudad'])
                                    ->whereHas('getdepartamento', function ($dep) use ($grad) {
                                        $dep->where('nombre', $grad['departamento']);
                                    })->first();
                                $ciud_cedula = Municipio::where('nombre', $grad['ciudadCedula'])
                                    ->whereHas('getdepartamento', function ($dep) use ($grad) {
                                        $dep->where('nombre', $grad['depCedula']);
                                    })->first();
                                $ciud_residencia = Municipio::where('nombre', $grad['ciudadResidencia'])
                                    ->whereHas('getdepartamento', function ($dep) use ($grad) {
                                        $dep->where('nombre', $grad['depResidencia']);
                                    })->first();
                                $genero = Genero::where('nombre', 'like', $est['genero'] . '%')->first();
                                $tipo_documento = TipoDocumento::where('abrv', $est['tipoDocumento'])->first();

                                $persona = new Persona();
                                $persona->apellidos = $grad['apellidos'];
                                $persona->ciudadOrigen = $ciud_nacimiento->id;
                                $persona->ciudadExpedicion = $ciud_cedula->id;
                                $persona->ciudadResidencia = $ciud_residencia->id;
                                $persona->etnia = $grad['etnia'];
                                $persona->nombres = $grad['nombres'];
                                $persona->identificacion = $grad['numeroDocumento'];
                                $persona->celular = $est['celular'];
                                $persona->direccion = $est['direccion'];
                                $persona->correo = $est['email'];
                                // $persona->correo_institucional = $est['email'];
                                $persona->estrato = $est['estrato'];
                                $persona->fechaNacimiento = $est['fecNacimiento'];
                                $persona->fechaNacimiento = $est['fecNacimiento'];
                                $persona->idGenero = $genero->id;
                                $persona->telefono_fijo = $est['telefono'];
                                $persona->tipodoc = $tipo_documento->id;
                                $persona->save();
                            }

                            if (!$persona->gethojadevida) {
                                $hoja_vida = new Hojavida();
                                $hoja_vida->idPersona = $persona->id;
                                $hoja_vida->perfil = '-';
                                $hoja_vida->activa = 1;
                                $hoja_vida->laborando = 0;
                                $hoja_vida->save();
                            }

                            $est_ins = false;
                            foreach ($persona->getestudiantes as $kest => $est1) {
                                if ($est1->getprograma->getprograma->nombre == $est['nombrePrograma']) $est_ins = true;
                            }

                            if (!$est_ins) {
                                $zonal = Municipio::where('nombre', $est['zonal'] == 'NO DEFINIDO' ? 'SANTA MARTA' : $est['zonal'])->first();
                                $tipo_estudiante = TipoEstudiante::where('nombre', 'Graduado')->first();
                                $dm = DependenciaModalidad::whereHas('getprograma', function ($prog) use ($est) {
                                    $prog->where('nombre', $est['nombrePrograma']);
                                })
                                    ->whereHas('getmodalidad', function ($mod) use ($est) {
                                        $mod->where('abrv', $est['modalidad']);
                                    })
                                    ->whereHas('jornada', function ($jor) use ($est) {
                                        $jor->where('nombre', $est['jorPrograma']);
                                    })->first();

                                $estudiante = new Estudiante();
                                $estudiante->idPersona = $persona->id;
                                $estudiante->idPrograma = $dm->id;
                                $estudiante->codigo = $est['codigo'];
                                $estudiante->idTipo = $tipo_estudiante->id;
                                $estudiante->folio = $grad['folio'];
                                $estudiante->acta = $grad['acta'];
                                $estudiante->libro = $grad['libro'];
                                $estudiante->idZonal = $zonal->id;
                                $estudiante->save();

                                $fecha_grado = FechasGrado::where('fecha_grado', implode('-', array_reverse(explode('/', $grad['fechaGrado']))))->first();

                                if (!$fecha_grado) {
                                    setlocale(LC_TIME, 'Spanish');
                                    Carbon::setUtf8(true);
                                    $fecha = new Carbon($grad['fechaGrado']);
                                    $tipo_grado = TipoGrado::where('nombre', 'like', '%' . $grad['tipoDeGruadiacion'] . '%')->first();

                                    $fecha_grado = new FechasGrado();
                                    $fecha_grado->nombre = $grad['tipoDeGruadiacion'] . ' - ' . $fecha->formatLocalized('%A %d %B %Y');
                                    $fecha_grado->anio = $fecha->year;
                                    $fecha_grado->tipo_grado = $tipo_grado->id;
                                    $fecha_grado->estado = 0;
                                    $fecha_grado->fecha_grado = $fecha;
                                    $fecha_grado->save();
                                }

                                $proceso_grado = new ProcesoGrado();
                                $proceso_grado->idEstudiante = $estudiante->id;
                                $proceso_grado->idFecha = $fecha_grado->id;
                                $proceso_grado->estado_ficha = 1;
                                $proceso_grado->estado_encuesta = 1;
                                $proceso_grado->estado_secretaria = 1;
                                $proceso_grado->estado_programa = 1;
                                $proceso_grado->save();
                            }
                        }
                    }
                }

                $usuario = new User();
                $usuario->idPersona = $persona->id;
                $usuario->identificacion = explode('@', $persona->correo_institucional)[0];
                $usuario->save();

                $usuario->roles()->attach($rol_graduado->id, ['activo' => 1]);
                $insert = true;
            }

            if ($insert) {
                $contenido = 'Buen dia,
                    <br>
                    <br>
                    <div style="font-size:1.2em;">
                    Se asoció un usuario al graduado; para iniciar sesión el usuario es <b>' . $usuario->identificacion . '</b>
                    </div>
                    <br><br>
                    <a href="http://sil.unimagdalena.edu.co">Más detalle en: sil.unimagdalena.edu.co</a>
                    <br><br>
                    Gracias por atender a nuestros mensajes
                    <br><br>
                    Atentamente,
                    <br><br>
                    <b>Econ. Esp. IVIS ALVARADO MONTENEGRO </b><br>
                    Directora Centro de Egresados <br>
                    Universidad del Magdalena';

                //dd($new->getuser);
                Mail::send('emails.registrosil', ['dependencia' => 'Centro de Egresados', 'contenido' => $contenido], function ($m) use ($usuario) {
                    $m->from('sil2@unimagdalena.edu.co', env('MAIL_FROM'));
                    $m->to($usuario->getuser->correo, $usuario->getuser->nombres . ' ' . $usuario->getuser->apellidos)->subject('Registro exitoso');
                });

                return ['type' => 'success', 'title' => 'Éxito', 'content' => 'Registro exitoso, su usuario es ' . $usuario->identificacion . '.'];
            } else return ['type' => 'error', 'title' => 'Error', 'content' => 'No se registró.'];
        } else if ($request->rol['nombre'] == 'Empresa') {
            $empresa = Empresa::where('nit', $request->nit)->first();
            if ($empresa) {
                $eeAceptada = EstadoEmpresas::where('nombre', 'ACEPTADA')->first()->id;
                if ($empresa->estadoSil == $eeAceptada || $empresa->estadoDipro == $eeAceptada) {
                    return ['title' => 'Error', 'content' => 'La empresa con el nit que quiere registrar ya está registrada y aprobada en la plataforma.', 'type' => 'error'];
                } else {
                    $archivo = $request->file('file_nit');
                    if ($archivo->getError() > 0) {
                        return ['title' => 'Error', 'content' => 'El nit debe ser un archivo PDF y debe pesar máximo 1MB', 'type' => 'error'];
                    }

                    if ($archivo->getMimeType() == 'application/pdf' && $archivo->getSize() <= 1048576) {
                        $fecha = \Carbon\Carbon::now();
                        $file_nit = 'NIT_' . $request->nit . '.pdf';
                        \Storage::disk('empresas')->put($file_nit, \File::get($archivo));
                    }

                    $representante = Persona::where('identificacion', $request->identificacion_representante)->first();
                    if (!$representante) {
                        $representante = new Persona();
                        $representante->nombres = $request->nombres_representante;
                        $representante->apellidos = $request->apellidos_representante;
                        $representante->idTipoDoc = $request->tipodoc_representante['id'];
                        $representante->identificacion = $request->identificacion_representante;
                        $representante->correo = $request->correo_representante;
                        $representante->save();
                    }

                    $empresa->nit = $request->nit;
                    $empresa->nombre = $request->empresa;
                    $empresa->idPersona = $representante->id;
                    $empresa->idTipoNit = $request->tipoNit['id'];
                    $empresa->idTipoEmpleador = $request->tipoEmpleador['id'];
                    $empresa->idActividadEconomica = $request->actividad['id'];
                    $empresa->estadoSil = EstadoEmpresas::where('nombre', 'POR APROBAR')->first()->id;
                    $empresa->paginaWeb = $request->pagina;
                    $empresa->file_nit = $file_nit;
                    $empresa->motivo_cancelacion = null;
                    $empresa->save();

                    $sede = Sede::find($empresa->getsedes[0]->id);
                    $sede->idMunicipio = $request->municipio['id'];
                    $sede->direccion = $request->direccion;
                    $sede->idEmpresa = $empresa->id;
                    $sede->idTipoSede = TipoSedes::where('nombre', 'PRINCIPAL')->first()->id;
                    $sede->telefono = $request->telefono;
                    $sede->correo = $request->email;
                    $sede->save();

                    $persona = Persona::where('identificacion', $request->identificacion)->first();
                    if (!$persona) {
                        $persona = new Persona();
                        $persona->nombres = $request->nombres;
                        $persona->apellidos = $request->apellidos;
                        $persona->correo = $request->correo;
                        $persona->celular = $request->celular;
                        $persona->idTipoDoc = $request->tipo_documento['id'];
                        $persona->identificacion = $request->identificacion;
                        $persona->save();
                    }

                    $rol_empresa = Rol::where('nombre', 'Empresa')->first();

                    $usuario = User::find($sede->getusuarios[0]->id);
                    $usuario->idPersona = $persona->id;
                    $usuario->password = \Hash::make($request->password);
                    $usuario->idSede = $sede->id;
                    $usuario->identificacion = $request->nit;
                    $usuario->save();

                    $usuario->roles()->attach($rol_empresa->id, ['activo' => 1]);

                    return [
                        'type' => 'success',
                        'title' => 'Registro exitoso!',
                        'content' => 'Empresa registrada con exito. Su usuario es su identificación'
                    ];
                }
            } else {
                $archivo = $request->file('file_nit');
                // return \File::get($archivo);
                if ($archivo->getError() > 0) {
                    return ['title' => 'Error', 'content' => 'El nit debe ser un archivo PDF y debe pesar máximo 1MB', 'type' => 'error'];
                }

                if ($archivo->getMimeType() == 'application/pdf' && $archivo->getSize() <= 1048576) {
                    $fecha = \Carbon\Carbon::now();
                    $file_nit = 'NIT_' . $request->nit . '.pdf';
                    \Storage::disk('empresas')->put($file_nit, \File::get($archivo));
                }
                $representante = Persona::where('identificacion', $request->identificacion_representante)->first();
                if ($representante == null || !(sizeof($representante) > 0)) {
                    $representante = new Persona();
                    $representante->nombres = $request->nombres_representante;
                    $representante->apellidos = $request->apellidos_representante;
                    $representante->idTipoDoc = $request->tipodoc_representante['id'];
                    $representante->identificacion = $request->identificacion_representante;
                    $representante->correo = $request->correo_representante;
                    $representante->save();
                }

                $empresa = new Empresa();
                $empresa->nit = $request->nit;
                $empresa->nombre = $request->empresa;
                $empresa->idPersona = $representante->id;
                $empresa->idTipoNit = $request->tipoNit['id'];
                $empresa->idTipoEmpleador = $request->tipoEmpleador['id'];
                $empresa->idActividadEconomica = $request->actividad['id'];
                $empresa->estadoDipro = EstadoEmpresas::where('nombre', 'POR APROBAR')->first()->id;
                $empresa->estadoSil = EstadoEmpresas::where('nombre', 'POR APROBAR')->first()->id;
                $empresa->paginaWeb = $request->pagina;
                $empresa->file_nit = $file_nit;
                $empresa->save();

                $sede = new Sede();
                $sede->idMunicipio = $request->municipio['id'];
                $sede->direccion = $request->direccion;
                $sede->idEmpresa = $empresa->id;
                $sede->idTipoSede = TipoSedes::where('nombre', 'PRINCIPAL')->first()->id;
                $sede->telefono = $request->telefono;
                $sede->correo = $request->email;
                $sede->save();

                $persona = Persona::where('identificacion', $request->identificacion)->first();
                if ($persona == null || !(sizeof($persona) > 0)) {
                    $persona = new Persona();
                    $persona->nombres = $request->nombres;
                    $persona->apellidos = $request->apellidos;
                    $persona->correo = $request->correo;
                    $persona->celular = $request->celular;
                    $persona->idTipoDoc = $request->tipo_documento['id'];
                    $persona->identificacion = $request->identificacion;
                    $persona->save();
                }

                $rol_empresa = Rol::where('nombre', 'Empresa')->first();
                $usuario = new User();
                $usuario->idPersona = $persona->id;
                $usuario->password = \Hash::make($request->password);
                $usuario->idSede = $sede->id;
                $usuario->identificacion = $request->nit;
                $usuario->save();

                $usuario->roles()->attach($rol_empresa->id, ['activo' => 1]);

                return ['type' => 'success', 'title' => 'Registro exitoso!', 'content' => 'Empresa registrada con exito. Su usuario es su identificación'];
            }
        }
    }

    public function postLogin(LoginRequest $request)
    {
        $log = false;
        $roles_permitidos = ['Empresa', 'Administrador Egresados', 'Graduado'];

        $usuario = User::where('identificacion', $request->identificacion)->first();

        if (!$usuario) return ['type' => 'error', 'title' => 'Error', 'content' => 'No existe el usuario.'];

        $roles = $request->rol ? [Rol::find($request->rol['id'])] : $usuario->roles()->whereIn('nombre', $roles_permitidos)->get();

        if (count($roles) == 0) return ['type' => 'error', 'title' => 'Error', 'content' => 'No existe el usuario.'];
        else if (count($roles) > 1) {
            $res = [];
            foreach ($roles as $krol => $rol) {
                array_push($res, ['id' => (int) $rol->id, 'nombre' => $rol->nombre]);
            }

            return ['type' => 'pendiente', 'roles' => $res];
        } else {
            $rol = $roles[0];

            if (in_array($rol->nombre, ['Administrador Egresados', 'Graduado'])) $log = $this->authldap($request->identificacion, $request->password);
            else {
                $emp = $usuario->getsede->getempresa;

                if ($emp->getestadosil->nombre == 'POR APROBAR') return ['type' => 'error', 'title' => 'Error!', 'content' => 'Su empresa aún no ha sido aprobada'];
                else if ($usuario->getsede->getempresa->getestadosil->nombre == 'RECHAZADA')
                    return ['type' => 'error', 'title' => 'Error!', 'content' => 'Su empresa ha sido rechazada. Se le envió el motivo de cancelación al correo especificado en la información de contacto, por favor, regístrese nuevamente.'];

                $log = Hash::check($request->password, $usuario->password);
            }

            if ($log) {
                Session()->put('rol', $rol);
                Auth::login($usuario);
                return ['type' => 'success', 'title' => '', 'content' => ''];
            } else return ['type' => 'error', 'title' => 'Error', 'content' => 'Credenciales incorrectas'];
        }

        // return $usuario;
        if (!isset($usuario) || sizeof($usuario) == 0)
            return ['type' => 'error', 'title' => 'Error!', 'content' => 'Credenciales incorrectas'];

        if (!$usuario->activo) return ['type' => 'error', 'title' => 'Error!', 'content' => 'Usuario no activo'];

        if (
            $usuario->getrol->nombre == 'Coordinador' || $usuario->getrol->nombre == 'Tutor' || $usuario->getrol->nombre == 'Coordinador de programa' || $usuario->getrol->nombre == 'Ori' || $usuario->getrol->nombre == 'Juridica' || $usuario->getrol->nombre == 'Administrador Dippro'
            || $usuario->getrol->nombre == 'Administrador Egresados'
        ) {

            $log = $this->authldap($request->identificacion, $request->password);
            // return $log ? 'true':'false';
        } else if ($usuario->getrol->nombre == 'Jefe inmediato' || $usuario->getrol->nombre == 'Empresa' || $usuario->getrol->nombre == 'Graduado') {
            if ($usuario->getrol->nombre == 'Empresa' || $usuario->getrol->nombre == 'Jefe inmediato') {
                if ($usuario->getsede->getempresa->getestadodipro->nombre == 'POR APROBAR' && $usuario->getsede->getempresa->getestadosil->nombre == 'POR APROBAR') {
                    return ['type' => 'error', 'title' => 'Error!', 'content' => 'Su empresa aún no ha sido aprobada'];
                } else if ($usuario->getsede->getempresa->getestadosil->nombre == 'RECHAZADA') {
                    return ['type' => 'error', 'title' => 'Error!', 'content' => 'Su empresa ha sido rechazada. Se le envió el motivo de cancelación al correo especificado en la información de contacto, por favor, regístrese nuevamente.'];
                }
            }
            $log = Hash::check($request->password, $usuario->password);
            //dd(Hash::make($request->password), $usuario->password);
        } else if ($usuario->getrol->nombre == 'Estudiante') {
            $estudiante = Estudiante::where('codigo', $request->identificacion)->first();

            $ws = new WebServiceSieg();

            $data = [
                'codigo' => $request->identificacion,
                'password' => strtoupper(md5($request->password)),
                'token' => strtoupper(md5($ws->token($request->identificacion))),
            ];

            $log = json_decode(json_encode($ws->call('login', [$data])), true)['return'];
        }

        if ($log) {
            if ($usuario->activo) {
                Auth::login($usuario);
                return ['type' => 'success', 'title' => '', 'content' => ''];
            } else {
                return ['type' => 'error', 'title' => 'Error!', 'content' => 'Este usuario no existe en el sistema'];
            }
        } else {
            return ['type' => 'error', 'title' => 'Error!', 'content' => 'Credenciales incorrectas'];
        }
    }

    public function getFormularioregistro()
    {

        $datos = array();

        $roles = [ //'0'=>['id'=>'1', 'nombre'=>'Estudiante'],
            '0' => ['id' => '2', 'nombre' => 'Graduado'],
            '1' => ['id' => '3', 'nombre' => 'Empresa']
        ];
        $tipoNit = TipoNit::get();
        $tipoEmpleador = TipoEmpleador::get();
        $actividades = ActividadesEconomicas::get();
        $paises = Pais::select('id', 'nombre')->get();
        $tipoEstudiantes = TipoEstudiante::whereIn('nombre', ['Preprácticas', 'Prácticas', 'Prácticas y preprácticas'])->get();
        $tipoDocs = TipoDocumento::whereIn('nombre', ['Cédula de ciudadanía', 'Documento extranjero', 'Cédula extranjería'])->get();
        $datos['roles'] = $roles;
        $datos['tipoNit'] = $tipoNit;
        $datos['tipoEmpleador'] = $tipoEmpleador;
        $datos['actividades'] = $actividades;
        $datos['paises'] = $paises;
        $datos['tiposDocs'] = $tipoDocs;
        $datos['tipoEstudiantes'] = $tipoEstudiantes;
        $datos['tipodocs'] = TipoDocumento::get();

        return $datos;
    }

    public function getDepartamentos($idPais)
    {
        $departamentos = Departamento::with('getpais')
            ->where('idPais', $idPais)
            ->get();
        return $departamentos;
    }

    public function getMunicipios($idDpto)
    {
        $municipios = Municipio::with('getdepartamento.getpais')
            ->where('idDepartamento', $idDpto)
            ->get();
        return $municipios;
    }

    public function getModalidades()
    {
        $modalidades = Modalidad::get();
        return $modalidades;
    }

    public function getCartas()
    {
        return view('home.cartas');
    }

    public function getValidar($codigo)
    {
        $carta = Carta::where('codigo_verificacion', $codigo)->first();
        if ($carta == null) {
            return "<center style='color:red;'>No existe la carta de presentación identificada con este código</center>";
        }

        $nombre = $carta->nombre_archivo;

        $path = storage_path('app/carta_presentacion/' . $nombre);

        header("Content-type: application/pdf");
        header("Content-Disposition: inline; filename=filename.pdf");
        @readfile($path);

        // return \Response::render($path);
    }

    private function asteriscos($cantidad)
    {
        $str = '';

        for ($i = 0; $i < $cantidad; $i++) {
            $str = $str . '*';
        }
        return $str;
    }

    private function cifrarCorreo($var)
    {
        $vector = explode('@', $var);

        $vector2 = explode('.', $vector[1]);

        $t11 = strlen($vector[0]);

        if ($t11 >= 10)
            $t12 = 3;
        else if ($t11 >= 5)
            $t12 = 2;
        else
            $t12 = round($t11 / 2, 0, PHP_ROUND_HALF_DOWN);

        $t13 = $t11 - $t12;

        $texR = substr($vector[0], $t12, $t11 - 1);

        $a1 = $this->asteriscos($t13);

        $texN = str_replace($texR, $a1, $vector[0]);

        $t21 = strlen($vector2[0]);
        $texR2 = substr($vector2[0], 1, $t21 - 1);
        $a2 = $this->asteriscos($t21 - 1);
        $texN2 = str_replace($texR2, $a2, $vector2[0]);

        $correo = $texN . '@' . $texN2 . '.' . $vector2[1];

        return $correo;
    }

    public function getCambiarclave()
    {
        return view('home.cambiarclave');
    }

    public function postCambiarclave(ClaveRequest $request)
    {
        if (Hash::check($request->actual, Auth::user()->password)) {
            $user = User::find(Auth::user()->id);
            $user->password = Hash::make($request->nueva);
            $user->codigo_verificacion = null;
            $user->save();
            return ['type' => 'success', 'title' => 'Éxito', 'content' => 'Clave cambiada con éxito'];
        } else {
            return ['type' => 'error', 'title' => 'Error!', 'content' => 'Su antigua clave es incorrecta'];
        }
    }

    public function postGenerarclave(ClaveRequest $request)
    {
        $user = User::find(Auth::user()->id);
        $user->password = Hash::make($request->nueva);
        $user->codigo_verificacion = null;
        $user->save();
        return ['type' => 'success', 'title' => 'Éxito', 'content' => 'Clave cambiada con éxito'];
    }


    public function getPrueba($var = null)
    {
        $codigo = '1082906733';
        //$codigo='1082861946';

        $ws = new WebServiceSieg();
        $data = [
            'tipoDocumento' => 'C.C.',
            'numeroDocumento' => $codigo,
            'token'   => strtoupper(md5($ws->token($codigo)))
        ];
        $estudiante = json_decode(json_encode($ws->call('getInformacionGraduadoByDocumentoIdentidad', [$data])), true);

        if (isset($estudiante['return'])) {
            $estudiante = $estudiante['return'];
        }

        //dd(isset($estudiante[0]));

        $codigo = $estudiante['codigoEstudiantil'];

        $ws = new WebServiceSieg();
        $data = [
            'codigo' => $codigo,
            'token'   => strtoupper(md5($ws->token($codigo))),
        ];
        $estudiante = json_decode(json_encode($ws->call('getInfoEstudianteByCodigo', [$data])), true);

        if (isset($estudiante['return'])) {
            $estudiante = $estudiante['return'];
        }

        $dep = DependenciaModalidad::where('idPrograma', Dependencia::where('nombre', $estudiante['nombrePrograma'])->first()->id)
            ->where('idFacultad', Dependencia::where('nombre', $estudiante['facultad'])->first()->id)
            ->first();


        //return sizeof($estudiante);

        dd($dep->id, $estudiante);


        // $new = User::where('identificacion', '1082983016')->first();
        // $contenido = 'Buen dia,
        //                   <br>
        //                   <br>
        //                   Se asoció un usuario al graduado; para iniciar sesión el usuario es '.$new->identificacion.' y la clave es '.$new->password.'
        //                   <br><br>
        //                   <a href="http://sil.unimagdalena.edu.co">Más detalle en: sil.unimagdalena.edu.co</a>
        //                   <br><br>
        //                   Gracias por atender a nuestros mensajes
        //                   <br><br>
        //                   Atentamente,
        //                   <br><br>
        //                   Econ. Esp. IVIS ALVARADO MONTENEGRO
        //                   Centro de Egresados
        //                   Universidad del Magdalena';
        // $dependencia = 'Centro de egresados';
        // //return view('emails.registrosil', compact('contenido', 'dependencia'));
        // // Mail::send('emails.registrosil', ['dependencia' => 'Centro de Egresados', 'contenido'=>$contenido], function ($m) use ($new) {
        // //     $m->from(env('MAIL_USERNAME'), env('MAIL_FROM'));
        // //     $m->to("rafa.pineda86@gmail.com", $new->getuser->nombres.' '.$new->getuser->apellidos)->subject('Registro exitoso');
        // //     //dd($m);
        // // });

        // Mail::send('emails.registrosil', ['dependencia' => 'Centro de Egresados', 'contenido'=>$contenido], function ($m) use ($new) {
        //     $m->from('sil2@unimagdalena.edu.co', env('MAIL_FROM'));
        //     $m->to($new->getuser->correo, $new->getuser->nombres.' '.$new->getuser->apellidos)->subject('Registro exitoso');
        // });


        // return "Prueba dd";

        // $contenido = 'Buen dia,
        //                   <br>
        //                   <br>
        //                   Se asoció un usuario al graduado; para iniciar sesión el usuario es '.$new->identificacion.' y la clave es '.$new->password.'
        //                   <br><br>
        //                   <a href="http://sil.unimagdalena.edu.co">Más detalle en: sil.unimagdalena.edu.co</a>
        //                   <br><br>
        //                   Gracias por atender a nuestros mensajes
        //                   <br><br>
        //                   Atentamente,
        //                   <br><br>
        //                   Esp. IVIS ALVARADO MONTENEGRO
        //                   Centro de Egresados
        //                   Universidad del Magdalena';



        // $texto = 'Prueba de correo';
        // Mail::raw($texto, function ($message) use ($new) {
        //     $message->from('sil2@unimagdalena.edu.co',"Lo quesea");
        //     $message->to($new->getuser->correo, $new->getuser->nombres.' '.$new->getuser->apellidos)->subject('Registro exitoso');

        // });

        // return view('emails.registrosil');



        // $vector = ['@', '-', '/', '_', '&', '%', '$'];

        // $num1 = rand(0, 6);
        // $num2 = rand(0, 6);

        // $num3 = rand(0, 9);
        // $num4 = rand(0, 9);
        // $num5 = rand(0, 9);
        // $num6 = rand(0, 9);

        // dd($num3.$num4.$num5.$num6);

        // $ws = new WebServiceSieg();

        // $data =[
        //     'tipoDocumento'=>'C.C.',
        //     'numeroDocumento'=>'1082861946',
        //     'token'=>strtoupper(md5($ws->token('1082861946'))),
        // ];
        // //'password'=>strtoupper(md5('THISlove_PS2')),

        // $log = json_decode(json_encode($ws->call('getInformacionGraduadoByDocumentoIdentidad',[$data])), true)['return'];

        // dd($log);
    }

    public function getClave($clave)
    {
        dd(Hash::make($clave));
    }
}
