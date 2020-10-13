@extends('master.master')

@section('title', 'Ofertas')

@section('contenido')

<div class="row" ng-controller="AdminCrearofertaCtrl" >
    
    <div class="col-md-1"></div>
    <div class="col-md-10" >
        <!--@{{ formulario.programas }}-->
        <form role="form" class="form-horizontal" ng-submit="guardarOferta()">
            @if(isset($id))
            <div ng-init="oferta.id = {{$id}}"/>
            @endif
            
            <div class="form-group" >
                <label class="col-lg-3" >Empresa</label>
                <div class="col-lg-12">
                    <ui-select ng-model="oferta.empresa" ng-disabled="oferta.getestado.nombre == 'Publicada'" ng-change="jefesbyempresa()">
                        <ui-select-match>
                            <span ng-bind="$select.selected.nombre"></span>
                        </ui-select-match>
                        <ui-select-choices repeat="item in (formulario.empresas | filter: $select.search) track by item.id">
                            <span ng-bind="item.nombre"></span>
                        </ui-select-choices>
                    </ui-select>
                    <p class="help-block text-danger">
                        @{{ errores['empresas.id'][0] }}
                    </p>
                </div>
            </div>
            
            @if($usuario->getrol->nombre == 'Administrador Dippro')
            <div class="form-group" >
                <label class="col-lg-3" >Jefe inmediato</label>
                <div class="col-lg-12">
                    <ui-select ng-model="oferta.jefe" ng-disabled="oferta.getestado.nombre == 'Publicada'">
                        <ui-select-match>
                            <span ng-bind="$select.selected.nombre"></span>
                        </ui-select-match>
                        <ui-select-choices repeat="item in (formulario.jefes | filter: $select.search) track by item.id">
                            <span ng-bind="item.nombre"></span>
                        </ui-select-choices>
                    </ui-select>
                    <p class="help-block text-danger">
                        @{{ errores['jefe.id'][0] }}
                    </p>
                </div>
            </div>
            @endif
            
            <div class="form-group">
                <label class="col-lg-4">Nombre del cargo</label>
                <div class="col-lg-12">
                    <input type="text" ng-model="oferta.nombre" ng-disabled="oferta.getestado.nombre == 'Publicada'" class="form-control" placeholder="Nombre del cargo"/>
                    <p class="help-block text-danger">
                        @{{ errores.nombre[0] }}
                    </p>
                </div>
            </div>
            
            <div class="form-group">
                <label class="col-lg-2">Vacantes</label>
                <div class="col-lg-12">
                    <input type="text" ng-model="oferta.vacantes" class="form-control" placeholder="Vacantes"/>
                    <p class="help-block text-danger">
                        @{{ errores.vacantes[0] }}
                    </p>
                </div>
            </div>
            
            @if(false)
            <div class="form-group">
                <label class="col-lg-2">Fecha de cierre</label>
                <div class="col-lg-12">
                    <input type="date" ng-model="oferta.fechacierre" class="form-control" placeholder="Fecha de cierre"/>
                    <p class="help-block text-danger">
                        @{{ errores.fechacierre[0] }}
                    </p>
                </div>
            </div>
            @endif
            
            @if($usuario->getrol->nombre == 'Administrador Dippro')
            <div class="form-group" >
                <label class="col-lg-2">Remuneración</label>
                <div class="col-lg-12">
                    <input type="text" ng-model="oferta.salario" ng-disabled="oferta.getestado.nombre == 'Publicada'" class="form-control" placeholder="Remuneración" ng-if="!oferta.pordefinir" ui-money-mask="0"/>
                    <p class="help-block text-danger">
                        @{{ errores.salario[0] }} 
                    </p>
                </div>
            </div>
            @endif
            
            @if($usuario->getrol->nombre == 'Administrador Egresados')
            <div class="form-group">
                <label class="col-lg-2">Salario</label>
                <div class="col-lg-12">
                    <ui-select ng-model="oferta.salario" ng-disabled="oferta.getestado.nombre == 'Publicada'">
                        <ui-select-match>
                            <span ng-bind="$select.selected.rango"></span>
                        </ui-select-match>
                        <ui-select-choices repeat="item in (formulario.salarios | filter: $select.search) track by item.id">
                            <span ng-bind="item.rango"></span>
                        </ui-select-choices>
                    </ui-select>
                    <p class="help-block text-danger">
                        @{{ errores['salario.id'][0] }}
                    </p>
                </div>
            </div>
            
            
            <div class="form-group" >
                <label class="col-lg-2">Tipo de contrato</label>
                <div class="col-lg-12">
                    <ui-select ng-model="oferta.contrato" ng-disabled="oferta.getestado.nombre == 'Publicada'">
                        <ui-select-match>
                            <span ng-bind="$select.selected.nombre"></span>
                        </ui-select-match>
                        <ui-select-choices repeat="item in (formulario.contratos | filter: $select.search) track by item.id">
                            <span ng-bind="item.nombre"></span>
                        </ui-select-choices>
                    </ui-select>
                    <p class="help-block text-danger">
                        @{{ errores['contrato.id'][0] }}
                    </p>
                </div>
            </div>
            @endif
            @if($usuario->getrol->nombre == 'Administrador Dippro')
            <div class="col-md-3" >
                <div class="form-group">
                    <label class="col-lg-2">Salud: </label>
                    <div class="col-lg-12">
                        <label>Si <input type="checkbox" ng-disabled="oferta.getestado.nombre == 'Publicada'" ng-model="oferta.saluds" ng-change="saluds()" /></label>&nbsp;&nbsp;&nbsp;
                        <label>No <input type="checkbox" ng-disabled="oferta.getestado.nombre == 'Publicada'" ng-model="oferta.saludn" ng-change="saludn()" /></label>
                        <p class="help-block text-danger">
                            @{{ errores.salud[0] }}
                        </p>
                    </div>
                </div>
            </div>
            
            <div class="col-md-9" >
                <div class="form-group">
                    <label class="col-lg-2">ARL: </label>
                    <div class="col-lg-12">
                        <label>Si <input type="checkbox" ng-disabled="oferta.getestado.nombre == 'Publicada'" ng-model="oferta.arls" ng-change="arls()" /></label>&nbsp;&nbsp;&nbsp;
                        <label>No <input type="checkbox" ng-disabled="oferta.getestado.nombre == 'Publicada'" ng-model="oferta.arln" ng-change="arln()" /></label>
                        <p class="help-block">
                            Se sugiere que por norma de ministerio de trabajo marque si
                        </p>
                        <p class="help-block text-danger">
                            @{{ errores.arl[0] }}
                        </p>
                    </div>
                </div>
            </div>
            @endif
            <div class="form-group">
                <label class="col-lg-3" >Programas</label>
                <div class="col-lg-12">
                    <ui-select multiple ng-model="oferta.programas" sortable="true" close-on-select="false" ng-disabled="oferta.getestado.nombre == 'Publicada'" ng-change="buscarEst()">
                        <ui-select-match placeholder="Seleccione los programas">@{{$item.nombre}}</ui-select-match>
                        <ui-select-choices repeat="programa in (formulario.programas | filter: $select.search) track by programa.id">
                            <small>
                                @{{programa.nombre}}
                            </small>
                        </ui-select-choices>
                    </ui-select>
                    <p class="help-block text-danger">
                        @{{ errores.programas[0] }}
                    </p>
                </div>
            </div>
            @if($usuario->getrol->nombre == 'Administrador Egresados')
            <div class="form-group" >
                <label class="col-lg-4">Herramientas informáticas</label>
                <div class="col-lg-12">
                    <textarea class="form-control noresize" ng-disabled="oferta.getestado.nombre == 'Publicada'" rows="5" ng-model="oferta.informaticas"></textarea>
                    <p class="help-block text-danger">
                        @{{ errores.informaticas[0] }}
                    </p>
                </div>
            </div>
            
            <div class="form-group" >
                <label class="col-lg-4">Experiencia laboral</label>
                <div class="col-lg-12">
                    <input type="text" ng-model="oferta.experiencia" ng-disabled="oferta.getestado.nombre == 'Publicada'" class="form-control" placeholder="Experiencia laboral"/>
                    <p class="help-block text-danger">
                        @{{ errores.experiencia[0] }}
                    </p>
                </div>
            </div>
            @endif
            
            <div class="form-group">
                <label class="col-lg-2">Perfil</label>
                <div class="col-lg-12">
                    <textarea class="form-control noresize" ng-disabled="oferta.getestado.nombre == 'Publicada'" rows="5" ng-model="oferta.perfil"></textarea>
                    <p class="help-block text-danger">
                        @{{ errores.perfil[0] }}
                    </p>
                </div>
            </div>
            
            <div class="form-group">
                <label class="col-lg-2">Funciones</label>
                <div class="col-lg-12">
                    <textarea class="form-control noresize" ng-disabled="oferta.getestado.nombre == 'Publicada'" rows="5" ng-model="oferta.funciones"></textarea>
                    <p class="help-block text-danger">
                        @{{ errores.funciones[0] }}
                    </p>
                </div>
            </div>
            <div class="form-group">
                <label class="col-lg-3" >Estudiantes</label>
                <div class="col-lg-12">
                    <ui-select multiple ng-model="oferta.estudiantes" sortable="true" close-on-select="false" ng-disabled="oferta.getestado.nombre == 'Publicada'" >
                        <ui-select-match placeholder="Seleccione los estudiantes">@{{$item.nombre}}</ui-select-match>
                        <ui-select-choices repeat="estudiante in (formulario.practicantes | filter: $select.search) track by estudiante.id">
                            <small>
                                @{{estudiante.nombre}}
                            </small>
                        </ui-select-choices>
                    </ui-select>
                    <p class="help-block text-danger">
                        @{{ errores.practicantes[0] }}
                    </p>
                </div>
            </div>
            <div class="form-group">
                <label class="col-lg-2">Observaciones</label>
                <div class="col-lg-12">
                    <textarea class="form-control noresize" ng-disabled="oferta.getestado.nombre == 'Publicada'" rows="5" ng-model="oferta.observaciones"></textarea>    
                    <p class="help-block text-danger">
                        @{{ errores.observaciones[0] }}
                    </p>
                </div>
            </div>
            
            <br>
            
            <button type="submit" class="btn btn-success">
                Guardar
            </button>
        </form>
        
    </div>
    <div class="col-md-1"></div>
    
</div>

@stop