@extends('master.master')

@section('title', 'Solicitantes')
@section('contenido')

<div class="row" ng-controller="CdnSolicitantesCtrl">
    
    <div class="col-md-1"></div>
    <div class="col-md-10">
        <fieldset>
            <legend>Solicitudes de prácticas</legend>   
            <button ng-click="aprobarpracticasmultiple()" ng-if="seleccionados.seleccionados.length > 0" class="btn btn-primary" 
                data-toggle="modal" data-target="#cargando" data-backdrop="static" data-keyboard="false">
                Aprobar prácticas
            </button>
            
            <table  object-table
            	data = "solicitantes"
            	display = "10"
            	headers = "Nombres, Apellidos, Código, Programa, Correo,Modalidad, Acciones"
            	fields = "getpersona.nombres,getpersona.apellidos,codigo,getprograma.nombre,getpersona.correo"
            	sorting = "compound"
            	editable = "false"
            	resize="false"
            	drag-columns="false">
                <tbody>
                    <td>
                        @{{ ::item.getpersona.nombres | uppercase }}
                    </td>
                    <td>
                        @{{ ::item.getpersona.apellidos | uppercase }}
                    </td>
                    <td>
                        @{{ ::item.codigo | uppercase }}
                    </td>
                    <td>
                        @{{ ::item.getprograma.nombre | uppercase }}
                    </td>
                    <td>
                        @{{ ::item.getpersona.correo | uppercase }}
                    </td>
                    <td>
                        @{{ ::item.getmodalidades[item.getmodalidades.length - 1].nombre | uppercase }}
                    </td>
                    <td class="text-center">
                        <input type="checkbox" ng-click="$owner.agregarSelccion(item.id)" >
                        <span class="fa fa-times pointer" data-id="@{{ ::item.id }}" data-toggle="modal" data-target="#rechazarPracticas" title="Rechazar solicitud de prácticas"></span>
                    </td>
                </tbody>
            </table>
        </fieldset>
        
        <div id="rechazarPracticas" class="modal fade" role="dialog">
            <div class="modal-dialog">
        
                <!-- Modal content-->
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                        <h4 class="modal-title">Rechazar prácticas</h4>
                    </div>
                    <div class="modal-body">
                        <form role="form" ng-submit="rechazarPracticas()">
                            <div class="form-group">
                                <label for="comment">Motivo</label>
                                <textarea class="form-control" rows="5" id="comment" ng-model="rechazar.motivo"></textarea>
                                <p class="help-block text-danger">
                                    @{{ errores.motivo[0] }}
                                </p>
                            </div>
                            <input type="submit" value="Submit" ng-show="false"/>
                        </form>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-rojo" data-dismiss="modal">Cerrar</button>
                        <button class="btn btn-success" ng-click="rechazarPracticas()" >Enviar</button>
                    </div>
                </div>
        
            </div>
        </div>
    </div>
    <div class="col-md-1"></div>
    
</div>

@stop