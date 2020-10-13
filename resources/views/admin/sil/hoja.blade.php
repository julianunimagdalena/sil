@extends('master._adminLayoutNew')
@section('titulo', 'Tablero de administrador')
@section('controller','ng-controller ="AdminHoja2Ctrl"')
@section('title')
    <h2>Tablero de administrador</h2>
@endsection

@section('content')

<div class="row" ng-init="idPersona={{$idPersona}}">
    
    <div class="col-md-12">
        <center>
            <h3 style="color: #004A87; font-weight: 700" ng-if="persona.gethojadevida[0].activa == 0">
               HOJA DE VIDA PRIVADA
                @section('tituloVista', 'HOJA DE VIDA')
            </h3>
            <h3 style="color: #004A87; font-weight: 700" ng-if="persona.gethojadevida[0].activa == 1">
               HOJA DE VIDA PÚBLICA 
                @section('tituloVista', 'HOJA DE VIDA')
            </h3>
        </center>
        <div ng-cloak>
            <md-content class="bordes">
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
                                                        <p style="color: #5a5a5e;">@{{persona.getciudadres.getdepartamento.getpais.nombre}}</p>
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
                                                        <p style="color: #5a5a5e;">@{{persona.getciudadres.getdepartamento.nombre}}</p>
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
                                                        <p style="color: #5a5a5e;"> @{{ persona.getciudadres.nombre }} </p>
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
                                                        <p style="color: #5a5a5e;"> @{{ persona.getestadocivil.nombre }} </p>
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
                                                        <p style="color: #5a5a5e;"> @{{ persona.direccion }} </p>
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
                                                        <p style="color: #5a5a5e;"> @{{ persona.estrato }} </p>
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
                                                        <p style="color: #5a5a5e;"> @{{ persona.telefono_fijo }} </p>
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
                                                        <p style="color: #5a5a5e;"> @{{ persona.correo }} </p>
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
                                                        <p style="color: #5a5a5e;"> @{{ persona.correo2 }} </p>
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
                                                        <p style="color: #5a5a5e;"> @{{ persona.celular }} </p>
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
                                                        <p style="color: #5a5a5e;"> @{{ persona.celular2 }} </p>
                                                    </div>
                                                    <md-divider></md-divider>  
                                                </md-list-item>    
                                            </md-list>
                                        </md-content>  
                                    </div>
                                </div>
                            </form>
                        </md-content>
                    </md-tab>
                    <md-tab label="Perfil">
                        <md-content class="md-padding">
                            <fieldset>
                                <legend>
                                    <h4>Perfil profesional</h4>
                                </legend>
                                <div class="form-group">
                                    <textarea class="form-control" disabled ng-model="persona.gethojadevida[0].perfil"></textarea>
                                </div>
                            </fieldset>
                                
                            <fieldset ng-if=" persona.gethojadevida[0].getcompetencias.length > 0  ">
                                <legend>
                                    <h4>
                                        Competencias personales
                                    </h4>
                                </legend>
                                <div class="form-group">
                                    <div class="col-lg-12">
                                        <p class="help-block text-danger">
                                            <ul>
                                                <li ng-repeat="competencia in persona.gethojadevida[0].getcompetencias">
                                                    @{{ competencia.nombre }}
                                                </li>
                                            </ul>
                                        </p>
                                        <br>
                                    </div>
                                </div>
                            </fieldset>
                                
                            <fieldset ng-if="persona.gethojadevida[0].getestudios.length > 0">
                                <legend>
                                    <h4>
                                        Estudios realizados
                                    </h4>
                                </legend>
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
                                    <h4>
                                        Distinciones
                                    </h4>
                                </legend>
                                <div class="form-group">
                                    <div class="col-lg-12">
                                        <p class="help-block text-danger">
                                            <ul>
                                                <li ng-repeat="distincion in persona.gethojadevida[0].getdistinciones">
                                                    @{{ distincion.nombre }}
                                                </li>
                                            </ul>
                                            <br>
                                        </p>
                                    </div>
                                </div>
                            </fieldset>
                            <fieldset ng-if=" persona.gethojadevida[0].getdiscapacidades.length > 0  ">
                                <legend>
                                    <h4>
                                        Discapacidades
                                    </h4>
                                </legend>
                                <div class="form-group">
                                    <div class="col-lg-12">
                                        <p class="help-block text-danger">
                                            <ul>
                                                <li ng-repeat="discapacidad in persona.gethojadevida[0].getdiscapacidades">
                                                    @{{ discapacidad.nombre }}
                                                </li>
                                            </ul>
                                            <br>
                                        </p>
                                    </div>
                                </div>
                            </fieldset>
                             <fieldset ng-if="persona.gethojadevida[0].getidiomashv.length > 0">
                                <legend>
                                    <h4>
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
 
                        </md-content>
                    </md-tab>
                    <md-tab label="Experiencia Laboral">
                        <md-content class="md-padding">
                            <fieldset ng-if="persona.gethojadevida[0].getexperiencias.length > 0">
                                    <legend>
                                        <h4>
                                            Experiencia Laboral
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
                        </md-content>
                    </md-tab>
                </md-tabs>
            </md-content>
        </div>
    </div>  
</div>

@stop