@extends('master.master')
@section('title', 'Usuario')
@section('contenido')
    <div class="row" ng-controller="usuariosCtrl">
        <div class="col-md-1">
            
        </div>
        <div class="col-md-10">
            <fieldset>
                <legend>Administración de usuarios</legend>
                
                <div class="row">
                    <div class="col-md-6">
                        <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#crearUsuario" ng-click="newUser()">
                            Nuevo usuario
                        </button>
                    </div>
                    <div class="col-md-6">
                    </div>
                    <br><br>
                    <div class="col-md-12">
                        <table  object-table
                        	data = "usuarios"
                        	display = "10"
                        	headers = "Identificación, Nombre, Correo,Rol,Dependencia, Acciones"
                        	fields = "identificacion,nombre,correo,rol,dependencia,sede"
                        	sorting = "compound"
                        	editable = "false"
                        	resize="false"
                        	drag-columns="false">
                            <tbody>
                                <td>
                                    @{{ ::item.identificacion }}
                                </td>
                                <td>
                                    @{{ ::item.getuser.nombres | uppercase }}
                                    @{{ ::item.getuser.apellidos | uppercase }}
                                </td>
                                <td>
                                    @{{ ::item.getuser.correo | uppercase }}
                                </td>
                                <td>
                                    @{{ ::item.getrol.nombre | uppercase }}
                                </td>
                                <td>
                                    <span ng-show="item.getdependencias.length == 1">
                                        @{{ ::item.getdependencias[0].nombre | uppercase }}
                                    </span>
                                </td>
                                <!--<td>-->
                                <!--    @{{ ::item.sede | uppercase }}-->
                                <!--</td>-->
                                <td class="text-center">
                                    <span data-id="@{{ ::item.id }}" data-toggle="modal" data-target="#crearUsuario" class="glyphicon glyphicon-pencil pointer blue" title="Editar"></span>
                                </td>
                            </tbody>
                        </table>
                    </div>
                </div>
            </fieldset>
            
        </div>
        <div class="col-md-1">
            
        </div>
        <!-- Modal -->
        <div id="crearUsuario" class="modal fade" role="dialog">
            <div class="modal-dialog">
        
                <!-- Modal content-->
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                        <h4 class="modal-title"><span ng-show="!editar">Crear</span><span ng-show="editar">Editar</span> usuario</h4>
                    </div>
                    <div class="modal-body">
                        <form role="form" class="form-horizontal" ng-submit="guardarUsuario()">
                            
                            <div class="form-group">
                                <label class="col-lg-2" for="rol">Rol</label>
                                <div class="col-lg-12">
                                    <input type="hidden" value="@{{usuario.id}}" ng-model="usuario.id"/>
                                    <ui-select ng-model="usuario.getrol">
                                        <ui-select-match>
                                            <span ng-bind="$select.selected.nombre"></span>
                                        </ui-select-match>
                                        <ui-select-choices repeat="rol in (datos.roles | filter: $select.search) track by rol.id">
                                            <span ng-bind="rol.nombre"></span>
                                        </ui-select-choices>
                                    </ui-select>
                                    <p class="help-block text-danger">
                                        @{{ errores.getrol[0] }}
                                    </p>
                                </div>
                            </div>
                            
                            <div class="form-group" ng-show="usuario.getrol.nombre == 'Coordinador de programa'">
                                <label class="col-lg-2" for="rol">Dependencia</label>
                                <div class="col-lg-12">
                                    <ui-select ng-model="usuario.dependencias">
                                        <ui-select-match>
                                            <span ng-bind="$select.selected.nombre"></span>
                                        </ui-select-match>
                                        <ui-select-choices repeat="dep in (datos.programas | filter: $select.search) track by dep.id">
                                            <span ng-bind="dep.nombre"></span>
                                        </ui-select-choices>
                                    </ui-select>
                                    <p class="help-block text-danger">
                                        @{{ errores.getdependencias[0] }}
                                    </p>
                                </div>
                            </div>
                            
                            <div class="form-group" ng-show="usuario.getrol.nombre == 'Coordinador'">
                                <label class="col-lg-12" for="rol">Programas de los que se encargará en coordinador</label>
                                <div class="col-lg-12">
                                    <ui-select multiple ng-model="usuario.dependencias" sortable="true" close-on-select="false">
                                        <ui-select-match placeholder="Seleccione los programas">@{{$item.nombre}}</ui-select-match>
                                        <ui-select-choices repeat="programa in (datos.programas | filter: $select.search) track by programa.id">
                                            <small>
                                                @{{programa.nombre}}
                                            </small>
                                        </ui-select-choices>
                                    </ui-select>
                                    <p class="help-block text-danger">
                                        @{{ errores.getdependencias[0] }}
                                    </p>
                                </div>
                            </div>
                            
                            <div class="form-group">
                                <label class="col-lg-2" for="identificacion">Identificación</label>
                                <div class="col-lg-12">
                                    <input type="text" ng-model="usuario.identificacion" class="form-control" placeholder="Identificaión" />
                                    <p class="help-block text-danger">
                                        @{{ errores.identificacion[0] }}
                                    </p>
                                </div>
                                    
                            </div>
                            
                            <div class="form-group">
                                <label class="col-lg-2" for="nombres">Nombres</label>
                                <div class="col-lg-12">
                                    <input type="text" ng-model="usuario.getuser.nombres" class="form-control" placeholder="Nombres"/>
                                    <p class="help-block text-danger">
                                        @{{ errores['getuser.nombres'][0] }}
                                    </p>
                                </div>
                                
                            </div>
                            
                            <div class="form-group">
                                <label class="col-lg-2" for="apellidos">Apellidos</label>
                                <div class="col-lg-12">
                                    <input type="text" ng-model="usuario.getuser.apellidos" class="form-control" placeholder="Apellidos"/>
                                    <p class="help-block text-danger">
                                        @{{ errores['getuser.apellidos'][0] }}
                                    </p>
                                </div>
                            </div>
                            
                            <div class="form-group">
                                <label class="col-lg-2" for="correo">Correo</label>
                                <div class="col-lg-12">
                                    <input type="email" ng-model="usuario.getuser.correo" class="form-control" placeholder="Correo"/>
                                    <p class="help-block text-danger">
                                        @{{ errores['getuser.correo'][0] }}
                                    </p>
                                </div>
                            </div>
                            
                            <div class="form-group">
                                <label class="col-lg-2" for="correo">Celular</label>
                                <div class="col-lg-12">
                                    <input type="text" ng-model="usuario.getuser.celular" class="form-control" placeholder="Celular"/>
                                    <p class="help-block text-danger">
                                        @{{ errores['getuser.celular'][0] }}
                                    </p>
                                </div>
                            </div>
                            <button type="submit" ng-show="false"></button>
                        </form>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-rojo" data-dismiss="modal">Cerrar</button>
                        <button type="submit" class="btn btn-success" ng-click="guardarUsuario()" >Guardar</button>
                    </div>
                </div>
        
            </div>
        </div>
    </div>
    
        
    
    <!--
    
                    
    
    -->

@stop