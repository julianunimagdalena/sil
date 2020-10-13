@extends('master.master')
@section('title', 'Usuario')
@section('contenido')
    <div class="row" ng-controller="SilUsuariosCtrl">
        <div class="col-md-1">
            
        </div>
        <div class="col-md-10">
            <fieldset>
                <legend>Administración de usuarios</legend>
                
                <div class="row">
                    <div class="col-md-6">
                        <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#crearUsuario" ng-click="newUser()">
                            Nuevo graduado
                        </button>
                    </div>
                    <div class="col-md-6">
                    </div>
                    <br><br>
                    <div class="col-md-12">
                        <table  object-table
                        	data = "usuarios"
                        	display = "10"
                        	headers = "Identificación, Nombre, Correo,Rol, Activo"
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
                                    <span ng-show="item.getrol.nombre == 'Graduado'">
                                        @{{ ::item.getuser.nombres | uppercase }}
                                        @{{ ::item.getuser.apellidos | uppercase }}
                                    </span>
                                    <span ng-show="item.getrol.nombre == 'Empresa'">
                                        @{{ ::item.getsede.getempresa.nombre | uppercase }}
                                    </span>
                                </td>
                                <td>
                                    @{{ ::item.getuser.correo | uppercase }}
                                </td>
                                <td>
                                    @{{ ::item.getrol.nombre | uppercase }}
                                </td>                                
                                <!--<td>-->
                                <!--    @{{ ::item.sede | uppercase }}-->
                                <!--</td>-->
                                <td class="text-center">
                                    <img src="{{ asset('/img/on.png') }}"  ng-show="item.activo==1" style="width:38px;" ng-click="$owner.activar(item.id)">
                                    <img src="{{ asset('/img/off.png') }}" ng-show="item.activo==0" style="width:40px;" ng-click="$owner.activar(item.id)">
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
                        <h4 class="modal-title">Registrar graduado</h4>
                    </div>
                    <div class="modal-body">
                        <form role="form" class="form-horizontal">
                            <div class="form-group">
                                <label class="col-lg-2" for="identificacion">Identificación</label>
                                <div class="col-lg-10">
                                    <input type="text" ng-model="usuario.identificacion" class="form-control" placeholder="Identificaión" ng-keypress="buscar($event)"/>
                                </div>
                            </div>
                            <div ng-if="usuario.edad != null && usuario.edad != 0">
                                <div class="form-group">
                                    <div class="col-lg-2"></div>
                                    <label class="col-lg-2" for="nombres">Nombres: </label>
                                    <div class="col-lg-8">
                                        <label> @{{ usuario.nombres }}</label>
                                    </div>                                    
                                </div>
                                
                                <div class="form-group">
                                    <div class="col-lg-2"></div>
                                    <label class="col-lg-2" for="apellidos">Apellidos: </label>
                                    <div class="col-lg-8">
                                        <label> @{{ usuario.apellidos }}</label>
                                    </div>
                                </div>
                                
                                <div class="form-group">
                                    <div class="col-lg-2"></div>
                                    <label class="col-lg-2" for="correo">Correo: </label>
                                    <div class="col-lg-8">
                                        <label> @{{ usuario.correo }}</label>
                                    </div>
                                </div>
                                
                                <div class="form-group">
                                    <div class="col-lg-2"></div>
                                    <label class="col-lg-2" for="correo">Edad: </label>
                                    <div class="col-lg-8">
                                        <label> @{{ usuario.edad }}</label>                                    
                                    </div>
                                </div>
                                <div class="form-group">
                                    <div class="col-lg-2"></div>
                                    <label class="col-lg-2" for="correo">Programa: </label>
                                    <div class="col-lg-8">
                                        <ul>
                                            <li ng-repeat="item in usuario.getestudiantes">
                                                @{{ item.getprograma.getprograma.nombre }}
                                            </li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                            <div ng-if="usuario.edad == 0 || mostrar" class="text-center">
                                <label>Usuario no encontrado</label>
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