<?php

namespace App\Http\Requests;

use App\Http\Requests\Request;

class DatosPersonalesRequest extends Request
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }
    
    

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'getciudad.id'=>'required|numeric|exists:municipios,id',
            'fechaNacimiento'=>'required|date',
            'getgenero.id'=>'required|numeric|exists:genero,id',
            'getciudadres.id'=>'required|numeric|exists:municipios,id',
            'getestadocivil.id'=>'required|numeric|exists:estadocivil,id',
            'direccion'=>'required|string',
            'correo'=>'required|email',
            'correo2'=>'email',
            'celular'=>'required|numeric',
            'estrato'=>'numeric',
            'telefono_fijo'=>'numeric',
            'celular2'=>'numeric'
        ];
    }
    
    public function messages()
    {
        return [
            'getciudad.id.required'=>'El campo ciudad es obligatorio',
            'getciudad.id.numeric'=>'El campo ciudad es inválido',
            'getciudad.id.exists'=>'El campo ciudad es inválido',
            'getciudadres.id.required'=>'El campo ciudad de residencia es obligatorio',
            'getciudadres.id.numeric'=>'El campo ciudad de residencia es inválido',
            'getciudadres.id.exists'=>'El campo ciudad de residencia es inválido',
            'getestadocivil.id.required' => 'El campo debe ser obligatorio',
            'getestadocivil.id.numeric' => 'El campo debe ser obligatorio',
            'getestadocivil.id.exists' => 'El campo debe ser obligatorio',
            'fechaNacimiento.required'=>'La fecha de nacimiento es obligatoria',
            'fechaNacimiento.date'=>'Formato de fecha incorrecto',
            'celular2.numeric' => 'El campo debe ser numerico',
            'correo2.email' => 'Email invalido'
        ];
    }
}
