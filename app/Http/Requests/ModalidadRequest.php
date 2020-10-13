<?php

namespace App\Http\Requests;

use App\Http\Requests\Request;

class ModalidadRequest extends Request
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
            'tipo.id'=>'required|exists:modalidades,id'
        ];
    }
    
    public function messages()
    {
        return [
            'tipo.id.required'=>'La modalidad es obligatoria',
            'tipo.id.exists'=>'La modalidad es inválida',
        ];
    }
}
