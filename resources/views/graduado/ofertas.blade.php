@extends('master.master')

@section('title', 'Ofertas')

@section('contenido')

<div class="row" ng-controller="GraduadoOfertasCtrl">

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
                        Salario
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
                    @{{ ::item.getsede.getempresa.nombre | uppercase }}
                </td>
                <td>
                    @{{ ::item.nombre | uppercase }}
                </td>
                <td>
                    @{{ ::item.vacantes }}
                </td>
                <td>
                    @{{ ::item.getsalario.rango }}
                </td>
                <td>

                	@{{ item.getpostulado.getestadoempresa.nombre }}
                    
                </td>
                <td>
                	<span>
                		@{{ item.getpostulado.getestadoestudiante.nombre }}             		
                	</span>
                    
                </td>
                <td class="text-center">
                    <a href="@{{$owner.raiz}}/graduado/postularse/@{{ ::item.id }}" ng-show="item.getpostulado == null">
                        <span class="fa fa-user-plus pointer" aria-hidden="true" title="Postularse"></span>
                    </a>
                    <a href="@{{$owner.raiz}}/graduado/nopostularse/@{{ ::item.id }}" ng-show="item.getpostulado.getestadoempresa.nombre == 'Postulado'">
                        <span class="glyphicon glyphicon-remove" aria-hidden="true" title="Cancelar inscripción"></span>
                    </a>
                    <a type="button" href="@{{$owner.raiz}}/graduado/aceptaroferta/@{{ ::item.id }}" ng-show="item.getpostulado.getestadoempresa.nombre == 'Seleccionado' && item.getpostulado.getestadoestudiante.nombre == 'Esperando respuesta'">
                        <span class="glyphicon glyphicon-ok" aria-hidden="true" title="Aceptar oferta"></span>
                    </a>                    
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
                        
                        <fieldset>
                            <legend> <span>Vacantes: </span> @{{oferta.vacantes}}</legend>
                        </fieldset>
                        
                        <fieldset>
                            <legend> <span>Fecha de cierre: </span> @{{oferta.fechaCierre | date:'dd/MM/yyyy'}} </legend>
                        </fieldset>
                        
                        <div ng-if="oferta.gettipo.nombre == 'Graduados'">
                            <fieldset>
                                <legend> <span>Salario: </span> @{{oferta.getsalario.rango }}</legend>
                            </fieldset>
                            
                            <fieldset>
                                <legend> <span>Tipo de contrato: </span> @{{oferta.getcontrato.nombre}} </legend>
                            </fieldset>
                        </div>

                        <div ng-if="oferta.gettipo.nombre == 'Graduados'">
                            <fieldset ng-if="oferta.getexperiencia != null">
                                <legend> <span>Experiencia laboral: </span> @{{ oferta.getexperiencia.nombre }} </legend>
                            </fieldset>
                            
                            <fieldset ng-if="oferta.herramientasInformaticas != null">
                                <legend> <span>Herramientas informáticas: </span></legend>
                                <p class="margen">
                                    @{{ oferta.herramientasInformaticas }}    
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
                            <ui-select multiple ng-model="oferta.getprogramas" sortable="true" close-on-select="false" ng-disabled="true">
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