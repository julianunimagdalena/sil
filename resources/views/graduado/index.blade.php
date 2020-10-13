@extends('master._adminLayoutNew')
@section('titulo', 'Tablero de Graduado - ')
@section('controller','ng-controller ="GraduadoOfertasCtrl"')
@section('title')
    <h2>Tablero de Graduado</h2>
@endsection

@section('estilos')

	<style>
		#link-ofertas * {
			color: #fff;
		}

		div.dropdown.no-arrow a {
			margin-right: 10px;
			cursor: pointer;
		}

		span.Seleccionado {
			color: #0288D1 !important;
		}

		span.No.seleccionado, span.no-ok {
			color: #D32F2F !important;
		}

		span.Elegido, span.ok {
			color: #388E3C !important;
		}
	</style>

@endsection

@section('content')
@section('tituloVista', 'OFERTAS LABORALES')
<h3 style="color: #004A87; font-weight: 700">OFERTAS LABORALES</h3>
<br>
<div class="row">
    <div class="col-xl-4 col-md-6 mb-4" ng-if="ofertas.length==0">
        <small class="text-muted">No hay ofertas laborales disponibles en el momento.</small>
    </div>
	<div class="col-xl-4 col-md-6 mb-4" ng-repeat="oferta in ofertas">
		<div class="card border-left-warning shadow h-100 py-2">
			<div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
				<h6 class="m-0 font-weight-bold text-primary">@{{oferta.nombre |uppercase}}</h6>
				<div class="dropdown no-arrow">
					<a class="dropdown-toggle" role="button" id="dropdownMenuLink" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
						<i class="fas fa-briefcase fa-2x text-gray-300"></i>
					</a>
				</div>
			</div>
			<div class="card-body d-flex flex-column justify-content-between">
				<div class="row no-gutters align-items-center">
					<div class="col mr-2">
						<div class="text-xs font-weight-bold mb-1">Empresa: @{{ oferta.oferta_egresados != 1 ? oferta.getsede.getempresa.nombre:oferta.empresa_egresados }}</div>
						<div class="text-xs font-weight-bold mb-1">Vacantes: @{{oferta.vacantes}}</div>
						<div class="text-xs font-weight-bold mb-1">Lugar : @{{ oferta.getmunicipio.getdepartamento.getpais.nombre}} - @{{ oferta.getmunicipio.nombre |uppercase}}</div>
						<div class="text-xs font-weight-bold mb-1">Fecha de cierre: @{{oferta.fechaCierre | date:'dd/MM/yyyy'}}</div>
						<div class="text-xs font-weight-bold mb-1" ng-show="oferta.getpostulado">
							Estado empresa:
							<span class="@{{ oferta.getpostulado.getestadoempresa.nombre }}" style="color: #FF8F00;">
								@{{ oferta.getpostulado.getestadoempresa.nombre }}
							</span>
						</div>
						<div class="text-xs font-weight-bold mb-1" ng-show="oferta.getpostulado">
							Estado estudiante:
							<span class="@{{ oferta.getpostulado.getestadoestudiante.nombre }}" ng-class="{ 'ok': oferta.getpostulado.getestadoestudiante.nombre == 'Aceptó', 'no-ok': oferta.getpostulado.getestadoestudiante.nombre == 'No aceptó' }" style="color: #FF8F00;">
								@{{ oferta.getpostulado.getestadoestudiante.nombre }}
							</span>
						</div>
					</div>
				</div>
				<div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
					<h6 class="m-0 font-weight-bold text-primary">Acciones</h6>
					<div class="dropdown no-arrow">
						<a href="" style="color: #004A87;" ng-click="postular(oferta.id)" ng-show="oferta.oferta_egresados != 1 && oferta.getpostulado == null">
							<span class="fas fa-user-plus pointer" aria-hidden="true" title="Postularse"></span>
						</a>
                        <a href="" style="color: #004A87;" ng-click="postularEgresados(oferta)" ng-show="oferta.oferta_egresados == 1">
                            <span class="fas fa-user-plus pointer" aria-hidden="true" title="Postularse"></span>
                        </a>
						<a href="" class="text-danger" ng-click="despostular(oferta.id)" ng-show="oferta.getpostulado.getestadoempresa.nombre == 'Postulado'">
							<span class="fas fa-ban fa-lg" aria-hidden="true" title="Cancelar postulación"></span>
						</a>
						<a style="color: #13855c;" ng-click="aceptarOferta(oferta.id)" ng-show="oferta.getpostulado.getestadoempresa.nombre == 'Seleccionado' && oferta.getpostulado.getestadoestudiante.nombre == 'Esperando respuesta'">
							<span class="fas fa-check fa-lg" aria-hidden="true" title="Aceptar oferta"></span>
						</a>
						<a class="dropdown-toggle" data-id="@{{oferta.id }}" data-toggle="modal" data-target="#detallesOferta">
							<span class="fas fa-search fa-lg" title="Ver detalles"></span>
						</a>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>

