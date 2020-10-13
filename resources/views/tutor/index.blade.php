@extends('master.master')

@section('title', 'Practicantes')

@section('contenido')

<div class="row" ng-controller="TutorIndexCtrl">
    
    <div class="col-md-1"></div>
    <div class="col-md-10">
        @if(Session('error') != null)
        <div class="alert alert-danger alert-dismissable">
            <button type="button" class="close" data-dismiss="alert">&times;</button>
            {{ Session('error') }}
        </div>
        @endif
        
        <table  object-table
        	data = "practicantes"
        	display = "10"
        	headers = "Código, Nombres, Apellidos,Programa, Correo,Celular, Acciones"
        	fields = "identificacion,nombre,correo,rol,dependencia,sede"
        	sorting = "compound"
        	editable = "false"
        	resize="false"
        	drag-columns="false">
            <tbody>
                <td>
                    @{{ ::item.getpractica.getestudiante.codigo }}
                </td>
                <td>
                    @{{ ::item.getpractica.getestudiante.getpersona.nombres | uppercase}}
                </td>
                <td>
                    @{{ ::item.getpractica.getestudiante.getpersona.apellidos | uppercase}}
                </td>
                <td>
                    @{{ ::item.getpractica.getestudiante.getprograma.nombre | uppercase}}
                </td>
                <td>
                    @{{ ::item.getpractica.getestudiante.getpersona.correo | uppercase}}
                </td>
                <td>
                    @{{ ::item.getpractica.getestudiante.getpersona.celular }}
                </td>
                <!--<td>-->
                <!--    @{{ ::item.sede | uppercase }}-->
                <!--</td>-->
                <td class="text-center">
                    <img data-id="@{{ ::item.id }}" src="/img/visita.png" style="max-width:20px;" title="Registrar visita" data-toggle="modal" data-target="#registrarVisita" />
                    <span class="fa fa-binoculars blue" data-id="@{{ ::item.id }}" aria-hidden="true" ng-show="item.getpractica.getvisitas.length > 0" title="Ver visitas" data-toggle="modal" data-target="#verVisitas"></span>
                    <span data-id="@{{ ::item.id }}" data-toggle="modal" data-target="#detallesPracticante" class="glyphicon glyphicon-search pointer blue" title="Detalles">
                    </span>
                    <a href="/evaluacion/evaluarestudiantebytutor/@{{ ::item.id }}">
                        <span class="fa fa-pencil-square-o blue" title="Evaluar practicante" ng-show="item.mostrar"></span>
                    </a>
                    <span class="fa fa-thumbs-up blue" aria-hidden="true" data-toggle="modal" data-target="#proyectoImpacto" 
                        ng-show="item.getpractica.proyecto_impacto == null || !item.getpractica.proyecto_impacto" 
                        title="Clasificar práctica como proyecto de impacto" data-id="@{{item.getpractica.id}}">
                    </span>
                </td>
            </tbody>
        </table>
        
        <!-- Modal -->
        <div id="detallesPracticante" class="modal fade" role="dialog">
            <div class="modal-dialog">
        
                <!-- Modal content-->
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                        <h4 class="modal-title">Detalles del practicante</h4>
                    </div>
                    <div class="modal-body">
                        <form role="form">
                            <div class="form-group row">
                                <label class="col-md-3">Nombre</label>
                                <div class="col-md-9">
                                    <p>
                                        @{{ practicante.getpractica.getestudiante.getpersona.nombres }}
                                        @{{ practicante.getpractica.getestudiante.getpersona.apellidos }}
                                    </p>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-md-3">Identificación</label>
                                <div class="col-md-9">
                                    <p>
                                        @{{ practicante.getpractica.getestudiante.getpersona.identificacion }}
                                    </p>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-md-3">Correo</label>
                                <div class="col-md-9">
                                    <p>
                                        @{{ practicante.getpractica.getestudiante.getpersona.correo }}
                                    </p>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-md-3">Celular</label>
                                <div class="col-md-9">
                                    <p>
                                        @{{ practicante.getpractica.getestudiante.getpersona.celular }}
                                    </p>
                                </div>
                            </div>
                            <div ng-repeat="postulado in practicante.getpractica.getestudiante.getpostulaciones">
                                <div class="form-group row" ng-if="postulado.getestadoempresa.nombre=='Seleccionado' && postulado.getestadoestudiante.nombre=='Aceptó'">
                                    <label class="col-md-3">Empresa</label>
                                    <div class="col-md-9">
                                        <p>
                                           @{{ postulado.getoferta.getsede.getempresa.nombre }}
                                        </p>
                                    </div>
                                </div>
                                <div class="form-group row" ng-if="postulado.getestadoempresa.nombre=='Seleccionado' && postulado.getestadoestudiante.nombre=='Aceptó'">
                                    <label class="col-md-3">Dirección</label>
                                    <div class="col-md-9">
                                        <p>
                                           @{{ postulado.getoferta.getsede.direccion }}
                                        </p>
                                    </div>
                                </div>
                                <div class="form-group row" ng-if="postulado.getestadoempresa.nombre=='Seleccionado' && postulado.getestadoestudiante.nombre=='Aceptó'">
                                    <label class="col-md-3">Jefe inmediato</label>
                                    <div class="col-md-9">
                                        <p>
                                           @{{ postulado.getoferta.getjefe.getuser.nombres }}
                                           @{{ postulado.getoferta.getjefe.getuser.apellidos }}
                                        </p>
                                    </div>
                                </div>
                                <div class="form-group row" ng-if="postulado.getestadoempresa.nombre=='Seleccionado' && postulado.getestadoestudiante.nombre=='Aceptó'">
                                    <label class="col-md-3">Correo jefe inmediato</label>
                                    <div class="col-md-9">
                                        <p>
                                           @{{ postulado.getoferta.getjefe.getuser.correo }}
                                        </p>
                                    </div>
                                </div>
                                <div class="form-group row" ng-if="postulado.getestadoempresa.nombre=='Seleccionado' && postulado.getestadoestudiante.nombre=='Aceptó'">
                                    <label class="col-md-3">Teléfono jefe inmediato</label>
                                    <div class="col-md-9">
                                        <p>
                                           @{{ postulado.getoferta.getjefe.getuser.celular }}
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-rojo" data-dismiss="modal">Cerrar</button>
                    </div>
                </div>
        
            </div>
        </div>
        
        
    </div>
    <div class="col-md-1"></div>
    
    <!-- Modal -->
    <div id="registrarVisita" class="modal fade" role="dialog">
        <div class="modal-dialog">
    
            <!-- Modal content-->
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title">Registrar visita o contacto</h4>
                </div>
                <div class="modal-body">
                    <form role="form">
                        <div class="form-group row">
                            <label class="col-md-12">Fecha</label>
                            <div class="col-md-12">
                                <p class="input-group" ng-init="opened = false">
                                    <input type="text" class="form-control" uib-datepicker-popup="dd/MM/yyyy" ng-model="visita.fecha" is-open="opened" ng-click="opened=!opened"/>
                                    <span class="input-group-btn">
                                        <button type="button" class="btn btn-default" ng-click="opened = !opened"><i class="glyphicon glyphicon-calendar"></i></button>
                                    </span>
                                </p>
                                <!--<input type="date" ng-model="visita.fecha" class="form-control"/>-->
                                <p class="help-block text-danger">
                                    @{{ errores.fecha[0] }}
                                </p>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-md-12">Hora</label>
                            <div class="col-md-12">
                                <!--<center>-->
                                    <div uib-timepicker ng-model="visita.hora" show-meridian="ismeridian"></div>
                                <!--</center>-->
                                <!--<input type="time" ng-model="visita.hora" class="form-control"/>-->
                                <p class="help-block text-danger">
                                    @{{ errores.hora[0] }}
                                </p>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-md-12">Tema tratado</label>
                            <div class="col-md-12">
                                <textarea ng-model="visita.tema" rows="5" class="form-control noresize" placeholder="Tema tratado en la visita"></textarea>
                                <p class="help-block text-danger">
                                    @{{ errores.tema[0] }}
                                </p>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-rojo" data-dismiss="modal">Cerrar</button>
                    <button type="button" class="btn btn-success" ng-click="guardarVisita()">Guardar</button>
                </div>
            </div>
    
        </div>
    </div>
    
    <!-- Modal -->
    <div id="verVisitas" class="modal fade" role="dialog">
        <div class="modal-dialog" style="width:1000px">
    
            <!-- Modal content-->
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title">Visitas o contactos registrados</h4>
                </div>
                <div class="modal-body">
                    <table  object-table
                    	data = "visitas"
                    	display = "10"
                    	headers = "Fecha registro,Fecha, Hora, Tema,Confirmación estudiante, Confirmación jefe inmediato"
                    	fields = "fecha_registro,fecha,hora,tema,firma_estudiante,firma_jefe"
                    	sorting = "compound"
                    	editable = "false"
                    	resize="false"
                    	drag-columns="false">
                        <tbody>
                            <td>
                                @{{ ::item.fecha_registro | date:'medium' }}
                            </td>
                            <td>
                                @{{ ::item.fecha | date:'dd/MM/yyyy'}}
                            </td>
                            <td>
                                @{{ ::item.hora }}
                            </td>
                            <td>
                                @{{ ::item.tema}}
                            </td>
                            <td>
                                @{{ ::item.firma_estudiante | estadoVisita }}
                            </td>
                            <td>
                                @{{ ::item.firma_jefe | estadoVisita }}
                            </td>
                        </tbody>
                    </table>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-rojo" data-dismiss="modal">Cerrar</button>
                </div>
            </div>
    
        </div>
    </div>
    
    <!-- Modal -->
    <div id="proyectoImpacto" class="modal fade" role="dialog">
        <div class="modal-dialog">
    
            <!-- Modal content-->
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title">Clasificar como proyecto de impacto</h4>
                </div>
                <div class="modal-body">
                    <form role="form" ng-submit="proyectoImpacto()" >
                        <div class="form-group">
                            <label>Nombre del proyecto</label>
                            <input type="text" class="form-control" ng-model="proyecto.nombre" placeholder="Nombre del proyecto">
                            <p class="help-block text-danger">
                                @{{errores.nombre[0]}}
                            </p>
                        </div>
                        <button type="submit" ng-show="false">
                            
                        </button>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-rojo" data-dismiss="modal">Cerrar</button>
                    <button type="button" class="btn btn-success" ng-click="proyectoImpacto()">Guardar</button>
                </div>
            </div>
    
        </div>
    </div>
    
</div>

@stop