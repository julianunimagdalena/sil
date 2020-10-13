<?php

namespace App\Models;

use Illuminate\Auth\Authenticatable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Auth\Passwords\CanResetPassword;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Contracts\Auth\CanResetPassword as CanResetPasswordContract;

use DB;

class User extends Model implements AuthenticatableContract, CanResetPasswordContract
{
    use Authenticatable, CanResetPassword;

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'usuarios';
    
    public $timestamps = true;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['password', 'idRol', 'identificacion'];

    /**
     * The attributes excluded from the model's JSON form.
     *
     * @var array
     */
    protected $hidden = ['password', 'remember_token'];
    
    public function getuser(){

        return  $this->belongsTo('App\Models\Persona','idPersona');

    }
    
    public function getrol(){
        return  $this->belongsTo('App\Models\Rol','idRol');
    }

    public function roles () {
        return $this->belongsToMany('App\Models\Rol', 'usuario_rol', 'usuario_id', 'rol_id')->withPivot('activo', 'ultima_actividad');
    }
    
    public function getsede(){
        return  $this->belongsTo('App\Models\Sede','idSede');
    }
    
    public function getnovedadesrecibidas()
    {
        return $this->belongsToMany('App\Models\Novedad', 'usuario_novedad', 'idUsuario', 'idNovedad');
    }
    
    public function getnovedades()
    {
        return $this->hasMany('App\Models\UsuarioNovedad', 'idUsuario');
    }
    
    public function getdependencias()
    {
        return $this->belongsToMany('App\Models\Dependencia', 'coordinador_programas', 'idCoordinador', 'idPrograma');
    }
    
    public function getnovedadesenviadas()
    {
        return $this->hasMany('App\Models\Novedad', 'idUsuario');
    }
    
    public static function usuariosbyempresa($id)
    {
        $usuarios = DB::table('usuarios')
                      ->join('personas', 'idPersona', '=', 'personas.id')
                      ->join('roles', 'idRol','=', 'roles.id')
                      ->join('sedes', 'idsede', '=', 'sedes.id')
                      ->join('empresas', function($join) use ($id){
                          $join->on('idEmpresa', '=', 'empresas.id')
                               ->where('empresas.id', '=', $id);
                      })
                      ->whereNotNull('cargo')
                      ->selectRaw('personas.identificacion, personas.nombres, personas.apellidos, 
                                   roles.nombre as rol, personas.correo, personas.celular, usuarios.id, usuarios.area, usuarios.cargo')
                      ->get();
        return $usuarios;
    }
    
    public static function getUsuario($id)
    {   
        $usuario = DB::table('usuarios')
                        ->join('personas', 'personas.id', '=', 'idPersona')
                        ->join('roles', 'roles.id', '=', 'idRol')
                        ->leftJoin('dependencias', 'dependencias.id', '=', 'idDependencia')
                        ->leftJoin('sedes', 'sedes.id', '=', 'idSede')
                        ->leftJoin('empresas', 'empresas.id', '=', 'idEmpresa')
                        ->leftJoin('municipios', 'municipios.id', '=', 'idMunicipio')
                        ->selectRaw("personas.nombres, personas.apellidos, personas.identificacion,personas.correo, usuarios.id, 
                                     roles.nombre as rol, dependencias.nombre as dependencia, concat(empresas.nombre, ' - ', municipios.nombre) as sede,
                                     personas.celular, usuarios.area, usuarios.cargo")
                        ->where('usuarios.id', $id)
                        ->first();
        
        return $usuario;
        
    }
    
    public static function getJefes($idSede)
    {
        $usuario = DB::table('usuarios')
                     ->join('personas', 'personas.id', '=', 'idPersona')
                     ->where('idSede', $idSede)
                     ->whereNotNull('area')
                     ->selectRaw("concat(personas.nombres,' ', personas.apellidos) as nombre, usuarios.id")
                     ->get();
                     
        return $usuario;
    }
    
    public static function getJefe($idJefe)
    {
        $usuario = DB::table('usuarios')
                     ->join('personas', 'personas.id', '=', 'idPersona')
                     ->where('usuarios.id', $idJefe)
                     ->selectRaw("concat(personas.nombres,' ', personas.apellidos) as nombre, usuarios.id")
                     ->get();
                     
        return $usuario;
    }
}
