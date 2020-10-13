<?php

namespace App\Http\Requests;

use App\Http\Requests\Request;
use App\Models\User;

class JefeRequest extends Request
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
        $usuario = User::find($this->id);
        if(sizeof($usuario)>0)
        {
            $id = $usuario->id;
            $idPersona = $usuario->idPersona;
        }
        else
        {
            $id=0;
            $idPersona=0;
        }
        return [
            'id'=>'numeric|exists:usuarios,id',
            'nombres'=>'required|alpha',
            'apellidos'=>'required|alpha',
            'correo'=>'required|email|unique:personas,correo,'.$idPersona,
            'celular'=>'required|numeric',
            'area'=>'required|alpha',
            'cargo'=>'required|alpha',
            'identificacion'=>'required|numeric|unique:usuarios,identificacion,'.$id,
        ];
    }
}
