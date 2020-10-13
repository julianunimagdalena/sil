<?php

namespace App\Http\Requests;

use App\Models\User;

class UsuarioRequest extends Request 
{
    
    public function authorize()
    {
        return true;
    }
    
    
    public function rules()
    {
        $rol = $this->getrol;
        if(!isset($this->id))
        {
            
            if($rol['nombre']=='Coordinador de programa' || $rol['nombre']=='Coordinador')
            {
                return [
                    'getrol'=>'required',
                    'dependencias'=>'required',
                    'identificacion'=>'required|numeric|unique:usuarios,identificacion',
                    'getuser.nombres'=>'required|alpha',
                    'getuser.apellidos'=>'required|alpha',
                    'getuser.correo'=>'required|email|unique:personas,correo',
                    'getuser.celular'=>'required|numeric'
                ];
            }
            else
            {
                return [
                    'getrol'=>'required',
                    'identificacion'=>'required|numeric|unique:usuarios,identificacion',
                    'getuser.nombres'=>'required|alpha',
                    'getuser.apellidos'=>'required|alpha',
                    'getuser.correo'=>'required|email|unique:personas,correo',
                    'getuser.celular'=>'required|numeric'
                ];
                
            }
        }
        else
        {
            $persona = User::find($this->id)->idPersona;
            if($rol['nombre']=='Coordinador de programa' || $rol['nombre']=='Coordinador')
            {
                return [
                    'id'=>'required|numeric|exists:usuarios,id',
                    'getrol'=>'required',
                    'dependencias'=>'required',
                    'identificacion'=>'required|numeric|unique:usuarios,identificacion,'.$this->id,
                    'getuser.nombres'=>'required|alpha',
                    'getuser.apellidos'=>'required|alpha',
                    'getuser.correo'=>'required|email|unique:personas,correo,'.$persona,
                    'getuser.celular'=>'required|numeric'
                ];
            }
            else
            {
                return [
                    'id'=>'required|numeric|exists:usuarios,id',
                    'getrol'=>'required',
                    'identificacion'=>'required|numeric|unique:usuarios,identificacion,'.$this->id,
                    'getuser.nombres'=>'required|alpha',
                    'getuser.apellidos'=>'required|alpha',
                    'getuser.correo'=>'required|email|unique:personas,correo,'.$persona,
                    'getuser.celular'=>'required|numeric'
                ];
                
            }
        }
            
        
    }
    
    public function messages()
    {
        return[
            'getrol.required'=>'El campo rol es obligatorio',
            'dependencias.required'=>'El campo dependencia es obligatorio',
            'getuser.nombres.required'=>'El campo nombres es obligatorio',
            'getuser.nombres.alpha'=>'El campo nombres solo debe contener letras y espacios',
            'getuser.apellidos.required'=>'El campo apellidos es obligatorio',
            'getuser.apellidos.alpha'=>'El campo apellidos solo debe contener letras y espacios',
            'getuser.correo.required'=>'El campo correo es obligatorio',
            'getuser.correo.email'=>'El campo correo no es una direción de correo válida',
            'getuser.correo.unique'=>'El campo correo debe ser único, este valor ya se encuentra registrado',
            'getuser.celular.required'=>'El campo celular es obligatorio',
            'getuser.celular.numeric'=>'El campo celular solo debe contener números',
        ];
    }
    
}