@extends('master.master')

@section('title', 'Legalizar')

@section('contenido')

<div class="row" ng-controller="EstOtrasLegalizarCtrl">
    
    <div class="col-md-2"></div>
    <div class="col-md-8">
        @if(Session('success') != null)
        <div class="alert alert-success alert-dismissable">
            <button type="button" class="close" data-dismiss="alert">&times;</button>
            {{ Session('success') }}
        </div>
        @endif
        @if(Session('error') != null)
        <div class="alert alert-danger alert-dismissable">
            <button type="button" class="close" data-dismiss="alert">&times;</button>
            {{ Session('error') }}
        </div>
        @endif
        <fieldset>
            <legend>
                Legalizar prácticas
                <div class="help-block" style="font-size:0.7em;">(Todos los archivos deben ser formato pdf y tamaño menor o igual a 1MB)</div>
            </legend>
            <form role="form" ng-submit="legalizar()">
                <div class="form-group" ng-if="estudiante.getpracticas[estudiante.getpracticas.length - 1].getmodalidad.nombre == 'Prácticas internacionales' || estudiante.getpracticas[estudiante.getpracticas.length - 1].getmodalidad.nombre == 'Semestre en el exterior'">
                    <label>Ciudad</label>
                    <ui-select ng-model="practica.ciudad">
                        <ui-select-match>
                            <span ng-bind="$select.selected.nombre"></span>
                        </ui-select-match>
                        <ui-select-choices repeat="item in (ciudades | filter: $select.search) track by item.id">
                            <small>
                                @{{item.getdepartamento.getpais.nombre+" - "+item.getdepartamento.nombre+" - "+item.nombre}}
                            </small>
                        </ui-select-choices>
                    </ui-select>
                    <p class="help-block text-danger">
                        @{{ errores['ciudad.id'][0] }}
                    </p>
                </div>
                
                <div class="form-group" ng-if="estudiante.getpracticas[estudiante.getpracticas.length - 1].getmodalidad.nombre == 'Prácticas internacionales' || estudiante.getpracticas[estudiante.getpracticas.length - 1].getmodalidad.nombre == 'Semestre en el exterior'">
                    <label ng-if="estudiante.getpracticas[estudiante.getpracticas.length - 1].getmodalidad.nombre == 'Prácticas internacionales'">
                        Empresa
                    </label>
                    <label ng-if="estudiante.getpracticas[estudiante.getpracticas.length - 1].getmodalidad.nombre == 'Semestre en el exterior'">
                        Universidad
                    </label>
                    <input type="text" class="form-control" ng-model="practica.empresa">
                    <p class="help-block text-danger">
                        @{{ errores.empresa[0] }}
                    </p>
                </div>
                
                <div class="form-group">
                    <label >
                        Carta dirigida a Dippro solicitando la práctica
                    </label>
                    <input type="file" uploader-model="practica.file_carta_solicitud" class="form-control file">
                    <p class="help-block text-danger">
                        @{{ errores['file_carta_solicitud'][0] }}
                    </p>
                    <br>
                </div>
                
                <div class="form-group" ng-if="estudiante.getpracticas[estudiante.getpracticas.length - 1].getmodalidad.nombre == 'Validación'">
                    <label>Certificado Laboral con la fecha de vinculación, tipo de contrato, cargo y funciones detalladas</label>
                    <input type="file" uploader-model="practica.file_certificado_laboral" class="form-control file">
                    <p class="help-block text-danger">
                        @{{ errores['file_certificado_laboral'][0] }}
                    </p>
                    <br>
                </div>
                
                
                <div class="form-group" ng-if="estudiante.getpracticas[estudiante.getpracticas.length - 1].getmodalidad.nombre != 'Práctica social' && estudiante.getpracticas[estudiante.getpracticas.length - 1].getmodalidad.nombre != 'Semestre en el exterior'">
                    <label ng-if="estudiante.getpracticas[estudiante.getpracticas.length - 1].getmodalidad.nombre == 'Validación' || estudiante.getpracticas[estudiante.getpracticas.length - 1].getmodalidad.nombre == 'Prácticas de empresarismo'">
                        Registro de la Cámara de Comercio de la organización (Certificado de Existencia y Representación Legal de la Empresa) Actualizada no mayor a 30 días
                    </label>
                    <label ng-if="estudiante.getpracticas[estudiante.getpracticas.length - 1].getmodalidad.nombre == 'Asesorías pymes'">
                        Cámara de comercio de la empresa
                    </label>
                    <label ng-if="estudiante.getpracticas[estudiante.getpracticas.length - 1].getmodalidad.nombre == 'Prácticas internacionales'">
                        Certificado legal de existencia de la empresa en la cual realizará las prácticas
                    </label>
                    <input type="file" uploader-model="practica.file_existencia_empresa" class="form-control file">
                    <p class="help-block text-danger">
                        @{{ errores['file_existencia_empresa'][0] }}
                    </p>
                    <br>
                </div>
                
                <div class="form-group" ng-if="estudiante.getpracticas[estudiante.getpracticas.length - 1].getmodalidad.nombre == 'Validación' || estudiante.getpracticas[estudiante.getpracticas.length - 1].getmodalidad.nombre == 'Prácticas de empresarismo'">
                    <label>
                        Certificado actualizado de afiliación a la salud, al fondo de pensiones y ARL Emitidos por las entidades Administradoras (no se aceptan planillas de pago)
                    </label>
                    <input type="file" uploader-model="practica.file_afiliacion_ss" class="form-control file">
                    <p class="help-block text-danger">
                        @{{ errores['file_afiliacion_ss'][0] }}
                    </p>
                    <br>
                </div>
                
                
                <div class="form-group" ng-if="estudiante.getpracticas[estudiante.getpracticas.length - 1].getmodalidad.nombre == 'Validación'">
                    <label>
                        Si el contrato es por Prestación de Servicios, anexar copia del mismo
                    </label>
                    <input type="file" uploader-model="practica.file_contrato" class="form-control file">
                    <p class="help-block text-danger">
                        @{{ errores['file_contrato'][0] }}
                    </p>
                    <br>
                </div>
                
                <div class="form-group" ng-if="estudiante.getpracticas[estudiante.getpracticas.length - 1].getmodalidad.nombre == 'Asesorías pymes' || estudiante.getpracticas[estudiante.getpracticas.length - 1].getmodalidad.nombre == 'Prácticas internacionales' || estudiante.getpracticas[estudiante.getpracticas.length - 1].getmodalidad.nombre == 'Semestre en el exterior'">
                    <label ng-if="estudiante.getpracticas[estudiante.getpracticas.length - 1].getmodalidad.nombre == 'Asesorías pymes'">
                        Carta de la empresa donde diga que le entregará toda información que necesite para que pueda realizar a cabalidad las asesorías
                    </label>
                    <label ng-if="estudiante.getpracticas[estudiante.getpracticas.length - 1].getmodalidad.nombre == 'Prácticas internacionales'">
                        Certificación de parte de la empresa donde acepta al estudiante como practicante y relacione las actividades que desempeñará
                    </label>
                    <label ng-if="estudiante.getpracticas[estudiante.getpracticas.length - 1].getmodalidad.nombre == 'Semestre en el exterior'">
                        Certificación de parte de la universidad donde acepta al estudiante
                    </label>
                    <input type="file" uploader-model="practica.file_carta_colaboracion" class="form-control file">
                    <p class="help-block text-danger">
                        @{{ errores['file_carta_colaboracion'][0] }}
                    </p>
                    <br>
                </div>
                
                <div class="form-group" ng-if="estudiante.getpracticas[estudiante.getpracticas.length - 1].getmodalidad.nombre == 'Asesorías pymes'">
                    <label>
                        Cédula del representante legal
                    </label>
                    <input type="file" uploader-model="practica.file_cedula_relegal" class="form-control file">
                    <p class="help-block text-danger">
                        @{{ errores['file_cedula_relegal'][0] }}
                    </p>
                    <br>
                </div>
                
                <div class="form-group" ng-if="estudiante.getpracticas[estudiante.getpracticas.length - 1].getmodalidad.nombre == 'Prácticas internacionales' || estudiante.getpracticas[estudiante.getpracticas.length - 1].getmodalidad.nombre == 'Semestre en el exterior'">
                    <label ng-if="estudiante.getpracticas[estudiante.getpracticas.length - 1].getmodalidad.nombre == 'Prácticas internacionales'">
                        Carta del Director de Programa en la cual conste que tiene conocimiento de la práctica internacional del estudiante
                    </label>
                    <label ng-if="estudiante.getpracticas[estudiante.getpracticas.length - 1].getmodalidad.nombre == 'Semestre en el exterior'">
                        Carta del Director de Programa en la cual conste que tiene conocimiento del semestre en el exterior del estudiante
                    </label>
                    <input type="file" uploader-model="practica.file_carta_director_programa" class="form-control file">
                    <p class="help-block text-danger">
                        @{{ errores['file_carta_director_programa'][0] }}
                    </p>
                    <br>
                </div>
                
                <div class="form-group" ng-if="estudiante.getpracticas[estudiante.getpracticas.length - 1].getmodalidad.nombre == 'Prácticas internacionales' || estudiante.getpracticas[estudiante.getpracticas.length - 1].getmodalidad.nombre == 'Semestre en el exterior'">
                    <label>
                        Formato RI-F01 para control de la movilidad saliente
                    </label>
                    <input type="file" uploader-model="practica.file_formato_movilidad" class="form-control file">
                    <p class="help-block text-danger">
                        @{{ errores['file_formato_movilidad'][0] }}
                    </p>
                    <br>
                </div>
                
                <div class="form-group" ng-if="estudiante.getpracticas[estudiante.getpracticas.length - 1].getmodalidad.nombre == 'Prácticas internacionales' || estudiante.getpracticas[estudiante.getpracticas.length - 1].getmodalidad.nombre == 'Semestre en el exterior'">
                    <label>
                        Pasaporte
                    </label>
                    <input type="file" uploader-model="practica.file_pasaporte" class="form-control file">
                    <p class="help-block text-danger">
                        @{{ errores['file_pasaporte'][0] }}
                    </p>
                    <br>
                </div>
                
                <div class="form-group" ng-if="estudiante.getpracticas[estudiante.getpracticas.length - 1].getmodalidad.nombre == 'Prácticas internacionales' || estudiante.getpracticas[estudiante.getpracticas.length - 1].getmodalidad.nombre == 'Semestre en el exterior'">
                    <label>
                        Visa
                    </label>
                    <input type="file" uploader-model="practica.file_visa" class="form-control file">
                    <p class="help-block text-danger">
                        @{{ errores['file_visa'][0] }}
                    </p>
                    <br>
                </div>
                
                <div class="form-group" ng-if="estudiante.getpracticas[estudiante.getpracticas.length - 1].getmodalidad.nombre == 'Prácticas internacionales' || estudiante.getpracticas[estudiante.getpracticas.length - 1].getmodalidad.nombre == 'Semestre en el exterior'">
                    <label>
                        Cédula
                    </label>
                    <input type="file" uploader-model="practica.file_cedula" class="form-control file">
                    <p class="help-block text-danger">
                        @{{ errores['file_cedula'][0] }}
                    </p>
                    <br>
                </div>
                
                <div class="form-group" ng-if="estudiante.getpracticas[estudiante.getpracticas.length - 1].getmodalidad.nombre == 'Prácticas internacionales' || estudiante.getpracticas[estudiante.getpracticas.length - 1].getmodalidad.nombre == 'Semestre en el exterior'">
                    <label>
                        Carnet
                    </label>
                    <input type="file" uploader-model="practica.file_carnet" class="form-control file">
                    <p class="help-block text-danger">
                        @{{ errores['file_carnet'][0] }}
                    </p>
                    <br>
                </div>
                
                <div class="form-group" ng-if="estudiante.getpracticas[estudiante.getpracticas.length - 1].getmodalidad.nombre == 'Prácticas internacionales' || estudiante.getpracticas[estudiante.getpracticas.length - 1].getmodalidad.nombre == 'Semestre en el exterior'">
                    <label>
                        Extrajuicio firmado por uno de los padres donde certifique y de fe que tiene conocimiento que su hijo estará fuera del país por un tiempo
                    </label>
                    <input type="file" uploader-model="practica.file_padres" class="form-control file">
                    <p class="help-block text-danger">
                        @{{ errores['file_padres'][0] }}
                    </p>
                    <br>
                </div>
                
                <div class="form-group" ng-if="estudiante.getpracticas[estudiante.getpracticas.length - 1].getmodalidad.nombre == 'Prácticas internacionales' || estudiante.getpracticas[estudiante.getpracticas.length - 1].getmodalidad.nombre == 'Semestre en el exterior'">
                    <label ng-if="estudiante.getpracticas[estudiante.getpracticas.length - 1].getmodalidad.nombre == 'Prácticas internacionales'">
                        Extrajuicio firmado por el estudiante donde declare que sólo se dedicará a las actividades de práctica que fueron certificadas por la empresa, durante el tiempo previamente acordado con la Universidad del Magdalena
                    </label>
                    <label ng-if="estudiante.getpracticas[estudiante.getpracticas.length - 1].getmodalidad.nombre == 'Semestre en el exterior'">
                        Extrajuicio firmado por el estudiante donde declare que sólo se dedicará a las actividades relacionadas con su semestre, durante el tiempo previamente acordado con la Universidad del Magdalena
                    </label>
                    <input type="file" uploader-model="practica.file_estudiante" class="form-control file">
                    <p class="help-block text-danger">
                        @{{ errores['file_estudiante'][0] }}
                    </p>
                    <br>
                </div>
                
                <div class="form-group" ng-if="estudiante.getpracticas[estudiante.getpracticas.length - 1].getmodalidad.nombre == 'Prácticas internacionales' || estudiante.getpracticas[estudiante.getpracticas.length - 1].getmodalidad.nombre == 'Semestre en el exterior'">
                    <label>
                        Itinerario de vuelo
                    </label>
                    <input type="file" uploader-model="practica.file_itinerario" class="form-control file">
                    <p class="help-block text-danger">
                        @{{ errores['file_itinerario'][0] }}
                    </p>
                    <br>
                </div>
                
                <div class="form-group" ng-if="estudiante.getpracticas[estudiante.getpracticas.length - 1].getmodalidad.nombre == 'Prácticas internacionales' || estudiante.getpracticas[estudiante.getpracticas.length - 1].getmodalidad.nombre == 'Semestre en el exterior'">
                    <label>
                        Seguro médico internacional – Gestionado por nuestra institución ASSIST CARD - CORIS
                    </label>
                    <input type="file" uploader-model="practica.file_seguro" class="form-control file">
                    <p class="help-block text-danger">
                        @{{ errores['file_seguro'][0] }}
                    </p>
                    <br>
                </div>
                
                
                <button type="submit" value="Submit" class="btn btn-success">
                    Guardar
                </button>
            </form>
        </fieldset>
            
        
    </div>
    <div class="col-md-2"></div>
    
</div>

@stop