@extends('master.master')

@section('title', 'Responder evaluación')

@section('contenido')

<div class="row" ng-controller="EvalResponderCtrl" ng-init="id={{$evaluacion->id}}; idEvaluado={{$evaluacion->idEvaluado}}">
    <div class="col-md-1"></div>
    <div class="col-md-10">
        <fieldset ng-if="evaluacion.estado">
            <legend>@{{ evaluacion.nombre | uppercase }}</legend>
            <form role="form">
                <div class="col-md-12 separator">
                    <div class="col-md-12" ng-repeat="seccion in evaluacion.getsecciones" ng-if="seccion.estado">
                        <fieldset class="separator">
                            <legend>
                                <p>
                                    <b>
                                        <span class="glyphicon glyphicon-list-alt"></span> @{{ seccion.enunciado }}
                                    </b>
                                </p>
                            </legend>
                            <div class="col-md-12" ng-repeat="pregunta in seccion.getpreguntas" ng-if="pregunta.estado">
                                <fieldset>
                                    <legend>
                                        <p>
                                            <span class="glyphicon glyphicon-question-sign"></span> @{{ pregunta.enunciado }}
                                        </p>
                                    </legend>
                                    <div ng-if="pregunta.gettipo.nombre == 'Cuantitativa'">
                                        <input type="number" ng-model='pregunta.respuesta'  class="form-control"/>
                                    </div>
                                    <div ng-if="pregunta.gettipo.nombre == 'Cualitativa'">
                                        <ul style="list-style:none;">
                                            <li ng-repeat="respuesta in pregunta.getpivoterespuesta" ng-if="respuesta.estado">
                                                <input type="radio" name="respuesta@{{ pregunta.id }}" ng-model='pregunta.respuesta' value="@{{ respuesta.id }}" /> @{{ respuesta.getrespuesta.nombre }}
                                            </li>
                                        </ul>
                                    </div>
                                    <div ng-if="pregunta.gettipo.nombre == 'Respuesta libre'">
                                        <textarea ng-model="pregunta.respuesta" rows="5" class="form-control noresize" ng-blur="respuestaLibre(pregunta)"></textarea>
                                    </div>
                                    <div ng-if="pregunta.gettipo.nombre == 'Booleana'">
                                        <ul style="list-style:none;">
                                            <input type="radio" name="respuesta@{{ pregunta.id }}" ng-model="pregunta.respuesta" value="@{{true}}" /> Si
                                            <br>
                                            <input type="radio" name="respuesta@{{ pregunta.id }}" ng-model="pregunta.respuesta" value="@{{false}}" /> No
                                        </ul>
                                    </div>
                                    <div ng-if="pregunta.gettipo.nombre == 'Booleana justificada'">
                                        <ul style="list-style:none;">
                                            <input type="radio" name="respuesta@{{ pregunta.id }}" ng-model="pregunta.respuesta" value="true" /> Si
                                            <input type="radio" name="respuesta@{{ pregunta.id }}" ng-model="pregunta.respuesta" value="false" /> No
                                            <br>¿Por qué?
                                            <textarea ng-model="pregunta.justificacion" rows="5" class="form-control noresize" ></textarea>
                                        </ul>
                                    </div>
                                </fieldset>
                            </div>
                            
                            <div class="col-md-12" ng-repeat="hija in seccion.getsecciones" ng-if="hija.estado">
                                <fieldset>
                                    <legend>
                                        <p>
                                            <b>
                                                <span class="glyphicon glyphicon-list-alt"></span> @{{ hija.enunciado }}
                                            </b>
                                        </p>
                                    </legend>
                                    <div class="col-md-12" ng-repeat="pre in hija.getpreguntas" ng-if="pre.estado">
                                        <fieldset>
                                            <legend>
                                                <p>
                                                    <span class="glyphicon glyphicon-question-sign"></span> @{{ pre.enunciado }}
                                                </p>
                                            </legend>
                                            <div ng-if="pre.gettipo.nombre == 'Cuantitativa'">
                                                <input type="number" ng-model="pre.respuesta" class="form-control"/>    
                                            </div>
                                            <div ng-if="pre.gettipo.nombre == 'Cualitativa'">
                                                <ul style="list-style:none;">
                                                    <li ng-repeat="respuesta in pre.getpivoterespuesta" ng-if="respuesta.estado">
                                                        <input type="radio" name="respuesta@{{ pre.id }}" ng-model="pre.respuesta" value="@{{ respuesta.id }}" /> @{{ respuesta.getrespuesta.nombre }}
                                                    </li>
                                                </ul>
                                            </div>
                                            <div ng-if="pre.gettipo.nombre == 'Respuesta libre'">
                                                <textarea ng-model="pre.respuesta" rows="5" class="form-control noresize" ng-blur="respuestaLibre(pre)"></textarea>
                                            </div>
                                            <div ng-if="pre.gettipo.nombre == 'Booleana'">
                                                <ul style="list-style:none;">
                                                    <input type="radio" name="respuesta@{{ pre.id }}" ng-model="pre.respuesta" value="true" /> Si
                                                    <br>
                                                    <input type="radio" name="respuesta@{{ pre.id }}" ng-model="pre.respuesta" value="false" /> No
                                                </ul>
                                            </div>
                                            <div ng-if="pre.gettipo.nombre == 'Booleana justificada'">
                                                <ul style="list-style:none;">
                                                    <input type="radio" name="respuesta@{{ pre.id }}" ng-model="pre.respuesta" value="true" /> Si
                                                    <input type="radio" name="respuesta@{{ pre.id }}" ng-model="pre.respuesta" value="false" /> No
                                                    <br>¿Por qué?
                                                    <textarea ng-model="pre.justificacion" rows="5" class="form-control noresize" ></textarea>
                                                </ul>
                                            </div>
                                        </fieldset>
                                    </div>
                                </fieldset>
                            </div>
                        </fieldset>
                    
                    </div>
                    <button class="btn btn-success" ng-click="guardarEvaluacion()" style="margin-left:16px">
                        Guardar
                    </button>
                </div>
                
            </form>
        </fieldset>
            
    </div>
    <div class="col-md-1"></div>
</div>

@stop