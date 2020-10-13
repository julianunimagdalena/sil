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

<div class="row" ng-controller="EstOfertasCtrl">
    
    <div class="col-md-1"></div>
    <div class="col-md-10">
        
        @if(Session('content') != null)
        <div class="alert alert-success alert-dismissable">
            <button type="button" class="close" data-dismiss="alert">&times;</button>
            {{ Session('content') }}
        </div>
        @endif
        
        @if(Session('warning') != null)
        <div class="alert alert-warning alert-dismissable">
            <button type="button" class="close" data-dismiss="alert">&times;</button>
            {{ Session('warning') }}
        </div>
        @endif
        
        @if(Session('error') != null)
        <div class="alert alert-danger alert-dismissable">
            <button type="button" class="close" data-dismiss="alert">&times;</button>
            {{ Session('error') }}
        </div>
        @endif
        
        <table  object-table
        	data = "ofertas"
        	display = "10"
        	headers = "Empresa, Cargo, Vacantes, Salario,Estado empresa, Estado estudiante, Acciones"
        	fields = "empresa,nombre,vacantes,salario, estado"
        	sorting = "compound"
        	editable = "false"
        	resize="false"
        	drag-columns="false">
            <thead>
                <tr>
                    <th ng-click="sortBy('nombre')" ng-class="headerIsSortedClass('Nombre cargo')" class="sortable">
                        Empresa
                    </th>
                    <th ng-click="sortBy('nombre')" ng-class="headerIsSortedClass('Nombre cargo')" class="sortable">
                        Cargo
                    </th>
                    <th ng-click="sortBy('nombre')" ng-class="headerIsSortedClass('Nombre cargo')" class="sortable">
                        Vacantes
                    </th>
                    <th ng-click="sortBy('nombre')" ng-class="headerIsSortedClass('Nombre cargo')" class="sortable">
                        
                        @if($tipo)
                            Remuneración
                        @else
                            Salario
                        @endif
                    </th>
                    <th ng-click="sortBy('nombre')" ng-class="headerIsSortedClass('Nombre cargo')" class="sortable">
                        Estado empresa
                    </th>
                    <th ng-click="sortBy('nombre')" ng-class="headerIsSortedClass('Nombre cargo')" class="sortable">
                        Estado estudiante
                    </th>
                    <th ng-click="sortBy('nombre')" ng-class="headerIsSortedClass('Nombre cargo')" class="sortable">
                        Acciones
                    </th>
                </tr>
            </thead>
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
                <td>
                    @{{ ::item.estado }}
                </td>
                <td>
                    @{{ ::item.estadoEst }}
                </td>
                <td class="text-center">
                    <a href="/estudiante/postularse/@{{ ::item.id }}" ng-show="::item.estado == null">
                        <span class="fa fa-user-plus pointer" aria-hidden="true" title="Postularse"></span>
                    </a>
                    <a href="/estudiante/nopostularse/@{{ ::item.id }}" ng-show="::item.estado == 'Postulado'">
                        <span class="glyphicon glyphicon-remove" aria-hidden="true" title="Cancelar inscripción"></span>
                    </a>
                    <a href="/estudiante/aceptaroferta/@{{ ::item.id }}" ng-show="item.estado == 'Seleccionado' && item.estadoEst == 'Esperando respuesta'">
                        <span class="glyphicon glyphicon-ok" aria-hidden="true" title="Aceptar oferta"></span>
                    </a>
                    <!--<a href="/estudiante/rechazaroferta/@{{ ::item.id }}" ng-show="item.estado == 'Seleccionado' && item.estadoEst == 'Esperando respuesta'">-->
                    <!--    <span class="glyphicon glyphicon-remove" aria-hidden="true" title="No aceptar oferta"></span>-->
                    <!--</a>-->
                    <span data-id="@{{ ::item.id }}"  data-toggle="modal" data-target="#detallesOferta" class="glyphicon glyphicon-search pointer blue" title="Detalles"></span>
                </td>
            </tbody>
        </table>
        
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
                        <fieldset ng-if="estudiante.gettipo.nombre == 'Prácticas' ">
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
                        
                        <fieldset ng-if="estudiante.gettipo.nombre == 'Prácticas'">
                            <legend> <span>Remuneración: </span> @{{oferta.salario | salarioOferta}}</legend>
                        </fieldset>
                        
                        <div ng-if="estudiante.gettipo.nombre == 'Egresado'">
                            <fieldset>
                                <legend> <span>Salario: </span> @{{oferta.salario.rango }}</legend>
                            </fieldset>
                            
                            <fieldset>
                                <legend> <span>Tipo de contrato: </span> @{{oferta.contrato.nombre}} </legend>
                            </fieldset>
                        </div>
                        
                        <div ng-if="estudiante.gettipo.nombre == 'Prácticas'">
                            <fieldset>
                                <legend> 
                                    <span>Salud: </span> 
                                    <label ng-if="oferta.salud">Si </label>
                                    <label ng-if="!oferta.salud">No </label>
                                    &nbsp;&nbsp;&nbsp;&nbsp;
                                    <span>ARL: </span> 
                                    <label ng-if="oferta.arl">Si </label>
                                    <label ng-if="!oferta.arl">No </label>
                                </legend>
                            </fieldset>
                        </div>
                        
                        <div ng-if="estudiante.gettipo.nombre == 'Egresado'">
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