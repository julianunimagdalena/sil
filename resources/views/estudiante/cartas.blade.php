@extends('master.master')

@section('title', 'Solicitar carta de presentación')

@section('contenido')

<div class="row" ng-controller="EstCartasCtrl">
    
    <div class="col-md-1"></div>
    <div class="col-md-10">
        <form role="form" ng-submit="solicitar_carta()">
            <div class="form-group">
                <label>Empresa</label>
                <ui-select ng-model="carta.empresa">
                    <ui-select-match>
                        <span ng-bind="$select.selected.nombre"></span>
                    </ui-select-match>
                    <ui-select-choices repeat="item in (empresas | filter: $select.search) track by item.id">
                        <span ng-bind="item.nombre"></span>
                    </ui-select-choices>
                </ui-select>
                <p class="help-block text-danger">
                    @{{ errores['empresa.id'][0] }}
                </p>
            </div>
            <div class="form-group">
                <label>Lugar de expedición del documento de identidad</label>
                <input type="text" class="form-control" ng-model="carta.ciudadExpedicion" placeholder="Ciudad en la que expidió su documento de identidad">
                <p class="help-block text-danger">
                    @{{ errores.ciudadExpedicion[0] }}
                </p>
            </div>
            <button type="submit" class="btn btn-success">Enviar</button>
        </form>
    </div>
    <div class="col-md-1"></div>
    
</div>

@stop