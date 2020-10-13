@extends('master.master')

@section('title', 'Evaluaciones')

@section('contenido')

<div class="row" ng-controller="AdminEvaluacionesCtrl">
    
    <div class="col-md-1"></div>
    <div class="col-md-10">
        <a href="{{asset('/adminsil/crearevaluacion')}}" class="btn btn-primary text-white">
            Crear evaluación
        </a>
        <table  object-table
        	data = "evaluaciones"
        	display = "10"
        	headers = "Rol evaluador, Rol evaluado, Nombre,Descripción, Acciones"
        	fields = "identificacion,nombre,correo,rol,dependencia,sede"
        	sorting = "compound"
        	editable = "false"
        	resize="false"
        	drag-columns="false">
            <tbody>
                <td>
                    @{{ ::item.getrolevaluador.nombre | uppercase }}
                </td>
                <td>
                    @{{ ::item.getrolevaluado.nombre | uppercase }}
                </td>
                <td>
                    @{{ ::item.nombre | uppercase }}
                </td>
                <td>
                    @{{ ::item.descripcion | uppercase }}
                </td>
                <!--<td>-->
                <!--    @{{ ::item.sede | uppercase }}-->
                <!--</td>-->
                <td class="text-center">
                    <a href="@{{ $owner.raiz }}/adminsil/crearevaluacion/@{{::item.id}}">
                        <span class="glyphicon glyphicon-pencil pointer" title="Editar"></span>
                    </a>
                    <span class="glyphicon glyphicon-ok blue pointer" title="Activar evaluación" ng-show="!item.estado" ng-click="$owner.cambiarEstado(item.id)"></span>
                    <span class="glyphicon glyphicon-remove blue pointer" title="Desactivar evaluación" ng-show="item.estado" ng-click="$owner.cambiarEstado(item.id)"></span>
                </td>
            </tbody>
        </table>
    </div>
    <div class="col-md-1"></div>
    
</div>

@stop