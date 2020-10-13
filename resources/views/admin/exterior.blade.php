@extends('master.master')

@section('title', 'Estudiantes en el exterior')

@section('contenido')

<div class="row" ng-controller="AdminExteriorCtrl">
    <div class="col-md-1"></div>
    <div class="col-md-10">
        <fieldset>
            <legend>Estudiantes en el exterior</legend>
            <div class="row separator">
                <div class="col-md-12">
                    <a href="@{{ raiz }}/admin/exteriorexcel/@{{periodo}}" class="btn btn-primary" style="color:white !important;" target="_blank">Exportar excel</a>        
                </div>
            </div>
            <div class="row">
                <div class="col-md-2">
                    Periodo 
                    <select ng-model="periodo" class="form-control" ng-change="filtrar()">
                        <option value="0">
                            Todos
                        </option>
                        <option ng-repeat="p in periodos" value="@{{p.periodo}}">
                            @{{p.periodo}}
                        </option>
                    </select>
                </div>
            </div>
                
            
            <div class="col-md-10"></div>
            
            <table  object-table
            	data = "exterior"
            	display = "10"
            	headers = "Código, Nombres, Modalidad,Organización, Ciudad, Programa, Periodo"
            	fields = "getempresa.nit,getempresa.nombre,getmunicipio.getdepartamento.getpais.nombre,getmunicipio.getdepartamento.nombre,getmunicipio.nombre, getempresa.getestadodipro.nombre"
            	sorting = "compound"
            	editable = "false"
            	resize="false"
            	drag-columns="false">
                <tbody>
                    <td>
                        @{{ ::item.getestudiante.codigo }}
                    </td>
                    <td>
                        @{{ ::item.getestudiante.getpersona.nombres | uppercase }}
                        @{{ ::item.getestudiante.getpersona.apellidos | uppercase }}
                    </td>
                    <td>
                        @{{ ::item.getmodalidad.nombre | uppercase }}
                    </td>
                    <td>
                        @{{ ::item.empresa | uppercase }}
                    </td>
                    <td>
                        @{{ ::item.getciudad.nombre | uppercase }},
                        @{{ ::item.getciudad.getdepartamento.getpais.nombre | uppercase }}
                    </td>
                    <td>
                        @{{ ::item.getestudiante.getprograma.nombre | uppercase }}
                    </td>
                    <td>
                        @{{ ::item.periodo }}
                    </td>
                </tbody>
            </table>
            
        </fieldset>
    </div>
    <div class="col-md-1"></div>
</div>

@stop