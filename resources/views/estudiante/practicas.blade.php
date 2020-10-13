<!--<span class="fa fa-pencil-square-o" title="Auto evaluación" style="font-size:5em;">-->
<!--                            </span>-->
@extends('master.master')

@section('title', 'Prácticas')

@section('contenido')

<div class="row" ng-controller="EstPracticaCtrl">
    <div class="col-md-1"></div>
    <div class="col-md-10">
        @if(Session('error') != null)
        <div class="alert alert-danger alert-dismissable">
            <button type="button" class="close" data-dismiss="alert">&times;</button>
            {{ Session('error') }}
        </div>
        @endif
        @if(Session('success') != null)
        <div class="alert alert-success alert-dismissable">
            <button type="button" class="close" data-dismiss="alert">&times;</button>
            {{ Session('success') }}
        </div>
        @endif
        <div class="panel panel-primary">
            <div class="panel-heading">
                <h3 class="panel-title">
                    <div class="izq" style="margin-top:8px">
                        Práctica profesional
                    </div>
                    <div class="der text-right">
                        <a href="/evaluacion/autoevaluacionestudiante" ng-if="apto == 1">
                            <span class="fa fa-pencil-square-o" title="Auto evaluación" style="color:white !important; font-size:2em;">
                            </span>
                        </a>
                        <span class="glyphicon glyphicon-paperclip pointer" ng-if="apto == 1" title="Adjuntar informe de prácticas" 
                              style="color:white !important; font-size:2em;" data-toggle="modal" data-target="#adjuntar">
                        </span>
                    </div>
                </h3>
            </div>
            <div class="panel-body">
                <div class="row">
                    <div class="col-lg-4">
                        <div class="form-group">
                            <label class="col-lg-12 text-center">Empresa</label>
                            <label class="col-lg-12 text-center">
                                <p class="nobolder">
                                    @{{ practicas.postulado.getoferta.getsede.getempresa.nombre | uppercase }}
                                </p>
                            </label>
                        </div>
                    </div>
                    <div class="col-lg-4">
                        <div class="form-group">
                            <label class="col-lg-12 text-center">Cargo</label>
                            <label class="col-lg-12 text-center">
                                <p class="nobolder">
                                    @{{ practicas.postulado.getoferta.nombre | uppercase }}
                                </p>
                            </label>
                        </div>
                    </div>
                    <div class="col-lg-4">
                        <div class="form-group">
                            <label class="col-lg-12 text-center">Jefe inmediato</label>
                            <label class="col-lg-12 text-center">
                                <p class="nobolder">
                                    @{{ practicas.postulado.getoferta.getjefe.getuser.nombres | uppercase }}
                                    @{{ practicas.postulado.getoferta.getjefe.getuser.apellidos | uppercase }}
                                </p>
                            </label>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-lg-4">
                        <div class="form-group">
                            <label class="col-lg-12 text-center">Tiempo de realización</label>
                            <label class="col-lg-12 text-center">
                                <p style="font-size:13px;">
                                    Inicio: <span class="nobolder">
                                        @{{ practicas.practica.fecha_inicio || " - " | date:'dd/MM/yyyy' }}
                                    </span>
                                <!--</p>-->
                                <!--<p >-->
                                    Fin: <span class="nobolder">
                                        @{{ practicas.practica.fecha_fin || " - " | date:'dd/MM/yyyy' }}
                                    </span>
                                </p>
                            </label>
                        </div>
                    </div>
                    <div class="col-lg-4">
                        <div class="form-group">
                            <label class="col-lg-12 text-center">Estado jefe inmediato</label>
                            <label class="col-lg-12 text-center">
                                <p class="nobolder">
                                    @{{ practicas.practica.aprobacion_jefe | estadoPractrica}}
                                </p>
                            </label>
                        </div>
                    </div>
                    <div class="col-lg-4">
                        <div class="form-group">
                            <label class="col-lg-12 text-center">Estado DIPPRO</label>
                            <label class="col-lg-12 text-center">
                                <p class="nobolder">
                                    @{{ practicas.practica.getestado.nombre }}
                                </p>
                            </label>
                        </div>
                    </div>
                </div>
            </div>
            <div class="panel-heading" style="border-top-left-radius: 0px;border-top-right-radius: 0px;">
                <h3 class="panel-title">
                    <div class="izq" style="margin-top:8px">
                        Tutor
                    </div>
                    <div class="der text-right">
                        <a href="/evaluacion/evaluartutorbyestudiante" ng-if="apto == 1">
                            <span class="fa fa-pencil-square-o" title="Evaluar tutor" style="color:white !important; font-size:2em;">
                            </span>    
                        </a>
                    </div>
                </h3>
            </div>
            <div class="panel-body">
                <div class="row text-center">
                    <div class="col-md-4">
                        <div class="form-group">
                            <label class="col-lg-12">Nombre</label>
                            <label class="col-lg-12 nobolder">
                                <p>
                                    @{{ practicas.practica.gettutores[practicas.practica.gettutores.length - 1].getuser.nombres }}
                                    @{{ practicas.practica.gettutores[practicas.practica.gettutores.length - 1].getuser.apellidos }}
                                </p>
                            </label>
                        </div>
                    </div>
                    <div class="col-md-4">    
                        <div class="form-group">
                            <label class="col-lg-12">Correo</label>
                            <p>
                                @{{ practicas.practica.gettutores[practicas.practica.gettutores.length - 1].getuser.correo }}
                            </p>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label class="col-lg-12">Celular</label>
                            <label class="col-lg-12 nobolder">
                                <p>
                                    @{{ practicas.practica.gettutores[practicas.practica.gettutores.length - 1].getuser.celular }}
                                </p>
                            </label>
                        </div>
                    </div>
                    <div class="col-md-12" ng-if="visitas.length > 0">
                        <fieldset>
                            <legend>
                                <b>Visitas o Contacto</b>
                            </legend>
                            <table  object-table
                            	data = "visitas"
                            	display = "10"
                            	headers = "Fecha, Hora, Tema, Confirmación jefe inmediato, Confirmar"
                            	fields = "fecha_registro,fecha,hora,tema,firma_jefe"
                            	sorting = "compound"
                            	editable = "false"
                            	resize="false"
                            	drag-columns="false">
                                <tbody>
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
                                        @{{ ::item.firma_jefe }}
                                    </td>
                                    <td>
                                        <div ng-show="item.firma_estudiante == null">
                                            Si <input type="radio" name="confirmar" ng-click="$owner.confirmarVisita(item.id)">
                                            No <input type="radio" name="confirmar" ng-click="$owner.confirmarvisita(item.id)">
                                        </div>
                                        <div ng-show="item.firma_estudiante != null">
                                            @{{ item.firma_estudiante | estadoVisita }}
                                        </div>
                                    </td>
                                </tbody>
                            </table>
                        </fieldset>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-1"></div>
    
    <!-- Modal -->
    <div id="adjuntar" class="modal fade" role="dialog">
        <div class="modal-dialog">
    
            <!-- Modal content-->
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title">Subir informe de prácticas</h4>
                </div>
                <div class="modal-body">
                    <form role="form" action="/estudiante/informepracticas" method="POST" enctype="multipart/form-data">
                        <div class="form-group">
                            <label for="ejemplo_archivo_1">Informe de prácticas</label>
                            <input type="file" name="informe">
                            <p class="help-block">Solo formato pdf o docx y tamaño menor o igual a 1MB.</p>
                            @if($errors->first('informe'))<p class="help-block text-danger">{{$errors->first('informe')}}</p>@endif
                        </div>
                        <button type="submit" value="Submit" class="btn btn-success">
                            Guardar
                        </button>
                    </form>
                </div>
            </div>
    
        </div>
    </div>
</div>

@stop