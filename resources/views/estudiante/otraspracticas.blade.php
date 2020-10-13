<!--<span class="fa fa-pencil-square-o" title="Auto evaluación" style="font-size:5em;">-->
<!--                            </span>-->
@extends('master.master')

@section('title', 'Prácticas')

@section('contenido')

<div class="row" ng-controller="EstOtrasPracticaCtrl">
    <div class="col-md-1"></div>
    <div class="col-md-10">
        <div class="panel panel-primary">
            <div class="panel-heading">
                <h3 class="panel-title">
                    Práctica profesional
                </h3>
            </div>
            <div class="panel-body">
                <div class="row">
                    <form role="form">
                        <div class="form-group">
                            <label class="col-md-2">Nombre</label>
                            <label class="col-md-10 nobolder">
                                @{{ estudiante.getpersona.nombres | uppercase }}
                                @{{ estudiante.getpersona.apellidos | uppercase }}
                            </label>
                        </div>
                        <div class="form-group">
                            <label class="col-md-2">Modalidad</label>
                            <label class="col-md-10 nobolder">
                                @{{ estudiante.getpracticas[estudiante.getpracticas.length - 1].getmodalidad.nombre | uppercase }}
                            </label>
                        </div>
                        <div class="form-group">
                            <label class="col-md-2">Estado</label>
                            <label class="col-md-10 nobolder">
                                @{{ estudiante.getpracticas[estudiante.getpracticas.length - 1].getestado.nombre | uppercase }}
                            </label>
                        </div>
                    </form>
                </div>
                
            </div>
            
        </div>
    </div>
    <div class="col-md-1"></div>
    
</div>

@stop