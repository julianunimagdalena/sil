<?php

namespace App\Http\Requests;

use App\Http\Requests\Request;

class ReferenciaRequest extends Request
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
        if($this->path()=='estudiante/referenciapersonal' || $this->path()=='graduado/referenciapersonal')
        {
            return [
                'nombre'=>'required|alpha',
                'ocupacion'=>'required|alpha',
                'telefono'=>'required|numeric',
            ];
        }
        else if($this->path()=='estudiante/referenciafamiliar' || $this->path()=='graduado/referenciafamiliar')
        {
            return [
                'nombre'=>'required|alpha',
                'ocupacion'=>'required|alpha',
                'telefono'=>'required|numeric',
                'getparentesco.id'=>'required|numeric|exists:parentesco,id',
            ];
        }
        
    }
    
    public function messages()
    {
        return[
            'getparentesco.id.required'=>'El parentesco es obligatorio',
            'getparentesco.id.numeric'=>'El parentesco es inválido',
            'getparentesco.id.exists'=>'El parentesco es inválido',
        ];
    }
}
