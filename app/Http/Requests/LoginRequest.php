<?php

namespace App\Http\Requests;

class LoginRequest extends Request
{

    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        $rules = [
            'identificacion' => 'required|string',
            'password' => 'required',
        ];

        if ($this->rol) $rules['rol.id'] = 'exists:roles,id';

        return $rules;
    }

}