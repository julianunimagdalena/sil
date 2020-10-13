<?php

namespace App\Http\Requests;

use App\Http\Requests\Request;

class RevisarConvenioJuridicaRequest extends Request
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
            'estado'=>'required|in:1,2',
            'observacion'=>'required_if:estado,2'
        ];
    }
    
    public function messages()
    {
        return [
            'observacion.required_if'=>'Las observaciones son obligatorias cuando algún documento está errado'
        ];
    }
}
