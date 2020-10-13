@extends('master.master')

@section('title', 'Pre-prácticas')

@section('contenido')

<div class="row" ng-controller="CdnCharlasCtrl">
    
    <div class="col-md-1"></div>
    <div class="col-md-10">
        <filedset>
            <legend>Charlas de pre-prácticas</legend>
            <div class="row">
                <div class="col-md-2">
                    Periodo 
                    <br>
                    <ui-select ng-model="filtro.periodo" ng-change="filtrarPeriodo()">
                        <ui-select-match>
                            <span ng-bind="$select.selected.nombre"></span>
                        </ui-select-match>
                        <ui-select-choices repeat="item in (periodos | filter: $select.search) track by item.id">
                            <span ng-bind="item.nombre"></span>
                        </ui-select-choices>
                    </ui-select>
                    
                    
                </div>
                <div class="col-md-10">
                    
                </div>
            </div>
            <br>
            
            <button class="btn btn-primary" data-toggle="modal" data-target="#crearCharla">
                Nueva charla
            </button>    
            
            <button class="btn btn-primary" data-toggle="modal" data-target="#asistencia">
                Agregar asistencia
            </button>
            
            
            <table  object-table
            	data = "charlas"
            	display = "10"
            	headers = "Charla, Periodo, Orador, Fecha, Horario, Acciones"
            	fields = "identificacion,nombre,correo,rol,dependencia,sede"
            	sorting = "compound"
            	editable = "false"
            	resize="false"
            	drag-columns="false">
                <tbody>
                    <td>
                        @{{ ::item.getconferencia.nombre }}
                    </td>
                    <td>
                        @{{ ::item.getperiodo.nombre }}
                    </td>
                    <td>
                        @{{ ::item.getorador.nombres }}
                        @{{ ::item.getorador.apellidos }}
                    </td>
                    <td>
                        @{{ ::item.fecha | date:'dd/MM/yyyy'}}
                    </td>
                    <td>
                        @{{ ::item.horaInicial }}
                        -
                        @{{ ::item.horaFinal }}
                    </td>
                    <td class="text-center">
                        <span class="glyphicon glyphicon-calendar blue" title="Agregar horario" data-toggle="modal" data-target="#addHorario"
                            data-id="@{{item.getconferencia.id}}" data-editar="false">
                        </span>
                        <span class="glyphicon glyphicon-pencil blue" title="Editar horario" data-toggle="modal" data-target="#addHorario"
                            data-id="@{{item.id}}" data-editar="true"></span>
                        <span class="glyphicon glyphicon-th-list blue" title="Generar lista" data-toggle="modal" data-target="#lista"
                            data-id="@{{item.id}}" ></span>
                        <span class="fa fa-list blue" aria-hidden="true" title="Ver asistencia" ng-click="$owner.verAsistencia(item.id)"
                            ng-show="item.getasistencias.length > 0"></span>
                        
                        <span class="glyphicon glyphicon-star blue" title="Ver Calificación" data-toggle="modal" data-target="#mdlCalificacion"
                            data-id="@{{item.id}}" ng-show="item.valoracion != null"></span>
                    </td>
                </tbody>
            </table>
            
                
        </filedset>
            
    </div>
    <div class="col-md-1"></div>
    <!-- Modal -->
    <div id="crearCharla" class="modal fade" role="dialog">
        <div class="modal-dialog">
        
            <!-- Modal content-->
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title">Crear charla</h4>
                </div>
                <div class="modal-body">
                    <form role="form">
                        <filedset class="row">
                            <legend>
                                <b >
                                    Conferencia
                                </b>
                            </legend>
                            <div class="form-group">
                                <div class="col-md-12">
                                    <label>Nombre</label>
                                </div>
                                <div class="col-md-12">
                                    <input type="tenxt" class="form-control" placeholder="Nombre de la charla" ng-model="charla.nombre">
                                </div>
                                <p class="help-block text-danger col-md-12">
                                    @{{ errores.nombre[0] }}
                                </p>
                            </div>
                        </filedset>
                        <fieldset class="row" style="margin-top:-22px;">
                            <div class="col-md-12">
                                <legend>
                                    <b>
                                        Conferencista
                                    </b>
                                </legend>
                                <div class="form-group">
                                    <label class="col-md-12">Identificación</label>
                                    <div class="col-md-11">
                                        <input type="text" class="form-control" ng-model="charla.getorador.identificacion" placeholder="Identificación">
                                    </div>
                                    <div class="col-md-1">
                                        <span class="fa fa-search fa-2x blue pointer" aria-hidden="true" ng-click="buscarPersona()">
                                        </span>
                                    </div>
                                    <p class="help-block text-danger col-md-12">
                                        @{{ errores['getorador.identificacion'][0] }}
                                    </p>
                                </div>
                                <div class="row">
                                    <div class="form-group col-md-6">
                                        <label class="col-md-12">Nombres</label>
                                        <div class="col-md-12">
                                            <input type="text" class="form-control" ng-model="charla.getorador.nombres" placeholder="Nombres">
                                        </div>
                                        <p class="help-block text-danger col-md-12">
                                            @{{ errores['getorador.nombres'][0] }}
                                        </p>
                                    </div>
                                    <div class="form-group col-md-6">
                                        <label class="col-md-12">Apellidos</label>
                                        <div class="col-md-12">
                                            <input type="text" class="form-control" ng-model="charla.getorador.apellidos" placeholder="Apellidos">
                                        </div>
                                        <p class="help-block text-danger col-md-12">
                                            @{{ errores['getorador.apellidos'][0] }}
                                        </p>
                                    </div>
                                </div>
                                
                                <div class="form-group" ng-if="mostrar">
                                    <label class="col-md-12">Correo</label>
                                    <div class="col-md-12">
                                        <input type="text" class="form-control" ng-model="charla.getorador.correo" placeholder="Correo">
                                    </div>
                                    <p class="help-block text-danger col-md-12">
                                        @{{ errores['getorador.correo'][0] }}
                                    </p>
                                </div>
                            </div>
                        </fieldset>
                        
                        <fieldset class="row">
                            <div class="col-md-12">
                                <legend>
                                    <b>
                                        Ubicación
                                    </b>
                                </legend>
                                <div class="form-group">
                                    <label class="col-md-12">Lugar</label>
                                    <div class="col-md-12">
                                        <input type="text" class="form-control" ng-model="charla.lugar" placeholder="Lugar">
                                    </div>
                                    <p class="help-block text-danger col-md-12">
                                        @{{ errores['lugar'][0] }}
                                    </p>
                                </div>
                                <div class="form-group">
                                    <label class="col-md-12">Fecha</label>
                                    <div class="input-group col-md-12" ng-init="opened = false">
                                        <input type="text" class="form-control" uib-datepicker-popup="dd/MM/yyyy" ng-model="charla.fecha" is-open="opened" ng-click="opened=!opened"
                                            style="margin-left: 14px"/>
                                        <span class="input-group-btn">
                                            <button type="button" class="btn btn-default" ng-click="opened = !opened"><i class="glyphicon glyphicon-calendar"></i></button>
                                        </span>
                                    </div>
                                    <p class="help-block text-danger col-md-12">
                                        @{{ errores['fecha'][0] }}
                                    </p>
                                </div>
                                <div class="row">
                                    <div class="form-group col-md-4">
                                        <label class="col-md-12">Hora inicial</label>
                                        <div class="col-md-12">
                                            <div uib-timepicker ng-model="charla.hora_inicial" show-meridian="ismeridian"></div>
                                        </div>
                                        <p class="help-block text-danger col-md-12">
                                            @{{ errores['str_hora_inicial'][0] }}
                                        </p>
                                    </div>
                                    <div class="form-group col-md-4">
                                        <label class="col-md-12">Hora final</label>
                                        <div class="col-md-12">
                                            <div uib-timepicker ng-model="charla.hora_final" show-meridian="ismeridian"></div>
                                        </div>
                                        <p class="help-block text-danger col-md-12">
                                            @{{ errores['str_hora_final'][0] }}
                                        </p>
                                    </div>
                                    <div class="form-group col-md-4" style="margin-top: 35px;">
                                        <label class="col-md-12">Cupo</label>
                                        <div class="col-md-12">
                                            <input type="text" class="form-control" ng-model="charla.cupo" placeholder="Cupo">
                                        </div>
                                        <p class="help-block text-danger col-md-12">
                                            @{{ errores['cupo'][0] }}
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </fieldset>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-rojo" data-dismiss="modal">Close</button>
                    <button class="btn btn-success" ng-click="guardarCharla()">
                        Guardar
                    </button>
                </div>
            </div>
        
        </div>
    </div>
    
    <div id="addHorario" class="modal fade" role="dialog" style="overflow-y: scroll;">
        <div class="modal-dialog">
        
            <!-- Modal content-->
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title">Horario</h4>
                </div>
                <div class="modal-body">
                    <form role="form">
                        <fieldset>
                            <lengend>
                                <b class="medium-text">
                                    Conferencista
                                </b>
                            </lengend>
                            <div class="form-group">
                                <label class="col-md-12">Identificación</label>
                                <div class="col-md-11">
                                    <input type="text" class="form-control" ng-model="charla.getorador.identificacion" placeholder="Identificación">
                                </div>
                                <div class="col-md-1">
                                    <span class="fa fa-search fa-2x blue pointer" aria-hidden="true" ng-click="buscarPersona()">
                                    </span>
                                </div>
                                <p class="help-block text-danger col-md-12 col-md-12">
                                    @{{ errores['getorador.identificacion'][0] }}
                                </p>
                            </div>
                            <div class="row">
                                <div class="form-group col-md-6">
                                    <label class="col-md-12">Nombres</label>
                                    <div class="col-md-12">
                                        <input type="text" class="form-control" ng-model="charla.getorador.nombres" placeholder="Nombres">
                                    </div>
                                    <p class="help-block text-danger col-md-12">
                                        @{{ errores['getorador.nombres'][0] }}
                                    </p>
                                </div>
                                <div class="form-group col-md-6">
                                    <label class="col-md-12">Apellidos</label>
                                    <div class="col-md-12">
                                        <input type="text" class="form-control" ng-model="charla.getorador.apellidos" placeholder="Apellidos">
                                    </div>
                                    <p class="help-block text-danger col-md-12">
                                        @{{ errores['getorador.apellidos'][0] }}
                                    </p>
                                </div>
                            </div>
                            
                            <div class="form-group" ng-if="mostrar">
                                <label class="col-md-12">Correo</label>
                                <div class="col-md-12">
                                    <input type="text" class="form-control" ng-model="charla.getorador.correo" placeholder="Correo">
                                </div>
                                <p class="help-block text-danger col-md-12">
                                    @{{ errores['getorador.correo'][0] }}
                                </p>
                            </div>
                        </fieldset>
                        
                        <fieldset>
                            <lengend>
                                <b class="medium-text">
                                    Ubicación
                                </b>
                            </lengend>
                            <div class="form-group">
                                <label class="col-md-12">Lugar</label>
                                <div class="col-md-12">
                                    <input type="text" class="form-control" ng-model="charla.lugar" placeholder="Lugar">
                                </div>
                                <p class="help-block text-danger col-md-12">
                                    @{{ errores['lugar'][0] }}
                                </p>
                            </div>
                            <div class="form-group">
                                <label class="col-md-12">Fecha</label>
                                <div class="input-group col-md-12" ng-init="opened = false">
                                    <input type="text" class="form-control" uib-datepicker-popup="dd/MM/yyyy" ng-model="charla.fecha" is-open="opened" ng-click="opened=!opened"
                                        style="margin-left: 14px"/>
                                    <span class="input-group-btn">
                                        <button type="button" class="btn btn-default" ng-click="opened = !opened"><i class="glyphicon glyphicon-calendar"></i></button>
                                    </span>
                                </div>
                                <p class="help-block text-danger col-md-12">
                                    @{{ errores['fecha'][0] }}
                                </p>
                            </div>
                            <div class="row">
                                <div class="form-group col-md-4">
                                    <label class="col-md-12">Hora inicial</label>
                                    <div class="col-md-12">
                                        <div uib-timepicker ng-model="charla.hora_inicial" show-meridian="ismeridian"></div>
                                    </div>
                                    <p class="help-block text-danger col-md-12">
                                        @{{ errores['str_hora_inicial'][0] }}
                                    </p>
                                </div>
                                <div class="form-group col-md-4">
                                    <label class="col-md-12">Hora final</label>
                                    <div class="col-md-12">
                                        <div uib-timepicker ng-model="charla.hora_final" show-meridian="ismeridian"></div>
                                    </div>
                                    <p class="help-block text-danger col-md-12">
                                        @{{ errores['str_hora_final'][0] }}
                                    </p>
                                </div>
                                <div class="form-group col-md-4" style="margin-top: 35px;">
                                    <label class="col-md-12">Cupo</label>
                                    <div class="col-md-12">
                                        <input type="text" class="form-control" ng-model="charla.cupo" placeholder="Cupo">
                                    </div>
                                    <p class="help-block text-danger col-md-12">
                                        @{{ errores['cupo'][0] }}
                                    </p>
                                </div>
                            </div>
                        </fieldset>
                        
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-rojo" data-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-success" ng-click="guardarHorario()">Guardar</button>
                </div>
            </div>
        
        </div>
    </div>
    
    <!-- Modal -->
    <div id="lista" class="modal fade" role="dialog">
        <div class="modal-dialog">
        
            <!-- Modal content-->
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title">Generar lista</h4>
                </div>
                <div class="modal-body">
                    <form role="form">
                        <div class="form-group">
                            <label>Programa</label>
                            <ui-select ng-model="lista.programa">
                                <ui-select-match>
                                    <span ng-bind="$select.selected.nombre"></span>
                                </ui-select-match>
                                <ui-select-choices repeat="item in (programas | filter: $select.search) track by item.id">
                                    <span ng-bind="item.nombre"></span>
                                </ui-select-choices>
                            </ui-select>
                            <p class="help-block text-danger">
                                @{{ errores.programa[0] }}
                            </p>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-rojo" data-dismiss="modal">Close</button>
                    <button class="btn btn-success" ng-click="generarLista()">
                        Generar
                    </button>
                </div>
            </div>
        
        </div>
    </div>
    
    
    <!-- Modal -->
    <div id="asistencia" class="modal fade" role="dialog">
        <div class="modal-dialog">
        
            <!-- Modal content-->
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title">Agregar asistencia</h4>
                </div>
                <div class="modal-body">
                    <form role="form">
                        <div class="form-group">
                            <label>Conferencia</label>
                            <ui-select ng-model="asistencia.conferencia">
                                <ui-select-match>
                                    <span ng-bind="$select.selected.nombre"></span>
                                </ui-select-match>
                                <ui-select-choices repeat="item in (conferencias | filter: $select.search) track by item.id">
                                    <span ng-bind="item.nombre"></span>
                                </ui-select-choices>
                            </ui-select>
                            <p class="help-block text-danger">
                                @{{ errores['conferencia.id'][0] }}
                            </p>
                        </div>
                        
                        <div class="form-group">
                            <label >Estudiantes</label>
                            <!--<div class="col-lg-12"> class="col-lg-3"-->
                                <ui-select multiple ng-model="asistencia.estudiantes" sortable="true" close-on-select="false">
                                    <ui-select-match placeholder="Seleccione los estudiantes">@{{$item.codigo}}</ui-select-match>
                                    <ui-select-choices repeat="item in (estudiantes | filter: $select.search) track by item.id">
                                        <small>
                                            @{{item.codigo+" - "+item.getpersona.nombres+" "+item.getpersona.apellidos}}
                                        </small>
                                    </ui-select-choices>
                                </ui-select>
                                <p class="help-block text-danger">
                                    @{{ errores.estudiantes[0] }}
                                </p>
                            <!--</div>-->
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-rojo" data-dismiss="modal">Close</button>
                    <button class="btn btn-success" ng-click="guardarAsistencia()">
                        Guardar
                    </button>
                </div>
            </div>
        
        </div>
    </div>
    
    <!-- Modal -->
    <div id="mdlAsistentes" class="modal fade" role="dialog">
        <div class="modal-dialog">
        
            <!-- Modal content-->
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title">Asistencia</h4>
                </div>
                <div class="modal-body">
                    <table  object-table
                    	data = "asistentes"
                    	display = "4"
                    	headers = "Código, Nombre, Programa, Asistencia"
                    	fields = "nombre,correo,rol,dependencia,sede"
                    	sorting = "compound"
                    	editable = "false"
                    	resize="false"
                    	drag-columns="false">
                        <tbody>
                            <td>
                                @{{ ::item.getestudiante.codigo }}
                            </td>
                            <td>
                                @{{ ::item.getestudiante.getpersona.nombres }}
                                @{{ ::item.getestudiante.getpersona.apellidos }}
                            </td>
                            <td>
                                @{{ ::item.getestudiante.getprograma.nombre }}
                            </td>
                            <td>
                                @{{ ::item.asistio | asistencia}}
                            </td>
                        </tbody>
                    </table>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-rojo" data-dismiss="modal">Close</button>
                </div>
            </div>
        
        </div>
    </div>
    
    <!-- Modal -->
    <div id="mdlCalificacion" class="modal fade" role="dialog">
        <div class="modal-dialog">
        
            <!-- Modal content-->
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title">Calificación</h4>
                </div>
                <div class="modal-body">
                    
                        <div style="position:relative; width:276px; margin:0 auto;">
                            <img src="/img/fondo-rojo.png" style="width:@{{(charla.valoracion/5)*276}}px; height:65px;"></img>
                            <div style="position:absolute; top:0; left:0;">
                                <img src="/img/Estrellas.png"></img>    
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12 text-center">
                                <span class="fa-4x">
                                    <!--<i class="fa fa-smile-o fa-2x blue" aria-hidden="true" ng-if="charla.valoracion != null && charla.valoracion >= 3"></i>-->
                                    <!--<i class="fa fa-frown-o fa-2x blue" aria-hidden="true" ng-if="charla.valoracion != null && charla.valoracion < 3"></i>-->
                                    <span class="fa-2x">
                                        @{{charla.valoracion | number:1}}    
                                    </span>
                                    <!--<i class="fa fa-frown-o fa-2x blue" aria-hidden="true" ng-if="charla.valoracion != null && charla.valoracion < 3"></i>-->
                                    <!--<i class="fa fa-smile-o fa-2x blue" aria-hidden="true" ng-if="charla.valoracion != null && charla.valoracion >= 3"></i>-->
                                </span>
                            </div>
                        </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-rojo" data-dismiss="modal">Close</button>
                </div>
            </div>
        
        </div>
    </div>
    
</div>

@stop