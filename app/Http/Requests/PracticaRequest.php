<?php

namespace App\Http\Requests;

use App\Http\Requests\Request;

use App\Models\EstadoPractica;

class PracticaRequest extends Request
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
        $estados = EstadoPractica::where('nombre', '!=', 'Esperando respuesta')->get();
        $estadosRequiredObservacion = EstadoPractica::where('nombre', '!=', 'Aprobada')->get();
        $varchar = '';
        foreach($estados as $estado)
        {
            $varchar = $varchar.$estado->id.',';
        }
        $varchar = substr($varchar, 0, -1);
        
        $varchar2 = '';
        foreach($estadosRequiredObservacion as $estado)
        {
            $varchar2 = $varchar2.$estado->id.',';
        }
        $varchar2 = substr($varchar2, 0, -1);
        
        return [
            'practica.estado.id'=>'required|in:'.$varchar.'',
            'practica.observaciones'=>'required_if:practica.estado.id,'.$varchar2.'|string',
        ];
    }
    
    public function messages()
    {
        return [
            'practica.estado.id.required'=>'El campo estado es obligatorio',
            'practica.estado.id.in'=>'El campo estado es inválido',
            'practica.observaciones.required_if'=>'El campo observaciones es obligatorio',
            'practica.observaciones.string'=>'El campo observaciones contiene caracteres inválidos',
        ];
    }
}
