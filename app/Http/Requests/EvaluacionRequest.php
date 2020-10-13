<?php

namespace App\Http\Requests;

use App\Http\Requests\Request;

class EvaluacionRequest extends Request
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
            'nombre'=>'required|alpha',
            'getrolevaluador.nombre'=>'required|in:"Tutor", "Jefe inmediato", "Estudiante", "Empresa"',
            'getrolevaluado.nombre'=>'required|in:"Tutor", "Jefe inmediato", "Estudiante", "Empresa"',
            'descripcion'=>'string',
        ];
    }
    
    public function messages()
    {
        return [
            'getrolevaluador.nombre.required'=>'El tipo de usuario que realizará la evaluación es obligatorio',
            'getrolevaluador.nombre.in'=>'El tipo de usuario que realizará la evaluación es inválido',
            'getrolevaluado.nombre.required'=>'El tipo de usuario al que se evaluará es obligatorio',
            'getrolevaluado.nombre.in'=>'El tipo de usuario al que se evaluará es inválido',
            // 'getrolevaluado.nombre'=>'required|in:"Tutor", "Jefe inmediato", "Estudiante", "Empresa"',
        ];
    }
}
