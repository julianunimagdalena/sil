<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Empresa extends Model
{
    protected $table = 'empresas';
    
    public $timestamps = true;
    
    public function getestadosil(){

        return  $this->belongsTo('App\Models\EstadoEmpresas','estadoSil');
    }
    
    public function getestadodipro(){
        return  $this->belongsTo('App\Models\EstadoEmpresas','estadoDipro');
    }
    
    public function gettiponit(){
        return  $this->belongsTo('App\Models\TipoNit','idTipoNit');
    }
    
    public function getconvenios()
    {
        return $this->hasMany('App\Models\Convenio', 'idEmpresa');
    }
    
    public function getsedes()
    {
        return $this->hasMany('App\Models\Sede', 'idEmpresa');
    }
    
    public function gettipoempleador()
    {
        return $this->belongsTo('App\Models\TipoEmpleador', 'idTipoEmpleador');
    }
    
    public function getactividadeconomica()
    {
        return $this->belongsTo('App\Models\ActividadesEconomicas', 'idActividadEconomica');
    }
    
    public function getrepresentante()
    {
        return $this->belongsTo('App\Models\Persona', 'idPersona');
    }
}
