@extends('master.master')

@section('title', 'Crear evaluación')

@section('contenido')

<div class="row" ng-controller="AdminCrearEvaluacionCtrl" ng-init="id= {{ $id }}">
    <div class="col-md-1"></div>
    <div class="col-md-10">
        <fieldset>
            <legend>
                <strong>
                    Crear evaluación
                </strong>
            </legend>
            
            <form role="form">
                <div class="col-md-12 separator">
                    <div class="form-group">
                        <label class="col-lg-12 control-label">Nombre de la evaluación</label>
                        <div class="col-lg-12">
                            <input type="text" ng-model="evaluacion.nombre" class="form-control"  placeholder="Nombre de la evaluación"/>
                            <p class="help-block text-danger">
                                @{{ errorEval.nombre[0] }}
                            </p>
                        </div>
                            
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label class="col-lg-12 control-label">Tipo de usuario que realizara la evaluación</label>
                        <div class="col-lg-12">
                            <ui-select ng-model="evaluacion.getrolevaluador" >
                                <ui-select-match>
                                    <span ng-bind="$select.selected.nombre"></span>
                                </ui-select-match>
                                <ui-select-choices repeat="item in (datos.roles | filter: $select.search) track by item.id">
                                    <span ng-bind="item.nombre"></span>
                                </ui-select-choices>
                            </ui-select>
                            <p class="help-block text-danger">
                                @{{ errorEval['getrolevaluador.nombre'][0] }}
                            </p>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label class="col-lg-12 control-label">Tipo de usuario al que se evaluará</label>
                        <div class="col-lg-12">
                            <ui-select ng-model="evaluacion.getrolevaluado" >
                                <ui-select-match>
                                    <span ng-bind="$select.selected.nombre"></span>
                                </ui-select-match>
                                <ui-select-choices repeat="item in (datos.roles | filter: $select.search) track by item.id">
                                    <span ng-bind="item.nombre"></span>
                                </ui-select-choices>
                            </ui-select>
                            <p class="help-block text-danger">
                                @{{ errorEval['getrolevaluado.nombre'][0] }}
                            </p>
                        </div>
                    </div>
                </div>
                <div class="col-md-12 separator" ng-if="evaluacion.getrolevaluado.nombre == 'Estudiante' && evaluacion.getrolevaluador.nombre == 'Tutor' ">
                    <div class="form-group">
                        <label class="col-lg-12 control-label">Si el estudiante es de prácticas, escoja la modalidad</label>
                        <div class="col-lg-12">
                            <ui-select multiple ng-model="evaluacion.getmodalidades" sortable="true" close-on-select="false">
                                <ui-select-match placeholder="Seleccione las modalidades">@{{$item.nombre}}</ui-select-match>
                                <ui-select-choices repeat="modalidad in (datos.modalidades | filter: $select.search) track by modalidad.id">
                                    <small>
                                        @{{modalidad.nombre}}
                                    </small>
                                </ui-select-choices>
                            </ui-select>
                            <p class="help-block text-danger">
                                @{{ errorEval['getmodalidades.nombre'][0] }}
                            </p>
                        </div>
                    </div>
                </div>
                <div class="col-md-12 separator">
                    <div class="form-group">
                        <label class="col-lg-12 control-label">Descripción de la evaluación</label>
                        <div class="col-lg-12">
                            <textarea ng-model="evaluacion.descripcion" rows="5" class="form-control noresize" placeholder="Descripción de la evaluación"></textarea>
                        </div>
                    </div>
                </div>
                <div class="col-md-12 separator">
                    <div class="col-md-12">
                        <button class="btn btn-success" ng-click="guardarEvaluacion()"
                            data-toggle="modal" data-target="#cargando" data-backdrop="static" data-keyboard="false">
                            Guardar
                        </button>    
                    </div>
                </div>
                <div class="col-md-12 separator">
                    <div class="col-md-12" ng-repeat="seccion in evaluacion.getsecciones">
                        <fieldset>
                            <legend>
                                <div class="izq">
                                    <p>
                                        <b>
                                            <span class="glyphicon glyphicon-list-alt"></span> @{{ seccion.enunciado }}
                                        </b>
                                    </p>
                                </div>
                                <div class="der text-right">
                                    <span class="glyphicon glyphicon-pencil blue pointer " data-toggle="modal" data-target="#newSeccion" data-id="@{{seccion.id}}" title="Editar sección"></span>
                                    <span class="glyphicon glyphicon-remove blue pointer" ng-click="eliminarSeccion(seccion.id)" ng-if="seccion.estado"
                                        data-toggle="modal" data-target="#cargando" data-backdrop="static" data-keyboard="false" title="Desactivar">
                                    </span>
                                    <span class="glyphicon glyphicon-ok blue pointer" ng-click="eliminarSeccion(seccion.id)" ng-if="!seccion.estado"
                                        data-toggle="modal" data-target="#cargando" data-backdrop="static" data-keyboard="false" title="Activar">
                                    </span>
                                    
                                </div>
                            </legend>
                            <div class="col-md-12" ng-repeat="pregunta in seccion.getpreguntas">
                                <fieldset>
                                    <legend>
                                        <div class="izq">
                                            <p>
                                                <span class="glyphicon glyphicon-question-sign"></span> @{{ pregunta.enunciado }}
                                            </p>
                                        </div>
                                        <div class="der text-right">
                                            <span class="glyphicon glyphicon-pencil blue pointer" data-toggle="modal" data-target="#newPregunta" data-id="@{{pregunta.id}}" title="Editar pregunta"></span>
                                            <span class="glyphicon glyphicon-remove blue pointer" ng-click="eliminarPregunta(pregunta.id)" ng-if="pregunta.estado"
                                                data-toggle="modal" data-target="#cargando" data-backdrop="static" data-keyboard="false">
                                                
                                            </span>
                                            <span class="glyphicon glyphicon-ok blue pointer" ng-click="eliminarPregunta(pregunta.id)" ng-if="!pregunta.estado"
                                                data-toggle="modal" data-target="#cargando" data-backdrop="static" data-keyboard="false">
                                            </span>
                                        </div>
                                    </legend>
                                    <p>
                                        Tipo: @{{ pregunta.gettipo.nombre }}
                                    </p>
                                    <p ng-if="pregunta.gettipo.nombre == 'Cuantitativa'">
                                        Minimo: @{{ pregunta.minimo }}
                                        Maximo: @{{ pregunta.maximo }}
                                    </p>
                                    <div ng-if="pregunta.gettipo.nombre == 'Cualitativa'">
                                        <ul>
                                            <li ng-repeat="respuesta in pregunta.getpivoterespuesta" ng-if="respuesta.estado">
                                                @{{ respuesta.getrespuesta.nombre }}
                                            </li>
                                        </ul>
                                    </div>
                                </fieldset>
                            </div>
                            
                            <div class="col-md-12" ng-repeat="hija in seccion.getsecciones">
                                <fieldset>
                                    <legend>
                                        <p>
                                            <div class="izq">
                                                <p>
                                                    <b>
                                                        <span class="glyphicon glyphicon-list-alt"></span> @{{ hija.enunciado }}
                                                    </b>
                                                </p>
                                            </div>
                                            <div class="der text-right">
                                                <span class="glyphicon glyphicon-pencil blue pointer" data-toggle="modal" data-target="#newSeccion" data-id="@{{hija.id}}" title="Editar sección"></span>
                                                <span class="glyphicon glyphicon-remove blue pointer" ng-click="eliminarSeccion(hija.id)" ng-if="hija.estado"
                                                    data-toggle="modal" data-target="#cargando" data-backdrop="static" data-keyboard="false">
                                                    
                                                </span>
                                                <span class="glyphicon glyphicon-ok blue pointer" ng-click="eliminarSeccion(hija.id)" ng-if="!hija.estado"
                                                    data-toggle="modal" data-target="#cargando" data-backdrop="static" data-keyboard="false">
                                                    
                                                </span>
                                            </div>
                                        </p>
                                    </legend>
                                    <div class="col-md-12" ng-repeat="pre in hija.getpreguntas">
                                        <fieldset>
                                            <legend>
                                                <div class="izq">
                                                    <p>
                                                        <span class="glyphicon glyphicon-question-sign"></span> @{{ pre.enunciado }}
                                                    </p>
                                                </div>
                                                <div class="der text-right">
                                                    <span class="glyphicon glyphicon-pencil blue pointer" data-toggle="modal" data-target="#newPregunta" data-id="@{{pre.id}}" title="Editar pregunta"></span>
                                                    <span class="glyphicon glyphicon-remove blue pointer" ng-click="eliminarPregunta(pre.id)" ng-if="pre.estado"
                                                        data-toggle="modal" data-target="#cargando" data-backdrop="static" data-keyboard="false">
                                                        
                                                    </span>
                                                    <span class="glyphicon glyphicon-ok blue pointer" ng-click="eliminarPregunta(pre.id)" ng-if="!pre.estado"
                                                        data-toggle="modal" data-target="#cargando" data-backdrop="static" data-keyboard="false">
                                                    </span>
                                                </div>
                                            </legend>
                                            <p>
                                                Tipo: @{{ pre.gettipo.nombre }}
                                            </p>
                                            <p ng-if="pre.gettipo.nombre == 'Cuantitativa'">
                                                Minimo: @{{ pre.minimo }}
                                                Maximo: @{{ pre.maximo }}
                                            </p>
                                            <div ng-if="pre.gettipo.nombre == 'Cualitativa'">
                                                <ul>
                                                    <li ng-repeat="respuesta in pre.getpivoterespuesta" ng-if="pre.estado">
                                                        @{{ respuesta.getrespuesta.nombre }}
                                                    </li>
                                                </ul>
                                            </div>
                                        </fieldset>
                                    </div>
                                </fieldset>
                            </div>
                        </fieldset>
                    </div>
                </div>
            </form>
            
        </fieldset>
    </div>
    <div class="col-md-1"></div>
    
    <div class="evaluacion-botones" >
        <div class="new-seccion pointer separator" ng-if="evaluacion.id != null" data-toggle="modal" data-target="#newSeccion" title="Añadir sección">
            <center>
                <span class="glyphicon glyphicon-plus"></span>
            </center>
        </div>
        <div class="new-pregunta pointer separator" ng-if="evaluacion.getsecciones.length > 0" data-toggle="modal" data-target="#newPregunta" title="Añadir pregunta">
            <center>
                <i class="glyphicon glyphicon-plus"></i>
                <span>?</span>
            </center>
        </div>
        <a href="{{asset('/adminsil/evaluaciones')}}" >
            <div class="new-pregunta-volver" title="Volver">
                <center>
                    <span class="glyphicon glyphicon-arrow-left" style="color:white !important"></span>
                </center>
            </div>
        </a>
    </div>
    
    <!-- Modal -->
    <div id="newSeccion" class="modal fade" role="dialog">
        <div class="modal-dialog">
    
            <!-- Modal content-->
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title">Añadir sección</h4>
                </div>
                <div class="modal-body">
                    <form role="form" class="form-horizontal">
                        <div class="form-group" ng-if="evaluacion.getsecciones.length > 0">
                            <label class="col-lg-12" for="rol">Sección padre</label>
                            <div class="col-lg-12">
                                <ui-select ng-model="nuevaSeccion.getpadre">
                                    <ui-select-match>
                                        <p ng-bind="$select.selected.enunciado | cortarTexto:80"></p>
                                    </ui-select-match>
                                    <ui-select-choices repeat="item in (evaluacion.getsecciones | filter: $select.search) track by item.id">
                                        <span ng-bind="item.enunciado || 'Sin enunciado'"> </span>
                                    </ui-select-choices>
                                </ui-select>
                                <p class="help-block text-danger">
                                    @{{ erroresSeccion['seccionPadre.id'][0] }}
                                </p>
                            </div>
                        </div>
                        
                        <div class="form-group">
                            <label class="col-lg-12" for="rol">Enunciado</label>
                            <div class="col-lg-12">
                                <textarea ng-model="nuevaSeccion.enunciado" rows="3" class="form-control noresize" placeholder="Enunciado"></textarea>
                                <p class="help-block text-danger">
                                    @{{ erroresSeccion.enunciado[0] }}
                                </p>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-rojo" data-dismiss="modal">Cerrar</button>
                    <button type="submit" class="btn btn-success" ng-click="guardarSeccion()" 
                        data-toggle="modal" data-target="#cargando" data-backdrop="static" data-keyboard="false">
                        Guardar
                    </button>
                </div>
            </div>
    
        </div>
    </div>
    
    
    <!-- Modal -->
    <div id="newPregunta" class="modal fade" role="dialog">
        <div class="modal-dialog">
    
            <!-- Modal content-->
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title">Añadir pregunta</h4>
                </div>
                <div class="modal-body" ng-style="modalBody">
                    <form role="form" class="form-horizontal">
                        <div class="form-group">
                            <label class="col-lg-12" for="rol">¿En qué sección deseas agregar la pregunta?</label>
                            <div class="col-lg-12">
                                <ui-select ng-model="nuevaPregunta.getseccion">
                                    <ui-select-match>
                                        <p ng-bind="$select.selected.enunciado | cortarTexto:80" style="overflow:hidden;"></p>
                                    </ui-select-match>
                                    <ui-select-choices repeat="item in (secciones | filter: $select.search) track by item.id">
                                        <span ng-bind="item.enunciado || 'Sin enunciado'"> </span>
                                    </ui-select-choices>
                                </ui-select>
                                <p class="help-block text-danger">
                                    @{{ erroresPregunta['getseccion.id'][0] }}
                                </p>
                            </div>
                        </div>
                        
                        <div class="form-group">
                            <label class="col-lg-12" for="rol">Enunciado</label>
                            <div class="col-lg-12">
                                <textarea ng-model="nuevaPregunta.enunciado" rows="3" class="form-control noresize" placeholder="Enunciado"></textarea>
                                <p class="help-block text-danger">
                                    @{{ erroresPregunta.enunciado[0] }}
                                </p>
                            </div>
                        </div>
                        
                        <div class="form-group">
                            <label class="col-lg-12" for="rol">Tipo de pregunta</label>
                            <div class="col-lg-12">
                                <ui-select ng-model="nuevaPregunta.gettipo" ng-change="cambioTipoPregunta()">
                                    <ui-select-match>
                                        <span ng-bind="$select.selected.nombre"></span>
                                    </ui-select-match>
                                    <ui-select-choices repeat="item in (datos.tipoPreguntas | filter: $select.search) track by item.id">
                                        <span ng-bind="item.nombre"> </span>
                                    </ui-select-choices>
                                </ui-select>
                                <p class="help-block text-danger">
                                    @{{ erroresPregunta['gettipo.id'][0] }}
                                </p>
                            </div>
                        </div>
                        <div ng-if="nuevaPregunta.gettipo.nombre == 'Cuantitativa'" style="margin-left:-15px">
                            <div class="col-lg-6">
                                <div class="form-group">
                                    <label class="col-lg-12" >Valor mínimo</label>
                                    <div class="col-lg-12">
                                        <input type="text" class="form-control" ng-model="nuevaPregunta.minimo">
                                        <p class="help-block text-danger">
                                            @{{ erroresPregunta.minimo[0] }}
                                        </p>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="form-group">
                                    <label class="col-lg-12">Valor máximo</label>
                                    <div class="col-lg-12">
                                        <input type="text" class="form-control" ng-model="nuevaPregunta.maximo">
                                        <p class="help-block text-danger">
                                            @{{ erroresPregunta.maximo[0] }}
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <div ng-if="nuevaPregunta.gettipo.nombre == 'Cualitativa'">
                            <div class="form-group">
                                <label class="col-lg-12">Agregue sus opciones de respuesta</label>
                                <div class="col-md-12">
                                    <ui-select multiple ng-model="nuevaPregunta.respuestas" sortable="true" close-on-select="false">
                                        <ui-select-match placeholder="Seleccione sus opciones de respuesta">@{{$item.nombre}}</ui-select-match>
                                        <ui-select-choices repeat="opcion in (datos.posiblesRespuestas | filter: $select.search) track by opcion.id">
                                            <small>
                                                @{{opcion.nombre}}
                                            </small>
                                        </ui-select-choices>
                                    </ui-select>
                                    <br>
                                    <p class="help-block text-danger">
                                        @{{ erroresPregunta.getposiblesrespuestas[0] }}
                                    </p>
                                </div>
                                <div class="col-md-10">
                                    <input type="text" ng-model="opcionRespuesta.nombre" class="form-control" placeholder="Si desea registrar una nueva opcion escribala aquí" ng-keypress="enterPress($event)">
                                </div>
                                <div class="col-md-2" style="font-size:2em; text-align:center; color:#337ab7;">
                                    <span class="glyphicon glyphicon-plus-sign pointer" title="Añadir" ng-click="agregarRespuesta()"></span>
                                </div>
                            </div>
                        </div>
                            
                        
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-rojo" data-dismiss="modal">Cerrar</button>
                    <button type="submit" class="btn btn-success" ng-click="guardarPregunta()" 
                        data-toggle="modal" data-target="#cargando" data-backdrop="static" data-keyboard="false">
                        Guardar
                    </button>
                </div>
            </div>
    
        </div>
    </div>
    
</div>

@stop
