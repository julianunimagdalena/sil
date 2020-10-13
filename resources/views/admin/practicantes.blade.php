@extends('master.master')

@section('title', 'Practicantes')

@section('contenido')

<div class="row" ng-controller="AdminPracticantesCtrl">
    
    <div class="col-md-1"></div>
    <div class="col-md-10">
        <fieldset>
            <legend>Practicantes</legend>
            <div class="row">
                <div class="col-md-3">
                    Filtrar por estado
                    <br>
                    <select class="form-control" ng-model="filtro" ng-change="filtrar()">
                        <option value="0">Todos</option>
                        <option value="1">Realizando prácticas</option>
                        <option value="2">No se encuentra realizando prácticas</option>
                    </select>
                </div>
                <div class="col-md-3">
                    Filtrar proyectos de impacto
                    <br>
                    <select class="form-control" ng-model="filtro_impacto" ng-change="filtrar_impacto()">
                        <option value="0">Todos</option>
                        <option value="3">Proyectos de impacto</option>
                        <option value="4">Demás prácticas</option>
                    </select>
                </div>
            </div>
                
            <table  object-table
            	data = "practicantes"
            	display = "10"
            	headers = "Código, Nombre, Programa,Modalidad, Estado, Empresa,  Acciones"
            	fields = "getempresa.nit,getempresa.nombre,getmunicipio.getdepartamento.getpais.nombre,getmunicipio.getdepartamento.nombre,getmunicipio.nombre, getempresa.getestadodipro.nombre"
            	sorting = "compound"
            	editable = "false"
            	resize="false"
            	drag-columns="false">
                <tbody>
                    <td>
                        @{{ ::item.codigo }}
                    </td>
                    <td>
                        @{{ ::item.nombre | uppercase }}
                    </td>
                    <td>
                        @{{ ::item.programa | uppercase }}
                    </td>
                    <td>
                        @{{ ::item.modalidad | uppercase }}
                    </td>
                    <td>
                        @{{ ::item.estado | uppercase }}
                    </td>
                    <td>
                        @{{ ::item.empresa | uppercase }}
                    </td>
                    <td class="text-center">
                        <!--<a href="">-->
                        <!--    <span class="glyphicon glyphicon-search pointer" title="Detalles"></span>-->
                        <!--</a>-->
                        <span class="fa fa-binoculars blue" data-id="@{{ ::item.idEst }}" aria-hidden="true" ng-show="item.modalidad=='Vinculación laboral'" title="Ver visitas" data-toggle="modal" data-target="#verVisitas"></span>
                        <span class="fa fa-comments-o blue" aria-hidden="true" title="Ver novedades" data-codigo="@{{item.codigo}}"
                           data-toggle="modal" data-target="#verNovedades" ></span>
                        <span class="fa fa-user-plus blue" aria-hidden="true" data-id="@{{ ::item.idEst }}" ng-show="item.modalidad=='Vinculación laboral' && item.idEstudiante == null" title="Postular estudiante a una oferta" data-toggle="modal" data-target="#postular"></span>
                        <a href="@{{ $owner.raiz }}/admin/verhojadevida/@{{::item.idEst}}" target="_blank">
                            <span class="glyphicon glyphicon-list-alt" title="Ver hoja de vida"></span> 
                        </a>
                        <a class="fa fa-download blue" aria-hidden="true" title="Descargar informe de practicas" ng-show="item.informe != null"
                            href="@{{ $owner.raiz }}/admin/descargarinforme/@{{item.id}}" target="_blank"></a>
                    </td>
                </tbody>
            </table>
            
        </fieldset>
        
        <!-- Modal -->
        <div id="postular" class="modal fade" role="dialog">
            <div class="modal-dialog">
        
                <!-- Modal content-->
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                        <h4 class="modal-title">Postular estudiante</h4>
                    </div>
                    <div class="modal-body">
                        <table  object-table
                        	data = "ofertas"
                        	display = "7"
                        	headers = "Empresa, Cargo, Vacantes, Remuneración, Postular"
                        	fields = "empresa,nombre,vacantes,salario"
                        	sorting = "compound"
                        	editable = "false"
                        	resize="false"
                        	drag-columns="false">
                            <tbody>
                                <td>
                                    @{{ ::item.empresa | uppercase }}
                                </td>
                                <td>
                                    @{{ ::item.nombre | uppercase }}
                                </td>
                                <td>
                                    @{{ ::item.vacantes }}
                                </td>
                                <td>
                                    @{{ ::item.salario | salarioOferta }}
                                </td>
                                <td class="text-center">
                                    <input type="checkbox" ng-click="$owner.postularEst(item.id)" ng-show="item.estado != 'Postulado'">
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
        
        <!-- Modal -->
        <div id="verVisitas" class="modal fade" role="dialog">
            <div class="modal-dialog" style="max-width:1000px">
        
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
                        	headers = "Fecha registro,Fecha, Hora, Tema,Confirmación estudiante, Confirmación jefe inmediato"
                        	fields = "fecha_registro,fecha,hora,tema,firma_estudiante,firma_jefe"
                        	sorting = "compound"
                        	editable = "false"
                        	resize="false"
                        	drag-columns="false">
                            <tbody>
                                <td>
                                    @{{ ::item.fecha_registro | date:'medium' }}
                                </td>
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
                                    @{{ ::item.firma_jefe | estadoVisita }}
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
    <div class="col-md-1"></div>
    
    <!-- Modal -->
    <div id="verNovedades" class="modal fade" role="dialog">
        <div class="modal-dialog" >
    
            <!-- Modal content-->
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title">Novedades</h4>
                </div>
                <div class="modal-body">
                    <div ng-if="novedades.length == 0">
                        <strong>
                            <center>
                                No hay novedades para mostrar
                            </center>
                        </strong>
                    </div>
                    <table  object-table ng-if="novedades.length > 0"
                    	data = "novedades"
                    	display = "5"
                    	headers = "Emisor,Fecha, Asusto, Contenido"
                    	fields = "fecha_registro,fecha,hora,tema,firma_estudiante,firma_jefe"
                    	sorting = "compound"
                    	editable = "false"
                    	resize="false"
                    	drag-columns="false">
                        <tbody>
                            <td>
                                @{{ ::item.getusuario.getuser.nombres }}
                                @{{ ::item.getusuario.getuser.apellidos }}
                            </td>
                            <td>
                                @{{ ::item.fecha | date:'medium'}}
                            </td>
                            <td>
                                @{{ ::item.asunto }}
                            </td>
                            <td>
                                @{{ ::item.contenido}}
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