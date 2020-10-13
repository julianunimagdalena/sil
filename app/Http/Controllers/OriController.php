<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use App\Http\Requests\PracticaOriRequest;

use Auth;
use Mail;

use App\Models\Modalidad;
use App\Models\ModalidadEstudiante;
use App\Models\Rol;
use App\Models\User;

class OriController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('ori');
    }
    
    public function getIndex()
    {
        return view('ori.practicantes');
    }
    
    public function getPracticantesjson($id = null)
    {
        if($id == null)
        {
            $practicantes = ModalidadEstudiante::with('getmodalidad')
                                      ->with('getestudiante.getpersona')
                                      ->with('getestudiante.getpersona')
                                      ->with('getestudiante.getprograma')
                                      ->with('getestudiante.getpostulaciones.getoferta.getsede.getempresa')
                                      ->where('aprobacion_estudiante', true)
                                      ->whereIn('idModalidad', Modalidad::select('id')
                                                                        ->where('nombre', 'Prácticas internacionales')
                                                                        ->orWhere('nombre', 'Semestre en el exterior')
                                                                        ->get()
                                                                        ->toArray()
                                                )
                                      ->whereNull('estado_ori')
                                      ->get();
        }
        else 
        {
            $practicantes = ModalidadEstudiante::with('getmodalidad')
                                      ->with('getestudiante.getpersona')
                                      ->with('getestudiante.getpersona')
                                      ->with('getestudiante.getprograma')
                                      ->with('getestudiante.getpostulaciones.getoferta.getsede.getempresa')
                                      ->where('aprobacion_estudiante', true)
                                      ->where('id', $id)
                                      ->whereIn('idModalidad', Modalidad::select('id')
                                                                        ->where('nombre', 'Prácticas internacionales')
                                                                        ->orWhere('nombre', 'Semestre en el exterior')
                                                                        ->get()
                                                                        ->toArray()
                                                )
                                      ->first();
        }
            
        
        return $practicantes;
    }
    
    public function postAprobarpractica(PracticaOriRequest $request)
    {
        $practica = ModalidadEstudiante::find($request->id);
        // $practicas = ModalidadEstudiante::find();
        
        if($request->practica['estado'] == "1")
        {
            $practica->estado_ori = true;
            $practica->save();
        }
        else if($request->practica['estado'] == "2")
        {
            $practica->estado_ori = false;
            $practica->save();
            $texto = 'Los documentos del estudiante '.$practica->getestudiante->getpersona->nombres.
                     ' '.$practica->getestudiante->getpersona->apellidos.' con código '.$practica->getestudiante->codigo.
                     ',  tienen las siguientes observaciones:'."\n".$request->practica['observaciones'];
            
            $admin = User::where('idRol', Rol::where('nombre', 'Administrador Dippro')->first()->id)->first();
            
            Mail::raw($texto, function ($message) use ($admin) {
                $message->from('hello@app.com', env('MAIL_FROM'));
    
                $message->to($admin->getuser->correo, $admin->getuser->nombres.' '.$admin->getuser->apellidos)->subject('Estado práctica');
                
            });
        }
        else
        {
            return ['title'=>'Error', 'content'=>'Estado no válido', 'type'=>'error'];
        }
        
        return ['title'=>'Éxito', 'content'=>'Cambio de estado realizado con éxito', 'type'=>'success'];
    }
    
    public function getCartasolicitud($id)
    {
        $practica = ModalidadEstudiante::find($id);
        
        $nombre = $practica->file_carta_solicitud;
        
        $path = storage_path('app/legalizacion/'.$nombre);
        
        return \Response::download($path);
    }
    
    public function getCertificadoexistenciaprac($id)
    {
        $practica = ModalidadEstudiante::find($id);
        
        if($practica->getmodalidad->nombre == 'Prácticas internacionales' 
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
    
    public function getCartacolaboracion($id)
    {
        $practica = ModalidadEstudiante::find($id);
        
        if(($practica->getmodalidad->nombre == 'Prácticas internacionales' || $practica->getmodalidad->nombre == 'Semestre en el exterior') 
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
}
