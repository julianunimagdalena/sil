<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Referencia extends Model
{
    public $timestamps = false;
    
    protected $fillable = ['nombre', 'ocupacion', 'telefono', 'parentesco', 'idHoja'];
    
    public function getparentesco(){
        return  $this->belongsTo('App\Models\Parentesco','parentesco');
    }
}