<!-- Modal -->
<div id="detallesOferta" class="modal fade" role="dialog">
    <div class="modal-dialog modal-lg">
    	<div class="modal-content">
    		<div class="modal-body row">
        		<md-content style="margin-top:-15px; border-top-right-radius: 5px; border-top-left-radius: 5px;">
                    <md-tabs md-dynamic-height md-border-bottom>
                    	<md-tab label="Detalle de oferta">
                    		<md-content class="md-padding">
                    			<div class="row">
                    				<div class="col-md-3">
                                        <md-content>
                                            <md-list>
                                                <md-list-item class="md-3-line">
                                                    <div class="md-list-item-text" >
                                                        <h3>Cargo</h3>
                                                        <p style="color:#5a5a5e;">@{{oferta.nombre}}</p>
                                                    </div>
                                                    <md-divider></md-divider>
                                                </md-list-item>
                                            </md-list>
                                        </md-content>
                                    </div>
                        			<div class="col-md-3">
                                        <md-content>
                                            <md-list>
                                                <md-list-item class="md-3-line">
                                                    <div class="md-list-item-text" >
                                                        <h3>Vacantes</h3>
                                                        <p style="color:#5a5a5e;">@{{oferta.vacantes}}</p>
                                                    </div>
                                                    <md-divider></md-divider>
                                                </md-list-item>
                                            </md-list>
                                        </md-content>
                                    </div>
                                    <div class="col-md-3">
                                        <md-content>
                                            <md-list>
                                                <md-list-item class="md-3-line">
                                                    <div class="md-list-item-text" >
                                                        <h3>Fecha de cierre</h3>
                                                        <p style="color:#5a5a5e;">@{{oferta.fechaCierre | date:'dd/MM/yyyy'}}</p>
                                                    </div>
                                                    <md-divider></md-divider>
                                                </md-list-item>
                                            </md-list>
                                        </md-content>
                                    </div>
                                    <div class="col-md-3" ng-if="oferta.gettipo.nombre == 'Graduados'">
                                        <md-content>
                                            <md-list>
                                                <md-list-item class="md-3-line">
                                                    <div class="md-list-item-text" >
                                                        <h3>Salario</h3>
                                                        <p style="color:#5a5a5e;">@{{oferta.getsalario.rango}}</p>
                                                    </div>
                                                    <md-divider></md-divider>
                                                </md-list-item>
                                            </md-list>
                                        </md-content>
                                    </div>
                                    <div class="col-md-4">
                                        <md-content>
                                            <md-list>
                                                <md-list-item class="md-3-line">
                                                    <div class="md-list-item-text" >
                                                        <h3>Lugar</h3>
                                                        <p style="color:#5a5a5e;"> @{{ oferta.getmunicipio.getdepartamento.getpais.nombre}}
                                                                        -
                                                                        @{{ oferta.getmunicipio.nombre}}</p>
                                                    </div>
                                                    <md-divider></md-divider>
                                                </md-list-item>
                                            </md-list>
                                        </md-content>
                                    </div>
                                    <div class="col-md-4" ng-if="oferta.gettipo.nombre == 'Graduados'">
                                        <md-content>
                                            <md-list>
                                                <md-list-item class="md-3-line">
                                                    <div class="md-list-item-text" >
                                                        <h3>Tipo de contrato</h3>
                                                        <p style="color:#5a5a5e;">@{{oferta.getcontrato.nombre}}</p>
                                                    </div>
                                                    <md-divider></md-divider>
                                                </md-list-item>
                                            </md-list>
                                        </md-content>
                                    </div>
                                    <div class="col-md-4" ng-if="oferta.gettipo.nombre == 'Graduados'">
                                        <md-content>
                                            <md-list>
                                                <md-list-item class="md-3-line">
                                                    <div class="md-list-item-text" >
                                                        <h3>Experiencia laboral</h3>
                                                        <p style="color:#5a5a5e;">@{{ oferta.getexperiencia ? oferta.getexperiencia.nombre : oferta.experiencia }}</p>
                                                    </div>
                                                    <md-divider></md-divider>
                                                </md-list-item>
                                            </md-list>
                                        </md-content>
                                    </div>
                                  	<div class="col-md-12" ng-if="oferta.herramientasInformaticas != null && oferta.gettipo.nombre == 'Graduados'">
                                        <md-content>
                                            <md-list>
                                                <md-list-item class="md-3-line">
                                                    <div class="md-list-item-text" >
                                                        <h3>Herramientas informáticas</h3>
                                                        <p style="color:#5a5a5e; text-align: justify;" >@{{oferta.herramientasInformaticas }} </p>
                                                    </div>
                                                    <md-divider></md-divider>
                                                </md-list-item>
                                            </md-list>
                                        </md-content>
                                	</div>
                                     <div class="col-md-12">
                                     	<md-content>
                                            <md-list>
                                                <md-list-item class="md-3-line">
                                                    <div class="md-list-item-text" >
                                                    	<h3>Programas</h3>
                                                    	<br>
                                                        <p>@{{ oferta.programas.join(', ') }}</p>
                                                    </div>
                                                    <md-divider></md-divider>
                                                </md-list-item>
                                            </md-list>
                                        </md-content>
                                    </div>
                                     <div class="col-md-12">
                                        <md-content>
                                            <md-list>
                                                <md-list-item class="md-3-line">
                                                    <div class="md-list-item-text" >
                                                        <h3>Perfil</h3>
                                                        <p style="color:#5a5a5e; text-align: justify;">@{{ oferta.perfil }}</p>
                                                    </div>
                                                    <md-divider></md-divider>
                                                </md-list-item>
                                            </md-list>
                                        </md-content>
                                    </div>
                                     <div class="col-md-12" ng-if="oferta.observaciones != null">
                                        <md-content>
                                            <md-list>
                                                <md-list-item class="md-3-line">
                                                    <div class="md-list-item-text" >
                                                        <h3>Observaciones</h3>
                                                        <p style="color:#5a5a5e; text-align: justify;">@{{oferta.observaciones}}</p>
                                                    </div>
                                                    <md-divider></md-divider>
                                                </md-list-item>
                                            </md-list>
                                        </md-content>
                                    </div>
                                    <div class="col-md-12" ng-if="oferta.funciones != null">
                                        <md-content>
                                            <md-list>
                                                <md-list-item class="md-3-line">
                                                    <div class="md-list-item-text" >
                                                        <h3>Funciones</h3>
                                                        <p style="color:#5a5a5e;">
                                                            @{{ oferta.funciones }}
                                                        </p>
                                                    </div>
                                                </md-list-item>
                                            </md-list>
                                        </md-content>
                                    </div>
                    			</div>

                    		</md-content>
                    	</md-tab>
                    </md-tabs>
                </md-content>
                <div class="modal-footer">
                    <button type="button" class="btn btn-rojo" data-dismiss="modal">Cerrar</button>
                </div>
            </div>

        </div>

    </div>
</div>

@endsection