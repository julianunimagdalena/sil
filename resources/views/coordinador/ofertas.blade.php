@extends('master.master')

@section('title', 'Ofertas')

@section('contenido')

    <style type="text/css">
        
        legend 
        {
            font-size:1em;
        }
        
        legend span
        {
            font-weight:bolder;
        }
        
        .margen
        {
            margin-top: -15px;
        }
        
    </style>

    <div class="row" ng-controller="CdnOfertasCtrl" >
        
        <div class="col-md-1"></div>
        <div class="col-md-10">
            <fieldset>
                <legend style="font-size:1.5em;">Administración de ofertas</legend>
                
                @if(session('success'))
                <div class="alert alert-success alert-dismissable">
                    <button type="button" class="close" data-dismiss="alert">&times;</button>
                    <strong>¡Exito!</strong> {{ session('success') }}
                </div>
                @endif
                
                @if(session('error'))
                <div class="alert alert-danger alert-dismissable">
                    <button type="button" class="close" data-dismiss="alert">&times;</button>
                    <strong>¡Error!</strong> {{ session('error') }}
                </div>
                @endif
                
                @if(session('content'))
                <div class="alert alert-danger alert-dismissable">
                    <button type="button" class="close" data-dismiss="alert">&times;</button>
                    <strong>¡Error!</strong> {{ session('content') }}
                </div>
                @endif
                
                @if($soloDipro)
                <a class="btn btn-primary" href="/adminsil/crearoferta" style="color:white;">
                    Crear oferta
                </a>
                @endif
                
                <table  object-table
                	data = "ofertas"
                	display = "10"
                	headers = "Nombre cargo, Programas, Vacantes, Salario, Fecha cierre, Estado, Acciones"
                	fields = "nombre,programas vacantes,salario,fechaCierre,estado"
                	sorting = "compound"
                	editable = "false"
                	resize="false"
                	drag-columns="false">
                    <thead>
                        <tr>
                            <th ng-click="sortBy('nombre')" ng-class="headerIsSortedClass('Nombre cargo')" class="sortable">
                                Nombre cargo
                            </th>
                            <th ng-click="sortBy('nombre')" ng-class="headerIsSortedClass('Nombre cargo')" class="sortable">
                                Programas
                            </th>
                            <th ng-click="sortBy('nombre')" ng-class="headerIsSortedClass('Nombre cargo')" class="sortable">
                                Vacantes
                            </th>
                            <th ng-click="sortBy('nombre')" ng-class="headerIsSortedClass('Nombre cargo')" class="sortable">
                                
                                @if($soloDipro)
                                    Remuneración
                                @elseif($soloSil)
                                    Salario
                                @endif
                            </th>
                            <th ng-click="sortBy('nombre')" ng-class="headerIsSortedClass('Nombre cargo')" class="sortable">
                                Fecha cierre
                            </th>
                            <th ng-click="sortBy('nombre')" ng-class="headerIsSortedClass('Nombre cargo')" class="sortable">
                                Estado
                            </th>
                            <th ng-click="sortBy('nombre')" ng-class="headerIsSortedClass('Nombre cargo')" class="sortable">
                                Acciones
                            </th>
                        </tr>
                    </thead>
                    <tbody>
                        <td>
                            @{{ ::item.nombre | uppercase }}
                        </td>
                        <td>
                            @{{ ::item.programas }}
                        </td>
                        <td>
                            @{{ ::item.vacantes }}
                        </td>
                        <td>
                            @{{ ::item.salario | salarioOferta}}
                        </td>
                        <td>
                            @{{ ::item.fechaCierre | date:'dd/MM/yyyy' }}
                        </td>
                        <td class="text-center">
                            @{{ ::item.getestado.nombre }}
                        </td>
                        <td class="text-center">
                            <!--<a href="/adminsil/cambiarestadooferta/@{{::item.id}}" >-->
                            <span class="glyphicon glyphicon-pencil blue pointer" title="Cambiar estado" data-id="@{{::item.id}}" data-toggle="modal" data-target="#cambiarestado" ng-show="!item.creada_por_dipro"></span>
                            <!--</a>-->
                            <a href="/adminsil/crearoferta/@{{::item.id}}" ng-show="item.creada_por_dipro">
                                <span class="glyphicon glyphicon-edit" title="Editar oferta"></span>
                            </a>
                            
                            <a>
                                <span class="glyphicon glyphicon-search blue pointer" title="Detalles" data-id="@{{ ::item.id }}" data-toggle="modal" data-target="#detallesOferta"></span>
                            </a>
                            
                        </td>
                    </tbody>
                </table>
                
            </fieldset>
            
            <!--modal-->
            
            <div id="cambiarestado" class="modal fade" role="dialog">
                <div class="modal-dialog">
            
                    <!-- Modal content-->
                    <div class="modal-content">
                        <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal">&times;</button>
                            <h4 class="modal-title">Cambiar estado</h4>
                        </div>
                        <div class="modal-body">
                            <form role="form" class="form-horizontal" ng-submit="cambiarEstado()">
                                
                                <div class="form-group">
                                    <label class="col-lg-2" for="rol">Estados</label>
                                    <div class="col-lg-12">
                                        <ui-select ng-model="oferta.estado">
                                            <ui-select-match>
                                                <span ng-bind="$select.selected.nombre"></span>
                                            </ui-select-match>
                                            <ui-select-choices repeat="item in (estados | filter: $select.search) track by item.id">
                                                <span ng-bind="item.nombre"></span>
                                            </ui-select-choices>
                                        </ui-select>
                                        <p class="help-block text-danger">
                                            @{{ erroresEstado['estado.id'][0] }}
                                        </p>
                                    </div>
                                </div>
                            </form>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-rojo" data-dismiss="modal">Cerrar</button>
                            <button type="submit" class="btn btn-success" ng-click="cambiarEstado()" >Guardar</button>
                        </div>
                    </div>
            
                </div>
            </div>
            
            <!-- Modal -->
            <div id="detallesOferta" class="modal fade" role="dialog">
                <div class="modal-dialog">
            
                    <!-- Modal content-->
                    <div class="modal-content">
                        <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal">&times;</button>
                            <!--<h4 class="modal-title">Detalles de oferta</h4>-->
                            <h4 class="modal-title">@{{oferta.nombre}}</h4>
                        </div>
                        <div class="modal-body">
                            <!---->
                            <fieldset ng-if="{{$soloDipro}}">
                                <legend> <span>Jefe inmediato: </span> @{{oferta.jefe.nombre | uppercase}}</legend>
                            </fieldset>
                            
                            <!--<fieldset>-->
                            <!--    <legend> <span>Nombre del cargo: </span> @{{oferta.nombre}}</legend>-->
                            <!--</fieldset>-->
                            
                            <fieldset>
                                <legend> <span>Vacantes: </span> @{{oferta.vacantes}}</legend>
                            </fieldset>
                            
                            <fieldset>
                                <legend> <span>Fecha de cierre: </span> @{{oferta.fechacierre | date:'dd/MM/yyyy'}} </legend>
                            </fieldset>
                            
                            <fieldset ng-if="{{$soloDipro}}">
                                <legend> <span>Remuneración: </span> @{{oferta.salario | salarioOferta}}</legend>
                            </fieldset>
                            
                            <div ng-if="{{$soloSil}}">
                                <fieldset>
                                    <legend> <span>Salario: </span> @{{oferta.salario.rango }}</legend>
                                </fieldset>
                                
                                <fieldset>
                                    <legend> <span>Tipo de contrato: </span> @{{oferta.contrato.nombre}} </legend>
                                </fieldset>
                            </div>
                            
                            <div ng-if="{{$soloDipro}}">
                                <fieldset>
                                    <legend> 
                                        <span>Salud: </span> 
                                        <label ng-if="oferta.salud">Si </label>
                                        <label ng-if="!oferta.salud">No </label>
                                        &nbsp;&nbsp;&nbsp;&nbsp;
                                        <span>ARL: </span> 
                                        <label ng-if="oferta.arl">Si </label>
                                        <label ng-if="!oferta.arl">No </label>
                                        &nbsp;&nbsp;&nbsp;&nbsp;
                                        <a href="/adminsil/cartaarl/@{{ oferta.id }}" target="_blank" ng-if="!oferta.arl && oferta.carta != null">
                                            Descargar carta de justificaión del no pago de ARL
                                        </a>
                                    </legend>
                                </fieldset>
                            </div>
                            
                            <div ng-if="{{$soloSil}}">
                                <fieldset ng-if="oferta.experiencia != null">
                                    <legend> <span>Experiencia laboral: </span> @{{ oferta.experiencia }} </legend>
                                </fieldset>
                                
                                <fieldset ng-if="oferta.informaticas != null">
                                    <legend> <span>Herramientas informáticas: </span></legend>
                                    <p class="margen">
                                        @{{ oferta.informaticas }}    
                                    </p>
                                </fieldset>
                            </div>
                            
                            <fieldset>
                                <legend> <span>Perfil: </span> </legend>
                                <p class="margen">
                                    @{{ oferta.perfil }}
                                </p>
                            </fieldset>
                            <fieldset ng-if="oferta.observaciones != null">
                                <legend> <span>Observaciones: </span> </legend>
                                <p class="margen">
                                    @{{ oferta.observaciones }}
                                </p>
                            </fieldset>
                            
                            <fieldset>
                                <legend> <span>Programas: </span> </legend>
                                <ui-select multiple ng-model="oferta.programas" sortable="true" close-on-select="false" ng-disabled="true">
                                    <ui-select-match placeholder="Seleccione los programas">@{{$item.nombre}}</ui-select-match>
                                    <ui-select-choices repeat="programa in (formulario.programas | filter: $select.search) track by programa.id">
                                        <small>
                                            @{{programa.nombre}}
                                        </small>
                                    </ui-select-choices>
                                </ui-select>
                            </fieldset>
                            
                            <!---->
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-rojo" data-dismiss="modal">Cerrar</button>
                        </div>
                    </div>
            
                </div>
            </div>
            
        </div>
        <div class="col-md-1"></div>
        
    </div>

@stop