<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use App\Models\Estudiante;
use App\Models\Persona;
use App\Models\Referencia;
use App\Models\Rol;
use App\Models\TipoEstudiante;
use App\Models\User;
use App\Models\UsuarioRol;

use App\Http\Requests\GraduadoRequest;

class SilController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('admineg');
    } 

    public function getIndex()
    {
        return view('admin.sil.index');
    }   

    public function getHojasdevida() {
        return view('admin.sil.hojas');
    }

    public function getHojasdevidajson () {
        $res = [];
        $tipo_graduado = TipoEstudiante::where('nombre', 'Graduado')->first()->id;
        $graduados = Persona::whereHas('getestudiantes', function ($estudiante) use ($tipo_graduado) {
                                $estudiante->where('idTipo', $tipo_graduado);
                            })->whereHas('getusuario.roles', function ($query) {
                                $query->where('nombre', 'Graduado');
                            })->get();
        
        foreach ($graduados as $key => $graduado) {
            $programas = '';
            foreach ($graduado->getestudiantes as $key => $estudiante) {
                $programas .= $estudiante->getprograma['getprograma']['nombre'];
                if ($key != $graduado->getestudiantes->count()-1) $programas .= ', ';
            }

            array_push($res, [
                'id' => $graduado->id,
                'nombres' => $graduado->nombres,
                'apellidos' => $graduado->apellidos,
                'programas' => $programas
            ]);
        }

        return $res;
    }

    public function getVerperfil($idPersona)
    {
        return view('admin.sil.hoja', compact('idPersona'));
    }

    public function getHojajson($idEstudiante)
    {
        $persona = Persona::with('gethojadevida.getcompetencias')
                          ->with('gethojadevida.getestudios.getmunicipio.getdepartamento.getpais')
                          ->with('gethojadevida.getexperiencias.municipio.getdepartamento.getpais')
                          ->with('gethojadevida.getexperiencias.duracion')
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
                          ->where('id', $idEstudiante)
                          ->first();
        // return $persona;
        $persona->gethojadevida[0]->getreferenciasp = Referencia::where('idHoja', $persona->gethojadevida[0]->id)
                                                                ->whereNull('parentesco')
                                                                ->get();
        $persona->gethojadevida[0]->getreferenciasf = Referencia::with('getparentesco')
                                                                ->where('idHoja', $persona->gethojadevida[0]->id)
                                                                ->whereNotNull('parentesco')
                                                                ->get();

        return $persona;        
        
    }

    public function getUsuarios()
    {
        return view('admin.sil.usuarios');
    }

    public function getUsuariosjson()
    {
        $roles_permitidos = ['Graduado', 'Empresa'];
        $res = [];

        $usuarios = User::whereHas('roles', function ($rol) use ($roles_permitidos) {
                            $rol->whereIn('nombre', $roles_permitidos);
                        })->get();

        foreach ($usuarios as $kusr => $usr) {
            foreach ($usr->roles as $krol => $rol) {
                if (in_array($rol->nombre, $roles_permitidos)) {
                    array_push($res, [
                        'id' => $usr->id,
                        'identificacion' => $usr->identificacion,
                        'nombre' => $rol->nombre == 'Graduado' ? $usr->getuser->nombres.' '.$usr->getuser->apellidos : $usr->getsede->getempresa->nombre,
                        'correo' => $usr->getuser->correo,
                        'rol' => [ 'id' => $rol->id, 'nombre' => $rol->nombre ],
                        'activo' => $rol->pivot->activo
                    ]);
                }  
            }
        }

        return $res;
    }

    public function getUsuariojson($identificacion)
    {        
        $persona = Persona::with('getestudiantes.getprograma.getprograma')
                          ->where('identificacion', $identificacion)
                          ->first();
        
                
        
        if(sizeof($persona) > 0)
        {
            if($persona->fechaNacimiento!=null)
            {
                $persona->edad = \Carbon\Carbon::createFromDate(explode('-', $persona->fechaNacimiento)[0],explode('-', $persona->fechaNacimiento)[1],explode('-', $persona->fechaNacimiento)[2])->age;
            }

            $est = Estudiante::where('idPersona', $persona->id)
                             ->where('idTipo', TipoEstudiante::where('nombre', 'Graduado')->first()->id)
                             ->get();
            if(sizeof($est)==0)
            {
                $persona = new Persona();
                $persona->edad = 0;
            }
        }
        else   // buscar en admisiones 
        {
            $persona = new Persona();
            $persona->edad = 0;
        }
        
        
                        
        return $persona;
    }

    public function postSaveusuario(GraduadoRequest $request)
    {   
        $vector = ['@', '-', '/', '_', '&', '%', '$'];            

        $persona = Persona::where('identificacion', $request->identificacion)->first();

        if($persona == null)
        {
            return ['type'=> 'error','title'=>'Error!', 'content'=>'No se encontró registros con esta identificación.'];
        }

        $estudiantes = Estudiante::where('idPersona', $persona->id)
                                 ->where('idTipo', TipoEstudiante::where('nombre', 'Graduado')->first()->id)
                                 ->get();
        // return $estudiantes;
        if(sizeof($estudiantes) == 0)
        {
            return ['type'=> 'error','title'=>'Error!', 'content'=>'Usted no es graduado'];
        }
        else
        {
            foreach ($estudiantes as $item) 
            {
                $usuario = User::where('identificacion', $item->codigo)->first();
                $usuario->activo = false;
                $usuario->save();
            }

            $usuario = User::where('identificacion', $request->identificacion)->first();
            if(sizeof($usuario) == 0)
            {
                $num1 = rand(0, 6);
                $num2 = rand(0, 6);
                $num3 = rand(0, 9);
                $num4 = rand(0, 9);
                $num5 = rand(0, 9);
                $num6 = rand(0, 9);

                
                $new = new User();
                $new->idPersona = $persona->id;
                $new->idRol = Rol::where('nombre', 'Graduado')->first()->id;
                $new->password = \Hash::make(explode(' ', $persona->nombres)[0].$vector[$num1].$persona->identificacion.$vector[$num2]);
                $new->identificacion = $persona->identificacion;
                $new->activo = true;
                $new->codigo_verificacion = $num3.$num4.$num5.$num6;
                $new->save();
            }
        }

        $contenido = 'Buen dia,
                      <br>
                      <br>
                      <div style="font-size:1.2em;">
                      Se asoció un usuario al graduado; para iniciar sesión el usuario es <b>'.$new->identificacion.'</b> y la clave es <b>'.explode(' ', $persona->nombres)[0].$vector[$num1].$persona->identificacion.$vector[$num2].'</b>
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
        \Mail::send('emails.registrosil', ['dependencia' => 'Centro de Egresados', 'contenido'=>$contenido], function ($m) use ($new) {
            $m->from('egresados2@unimagdalena.edu.co', env('MAIL_FROM'));
            $m->to($new->getuser->correo, $new->getuser->nombres.' '.$new->getuser->apellidos)->subject('Registro exitoso');
        });             

        return ['type'=> 'success','title'=>'Éxito', 'content'=>'Graduado registrado exitosamente. Se envió su usuario y contraseña al 
        correo del graduado'];
    }

    public function getFormulariousuario()
    {
        return $roles = Rol::whereIn('nombre', ['Empresa', 'Graduado'])->get();
    }

    public function getActivar($id, $rol)
    {
        $ur = UsuarioRol::where('usuario_id', $id)->where('rol_id', $rol)->first();

        $content = "";

        if($ur->activo==1)
        {
            $ur->activo=0;
            $content = "Usuario desactivado con éxito";
        }
        else
        {
            $ur->activo=1;
            $content = "Usuario activado con éxito";
        }

        $ur->save();

        return[
            'title'=>'Éxito',
            'content'=>$content,
            'type'=>'success'
        ];

    }
}
