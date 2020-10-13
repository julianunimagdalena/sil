@extends('master._adminLayoutNew')
@section('titulo', 'Tablero de empresa - ')
@section('controller','ng-controller ="EmpHoja2Ctrl"')
@section('title')
    <h2>Tablero de empresa</h2>
@endsection

@section('left')
  
@endsection

@section('content')

<h3 style="color: #004A87; font-weight: 700">HOJA DE VIDA</h3>
<br>
<div class="row" ng-init="idPersona={{$idPersona}}">
    <div class="col-md-12">
    <div ng-cloak>
            <md-content class="bordes">
                <md-tabs md-dynamic-height md-border-bottom>
                    <md-tab label="Datos personales">
                        <md-content class="md-padding">
                            <div class="row">                            
                                @if ($mostrarDatos)
                                    <div class="col-md-3">
                                @else
                                    <div class="col-md-6">
                                @endif
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
                                @if ($mostrarDatos)
                                    <div class="col-md-3">
                                @else
                                    <div class="col-md-6">
                                @endif
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
                                @if ($mostrarDatos)
                                    <div class="col-md-3">
                                @else
                                    <div class="col-md-6">
                                    @endif
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
                                @if ($mostrarDatos)
                                    <div class="col-md-3">
                                @else
                                    <div class="col-md-6">
                                @endif
                                <md-content>
                                    <md-list>
                                        <md-list-item class="md-3-line">
                                            <div class="md-list-item-text">
                                                <h3>Edad</h3>
                                                <p style="color: #5a5a5e;">@{{persona.edad}} años</p>
                                            </div>
                                            <md-divider></md-divider>  
                                        </md-list-item>    
                                    </md-list>
                                </md-content>  
                            </div>
                        </div>
                            @if ($mostrarDatos)
                            <div class="row">
                                
                                    <div class="col-md-6">
                                        <md-content>
                                            <md-list>
                                                <md-list-item class="md-3-line">
                                                    <div class="md-list-item-text">
                                                        <div class="form-group">
                                                            <h3>Telefono celular</h3>
                                                            <p style="color: #5a5a5e;">@{{ persona.celular }}</p>
                                                        </div>
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
                                                        <div class="form-group">
                                                            <h3>Telefono celular adicional</h3>
                                                            <p style="color: #5a5a5e;">@{{ persona.celular2 }}</p>
                                                        </div>
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
                                                        <div class="form-group">
                                                            <h3>Correo</h3>
                                                            <p style="color: #5a5a5e;">@{{persona.correo}}</p>
                                                        </div>
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
                                                        <div class="form-group">
                                                            <h3>Correo adicional</h3>
                                                            <p style="color: #5a5a5e;">@{{persona.correo2}}</p>
                                                        </div>
                                                    </div>
                                                    <md-divider></md-divider>  
                                                </md-list-item>    
                                            </md-list>
                                        </md-content>  
                                    </div>
                            </div>
                            @endif
                        </md-content>
                    </md-tab>
                    <md-tab label="Perfil">
                        <md-content class="md-padding">
                            <fieldset>
                                    <legend style="font-weight: 600">
                                        Perfil profesional
                                    </legend>
                                    <div class="form-group">
                                        <label class="col-lg-2"></label>
                                        <div class="col-lg-12">
                                           {{--  <textarea>
                                                @{{ persona.gethojadevida[0].perfil }}
                                                <br><br>
                                            </textarea> --}}
                                            <textarea class="form-control noresize" rows="5" ng-disabled="true" ng-model="persona.gethojadevida[0].perfil">
                                                        </textarea>
                                        </div>
                                    </div>
                                </fieldset>
                                
                                <fieldset ng-if=" persona.gethojadevida[0].getcompetencias.length > 0  ">
                                    <legend>
                                        <h4 style="font-weight: 600">
                                            Competencias personales
                                        </h4>
                                    </legend>
                                        <div class="form-group">
                                        <div class="row">
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
                                        </div>
                                    </div>
                                </fieldset>
                                
                                <fieldset ng-if="persona.gethojadevida[0].getestudios.length > 0">
                                    <legend>
                                        <h4 style="font-weight: 600">
                                            Estudios realizados
                                        </h4>
                                    </legend>
                       {{--              <div class="form-group">
                                        <div class="col-lg-12" ng-repeat="estudio in persona.gethojadevida[0].getestudios">
                                            <fieldset>
                                                <legend>
                                                    <h5>
                                                        @{{ estudio.titulo || 'lorem ipsum' | uppercase }}
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
                                    </div> --}}

                                     <div class="form-group">
                                        <div class="row">
                                            <div class="col-xl-4 col-md-6 mb-4" ng-repeat="estudio in persona.gethojadevida[0].getestudios">
                                                <div class="card border-left-warning shadow h-100 py-2">
                                                    <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                                                        <h6 class="m-0 font-weight-bold text-primary">@{{ estudio.titulo | uppercase }}</h6>
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
                                <fieldset ng-if=" persona.gethojadevida[0].getdistinciones.length > 0  ">
                                    <legend>
                                        <h4 style="font-weight: 600">
                                            Distinciones
                                        </h4>
                                    </legend>
                                       <div class="form-group">
                                        <div class="row">
                                            <div class="col-xl-3 col-md-6 mb-4">
                                              <div class="card border-left-primary shadow h-100 py-2">
                                                <div class="card-body">
                                                  <div class="row no-gutters align-items-center">
                                                    <div class="col mr-2">
                                                        <li class="text-xs font-weight-bold text-primary text-uppercase mb-1" ng-repeat="distincion in persona.gethojadevida[0].getdistinciones">
                                                            @{{ distincion.nombre }}
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
                                <fieldset ng-if=" persona.gethojadevida[0].getdiscapacidades.length > 0  ">
                                    <legend>
                                        <h4 style="font-weight: 600">
                                            Discapacidades
                                        </h4>
                                    </legend>
                                    <div class="form-group">
                                        <div class="row">
                                            <div class="col-xl-3 col-md-6 mb-4">
                                              <div class="card border-left-info shadow h-100 py-2">
                                                <div class="card-body">
                                                  <div class="row no-gutters align-items-center">
                                                    <div class="col mr-2">
                                                        <li class="text-xs font-weight-bold text-primary text-uppercase mb-1" ng-repeat="discapacidad in persona.gethojadevida[0].getdiscapacidades">
                                                            @{{ discapacidad.nombre | uppercase }}
                                                        </li>
                                                    </div>
                                                    <div class="col-auto">
                                                      <i class="fas fa-wheelchair fa-2x text-gray-300"></i>
                                                    </div>
                                                  </div>
                                                </div>
                                              </div>
                                            </div>
                                        </div>
                                    </div>
                                </fieldset>
                                 <fieldset ng-if="persona.gethojadevida[0].getidiomashv.length > 0">
                                    <legend>
                                        <h4 style="font-weight: 600">
                                            Idiomas
                                        </h4>
                                    </legend>
                                       <div class="form-group">
                                        <div class="row">
                                            <div class="col-xl-4 col-md-6 mb-4" ng-repeat="idioma in persona.gethojadevida[0].getidiomashv">
                                                <div class="card border-left-warning shadow h-100 py-2">
                                                    <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                                                        <h6 class="m-0 font-weight-bold text-primary">@{{ idioma.getidioma.nombre | uppercase }}</h6>
                                                    </div>  
                                                    <div class="card-body d-flex flex-column justify-content-between">
                                                        <div class="row no-gutters align-items-center">
                                                            <div class="col mr-2">
                                                                <div class="text-xs font-weight-bold mb-1">
                                                                    <i class="fas fa-book"></i>
                                                                    Nivel de lectura:  @{{ idioma.getnivellectura.nombre | uppercase }}
                                                                </div>
                                                                <div class="text-xs font-weight-bold mb-1">
                                                                    <i class="fas fa-microphone"></i>
                                                                    Nivel de habla:  @{{ idioma.getnivelhabla.nombre | uppercase }}
                                                                </div>
                                                                <div class="text-xs font-weight-bold mb-1">
                                                                    <i class="fas fa-pencil-alt"></i>
                                                                    Nivel de lectura:  @{{ idioma.getnivelescritura.nombre | uppercase }}
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </fieldset>
 
                        </md-content>
                    </md-tab>
                    <md-tab label="Experiencia Laboral">
                        <md-content class="md-padding">
                            <fieldset ng-if="persona.gethojadevida[0].getexperiencias.length > 0">
                                    <legend>
                                        <h4 style="font-weight: 600">
                                            Experiencia
                                        </h4>
                                    </legend>
                                    <div class="form-group">
                                        <div class="row">
                                            <div class="col-xl-4 col-md-6 mb-4" ng-repeat="exp in persona.gethojadevida[0].getexperiencias">
                                                <div class="card border-left-warning shadow h-100 py-2">
                                                    <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                                                        <h6 class="m-0 font-weight-bold text-primary">@{{ exp.cargo || 'lorem ipsum' | uppercase }}</h6>
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
                        </md-content>
                    </md-tab>
                    <md-tab label="Referencias">
                        <md-content class="md-padding">
                          <fieldset ng-if="persona.gethojadevida[0].getreferenciasp.length > 0">
                                    <legend>
                                        <h4>
                                            Referencias personales
                                        </h4>
                                    </legend>
                                    <div class="form-group">
                                        <div class="row">
                                            <div class="col-xl-4 col-md-6 mb-4" ng-repeat="referenciap in persona.gethojadevida[0].getreferenciasp">
                                                <div class="card border-left-warning shadow h-100 py-2">
                                                    <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                                                        <h6 class="m-0 font-weight-bold text-primary">@{{ referenciap.nombre | uppercase }}</h6>
                                                    </div>  
                                                    <div class="card-body d-flex flex-column justify-content-between">
                                                        <div class="row no-gutters align-items-center">
                                                            <div class="col mr-2">
                                                                <div class="text-xs font-weight-bold mb-1">
                                                                    Ocupación: @{{ referenciap.ocupacion | uppercase }}
                                                                </div>
                                                                <div class="text-xs font-weight-bold mb-1">
                                                                    Telefono: @{{ referenciap.telefono }}
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    
                                </fieldset>
                                
                                <fieldset ng-if="persona.gethojadevida[0].getreferenciasf.length > 0">
                                    <legend>
                                        <h4>
                                            Referencias familiares
                                        </h4>
                                    </legend>
                                    <div class="form-group">
                                        <div class="row">
                                            <div class="col-xl-4 col-md-6 mb-4" ng-repeat="referenciaf in persona.gethojadevida[0].getreferenciasf">
                                                <div class="card border-left-warning shadow h-100 py-2">
                                                    <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                                                        <h6 class="m-0 font-weight-bold text-primary">@{{ referenciaf.nombre | uppercase }}</h6>
                                                    </div>  
                                                    <div class="card-body d-flex flex-column justify-content-between">
                                                        <div class="row no-gutters align-items-center">
                                                            <div class="col mr-2">
                                                                <div class="text-xs font-weight-bold mb-1">
                                                                    Ocupación: @{{ referenciaf.ocupacion | uppercase }}
                                                                </div>
                                                                <div class="text-xs font-weight-bold mb-1">
                                                                    Teléfono: @{{ referenciaf.telefono }}
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
                        </md-content>
                    </md-tab>
                </md-tabs>
            </md-content>
        </div>
  </div>  
</div>

@stop