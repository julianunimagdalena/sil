<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Evaluacion extends Model
{
    protected $table='evaluaciones';
    
    public $timestamps = false;
    
    protected $fillable = ['idRolevaluador', 'idRolevaluado', 'nombre' ,'descripcion'];
    
    public function getrolevaluador()
    {
        return  $this->belongsTo('App\Models\Rol','idRolevaluador');
    }
    
    public function getrolevaluado()
    {
        return  $this->belongsTo('App\Models\Rol','idRolevaluado');
    }
    
    public function getsecciones()
    {
        return  $this->hasMany('App\Models\Seccion','idEvaluacion');
    }
    
    public function getmodalidades()
    {
        return $this->belongsToMany('App\Models\Modalidad', 'modalidad_evaluacion', 'idEvaluacion', 'idModalidad');
    }
}
