@extends('master.master')

@section('title', 'Practicantes')

@section('contenido')

<div class="row" ng-controller="JefePracticantesCtrl">
    
    <div class="col-md-1"></div>
    <div class="col-md-10">
        @if(Session('error') != null)
        <div class="alert alert-danger alert-dismissable">
            <button type="button" class="close" data-dismiss="alert">&times;</button>
            {{ Session('error') }}
        </div>
        @endif
        <!--<button ng-click="aprobarpracticas()" ng-if="aprobados.ids.length > 0" class="btn btn-primary" -->
        <!--    data-toggle="modal" data-target="#cargando" data-backdrop="static" data-keyboard="false">-->
        <!--    Autorizar prácticantes-->
        <!--</button>-->
        <table  object-table
        	data = "practicantes"
        	display = "10"
        	headers = "Nombres, Apellidos, Programa, Cargo, Estado, Acciones"
        	fields = "empresa,nombre,vacantes,salario, estado"
        	sorting = "compound"
        	editable = "false"
        	resize="false"
        	drag-columns="false">
            <tbody>
                <td>
                    @{{ ::item.getestudiante.getpersona.nombres | uppercase }}
                </td>
                <td>
                    @{{ ::item.getestudiante.getpersona.apellidos | uppercase }}
                </td>
                <td>
                    @{{ ::item.getestudiante.getprograma.nombre | uppercase }}
                </td>
                <td>
                    @{{ ::item.getoferta.nombre | uppercase }}
                </td>
                <td>
                    @{{ ::item.getestudiante.getpracticas[item.getestudiante.getpracticas.length - 1].aprobacion_jefe | estadoPractrica }}
                </td>
                <td class="text-center">
                    <!--<input type="checkbox" ng-model="estadoActa" -->
                    <!--ng-show="item.getestudiante.getpracticas[item.getestudiante.getpracticas.length - 1].aprobacion_jefe == 0" -->
                    <!--ng-click="$owner.aprobarPracticante(item.getestudiante.getpracticas[item.getestudiante.getpracticas.length - 1].id)"/>-->
                    <a href="/evaluacion/evaluarestudiantebyjefe/@{{ ::item.getestudiante.id }}" class="medium-text" title="Evaluar practicante" ng-show="item.mostrar">
                        <span class="glyphicon glyphicon-edit"></span>
                    </a>
                    <span class="fa fa-binoculars blue" data-id="@{{ ::item.getestudiante.getpracticas[ item.getestudiante.getpracticas.length - 1 ].id }}" aria-hidden="true" ng-show="item.getestudiante.getpracticas[ item.getestudiante.getpracticas.length - 1 ].getvisitas.length > 0" title="Ver visitas" data-toggle="modal" data-target="#verVisitas"></span>
                    <a href="/jefe/veracta/@{{ ::item.getestudiante.getpracticas[item.getestudiante.getpracticas.length - 1].id }}" title="Ver acta de legalización">
                        <span class="glyphicon glyphicon-search"></span>
                    </a>
                </td>
            </tbody>
        </table>
    </div>
    <div class="col-md-1"></div>
    
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
                    	headers = "Fecha, Hora, Tema,Confirmación estudiante, Confirmación jefe inmediato"
                    	fields = "fecha,hora,tema,firma_estudiante,firma_jefe"
                    	sorting = "compound"
                    	editable = "false"
                    	resize="false"
                    	drag-columns="false">
                        <tbody>
                            <!--<td>-->
                            <!--    @{{ ::item.fecha_registro | date:'medium' }}-->
                            <!--</td>-->
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
                                <div ng-show="item.firma_jefe == null">
                                    Si <input type="radio" name="confirmar" ng-click="$owner.confirmarVisita(item.id)">
                                    No <input type="radio" name="confirmar" ng-click="$owner.confirmarvisita(item.id)">
                                </div>
                                <div ng-show="item.firma_jefe != null">
                                    @{{ item.firma_jefe | estadoVisita }}
                                </div>
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
    
</div>

@stop