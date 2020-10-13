@extends('master.master')
@section('title', 'Usuarios')
@section('contenido')

    <div class="row" ng-controller="EmpUsuariosCtrl">
        <div class="col-md-1"></div>
        <div class="col-md-10">
            <button class="btn btn-primary" data-toggle="modal" data-target="#crearUsuario" ng-click="newUser()">
                Crear jefe
            </button>
            
            <table  object-table
            	data = "usuarios"
            	display = "10"
            	headers = "Identificación, Nombres,Apellidos, Area, Cargo, Correo,Celular, Acciones"
            	fields = "identificacion,nombres,apellidos,rol,area,correo,celular"
            	sorting = "compound"
            	editable = "false"
            	resize="false"
            	drag-columns="false">
                <tbody>
                    <td>
                        @{{ ::item.identificacion }}
                    </td>
                    <td>
                        @{{ ::item.nombres | uppercase }}
                    </td>
                    <td>
                        @{{ ::item.apellidos | uppercase }}
                    </td>
                    <td>
                        @{{ ::item.area | uppercase }}
                    </td>
                    <td>
                        @{{ ::item.cargo | uppercase }}
                    </td>
                    <td>
                        @{{ ::item.correo | uppercase }}
                    </td>
                    <td>
                        @{{ ::item.celular | uppercase }}
                    </td>
                    <td class="text-center">
                        <span data-id="@{{ ::item.id }}" data-toggle="modal" data-target="#crearUsuario" class="glyphicon glyphicon-pencil pointer blue" title="Editar"></span>
                    </td>
                </tbody>
            </table>
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
                                
                                <input type="hidden" value="@{{usuario.id}}" ng-model="usuario.id"/>
                                
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
                                        <input type="text" ng-model="usuario.nombres" class="form-control" placeholder="Nombres"/>
                                        <p class="help-block text-danger">
                                            @{{ errores.nombres[0] }}
                                        </p>
                                    </div>
                                    
                                </div>
                                
                                <div class="form-group">
                                    <label class="col-lg-2" for="apellidos">Apellidos</label>
                                    <div class="col-lg-12">
                                        <input type="text" ng-model="usuario.apellidos" class="form-control" placeholder="Apellidos"/>
                                        <p class="help-block text-danger">
                                            @{{ errores.apellidos[0] }}
                                        </p>
                                    </div>
                                </div>
                                
                                <div class="form-group">
                                    <label class="col-lg-2" for="correo">Correo</label>
                                    <div class="col-lg-12">
                                        <input type="email" ng-model="usuario.correo" class="form-control" placeholder="Correo"/>
                                        <p class="help-block text-danger">
                                            @{{ errores.correo[0] }}
                                        </p>
                                    </div>
                                </div>
                                
                                <div class="form-group">
                                    <label class="col-lg-2" for="correo">Celular</label>
                                    <div class="col-lg-12">
                                        <input type="text" ng-model="usuario.celular" class="form-control" placeholder="Celular"/>
                                        <p class="help-block text-danger">
                                            @{{ errores.celular[0] }}
                                        </p>
                                    </div>
                                    
                                </div>
                                
                                <div class="form-group">
                                    <label class="col-lg-2" for="correo">Area</label>
                                    <div class="col-lg-12">
                                        <input type="text" ng-model="usuario.area" class="form-control" placeholder="Area"/>
                                        <p class="help-block text-danger">
                                            @{{ errores.area[0] }}
                                        </p>
                                    </div>
                                    
                                </div>
                                
                                <div class="form-group">
                                    <label class="col-lg-2" for="correo">Cargo</label>
                                    <div class="col-lg-12">
                                        <input type="text" ng-model="usuario.cargo" class="form-control" placeholder="Cargo"/>
                                        <p class="help-block text-danger">
                                            @{{ errores.cargo[0] }}
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
        <div class="col-md-1"></div>
    </div>

@stop