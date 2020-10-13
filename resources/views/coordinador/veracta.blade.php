@extends('master.master')

@section('title', 'Acta de legalización')


@section('contenido')

<div class="row" ng-controller="CdnVerActasCtrl" ng-init="acta.id = {{ $acta->id }}">
    <div class="col-md-1"></div>
    <div class="col-md-10">
        <fieldset>
            <legend>
                Acta de legalización
            </legend>
            <form role="form" ng-submit="guardarActa()">
                
                <div class="form-group row">
                    <label class="col-md-3">Nombre</label>
                    <div class="col-md-9">
                        <p>
                            {{ $acta->getestudiante->getpersona->nombres }} {{ $acta->getestudiante->getpersona->apellidos }}
                        </p>
                    </div>
                </div>
                <div class="form-group row">
                    <label class="col-md-3">Identificación</label>
                    <div class="col-md-9">
                        <p>
                            {{ $acta->getestudiante->getpersona->identificacion }}
                        </p>
                    </div>
                </div>
                <div class="form-group row">
                    <label class="col-md-3">Número de teléfono</label>
                    <div class="col-md-9">
                        <p>
                            {{ $acta->getestudiante->getpersona->celular }}
                        </p>
                    </div>
                </div>
                <div class="form-group row">
                    <label class="col-md-3">Código del estudiante</label>
                    <div class="col-md-9">
                        <p>
                            {{ $acta->getestudiante->codigo }}
                        </p>
                    </div>
                </div>
                <div class="form-group row">
                    <label class="col-md-3">Periodo académico</label>
                    <div class="col-md-9">
                        <p>
                            {{ $acta->periodo }}
                        </p>
                    </div>
                </div>
                <div class="form-group row">
                    <label class="col-md-3">Programa académico</label>
                    <div class="col-md-9">
                        <p>
                            {{ $acta->getestudiante->getprograma->nombre }}
                        </p>
                    </div>
                </div>
                <div class="form-group row">
                    <label class="col-md-3">Nombre del Tutor Dippro</label>
                    <div class="col-md-9">
                        <ui-select ng-model="acta.tutor">
                            <ui-select-match>
                                <span ng-bind="$select.selected.nombre"></span>
                            </ui-select-match>
                            <ui-select-choices repeat="item in (datos.tutores | filter: $select.search) track by item.id">
                                <span ng-bind="item.nombre"></span>
                            </ui-select-choices>
                        </ui-select>
                        <p class="help-block text-danger">
                            @{{ errores['tutor.id'][0] }}
                        </p>
                    </div>
                </div>
                <div class="form-group row">
                    <label class="col-md-3">Empresa</label>
                    <div class="col-md-9">
                        <p>
                            {{ $acta->postulado->getoferta->getsede->getempresa->nombre }}
                        </p>
                    </div>
                </div>
                <div class="form-group row">
                    <label class="col-md-3">Dirección de la empresa</label>
                    <div class="col-md-9">
                        <p>
                            {{ $acta->postulado->getoferta->getsede->direccion }}
                        </p>
                    </div>
                </div>
                <div class="form-group row">
                    <label class="col-md-3">Ciudad de la empresa</label>
                    <div class="col-md-9">
                        <p>
                            {{ $acta->postulado->getoferta->getsede->getmunicipio->nombre }}
                        </p>
                    </div>
                </div>
                <div class="form-group row">
                    <label class="col-md-3">Jefe inmediato</label>
                    <div class="col-md-9">
                        <p>
                            {{ $acta->postulado->getoferta->getjefe->getuser->nombres }}
                            {{ $acta->postulado->getoferta->getjefe->getuser->apellidos }}
                        </p>
                    </div>
                </div>
                <div class="form-group row">
                    <label class="col-md-3">Teléfono jefe inmediato</label>
                    <div class="col-md-9">
                        <p>
                            {{ $acta->postulado->getoferta->getjefe->getuser->celular }}
                        </p>
                    </div>
                </div>
                <div class="form-group row">
                    <label class="col-md-3">Correo jefe inmediato</label>
                    <div class="col-md-9">
                        <p>
                            {{ $acta->postulado->getoferta->getjefe->getuser->correo }}
                        </p>
                    </div>
                </div>
                <div class="form-group row">
                    <label class="col-md-3">Cargo jefe inmediato</label>
                    <div class="col-md-9">
                        <p>
                            {{ $acta->postulado->getoferta->getjefe->cargo }}
                        </p>
                    </div>
                </div>
                <div class="form-group row">
                    <label class="col-md-3">Funciones a realizar durante la práctica</label>
                    <div class="col-md-9">
                        <label class="nobolder" style="margin-left: -1px;">
                            <p>
                                {{ $acta->postulado->getoferta->funciones }}
                            </p>
                        </label>
                    </div>
                </div>
                <div class="form-group row">
                    <label class="col-md-3">Dependencia donde realiza la práctica</label>
                    <div class="col-md-9">
                        <p>
                            {{ $acta->postulado->getoferta->getjefe->area }}
                        </p>
                    </div>
                </div>
                <div class="form-group row">
                    <label class="col-md-3">Fecha de inicio</label>
                    <div class="col-md-9">
                        <input type="date" ng-model="acta.fecha_inicio" class="form-control" id="fecha_inicio_acta" ng-change="fechaFinActa()"/>
                        <p class="help-block text-danger">
                            @{{ errores.fecha_inicio[0] }}
                        </p>
                    </div>
                </div>
                <br>
                <div class="form-group row">
                    <label class="col-md-3">Fecha de terminación</label>
                    <div class="col-md-9">
                        <input type="date" ng-model="acta.fecha_fin" class="form-control" id="fecha_fin_acta"/>
                        <p class="help-block text-danger">
                            @{{ errores.fecha_fin[0] }}
                        </p>
                    </div>
                </div>
                <br>
                <div class="form-group row">
                    <label class="col-md-3">Horario de práctica</label>
                    <div class="col-md-9">
                        <label class="nobolder" style="margin-left: -1px;">
                            <p>
                                {{ $acta->horario }}
                            </p>
                        </label>
                    </div>
                </div>
                <div class="form-group row">
                    <label class="col-md-3">Remuneración</label>
                    <div class="col-md-9">
                        <p>
                            {{ number_format($acta->postulado->getoferta->salario) }}
                        </p>
                    </div>
                </div>
                <div class="form-group row">
                    <label class="col-md-3">Aseguradora ARL</label>
                    <div class="col-md-9">
                        @if($acta->certificado_arl != null)
                        <p>
                            {{ strtoupper($acta->nombre_arl) }}
                        </p>
                        @else
                            <input type="text" ng-model="acta.nombre_arl" class="form-control" >
                            <p class="help-block text-danger">
                                @{{ errores.nombre_arl[0] }}
                            </p>
                            <br>
                        @endif
                    </div>
                </div>
                <div class="form-group row">
                    <label class="col-md-3">Certificado de ARL</label>
                    <div class="col-md-9">
                        @if($acta->certificado_arl != null)
                        <p>
                            <a href="/cdn/certificadoarl/{{ $acta->id }}" target="_blank">Descargar certificado de ARL</a>
                        </p>
                        @else
                            <input type="file" uploader-model="acta.file" >
                            <p class="help-block text-danger">
                                @{{ errores.file[0] }}
                            </p>
                            <br>
                        @endif
                    </div>
                </div>
                <div class="form-group row">
                    <label class="col-md-3">Certificado de salud</label>
                    <div class="col-md-9">
                        <p>
                            <a href="/cdn/certificadosalud/{{ $acta->id }}" target="_blank">Descargar certificado de salud</a>
                        </p>
                    </div>
                </div>
                <div class="form-group row">
                    <label class="col-md-3">Estado</label>
                    <div class="col-md-9">
                        <ui-select ng-model="acta.estado">
                            <ui-select-match>
                                <span ng-bind="$select.selected.nombre"></span>
                            </ui-select-match>
                            <ui-select-choices repeat="item in (datos.estados | filter: $select.search) track by item.id">
                                <span ng-bind="item.nombre"></span>
                            </ui-select-choices>
                        </ui-select>
                        <p class="help-block text-danger">
                            @{{ errores['estado.id'][0] }}
                            @{{ errores.estado[0] }}
                        </p>
                    </div>
                </div>
                <div class="form-group row" ng-if="acta.estado.nombre != 'Aprobada' && acta.estado.nombre != 'Esperando respuesta'">
                    <label class="col-md-3">Observaciones</label>
                    <div class="col-md-9">
                        <textarea ng-model="acta.observaciones" cols="30" rows="5" placeholder="Observaciones" class="form-control noresize"></textarea>
                        <p class="help-block text-danger">
                            @{{ errores.observaciones[0] }}
                        </p>
                    </div>
                </div>
                <br>
                <div class="checkbox" ng-if="mostrar && acta.estado.nombre == 'Aprobada'">
                    <label>
                        <input type="checkbox" ng-model="acta.aprobacion_dippro" ng-value="true"> 
                        Confirmo que la información que se encuentra en esta acta es verídica.
                        <p class="help-block">Esta confirmación se tiene en cuenta como su firma.</p>
                        <p class="help-block text-danger">
                            @{{ errores.aprobacion_dippro[0] }}
                        </p>
                    </label>
                </div>
                <button class="btn btn-success" type="submit"
                        data-toggle="modal" data-target="#cargando" data-backdrop="static" data-keyboard="false">
                    Guardar
                </button>
            </form>
            
        </fieldset>
    </div>
    <div class="col-md-1"></div>
</div>

@stop