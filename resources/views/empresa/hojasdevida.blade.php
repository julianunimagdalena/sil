@extends('master._adminLayoutNew')
@section('titulo', 'Tablero de Empresa - ')
@section('controller','ng-controller ="EmpHojasdevidaCtrl"')
@section('title')
    <h2>Tablero de empresa</h2>
@endsection

@section('estilos')

    <style>
        #link-hojasvida * {
            color: #fff;
        }
    </style>
  
@endsection

@section('content')
<h3 style="color: #004A87; font-weight: 700">HOJA DE VIDA</h3>
<div class="alert alert-info">Se muestran los perfiles de los graduados con programas basados en sus ofertas.</div>

<div class="row">
<div class="col-md-12">
	<table  object-table
        	data = "graduados"
        	display = "10"
        	headers = "Nombre completo, Programa, Ver perfil"
        	fields = "empresa,nombre,vacantes,salario, estado"
        	sorting = "compound"
        	editable = "false"
        	resize="false"
        	drag-columns="false">            
            <tbody>
                <td>
                    @{{ ::item.nombres + ' ' + item.apellidos | uppercase }}
                </td>
                <td>
                    @{{ ::item.programas | uppercase }}
                </td>
                <td class="text-center">                    
                    <a style="color: black;" href="{{ Request::root() }}/empresa/verperfil/@{{::item.id}}" target="_blank">
                        <span class="fas fa-list-alt" title="Ver hoja de vida"></span> 
                    </a>
                    <span class="fas fa-paper-plane" title="Invitar graduado" ng-show="item.mostrar" ng-click="$owner.invitar(item.id)"></span> 

                </td>
            </tbody>
        </table>

</div>
<div class="col-md-1"></div>
	
</div>

@stop