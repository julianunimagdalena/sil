@extends('master.master')

@section('title', 'Horario')

@section('contenido')

<div class="row" ng-controller="EstHorarioCtrl">
    
    <div class="col-md-1"></div>
    <div class="col-md-10">
        <table  object-table
        	data = "conferencias"
        	display = "10"
        	headers = "Charla, lugar, Orador, Fecha, Horario,Asistencia, Acciones"
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
                    @{{ ::item.lugar }}
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
                <td>
                    @{{ item.getasistencias[0].asistio | asistencia }}
                </td>
                <td class="text-center">
                    <span class="glyphicon glyphicon-pencil blue" title="Evaluar conferencia" data-id="@{{ item.getasistencias[0].id }}"
                        data-toggle="modal" data-target="#calificar" ng-show="item.getasistencias[0].asistio"></span>
                </td>
            </tbody>
        </table>
    </div>
    <div class="col-md-1"></div>
    
    <!-- Modal -->
    <div id="calificar" class="modal fade" role="dialog">
        <div class="modal-dialog">
    
            <!-- Modal content-->
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title">Calificar conferencia</h4>
                </div>
                <div class="modal-body">
                    <form role="form">
                        <div class="form-group row">
                            <div class="col-md-2" style="padding-top: 7px;">
                                <b>
                                    Calificación
                                </b>
                            </div>
                            <div class="col-md-3">
                                <p class="clasificacion">
                                    <input id="radio1" type="radio" name="estrellas" class="rad-estrella" value="5" ng-click="cincoestrella()"><!--
                                    --><label class="lbl-estrella" for="radio1">★</label><!--
                                    --><input id="radio2" type="radio" name="estrellas" class="rad-estrella" value="4" ng-click="cuatroestrella()"><!--
                                    --><label class="lbl-estrella" for="radio2">★</label><!--
                                    --><input id="radio3" type="radio" name="estrellas" class="rad-estrella" value="3" ng-click="tresestrella()"><!--
                                    --><label class="lbl-estrella" for="radio3">★</label><!--
                                    --><input id="radio4" type="radio" name="estrellas" class="rad-estrella" value="2" ng-click="dosestrella()"><!--
                                    --><label class="lbl-estrella" for="radio4">★</label><!--
                                    --><input id="radio5" type="radio" name="estrellas" class="rad-estrella" value="1" ng-click="unaestrella()"><!--
                                    --><label class="lbl-estrella" for="radio5">★</label>
                                  </p>
                            </div>
                            <div class="col-md-7"></div>
                            <p class="help-block text-danger col-md-12">
                                @{{errores.valor[0]}}
                            </p>
                        </div>
                        <!--<div class="form-group row">-->
                        <!--    <div class="col-md-12">-->
                        <!--        <label for="ejemplo_archivo_1">Observaciones</label>-->
                        <!--        <textarea cols="30" rows="5" class="form-control noresize" ng-model="calificacion.observaciones"></textarea>-->
                        <!--        <p class="help-block text-danger">-->
                        <!--            @{{errores.observaciones[0]}}-->
                        <!--        </p>-->
                        <!--    </div>-->
                        <!--</div>-->
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-rojo" data-dismiss="modal">Cerrar</button>
                    <button type="submit" class="btn btn-success" ng-click="guardarCalificacion()">Guardar</button>
                </div>
            </div>
    
        </div>
    </div>
    
</div>

@stop