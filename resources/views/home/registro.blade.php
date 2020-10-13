@extends('master.masterPrincipal')
@section('title', 'Inicio')
@section('controller','ng-controller="registroCtrl"')
@section('contenido')
        <style>
            .ui-select-match-text{
              width: 100%;
              overflow: hidden;
              text-overflow: ellipsis;
              padding-right: 40px;
            }
            .ui-select-toggle > .btn.btn-link {
              margin-right: 10px;
              top: 6px;
              position: absolute;
              right: 10px;
            }
            .text-danger {
                color: red;
            }
        </style>

        
        <div class="col-md-12" style="margin-top: 15px; margin-bottom: 15px;">
            <fieldset>
                <legend>Registro de usuarios</legend>
                <form role="form" ng-submit="registrarUsuario()">
                    <div class="col-sm-4"></div>
                    <div class="form-group col-sm-4">
                        <label for="rol" >Rol</label>
                        <ui-select ng-model="usuario.rol" id="rol" ng-change="registrarEmpresa()">
                            <ui-select-match placeholder="Seleccionar">
                                <span ng-bind="$select.selected.nombre"></span>
                            </ui-select-match>
                            <ui-select-choices repeat="rol in (datos.roles | filter: $select.search) track by rol.id">
                                <span ng-bind="rol.nombre"></span>
                            </ui-select-choices>
                        </ui-select>
                        <p class="help-block text-danger">
                            @{{ errores['rol.id'][0] }}
                        </p>
                    </div>
                    <div class="col-sm-4"></div>
                    <br><br>

                    <div ng-show="usuario.rol.nombre == 'Graduado'">
                        <br><br>
                        <legend>Datos del Graduado</legend>
                        <div class="col-sm-6">
                            <div class="form-group">
                            <label for="rol" >Tipo de documento</label>
                            <ui-select ng-model="usuario.tipodoc">
                                <ui-select-match placeholder="Seleccionar">
                                    <span ng-bind="$select.selected.nombre"></span>
                                </ui-select-match>
                                <ui-select-choices repeat="item in (datos.tipodocs | filter: $select.search) track by item.id">
                                    <span ng-bind="item.nombre"></span>
                                </ui-select-choices>
                            </ui-select>
                            <p class="help-block text-danger">
                                @{{ errores['tipodoc.id'][0] }}
                            </p>
                        </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="form-group">
                            <label for="codigo" >Identificación</label>
                            <input type="text" ng-model="usuario.identificacion" class="form-control"/>
                            <p class="help-block text-danger">
                                @{{ errores.identificacion[0] }}
                            </p>
                        </div>   
                        </div>
                        
                    </div>
                    
                    <div ng-show="usuario.rol.nombre == 'Estudiante'">
                        
                        <div class="form-group">
                            <label for="rol" >Étapa estudiante</label>
                            <ui-select ng-model="usuario.tipoEstudiante" >
                                <ui-select-match>
                                    <span ng-bind="$select.selected.nombre"></span>
                                </ui-select-match>
                                <ui-select-choices repeat="item in (datos.tipoEstudiantes | filter: $select.search) track by item.id">
                                    <span ng-bind="item.nombre"></span>
                                </ui-select-choices>
                            </ui-select>
                            <p class="help-block text-danger">
                                @{{ errores['tipoEstudiante.id'][0] }}
                            </p>
                        </div>
                        
                        <div class="form-group" ng-if="usuario.tipoEstudiante.nombre == 'Prácticas' || usuario.tipoEstudiante.nombre == 'Prácticas y preprácticas'">
                            <!--class="col-lg-12"-->
                            <label for="rol" >Modalidad de prácticas</label>
                            <div >
                                <ui-select ng-model="usuario.modalidad" >
                                    <ui-select-match>
                                        <span ng-bind="$select.selected.nombre"></span>
                                    </ui-select-match>
                                    <ui-select-choices repeat="item in (modalidades | filter: $select.search) track by item.id">
                                        <span ng-bind="item.nombre"></span>
                                    </ui-select-choices>
                                </ui-select>
                                <p class="help-block text-danger">
                                    @{{ errores['modalidad.id'][0] }}
                                </p>
                            </div>
                        </div>
                        
                        <div class="form-group">
                            <label for="codigo" >Código</label>
                            <input type="text" ng-model="usuario.codigo" class="form-control"/>
                            <p class="help-block text-danger">
                                @{{ errores.codigo[0] }}
                            </p>
                        </div>

                        <div class="form-group">
                            <label for="codigo" >Contraseña del módulo estudiante de admisiones</label>
                            <input type="password" ng-model="usuario.password" class="form-control"/>
                            <p class="help-block text-danger">
                                @{{ errores.password[0] }}
                            </p>
                        </div>
                    </div>
                    
                    <div ng-show="usuario.rol.nombre == 'Empresa'">
                        <br><br>
                        <legend>Datos de la empresa</legend>
                        
                        <div class="row">
                            <div class="form-group col-sm-4">
                                <label for="rol" >Tipo de nit</label>
                                <ui-select ng-model="usuario.tipoNit" id="rol">
                                    <ui-select-match placeholder="Seleccionar">
                                        <span ng-bind="$select.selected.nombre"></span>
                                    </ui-select-match>
                                    <ui-select-choices repeat="nit in (datos.tipoNit | filter: $select.search) track by nit.id">
                                        <span ng-bind="nit.nombre"></span>
                                    </ui-select-choices>
                                </ui-select>
                                <p class="help-block text-danger">
                                    @{{ errores['tipoNit.id'][0] }}
                                </p>
                            </div>
                            
                            <div class="form-group col-sm-4">
                                <label for="nit">Nit</label>
                                <input type="text" ng-model="usuario.nit" class="form-control"/>
                                <p class="help-block text-danger">
                                    @{{ errores.nit[0] }}
                                </p>
                            </div>

                            <div class="form-group col-sm-4">
                                <label for="empresa" >Nombre</label>
                                <input type="text" ng-model="usuario.empresa" class="form-control"/>
                                <p class="help-block text-danger">
                                    @{{ errores.empresa[0] }}
                                </p>
                            </div>
                            
                            <div class="form-group col-sm-6">
                                <label for="rol" >Tipo de empleador</label>
                                <ui-select ng-model="usuario.tipoEmpleador" id="rol">
                                    <ui-select-match placeholder="Seleccionar">
                                        <span ng-bind="$select.selected.nombre"></span>
                                    </ui-select-match>
                                    <ui-select-choices repeat="item in (datos.tipoEmpleador | filter: $select.search) track by item.id">
                                        <span ng-bind="item.nombre"></span>
                                    </ui-select-choices>
                                </ui-select>
                                <p class="help-block text-danger">
                                    @{{ errores['tipoEmpleador.id'][0] }}
                                </p>
                            </div>
                            
                            <div class="form-group col-sm-6">
                                <label for="rol" >Actividad económica</label>
                                <ui-select ng-model="usuario.actividad" id="rol">
                                    <ui-select-match placeholder="Seleccionar">
                                        <span ng-bind="$select.selected.nombre"></span>
                                    </ui-select-match>
                                    <ui-select-choices repeat="item in (datos.actividades | filter: $select.search) track by item.id">
                                        <span ng-bind="item.nombre"></span>
                                    </ui-select-choices>
                                </ui-select>
                                <p class="help-block text-danger">
                                    @{{ errores['actividad.id'][0] }}
                                </p>
                            </div>
                            
                            <div class="form-group col-sm-6">
                            <label for="rol" >Página web</label>
                                <input type="text" ng-model="usuario.pagina" class="form-control"/>
                                <p class="help-block text-danger">
                                    @{{ errores.pagina[0] }}
                                </p>
                            </div>

                            <!-- <div class="form-group col-sm-6">
                                <div class="input-group input-file" name="Fichier1">
                                    <input type="text" class="form-control" placeholder='Choose a file...' />           
                                    <span class="input-group-btn">
                                        <button class="btn btn-default btn-choose" type="button">Choose</button>
                                    </span>


                                </div>
                            </div>  -->

                            <div class="form-group col-sm-6">
                                <label>Adjuntar el nit</label>
                                <div class="col-sm-12" style="padding: 0;">
                                    <div class="col-sm-8" style="padding: 0;">
                                        <input id="mArchivo" style="height: 35.5px;" type="text" class="form-control" onkeypress="event.preventDefault();" disabled>
                                    </div>
                                    <button type="button" class="btn btn-light col-sm-4" ng-click="elegirArchivo()">Adjuntar archivo</button>
                                </div>
                                <input id="archivo" style="display: none;" type="file" uploader-model="usuario.file_nit" onchange="angular.element(this).scope().mostrarNombre()">
                                <p class="help-block">
                                    Este archivo debe pesar máximo 1MB y debe ser formato PDF
                                </p>
                                <p class="help-block text-danger">
                                    @{{ errores.file_nit[0] }}
                                </p>
                            </div>
                        </div>
                        <br>
                        <legend>Datos de la sede principal</legend>
                        <div class="row">
                            <div class="form-group col-sm-4">
                            <label for="rol" >País</label>
                                <ui-select ng-model="usuario.pais" ng-change="selectPais()">
                                    <ui-select-match placeholder="Seleccionar">
                                        <span ng-bind="$select.selected.nombre"></span>
                                    </ui-select-match>
                                    <ui-select-choices repeat="pais in (datos.paises | filter: $select.search) track by pais.id">
                                        <span ng-bind="pais.nombre"></span>
                                    </ui-select-choices>
                                </ui-select>
                                <p class="help-block text-danger">
                                    @{{ errores.pais[0] }}
                                </p>
                            </div>
                            
                            <div class="form-group col-sm-4">
                            <label for="rol" >Departamento</label>
                                <ui-select ng-model="usuario.departamento" ng-change="selectDepartamento()">
                                    <ui-select-match placeholder="Seleccionar">
                                        <span ng-bind="$select.selected.nombre"></span>
                                    </ui-select-match>
                                    <ui-select-choices repeat="dpto in (datos.departamentos | filter: $select.search) track by dpto.id">
                                        <span ng-bind="dpto.nombre"></span>
                                    </ui-select-choices>
                                </ui-select>
                                <p class="help-block text-danger">
                                    @{{ errores.departamento[0] }}
                                </p>
                            </div>
                            
                            <div class="form-group col-sm-4">
                            <label for="rol" >Municipio</label>
                                <ui-select ng-model="usuario.municipio" >
                                    <ui-select-match placeholder="Seleccionar">
                                        <span ng-bind="$select.selected.nombre"></span>
                                    </ui-select-match>
                                    <ui-select-choices repeat="item in (datos.municipios | filter: $select.search) track by item.id">
                                        <span ng-bind="item.nombre"></span>
                                    </ui-select-choices>
                                </ui-select>
                                <p class="help-block text-danger">
                                    @{{ errores['municipio.id'][0] }}
                                </p>
                            </div>
                            
                            <div class="form-group col-sm-4">
                            <label for="rol" >Dirección</label>
                                <input type="text" ng-model="usuario.direccion" class="form-control"/>
                                <p class="help-block text-danger">
                                    @{{ errores.direccion[0] }}
                                </p>
                            </div>
                            
                            <div class="form-group col-sm-4">
                            <label for="rol" >Telefono</label>
                                <input type="text" ng-model="usuario.telefono" class="form-control"/>
                                <p class="help-block text-danger">
                                    @{{ errores.telefono[0] }}
                                </p>
                            </div>
                            <div class="form-group col-sm-4">
                            <label for="rol" >Correo empresarial</label>
                                <input type="text" ng-model="usuario.email" class="form-control"/>
                                <p class="help-block text-danger">
                                    @{{ errores.email[0] }}
                                </p>
                            </div>
                        </div>
                        <br>
                        <legend>Datos del representante legal</legend>
                        <div class="row">
                            <div class="form-group col-sm-6">
                                <label for="empresa" >Nombres</label>
                                <input type="text" ng-model="usuario.nombres_representante" class="form-control"/>
                                <p class="help-block text-danger">
                                    @{{ errores.nombres_representante[0] }}
                                </p>
                            </div>
                            
                            <div class="form-group col-sm-6">
                                <label for="empresa" >Apellidos</label>
                                <input type="text" ng-model="usuario.apellidos_representante" class="form-control"/>
                                <p class="help-block text-danger">
                                    @{{ errores.apellidos_representante[0] }}
                                </p>
                            </div>
                            
                            <div class="form-group col-sm-3">
                            <label for="rol" >Tipo de documento</label>
                                <ui-select ng-model="usuario.tipodoc_representante">
                                    <ui-select-match placeholder="Seleccionar">
                                        <span ng-bind="$select.selected.nombre"></span>
                                    </ui-select-match>
                                    <ui-select-choices repeat="pais in (datos.tiposDocs | filter: $select.search) track by pais.id">
                                        <span ng-bind="pais.nombre"></span>
                                    </ui-select-choices>
                                </ui-select>
                                <p class="help-block text-danger">
                                    @{{ errores['tipodoc_representante.id'][0] }}
                                    @{{ errores['tipodoc_representante.nombre'][0] }}
                                </p>
                            </div>
                            
                            <div class="form-group col-sm-4">
                                <label for="nombre" >Identificación</label>
                                <input type="text" ng-model="usuario.identificacion_representante" class="form-control"/>
                                <p class="help-block text-danger">
                                    @{{ errores.identificacion_representante[0] }}
                                </p>
                            </div>
                            
                            <div class="form-group col-sm-5">
                                <label for="nombre" >Correo</label>
                                <input type="text" ng-model="usuario.correo_representante" class="form-control"/>
                                <p class="help-block text-danger">
                                    @{{ errores.correo_representante[0] }}
                                </p>
                            </div>
                        </div>
                        <br>
                        <legend>Información de contacto (Persona encargada)</legend>
                        <div class="row">
                            <div class="form-group col-sm-4">
                                <label for="nombre" >Nombres</label>
                                <input type="text" ng-model="usuario.nombres" class="form-control"/>
                                <p class="help-block text-danger">
                                    @{{ errores.nombres[0] }}
                                </p>
                            </div>
                            
                            <div class="form-group col-sm-4">
                                <label for="nombre" >Apellidos</label>
                                <input type="text" ng-model="usuario.apellidos" class="form-control"/>
                                <p class="help-block text-danger">
                                    @{{ errores.apellidos[0] }}
                                </p>
                            </div>
                            
                            <div class="form-group col-sm-4">
                            <label for="rol" >Tipo de documento</label>
                                <ui-select ng-model="usuario.tipo_documento">
                                    <ui-select-match placeholder="Seleccionar">
                                        <span ng-bind="$select.selected.nombre"></span>
                                    </ui-select-match>
                                    <ui-select-choices repeat="pais in (datos.tiposDocs | filter: $select.search) track by pais.id">
                                        <span ng-bind="pais.nombre"></span>
                                    </ui-select-choices>
                                </ui-select>
                                <p class="help-block text-danger">
                                    @{{ errores['tipo_documento.id'][0] }}
                                    @{{ errores['tipo_documento.nombre'][0] }}
                                </p>
                            </div>
                            
                            <div class="form-group col-sm-4">
                                <label for="nombre" >Identificación</label>
                                <input type="text" ng-model="usuario.identificacion" class="form-control"/>
                                <p class="help-block text-danger">
                                    @{{ errores.identificacion[0] }}
                                </p>
                            </div>
                            
                            <div class="form-group col-sm-4">
                                <label for="nombre" >Correo</label>
                                <input type="email" ng-model="usuario.correo" class="form-control"/>
                                <p class="help-block text-danger">
                                    @{{ errores.correo[0] }}
                                </p>
                            </div>
                            
                            <div class="form-group col-sm-4">
                                <label for="nombre" >Celular</label>
                                <input type="text" ng-model="usuario.celular" class="form-control"/>
                                <p class="help-block text-danger">
                                    @{{ errores.celular[0] }}
                                </p>
                            </div>
                        </div>
                            
                        <legend>Datos de usuario</legend>
                        <div class="row">
                            <div class="form-group col-sm-6">
                                <label for="password" >Contraseña</label>
                                <input type="password" ng-model="usuario.password" class="form-control"/>
                                <p class="help-block text-danger">
                                    @{{ errores.password[0] }}
                                </p>
                            </div>
                            
                            <div class="form-group col-sm-6">
                                <label for="nombre" >Confirmar contraseña</label>
                                <input type="password" ng-model="usuario.passwordconfirmada" class="form-control"/>
                                <p class="help-block text-danger">
                                    @{{ errores.passwordconfirmada[0] }}
                                </p>
                            </div>
                        </div>
                    </div>
                    <br>
                    <div class="col-sm-12">
                        <button type="submit" class="btn btn-success" >Registrarse</button>
                    </div>
                </form>
  
            </fieldset>
        </div>

    {{-- modal aceptar términos empresa --}}
    <div class="modal fade" id="empresaModal" tabindex="-1" role="dialog" aria-labelledby="empresaModalLabel">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header bg-primary" style="padding: 10px;">
                    <h4 class="modal-title" id="empresaModalLabel">Acepto los términos y condiciones</h4>
                </div>
                <div class="modal-body">
                    <p class="text-justify">
                        Teniendo en cuenta que para la prestación de los servicios de gestión y colocación se requiere el suministro de información, de conformidad con lo dispuesto por el Decreto 2852 de 2013, autorizo con consentimiento previo e informado, en los términos establecidos por la Ley Estatutaria 1581 de 2012, para que los Centros de Atención autorizados por el Ministerio del Trabajo, realicen el tratamiento de la información para el fin exclusivo de realizar las labores de intermediación laboral y análisis ocupacional en mi favor. En ese sentido, manifiesto que la información por mí suministrada, estará a disposición, para los mismos fines y con las mismas restricciones, para todos los prestadores autorizados del Servicio Público de Empleo, de acuerdo con lo dispuesto en el artículo 21 del Decreto 2852 de 2013 en el literal e:
                    </p>
                    <blockquote>
                        <em>
                            “Prestar los servicios con respeto a la dignidad y el derecho a la intimidad de los oferentes y demandantes. El tratamiento de sus datos, se realizará atendiendo lo dispuesto por la Ley Estatutaria 1581 de 2012 y demás disposiciones sobre la materia”.
                        </em>
                    </blockquote>
                </div>
                <div class="modal-footer" style="border-top: 0;">
                    <button type="button" class="btn btn-primary" data-dismiss="modal">Aceptar</button>
                    <a href="{{ asset('/') }}" class="btn btn-default">Cancelar</a>
                </div>
            </div>
        </div>
    </div>

@stop