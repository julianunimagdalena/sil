@extends('master._adminLayoutNew')
@section('titulo', 'Tablero de novedad')
@section('controller','ng-controller ="NovIndexCtrl"')
@section('title')
    <h2>Tablero de novedad</h2>
@endsection

@section('estilos')

    <style type="text/css">
        #link-novedad * {
            color: #fff;
        }

        div.novedades-side-left {
            border-right: 1px solid #ddd;
        }

        .bandeja>div {
            border-bottom: 1px solid #ddd;
            padding: 7px;
            cursor: pointer
        }

        .bandeja>div:last-child {
            border-bottom: none;
        }

        .bandeja>div.no-leidas {
            font-weight: 650;
            background-color: #E57373;
        }

        #nuevo-mensaje {
            position: fixed;
            right: 20px;
            bottom: 70px;
            z-index: 99;
        }

        #nuevo-mensaje button {
            border-radius: 70px;
            padding: 25px;
        }

        #nuevo-mensaje i {
            position: absolute;
            left: 17px;
            top: 18px;
        }
    </style>
  
@endsection

@section('content')
<h3 style="color: #004A87; font-weight: 700">NOVEDADES</h3>
@section('tituloVista', 'NOVEDADES')

@if(session('rol')->nombre!='Administrador Egresados')
    <div id="nuevo-mensaje" ng-click="redactarNovedad()">
        <button class="btn btn-primary" data-toggle="tooltip" data-placement="left" title="Nuevo mensaje"><i class="fas fa-lg fa-plus"></i></button>
    </div>
@endif

