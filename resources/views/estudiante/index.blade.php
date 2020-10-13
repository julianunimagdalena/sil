@extends('master.master')

@section('title', 'Estudiante')

@section('contenido')

    <div class="row" ng-controller="EstIndexCtrl">
        
        <div class="col-md-1"></div>
        <div class="col-md-10">
            <div class="panel panel-primary">
                <div class="panel-heading">
                    <h3 class="panel-title">@{{ estudiante.getpersona.nombres }} @{{ estudiante.getpersona.apellidos }}</h3>
                </div>
                <div class="panel-body">
                    <div class="col-md-2 text-center">
                        @if(isset($image))
                        <img src="data:image/jpeg;base64,{{base64_encode( $image )}}" class="myImg"/>
                        @else
                        <img src="{{asset('/img/sin_perfil.png')}}" class="myImg"/>
                        @endif
                    </div>
                    <div class="col-md-10">
                        <div class="row separator">
                            <div class="col-md-2">
                                <strong>
                                    Código:
                                </strong>
                            </div>
                            <div class="col-md-10">
                                @{{ estudiante.codigo }}
                            </div>
                        </div>    
                        <div class="row separator">
                            <div class="col-md-2">
                                <strong>
                                    Identificaión:
                                </strong>
                            </div>
                            <div class="col-md-10">
                                @{{ estudiante.getpersona.identificacion }}
                            </div>
                        </div>
                        <div class="row separator">
                            <div class="col-md-2">
                                <strong>
                                    Correo:
                                </strong>
                            </div>
                            <div class="col-md-10">
                                @{{ estudiante.getpersona.correo }}
                            </div>
                        </div>
                        <div class="row separator">
                            <div class="col-md-2">
                                <strong>
                                    Celular:
                                </strong>
                            </div>
                            <div class="col-md-10">
                                @{{ estudiante.getpersona.celular }}
                            </div>
                        </div>
                        <div class="row separator">
                            <div class="col-md-2">
                                <strong>
                                    Estado:
                                </strong>
                            </div>
                            <div class="col-md-10">
                                @{{ estudiante.gettipo.nombre }}
                            </div>
                        </div>
                        
                        <form role="form" ng-submit="solicitarPracticas()" ng-if="estudiante.gettipo.nombre == 'Preprácticas'">
                            <div class="form-group">
                                <!--class="col-lg-12"-->
                                <label for="rol" >Modalidad de prácticas</label>
                                <div >
                                    <ui-select ng-model="modalidad.tipo" >
                                        <ui-select-match>
                                            <span ng-bind="$select.selected.nombre"></span>
                                        </ui-select-match>
                                        <ui-select-choices repeat="item in (modalidades | filter: $select.search) track by item.id">
                                            <span ng-bind="item.nombre"></span>
                                        </ui-select-choices>
                                    </ui-select>
                                    <p class="help-block text-danger">
                                        @{{ errores['tipo.id'][0] }}
                                    </p>
                                </div>
                            </div>
                            <div class="form-group">
                                <!--class="col-lg-12"-->
                                
                                <div >
                                    <input type="checkbox" ng-model="modalidad.simultaneo"/>
                                    <label for="rol" >Solicitar prácticas simultaneas con preprácticas</label>
                                </div>
                            </div>
                            <button class="btn btn-primary" type="submit">
                                Solicitar prácticas
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-1"></div>
        
        
    </div>

@stop