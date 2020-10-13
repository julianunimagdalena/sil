@extends('master.master')

@section('title', 'Aprobar prácticas')

@section('contenido')

<div class="row" ng-controller="AdminActasCtrl">
    
    <div class="col-md-1"></div>
    <div class="col-md-10">
        
        @if(Session('error') != null)
        <div class="alert alert-danger alert-dismissable">
            <button type="button" class="close" data-dismiss="alert">&times;</button>
            {{ Session('error') }}
        </div>
        @endif
        
        <fieldset>
            <legend>Aprobar actas de legalización de prácticas</legend>
            <table  object-table
            	data = "actas"
            	display = "10"
            	headers = "Código, Nombres, Apellidos,Programa,Modalidad,Empresa,Estado,  Acciones"
            	fields = "getempresa.nit,getempresa.nombre,getmunicipio.getdepartamento.getpais.nombre,getmunicipio.getdepartamento.nombre,getmunicipio.nombre, getempresa.getestadodipro.nombre"
            	sorting = "compound"
            	editable = "false"
            	resize="false"
            	drag-columns="false">
                <tbody>
                    <td>
                        @{{ ::item.getestudiante.codigo }}
                    </td>
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
                        @{{ ::item.getmodalidad.nombre | uppercase }}
                    </td>
                    <td>
                        <span ng-show="item.empresa != null">
                            @{{ ::item.empresa | uppercase }}
                        </span> 
                        <span ng-show="item.empresa == null">
                            @{{ ::item.getestudiante.getpostulaciones[ item.getestudiante.getpostulaciones.length - 1].getoferta.getsede.getempresa.nombre | uppercase }}
                        </span>
                    </td>
                    <td>
                        @{{ ::item.getestado.nombre | uppercase }}                        
                    </td>
                    <td class="text-center">
                        <a href="@{{ $owner.raiz }}/admin/veracta/@{{ ::item.id }}" ng-show="item.getmodalidad.nombre == 'Vinculación laboral'" >
                            <span class="glyphicon glyphicon-search pointer" title="Ver acta"></span>
                        </a>
                        <span class="fa fa-file-text blue" aria-hidden="true" title="Ver documentos" ng-show="item.getmodalidad.nombre != 'Vinculación laboral'"
                              data-toggle="modal" data-target="#documentos" data-id="@{{item.getestudiante.id}}">
                        </span>
                        <span class="glyphicon glyphicon-pencil blue pointer" title="Cambiar estado" ng-show="item.getmodalidad.nombre != 'Vinculación laboral'"
                              data-toggle="modal" data-target="#cambiarEstado" data-id="@{{item.getestudiante.id}}">
                        </span>
                    </td>
                </tbody>
            </table>
            
        </fieldset>
    </div>
    <div class="col-md-1"></div>
    
    <!-- Modal -->
    <div id="documentos" class="modal fade" role="dialog">
        <div class="modal-dialog">
    
            <!-- Modal content-->
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title">Documentos</h4>
                </div>
                <div class="modal-body text-center">
                    <a href="@{{ raiz }}/admin/cartasolicitud/@{{estudiante.getpracticas[estudiante.getpracticas.length - 1].id}}" ng-if="estudiante.getpracticas[estudiante.getpracticas.length - 1].file_carta_solicitud != null" target="_blank">
                        Carta de solicitud de practicas
                        <br>
                    </a>
                    
                    <a href="@{{ raiz }}/admin/certificadolaboral/@{{estudiante.getpracticas[estudiante.getpracticas.length - 1].id}}" ng-if="estudiante.getpracticas[estudiante.getpracticas.length - 1].getmodalidad.nombre == 'Validación' && estudiante.getpracticas[estudiante.getpracticas.length - 1].file_certificado_laboral != null" target="_blank">
                        Certificado laboral
                        <br>
                    </a>
                    
                    <a href="@{{ raiz }}/admin/certificadoexistenciaprac/@{{estudiante.getpracticas[estudiante.getpracticas.length - 1].id}}" 
                    ng-if="(estudiante.getpracticas[estudiante.getpracticas.length - 1].getmodalidad.nombre == 'Validación'
                            || estudiante.getpracticas[estudiante.getpracticas.length - 1].getmodalidad.nombre == 'Asesorías pymes'
                            || estudiante.getpracticas[estudiante.getpracticas.length - 1].getmodalidad.nombre == 'Prácticas internacionales'
                            || estudiante.getpracticas[estudiante.getpracticas.length - 1].getmodalidad.nombre == 'Prácticas de empresarismo')
                           && estudiante.getpracticas[estudiante.getpracticas.length - 1].file_existencia_empresa != null" target="_blank">
                        Certificado de existencia de la empresa
                        <br>
                    </a>
                    
                    <a href="@{{ raiz }}/admin/certificadoss/@{{estudiante.getpracticas[estudiante.getpracticas.length - 1].id}}" 
                    ng-if="(estudiante.getpracticas[estudiante.getpracticas.length - 1].getmodalidad.nombre == 'Validación'
                            || estudiante.getpracticas[estudiante.getpracticas.length - 1].getmodalidad.nombre == 'Prácticas de empresarismo')
                           && estudiante.getpracticas[estudiante.getpracticas.length - 1].file_afiliacion_ss != null" target="_blank">
                        Certificado de afiliacion a seguridad social
                        <br>
                    </a>
                    
                    <a href="@{{ raiz }}/admin/contrato/@{{estudiante.getpracticas[estudiante.getpracticas.length - 1].id}}" ng-if="estudiante.getpracticas[estudiante.getpracticas.length - 1].getmodalidad.nombre == 'Validación' && estudiante.getpracticas[estudiante.getpracticas.length - 1].file_certificado_laboral != null" target="_blank">
                        Contrato de prestación de servicios
                        <br>
                    </a>
                    
                    <a href="@{{ raiz }}/admin/cartacolaboracion/@{{estudiante.getpracticas[estudiante.getpracticas.length - 1].id}}" 
                    ng-if="(estudiante.getpracticas[estudiante.getpracticas.length - 1].getmodalidad.nombre == 'Asesorías pymes'
                            || estudiante.getpracticas[estudiante.getpracticas.length - 1].getmodalidad.nombre == 'Prácticas internacionales'
                            || estudiante.getpracticas[estudiante.getpracticas.length - 1].getmodalidad.nombre == 'Semestre en el exterior')
                           && estudiante.getpracticas[estudiante.getpracticas.length - 1].file_carta_colaboracion != null" target="_blank">
                        Carta de colaboración con el estudiante
                        <br>
                    </a>
                    
                    <a href="@{{ raiz }}/admin/cedularelegal/@{{estudiante.getpracticas[estudiante.getpracticas.length - 1].id}}" 
                    ng-if="estudiante.getpracticas[estudiante.getpracticas.length - 1].getmodalidad.nombre == 'Asesorías pymes'
                           && estudiante.getpracticas[estudiante.getpracticas.length - 1].file_cedula_relegal != null" target="_blank">
                        Cédula del representante legal
                        <br>
                    </a>
                    
                    <div ng-if="estudiante.getpracticas[estudiante.getpracticas.length - 1].getmodalidad.nombre == 'Prácticas internacionales'
                            || estudiante.getpracticas[estudiante.getpracticas.length - 1].getmodalidad.nombre == 'Semestre en el exterior'">
                        
                        <a href="@{{ raiz }}/admin/cartadirector/@{{estudiante.getpracticas[estudiante.getpracticas.length - 1].id}}" target="_blank"
                            ng-if="estudiante.getpracticas[estudiante.getpracticas.length - 1].file_carta_director_programa != null">
                            Carta del director de programa
                            <br>
                        </a>
                        <a href="@{{ raiz }}/admin/formatomovilidad/@{{estudiante.getpracticas[estudiante.getpracticas.length - 1].id}}" target="_blank"
                            ng-if="estudiante.getpracticas[estudiante.getpracticas.length - 1].file_formato_movilidad != null">
                            Formato de movilidad
                            <br>
                        </a>
                        <a href="@{{ raiz }}/admin/pasaporte/@{{estudiante.getpracticas[estudiante.getpracticas.length - 1].id}}" target="_blank"
                            ng-if="estudiante.getpracticas[estudiante.getpracticas.length - 1].file_pasaporte != null">
                            Pasaporte
                            <br>
                        </a>
                        <a href="@{{ raiz }}/admin/visa/@{{estudiante.getpracticas[estudiante.getpracticas.length - 1].id}}" target="_blank"
                            ng-if="estudiante.getpracticas[estudiante.getpracticas.length - 1].file_visa != null">
                            Visa
                            <br>
                        </a>
                        <a href="@{{ raiz }}/admin/cedulaestudiante/@{{estudiante.getpracticas[estudiante.getpracticas.length - 1].id}}" target="_blank"
                            ng-if="estudiante.getpracticas[estudiante.getpracticas.length - 1].file_cedula != null">
                            Cédula del estudiante
                            <br>
                        </a>
                        <a href="@{{ raiz }}/admin/carnet/@{{estudiante.getpracticas[estudiante.getpracticas.length - 1].id}}" target="_blank"
                            ng-if="estudiante.getpracticas[estudiante.getpracticas.length - 1].file_carnet != null">
                            Carnet estudiantil
                            <br>
                        </a>
                        <a href="@{{ raiz }}/admin/padres/@{{estudiante.getpracticas[estudiante.getpracticas.length - 1].id}}" target="_blank"
                            ng-if="estudiante.getpracticas[estudiante.getpracticas.length - 1].file_padres != null">
                            Extrajuicio de los padres
                            <br>
                        </a>
                        <a href="@{{ raiz }}/admin/extraestudiante/@{{estudiante.getpracticas[estudiante.getpracticas.length - 1].id}}" target="_blank"
                            ng-if="estudiante.getpracticas[estudiante.getpracticas.length - 1].file_estudiante != null">
                            Extrajuicio del estudiante
                            <br>
                        </a>
                        <a href="@{{ raiz }}/admin/itinerario/@{{estudiante.getpracticas[estudiante.getpracticas.length - 1].id}}" target="_blank"
                            ng-if="estudiante.getpracticas[estudiante.getpracticas.length - 1].file_itinerario != null">
                            Itinerario de vuelo
                            <br>
                        </a>
                        <a href="@{{ raiz }}/admin/seguro/@{{estudiante.getpracticas[estudiante.getpracticas.length - 1].id}}" target="_blank"
                            ng-if="estudiante.getpracticas[estudiante.getpracticas.length - 1].file_seguro != null">
                            Seguro médico internacional
                            <br>
                        </a>
                    </div>
                        
                    
                    
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-rojo" data-dismiss="modal">Cerrar</button>
                </div>
            </div>
    
        </div>
    </div>
    
    <!-- Modal -->
    <div id="cambiarEstado" class="modal fade" role="dialog">
        <div class="modal-dialog">
    
            <!-- Modal content-->
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title">Cambiar estado</h4>
                </div>
                <div class="modal-body">
                    
                    <!---->
                    
                    <form role="form" class="form-horizontal">
                            
                        <div class="form-group">
                            <label class="col-lg-2" for="rol">Estado</label>
                            <div class="col-lg-12">
                                <ui-select ng-model="estudiante.practica.estado" ng-change="cambio()">
                                    <ui-select-match>
                                        <span ng-bind="$select.selected.nombre"></span>
                                    </ui-select-match>
                                    <ui-select-choices repeat="item in (estados | filter: $select.search) track by item.id">
                                        <span ng-bind="item.nombre"></span>
                                    </ui-select-choices>
                                </ui-select>
                                <p class="help-block text-danger">
                                    @{{ errores['practica.estado.id'][0] }}
                                </p>
                            </div>
                        </div>
                        
                        <div class="form-group" ng-if="estudiante.practica.estado.nombre != 'Aprobada' && booleano">
                            <label class="col-lg-2" for="rol">Observaciones</label>
                            <div class="col-lg-12">
                                <textarea ng-model="estudiante.practica.observaciones" cols="30" rows="5" class="form-control noresize"></textarea>
                                <p class="help-block text-danger">
                                    @{{ errores['practica.observaciones'][0] }}
                                </p>
                            </div>
                        </div>
                    </form>
                    
                    <!---->
                    
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-rojo" data-dismiss="modal">Cerrar</button>
                    <button type="button" class="btn btn-success" ng-click="cambiarEstadoPractica()">Guardar</button>
                </div>
            </div>
    
        </div>
    </div>
    
</div>

@stop