<div class="row">
    <div class="col-md-12">
        {{-- <div id="novedades-new" class="panel panel-primary" ng-style="estilo">
            <div class="panel-heading" >
                
                <button type="button" class="minimizar" ng-click="minimizarNuevaNovedad()" >_</button>
                <button type="button" class="maximizar" ng-click="maximizarNuevaNovedad()" ></button>
                <button type="button" class="close" data-dismiss="alert" ng-click="cerrarNovedad()" style="position:relative;right:-47px;">&times;</button>
                <h3 class="panel-title">
                    Nueva novedad
                </h3>
            </div>
            <div class="panel-body">
                <form role="form" ng-submit="enviarNovedad()">
                    @if(session('rol')->nombre != 'Graduado' && session('rol')->nombre != 'Empresa')
                        <div class="form-group" ng-if="estudiantes.length > 0 && showDestinatarios">
                            <ui-select ng-model="newNovedad.estudiante" ng-change="usuariosSegunEstudiante()">
                                <ui-select-match>
                                    <span ng-bind="$select.selected.nombre"></span>
                                </ui-select-match>
                                <ui-select-choices repeat="usuario in (estudiantes | filter: $select.search) track by usuario.id">
                                    <span ng-bind="usuario.nombre"></span>
                                </ui-select-choices>
                            </ui-select>
                        </div>
                        <div class="separador" ng-if="estudiantes.length > 0 && showDestinatarios"></div>
                        <div class="form-group" ng-if="showDestinatarios">
                            <ui-select multiple ng-model="newNovedad.destinatarios" sortable="true" close-on-select="false">
                                <ui-select-match placeholder="Destinatarios">@{{$item.nombre}}</ui-select-match>
                                <ui-select-choices repeat="usuario in (usuarios | filter: $select.search) track by usuario.id">
                                    <small>
                                        @{{usuario.nombre}}
                                    </small>
                                </ui-select-choices>
                            </ui-select>
                        </div>
                        <div class="separador" ng-if="showDestinatarios"></div>
                    @endif
                    <div class="form-group">
                        <input type="text" class="form-control" ng-model="newNovedad.asunto" placeholder="Asunto">
                    </div>
                    <div class="separador"></div>
                    <div class="form-group">
                        <textarea class="form-control noresize" rows="9" ng-model="newNovedad.contenido"></textarea>
                    </div>
                    <div class="separador"></div>
                    <button class="btn btn-success"
                            data-toggle="modal" data-target="#cargando" data-backdrop="static" data-keyboard="false">
                        Enviar
                    </button>
                </form>
            </div>
            
        </div> --}}
        
        <div ng-cloak>
            <md-content class="bordes">
                <md-tabs md-dynamic-height md-border-bottom>
                    <md-tab label="Recibidas">
                        <md-content class="md-padding novedades-fondo">
                            <div class="row">
                                <div class="col-md-4 novedades-side-left">
                                    <div class="row bandeja">
                                        <div ng-repeat="li in novedades" ng-class="li.class" class="col-md-12 vertical-center-content">
                                            <div class="col-md-1 mi-columna">
                                                <!-- <input type="checkbox" class="novedades-checkbox" ng-click="seleccionarNovedad(li.id)"/> -->
                                            </div>
                                            <div class="col-md-10 mi-columna" ng-click="leerNoverdad(li.id)">
                                                <div class="medium-text">                                                    
                                                    @{{ li.asunto}}
                                                </div>
                                                {{-- @{{ li.contenido }}   --}}
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-8">
                                    <div class="col-md-12 medium-text novedades-barra padding">
                                        <div ng-if="novedad.recibida" ng-click="responderNovedad()" style="background-color: #004a87; color: white; cursor: pointer; padding: 7px; margin-bottom: 10px;">
                                            <i class="fas fa-share"></i> Responder
                                        </div>
                                    </div>
                                    <div class="col-md-12">
                                        <div ng-if="novedad.contenido == null">
                                            <div class="big-text novedades-empty text-center">
                                                <label>
                                                    <em>
                                                        Seleccione un mensaje para ver los detalles.
                                                    </em>
                                                    <center >
                                                        <div class="col-md-12 renglones"></div>
                                                        <div class="col-md-12 renglones"></div>
                                                        <div class="col-md-12 renglones"></div>
                                                        <div class="col-md-12 renglones"></div>
                                                    </center>
                                                </label>
                                            </div>
                                        </div>
                                        <div ng-if="novedad.contenido != null">
                                            <span><strong>Fecha:</strong></span> @{{ novedad.fecha }}
                                            <div class="text-center">
                                                <strong class="big-text">
                                                    @{{ novedad.asunto }}
                                                </strong>
                                                <br>
                                                De: @{{ novedad.sender }}
                                            </div>
                                            <br>
                                            <h4><strong>Datos de contacto</strong></h4>
                                            <p class="text-justify">
                                                <strong>Correo electronico: </strong>
                                                @{{novedad.correo}}
                                            </p>
                                            <p class="text-justify">
                                                <strong>Telefono celular: </strong>
                                                @{{novedad.celular}}
                                            </p>
                                            <hr>
                                            <p class="text-justify">
                                                @{{ novedad.contenido }}
                                            </p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </md-content>
                    </md-tab>
                    {{-- @if(session('rol')->nombre!='Administrador Egresados') --}}
                    <md-tab label="Enviadas">
                        <md-content class="md-padding">
                            <div class="row">
                                <div class="col-md-4 novedades-side-left">
                                    <div class="row bandeja">
                                        <div ng-repeat="li in enviadas" ng-class="li.class" class="col-md-12 vertical-center-content">
                                            <div class="col-md-1 mi-columna">
                                                <!-- <input type="checkbox" class="novedades-checkbox" ng-click="seleccionarNovedad(li.id)"/> -->
                                            </div>
                                            <div class="col-md-10 mi-columna" ng-click="leerNoverdad(li.id)">
                                                <div class="medium-text">
                                                    @{{ li.asunto }}                                                    
                                                </div>
                                                {{-- @{{ li.contenido }}     --}}
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-8">
                                    <div class="col-md-12 medium-text novedades-barra padding">
                                    </div>
                                    <div class="col-md-12">
                                        <div ng-if="novedad.contenido == null">
                                            <div class="big-text novedades-empty text-center">
                                                <label>
                                                    <em>
                                                        Seleccione un mensaje para ver los detalles.
                                                    </em>
                                                    <center >
                                                        <div class="col-md-12 renglones"></div>
                                                        <div class="col-md-12 renglones"></div>
                                                        <div class="col-md-12 renglones"></div>
                                                        <div class="col-md-12 renglones"></div>
                                                    </center>
                                                </label>
                                            </div>
                                        </div>
                                        <div ng-if="novedad.contenido != null">
                                            <span><strong>Fecha:</strong></span> @{{ novedad.fecha }}
                                            <div class="text-center">
                                                <strong class="big-text">
                                                    @{{ novedad.asunto }}
                                                </strong>
                                            </div>
                                            <p class="text-justify">
                                                Para: 
                                                <span ng-repeat="usuario in novedad.getusuarios">
                                                    @{{ usuario.getuser.nombres }}
                                                    @{{ usuario.getuser.apellidos }};
                                                </span>
                                                <br>
                                                <br>
                                                @{{ novedad.contenido }}
                                            </p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </md-content>
                    </md-tab>
                    {{-- @endif --}}
                </md-tabs>
            </md-content>
        </div>
    </div>
</div>

{{-- MODAL NUEVO MENSAJE --}}
<div class="modal fade" id="nuevoMensajeModal" tabindex="-1" role="dialog" aria-labelledby="nuevoMensajeModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="nuevoMensajeModalLabel">Nuevo mensaje</h5>
                <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
            </div>
            <div class="modal-body">
                <form>
                    <div class="form-group">
                        <input type="text" class="form-control" ng-model="newNovedad.asunto" placeholder="Asunto">
                    </div>
                    <div class="form-group">
                        <textarea class="form-control noresize" rows="5" placeholder="Contenido" ng-model="newNovedad.contenido"></textarea>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button class="btn btn-secondary" type="button" data-dismiss="modal">Cancelar</button>
                <button class="btn btn-primary" ng-click="enviarNovedad()">Enviar</button>
            </div>
        </div>
    </div>
</div>

@endsection