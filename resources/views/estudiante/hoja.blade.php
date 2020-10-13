@extends('master.master')

@section('title', 'Hoja de Vida')

@section('contenido')

<div class="row" ng-controller="EstHojaCtrl">
    
    <div class="col-md-1"></div>
    <div class="col-md-10">
        
        <!--//////////////////////////////////////////////////////////////////////////////////////////////////-->
        
        
        <div ng-cloak>
            <md-content class="bordes">
                <md-tabs md-dynamic-height md-border-bottom>
                    <md-tab label="Datos personales">
                        <md-content class="md-padding">
                            <form role="form" ng-submit="guardarDatosPersonales()">
                                <div class="form-group">
                                    <label class="control-label col-lg-12">Nombre</label>
                                    <label class="col-lg-12 nobolder">
                                        @{{ hoja.estudiante.getpersona.nombres }} @{{ hoja.estudiante.getpersona.apellidos }}    
                                    </label>
                                </div>
                                
                                <div class="form-group">
                                    <label class="control-label col-lg-12">Identificación</label>
                                    <label class="col-lg-12 nobolder">
                                        @{{ hoja.estudiante.getpersona.identificacion | number:0 }}
                                    </label>
                                </div>
                                <!--ng-if="hoja.estudiante.gettipo.nombre == 'Egresado'"-->
                                <div class="form-group" >
                                    <label class="control-label col-lg-12">Programa</label>
                                    <label class="col-lg-12 nobolder">
                                        @{{ hoja.estudiante.getprograma.nombre | uppercase }}
                                    </label>
                                </div>
                                
                                <div class="form-group">
                                    <label for="rol" class="col-lg-12" >País de origen</label>
                                    <div class="col-lg-12 separator">
                                        <ui-select ng-model="hoja.estudiante.getpersona.getciudad.getdepartamento.getpais" ng-change="selectPais()">
                                            <ui-select-match>
                                                <span ng-bind="$select.selected.nombre"></span>
                                            </ui-select-match>
                                            <ui-select-choices repeat="item in (hoja.paises | filter: $select.search) track by item.id">
                                                <span ng-bind="item.nombre"></span>
                                            </ui-select-choices>
                                        </ui-select>
                                        
                                    </div>
                                </div>
                                
                                <div class="form-group">
                                    <label for="rol" class="col-lg-12" >Departamento de origen</label>
                                    <div class="col-lg-12 separator">
                                        <ui-select ng-model="hoja.estudiante.getpersona.getciudad.getdepartamento" ng-change="selectDepartamento()">
                                            <ui-select-match>
                                                <span ng-bind="$select.selected.nombre"></span>
                                            </ui-select-match>
                                            <ui-select-choices repeat="item in (hoja.departamentos | filter: $select.search) track by item.id">
                                                <span ng-bind="item.nombre"></span>
                                            </ui-select-choices>
                                        </ui-select>
                                        
                                    </div>
                                </div>
                                
                                <div class="form-group">
                                    <label for="rol" class="col-lg-12">Ciudad de origen</label>
                                    <div class="col-lg-12 separator">
                                        <ui-select ng-model="hoja.estudiante.getpersona.getciudad">
                                            <ui-select-match>
                                                <span ng-bind="$select.selected.nombre"></span>
                                            </ui-select-match>
                                            <ui-select-choices repeat="item in (hoja.municipios | filter: $select.search) track by item.id">
                                                <span ng-bind="item.nombre"></span>
                                            </ui-select-choices>
                                        </ui-select>
                                        <p class="help-block text-danger">
                                            @{{ errorDatosPersonales['getciudad.id'][0] }}
                                        </p>
                                    </div>
                                </div>
                                
                                <div class="form-group" >
                                    <label class="control-label col-lg-12">Fecha de nacimiento</label>
                                    <div class="col-lg-12 nobolder">
                                        <input type="date" ng-model="hoja.estudiante.getpersona.fechaNacimiento" class="form-control"/>
                                        <!--<md-datepicker ng-model="hoja.estudiante.getpersona.fechaNacimiento" md-placeholder="Fecha de nacimiento"></md-datepicker>-->
                                        <p class="help-block text-danger">
                                            @{{ errorDatosPersonales.fechaNacimiento[0] }}
                                        </p>
                                    </div>
                                </div>
                                
                                <div class="form-group">
                                    <label for="rol" class="col-lg-12" >Género</label>
                                    <div class="col-lg-12 separator">
                                        <ui-select ng-model="hoja.estudiante.getpersona.getgenero">
                                            <ui-select-match>
                                                <span ng-bind="$select.selected.nombre"></span>
                                            </ui-select-match>
                                            <ui-select-choices repeat="item in (hoja.generos | filter: $select.search) track by item.id">
                                                <span ng-bind="item.nombre"></span>
                                            </ui-select-choices>
                                        </ui-select>
                                        <p class="help-block text-danger">
                                            @{{ errorDatosPersonales['getgenero.id'][0] }}
                                        </p>
                                    </div>
                                </div>
                                
                                <div class="form-group">
                                    <label for="rol" class="col-lg-12" >Estado civil</label>
                                    <div class="col-lg-12 separator">
                                        <ui-select ng-model="hoja.estudiante.getpersona.getestadocivil">
                                            <ui-select-match>
                                                <span ng-bind="$select.selected.nombre"></span>
                                            </ui-select-match>
                                            <ui-select-choices repeat="item in (hoja.estadocivil | filter: $select.search) track by item.id">
                                                <span ng-bind="item.nombre"></span>
                                            </ui-select-choices>
                                        </ui-select>
                                        <p class="help-block text-danger">
                                            @{{ errorDatosPersonales['getestadocivil.id'][0] }}
                                        </p>
                                    </div>
                                </div>
                                
                                <div class="form-group" >
                                    <label class="control-label col-lg-12">Dirección</label>
                                    <div class="col-lg-12 separator">
                                        <input type="text" ng-model="hoja.estudiante.getpersona.direccion" class="form-control" ng-value="hoja.estudiante.getpersona.direccion || hoja.estudianteAyre.direccion"/>
                                        <p class="help-block text-danger">
                                            @{{ errorDatosPersonales.direccion[0] }}
                                        </p>
                                    </div>
                                </div>
                                
                                <div class="form-group" >
                                    <label class="control-label col-lg-12">Correo</label>
                                    <div class="col-lg-12 separator">
                                        <input type="text" ng-model="hoja.estudiante.getpersona.correo" class="form-control"/>
                                        <p class="help-block text-danger">
                                            @{{ errorDatosPersonales.correo[0] }}
                                        </p>
                                    </div>
                                </div>
                                
                                <div class="form-group" >
                                    <label class="control-label col-lg-12">Teléfono</label>
                                    <div class="col-lg-12 separator">
                                        <input type="text" ng-model="hoja.estudiante.getpersona.celular" class="form-control" ng-value="hoja.estudiante.getpersona.celular || hoja.estudianteAyre.celular"/>
                                        <p class="help-block text-danger">
                                            @{{ errorDatosPersonales.celular[0] }}
                                        </p>
                                    </div>
                                </div>
                                
                                <div class="col-lg-12">
                                    <button type="submit" class="btn btn-success">
                                        Actualizar datos personales
                                    </button>
                                </div>
                                        
                                
                            </form>
                        </md-content>
                    </md-tab>
                    <md-tab label="Perfil">
                        <md-content class="md-padding">
                            
                            <form role="form" ng-submit="guardarPerfil()">
                                <fieldset>
                                    <legend>Perfil</legend>
                                    <div class="form-group">
                                        <label class="col-lg-2"></label>
                                        <div class="col-lg-12">
                                            <textarea class="form-control noresize" rows="7" ng-model="hoja.perfil"></textarea>
                                            <p class="help-block text-danger">
                                                @{{ errores.perfil[0] }}
                                            </p>
                                        </div>
                                    </div>
                                </fieldset>
                                
                                <fieldset>
                                    <legend>
                                        <h4>
                                            Competencias personales
                                        </h4>
                                    </legend>
                                    <div class="form-group">
                                        <label class="col-lg-3" ></label>
                                        <div class="col-lg-12">
                                            <ui-select multiple ng-model="hoja.getcompetencias" sortable="true" close-on-select="false" >
                                                <ui-select-match placeholder="Seleccione sus competencias">@{{$item.nombre}}</ui-select-match>
                                                <ui-select-choices repeat="competencia in (hoja.competenciasdb | filter: $select.search) track by competencia.id">
                                                    <small>
                                                        @{{competencia.nombre}}
                                                    </small>
                                                </ui-select-choices>
                                            </ui-select>
                                            <br>
                                            <p class="help-block text-danger">
                                                @{{ errores['getcompetencias.id'][0] }}
                                            </p>
                                        </div>
                                    </div>
                                </fieldset>
                                
                                <fieldset>
                                    <legend>
                                        <h4>
                                            <div class="izq">
                                                Estudios realizados
                                            </div>
                                            <div class="der text-right">
                                                <span class="glyphicon glyphicon-plus pointer" title="Agregar estudio" data-toggle="modal" data-target="#agregarEstudio">
                                                </span>
                                            </div>
                                        </h4>
                                    </legend>
                                    
                                    <div class="form-group">
                                        <div class="col-lg-12" ng-repeat="estudio in hoja.getestudios">
                                            <fieldset>
                                                <legend>
                                                    <h5>
                                                        <div class="izq">
                                                            @{{ estudio.titulo || 'lorem ipsum' | uppercase}} 
                                                        </div>
                                                        <div class="der text-right">
                                                            <span class="glyphicon glyphicon-remove pointer" title="Eliminar estudio" ng-click="quitarEstudio(estudio.titulo)">
                                                            </span>
                                                        </div>
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
                                            <div class="izq">
                                                Experiencia Laboral
                                            </div>
                                            <div class="der text-right">
                                                <span class="glyphicon glyphicon-plus pointer" title="Agregar expreriencia" data-toggle="modal" data-target="#agregarExperiencia">
                                                </span>
                                            </div>
                                        </h4>
                                    </legend>
                                    
                                    <div class="form-group">
                                        
                                        <div class="col-lg-12" ng-repeat="exp in hoja.getexperiencias">
                                            <fieldset>
                                                <legend>
                                                    <h5>
                                                        <div class="izq">
                                                            @{{ exp.cargo || 'lorem ipsum' | uppercase}} 
                                                        </div>
                                                        <div class="der text-right">
                                                            <span class="glyphicon glyphicon-remove pointer" title="Eliminar experiencia laboral" ng-click="quitarExperiencia(exp.cargo, exp.empresa)">
                                                            </span>
                                                        </div>
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
                                            <div class="izq">
                                                Idiomas
                                            </div>
                                            <div class="der text-right">
                                                <span class="glyphicon glyphicon-plus pointer" title="Agregar idioma" data-toggle="modal" data-target="#agregarIdioma">
                                                </span>
                                            </div>
                                        </h4>
                                    </legend>
                                    
                                    <div class="form-group">
                                        
                                        <div class="col-lg-12" ng-repeat="idioma in hoja.getidiomas">
                                            
                                            <fieldset>
                                                <legend>
                                                    <h5>
                                                        <div class="izq">
                                                            @{{ idioma.getidioma.nombre }}
                                                        </div>
                                                        <div class="der text-right">
                                                            <span class="glyphicon glyphicon-remove pointer" title="Eliminar idioma" ng-click="quitarIdioma(idioma.getidioma.nombre)">
                                                            </span>
                                                        </div>
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
                                
                                <div class="col-lg-12">
                                    <button type="submit" class="btn btn-success">
                                        Actualizar perfil    
                                    </button>
                                </div>
                            </form>
                            
                        </md-content>
                    </md-tab>
                    <md-tab label="Referencias">
                        <md-content class="md-padding">
                            <form role="form" ng-submit="guardarReferencias()">
                                <fieldset>
                                    <legend>
                                        <h4>
                                            <div class="izq">
                                                <span class="tituloshv">
                                                    Referencias personales
                                                </span>
                                            </div>
                                            <div class="der text-right">
                                                <span class="glyphicon glyphicon-plus pointer" title="Agregar referencia" data-toggle="modal" data-target="#agregarReferenciaP">
                                                </span>
                                            </div>
                                        </h4>
                                    </legend>
                                    
                                    <div class="form-group">
                                        <div class="col-lg-12" ng-repeat="referenciap in hoja.getreferenciasp">
                                            <fieldset>
                                                <legend>
                                                    <h5>
                                                        <div class="izq">
                                                            @{{ referenciap.nombre || 'Lorem ipsum' | uppercase }}
                                                        </div>
                                                        <div class="der text-right">
                                                            <span class="glyphicon glyphicon-remove pointer" title="Eliminar idioma" ng-click="quitarPersonal(referenciap.telefono)">
                                                            </span>
                                                        </div>
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
                                            <div class="izq">
                                                Referencias familiares
                                            </div>
                                            <div class="der text-right">
                                                <span class="glyphicon glyphicon-plus pointer" title="Agregar referencia" data-toggle="modal" data-target="#agregarReferenciaF">
                                                </span>
                                            </div>
                                        </h4>
                                    </legend>
                                    
                                    <div class="form-group">
                                        
                                        <div class="col-lg-12" ng-repeat="referenciaf in hoja.getreferenciasf">
                                            
                                            <fieldset>
                                                <legend>
                                                    <h5>
                                                        <div class="izq">
                                                            @{{ referenciaf.nombre | uppercase }}
                                                        </div>
                                                        <div class="der text-right">
                                                            <span class="glyphicon glyphicon-remove pointer" title="Eliminar idioma" ng-click="quitarFamiliar(referenciaf.telefono)">
                                                            </span>
                                                        </div>
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
                                
                                <div class="col-lg-12">
                                    <button type="submit" class="btn btn-success">
                                        Actualizar Referencias    
                                    </button>
                                </div>
                            </form>
                            
                        </md-content>
                    </md-tab>
                </md-tabs>
            </md-content>
        </div>
        
            
        
        <!--//////////////////////////////////////////////////////////////////////////////////////////////////////-->
        
        <!-- Modal -->
        <div id="agregarEstudio" class="modal fade" role="dialog">
            <div class="modal-dialog">
        
                <!-- Modal content-->
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                        <h4 class="modal-title">Agregar estudio</h4>
                    </div>
                    <div class="modal-body">
                        <form role="form" class="form-horizontal" ng-submit="agreagarEstudio()">
                            <div class="form-group">
                                <label class="col-lg-2">Institución</label>
                                <div class="col-lg-12">
                                    <input type="text" ng-model="estudios.institucion" class="form-control" placeholder="Institución" />
                                    <p class="help-block text-danger">
                                        @{{ error.institucion[0] }}
                                    </p>
                                </div>
                            </div>
                            
                            <div class="form-group">
                                <label class="col-lg-3">Título obtenido</label>
                                <div class="col-lg-12">
                                    <input type="text" ng-model="estudios.titulo" class="form-control" placeholder="Titulo obtenido"/>
                                    <p class="help-block text-danger">
                                        @{{ error.titulo[0] }}
                                    </p>
                                </div>
                            </div>
                            <!---->
                            <div class="form-group">
                            <label for="rol" class="col-lg-12">Ciudad, municipio o provincia</label>
                            <div class="col-lg-12">
                                <ui-select ng-model="estudios.getmunicipio">
                                    <ui-select-match>
                                        <span ng-bind="$select.selected.nombre"></span>
                                    </ui-select-match>
                                    <ui-select-choices repeat="item in (hoja.ciudades | filter: $select.search) track by item.id">
                                        <span ng-bind="item.nombre"></span>
                                    </ui-select-choices>
                                </ui-select>
                                <p class="help-block text-danger">
                                    @{{ error['getmunicipio.id'][0] }}
                                </p>
                            </div>
                            
                            </div>
                            
                            <!---->
                            
                            <div class="form-group">
                            <label for="rol" class="col-lg-5" >Año de grado</label>
                            <div class="col-lg-12">
                                <ui-select ng-model="estudios.anio">
                                    <ui-select-match>
                                        <span ng-bind="$select.selected.nombre"></span>
                                    </ui-select-match>
                                    <ui-select-choices repeat="item in (hoja.anios | filter: $select.search) track by item.id">
                                        <span ng-bind="item.nombre"></span>
                                    </ui-select-choices>
                                </ui-select>
                                <p class="help-block text-danger">
                                    @{{ error.anioGrado[0] }}
                                </p>
                            </div>
                            </div>
                            
                            <div class="form-group">
                                <label class="col-lg-2">Méritos</label>
                                <div class="col-lg-12">
                                    <textarea class="form-control noresize" rows="5" ng-model="estudios.observaciones">
                                    </textarea>
                                </div>
                                <p class="help-block text-danger">
                                    @{{ error.observaciones[0] }}
                                </p>
                            </div>
                            <button type="submit" ng-show="false"></button>
                        </form>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-rojo" data-dismiss="modal" ng-click="cancelarEstudio()">Cerrar</button>
                        <button type="submit" class="btn btn-success" ng-click="agreagarEstudio()" >Guardar</button>
                    </div>
                </div>
        
            </div>
        </div>
        
        <!-- Modal -->
        <div id="agregarExperiencia" class="modal fade" role="dialog">
            <div class="modal-dialog">
        
                <!-- Modal content-->
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                        <h4 class="modal-title">Agregar experiencia laboral</h4>
                    </div>
                    <div class="modal-body">
                        <form role="form" class="form-horizontal" ng-submit="agreagarExperiencia()">
                            <div class="form-group">
                                <label class="col-lg-2">Empresa</label>
                                <div class="col-lg-12">
                                    <input type="text" ng-model="experiencia.empresa" class="form-control" placeholder="Empresa" />
                                    <p class="help-block text-danger">
                                        @{{ errorExp.empresa[0] }}
                                    </p>
                                </div>
                            </div>
                            
                            <div class="form-group">
                                <label class="col-lg-3">Cargo</label>
                                <div class="col-lg-12">
                                    <input type="text" ng-model="experiencia.cargo" class="form-control" placeholder="Cargo"/>
                                    <p class="help-block text-danger">
                                        @{{ errorExp.cargo[0] }}
                                    </p>
                                </div>
                            </div>
                            <!---->
                            <div class="form-group">
                                <label class="col-lg-3">Duración</label>
                                <div class="col-lg-12">
                                    <input type="text" ng-model="experiencia.duracion" class="form-control" placeholder="Duración"/>
                                    <p class="help-block text-danger">
                                        @{{ errorExp.duracion[0] }}
                                    </p>
                                </div>
                            </div>
                            
                            <!---->
                            
                            <div class="form-group">
                                <label class="col-lg-5">Funciones y méritos</label>
                                <div class="col-lg-12">
                                    <textarea class="form-control noresize" rows="5" ng-model="experiencia.funcioneslogros">
                                    </textarea>
                                    <p class="help-block text-danger">
                                        @{{ errorExp.funcioneslogros[0] }}
                                    </p>
                                </div>
                                
                            </div>
                            <button type="submit" ng-show="false"></button>
                        </form>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-rojo" data-dismiss="modal" ng-click="cancelarExperiencia()">Cerrar</button>
                        <button type="submit" class="btn btn-success" ng-click="agreagarExperiencia()" >Guardar</button>
                    </div>
                </div>
        
            </div>
        </div>
        
        <!-- Modal -->
        <div id="agregarIdioma" class="modal fade" role="dialog">
            <div class="modal-dialog">
        
                <!-- Modal content-->
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                        <h4 class="modal-title">Agregar idioma</h4>
                    </div>
                    <div class="modal-body">
                        <form role="form" class="form-horizontal" ng-submit="agreagarIdioma()">
                            <div class="form-group">
                                <label for="rol" class="col-lg-5" >Idioma</label>
                                <div class="col-lg-12">
                                    <ui-select ng-model="nuevoIdioma.getidioma">
                                        <ui-select-match>
                                            <span ng-bind="$select.selected.nombre"></span>
                                        </ui-select-match>
                                        <ui-select-choices repeat="item in (hoja.idiomasdb | filter: $select.search) track by item.id">
                                            <span ng-bind="item.nombre"></span>
                                        </ui-select-choices>
                                    </ui-select>
                                    <p class="help-block text-danger">
                                        @{{ errorIdioma['getidioma.id'][0] }}
                                    </p>
                                </div>
                            </div>
                            
                            <div class="form-group">
                                <label for="rol" class="col-lg-5" ><span class="fa fa-book"></span> Nivel de lectura</label>
                                <div class="col-lg-12">
                                    <ui-select ng-model="nuevoIdioma.getnivellectura">
                                        <ui-select-match>
                                            <span ng-bind="$select.selected.nombre"></span>
                                        </ui-select-match>
                                        <ui-select-choices repeat="item in (hoja.nivelesidiomasdb | filter: $select.search) track by item.id">
                                            <span ng-bind="item.nombre"></span>
                                        </ui-select-choices>
                                    </ui-select>
                                    <p class="help-block text-danger">
                                        @{{ errorIdioma['getnivellectura.id'][0] }}
                                    </p>
                                </div>
                            </div>
                            
                            <div class="form-group">
                                <label for="rol" class="col-lg-5" ><span class="fa fa-pencil-square-o" aria-hidden="true"></span> Nivel de escritura</label>
                                <div class="col-lg-12">
                                    <ui-select ng-model="nuevoIdioma.getnivelescritura">
                                        <ui-select-match>
                                            <span ng-bind="$select.selected.nombre"></span>
                                        </ui-select-match>
                                        <ui-select-choices repeat="item in (hoja.nivelesidiomasdb | filter: $select.search) track by item.id">
                                            <span ng-bind="item.nombre"></span>
                                        </ui-select-choices>
                                    </ui-select>
                                    <p class="help-block text-danger">
                                        @{{ errorIdioma['getnivelescritura.id'][0] }}
                                    </p>
                                </div>
                            </div>
                            
                            <div class="form-group">
                                <label for="rol" class="col-lg-5" ><span class="fa fa-microphone" aria-hidden="true"></span> Nivel de habla</label>
                                <div class="col-lg-12">
                                    <ui-select ng-model="nuevoIdioma.getnivelhabla">
                                        <ui-select-match>
                                            <span ng-bind="$select.selected.nombre"></span>
                                        </ui-select-match>
                                        <ui-select-choices repeat="item in (hoja.nivelesidiomasdb | filter: $select.search) track by item.id">
                                            <span ng-bind="item.nombre"></span>
                                        </ui-select-choices>
                                    </ui-select>
                                    <p class="help-block text-danger">
                                        @{{ errorIdioma['getnivelhabla.id'][0] }}
                                    </p>
                                </div>
                            </div>
                            <button type="submit" ng-show="false"></button>
                        </form>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-rojo" data-dismiss="modal" ng-click="cancelarIdioma()">Cerrar</button>
                        <button type="submit" class="btn btn-success" ng-click="agreagarIdioma()" >Guardar</button>
                    </div>
                </div>
        
            </div>
        </div>
        
        <!-- Modal -->
        <div id="agregarReferenciaP" class="modal fade" role="dialog">
            <div class="modal-dialog">
        
                <!-- Modal content-->
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                        <h4 class="modal-title">Referencia personal</h4>
                    </div>
                    <div class="modal-body">
                        <form role="form" class="form-horizontal" ng-submit="agreagarReferenciaP()">
                            <div class="form-group">
                                <label class="col-lg-2">Nombre</label>
                                <div class="col-lg-12">
                                    <input type="text" ng-model="referenciaPersonal.nombre" class="form-control" placeholder="Nombre" />
                                    <p class="help-block text-danger">
                                        @{{ errorPersonal.nombre[0] }}
                                    </p>
                                </div>
                            </div>
                            
                            <div class="form-group">
                                <label class="col-lg-3">Ocupación</label>
                                <div class="col-lg-12">
                                    <input type="text" ng-model="referenciaPersonal.ocupacion" class="form-control" placeholder="Ocupación"/>
                                    <p class="help-block text-danger">
                                        @{{ errorPersonal.ocupacion[0] }}
                                    </p>
                                </div>
                            </div>
                            
                            <div class="form-group">
                                <label class="col-lg-3">Teléfono</label>
                                <div class="col-lg-12">
                                    <input type="text" ng-model="referenciaPersonal.telefono" class="form-control" placeholder="Teléfono"/>
                                    <p class="help-block text-danger">
                                        @{{ errorPersonal.telefono[0] }}
                                    </p>
                                </div>
                            </div>
                            <button type="submit" ng-show="false"></button>
                        </form>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-rojo" data-dismiss="modal" ng-click="cancelarPersonal()">Cerrar</button>
                        <button type="submit" class="btn btn-success" ng-click="agreagarReferenciaP()" >Guardar</button>
                    </div>
                </div>
        
            </div>
        </div>
        
        
        <!-- Modal -->
        <div id="agregarReferenciaF" class="modal fade" role="dialog">
            <div class="modal-dialog">
        
                <!-- Modal content-->
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                        <h4 class="modal-title">Referencia familiar</h4>
                    </div>
                    <div class="modal-body">
                        <form role="form" class="form-horizontal" ng-submit="agreagarReferenciaF()">
                            <div class="form-group">
                                <label class="col-lg-2">Nombre</label>
                                <div class="col-lg-12">
                                    <input type="text" ng-model="referenciaFamiliar.nombre" class="form-control" placeholder="Nombre" />
                                    <p class="help-block text-danger">
                                        @{{ errorFamiliar.nombre[0] }}
                                    </p>
                                </div>
                            </div>
                            
                            <div class="form-group">
                                <label class="col-lg-3">Ocupación</label>
                                <div class="col-lg-12">
                                    <input type="text" ng-model="referenciaFamiliar.ocupacion" class="form-control" placeholder="Ocupación"/>
                                    <p class="help-block text-danger">
                                        @{{ errorFamiliar.ocupacion[0] }}
                                    </p>
                                </div>
                            </div>
                            
                            <div class="form-group">
                                <label class="col-lg-3">Teléfono</label>
                                <div class="col-lg-12">
                                    <input type="text" ng-model="referenciaFamiliar.telefono" class="form-control" placeholder="Teléfono"/>
                                    <p class="help-block text-danger">
                                        @{{ errorFamiliar.telefono[0] }}
                                    </p>
                                </div>
                            </div>
                            <!---->
                            <div class="form-group">
                                <label for="rol" class="col-lg-12">Parentesco</label>
                                <div class="col-lg-12">
                                    <ui-select ng-model="referenciaFamiliar.getparentesco">
                                        <ui-select-match>
                                            <span ng-bind="$select.selected.nombre"></span>
                                        </ui-select-match>
                                        <ui-select-choices repeat="item in (hoja.parentescosdb | filter: $select.search) track by item.id">
                                            <span ng-bind="item.nombre"></span>
                                        </ui-select-choices>
                                    </ui-select>
                                    <p class="help-block text-danger">
                                        @{{ errorFamiliar['getparentesco.id'][0] }}
                                    </p>
                                </div>
                            
                            </div>
                            <button type="submit" ng-show="false"></button>
                        </form>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-rojo" data-dismiss="modal" ng-click="cancelarFamiliar()">Cerrar</button>
                        <button type="submit" class="btn btn-success" ng-click="agreagarReferenciaF()" >Guardar</button>
                    </div>
                </div>
        
            </div>
        </div>
        
        
        
    </div>
    <div class="col-md-1"></div>
    
</div>

@stop