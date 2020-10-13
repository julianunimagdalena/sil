<?php

namespace App\Http\Requests;

class CambiarEstadoEmpresaRequest extends Request 
{
    
    public function authorize()
    {
        return true;
    }
    
    
    public function rules()
    {
        return [
            'id' => 'required|exists:empresas,id',
            'estado.id' => 'required|numeric|exists:estadoempresas,id',
            'motivo_cancelacion' => 'required_if:estado.id,4'
        ];
    }
    
}