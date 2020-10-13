<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class HojaCompetencia extends Model
{
    protected $table ='competencias_hojadevida';
    
    public $timestamps = false;
    
    protected $fillable = ['idHoja', 'idCompetencia'];
}
