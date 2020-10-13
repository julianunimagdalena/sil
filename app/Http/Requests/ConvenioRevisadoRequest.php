<?php

namespace App\Http\Requests;

use App\Http\Requests\Request;

class ConvenioRevisadoRequest extends Request
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
            'file_minuta'=>'required_if:estado,2|mimes:pdf,docx|max:1024',
            'observacion'=>'required_if:estado,1|string'
        ];
    }
    
    public function messages()
    {
        return[
            'file_minuta.required_if'=>'El archivo munita es obligatorio cuando se va a enviar en convenio a la oficina de jurídica',
            'file_minuta.mimes'=>'El archivo munita debe ser formato pdf o docx',
            'file_minuta.max'=>'El archivo munita debe ser de tamaño máximo 1 MB',
            'observacion.required_if'=>'Es necesario explicar porque se devuelve el convenio a la empresa',
        ];
    }
}
