<?php

namespace App\Http\Requests;

use App\Http\Requests\Request;

class ClaveRequest extends Request
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
        if($this->path() == 'home/generarclave')
        {
            return [                
                'nueva'=>'required|string',
                'confirmacion'=>'required|string|same:nueva',
            ];
        }
        else if($this->path() == 'home/cambiarclave')
        {
            return [
                'actual'=>'required|string',
                'nueva'=>'required|string|different:actual',
                'confirmacion'=>'required|string|same:nueva',
            ];
        }
            
    }

    public function messages()
    {
        return [
            'actual.required'=>'La contraseña actual es obligatoria',
            'actual.string'=>'La contraseña actual es inválida',
            'nueva.required'=>'La nueva contraseña es obligatoria',
            'nueva.string'=>'La nueva contraseña es inválida',
            'nueva.different'=>'La nueva contraseña debe ser diferente de la contraseña actual',
            'confirmacion.required'=>'La confirmación de la contraseña es obligatoria',
            'confirmacion.string'=>'La confirmación de la contraseña es inválida',
            'confirmacion.same'=>'La confirmación de la contraseña no coincide',
        ];
    }
}
