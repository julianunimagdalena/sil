<!DOCTYPE html>
<html ng-app="dipro">
    <head>
        <meta charset="utf-8" />
        <link rel="icon" type="image/png" href="{{asset('/img/Escudo.png')}}" />
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="author" content="FG">
        <title>@yield('title')</title>
        <script   src="https://code.jquery.com/jquery-2.2.3.min.js"   integrity="sha256-a23g1Nt4dtEYOj7bR+vTu7+T8VP13humZFBJNIYoEJo="   crossorigin="anonymous"></script>
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css" type="text/css" />
        <script type="text/javascript" src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/js/bootstrap.min.js"></script>
        <link rel="stylesheet" href="{{asset('/css/style.css')}}" type="text/css" />
        <script type="text/javascript" src="{{asset('/js/angular.min.js')}}"></script>
        <script type="text/javascript" src="{{asset('/js/controller.js')}}"></script>
        <script src="{{asset('/js/angular-locale_es-es.js')}}"></script>
        <script type="text/javascript" src="{{asset('/js/scriptJQ.js')}}"></script>
        <script src="{{asset('/js/angular-input-masks-standalone.min.js')}}"></script>
        <script src="{{asset('/ckeditor/ckeditor.js')}}"></script>
        <script src="{{asset('/angular-ckeditor/angular-ckeditor.js')}}"></script>
        <script src="{{asset('/js/moment.min.js')}}"></script>
        <script src="{{asset('/js/moment-with-locales.js')}}"></script>
        <script type="text/javascript" src="{{asset('/js/select.min.js')}}"></script>
        <link rel="stylesheet" href="{{asset('/css/select.min.css')}}" type="text/css" />
        <script type="text/javascript" src="{{asset('/sweetalert/dist/sweetalert.min.js')}}"></script>
        <link rel="stylesheet" href="{{asset('/sweetalert/dist/sweetalert.css')}}" type="text/css" />
        <link rel="stylesheet" href="{{asset('/table/object-table-style.css')}}" type="text/css" />
        <script type="text/javascript" src="{{asset('/table/object-table.js')}}"></script>

        <link rel="stylesheet" href="{{asset('/font-awesome/css/font-awesome.min.css')}}">
        
        <script type="text/javascript" src="{{asset('/js/angular-material.min.js')}}"></script>
        <script type="text/javascript" src="{{asset('/js/angular-animate.min.js')}}"></script>
        <script type="text/javascript" src="{{asset('/js/angular-aria.min.js')}}"></script>
        <script type="text/javascript" src="{{asset('/js/angular-messages.min.js')}}"></script>
        <link rel="stylesheet" href="{{asset('/css/angular-material.min.css')}}" type="text/css" />
        <link rel="stylesheet" href="{{asset('/bootstrap-gh-pages/ui-bootstrap-2.0.2-csp.css')}}">
        <script src="{{asset('/bootstrap-gh-pages/ui-bootstrap-tpls-2.0.2.min.js')}}"></script>
    </head>
    <body>
        <div class="row" style="height:20px; background:#00223e; color:white;">
            <div class="col-md-1"></div>
            <div class="col-md-10" ng-controller="indexCtrl">
                @{{ fecha | date:'EEEE, MMMM d, y' }}
            </div>
            <div class="col-md-1"></div>
                
        </div>
        <header class="text-center">
            <div class="row">
                <div class="col-md-2">                   
                    
                </div>
                <div class="col-md-3">
                    <a href="{{asset('/')}}">
                        <img src="{{asset('/img/escudo_unimag2.png')}}" style="max-width: 105px;"></img><br>                        
                    </a>
                    <span style="color: #fff;font-size: 1.3em;" >CENTRO DE EGRESADOS</span><br><span style="color: #a8b983;">Vicerretoría de Extensión y Proyección Social</span>
                </div>
                <div class="col-md-5 text-right text-banner">                    
                    Sistema de<br>Intermediación Laboral<br>
                    <span>PORTAL DE EMPLEO | UNIMAGDALENA</span>
                </div>
                <div class="col-md-2">
                    <div id="cerrasesion">

                        @if(Auth::check())
                            <ul class="mi-nav">
                                <li class="mi-dropdown">
                                    <a >
                                        {{Auth::user()->identificacion}} <b class="caret"></b>
                                    </a>                                    
                                    <ul class="mi-dropdown-menu">
                                    @if(Auth::user()->getrol->nombre=='Jefe inmediato' || Auth::user()->getrol->nombre=='Empresa' || Auth::user()->getrol->nombre=='Graduado')
                                        <li>                                    
                                            <a href="{{asset('/home/cambiarclave')}}">Cambiar contraseña</a>
                                        </li>
                                    @endif
                                        <li>                                            
                                            <a href="{{asset('/auth/logout')}}">Cerrar sesión</a>
                                        </li>
                                    </ul>
                                </li>
                                
                            </ul>                            
                        @endif
                    </div>
                        
                </div>
            </div>
            
        </header>
        
        <nav class="navbar navbar-default" role="navigation">                            
            @if(Auth::check())
            <div class="row">
                <div class="col-md-1"></div>
                <div class="col-md-10">
                    <div class="navbar-header">
                        <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-ex1-collapse">
                            <span class="sr-only">Desplegar navegación</span>
                            <span class="icon-bar"></span>
                            <span class="icon-bar"></span>
                            <span class="icon-bar"></span>
                        </button>
                        <!--<a class="navbar-brand" href="#">Logotipo</a>-->
                    </div>
             
                      <!-- Agrupar los enlaces de navegación, los formularios y cualquier
                           otro elemento que se pueda ocultar al minimizar la barra -->
                    <div class="collapse navbar-collapse navbar-ex1-collapse">
                        <ul class="nav navbar-nav">                                                        
                            @if(Auth::user()->getrol->nombre=='Administrador Dippro')
                            <li>
                                <a href="{{asset('/admin/usuarios')}}">Usuarios</a>
                            </li>
                            <li class="dropdown">
                                <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                                  Estudiantes <b class="caret"></b>
                                </a>
                                <ul class="dropdown-menu">
                                    <li><a href="{{asset('/admin/prepracticas')}}">Pre-prácticas</a></li>
                                    <li><a href="{{asset('/admin/solicitantes')}}">Solicitudes de prácticas</a></li>
                                    <li><a href="{{asset('/admin/actas')}}">Aprobar prácticas</a></li>
                                    <li><a href="{{asset('/admin/practicantes')}}">Practicantes</a></li>
                                    <li class="divider"></li>
                                    <li><a href="{{asset('/admin/charlas')}}">Conferencias</a></li>
                                    <li><a href="{{asset('/admin/horarios')}}">Horarios</a></li>
                                    <li class="divider"></li>
                                    <li><a href="{{asset('/admin/cartas')}}">Cartas de presentación</a></li>
                                    <li class="divider"></li>
                                    <li><a href="{{asset('/novedad')}}">Novedades</a></li>
                                </ul>
                            </li>
                            
                            
                            <li><a href="{{asset('/adminsil/evaluaciones')}}">Evaluaciones</a></li>
                            <li class="dropdown">
                                <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                                  Empresas <b class="caret"></b>
                                </a>
                                <ul class="dropdown-menu">
                                    <li><a href="{{asset('/adminsil/empresas')}}">Empresas</a></li>
                                    <li><a href="{{asset('/adminsil/ofertas')}}">Ofertas</a></li>
                                    <li><a href="{{asset('/admin/convenios')}}">Convenios</a></li>
                                </ul>
                            </li>
                            <li><a href="{{asset('/admin/correomasivo')}}">Envío de correo</a></li>
                            @endif
                            @if(Auth::user()->getrol->nombre=='Administrador Egresados')
                            <li><a href="{{asset('/sil/usuarios')}}">Usuarios</a></li>
                            <li><a href="{{asset('/adminsil/empresas')}}">Empresas</a></li>
                            <li><a href="{{asset('/adminsil/ofertas')}}">Ofertas</a></li>
                            <li><a href="{{asset('/sil/hojasdevida')}}">Hojas de vida</a></li>
                            <li><a href="{{asset('/novedad')}}">Novedades</a></li>
                            @endif
                            @if(Auth::user()->getrol->nombre=='Administrador Dippro' || Auth::user()->getrol->nombre=='Administrador Egresados')                            
                            
                            @endif
                            
                            @if(Auth::user()->getrol->nombre=='Empresa')
                            <li><a href="{{asset('/empresa/usuarios')}}">Usuarios</a></li>
                            <li><a href="{{asset('/empresa/ofertas')}}">Ofertas</a></li>
                            <li><a href="{{asset('/empresa/hojasdevida')}}">Hojas de vida</a></li>
                            <li><a href="{{asset('/empresa/convenio')}}">Convenio</a></li>
                            
                            @endif
                            
                            @if(Auth::user()->getrol->nombre=='Estudiante')
                            <li><a href="{{asset('/estudiante')}}">Inicio</a></li>
                            <li><a href="{{asset('/estudiante/hojadevida')}}">Hoja de vida</a></li>
                                @if($estudiante->gettipo->nombre == 'Prácticas')
                                    @if(sizeof($estudiante->getmodalidades)>0)
                                        @if($estudiante->getmodalidades[ sizeof($estudiante->getmodalidades) - 1]->nombre == 'Vinculación laboral')
                                            <li><a href="{{asset('/estudiante/ofertas')}}">Ofertas</a></li>
                                        @endif
                                    @endif
                                    <li><a href="{{asset('/estudiante/practicas')}}">Prácticas</a></li>
                                @endif
                                @if($estudiante->gettipo->nombre == 'Preprácticas' || $estudiante->gettipo->nombre == 'Prácticas y preprácticas')
                                    <li class="dropdown">
                                        <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                                          Preprácticas <b class="caret"></b>
                                        </a>
                                        <ul class="dropdown-menu">
                                            <li><a href="{{asset('/estudiante/conferencias')}}">Conferencias</a></li>
                                            <li><a href="{{asset('/estudiante/horario')}}">Horario</a></li>
                                        </ul>
                                    </li>
                                @endif                                
                                <li><a href="{{asset('/estudiante/cartas')}}">Carta de presentación</a></li>
                                <li><a href="{{asset('/novedad')}}">Novedades</a></li>
                            @endif

                            @if(Auth::user()->getrol->nombre=='Graduado')
                                <li><a href="{{asset('/graduado')}}">Inicio</a></li>
                                <li><a href="{{asset('/graduado/hojavida')}}">Hoja de vida</a></li>
                                <li><a href="{{asset('/graduado/ofertas')}}">Ofertas</a></li>
                                <li><a href="{{asset('/novedad')}}">Novedades</a></li>
                                <li><a href="{{asset('/graduado/reporte')}}">Reporde de postulaciones</a></li>
                                <li><a href="{{asset('/graduado/config')}}">Configuraciones</a></li>                                
                            @endif
                            
                            @if(Auth::user()->getrol->nombre=='Jefe inmediato')
                            <li><a href="{{asset('/jefe')}}">Practicantes</a></li>
                            <li><a href="{{asset('/novedad')}}">Novedades</a></li>
                            @endif
                            
                            @if(Auth::user()->getrol->nombre=='Tutor')
                            <li><a href="{{asset('/tutor')}}">Practicantes</a></li>
                            <li><a href="{{asset('/novedad')}}">Novedades</a></li>
                            @endif
                            
                            @if(Auth::user()->getrol->nombre=='Coordinador')
                            <li class="dropdown">
                                <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                                  Estudiantes <b class="caret"></b>
                                </a>
                                <ul class="dropdown-menu">
                                    <li><a href="{{asset('/cdn/prepracticas')}}">Pre-prácticas</a></li>
                                    <li><a href="{{asset('/cdn/solicitantes')}}">Solicitudes de prácticas</a></li>
                                    <li><a href="{{asset('/cdn/actas')}}">Aprobar prácticas</a></li>
                                    <li><a href="{{asset('/cdn/practicantes')}}">Practicantes</a></li>
                                    
                                    
                                </ul>
                            </li>
                            <li><a href="{{asset('/cdn/charlas')}}">Pre-prácticas</a></li>
                            <li><a href="{{asset('/cdn/cartas')}}">Cartas de presentación</a></li>
                            
                            <li><a href="{{asset('/cdn/convenios')}}">Convenios</a></li>
                            
                            
                            <li><a href="{{asset('/cdn/ofertas')}}">Ofertas</a></li>
                            <li><a href="{{asset('/admin/correomasivo')}}">Envío de correo</a></li>
                            
                            
                            @endif
                            
                            @if(Auth::user()->getrol->nombre=='Administrador Dippro' || Auth::user()->getrol->nombre=='Coordinador')
                            <li class="dropdown">
                                <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                                  Indicadores <b class="caret"></b>
                                </a>
                                <ul class="dropdown-menu">
                                    <li><a href="{{asset('/admin/exterior')}}">Estudiantes en el exterior</a></li>
                                    <li><a href="{{asset('/admin/vinculacion')}}">Vinculación laboral</a></li>
                                    <li><a href="{{asset('/admin/fsantamarta')}}">Prácticas nacionales</a></li>
                                    <li><a href="{{asset('/admin/ubicacion')}}">Ubicación practicantes</a></li>
                                    <li><a href="{{asset('/admin/laborando')}}">Estudiantes que quedaron laborando</a></li>
                                    <li><a href="{{asset('/admin/impacto')}}">Proyectos de impacto</a></li>
                                </ul>
                            </li>
                            @endif
                        </ul>
                    </div>
                </div>
                <div class="col-md-1"></div>
              <!-- El logotipo y el icono que despliega el menú se agrupan
                   para mostrarlos mejor en los dispositivos móviles -->
            </div>
            @endif
            @if(!Auth::check())
            <div class="row">
                <div class="col-md-1"></div>
                <div class="col-md-10">
                    <div class="navbar-header">
                        <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-ex1-collapse">
                            <span class="sr-only">Desplegar navegación</span>
                            <span class="icon-bar"></span>
                            <span class="icon-bar"></span>
                            <span class="icon-bar"></span>
                        </button>
                        <!--<a class="navbar-brand" href="#">Logotipo</a>-->
                    </div>
             
                      <!-- Agrupar los enlaces de navegación, los formularios y cualquier
                           otro elemento que se pueda ocultar al minimizar la barra -->
                    <div class="collapse navbar-collapse navbar-ex1-collapse">
                        <ul class="nav navbar-nav" style="width:100%;"> 
                            <li style="width:19%;" class="text-center"><a href="{{asset('/home')}}">Inicio</a></li>
                            <li style="width:19%;" class="text-center"><a href="{{asset('/home/normatividad')}}">Normatividad</a></li>
                            <li style="width:19%;" class="text-center"><a href="{{asset('/home/institucionalidad')}}">Institucionalidad</a></li>
                            <li style="width:19%;" class="text-center"><a href="{{asset('/home/inscribete')}}">¿Por qué inscribirse?</a></li>
                            <li style="width:19%;" class="text-center"><a href="{{asset('/home/contacto')}}">Contáctenos</a></li>
                        </ul>
                    </div>
                </div>
                <div class="col-md-1"></div>
              <!-- El logotipo y el icono que despliega el menú se agrupan
                   para mostrarlos mejor en los dispositivos móviles -->
            </div>
            @endif
                
          
        </nav>
        
        
        <section class="container">
            <div class="row" >
    
                <div class="col-md-1"></div>
                <div class="col-md-10">
                    @yield('contenido')
                    <center>
                        Vinculado a la red de prestadores del Servicio Público de Empleo. Autorizado por la Resolución N° 687 de agosto 22 de 2017.
                        <br>
                        <br>
                        <img src="{{asset('/img/spe/serviciomin.png')}}">
                    </center>
                </div>
                <div class="col-md-1"></div>
                
                
                
            </div>
        </section>
        <div id="cargando" class="modal fade" role="dialog">
            <div class="modal-dialog" style="margin-top:300px;">
                <div layout="row" layout-sm="column" layout-align="space-around">
                    <md-progress-circular md-mode="indeterminate"></md-progress-circular>
                </div>
            </div>
        </div>
        
        <footer class="footer">
            <div class="row" style="height:20px; background:#00223e;">
                
            </div>
            <div class="row">
                <div >
                    <div class="col-md-1"></div>
                    <div class="col-md-10">
                        <!-- <div class="col-md-6">
                            Centro de Egresados <br>
                            Cra. 32 #22-08 - Edf. V piso 2, sede principal <br>
                            4217940 Ext.3150-3275 | egresados@unimagdalena.edu.co <br>
                            <a href="#">egresados.unimagdalena.edu.co</a> 
                        </div> -->
                        <div class="col-md-12 text-center">
                            <img src="{{asset('/img/footer/new_footer.png')}}"></img>
                        </div>
                        <!-- <div class="col-md-1">
                            
                        </div> -->
                    </div>
                    <div class="col-md-1"></div>
                </div>
            </div>
        </footer>
    </body>
</html>