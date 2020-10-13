<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Visita extends Model
{
    public $timestamps = false;
    
    protected $fillable = ['idPractica', 'fecha_registro', 'tema', 'firma_estudiante','hora', 'firma_jefe', 'firma_tutor', 'fecha', 'jefe'];
    
    public function getpractica()
    {
        return $this->belongsTo('App\Models\ModalidadEstudiante', 'idPractica');
    }
}
