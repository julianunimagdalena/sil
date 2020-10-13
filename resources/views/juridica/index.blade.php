@extends('master.master')

@section('title', 'Inicio')

@section('contenido')

<div class="row" ng-controller="JuridicaConveniosCtrl">
    
    <div class="col-md-1"></div>
    <div class="col-md-10">
        @if(Session('error') != null)
        <div class="alert alert-danger alert-dismissable">
            <button type="button" class="close" data-dismiss="alert">&times;</button>
            {{ Session('error') }}
        </div>
        @endif
        <table  object-table
        	data = "convenios"
        	display = "10"
        	headers = "Empresa, Fecha de inicio, Fecha de terminación, Documentos, Estado, Acciones"
        	fields = "identificacion,nombre,correo,rol,dependencia,sede"
        	sorting = "compound"
        	editable = "false"
        	resize="false"
        	drag-columns="false">
            <tbody>
                <td>
                    @{{ ::item.getempresa.nombre }}
                </td>
                <td>
                    @{{ ::item.fecha_inicio }}
                </td>
                <td>
                    @{{ ::item.fecha_fin }}
                </td>
                <td>
                    <a href="/juridica/minuta/@{{::item.id}}" ng-show="item.minuta != null" target="_blank">
                        Descargar minuta
                    </a>
                    <a href="/juridica/certificadoexistencia/@{{::item.id}}" ng-show="item.certificado_existencia != null" target="_blank">
                        Certificado de existencia
                        <br>
                    </a>
                    
                    <a href="/juridica/cedula/@{{::item.id}}" ng-show="item.cedula_representante != null" target="_blank">
                        Cédula del representante legal
                        <br>
                    </a>
                    <a href="/juridica/procuraduria/@{{::item.id}}" ng-show="item.procuraduria != null" target="_blank">
                        Certificado procuraduría
                        <br>
                    </a>
                    <a href="/juridica/contraloria/@{{::item.id}}" ng-show="item.contraloria != null" target="_blank">
                        Certificado contraloría
                        <br>
                    </a>
                    <a href="/juridica/rut/@{{::item.id}}" ng-show="item.rut != null" target="_blank">
                        Rut
                        <br>
                    </a>
                    <a href="/juridica/actaposesion/@{{::item.id}}" ng-show="item.acta_posesion != null" target="_blank">
                        Acta de posesión
                        <br>
                    </a>
                    <a href="/juridica/actoadministrativo/@{{::item.id}}" ng-show="item.acto_administrativo != null" target="_blank">
                        Acto administrativo
                        <br>
                    </a>
                    <a href="/juridica/militar/@{{::item.id}}" ng-show="item.certificado_militar != null" target="_blank">
                        Certificado situación militar
                    </a>
                </td>
                <td>
                    @{{ ::item.getestado.nombre }}
                </td>
                <td class="text-center">
                    <span class="glyphicon glyphicon-search blue" title="Ver detaller de la empresa"></span>
                    
                    <span class="glyphicon glyphicon-edit blue" 
                        title="Adjuntar minuta" data-toggle="modal" data-target="#adjuntarMinuta" data-id="@{{item.id}}">
                    </span>
                </td>
            </tbody>
        </table>
    </div>
    <div class="col-md-1"></div>
    
    <!-- Modal -->
    <div id="adjuntarMinuta" class="modal fade" role="dialog">
        <div class="modal-dialog">
            <!-- Modal content-->
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title">Cambiar estado</h4>
                </div>
                <div class="modal-body">
                    <form role="form" ng-submit="revisionDocs()">
                        <div class="form-group">
                            <label >Estado</label>
                            <select class="form-control" ng-model="convenio.estado">
                                <option value="1">
                                    Todos los documentos están en orden
                                </option>
                                <option value="2">
                                    Algún documento está errado
                                </option>
                            </select>
                            <p class="help-block text-danger">
                                @{{ errores['estado'][0] }}
                            </p>
                        </div>
                        <div class="form-group" ng-if="convenio.estado == 2">
                            <label for="ejemplo_archivo_1">Observación</label>
                            <textarea class="form-control noresize" rows="5" ng-model="convenio.observacion"></textarea>
                            <p class="help-block text-danger">
                                @{{ errores.observacion[0] }}
                            </p>
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