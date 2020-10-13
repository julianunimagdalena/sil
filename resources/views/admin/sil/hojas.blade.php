@extends('master._adminLayoutNew')
@section('titulo', 'Tablero de administrador')
@section('controller','ng-controller ="AdminHojasdevidaCtrl"')
@section('title')
    <h2>Tablero de administrador</h2>
@endsection

@section('estilos')

    <style>
        #link-hojasvida * {
            color: #fff;
        }
    </style>
  
@endsection

@section('content')

<h3 style="color: #004A87; font-weight: 700">HOJAS DE VIDA</h3>
@section('tituloVista', 'HOJAS DE VIDA')

<div class="row">
    <div class="col-md-12 table-responsive" style="overflow-x: auto">
        
        <table  object-table
                data = "graduados"
                display = "10"
                headers = "Nombre completo, Programa, Ver perfil"
                fields = "empresa,nombre,vacantes,salario,estado"
                sorting = "compound"
                editable = "false"
                resize="false"
                drag-columns="false">            
            <tbody>
                <td>
                    @{{ ::item.nombres + ' ' + item.apellidos | uppercase }}
                </td>
                <td>
                    @{{ item.programas }}
                </td>
                <td class="text-center">                    
                    <a href="@{{ $owner.raiz }}/sil/verperfil/@{{ ::item.id }}" target="_blank" style="color: black !important;">
                        <span class="fa fa-list-alt" title="Ver hoja de vida"></span> 
                    </a>
                </td>
            </tbody>
        </table>
    </div>
</div>    
@endsection