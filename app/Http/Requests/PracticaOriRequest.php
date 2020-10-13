<?php

namespace App\Http\Requests;

use App\Http\Requests\Request;

class PracticaOriRequest extends Request
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
            'practica.estado'=>'required|in:1,2',
            'practica.observaciones'=>'required_if:practica.estado,2|string',
        ];
    }
    
    public function messages()
    {
        return [
            'practica.estado.required'=>'El campo estado es obligatorio',
            'practica.estado.in'=>'El campo estado es inválido',
            'practica.observaciones.required_if'=>'El campo observaciones es obligatorio',
            'practica.observaciones.string'=>'El campo observaciones contiene caracteres inválidos',
        ];
    }
}
