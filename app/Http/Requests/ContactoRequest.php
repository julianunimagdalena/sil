<?php

namespace App\Http\Requests;

use App\Http\Requests\Request;

class ContactoRequest extends Request
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
            'nombres'=>'required|alpha',
            'apellidos'=>'required|alpha',
            'tipo_de_identificacion'=>'required|exists:tipo_documentos,id',
            'identificacion'=>'required|numeric',
            'correo'=>'required|email',
            'celular'=>'required|numeric',
            'codigo_de_verificacion'=>'required',
            'comentario'=>'required|string',
        ];
    }    
}
