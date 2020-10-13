@extends('master._adminLayoutNew')
@section('titulo', 'Tablero de administrador')
@section('controller','ng-controller ="SilUsuariosCtrl"')
@section('title')
    <h2>Tablero de administrador</h2>
@endsection

@section('estilos')
    
    <style>
        #link-usuarios * {
            color: white;
        }
    </style>

@endsection

@section('content')

    {{-- <h1 style="text-transform: uppercase; font-weight: 600">Datos de usuarios</h1> --}}
    <h3 style="color: #004A87; font-weight: 700">DATOS DE USUARIOS</h3>
    @section('tituloVista', 'DATOS DE USUARIOS')
    {{-- <hr> --}}
    <div class="row">

        <div class="col-md-12 table-responsive" style="overflow-x: auto">
        	<table object-table
                	data = "usuarios"
                	display = "10"
                	headers = "Identificación, Nombre, Correo,Rol, Activo"
                	fields = "identificacion,nombre,correo,rol,dependencia,sede"
                	sorting = "compound"
                	editable = "false"
                	resize= "false"
                	drag-columns="false">
                <tbody>
                    <td>
                        @{{ ::item.identificacion }}
                    </td>
                    <td>
                        @{{ ::item.nombre | uppercase }}
                    </td>
                    <td>
                        @{{ ::item.correo | uppercase }}
                    </td>
                    <td>
                        @{{ ::item.rol.nombre | uppercase }}
                    </td>                                
                    <!--<td>-->
                    <!--    @{{ ::item.sede | uppercase }}-->
                    <!--</td>-->
                    <td class="text-center">
                        <img src="{{ asset('/img/on.png') }}"  ng-show="item.activo==1" style="width:38px;" ng-click="$owner.activar(item.id, item.rol.id)">
                        <img src="{{ asset('/img/off.png') }}" ng-show="item.activo==0" style="width:40px;" ng-click="$owner.activar(item.id, item.rol.id)">
                    </td>
                </tbody>
            </table>
        </div>
        <div class="alert alert-warning" role="alert" ng-if="usuarios.length == 0">
            No hay usuarios almacenados en el sistema
        </div>
        <div class="alert alert-warning" role="alert" ng-if="usuarios.length > 0 && ( usuarios | filter: search).length == 0">
            No hay resultados para la búsqueda ingresada
        </div>
    </div>
    <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#crearUsuario" ng-click="newUser()">
        Nuevo graduado
    </button>
{{-- <div class="row"> --}}

{{-- </div> --}}

       {{-- </div> --}}
    <!-- Modal -->
    <div id="crearUsuario" class="modal fade" role="dialog">
        <div class="modal-dialog">
    
            <!-- Modal content-->
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Registrar graduado</h5>
                    <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
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
    {{-- </div> --}}


@endsection