<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use Auth;
use App\Models\EstadoPostulado;
use App\Models\EstadoPostuladoEst;
use App\Models\EstadoPractica;
use App\Models\Modalidad;
use App\Models\ModalidadEstudiante;
use App\Models\Oferta;
use App\Models\Postulado;
use App\Models\Visita;

class JefeController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('jefe');
    }
    public function getIndex()
    {
        return view('jefe.practicantes');
    }
    
    public function getPracticantesjson()
    {
        $ofertas = Oferta::where('idJefe', Auth::user()->id)->select('id')->get()->toArray();
        $idEstudiantes = ModalidadEstudiante::where('idModalidad', Modalidad::where('nombre', 'Vinculación laboral')->first()->id)
                                            ->select('idEstudiante')
                                            ->get()->toArray();
        // return $ofertas;
        $postulados = Postulado::with('getoferta')
                               ->with('getestudiante.getpersona')
                               ->with('getestudiante.getprograma')
                               ->with('getestudiante.getpracticas.getvisitas')
                               ->whereHas('getestudiante', function($query) use ($idEstudiantes){
                                   $query->whereIn('estudiantes.id', $idEstudiantes);
                               })
                               ->whereIn('idOferta', $ofertas)
                               ->where('idEstatoEmpresa', EstadoPostulado::where('nombre', 'Seleccionado')->first()->id)
                               ->where('idEstadoEstudiante', EstadoPostuladoEst::where('nombre', 'Aceptó')->first()->id)
                               ->get();
                               
        // dd($postulados);
        
        $fecha_actual = \Carbon\Carbon::now();
        
        foreach ($postulados as $practicante) 
        {
            foreach($practicante->getestudiante->getpracticas as $practicas)
            {
                if(sizeof(explode('-',$practicas->fecha_fin))>1)
                {
                    $fecha_fin = \Carbon\Carbon::create(explode('-',$practicas->fecha_fin)[0], explode('-',$practicas->fecha_fin)[1], explode('-',$practicas->fecha_fin)[2]);
                    
                    $practicante->mostrar = $fecha_fin->diffInDays($fecha_actual) < 15 || $fecha_fin < $fecha_actual;
                }
                else
                {
                    $practicante->mostrar = false;
                }
            }
            
        }
                               
        return $postulados;
    }
    
    public function postAprobarpractcas(Request $request)
    {
        $practicas = ModalidadEstudiante::whereIn('id', $request->ids)->get();
        
        foreach($practicas as $practica)
        {
            $practica->aprobacion_jefe = true;
            $practica->fecha_aprobacion_jefe = \Carbon\Carbon::now();
            $practica->save();
        }
        
        return ['title'=>'Éxito', 'content'=>'Prácticas aprobadas con éxito', 'type'=>'success'];
    }
    
    public function getAprobaracta($id)
    {
        $practica = ModalidadEstudiante::find($id);
        $practica->aprobacion_jefe = true;
        $practica->fecha_aprobacion_jefe = \Carbon\Carbon::now();
        $practica->save();
        return ['title'=>'Éxito', 'content'=>'Prácticas aprobadas con éxito', 'type'=>'success'];
    }
    
    public function getVeracta($id)
    {
        $acta = ModalidadEstudiante::where('aprobacion_estudiante', true)
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
                                         
        return view('jefe.veracta', compact('acta'));
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
    
    public function getVisitasjson($id)
    {
        $practicas = ModalidadEstudiante::find($id);
        $visitas = $practicas->getvisitas;
        return $visitas;
    }
    
    public function getConfirmarvisita($id)
    {
        $visita = Visita::find($id);
        $visita->firma_jefe = true;
        $visita->save();
        return ['title'=>'Éxito', 'content'=>'Visita confirmada con éxito', 'type'=>'success'];
    }
    
    public function getNoconfirmarvisita($id)
    {
        $visita = Visita::find($id);
        $visita->firma_jefe = false;
        $visita->save();
        return ['title'=>'Éxito', 'content'=>'Visita no confirmada con éxito', 'type'=>'success'];
    }
    
    public function getVisitasbyvisitajson($idVisita)
    {
        $visita = Visita::find($idVisita);
        $practicas = $visita->getpractica;
        $visitas = $practicas->getvisitas;
        return $visitas;
    }
}
