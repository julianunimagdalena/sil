@extends('master.master')

@section('title', 'Conferencias')

@section('contenido')

<div class="row" ng-controller="AdminConferenciasCtrl">
    
    <div class="col-md-1"></div>
    <div class="col-md-10">
        <fieldset>
            <legend>
                Conferencias
            </legend>
            <button class="btn btn-primary" data-toggle="modal" data-target="#mdlConferencia" ng-click="accion = 'Nueva'">
                Nueva conferencia
            </button>
            <table  object-table
            	data = "conferencias"
            	display = "10"
            	headers = "Nombre, Valoración, Acciones"
            	fields = "identificacion,nombre,correo,rol,dependencia,sede"
            	sorting = "compound"
            	editable = "false"
            	resize="false"
            	drag-columns="false">
                <thead class="text-center">
                    <th>
                        Nombre
                    </th>
                    <th>
                        Programas para los que es obligatoria
                    </th>
                    <th style="width:20%;">
                        Valoración
                    </th>
                    <th style="width:20%;">
                        Acciones
                    </th>
                </thead>
                <tbody>
                    <td>
                        @{{ ::item.nombre }}
                    </td>
                    <td>
                        @{{ ::item.nombreProgramas }}
                    </td>
                    <td>
                        @{{ ::item.valoracion | number:1 }}
                    </td>
                    <td class="text-center" >
                        <span class="glyphicon glyphicon-pencil blue" title="Editar conferencia" ng-click="$owner.accionar()"
                            data-id="@{{ item.id }}" data-toggle="modal" data-target="#mdlConferencia"></span>
                    </td>
                </tbody>
            </table>
        </fieldset>
    </div>
    <div class="col-md-1"></div>
    
    <!-- Modal -->
    <div id="mdlConferencia" class="modal fade" role="dialog">
        <div class="modal-dialog">
        
            <!-- Modal content-->
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title">@{{accion}} conferencia</h4>
                </div>
                <div class="modal-body">
                    <form role="form" ng-submit="crearConferencia()">
                        <div class="form-group">
                            <label >Nombre</label>
                            <input type="text" class="form-control" ng-model="conferencia.nombre" placeholder="Nombre de la conferencia">
                            <p class="help-block text-danger">
                                @{{ errores.nombre[0] }}
                            </p>
                            <button type="submit" ng-show="false"></button>
                        </div>
                        <div class="form-group">
                            <label >Programas para los que es obligatoria la conferencia</label>
                            
                            <ui-select multiple ng-model="conferencia.getprogramas" sortable="true" close-on-select="false">
                                <ui-select-match placeholder="Seleccione los programas">@{{$item.nombre}}</ui-select-match>
                                <ui-select-choices repeat="item in (programas | filter: $select.search) track by item.id">
                                    <small>
                                        @{{item.nombre}}
                                    </small>
                                </ui-select-choices>
                            </ui-select>
                            <p class="help-block text-danger">
                                @{{ errores.programas[0] }}
                            </p>
                            
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-rojo" data-dismiss="modal">Cerrar</button>
                    <button type="button" class="btn btn-success" ng-click="crearConferencia()">Guardar</button>
                </div>
            </div>
        
        </div>
    </div>
    
</div>

@stop

