<?php

namespace App\ModelS3;

use Illuminate\Database\Eloquent\Model;

class PersonaS3 extends Model
{
    protected $connection = 'SIEG3'; 
     
    protected $table = 'personas';
}
