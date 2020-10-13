@extends('master.master')

@section('title', 'Conferencias')

@section('contenido')

<div class="row" ng-controller="EstConferenciasCtrl">
    
    <div class="col-md-1"></div>
    <div class="col-md-10">
        
        <div class="alert alert-info" ng-if="faltante > 0 ">
            <strong>¡Atento!</strong> Aún le faltan @{{ faltante }} conferencias por registrar
        </div>
        
        <table  object-table
        	data = "conferencias"
        	display = "10"
        	headers = "Charla, lugar, Orador, Fecha, Horario,Cupo, Acciones"
        	fields = "identificacion,nombre,correo,rol,dependencia,sede"
        	sorting = "compound"
        	editable = "false"
        	resize="false"
        	drag-columns="false">
            <tbody>
                <td>
                    @{{ ::item.getconferencia.nombre }}
                </td>
                <td>
                    @{{ ::item.lugar }}
                </td>
                <td>
                    @{{ ::item.getorador.nombres }}
                    @{{ ::item.getorador.apellidos }}
                </td>
                <td>
                    @{{ ::item.fecha | date:'dd/MM/yyyy'}}
                </td>
                <td>
                    @{{ ::item.horaInicial }}
                    -
                    @{{ ::item.horaFinal }}
                </td>
                <td>
                    @{{ (item.cupo - item.getasistencias.length)+"/"+item.cupo }}
                </td>
                <td class="text-center">
                    <span class="glyphicon glyphicon-plus blue" title="Agregar conferencia" ng-click="$owner.agregarConferencia(item.id)"></span>
                </td>
            </tbody>
        </table>
    </div>
    <div class="col-md-1"></div>
    
</div>

@stop