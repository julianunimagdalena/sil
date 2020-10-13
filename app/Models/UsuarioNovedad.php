<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UsuarioNovedad extends Model
{
    protected $table ='usuario_novedad';
    
    public $timestamps = false;
    
    protected $fillable =['idUsuario', 'idNovedad'];
    
    public function getnovedad(){
        return  $this->belongsTo('App\Models\Novedad','idNovedad');
    }

    public function getusuario(){
        return  $this->belongsTo('App\Models\User','idUsuario');
    }
}
