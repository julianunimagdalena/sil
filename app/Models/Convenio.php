<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Convenio extends Model
{
    public $timestamps = false;
    
    public function getestado()
    {
        return $this->belongsTo('App\Models\EstadoConvenio', 'estado');
    }
    
    public function getactasrenovacion()
    {
        return $this->hasMany('App\Models\ActaRenovacion', 'idConvenio');
    }
    
    public function getempresa()
    {
        return $this->belongsTo('App\Models\Empresa', 'idEmpresa');
    }
}
