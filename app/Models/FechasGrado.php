<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FechasGrado extends Model
{
    protected $table = 'fechas_de_grado';

   	public function modalidadesEstudio () {
   		return $this->belongsToMany('App\Models\ModalidadEstudio', 'fechas_modalidades', 'idFecha', 'idModalidad');
   	}
}
