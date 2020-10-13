@extends('master.master')

@section('title', 'Verificación de cartas de presentación')

@section('contenido')
<div class="row" ng-controller="homeCartasCtrl">
    
    <div class="col-md-1"></div>
    <div class="col-md-10">
        <div class="col-md-12 text-center separator">
            <form role="form" class="form-inline" ng-submit="verificarCarta()">
                <div class="form-group">
                    <label>Código de verificación</label>
                    <input type="text" class="form-control" ng-model="carta.codigo" placeholder="Código de verificaión">
                </div>
                <button type="submit" class="btn btn-success">Verificar</button>
            </form>
        </div>
        
        <div class="col-md-12" ng-if="enlace != null">
            <center>
                <iframe src="@{{enlace}}" frameborder="0" width="100%" height="700"></iframe>    
            </center>
        </div>
    </div>
    <div class="col-md-1"></div>
    
</div>
@stop