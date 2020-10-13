<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PreguntaRespuesta extends Model
{
    protected $table ='pregunta_respuesta';
    
    public $timestamps = false;
    
    protected $fillable = ['idPregunta', 'idRespuesta'];
    
    public function getrespuesta()
    {
        return  $this->belongsTo('App\Models\PosibleRespuesta','idRespuesta');
    }
}
