<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Novedad extends Model
{
    protected $table ='novedades';
    
    public $timestamps = false;
    
    protected $fillable = ['idUsuario', 'asunto', 'contenido', 'fecha', 'idRespuesta'];
    
    public function getusuario()
    {
        return $this->belongsTo('App\Models\User', 'idUsuario');
    }
    
    public function getusuarios()
    {
        return $this->belongsToMany('App\Models\User', 'usuario_novedad', 'idNovedad', 'idUsuario');
    }

    public function getusuariosnovedad()
    {
        return $this->hasMany('App\Models\UsuarioNovedad', 'idNovedad');
    }
    
    
}
