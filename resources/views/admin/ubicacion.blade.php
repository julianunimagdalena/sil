@extends('master.master')

@section('title', 'Prácticas fuera de Santa Marta')

@section('contenido')

<div class="row" ng-controller="AdminUbicacionCtrl">
    
    <div class="col-md-1"></div>
    <div class="col-md-10">
        <fieldset>
            <legend>Vinculación laboral</legend>
            <div class="row separator">
                <div class="col-md-12">
                    <a href="@{{ raiz }}/admin/ubicacionexcel/@{{periodo}}" class="btn btn-primary" style="color:white !important;" target="_blank">Exportar excel</a>        
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
            	data = "ubicacion"
            	display = "25"
            	headers = "Programa, Internacional, Empresarismo, PYMES, Validación, Vinculación, Exterior, Social, Total"
            	fields = "Programa, Internacional, Empresarismo, PYMES, Validacion, Vinculacion, Exterior, Social, Total"
            	sorting = "compound"
            	editable = "false"
            	resize="false"
            	drag-columns="false">
                <tbody>
                    <td>
                        @{{ ::item.Programa }}
                    </td>
                    <td>
                        @{{ ::item.Internacional }}
                    </td>
                    <td>
                        @{{ ::item.Empresarismo }}
                    </td>
                    <td>
                        @{{ ::item.PYMES }}
                    </td>
                    <td>
                        @{{ ::item.Validacion }}
                    </td>
                    <td>
                        @{{ ::item.Vinculacion }}
                    </td>
                    <td>
                        @{{ ::item.Exterior }}
                    </td>
                    <td>
                        @{{ ::item.Social }}
                    </td>
                    <td>
                        @{{ ::item.Total }}
                    </td>
                </tbody>
            </table>
            
        </fieldset>
    </div>
    <div class="col-md-1"></div>
    
</div>

@stop