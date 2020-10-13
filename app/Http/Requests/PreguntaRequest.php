<?php

namespace App\Http\Requests;

use App\Http\Requests\Request;

class PreguntaRequest extends Request
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
            'getseccion.id'=>'required|exists:secciones,id',
            'gettipo.id'=>'required|exists:tipo_pregunta,id',
            'minimo'=>'required_if:gettipo.nombre,"Cuantitativa"|numeric|min:0',
            'maximo'=>'required_if:gettipo.nombre,"Cuantitativa"|numeric|greater_than:'.$this->minimo,
            'enunciado'=>'required|string',
            'respuestas'=>'required_if:gettipo.nombre,"Cualitativa"|array|min:3',
        ];
    }
    
    public function messages()
    {
        return[
            'getseccion.id.required'=>'Debe escoger una sección',
            'getseccion.id.exists'=>'Esta sección es invalida',
            'gettipo.id.required'=>'Debe escoger un tipo de pregunta',
            'gettipo.id.exists'=>'El tipo de pregunta es inválido',
            'minimo.required_if'=>'El valor mínimo es obligatorio',
            'minimo.numeric'=>'El valor mínimo debe ser numérico',
            'minimo.min'=>'El valor mínimo debe ser al menos 0',
            'maximo.required_if'=>'El valor máximo es obligatorio',
            'maximo.numeric'=>'El valor máximo debe ser numérico',
            'maximo.min'=>'El valor máximo debe ser mayor que '.$this->minimo,
            'respuestas.required_if'=>'Las posibles resuestas son obligatorias',
            'respuestas.array'=>'Las posibles resuestas deben se una lista',
            'respuestas.min'=>'Debe agregar al menos 3 posibles respuestas',
        ];
    }
}
