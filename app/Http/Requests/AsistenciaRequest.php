<?php

namespace App\Http\Requests;

use App\Http\Requests\Request;

class AsistenciaRequest extends Request
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
            'conferencia.id'=>'required|exists:conferencia,id',
            'estudiantes'=>'required|min:1',
        ];
    }
    
    public function messages()
    {
        return[
            'conferencia.id.required'=>'El campo conferencia es obligatorio',
            'conferencia.id.exists'=>'El campo conferencia es inválido',
        ];
    }
}
