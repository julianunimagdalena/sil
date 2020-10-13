<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Requests\ProyectoRequest;
use App\Http\Requests\VisitaRequest;

use App\Http\Controllers\Controller;
use DB;
use Auth;
use Mail;

use App\Models\ModalidadEstudiante;
use App\Models\PracticaTutor;
use App\Models\Visita;

class TutorController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('tutor');
    }
    
    public function getIndex()
    {
        return view('tutor.index');
    }
    
    public function getPracticantesjson()
    {
        $practicantes = PracticaTutor::with('getpractica.getestudiante.getpersona')
                                     ->with('getpractica.getestudiante.getprograma')
                                     ->with('getpractica.getvisitas')
                                     ->with('getpractica.getestudiante.getpostulaciones.getoferta.getsede.getempresa')
                                     ->where('activo', 1)
                                     ->where('idTutor', Auth::user()->id)
                                     ->get();
                                     
        $fecha_actual = \Carbon\Carbon::now();
        
        foreach ($practicantes as $practicante) 
        {
            $fecha_fin = \Carbon\Carbon::create(explode('-',$practicante->getpractica->fecha_fin)[0], explode('-',$practicante->getpractica->fecha_fin)[1], explode('-',$practicante->getpractica->fecha_fin)[2]);
                
            $practicante->mostrar = $fecha_fin->diffInDays($fecha_actual) < 15 || $fecha_fin < $fecha_actual;
        }
        return $practicantes;
    }
    
    public function getPracticantejson($id)
    {
        $practicante =  PracticaTutor::with('getpractica.getestudiante.getpersona')
                                     ->with('getpractica.getestudiante.getprograma')
                                     ->with('getpractica.getestudiante.getpostulaciones.getoferta.getsede.getempresa')
                                     ->with('getpractica.getestudiante.getpostulaciones.getoferta.getjefe.getuser')
                                     ->with('getpractica.getestudiante.getpostulaciones.getestadoestudiante')
                                     ->with('getpractica.getestudiante.getpostulaciones.getestadoempresa')
                                     ->where('activo', 1)
                                     ->where('id', $id)
                                     ->where('idTutor', Auth::user()->id)
                                     ->first();
        return $practicante;
    }
    
    public function postRegistrarvisita(VisitaRequest $request)
    {
        $practica = PracticaTutor::find($request->id)->getpractica;
        
        Visita::create(['idPractica'=>$practica->id, 'fecha_registro'=>\Carbon\Carbon::now(), 'tema'=>$request->tema, 'firma_tutor'=>true, 'fecha'=>$request->fecha, 'hora'=>$request->strHora]);
        
        
        $texto = 'Tiene una nueva visita registrada. Por favor ingrese a la plataforma de prácticas para confirmar esta visita.';
        $estudiante = $practica->getestudiante;
        Mail::raw($texto, function ($message) use ($estudiante) {
            $message->from('hello@app.com', env('MAIL_FROM'));

            $message->to($estudiante->getpersona->correo, $estudiante->getpersona->nombres.' '.$estudiante->getpersona->apellidos)->subject('Nueva visita');
            
        });
        
        return ['title'=>'Éxito', 'content'=>'Visita registrada con éxito', 'type'=>'success'];
    }
    
    public function getVisitasjson($id)
    {
        $practica = PracticaTutor::find($id)->getpractica->id;
        $practicas = ModalidadEstudiante::find($practica);
        $visitas = $practicas->getvisitas;
        return $visitas;
    }
    
    public function postProyectoimpacto(ProyectoRequest $request)
    {
        $practica = ModalidadEstudiante::find($request->id);
        $practica->proyecto_impacto = true;
        $practica->nombre_impacto = $request->nombre;
        $practica->save();
        return ['title'=>'Éxito', 'content'=>'Práctica clasificada como proyecto de impacto con éxito', 'type'=>'success'];
    }
}
