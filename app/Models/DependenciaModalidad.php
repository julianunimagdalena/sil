<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DependenciaModalidad extends Model
{
    protected $table = 'dependencias_modalidades';

    public $timestamps = false;

    public function getprograma()
    {
    	return $this->belongsTo('App\Models\Dependencia', 'idPrograma');
    }

	public function getmodalidad()
    {
    	return $this->belongsTo('App\Models\ModalidadEstudio', 'idModalidad');
    }  

    public function getestudiantes()
    {
        return $this->hasMany('App\Models\Estudiante', 'idPrograma');
    }  
}
