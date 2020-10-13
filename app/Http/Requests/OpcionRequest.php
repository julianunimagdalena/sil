<?php

namespace App\Http\Requests;

use App\Http\Requests\Request;

class OpcionRequest extends Request
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
            'opcion.nombre'=>'required'
        ];
    }
    
    public function messages()
    {
        return [
            'opcion.nombre.required'=>'Es necesario que escriba la opcion de respuesta',
        ];
    }
}
