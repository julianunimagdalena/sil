<?php

namespace App\Http\Requests;

use App\Http\Requests\Request;

class CartaRequest extends Request
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
        // dd($this->all(), "hola");
        return [
            'empresa.id'=>'required|exists:empresas,id',
            'ciudadExpedicion'=>'required|alpha',
        ];
    }
    
    public function messages()
    {
        return [
            'empresa.id.required'=>'El campo empresa es obligatorio',
            'empresa.id.exists'=>'El campo empresa es inválido',
        ];
    }
}
