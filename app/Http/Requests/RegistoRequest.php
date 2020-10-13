<?php

namespace App\Http\Requests;

use App\Models\User;

class RegistoRequest extends Request 
{
    
    public function authorize()
    {
        return true;
    }
    
    
    public function rules()
    {
        
        if($this->rol['nombre'] == 'Estudiante')
        {
            return [
                'rol.id'=>'required|numeric',
                'tipoEstudiante.id'=>'required|numeric|exists:tipoestudiantes,id',
                'modalidad.id'=>'required_if:tipoEstudiante.nombre,"Prácticas y preprácticas","Prácticas"|
                                 numeric|exists:modalidades,id',
                'codigo' => 'required|numeric|unique:estudiantes,codigo',
                'password' => 'required',
            ];
        }
        else if($this->rol['nombre'] == 'Empresa')
        {
            return [
                'rol.id'=>'required|numeric|exists:roles,id',
                'tipoNit.id'=>'required|numeric|exists:tiponit,id',
                'nit' => 'required|alpha_dash', //numeric_dash
                // 'nit' => 'required|numeric|unique:empresas,nit', //numeric_dash
                'file_nit'=>'required|mimes:pdf|max:1024',
                'empresa' => 'required|string|unique:empresas,nombre',
                'tipoEmpleador.id'=>'required|numeric|exists:tipoempleador,id',
                'actividad.id'=>'required|numeric|exists:actividades_economicas,id',
                'telefono'=>'numeric',
                'pagina'=>'string',
                'email'=>'email',
                'municipio.id'=>'required|numeric|exists:municipios,id',
                'direccion'=>'required|string',
                'nombres' => 'required|alpha',
                'apellidos' => 'required|alpha',
                'identificacion' => 'required|numeric',
                'correo' => 'required|email',
                'celular' => 'required|numeric',
                'password' => 'required|string',
                'passwordconfirmada' => 'required|string|same:password',
                'nombres_representante' => 'required|alpha',
                'apellidos_representante' => 'required|alpha',
                'tipodoc_representante.id' => 'required|numeric|exists:tipo_documentos,id',
                'tipodoc_representante.nombre' => 'in:"Cédula de ciudadanía","Documento extranjero","Cédula extranjería"',
                'identificacion_representante' => 'required|numeric',
                'correo_representante' => 'required|email',
                'tipo_documento.id' => 'required|numeric|exists:tipo_documentos,id',
                'tipo_documento.nombre' => 'in:"Cédula de ciudadanía","Documento extranjero","Cédula extranjería"',
            ];
        }
        else if($this->rol['nombre'] == 'Graduado')
        {
            return [
                'rol.id'=>'required|numeric',
                'identificacion' => 'required|numeric|unique:usuarios,identificacion',
                'tipodoc.id' => 'required|numeric|exists:tipo_documentos,id',
            ];
        }
        else 
        {
            return [
                'rol.id'=>'required|in:@',
                'seguridad' => 'required',
            ];
        }
    }
    
    public function messages()
    {
        return [
            'rol.id.required' =>'El campo rol es obligatorio',
            'rol.id.numeric' =>'El campo rol debe ser numérico',
            'rol.id.exists' =>'El campo rol es invalido',
            'rol.id.in' =>'El campo rol es invalido',
            
            'tipoEstudiante.id.required' =>'El campo étapa es obligatorio',
            'tipoEstudiante.id.numeric' =>'El campo étapa debe ser numérico',
            'tipoEstudiante.id.exists' =>'El campo étapa es invalido',
            
            'modalidad.id.required_if' =>'El campo modalidad es obligatorio cuando la etapa es de prácticas',
            'modalidad.id.numeric' =>'El campo modalidad debe ser numérico',
            'modalidad.id.exists' =>'El campo modalidad es invalido',
            
            'tipo_documento.id.required' => 'El campo tipo de documento es obligatorio',
            'tipo_documento.id.numeric' => 'El campo tipo de documento es inválido',
            'tipo_documento.id.exists' => 'El campo tipo de documento es inválido',
            'tipo_documento.nombre.in' => 'El campo tipo de documento es inválido',
            
            'tipodoc_representante.id.required' => 'El campo tipo de documento es obligatorio',
            'tipodoc_representante.id.numeric' => 'El campo tipo de documento es inválido',
            'tipodoc_representante.id.exists' => 'El campo tipo de documento es inválido',
            'tipodoc_representante.nombre.in' => 'El campo tipo de documento es inválido',
            
            'file_nit.required'=>'Adjuntar el nit es obligatorio',
            'file_nit.mimes'=>'El nit debe ser formato PDF',
            'file_nit.max'=>'El nit debe pesar máximo 1 MB',

            'tipodoc.id.required' => 'required|numeric|exists:tipo_documentos,id',
            'tipodoc.id.required' => 'El tipo de documento es obligatorio',
            'tipodoc.id.numeric' => 'El tipo de documento es inválido',
            'tipodoc.id.exists' => 'El tipo de documento es inválido',
            
        ];
    }
    
}