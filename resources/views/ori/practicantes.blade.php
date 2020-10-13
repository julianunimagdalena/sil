@extends('master.master')

@section('title', 'Inicio')

@section('contenido')

<div class="row" ng-controller="practicantesOriCtrl">
    <div class="col-md-1"></div>
    <div class="col-md-10">
        <table  object-table
        	data = "practicantes"
        	display = "10"
        	headers = "Código, Nombres, Apellidos,Programa,Modalidad,Documentos,  Acciones"
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
                    <a href="/ori/cartasolicitud/@{{item.id}}" ng-show="item.file_carta_solicitud != null" target="_blank">
                        Carta de solicitud de practicas
                        <br>
                    </a>
                    
                    <a href="/ori/certificadoexistenciaprac/@{{item.id}}" 
                    ng-show="item.getmodalidad.nombre == 'Prácticas internacionales'
                           && item.file_existencia_empresa != null" target="_blank">
                        Certificado de existencia de la empresa
                        <br>
                    </a>
                    
                    <a href="/ori/cartacolaboracion/@{{item.id}}" 
                    ng-show="item.file_carta_colaboracion != null" target="_blank">
                        Carta de colaboración con el estudiante
                        <br>
                    </a>
                        
                    <a href="/ori/cartadirector/@{{item.id}}" target="_blank"
                        ng-show="item.file_carta_director_programa != null">
                        Carta del director de programa
                        <br>
                    </a>
                    <a href="/ori/formatomovilidad/@{{item.id}}" target="_blank"
                        ng-show="item.file_formato_movilidad != null">
                        Formato de movilidad
                        <br>
                    </a>
                    <a href="/ori/pasaporte/@{{item.id}}" target="_blank"
                        ng-show="item.file_pasaporte != null">
                        Pasaporte
                        <br>
                    </a>
                    <a href="/ori/visa/@{{item.id}}" target="_blank"
                        ng-show="item.file_visa != null">
                        Visa
                        <br>
                    </a>
                    <a href="/ori/cedulaestudiante/@{{item.id}}" target="_blank"
                        ng-show="item.file_cedula != null">
                        Cédula del estudiante
                        <br>
                    </a>
                    <a href="/ori/carnet/@{{item.id}}" target="_blank"
                        ng-show="item.file_carnet != null">
                        Carnet estudiantil
                        <br>
                    </a>
                    <a href="/ori/padres/@{{item.id}}" target="_blank"
                        ng-show="item.file_padres != null">
                        Extrajuicio de los padres
                        <br>
                    </a>
                    <a href="/ori/extraestudiante/@{{item.id}}" target="_blank"
                        ng-show="item.file_estudiante != null">
                        Extrajuicio del estudiante
                        <br>
                    </a>
                    <a href="/ori/itinerario/@{{item.id}}" target="_blank"
                        ng-show="item.file_itinerario != null">
                        Itinerario de vuelo
                        <br>
                    </a>
                    <a href="/ori/seguro/@{{item.id}}" target="_blank"
                        ng-show="item.file_seguro != null">
                        Seguro médico internacional
                        <br>
                    </a>
                    
                </td>
                <td class="text-center">
                    <span class="glyphicon glyphicon-pencil blue pointer" title="Cambiar estado" 
                          data-toggle="modal" data-target="#cambiarEstado" data-id="@{{item.id}}">
                    </span>
                </td>
            </tbody>
        </table>
    </div>
    <div class="col-md-1"></div>
    
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
                                <select ng-model="practicante.practica.estado" ng-change="cambio()" class="form-control">
                                    <option value="1">Documentos en orden</option>
                                    <option value="2">Enviar observaciones</option>
                                </select>
                                <p class="help-block text-danger">
                                    @{{ errores['practica.estado'][0] }}
                                </p>
                            </div>
                        </div>
                        
                        <div class="form-group" ng-if="practicante.practica.estado == '2' && booleano">
                            <label class="col-lg-2" for="rol">Observaciones</label>
                            <div class="col-lg-12">
                                <textarea ng-model="practicante.practica.observaciones" cols="30" rows="5" class="form-control noresize"></textarea>
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