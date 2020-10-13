<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use Auth;

use App\Models\Estudiante;
use App\Models\Evaluacion;
use App\Models\PracticaTutor;
use App\Models\Respuesta;
use App\Models\Rol;

class EvaluacionController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('val_eval', ['only'=>'postSaverespuestasevaluacion']);
        $this->middleware('estudiante', ['only'=>['getEvaluartutorbyestudiante', 'getAutoevaluacionestudiante']]);
        $this->middleware('jefe', ['only'=>['getEvaluarestudiantebyjefe']]);
    }
    
    // public function getIndex()
    // {
        
    //     $estudiante = null;
    //     if(Auth::user()->getrol->nombre == 'Estudiante')
    //     {
    //         $estudiante = Estudiante::where('codigo', Auth::user()->identificacion)->first();
    //     }
    //     $id=7;
    //     $evaluacion = Evaluacion::find(7);
    //     $evaluacion->idEvaluado = 19;
    //     return view('evaluacion.responder', compact('estudiante', 'id', 'evaluacion'));
    // }
    
    public function getEvaluartutorbyestudiante()
    {
        $evaluacion = Evaluacion::where('idRolevaluador', Rol::where('nombre', 'Estudiante')->first()->id)
                                ->where('idRolevaluado', Rol::where('nombre', 'Tutor')->first()->id)
                                ->first();
        if(sizeof($evaluacion)==0 || !$evaluacion->estado)
        {
            $msj = array(
                'error'=>'No es posible realizar esta evaluación'
            );
            return redirect('/estudiante/practicas')->with($msj);
        }
        
        $estudiante = null;
        if(Auth::user()->getrol->nombre == 'Estudiante')
        {
            $estudiante = Estudiante::where('codigo', Auth::user()->identificacion)->first();
        }
        $bool = false;
        foreach($estudiante->getpracticas as $p)
        {
            if($p->getestado->nombre=='Aprobada')
            {
                $bool = true;
                $fecha_fin = \Carbon\Carbon::create(explode('-',$p->fecha_fin)[0], explode('-',$p->fecha_fin)[1], explode('-',$p->fecha_fin)[2]);
                
                $fecha_actual = \Carbon\Carbon::now(); 
                
                if($fecha_fin->diffInDays($fecha_actual) > 15 && $fecha_fin > $fecha_actual)
                {
                    $msj = array(
                        'error'=>'Usted aún no puede hacer la evaluación de su tutor'
                    );
                    return redirect('/estudiante/practicas')->with($msj);
                }
                
                $evaluacion->idEvaluado = $p->gettutores[sizeof($p->gettutores) - 1]->id;
                break;
            }
        }
        if(!$bool)
        {
            $msj = array(
                'error'=>'Usted aún no tiene las prácticas aprobadas'
            );
            return redirect('/estudiante/practicas')->with($msj);
        }
        $respuesta = Respuesta::where('idUsuario', Auth::user()->id)
                              ->where('idEvaluado', $evaluacion->idEvaluado)
                              ->get();
        if(sizeof($respuesta)>0)
        {
            $msj = array(
                'error'=>'Usted ya evaluó a su tutor'
            );
            return redirect('/estudiante/practicas')->with($msj);
        }
        return view('evaluacion.responder', compact('estudiante','evaluacion'));
    }
    
    public function getAutoevaluacionestudiante()
    {
        $evaluacion = Evaluacion::where('idRolevaluador', Rol::where('nombre', 'Estudiante')->first()->id)
                                ->where('idRolevaluado', Rol::where('nombre', 'Estudiante')->first()->id)
                                ->first();
        
        if(sizeof($evaluacion)==0 || !$evaluacion->estado)
        {
            $msj = array(
                'error'=>'No es posible realizar esta evaluación'
            );
            return redirect('/estudiante/practicas')->with($msj);
        }
        
        $evaluacion->idEvaluado = Auth::user()->id;
        
        $estudiante = null;
        if(Auth::user()->getrol->nombre == 'Estudiante')
        {
            $estudiante = Estudiante::where('codigo', Auth::user()->identificacion)->first();
        }
        $bool = false;
        foreach($estudiante->getpracticas as $p)
        {
            if($p->getestado->nombre=='Aprobada')
            {
                $bool = true;
                $fecha_fin = \Carbon\Carbon::create(explode('-',$p->fecha_fin)[0], explode('-',$p->fecha_fin)[1], explode('-',$p->fecha_fin)[2]);
                
                $fecha_actual = \Carbon\Carbon::now(); 
                
                if($fecha_fin->diffInDays($fecha_actual) > 15 && $fecha_fin > $fecha_actual)
                {
                    $msj = array(
                        'error'=>'Usted aún no puede hacer la auto-evaluación'
                    );
                    return redirect('/estudiante/practicas')->with($msj);
                }
                break;
            }
        }
        if(!$bool)
        {
            $msj = array(
                'error'=>'Usted aún no tiene las prácticas aprobadas'
            );
            return redirect('/estudiante/practicas')->with($msj);
        }
        $respuesta = Respuesta::where('idUsuario', Auth::user()->id)
                              ->where('idEvaluado', $evaluacion->idEvaluado)
                              ->get();
        if(sizeof($respuesta)>0)
        {
            $msj = array(
                'error'=>'Usted ya realizó la auto-evaluación'
            );
            return redirect('/estudiante/practicas')->with($msj);
        }
        return view('evaluacion.responder', compact('estudiante','evaluacion'));
    }
    
    
    public function getEvaluarestudiantebyjefe($idEstudiante)
    {
        $evaluacion = Evaluacion::where('idRolevaluador', Rol::where('nombre', 'Jefe inmediato')->first()->id)
                                ->where('idRolevaluado', Rol::where('nombre', 'Estudiante')->first()->id)
                                ->first();
                                
        $estudiante = Estudiante::find($idEstudiante);
        
        if(sizeof($evaluacion)==0 || !$evaluacion->estado)
        {
            $msj = array(
                'error'=>'No es posible realizar esta evaluación'
            );
            return redirect('/jefe')->with($msj);
        }
        
        $evaluacion->idEvaluado = $estudiante->getpersona->getusuarios[0]->id;
        
        $bool = false;
        foreach($estudiante->getpracticas as $p)
        {
            if($p->getestado->nombre=='Aprobada')
            {
                $bool = true;
                $fecha_fin = \Carbon\Carbon::create(explode('-',$p->fecha_fin)[0], explode('-',$p->fecha_fin)[1], explode('-',$p->fecha_fin)[2]);
                
                $fecha_actual = \Carbon\Carbon::now(); 
                
                if($fecha_fin->diffInDays($fecha_actual) > 15 && $fecha_fin > $fecha_actual)
                {
                    $msj = array(
                        'error'=>'Usted aún no puede evaluar a este practicante'
                    );
                    return redirect('/jefe')->with($msj);
                }
                break;
            }
        }
        if(!$bool)
        {
            $msj = array(
                'error'=>'Este practicante aún no tiene las prácticas aprobadas'
            );
            return redirect('/jefe')->with($msj);
        }
        $respuesta = Respuesta::where('idUsuario', Auth::user()->id)
                              ->where('idEvaluado', $evaluacion->idEvaluado)
                              ->get();
        if(sizeof($respuesta)>0)
        {
            $msj = array(
                'error'=>'Usted ya evaluó a este practicante'
            );
            return redirect('/jefe')->with($msj);
        }
        return view('evaluacion.responder', compact('estudiante','evaluacion'));
    }
    
    public function getEvaluarestudiantebytutor($id)
    {
        $practica = PracticaTutor::find($id)->getpractica;
        $idModalidad = $practica->idModalidad;
        
        $evaluaciones = Evaluacion::where('idRolevaluador', Rol::where('nombre', 'Tutor')->first()->id)
                                ->where('idRolevaluado', Rol::where('nombre', 'Estudiante')->first()->id)
                                // ->where('modalidad_evaluacion.idmodalidad', '=', $idModalidad);
                                ->get();
        $evaluacion = null;
        foreach($evaluaciones as $e)
        {
            foreach($e->getmodalidades as $modalidad)
            {
                if($modalidad->id == $idModalidad)
                {
                    $evaluacion = $e;
                    break;
                }
            }
            if($evaluacion != null)
            {
                break;
            }
        }
                                
        $estudiante = Estudiante::find($practica->idEstudiante);
        
        if(sizeof($evaluacion)==0 || !$evaluacion->estado)
        {
            $msj = array(
                'error'=>'No es posible realizar esta evaluación'
            );
            return redirect('/tutor')->with($msj);
        }
        
        $evaluacion->idEvaluado = $estudiante->getpersona->getusuarios[0]->id;
        
        
        
        $bool = false;
        foreach($estudiante->getpracticas as $p)
        {
            if($p->getestado->nombre=='Aprobada')
            {
                $bool = true;
                $fecha_fin = \Carbon\Carbon::create(explode('-',$p->fecha_fin)[0], explode('-',$p->fecha_fin)[1], explode('-',$p->fecha_fin)[2]);
                
                $fecha_actual = \Carbon\Carbon::now(); 
                
                if($fecha_fin->diffInDays($fecha_actual) > 15 && $fecha_fin > $fecha_actual)
                {
                    $msj = array(
                        'error'=>'Usted aún no puede evaluar a este practicante'
                    );
                    return redirect('/tutor')->with($msj);
                }
                break;
            }
        }
        if(!$bool)
        {
            $msj = array(
                'error'=>'Este practicante aún no tiene las prácticas aprobadas'
            );
            return redirect('/tutor')->with($msj);
        }
        $respuesta = Respuesta::where('idUsuario', Auth::user()->id)
                              ->where('idEvaluado', $evaluacion->idEvaluado)
                              ->get();
        if(sizeof($respuesta)>0)
        {
            $msj = array(
                'error'=>'Usted ya evaluó a este practicante'
            );
            return redirect('/tutor')->with($msj);
        }
        // dd($estudiante, $evaluacion);
        return view('evaluacion.responder', compact('estudiante','evaluacion'));
    }
    
    public function getEvaluacion($id=null)
    {
        $evaluacion = null;
        if($id==null)
        {
            $evaluacion = Evaluacion::with('getrolevaluador')
                             ->with('getrolevaluado')
                             ->with('getsecciones.getsecciones.getpreguntas.gettipo')
                             ->with('getsecciones.getsecciones.getpreguntas.getpivoterespuesta.getrespuesta')
                             ->with('getsecciones.getpreguntas.gettipo')
                             ->with('getsecciones.getpreguntas.getpivoterespuesta.getrespuesta')
                            //  ->where('id', $id)
                             ->get();
            
        }
        else
        {
            $evaluacion = Evaluacion::with('getrolevaluador')
                             ->with('getrolevaluado')
                             ->with('getsecciones.getsecciones.getpreguntas.gettipo')
                             ->with('getsecciones.getsecciones.getpreguntas.getpivoterespuesta.getrespuesta')
                             ->with('getsecciones.getpreguntas.gettipo')
                             ->with('getsecciones.getpreguntas.getpivoterespuesta.getrespuesta')
                             ->where('id', $id)
                             ->first();
        }
        return $evaluacion;
    }
    
    public function postSaverespuestasevaluacion(Request $request)
    {
        
        foreach($request->getsecciones as $seccion)
        {
            if($seccion['estado'])
            {
                foreach($seccion['getpreguntas'] as $pregunta)
                {
                    if($pregunta['estado'])
                    {
                        if($pregunta['gettipo']['nombre']=='Cualitativa')
                        {
                            Respuesta::create(['idRespuesta'=>$pregunta['respuesta'], 'idUsuario'=>Auth::user()->id, 'idEvaluado'=>$request->idEvaluado, 'idPregunta'=>$pregunta['id']]);
                        }
                        else if($pregunta['gettipo']['nombre']=='Cuantitativa')
                        {
                            Respuesta::create(['respuestaCuantitativa'=>$pregunta['respuesta'], 'idUsuario'=>Auth::user()->id, 'idEvaluado'=>$request->idEvaluado, 'idPregunta'=>$pregunta['id']]);
                        }
                        else if($pregunta['gettipo']['nombre']=='Respuesta libre')
                        {
                            Respuesta::create(['respuestaLibre'=>$pregunta['respuesta'], 'idUsuario'=>Auth::user()->id, 'idEvaluado'=>$request->idEvaluado, 'idPregunta'=>$pregunta['id']]);
                        }
                        else if($pregunta['gettipo']['nombre']=='Booleana')
                        {
                            Respuesta::create(['respuestaBooleana'=>$pregunta['respuesta'], 'idUsuario'=>Auth::user()->id, 'idEvaluado'=>$request->idEvaluado, 'idPregunta'=>$pregunta['id']]);
                        }
                        else if($pregunta['gettipo']['nombre']=='Booleana justificada')
                        {
                            Respuesta::create(['respuestaBooleana'=>$pregunta['respuesta'],'respuestaLibre'=>$pregunta['justificacion'], 'idUsuario'=>Auth::user()->id, 'idEvaluado'=>$request->idEvaluado, 'idPregunta'=>$pregunta['id']]);
                        }
                    }
                }
                
                foreach($seccion['getsecciones'] as $hija)//secciones hijas
                {
                    if($hija['estado'])
                    {
                        // ////////////////////////
                        foreach($hija['getpreguntas'] as $pregunta)
                        {
                            if($pregunta['estado'])
                            {
                                if($pregunta['gettipo']['nombre']=='Cualitativa')
                                {
                                    Respuesta::create(['idRespuesta'=>$pregunta['respuesta'], 'idUsuario'=>Auth::user()->id, 'idEvaluado'=>$request->idEvaluado, 'idPregunta'=>$pregunta['id']]);
                                }
                                else if($pregunta['gettipo']['nombre']=='Cuantitativa')
                                {
                                    Respuesta::create(['respuestaCuantitativa'=>$pregunta['respuesta'], 'idUsuario'=>Auth::user()->id, 'idEvaluado'=>$request->idEvaluado, 'idPregunta'=>$pregunta['id']]);
                                }
                                else if($pregunta['gettipo']['nombre']=='Respuesta libre')
                                {
                                    Respuesta::create(['respuestaLibre'=>$pregunta['respuesta'], 'idUsuario'=>Auth::user()->id, 'idEvaluado'=>$request->idEvaluado, 'idPregunta'=>$pregunta['id']]);
                                }
                                else if($pregunta['gettipo']['nombre']=='Booleana')
                                {
                                    Respuesta::create(['respuestaBooleana'=>$pregunta['respuesta'], 'idUsuario'=>Auth::user()->id, 'idEvaluado'=>$request->idEvaluado, 'idPregunta'=>$pregunta['id']]);
                                }
                                else if($pregunta['gettipo']['nombre']=='Booleana justificada')
                                {
                                    Respuesta::create(['respuestaBooleana'=>$pregunta['respuesta'],'respuestaLibre'=>$pregunta['justificacion'], 'idUsuario'=>Auth::user()->id, 'idEvaluado'=>$request->idEvaluado, 'idPregunta'=>$pregunta['id']]);
                                }
                            }
                        }
                        // ////////////////////////
                    }
                }
            }
                
            
                
        }
        
        return ['title'=>'Éxito', 'content'=>'La evaluación fue guardada con éxito', 'type'=>'success'];
    }
}
