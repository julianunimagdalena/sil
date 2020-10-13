<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use Auth;

use App\Http\Requests\CodigoRequest;

use App\Models\CoordinadorDependencia;
use App\Models\EstadoPractica;
use App\Models\Estudiante;
use App\Models\ModalidadEstudiante;

class ProgramaController extends Controller
{
    
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('programa');
    }
    
    public function getIndex()
    {
        return view('programa.index');
    }
    
    public function getPracticantesjson()
    {
        $idPracticantes = ModalidadEstudiante::select('idEstudiante')->where('estado', EstadoPractica::where('nombre', 'Aprobada')->first()->id)->get()->toArray();
        
        $idPrograma = CoordinadorDependencia::where('idCoordinador', Auth::user()->id)->first()->idPrograma;
        $estudiantes = Estudiante::with('getpersona')
                                 ->with('getpracticas.getmodalidad')
                                 ->with('getpostulaciones.getoferta.getsede.getempresa')
                                 ->with('getprograma')
                                 ->where('idPrograma', $idPrograma)
                                 ->whereIn('id', $idPracticantes)
                                 ->get();
        return $estudiantes;
    }
    
    public function getDatosprogramajson()
    {
        $user = Auth::user();
        
        return $user->getdependencias[0];
    }
    
    public function postSavecodigo(CodigoRequest $request)
    {
         $user = Auth::user();
        
        $programa = $user->getdependencias[0];
        
        $programa->codigoPracticas = $request->codigo_practicas;
        
        $programa->save();
        
        return [
            'title'=>'Éxito',
            'content'=>'Código actualizado con éxito',
            'type'=>'success',
        ];
    }
}
