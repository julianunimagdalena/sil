<?php

namespace App\Http\Requests;

use App\Http\Requests\Request;

class DocumentosRequest extends Request
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
            'file_existencia'=>'mimes:pdf|max:1024',
            'file_cedula'=>'mimes:pdf|max:1024',
            'file_procuraduria'=>'mimes:pdf|max:1024',
            'file_contraloria'=>'mimes:pdf|max:1024',
            'file_rut'=>'mimes:pdf|max:1024',
            'file_posesion'=>'mimes:pdf|max:1024',
            'file_acto_administrativo'=>'mimes:pdf|max:1024',
            'file_militar'=>'mimes:pdf|max:1024',
        ];
    }
    
    public function messages()
    {
        return [
            'file_existencia.mimes'=>'El certificado de existensia y representación legal debe ser formato pdf.',
            'file_existencia.max'=>'Todos los archivos deben ser de tamaño menor o igual a 1MB.',
            // 'file_cedula'=>'mimes:pdf|max:1024',
            // 'file_procuraduria'=>'mimes:pdf|max:1024',
            // 'file_contraloria'=>'mimes:pdf|max:1024',
            // 'file_rut'=>'mimes:pdf|max:1024',
            // 'file_posesion'=>'mimes:pdf|max:1024',
            // 'file_acto_administrativo'=>'mimes:pdf|max:1024',
            // 'file_militar'=>'mimes:pdf|max:1024',
        ];
    }
}
