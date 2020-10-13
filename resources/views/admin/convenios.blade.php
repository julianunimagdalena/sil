@extends('master.master')

@section('title', 'Convenios')

@section('contenido')

<style>
    .uib-datepicker-popup table
    {
        margin-top:-7px;
    }
</style>

<div class="row" ng-controller="AdminConveniosCtrl">
    
    <div class="col-md-1"></div>
    <div class="col-md-10">
        
        @if(Session('error') != null)
        <div class="alert alert-danger alert-dismissable">
            <button type="button" class="close" data-dismiss="alert">&times;</button>
            {{ Session('error') }}
        </div>
        @endif
        
        <a href="{{asset('/admin/conveniosexcel')}}" class="btn btn-primary" style="color:white !important;" target="_blank">Exportar excel</a>
        
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
                    <a href="@{{ $owner.raiz }}/admin/certificadoexistencia/@{{::item.id}}" ng-show="item.certificado_existencia != null" target="_blank">
                        Certificado de existencia
                        <br>
                    </a>
                    
                    <a href="@{{ $owner.raiz }}/admin/cedula/@{{::item.id}}" ng-show="item.cedula_representante != null" target="_blank">
                        Cédula del representante legal
                        <br>
                    </a>
                    <a href="@{{ $owner.raiz }}/admin/procuraduria/@{{::item.id}}" ng-show="item.procuraduria != null" target="_blank">
                        Certificado procuraduría
                        <br>
                    </a>
                    <a href="@{{ $owner.raiz }}/admin/contraloria/@{{::item.id}}" ng-show="item.contraloria != null" target="_blank">
                        Certificado contraloría
                        <br>
                    </a>
                    <a href="@{{ $owner.raiz }}/admin/rut/@{{::item.id}}" ng-show="item.rut != null" target="_blank">
                        Rut
                        <br>
                    </a>
                    <a href="@{{ $owner.raiz }}/admin/actaposesion/@{{::item.id}}" ng-show="item.acta_posesion != null" target="_blank">
                        Acta de posesión
                        <br>
                    </a>
                    <a href="@{{ $owner.raiz }}/admin/actoadministrativo/@{{::item.id}}" ng-show="item.acto_administrativo != null" target="_blank">
                        Acto administrativo
                        <br>
                    </a>
                    <a href="@{{ $owner.raiz }}/admin/militar/@{{::item.id}}" ng-show="item.certificado_militar != null" target="_blank">
                        Certificado situación militar
                        <br>
                    </a>
                    <a href="@{{ $owner.raiz }}/admin/fileconvenio/@{{::item.id}}" ng-show="item.convenio != null" target="_blank">
                        Convenio
                        <br>
                    </a>
                    <a href="@{{ $owner.raiz }}/admin/actarenovacion/@{{::item.id}}" ng-show="item.getactasrenovacion != null && item.getactasrenovacion.length > 0" target="_blank">
                        Acta de renovación
                    </a>
                </td>
                <td>
                    @{{ ::item.getestado.nombre }}
                </td>
                <td class="text-center">
                    <span class="glyphicon glyphicon-search blue" title="Ver detalles de la empresa" data-toggle="modal" data-target="#detallesEmpresa" data-id="@{{item.getempresa.id}}"></span>
                    <span ng-show="item.getestado.nombre == 'Esperando aprobación'">
                        <span class="glyphicon glyphicon-ok blue" title="Aprobar proceso de convenio" ng-click="$owner.aprobarConvenio(item.id)"></span>
                        <span class="glyphicon glyphicon-remove blue" title="No aprobar proceso de convenio" ng-click="$owner.noAprobarConvenio(item.id)"></span>
                    </span>
                    <span class="glyphicon glyphicon-edit blue" ng-show="item.getestado.nombre == 'En revisión por Dippro'"
                        title="Adjuntar minuta" data-toggle="modal" data-target="#adjuntarMinuta" data-id="@{{item.id}}">
                    </span>
                    <span ng-show="item.getestado.nombre == 'Aprobado por la oficina jurídica'"
                        title="Enviar por firma de la empresa" ng-click="$owner.firmarEmpresa(item.id)">
                        <img src="/img/firma_transparente2.png" style="max-width:20px;"></img>
                    </span>
                    <span class="glyphicon glyphicon-check blue" ng-show="item.getestado.nombre == 'Firma por parte de la empresa'"
                        title="Recibir convenio firmado" ng-click="$owner.recepcionDippro(item.id)">
                    </span>
                    <img src="/img/suscribir.png" style="max-width:20px;" data-toggle="modal" 
                        data-target="#suscribirConvenio" data-id="@{{item.id}}"
                        title="Suscribir convenio" ng-show="item.getestado.nombre == 'Recepción en Dippro'"/>
                        
                    <span class="glyphicon glyphicon-refresh blue" title="Renovar convenio" ng-show="item.getestado.nombre == 'Suscrito' && item.mostrar"
                        data-toggle="modal" data-target="#renovarConvenio" data-id="@{{item.id}}">
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
                    <h4 class="modal-title">Adjuntar minuta</h4>
                </div>
                <div class="modal-body">
                    <form role="form" ng-submit="revisionDocs()">
                        <div class="form-group">
                            <label >Estado</label>
                            <select class="form-control" ng-model="convenio.estado">
                                <option value="1">
                                    Enviar a la empresa
                                </option>
                                <option value="2">
                                    Enviar a jurídica
                                </option>
                            </select>
                            <p class="help-block text-danger">
                                @{{ errores['estado'][0] }}
                            </p>
                        </div>
                        <div class="form-group">
                            <label for="ejemplo_archivo_1">Minuta</label>
                            <input class="form-control file" type="file" uploader-model="convenio.file_minuta">
                            <p class="help-block text-danger"> @{{ errores.file_minuta[0] }} </p>
                        </div>
                        <div class="form-group">
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
    
    <div id="detallesEmpresa" class="modal fade" role="dialog">
        <div class="modal-dialog" style="width:900px;">
    
            <!-- Modal content-->
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title">Detalles</h4>
                </div>
                <div class="modal-body">
                    <form role="form" class="form-horizontal">
                        <div class="row">
                            <div class="col-md-12">
                                <fieldset>
                                    <legend>
                                        <b style="font-size:18px;">Datos de la empresa</b>
                                    </legend>
                                </fieldset>
                            </div>
                            <div class="form-group col-md-4">
                                <label class="col-lg-12" for="rol">Nombre</label>
                                <div class="col-lg-12">
                                    <p>
                                        @{{ empresa.nombre }}
                                    </p>
                                </div>
                            </div>
                            <div class="form-group col-md-4">
                                <label class="col-lg-12" for="rol">Tipo de documento</label>
                                <div class="col-lg-12">
                                    <p>
                                        @{{ empresa.gettiponit.nombre }}
                                    </p>
                                </div>
                            </div>
                            <div class="form-group col-md-4">
                                <label class="col-lg-12" for="rol">Nit</label>
                                <div class="col-lg-12">
                                    <p>
                                        @{{ empresa.nit }}
                                    </p>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="form-group col-md-4">
                                <label class="col-lg-12" for="rol">Correo</label>
                                <div class="col-lg-12">
                                    <p>
                                        @{{ empresa.getsedes[0].correo }}
                                    </p>
                                </div>
                            </div>
                            <div class="form-group col-md-4">
                                <label class="col-lg-12" for="rol">Pagina web</label>
                                <div class="col-lg-12">
                                    <p>
                                        @{{ empresa.paginaWeb }}
                                    </p>
                                </div>
                            </div>
                            <div class="form-group col-md-4">
                                <label class="col-lg-12" for="rol">Teléfono</label>
                                <div class="col-lg-12">
                                    <p>
                                        @{{ empresa.getsedes[0].telefono }}
                                    </p>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="form-group col-md-6">
                                <label class="col-lg-12" for="rol">Dirección</label>
                                <div class="col-lg-12">
                                    <p>
                                        @{{ empresa.getsedes[0].getmunicipio.getdepartamento.getpais.nombre }},
                                        @{{ empresa.getsedes[0].getmunicipio.getdepartamento.nombre }},
                                        @{{ empresa.getsedes[0].getmunicipio.nombre }} - 
                                        @{{ empresa.getsedes[0].direccion }}
                                    </p>
                                </div>
                            </div>
                            <div class="form-group col-md-6">
                                <label class="col-lg-12" for="rol">Tipo de empleador</label>
                                <div class="col-lg-12">
                                    <p>
                                        @{{ empresa.gettipoempleador.nombre }}
                                    </p>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="form-group col-md-12">
                                <label class="col-lg-12" for="rol">Actividad económica</label>
                                <div class="col-lg-12">
                                    <p>
                                        @{{ empresa.getactividadeconomica.nombre }}
                                    </p>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12">
                                <fieldset>
                                    <legend>
                                        <b style="font-size:18px;">
                                            Datos del representante legal
                                        </b>
                                    </legend>
                                </fieldset>
                            </div>
                            <div class="form-group col-md-6">
                                <label class="col-lg-12" for="rol">Nombre</label>
                                <div class="col-lg-12">
                                    <p>
                                        @{{ empresa.getrepresentante.nombres }}
                                        @{{ empresa.getrepresentante.apellidos }}
                                    </p>
                                </div>
                            </div>
                            <div class="form-group col-md-6">
                                <label class="col-lg-12" for="rol">Identificación</label>
                                <div class="col-lg-12">
                                    <p>
                                        @{{ empresa.getrepresentante.identificacion }}
                                    </p>
                                </div>
                            </div>
                        </div>
                        
                        <div class="row">
                            <div class="col-md-12">
                                <fieldset>
                                    <legend>
                                        <b style="font-size:18px;">
                                            Información del contacto
                                        </b>
                                    </legend>
                                </fieldset>
                            </div>
                            <div class="form-group col-md-6">
                                <label class="col-lg-12" for="rol">Nombre</label>
                                <div class="col-lg-12">
                                    <p>
                                        @{{ empresa.getsedes[0].getusuarios[0].getuser.nombres }}
                                        @{{ empresa.getsedes[0].getusuarios[0].getuser.apellidos }}
                                    </p>
                                </div>
                            </div>
                            <div class="form-group col-md-6">
                                <label class="col-lg-12" for="rol">Identificación</label>
                                <div class="col-lg-12">
                                    <p>
                                        @{{ empresa.getsedes[0].getusuarios[0].getuser.identificacion }}
                                    </p>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="form-group col-md-6">
                                <label class="col-lg-12" for="rol">Celular</label>
                                <div class="col-lg-12">
                                    <p>
                                        @{{ empresa.getsedes[0].getusuarios[0].getuser.celular }}
                                    </p>
                                </div>
                            </div>
                            <div class="form-group col-md-6">
                                <label class="col-lg-12" for="rol">Correo</label>
                                <div class="col-lg-12">
                                    <p>
                                        @{{ empresa.getsedes[0].getusuarios[0].getuser.correo }}
                                    </p>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-rojo" data-dismiss="modal">Cerrar</button>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Modal -->
    <div id="suscribirConvenio" class="modal fade" role="dialog">
        <div class="modal-dialog">
            <!-- Modal content-->
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title">Suscribir convenio</h4>
                </div>
                <div class="modal-body">
                    <form role="form" ng-submit="suscribir_convenio()">
                        <div class="form-group row">
                            <label class="col-md-12">Convenio</label>
                            <div class="col-md-12">
                                <input type="file" uploader-model="convenio.file_convenio"  class="form-control file"/>
                                <p class="help-block text-danger">
                                    @{{ errores.file_convenio[0] }}
                                </p>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-md-12">Fecha inicial</label>
                            <div class="col-md-12">
                                <p class="input-group" ng-init="fecha_inicio_opened = false">
                                    <input type="text" class="form-control" uib-datepicker-popup="dd/MM/yyyy" ng-model="convenio.fecha_inicial" is-open="fecha_inicio_opened" ng-click="fecha_inicio_opened=!fecha_inicio_opened"/>
                                    <span class="input-group-btn">
                                        <button type="button" class="btn btn-default" ng-click="fecha_inicio_opened = !fecha_inicio_opened"><i class="glyphicon glyphicon-calendar"></i></button>
                                    </span>
                                </p>
                                <!--<input type="date" ng-model="visita.fecha" class="form-control"/>-->
                                <p class="help-block text-danger">
                                    @{{ errores.fecha_inicial[0] }}
                                </p>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-md-12">Fecha final</label>
                            <div class="col-md-12">
                                <p class="input-group" ng-init="opened = false">
                                    <input type="text" class="form-control" uib-datepicker-popup="dd/MM/yyyy" ng-model="convenio.fecha_final" is-open="opened" ng-click="opened=!opened"/>
                                    <span class="input-group-btn">
                                        <button type="button" class="btn btn-default" ng-click="opened = !opened"><i class="glyphicon glyphicon-calendar"></i></button>
                                    </span>
                                </p>
                                <!--<input type="date" ng-model="visita.fecha" class="form-control"/>-->
                                <p class="help-block text-danger">
                                    @{{ errores.fecha_final[0] }}
                                </p>
                            </div>
                        </div>
                        <button type="submit" value="Submit" class="btn btn-success">
                            Guardar
                        </button>
                    </form>
                </div>
            </div>
    
        </div>
    </div>
    
    
    <!-- Modal -->
    <div id="renovarConvenio" class="modal fade" role="dialog">
        <div class="modal-dialog">
            <!-- Modal content-->
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title">Renovar convenio</h4>
                </div>
                <div class="modal-body">
                    <form role="form" ng-submit="renovar_convenio()">
                        <div class="form-group row">
                            <label class="col-md-12">Acta de renovación</label>
                            <div class="col-md-12">
                                <input type="file" uploader-model="convenio.file_renovacion"  class="form-control file"/>
                                <p class="help-block text-danger">
                                    @{{ errores.file_renovacion[0] }}
                                </p>    
                            </div>
                            
                            <label class="col-md-12">Fecha</label>
                            <div class="col-md-12">
                                <p class="input-group" ng-init="fecha_opened = false">
                                    <input type="text" class="form-control" uib-datepicker-popup="dd/MM/yyyy" ng-model="convenio.fecha" is-open="fecha_opened" ng-click="fecha_opened=!fecha_opened"/>
                                    <span class="input-group-btn">
                                        <button type="button" class="btn btn-default" ng-click="fecha_opened = !fecha_opened"><i class="glyphicon glyphicon-calendar"></i></button>
                                    </span>
                                </p>
                                <!--<input type="date" ng-model="visita.fecha" class="form-control"/>-->
                                <p class="help-block text-danger">
                                    @{{ errores.fecha[0] }}
                                </p>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-lg-2">Descripción</label>
                            <div class="col-lg-12">
                                <textarea class="form-control noresize" rows="5" ng-model="convenio.descripcion"></textarea>    
                                <p class="help-block text-danger">
                                    @{{ errores.descripcion[0] }}
                                </p>
                            </div>
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