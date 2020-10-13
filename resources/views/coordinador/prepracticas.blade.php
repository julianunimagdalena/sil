@extends('master.master')

@section('title', 'Pre-prácticas')

@section('contenido')

<div class="row" ng-controller="CdnPrepracticasCtrl">
    <div class="col-md-1"></div>
    <div class="col-md-10">
        <fieldset>
            <legend>Pre-praticantes</legend>
            <div class="row separator">
                <div class="col-md-10 text-left">
                    <button class="btn btn-primary" ng-click="aprobarPrepracticas()" ng-if="estudiantes_id.length > 0">
                        Aprobar
                    </button>
                </div>
                <div class="col-md-2 text-right">
                    <img src="/img/seleccionarTodo.png" ng-if="estudiantes_id.length == 0 && estudiantes.length > 0"  ng-click="seleccionarTodo()">
                    <img src="/img/seleccionParcial.png" ng-if="estudiantes_id.length > 0 && estudiantes_id.length < estudiantes.length" ng-click="quitarSeleccion()">
                    <img src="/img/seleccionTotal.png" ng-if="estudiantes_id.length > 0 && estudiantes_id.length == estudiantes.length" ng-click="quitarSeleccion()"> 
                </div>
            </div>
            
                
            
            
            <table  object-table
            	data = "estudiantes"
            	display = "10"
            	headers = "Código, Nombre, Programa, Acciones"
            	fields = "getempresa.nit,getempresa.nombre,getmunicipio.getdepartamento.getpais.nombre,getmunicipio.getdepartamento.nombre,getmunicipio.nombre, getempresa.getestadodipro.nombre"
            	sorting = "compound"
            	editable = "false"
            	resize="false"
            	drag-columns="false">
                <tbody>
                    <td>
                        @{{ ::item.codigo }}
                    </td>
                    <td>
                        @{{ ::item.getpersona.nombres | uppercase }}
                        @{{ ::item.getpersona.apellidos | uppercase }}
                    </td>
                    <td>
                        @{{ ::item.getprograma.nombre | uppercase }}
                    </td>
                    <td class="text-center">
                        <input type="checkbox" ng-click="$owner.seleccionar(item.id)" ng-model="item.seleccionado"/>
                    </td>
                </tbody>
            </table>
            
        </fieldset>
    </div>
    <div class="col-md-1"></div>
</div>

@stop