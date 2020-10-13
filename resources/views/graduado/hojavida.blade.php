@extends('master._adminLayoutNew')
@section('titulo', 'Tablero de Graduado - ')
@section('controller','ng-controller ="GraduadoHojaCtrl"')
@section('title')
    <h2>Tablero de Graduado</h2>
@endsection

@section('estilos')

    <style>
        #link-hojavida * {
            color: #fff;
        }

        .card p {
            margin-bottom: 0;
        }

        h4 {
            font-weight: 600;
        }

    </style>
  
@endsection

@section('content')
<h3 style="color: #004A87; font-weight: 700">HOJA DE VIDA</h3>
@section('tituloVista', 'HOJA DE VIDA')
<div class="row">
    <div class="col-md-12">
        <div ng-cloak>
            <md-content class="bordes" >
                <md-tabs md-dynamic-height md-border-bottom>
                    <md-tab label="Datos personales">
                        <md-content class="md-padding">
                            <form role="form" ng-submit="guardarDatosPersonales()">
                                <div class="row">
                                    
                                    <div class="col-md-4">
                                        <md-content>
                                            <md-list>
                                                <md-list-item class="md-3-line">
                                                    <div class="md-list-item-text">
                                                        <h3>Nombre</h3>
                                                        <p style="color: #5a5a5e;"> @{{ persona.nombres }} @{{ persona.apellidos }}  </p>
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
                                                    <div class="md-list-item-text">
                                                        <h3>Identificacion</h3>
                                                        <p style="color: #5a5a5e;"> @{{ persona.identificacion }} </p>
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
                                                    <div class="md-list-item-text">
                                                        <h3>Programa(s)</h3>
                                                        <p ng-repeat="item in persona.getestudiantes" style="color: #5a5a5e;">@{{ item.getprograma.getprograma.nombre | uppercase }}</p>
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
                                                    <div class="md-list-item-text">
                                                        <h3>Pais de origen</h3>
                                                        <p style="color: #5a5a5e;">@{{persona.getciudad.getdepartamento.getpais.nombre}}</p>
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
                                                    <div class="md-list-item-text">
                                                        <h3>Departamento de origen</h3>
                                                        <p style="color: #5a5a5e;">@{{persona.getciudad.getdepartamento.nombre}}</p>
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
                                                    <div class="md-list-item-text">
                                                        <h3>Ciudad de origen</h3>
                                                        <p style="color: #5a5a5e;">@{{persona.getciudad.nombre}}</p>
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
                                                    <div class="md-list-item-text">
                                                        <h3>Pais de residencia</h3>
                                                        <div class="form-group">
                                                            <md-select ng-model="persona.getciudadres.getdepartamento.getpais" ng-model-options="{trackBy: '$value.id'}" aria-label="true" ng-change="departamentos()">
                                                                <md-option ng-value="est" ng-repeat="est in persona.paises" ng-selecteed="index==1">@{{ est.nombre }}</md-option>
                                                            </md-select>
                                                        </div>
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
                                                    <div class="md-list-item-text">
                                                        <h3>Departamento de residencia</h3>
                                                        <div class="form-group">
                                                            <md-select ng-model="persona.getciudadres.getdepartamento" ng-model-options="{trackBy: '$value.id'}" aria-label="true" ng-change="ciudades()">
                                                                <md-option ng-value="est" ng-repeat="est in persona.departamentos" ng-selecteed="index==1">@{{ est.nombre }}</md-option>
                                                            </md-select>
                                                        </div>
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
                                                    <div class="md-list-item-text">
                                                        <h3>Ciudad de residencia</h3>
                                                        <div class="form-group">
                                                            <md-select ng-model="persona.getciudadres" ng-model-options="{trackBy: '$value.id'}" aria-label="true">
                                                                <md-option ng-value="est" ng-repeat="est in persona.ciudades" ng-selecteed="index==1">@{{ est.nombre }}</md-option>
                                                            </md-select>
                                                            <p class="help-block text-danger" style="color: red">
                                                                @{{ errores['getciudadres.id'][0] }}
                                                            </p>
                                                        </div>
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
                                                    <div class="md-list-item-text">
                                                        <div class="form-group">
                                                            <h3>Fecha de nacimiento</h3>
                                                            <p style="color: #5a5a5e;">@{{persona.fechaNacimiento | date: 'dd/MM/yyyy'}}</p>
                                                        </div>
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
                                                    <div class="md-list-item-text">
                                                        <h3>Genero</h3>
                                                        <p style="color: #5a5a5e;">@{{persona.getgenero.nombre}}</p>
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
                                                    <div class="md-list-item-text">
                                                        <h3>Estado civil</h3>
                                                        <div class="form-group">
                                                            <md-select ng-model="persona.getestadocivil" ng-model-options="{trackBy: '$value.id'}" aria-label="true">
                                                                <md-option ng-value="est" ng-repeat="est in persona.estadosciviles" ng-selecteed="index==1">@{{ est.nombre }}</md-option>
                                                            </md-select>
                                                            <p class="help-block text-danger" style="color: red">
                                                                @{{ errores['getestadocivil.id'][0] }}
                                                            </p>
                                                        </div>
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
                                                    <div class="md-list-item-text">
                                                        <h3>Direccion</h3>
                                                        <md-input-container class="md-block" flex-gt-sm>
                                                            <input ng-model="persona.direccion" aria-label="true">
                                                            <p class="help-block text-danger" style="color: red">
                                                                @{{ errores.direccion[0] }}
                                                            </p>
                                                        </md-input-container>
                                                        
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
                                                    <div class="md-list-item-text">
                                                        <h3>Estrato</h3>
                                                        <md-input-container class="md-block" flex-gt-sm>
                                                            <input ng-model="persona.estrato" aria-label="true">
                                                            <p class="help-block text-danger" style="color: red">
                                                                @{{ errores.estrato[0] }}
                                                            </p>
                                                        </md-input-container>
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
                                                    <div class="md-list-item-text">
                                                        <h3>Teléfono fijo</h3>
                                                        <md-input-container class="md-block" flex-gt-sm>
                                                            <input ng-model="persona.telefono_fijo" aria-label="true">
                                                            <p class="help-block text-danger" style="color: red">
                                                                @{{ errores.telefono_fijo[0] }}
                                                            </p>
                                                        </md-input-container>
                                                    </div>
                                                    <md-divider></md-divider>  
                                                </md-list-item>    
                                            </md-list>
                                        </md-content>  
                                    </div>
                                    <div class="col-md-6">
                                        <md-content>
                                            <md-list>
                                                <md-list-item class="md-3-line">
                                                    <div class="md-list-item-text">
                                                        <h3>Correo electronico principal</h3>
                                                        <md-input-container class="md-block" flex-gt-sm>
                                                            <input type="email" ng-model="persona.correo" aria-label="true">
                                                            <p class="help-block text-danger" style="color: red">
                                                                @{{ errores.correo[0] }}
                                                            </p>
                                                        </md-input-container>
                                                    </div>
                                                    <md-divider></md-divider>  
                                                </md-list-item>    
                                            </md-list>
                                        </md-content>  
                                    </div>
                                    <div class="col-md-6">
                                        <md-content>
                                            <md-list>
                                                <md-list-item class="md-3-line">
                                                    <div class="md-list-item-text">
                                                        <h3>Correo electronico adicional</h3>
                                                        <md-input-container class="md-block" flex-gt-sm>
                                                            <input type="email" ng-model="persona.correo2" aria-label="true">
                                                            <p class="help-block text-danger" style="color: red">
                                                                @{{ errores.correo2[0] }}
                                                            </p>
                                                        </md-input-container>
                                                    </div>
                                                    <md-divider></md-divider>  
                                                </md-list-item>    
                                            </md-list>
                                        </md-content>  
                                    </div>
                                    <div class="col-md-6">
                                        <md-content>
                                            <md-list>
                                                <md-list-item class="md-3-line">
                                                    <div class="md-list-item-text">
                                                        <h3>Teléfono celular principal</h3>
                                                        <md-input-container class="md-block" flex-gt-sm>
                                                            <input ng-model="persona.celular" aria-label="true">
                                                            <p class="help-block text-danger" style="color: red">
                                                                @{{ errores.celular[0] }}
                                                            </p>
                                                        </md-input-container>
                                                    </div>
                                                    <md-divider></md-divider>  
                                                </md-list-item>    
                                            </md-list>
                                        </md-content>  
                                    </div>
                                    <div class="col-md-6">
                                        <md-content>
                                            <md-list>
                                                <md-list-item class="md-3-line">
                                                    <div class="md-list-item-text">
                                                        <h3>Teléfono celular adicional</h3>
                                                        <md-input-container class="md-block" flex-gt-sm>
                                                            <input ng-model="persona.celular2" aria-label="true">
                                                            <p class="help-block text-danger" style="color: red">
                                                                @{{ errores.celular2[0] }}
                                                            </p>
                                                        </md-input-container>
                                                    </div>
                                                    <md-divider></md-divider>  
                                                </md-list-item>    
                                            </md-list>
                                        </md-content>  
                                    </div>
                                    <div class="col-lg-12">
                                        <button type="submit" class="btn btn-success">
                                            Actualizar datos personales
                                        </button>
                                    </div>
                                </div>
                            </form>
                        </md-content>
                    </md-tab>
                    <md-tab label="Perfil">
                        <md-content class="md-padding">
                            
                            <form role="form" ng-submit="guardarPerfil()">
                                <fieldset>
                                    <legend style="font-weight: 600">Perfil profesional</legend>
                                    <div class="form-group">
                                        <label class="col-lg-2"></label>
                                        <div class="col-lg-12">
                                            <textarea class="form-control noresize" rows="7" ng-model="persona.gethojadevida[0].perfil"></textarea>
                                            <p class="help-block text-danger">
                                                @{{ errores.perfil[0] }}
                                            </p>
                                        </div>
                                    </div>
                                </fieldset>
                                
                                <fieldset>
                                    <legend>
                                        <h4 style="font-weight: 600">
                                            Competencias personales
                                        </h4>
                                    </legend>
              {{--                       <div class="form-group">
                                        <ul>
                                            <li ng-repeat="i in persona.gethojadevida[0].getcompetencias">@{{ i.nombre }}</li>
                                        </ul>
                                        <select class="form-control bselect" ng-model="persona.gethojadevida[0].getcompetencias" multiple ng-options="i.nombre for i in persona.competencias track by i.id"></select>
                                        <p class="help-block text-danger">
                                            @{{ errores['getcompetencias.id'][0] }}
                                        </p>
                                        <br>
                                    </div> --}}
                                      <div class="form-group">
                                        <div class="col-md-12">
                                            <div class="col-xl-4 col-md-6 mb-4">
                                              <div class="card border-left-success shadow h-100 py-2">
                                                <div class="card-body">
                                                  <div class="row no-gutters align-items-center">
                                                    <div class="col mr-2">
                                                        <li class="text-xs font-weight-bold text-primary text-uppercase mb-1" ng-repeat="i in persona.gethojadevida[0].getcompetencias">
                                                            @{{ i.nombre }}
                                                        </li>
                                                    </div>
                                                    <div class="col-auto">
                                                      <i class="fas fa-ballot-check fa-2x text-gray-300"></i>
                                                    </div>
                                                  </div>
                                                </div>
                                              </div>
                                            </div>
                                              <div class="col-xl-3 col-md-6 mb-4">
                                                <label>Seleccione</label>
                                                      <select class="form-control bselect" ng-model="persona.gethojadevida[0].getcompetencias" multiple ng-options="i.nombre for i in persona.competencias track by i.id"></select>
                                                        <p class="help-block text-danger">
                                                            @{{ errores['getcompetencias.id'][0] }}
                                                        </p>
                                                </div>
                                        </div>
                                    </div>
                                </fieldset>
                                
                                <fieldset>
                                    <legend class="d-flex">
                                        <h4 style="font-weight: 600">
                                            Estudios realizados
                                        </h4>
                                        <i class="fa fa-plus ml-auto text-primary " style="cursor: pointer;" title="Agregar estudio" data-toggle="modal" data-target="#agregarEstudio"></i>
                                    </legend>
                                    <div class="form-group">
                                        <div class="row">
                                            <div class="col-xl-4 col-md-6 mb-4" ng-repeat="estudio in persona.gethojadevida[0].getestudios">
                                                <div class="card border-left-warning shadow h-100 py-2">
                                                    <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                                                        <h6 class="m-0 font-weight-bold text-primary">@{{ estudio.titulo | uppercase }}</h6>
                                                        <i style="cursor: pointer;" class="fas fa-times text-danger" data-toggle="tooltip" data-placement="top" title="Borrar" ng-click="quitarEstudio(estudio.titulo)"></i>
                                                    </div>  
                                                    <div class="card-body d-flex flex-column justify-content-between">
                                                        <div class="row no-gutters align-items-center">
                                                            <div class="col mr-2">
                                                                <div class="text-xs font-weight-bold mb-1">
                                                                    Institución: @{{ estudio.institucion | uppercase }}
                                                                </div>
                                                                <div class="text-xs font-weight-bold mb-1">
                                                                    Ciudad o municipio: @{{ estudio.getmunicipio.getdepartamento.getpais.nombre + ", "+ estudio.getmunicipio.getdepartamento.nombre + " - " + estudio.getmunicipio.nombre || 'lorem ipsum' }}
                                                                </div>
                                                                <div class="text-xs font-weight-bold mb-1">
                                                                    Año de grado: @{{ estudio.anioGrado || 'lorem ipsum' }}
                                                                </div>
                                                                <br>
                                                                <div class="text-xs font-weight-bold mb-1">
                                                                    <span class="text-uppercase" style="font-weight: 1000 !important">Méritos</span>
                                                                    <br>
                                                                    @{{ estudio.observaciones }}
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </fieldset>
                                
                                <!---->
                                
                                <fieldset>
                                    <legend class="d-flex justify-content-between">
                                        <h4>Experiencia Laboral</h4>
                                        <i class="fa fa-plus ml-auto text-primary" style="cursor: pointer;" title="Agregar estudio" data-toggle="modal" data-target="#agregarExperiencia"></i>
                                    </legend>
                                    <div class="form-group">
                                        <div class="row">
                                            <div class="col-xl-4 col-md-6 mb-4" ng-repeat="exp in persona.gethojadevida[0].getexperiencias">
                                                <div class="card border-left-warning shadow h-100 py-2">
                                                    <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                                                        <h6 class="m-0 font-weight-bold text-primary">@{{ exp.cargo || 'lorem ipsum' | uppercase }}</h6>
                                                        <i style="cursor: pointer;" class="fas fa-times text-danger" data-toggle="tooltip" data-placement="top" title="Borrar" ng-click="quitarExperiencia(exp.cargo, exp.empresa)"></i>
                                                    </div>  
                                                    <div class="card-body d-flex flex-column justify-content-between">
                                                        <div class="row no-gutters align-items-center">
                                                            <div class="col mr-2">
                                                                <div class="text-xs font-weight-bold mb-1">
                                                                    Empresa: @{{ exp.empresa || 'lorem ipsum' | uppercase }}
                                                                </div>
                                                                <div class="text-xs font-weight-bold mb-1">
                                                                    Lugar: @{{ exp.municipio.getdepartamento.getpais.nombre + ", "+ exp.municipio.getdepartamento.nombre + " - " + exp.municipio.nombre || 'lorem ipsum' }}
                                                                </div>
                                                                <div class="text-xs font-weight-bold mb-1">
                                                                    Duracion: @{{ exp.duracion.nombre || 'lorem ipsum' | uppercase }}
                                                                </div>
                                                                <br>
                                                                <div class="text-xs font-weight-bold mb-1">
                                                                    <span class="text-uppercase" style="font-weight: 1000 !important">Funciones y meritos</span>
                                                                    <br>
                                                                    @{{ exp.funcioneslogros }}
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    
                                </fieldset>

                                <fieldset>
                                    <legend>
                                        <h4 style="font-weight: 600">
                                            Discapacidades
                                        </h4>
                                    </legend>
                                    <div class="form-group">
                                        <div class="col-md-12">
                                            <div class="col-xl-3 col-md-6 mb-4">
                                              <div class="card border-left-info shadow h-100 py-2">
                                                <div class="card-body">
                                                  <div class="row no-gutters align-items-center">
                                                    <div class="col mr-2">
                                                        <li class="text-xs font-weight-bold text-primary text-uppercase mb-1" ng-repeat="i in persona.gethojadevida[0].getdiscapacidades">
                                                            @{{ i.nombre }}
                                                        </li>
                                                    </div>
                                                    <div class="col-auto">
                                                      <i class="fas fa-wheelchair fa-2x text-gray-300"></i>
                                                    </div>
                                                  </div>
                                                </div>
                                              </div>
                                            </div>
                                              <div class="col-xl-3 col-md-6 mb-4">
                                                <label>Seleccione</label>
                                                    <select id="selectdiscap" class="form-control bselect" ng-model="persona.gethojadevida[0].getdiscapacidades" multiple ng-options="i.nombre for i in persona.discapacidades track by i.id" placeholder="First name"></select>
                                                    <p class="help-block text-danger">
                                                    @{{ errores['getdiscapacidades.id'][0] }}
                                                    </p>
                                                </div>
                                        </div>
                                    </div>
                                </fieldset>
                                <fieldset>
                                    <legend class="d-flex justify-content-between">
                                        <h4>Distinciones</h4>
                                        <span class="fas fa-plus text-primary" title="Agregar distincion" data-toggle="modal" data-target="#agregarDistincion"></span>
                                    </legend>
                                    <div class="form-group" ng-if="persona.gethojadevida[0].getdistinciones.length>0">
                                        <div class="row">
                                            <div class="col-xl-3 col-md-6 mb-4">
                                              <div class="card border-left-primary shadow h-100 py-2">
                                                <div class="card-body">
                                                  <div class="row no-gutters align-items-center">
                                                    <div class="col mr-2">
                                                        <li class="text-xs font-weight-bold text-primary text-uppercase mb-1" ng-repeat="distincion in persona.gethojadevida[0].getdistinciones">
                                                            @{{ distincion.nombre }} 
                                                            <i class="fas fa-times text-danger ml-5" title="Eliminar distincion" ng-click="quitarDistincion(distincion.nombre)"></i>
                                                        </li>
                                                    </div>
                                                    <div class="col-auto">
                                                      <i class="fas fa-medal fa-2x text-gray-300"></i>
                                                    </div>
                                                  </div>
                                                </div>
                                              </div>
                                            </div>
                                        </div>
                                    </div>
                                </fieldset>
                                <fieldset>
                                    <legend class="d-flex justify-content-between">
                                        <h4>Idiomas</h4>
                                        <span class="fas fa-plus text-primary" title="Agregar idioma" data-toggle="modal" data-target="#agregarIdioma"></span>
                                    </legend>
                                    <div class="form-group">
                                        <div class="row">
                                            <div class="col-xl-4 col-md-6 mb-4" ng-repeat="idioma in persona.gethojadevida[0].getidiomashv">
                                                <div class="card border-left-warning shadow h-100 py-2">
                                                    <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                                                        <h6 class="m-0 font-weight-bold text-primary">@{{ idioma.getidioma.nombre | uppercase }}</h6>
                                                        <i style="cursor: pointer;" class="fas fa-times text-danger" data-toggle="tooltip" data-placement="top" title="Borrar" ng-click="quitarIdioma(idioma.getidioma.nombre)"></i>
                                                    </div>  
                                                    <div class="card-body d-flex flex-column justify-content-between">
                                                        <div class="row no-gutters align-items-center">
                                                            <div class="col mr-2">
                                                                <div class="text-xs font-weight-bold mb-1">
                                                                    <i class="fas fa-book"></i>
                                                                    Nivel de lectura: @{{ idioma.getnivellectura.nombre | uppercase }}
                                                                </div>
                                                                <div class="text-xs font-weight-bold mb-1">
                                                                    <i class="fas fa-microphone"></i>
                                                                    Nivel de habla: @{{ idioma.getnivelhabla.nombre | uppercase }}
                                                                </div>
                                                                <div class="text-xs font-weight-bold mb-1">
                                                                    <i class="fas fa-pencil-alt"></i>
                                                                    Nivel de lectura: @{{ idioma.getnivelescritura.nombre | uppercase }}
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
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
                                    <legend class="d-flex justify-content-between">
                                        <h4>Referencias personales</h4>
                                        <i class="fas fa-plus text-primary" style="cursor: pointer;" title="Agregar referencia" data-toggle="modal" data-target="#agregarReferenciaP"></i>
                                    </legend>
                                    <div class="form-group">
                                        <div class="row">
                                            <div class="col-xl-4 col-md-6 mb-4" ng-repeat="referenciap in persona.gethojadevida[0].getreferenciasp">
                                                <div class="card border-left-warning shadow h-100 py-2">
                                                    <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                                                        <h6 class="m-0 font-weight-bold text-primary">@{{ referenciap.nombre | uppercase }}</h6>
                                                        <i style="cursor: pointer;" class="fas fa-times text-danger" data-toggle="tooltip" data-placement="top" title="Borrar" ng-click="quitarPersonal(referenciap.telefono)"></i>
                                                    </div>  
                                                    <div class="card-body d-flex flex-column justify-content-between">
                                                        <div class="row no-gutters align-items-center">
                                                            <div class="col mr-2">
                                                                <div class="text-xs font-weight-bold mb-1">
                                                                    Ocupacion: @{{ referenciap.ocupacion | uppercase }}
                                                                </div>
                                                                <div class="text-xs font-weight-bold mb-1">
                                                                    Telefono: @{{ referenciap.telefono | uppercase }}
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    
                                </fieldset>
                                
                                <fieldset>
                                    <legend class="d-flex justify-content-between">
                                        <h4>Referencias familiares</h4>
                                        <i class="fas fa-plus text-primary" style="cursor: pointer;" title="Agregar referencia" data-toggle="modal" data-target="#agregarReferenciaF"></i>
                                    </legend>
                                    <div class="form-group">
                                        <div class="row">
                                            <div class="col-xl-4 col-md-6 mb-4" ng-repeat="referenciaf in persona.gethojadevida[0].getreferenciasf">
                                                <div class="card border-left-warning shadow h-100 py-2">
                                                    <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                                                        <h6 class="m-0 font-weight-bold text-primary">@{{ referenciaf.nombre | uppercase }}</h6>
                                                        <i style="cursor: pointer;" class="fas fa-times text-danger" data-toggle="tooltip" data-placement="top" title="Borrar" ng-click="quitarFamiliar(referenciaf.telefono)"></i>
                                                    </div>  
                                                    <div class="card-body d-flex flex-column justify-content-between">
                                                        <div class="row no-gutters align-items-center">
                                                            <div class="col mr-2">
                                                                <div class="text-xs font-weight-bold mb-1">
                                                                    Ocupación: @{{ referenciaf.ocupacion | uppercase }}
                                                                </div>
                                                                <div class="text-xs font-weight-bold mb-1">
                                                                    Teléfono: @{{ referenciaf.telefono | uppercase }}
                                                                </div>
                                                                <div class="text-xs font-weight-bold mb-1">
                                                                    Parentesco: @{{ referenciaf.getparentesco.nombre | uppercase }}
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
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
        
        <!-- Modal -->
        <div id="agregarEstudio" class="modal fade" role="dialog">
            <div class="modal-dialog modal-lg">
        
                <!-- Modal content-->
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">Agregar estudio</h5>
                        <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">×</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <form role="form" class="form-horizontal" ng-submit="agregarEstudio()">
                            <div class="row">
                                <div class="col-sm-6">
                                    <div class="form-group">
                                        <label>Institución</label>
                                        <input type="text" ng-model="estudios.institucion" class="form-control" placeholder="Institución" />
                                        <p class="help-block text-danger">
                                            @{{ error.institucion[0] }}
                                        </p>
                                    </div>
                                </div>
                                <div class="col-sm-6">
                                    <div class="form-group">
                                        <label>Título obtenido</label>
                                        <input type="text" ng-model="estudios.titulo" class="form-control" placeholder="Titulo obtenido"/>
                                        <p class="help-block text-danger">
                                            @{{ error.titulo[0] }}
                                        </p>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-sm-6">
                                    <div class="form-group">
                                        <label>Ciudad, municipio o provincia</label>
                                        <select class="form-control bselect" data-live-search="true" ng-model="estudios.getmunicipio" ng-options="i.getdepartamento.getpais.nombre + ' - ' + i.nombre for i in persona.ciudades">
                                            <option value="" selected hidden>Selecciona una opción</option>
                                        </select>
                                        <p class="help-block text-danger">
                                            @{{ error['getmunicipio.id'][0] }}
                                        </p>
                                    </div>
                                </div>
                                <div class="col-sm-6">
                                    <div class="form-group">
                                        <label>Año de grado</label>
                                        <select class="form-control bselect" data-live-search="true" ng-model="estudios.anio" ng-options="i.nombre for i in persona.anios">
                                            <option value="" selected hidden>Selecciona una opción</option>
                                        </select>
                                        <p class="help-block text-danger">
                                            @{{ error.anioGrado[0] }}
                                        </p>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="form-group">
                                <label>Méritos</label>
                                <textarea class="form-control noresize" rows="5" ng-model="estudios.observaciones" placeholder="Suministrar informacion"></textarea>
                                <p class="help-block text-danger">
                                    @{{ error.observaciones[0] }}
                                </p>
                            </div>
                            <button type="submit" ng-show="false"></button>
                        </form>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-rojo" data-dismiss="modal" ng-click="cancelarEstudio()">Cerrar</button>
                        <button type="submit" class="btn btn-success" ng-click="agregarEstudio()" >Guardar</button>
                    </div>
                </div>
        
            </div>
        </div>
        
        <!-- Modal -->
        <div id="agregarExperiencia" class="modal fade" role="dialog">
            <div class="modal-dialog modal-lg">
        
                <!-- Modal content-->
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">Agregar experiencia laboral</h5>
                        <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">×</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <form role="form" class="form-horizontal" ng-submit="agreagarExperiencia()">
                            <div class="row">
                                <div class="col-sm-4">
                                    <div class="form-group">
                                        <label>Empresa</label>
                                        <input type="text" ng-model="experiencia.empresa" class="form-control" placeholder="Empresa" />
                                        <p class="help-block text-danger">
                                            @{{ errorExp.empresa[0] }}
                                        </p>
                                    </div>
                                </div>
                                <div class="col-sm-4">
                                    <div class="form-group">
                                        <label>Cargo</label>
                                        <input type="text" ng-model="experiencia.cargo" class="form-control" placeholder="Cargo"/>
                                        <p class="help-block text-danger">
                                            @{{ errorExp.cargo[0] }}
                                        </p>
                                    </div>
                                </div>
                                <div class="col-sm-4">
                                    <div class="form-group">
                                        <label>Nivel del cargo</label>
                                        <select class="form-control" ng-model="experiencia.nivel_cargo_id" ng-options="item.id as item.nombre for item in persona.niveles_cargo">
                                            <option value="" selected hidden>Seleccione una opción</option>
                                        </select>
                                        <p class="help-block text-danger">
                                            @{{ errorExp.nivel_cargo_id[0] }}
                                        </p>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-sm-4">
                                    <div class="form-group">
                                        <label>País</label>
                                        <select class="form-control" ng-model="experiencia.pais" ng-options="item.nombre for item in persona.paisess track by item.id">
                                            <option value="" selected hidden>Seleccione una opción</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-sm-4">
                                    <div class="form-group">
                                        <label>Departamento</label>
                                        <select     class="form-control" ng-model="experiencia.departamento" ng-options="item.nombre for item in experiencia.pais.departamentos track by item.id">
                                            <option value="" selected hidden>Seleccione una opción</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-sm-4">
                                    <div class="form-group">
                                        <label>Municipio</label>
                                        <select class="form-control" ng-model="experiencia.municipio_id" ng-options="item.id as item.nombre for item in experiencia.departamento.municipios">
                                            <option value="" selected hidden>Seleccione una opción</option>
                                        </select>
                                        <p class="help-block text-danger">
                                            @{{ errorExp.municipio_id[0] }}
                                        </p>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-sm-4">
                                    <div class="form-group">
                                        <label>Duración</label>
                                        <select class="form-control" ng-model="experiencia.duracion" ng-options="item.nombre for item in persona.duraciones track by item.id">
                                            <option value="" selected hidden>Seleccione una opción</option>
                                        </select>
                                        <p class="help-block text-danger">
                                            @{{ errorExp['duracion.id'][0] }}
                                        </p>
                                    </div>
                                </div>
                                <div class="col-sm-4">
                                    <div class="form-group">
                                        <label>Tipo de vinculación</label>
                                        <select class="form-control" ng-model="experiencia.tipo_vinculacion_id" ng-options="item.id as item.nombre for item in persona.tipos_vinculacion">
                                            <option value="" selected hidden>Seleccione una opción</option>
                                        </select>
                                        <p class="help-block text-danger">
                                            @{{ errorExp.tipo_vinculacion_id[0] }}
                                        </p>
                                    </div>
                                </div>
                                <div class="col-sm-4">
                                    <div class="form-group">
                                        <label>Rango salarial</label>
                                        <select class="form-control" ng-model="experiencia.salario_id" ng-options="i.id as i.rango for i in persona.salarios">
                                            <option value="" selected hidden>Seleccione una opción</option>
                                        </select>
                                        <p class="help-block text-danger">
                                            @{{ errorExp.salario_id[0] }}
                                        </p>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-sm-6">
                                    <div class="form-group">
                                        <label>Correo electrónico</label>
                                        <input type="text" class="form-control" placeholder="Correo electrónico" ng-model="experiencia.email">
                                        <p class="help-block text-danger">
                                            @{{ errorExp.email[0] }}
                                        </p>
                                    </div>
                                </div>
                                <div class="col-sm-6">
                                    <div class="form-group">
                                        <label>Teléfono</label>
                                        <input type="text" class="form-control" placeholder="Teléfono" ng-model="experiencia.telefono">
                                        <p class="help-block text-danger">
                                            @{{ errorExp.telefono[0] }}
                                        </p>
                                    </div>
                                </div>
                            </div>
                                    
                            
                            <!---->
                            
                            <div class="form-group">
                                <label>Funciones y méritos</label>
                                <textarea class="form-control noresize" rows="5" ng-model="experiencia.funcioneslogros" placeholder="Suministrar informacion">
                                </textarea>
                                <p class="help-block text-danger">
                                    @{{ errorExp.funcioneslogros[0] }}
                                </p>
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
            <div class="modal-dialog modal-lg">
        
                <!-- Modal content-->
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">Agregar idioma</h5>
                        <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">×</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <form role="form" class="form-horizontal" ng-submit="agreagarIdioma()">
                            <div class="form-group">
                                <label>Idioma</label>
                                <div>
                                    <select class="form-control" ng-model="nuevoIdioma.getidioma" ng-change="prueba()" ng-options="i.nombre for i in persona.idiomasdb">
                                        <option value="" selected hidden>Seleccione una opción</option>
                                    </select>
                                    <p class="help-block text-danger">
                                        @{{ errorIdioma['getidioma.id'][0] }}
                                    </p>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-sm-4">
                                    <div class="form-group">
                                        <label><span class="fas fa-book"></span> Nivel de lectura</label>
                                        <select class="form-control" ng-model="nuevoIdioma.getnivellectura" ng-options="i.nombre for i in persona.niveles">
                                            <option value="" selected hidden>Seleccione una opción</option>
                                        </select>
                                        <p class="help-block text-danger">
                                            @{{ errorIdioma['getnivellectura.id'][0] }}
                                        </p>
                                    </div>
                                </div>
                                <div class="col-sm-4">
                                    <div class="form-group">
                                        <label><span class="fas fa-pencil-alt" aria-hidden="true"></span> Nivel de escritura</label>
                                        <select class="form-control" ng-model="nuevoIdioma.getnivelescritura" ng-options="i.nombre for i in persona.niveles">
                                            <option value="" selected hidden>Seleccione una opción</option>
                                        </select>
                                        <p class="help-block text-danger">
                                            @{{ errorIdioma['getnivelescritura.id'][0] }}
                                        </p>
                                    </div>
                                </div>
                                <div class="col-sm-4">
                                    <div class="form-group">
                                        <label><span class="fas fa-microphone" aria-hidden="true"></span> Nivel de habla</label>
                                        <select class="form-control" ng-model="nuevoIdioma.getnivelhabla" ng-options="i.nombre for i in persona.niveles">
                                            <option value="" selected hidden>Seleccione una opción</option>
                                        </select>
                                        <p class="help-block text-danger">
                                            @{{ errorIdioma['getnivelhabla.id'][0] }}
                                        </p>
                                    </div>
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
        <div id="agregarDistincion" class="modal fade" role="dialog">
            <div class="modal-dialog">
        
                <!-- Modal content-->
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">Agregar distinción</h5>
                        <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">×</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <form role="form" class="form-horizontal" ng-submit="agregarDistincion()">
                            <div class="form-group">
                                <label>Nueva distinción</label>
                                <input class="form-control" type="text" ng-model="nuevaDistincion" placeholder="Distincion">
                            </div>
                            <button type="submit" ng-show="false"></button>
                        </form>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-rojo" data-dismiss="modal" ng-click="cancelarDistincion()">Cerrar</button>
                        <button type="submit" class="btn btn-success" ng-click="agregarDistincion()" >Guardar</button>
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
                        <h5 class="modal-title" id="exampleModalLabel">Referencia personal</h5>
                        <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">×</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <form role="form" class="form-horizontal" ng-submit="agreagarReferenciaP()">
                            <div class="row">
                                <div class="col-sm-6">
                                    <div class="form-group">
                                        <label>Nombre</label>
                                        <input type="text" ng-model="referenciaPersonal.nombre" class="form-control" placeholder="Nombre" />
                                        <p class="help-block text-danger">
                                            @{{ errorPersonal.nombre[0] }}
                                        </p>
                                    </div>
                                </div>
                                <div class="col-sm-6">
                                    <div class="form-group">
                                        <label>Teléfono</label>
                                        <input type="text" ng-model="referenciaPersonal.telefono" class="form-control" placeholder="Teléfono"/>
                                        <p class="help-block text-danger">
                                            @{{ errorPersonal.telefono[0] }}
                                        </p>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <label>Ocupación</label>
                                <input type="text" ng-model="referenciaPersonal.ocupacion" class="form-control" placeholder="Ocupación"/>
                                <p class="help-block text-danger">
                                    @{{ errorPersonal.ocupacion[0] }}
                                </p>
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
            <div class="modal-dialog modal-lg">
        
                <!-- Modal content-->
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">Referencia familiar</h5>
                        <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">×</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <form role="form" class="form-horizontal" ng-submit="agreagarReferenciaF()">
                            <div class="row">
                                <div class="col-sm-6">
                                    <div class="form-group">
                                        <label>Nombre</label>
                                        <input type="text" ng-model="referenciaFamiliar.nombre" class="form-control" placeholder="Nombre" />
                                        <p class="help-block text-danger">
                                            @{{ errorFamiliar.nombre[0] }}
                                        </p>
                                    </div>
                                </div>
                                <div class="col-sm-6">
                                    <div class="form-group">
                                        <label >Teléfono</label>
                                        <input type="text" ng-model="referenciaFamiliar.telefono" class="form-control" placeholder="Teléfono"/>
                                        <p class="help-block text-danger">
                                            @{{ errorFamiliar.telefono[0] }}
                                        </p>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-sm-6">
                                    <div class="form-group">
                                        <label>Ocupación</label>
                                        <input type="text" ng-model="referenciaFamiliar.ocupacion" class="form-control" placeholder="Ocupación"/>
                                        <p class="help-block text-danger">
                                            @{{ errorFamiliar.ocupacion[0] }}
                                        </p>
                                    </div>
                                </div>
                                <div class="col-sm-6">
                                    <div class="form-group">
                                        <label>Parentesco</label>
                                        <select class="form-control" ng-model="referenciaFamiliar.getparentesco" ng-options="i.nombre for i in persona.parentescos">
                                            <option value="" selected hidden>Seleccione una opción</option>
                                        </select>
                                        <p class="help-block text-danger">
                                            @{{ errorFamiliar['getparentesco.id'][0] }}
                                        </p>
                                    </div>
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

@endsection