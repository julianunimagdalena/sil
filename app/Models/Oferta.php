<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

use DB;

class Oferta extends Model
{
    protected $table='ofertas';
    
    public $timestamps=false;

    protected $casts = [
        'vacantes' => 'integer'
    ];

    public function postulados () {
        return $this->belongsToMany('App\Models\Persona', 'postulados', 'idOferta', 'idPersona')->withPivot('idEstadoEstudiante', 'idEstatoEmpresa');
    }
    
    public function gettipo(){
        return  $this->belongsTo('App\Models\Tipooferta','idTipo');
    }
    
    public function getsalario(){
        return  $this->belongsTo('App\Models\Salario','idSalario');
    }
    
    public function getjefe(){
        return  $this->belongsTo('App\Models\User','idJefe');
    }
    
    public function getcontrato(){
        return  $this->belongsTo('App\Models\Contrato','idContrato');
    }

    public function getexperiencia(){
        return  $this->belongsTo('App\Models\TExperiencia','idExperiencia');
    }
    
    public function getprogramas()
    {
        return $this->belongsToMany('App\Models\Dependencia', 'ofertas_programas', 'idOferta', 'idDependencia');
    }  

    public function getofertaprogramas()
    {
        return $this->hasMany('App\Models\OfertaPrograma','idOferta');
    }  
    
    public function getpostulados()
    {
        return $this->hasMany('App\Models\Postulado', 'idOferta');
    }
    
    public function getsede()
    {
        return $this->belongsTo('App\Models\Sede', 'idSede');
    }
    
    public function getestado()
    {
        return $this->belongsTo('App\Models\EstadoOferta', 'estado');
    }

    public function getmunicipio()
    {
        return $this->belongsTo('App\Models\Municipio', 'idMunicipio');
    }
    
    public static function getOfertasByEstudiante($idPrograma, $idEstudiante)
    {
        $fecha = \Carbon\Carbon::now()->toDateString();
        
        $ofertas = DB::table('ofertas')
                     ->join('ofertas_programas', function($join) use ($idPrograma){
                         $join->on('ofertas_programas.idOferta', '=', 'ofertas.id')
                              ->where('ofertas_programas.idDependencia','=', $idPrograma);
                     })
                     ->join('sedes', 'sedes.id', '=', 'idSede')
                     ->join('empresas', 'empresas.id', '=', 'idEmpresa')
                    //  ->leftJoin('postulados', 'postulados.idOferta', '=', 'ofertas.id')
                     ->leftJoin('postulados', function($join) use ($idEstudiante){
                         $join->on('postulados.idOferta', '=', 'ofertas.id')
                              ->where('postulados.idEstudiante','=', $idEstudiante);
                     })
                     ->leftJoin('estadopostulados', 'idEstatoEmpresa', '=', 'estadopostulados.id')
                     ->leftJoin('estadopostuladosestudiante', 'idEstadoEstudiante', '=', 'estadopostuladosestudiante.id')
                     ->where('idTipo', Tipooferta::where('nombre', 'Practicantes')->first()->id)
                     ->where('estado', EstadoOferta::where('nombre', 'Publicada')->first()->id)
                     ->where('fechaCierre', '>=', $fecha)
                     ->selectRaw('ofertas.id,empresas.nombre as empresa, ofertas.nombre, ofertas.vacantes, ofertas.salario, 
                                  estadopostulados.nombre as estado, postulados.id as idPostulado,
                                  estadopostuladosestudiante.nombre as estadoEst')
                     ->get();
        return $ofertas; 
    }
    
    public static function getOfertasByEgresado($idPrograma, $idPersona)
    {
        $fecha = \Carbon\Carbon::now()->toDateString();
        $ofertas = DB::table('ofertas')
                     ->join('ofertas_programas', function($join) use ($idPrograma){
                         $join->on('ofertas_programas.idOferta', '=', 'ofertas.id')
                              ->whereIn('ofertas_programas.idDependencia','=', $idPrograma);
                     })
                     ->join('sedes', 'sedes.id', '=', 'idSede')
                     ->join('empresas', 'empresas.id', '=', 'idEmpresa')
                     ->join('salarios', 'salarios.id', '=', 'idsalario')
                     ->leftJoin('postulados', function($join) use ($idPersona){
                         $join->on('postulados.idOferta', '=', 'ofertas.id')
                              ->where('postulados.idPersona','=', $idPersona);
                     })
                     ->leftJoin('estadopostulados', 'idEstatoEmpresa', '=', 'estadopostulados.id')
                     ->where('idTipo', Tipooferta::where('nombre', 'Egresados')->first()->id)
                     ->where('estado', EstadoOferta::where('nombre', 'Publicada')->first()->id)
                     ->where('fechaCierre', '>', $fecha)
                     ->selectRaw('ofertas.id,empresas.nombre as empresa, ofertas.nombre, ofertas.vacantes, salarios.rango as salario
                                 ,estadopostulados.nombre as estado, postulados.id as idPostulado')
                     ->get();
        return $ofertas; 
    }
    
    public static function getOfertasById($id, $idPersona)
    {
        $ofertas = DB::table('ofertas')
                     ->join('ofertas_programas', function($join) {
                         $join->on('ofertas_programas.idOferta', '=', 'ofertas.id');
                     })
                     ->join('sedes', 'sedes.id', '=', 'idSede')
                     ->join('empresas', 'empresas.id', '=', 'idEmpresa')
                     ->join('postulados', function($join) use ($idPersona){
                         $join->on('postulados.idOferta', '=', 'ofertas.id')
                              ->where('postulados.idPersona','=', $idPersona);
                     })
                    //  ->Join('estadopostulados', 'idEstatoEmpresa', '=', 'estadopostulados.id')
                     ->join('estadopostulados', function($join) {
                         $join->on('estadopostulados.id', '=', 'idEstatoEmpresa')
                              ->where('idEstatoEmpresa', '=', 3);
                     })
                    //  ->Join('estadopostuladosestudiante', 'idEstadoEstudiante', '=', 'estadopostuladosestudiante.id')
                     ->join('estadopostuladosestudiante', function($join) {
                         $join->on('estadopostuladosestudiante.id', '=', 'idEstadoEstudiante')
                              ->where('idEstadoEstudiante', '=', 2);
                     })
                     ->where('ofertas.id', $id)
                     ->selectRaw('distinct ofertas.id,empresas.nombre as empresa, ofertas.nombre, ofertas.vacantes, ofertas.salario, 
                                  estadopostulados.nombre as estado, postulados.id as idPostulado,
                                  estadopostuladosestudiante.nombre as estadoEst')
                     ->get();
        return $ofertas; 
    }
}
