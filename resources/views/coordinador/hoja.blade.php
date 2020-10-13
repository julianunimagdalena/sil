@extends('master.master')

@section('title', 'Hoja de vida')

@section('contenido')

<style type="text/css">
    div.row
    {
        margin-left:0px;
    }
</style>

<div class="row" ng-controller="CdnHojaCtrl" ng-init="idEstudiante={{$idEstudiante}}">
    <div class="col-md-1"></div>
    <div class="col-md-10">
        <div ng-cloak>
            <md-content class="bordes">
                <md-tabs md-dynamic-height md-border-bottom>
                    <md-tab label="Hoja de vida">
                        <md-content class="md-padding">
                                
                            <fieldset>
                                <legend>Datos personales</legend>
                                <form role="form">
                                    <div class="form-group">
                                        <div class="row">
                                            <label class="control-label col-lg-3">Nombre</label>
                                            <label class="col-lg-9 nobolder">
                                                @{{ estudiante.getpersona.nombres }} @{{ estudiante.getpersona.apellidos }}
                                            </label>    
                                        </div>
                                        
                                    </div>
                                    
                                    <div class="form-group">
                                        <div class="row">
                                            <label class="control-label col-lg-3">Identificación</label>
                                            <label class="col-lg-9 nobolder">
                                                @{{ estudiante.getpersona.identificacion | number:0 }}
                                            </label>
                                        </div>
                                    </div>
                                    <!--ng-if="hoja.estudiante.gettipo.nombre == 'Egresado'"-->
                                    <div class="form-group" >
                                        <div class="row">
                                            <label class="control-label col-lg-3">Programa</label>
                                            <label class="col-lg-9 nobolder">
                                                @{{ estudiante.getprograma.nombre | uppercase }}
                                            </label>
                                        </div>
                                    </div>
                                    
                                    <div class="form-group" >
                                        <div class="row">
                                            <label class="control-label col-lg-3">Ciudad origen</label>
                                            <label class="col-lg-9 nobolder">
                                                @{{ estudiante.getpersona.getciudad.nombre | uppercase }}
                                            </label>
                                        </div>
                                            
                                    </div>
                                    
                                    <div class="form-group">
                                        <div class="row">
                                            <label class="control-label col-lg-3">Fecha de nacimiento</label>
                                            <label class="col-lg-9 nobolder">
                                                @{{ estudiante.getpersona.fechaNacimiento | date:'dd/MM/yyyy'}}
                                            </label>
                                        </div>
                                            
                                    </div>
                                    
                                    <div class="form-group">
                                        <div class="row">
                                            <label for="rol" class="col-lg-3" >Género</label>
                                            <label class="col-lg-9 nobolder">
                                                @{{ estudiante.getpersona.getgenero.nombre | uppercase }}
                                            </label>
                                        </div>
                                    </div>
                                    
                                    <div class="form-group">
                                        <div class="row">
                                            <label for="rol" class="col-lg-3" >Estado civil</label>
                                            <label class="col-lg-9 nobolder">
                                                @{{ estudiante.getpersona.getestadocivil.nombre | uppercase }}
                                            </label>
                                        </div>
                                            
                                    </div>
                                    
                                    <div class="form-group" >
                                        <div class="row">
                                            <label class="control-label col-lg-3">Dirección</label>
                                            <label class="col-lg-9 nobolder">
                                                @{{ estudiante.getpersona.direccion | uppercase }}
                                            </label>
                                        </div>
                                    </div>
                                    
                                    <div class="form-group" >
                                        <div class="row">
                                            <label class="control-label col-lg-3">Correo</label>
                                            <label class="col-lg-9 nobolder">
                                                @{{ estudiante.getpersona.correo | uppercase }}
                                            </label>    
                                        </div>
                                    </div>
                                    
                                    <div class="form-group" >
                                        <div class="row">
                                            <label class="control-label col-lg-3">Teléfono</label>
                                            <label class="col-lg-9 nobolder">
                                                @{{ estudiante.getpersona.celular | uppercase }}
                                            </label>
                                        </div>
                                    </div>
                                </form>
                            </fieldset>
                            <br>
                            <form role="form">
                                <fieldset>
                                    <legend>Perfil</legend>
                                    <div class="form-group">
                                        <label class="col-lg-2"></label>
                                        <div class="col-lg-12">
                                            <p>
                                                @{{ estudiante.gethojadevida[0].perfil }}
                                                <br><br>
                                            </p>
                                        </div>
                                    </div>
                                </fieldset>
                                
                                <fieldset ng-if=" estudiante.gethojadevida[0].getcompetencias.length > 0  ">
                                    <legend>
                                        <h4>
                                            Competencias personales
                                        </h4>
                                    </legend>
                                    <div class="form-group">
                                        <label class="col-lg-3" ></label>
                                        <div class="col-lg-12">
                                            <p class="help-block text-danger">
                                                <ul>
                                                    <li ng-repeat="competencia in estudiante.gethojadevida[0].getcompetencias">
                                                        @{{ competencia.nombre }}
                                                    </li>
                                                </ul>
                                                <br><br>
                                            </p>
                                        </div>
                                    </div>
                                </fieldset>
                                
                                <fieldset>
                                    <legend>
                                        <h4>
                                            Estudios realizados
                                        </h4>
                                    </legend>
                                    
                                    <div class="form-group">
                                        <div class="col-lg-12" ng-repeat="estudio in estudiante.gethojadevida[0].getestudios">
                                            <fieldset>
                                                <legend>
                                                    <h5>
                                                        @{{ estudio.titulo || 'lorem ipsum' | uppercase}}
                                                    </h5>
                                                </legend>
                                                <label class="col-lg-3">Institución</label>
                                                <label class="col-lg-9 nobolder">@{{ estudio.institucion || 'lorem ipsum' | uppercase}}</label>
                                                <label class="col-lg-3">Ciudad o municipio</label>
                                                <label class="col-lg-9 nobolder">@{{ estudio.getmunicipio.nombre || 'lorem ipsum' | uppercase}}</label>
                                                <label class="col-lg-3">Año de grado</label>
                                                <label class="col-lg-9 nobolder">@{{ estudio.anioGrado || 'lorem ipsum' }}</label>
                                                <div class="form-group" ng-if="estudio.observaciones != null">
                                                    <label class="col-lg-2">Méritos</label>
                                                    <div class="col-lg-12">
                                                        <textarea class="form-control noresize" rows="5" ng-disabled="true" ng-model="estudio.observaciones">
                                                        </textarea>
                                                    </div>
                                                </div>
                                            </fieldset>
                                            <br>
                                            
                                        </div>
                                    </div>
                                    
                                </fieldset>
                                
                                <!---->
                                
                                <fieldset>
                                    <legend>
                                        <h4>
                                            Experiencia Laboral
                                        </h4>
                                    </legend>
                                    
                                    <div class="form-group">
                                        
                                        <div class="col-lg-12" ng-repeat="exp in estudiante.gethojadevida[0].getexperiencias">
                                            <fieldset>
                                                <legend>
                                                    <h5>
                                                        @{{ exp.cargo || 'lorem ipsum' | uppercase}} 
                                                    </h5>
                                                </legend>
                                                <label class="col-lg-3">Empresa</label>
                                                <label class="col-lg-9 nobolder">@{{ exp.empresa || 'lorem ipsum' | uppercase}}</label>
                                                <label class="col-lg-3">Duración</label>
                                                <label class="col-lg-9 nobolder">@{{ exp.duracion || 'lorem ipsum' | uppercase}}</label>
                                                <div class="form-group" >
                                                    <label class="col-lg-5">Funciones y meritos</label>
                                                    <div class="col-lg-12">
                                                        <textarea class="form-control noresize" rows="5" ng-disabled="true" ng-model="exp.funcioneslogros">
                                                        </textarea>
                                                    </div>
                                                </div>
                                            </fieldset>
                                            <br>
                                            
                                        </div>
                                    </div>
                                    
                                </fieldset>
                                
                                <fieldset>
                                    <legend>
                                        <h4>
                                            Idiomas
                                        </h4>
                                    </legend>
                                    
                                    <div class="form-group">
                                        
                                        <div class="col-lg-12" ng-repeat="idioma in estudiante.gethojadevida[0].getnivelidiomas">
                                            
                                            <fieldset>
                                                <legend>
                                                    <h5>
                                                        @{{ idioma.getidioma.nombre }}
                                                    </h5>
                                                </legend>
                                                
                                                <div class="col-md-4">
                                                    <span class="fa fa-book" aria-hidden="true" ></span>
                                                    @{{ idioma.getnivellectura.nombre }}
                                                </div>
                                                <div class="col-md-4">
                                                    <span class="fa fa-microphone" aria-hidden="true"></span>
                                                    @{{ idioma.getnivelhabla.nombre }}
                                                </div>
                                                <div class="col-md-4">
                                                    <span class="fa fa-pencil-square-o" aria-hidden="true"></span>
                                                    @{{ idioma.getnivelescritura.nombre }}
                                                </div>
                                            </fieldset>
                                            
                                            
                                            <br>
                                            
                                        </div>
                                    </div>
                                    
                                </fieldset>
                                
                            </form>
                            
                            <form role="form">
                                <fieldset>
                                    <legend>
                                        <h4>
                                            Referencias personales
                                        </h4>
                                    </legend>
                                    
                                    <div class="form-group">
                                        <div class="col-lg-12" ng-repeat="referenciap in estudiante.gethojadevida[0].getreferencias">
                                            <fieldset ng-if="referenciap.getparentesco == null">
                                                <legend>
                                                    <h5>
                                                        @{{ referenciap.nombre || 'Lorem ipsum' | uppercase }}
                                                    </h5>
                                                </legend>
                                                <label class="col-lg-3">Ocupación</label>
                                                <label class="col-lg-9 nobolder">@{{ referenciap.ocupacion || 'lorem ipsum' | uppercase}}</label>
                                                <label class="col-lg-3">Duración</label>
                                                <label class="col-lg-9 nobolder">@{{ referenciap.telefono || 'lorem ipsum' | uppercase}}</label>
                                            </fieldset>
                                            <br>
                                        </div>
                                    </div>
                                    
                                </fieldset>
                                
                                <fieldset>
                                    <legend>
                                        <h4>
                                            Referencias familiares
                                        </h4>
                                    </legend>
                                    
                                    <div class="form-group">
                                        
                                        <div class="col-lg-12" ng-repeat="referenciaf in estudiante.gethojadevida[0].getreferencias">
                                            
                                            <fieldset ng-if="referenciaf.getparentesco != null">
                                                <legend>
                                                    <h5>
                                                        @{{ referenciaf.nombre | uppercase }}
                                                    </h5>
                                                </legend>
                                                
                                                <label class="col-lg-3">Ocupación</label>
                                                <label class="col-lg-9 nobolder">@{{ referenciaf.ocupacion || 'lorem ipsum' | uppercase}}</label>
                                                <label class="col-lg-3">Teléfono</label>
                                                <label class="col-lg-9 nobolder">@{{ referenciaf.telefono || 'lorem ipsum' | uppercase}}</label>
                                                <label class="col-lg-3">Parentesco</label>
                                                <label class="col-lg-9 nobolder">@{{ referenciaf.getparentesco.nombre || 'lorem ipsum' | uppercase}}</label>
                                            </fieldset>
                                            
                                            
                                            <br>
                                            
                                        </div>
                                    </div>
                                    
                                </fieldset>
                                
                            </form>
                                
                        </md-content>
                    </md-tab>
                    
                </md-tabs>
            </md-content>
        </div>
    </div>
    <div class="col-md-1"></div>
        
    
    
</div>

@stop