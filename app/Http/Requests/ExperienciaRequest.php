<?php

namespace App\Http\Requests;

use App\Http\Requests\Request;

class ExperienciaRequest extends Request
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
            'empresa'=>'required|alpha',
            'cargo'=>'required|alpha',
            'duracion.id'=>'required|exists:duraciones,id',
            'funcioneslogros'=>'required|string',
            'nivel_cargo_id'=>'required|exists:niveles_cargo,id',
            'tipo_vinculacion_id'=>'required|exists:tipos_vinculacion,id',
            'municipio_id'=>'required|exists:municipios,id',
            'email'=>'required|email',
            'telefono'=>'required|numeric',
            'salario_id'=>'required|numeric|exists:salarios,id'
        ];
    }
    
    public function messages()
    {
        return [
            'funcioneslogros.required'=>'El campo funciones y meritos es obligatorio',
            'funcioneslogros.string'=>'El campo funciones y meritos tiene caracteres inválidos',
            'nivel_cargo_id.required' => 'Campo Obligatorio',
            'tipo_vinculacion_id.required' => 'Campo Obligatorio',
            'municipio_id.required' => 'Campo Obligatorio',
            'email.required' => 'Campo Obligatorio',
            'telefono.required' => 'Campo Obligatorio',
            'salario_id.required' => 'Campo Obligatorio',
            ];
    }
}
