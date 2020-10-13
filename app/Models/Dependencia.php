<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

use DB;

class Dependencia extends Model
{
    protected $table = 'dependencias';
    
    public $timestamps=false;

    public function getdependenciamodalidad()
    {
    	return $this->hasMany('App\Models\DependenciaModalidad', 'idPrograma');
    }

    public function getofertaprograma()
    {
    	return $this->hasMany('App\Models\OfertaPrograma', 'idDependencia');
    }
    
    public function tipo () {
        return $this->belongsTo('App\Models\TipoDependencia', 'idTipo');
    }
}