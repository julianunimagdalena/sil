<?php

namespace App\Http\Requests;

use App\Http\Requests\Request;

class EstadoOfertaRequest extends Request
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
            'estado.id'=>'required|numeric|exists:estado_ofertas,id',
            'mensaje'=>'required_if:estado.nombre,Rechazada,Errada',
        ];
    }
    
    public function messages()
    {
        return [
            'estado.id.required'=>'El campo estado es obligatorio',
            'estado.id.numeric'=>'El campo estado es inválido',
            'estado.id.exists'=>'El campo estado es inválido',
            'mensaje.required_if'=>'Especifique motivo'
        ];
    }
}
