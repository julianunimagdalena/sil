@extends('master.master')

@section('title', 'Solicitud de convenio')

@section('contenido')

<div class="row" ng-controller="EmpConveniosCtrl">
    <div class="col-md-1"></div>
    <div class="col-md-10">
        @if(Session('error') != null)
        <div class="alert alert-danger alert-dismissable">
            <button type="button" class="close" data-dismiss="alert">&times;</button>
            {{ Session('error') }}
        </div>
        @endif
        
        <button class="btn btn-primary" ng-click="solicitarConvenio()" ng-if="ultimoConvenio.getestado == null || ultimoConvenio.getestado.nombre == 'No aprobado'" >
            Solicitar convenio
        </button>
        
        <table  object-table
        	data = "convenios"
        	display = "10"
        	headers = "Fecha de inicio, Fecha de terminación, Documentos, Estado, Acciones"
        	fields = "identificacion,nombre,correo,rol,dependencia,sede"
        	sorting = "compound"
        	editable = "false"
        	resize="false"
        	drag-columns="false">
            <tbody>
                <td>
                    @{{ ::item.fecha_inicio }}
                </td>
                <td>
                    @{{ ::item.fecha_fin }}
                </td>
                <td>
                    <a href="/empresa/certificadoexistencia/@{{::item.id}}" ng-show="item.certificado_existencia != null" target="_blank">
                        Certificado de existencia
                        <br>
                    </a>
                    
                    <a href="/empresa/cedula/@{{::item.id}}" ng-show="item.cedula_representante != null" target="_blank">
                        Cédula del representante legal
                        <br>
                    </a>
                    <a href="/empresa/procuraduria/@{{::item.id}}" ng-show="item.procuraduria != null" target="_blank">
                        Certificado procuraduría
                        <br>
                    </a>
                    <a href="/empresa/contraloria/@{{::item.id}}" ng-show="item.contraloria != null" target="_blank">
                        Certificado contraloría
                        <br>
                    </a>
                    <a href="/empresa/rut/@{{::item.id}}" ng-show="item.rut != null" target="_blank">
                        Rut
                        <br>
                    </a>
                    <a href="/empresa/actaposesion/@{{::item.id}}" ng-show="item.acta_posesion != null" target="_blank">
                        Acta de posesión
                        <br>
                    </a>
                    <a href="/empresa/actoadministrativo/@{{::item.id}}" ng-show="item.acto_administrativo != null" target="_blank">
                        Acto administrativo
                        <br>
                    </a>
                    <a href="/empresa/militar/@{{::item.id}}" ng-show="item.certificado_militar != null" target="_blank">
                        Certificado situación militar
                        <br>
                    </a>
                    <a href="/empresa/fileconvenio/@{{::item.id}}" ng-show="item.convenio != null" target="_blank">
                        Convenio
                        <br>
                    </a>
                    <a href="/empresa/actarenovacion/@{{::item.id}}" ng-show="item.getactasrenovacion != null && item.getactasrenovacion.length > 0" target="_blank">
                        Acta de renovación
                    </a>
                </td>
                <td>
                    @{{ ::item.getestado.nombre }}
                </td>
                <td class="text-center">
                    <a href="/empresa/subirdocs/@{{ item.id }}">
                        <span class="glyphicon glyphicon-paperclip pointer blue" title="Cargar documentos" ng-show="item.getestado.nombre == 'Aprobado'">
                        </span>
                    </a>
                    <span class="glyphicon glyphicon-send blue" title="Enviar convenio a Dippro" ng-click="$owner.enviar(item.id)" ng-show="item.getestado.nombre == 'Aprobado'"
                        >
                        
                    </span>
                </td>
            </tbody>
        </table>
        
    </div>
    <div class="col-md-1"></div>
</div>

@stop