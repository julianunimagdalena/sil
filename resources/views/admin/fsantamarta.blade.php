@extends('master.master')

@section('title', 'Prácticas fuera de Santa Marta')

@section('contenido')

<div class="row" ng-controller="AdminFSantaMartaCtrl">
    
    <div class="col-md-1"></div>
    <div class="col-md-10">
        <fieldset>
            <legend>Vinculación laboral</legend>
            <div class="row separator">
                <div class="col-md-12">
                    <a href="@{{ raiz }}/admin/fsantamartaexcel/@{{periodo}}" class="btn btn-primary" style="color:white !important;" target="_blank">Exportar excel</a>        
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
            	data = "fsantamarta"
            	display = "10"
            	headers = "Código, Nombres, Organización, Ciudad, Programa, Periodo"
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
                        @{{ ::item.getestudiante.getpostulaciones[ item.getestudiante.getpostulaciones.length - 1].getoferta.getsede.getempresa.nombre | uppercase }}
                    </td>
                    <td>
                        @{{ ::item.getestudiante.getpostulaciones[ item.getestudiante.getpostulaciones.length - 1].getoferta.getsede.getmunicipio.nombre | uppercase }}
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