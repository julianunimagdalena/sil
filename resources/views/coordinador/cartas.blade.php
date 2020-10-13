@extends('master.master')

@section('title', 'Cartas de presentación')

@section('contenido')

<div class="row" ng-controller="CdnCartasCtrl">
    
    <div class="col-md-1"></div>
    <div class="col-md-10">
        <fieldset>
            <legend>
                Cartas de presentación
            </legend>
            
            <table  object-table
            	data = "cartas"
            	display = "10"
            	headers = "Código, Nombre, Empresa, Ciudad, Estado , Acciones"
            	fields = "identificacion,nombre,correo,rol,dependencia,sede"
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
                        @{{ ::item.empresa | uppercase }}
                    </td>
                    <td>
                        @{{ ::item.ciudad | uppercase }}
                    </td>
                    <td>
                        @{{ ::item.getestado.nombre | uppercase }}
                    </td>
                    <!--<td>-->
                    <!--    @{{ ::item.sede | uppercase }}-->
                    <!--</td>-->
                    <td class="text-center">
                        <span data-id="@{{ ::item.id }}" class="glyphicon glyphicon-pencil pointer blue" 
                            title="Aprobar o desaprobar carta" data-toggle="modal" data-target="#mdlEstado"
                            ></span>
                            <!--ng-show="item.getestado.nombre=='Esperando respuesta'"-->
                    </td>
                </tbody>
            </table>
            
        </fieldset>
    </div>
    <div class="col-md-1"></div>
    
    <div id="mdlEstado" class="modal fade scroll" role="dialog">
        <div class="modal-dialog">
        
            <!-- Modal content-->
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title">Cambiar estado</h4>
                </div>
                <div class="modal-body">
                    <form role="form" ng-submit="cambiarEstadoCarta()">
                        <div class="form-group">
                            <label >Estado</label>
                            <ui-select ng-model="carta.getestado">
                                <ui-select-match>
                                    <span ng-bind="$select.selected.nombre"></span>
                                </ui-select-match>
                                <ui-select-choices repeat="item in (estados | filter: $select.search) track by item.id">
                                    <span ng-bind="item.nombre"></span>
                                </ui-select-choices>
                            </ui-select>
                            <p class="help-block text-danger">
                                @{{ errores['getestado.id'][0] }}
                            </p>
                        </div>
                        <div ng-if="carta.getestado.nombre == 'Aprobada'">
                            <div class="form-group">
                                <label>Tipo de carta</label>
                                <select ng-model="carta.modelo" class="form-control">
                                    <option value="1">Carta con restricción</option>
                                    <option value="2">Carta con restricción y promedio</option>
                                    <option value="3">Carta sin restricción</option>
                                    <option value="4">Carta sin restricción y promedio</option>
                                </select>
                                <p class="help-block text-danger">
                                    @{{ errores['modelo'][0] }}
                                </p>
                            </div>
                            <div class="form-group" ng-if="carta.modelo == 2 || carta.modelo == 4">
                                <label>Promedio del estudiante</label>
                                <input type="text" class="form-control" ng-model="carta.promedio">
                                <p class="help-block text-danger">
                                    @{{ errores['promedio'][0] }}
                                </p>
                            </div>
                            <div class="form-group">
                                <label >Radicado</label>
                                <input type="text" class="form-control" ng-model="carta.radicado" placeholder="Radicado">
                                <p class="help-block text-danger">
                                    @{{ errores['radicado'][0] }}
                                </p>
                            </div>
                            <div class="form-group">
                                <label >Año</label>
                                <input type="text" class="form-control" ng-model="carta.anio" placeholder="Año en el que el estudiante aspira a realizar sus prácticas">
                                <p class="help-block text-danger">
                                    @{{ errores['anio'][0] }}
                                </p>
                            </div>
                            <div class="form-group">
                                <label >Periodo</label>
                                <select ng-model="carta.periodo" class="form-control">
                                    <option value="I">I</option>
                                    <option value="II">II</option>
                                </select>
                                <p class="help-block text-danger">
                                    @{{ errores['periodo'][0] }}
                                </p>
                            </div>
                        </div>
                            
                        
                        <input type="submit" ng-show="false"/>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-rojo" data-dismiss="modal">Close</button>
                    <button class="btn btn-success" ng-click="cambiarEstadoCarta()">Guardar</button>
                </div>
            </div>
        
        </div>
    </div>
    
</div>

@stop