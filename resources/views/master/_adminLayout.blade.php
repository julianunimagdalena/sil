<!DOCTYPE html>

<html>
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="root" content="{{ Request::root() }}">
    <title>@yield('titulo') Universidad del Magdalena</title>
    <link rel="shortcut icon" href="{{asset('/img/favicon.ico')}}" type="image/x-icon">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.6-rc.0/css/select2.min.css" rel="stylesheet" />
    <link rel='icon' sizes='192x192' href="{{asset('/img/Escudo.png')}}">
    <link href="https://fonts.googleapis.com/css?family=Roboto" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css?family=Open+Sans" rel="stylesheet">
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
    <link href="{{asset('/css/bootstrap.min.css')}}" rel="stylesheet" type="text/css" />
    <link href="{{asset('/font-awesome/css/font-awesome.min.css')}}" rel="stylesheet" type="text/css">
    <link href="{{asset('/fonts/glyphicons-halflings-regular.ttf')}}" rel="stylesheet" type="text/css" />
    <link href="{{asset('/ionicons/css/ionicons.min.css')}}" rel="stylesheet" type="text/css" />
    <link rel="stylesheet" href="{{asset('/sweetalert/dist/sweetalert.css')}}" type="text/css" />
    <link href="{{asset('/css/ADM-dateTimePicker.min.css')}}" rel="stylesheet" type="text/css" />
    <link href="{{asset('/table/object-table-style.css')}}" rel="stylesheet" type="text/css" />
    <link href="{{asset('/css/select.min.css')}}" rel="stylesheet" type="text/css" />
    <link rel="stylesheet" href="{{asset('/css/angular-material.min.css')}}" type="text/css" />
    <link rel="stylesheet" href="{{asset('/css/style.css')}}" type="text/css" />
    <link href="{{asset('/css/bootstrap-select.min.css')}}" rel="stylesheet" type="text/css" />
    <link href="{{asset('/css/customAdmin.css')}}" rel="stylesheet" type="text/css" />
    <link href="{{asset('/css/customStyle2.css')}}" rel="stylesheet" type="text/css" />
    <link href="{{asset('/css/customStyle3.css')}}" rel="stylesheet" type="text/css" />
    <link href="{{asset('/css/customStyle4.css')}}" rel="stylesheet" type="text/css" />
    <link href="{{asset('/css/bootstrap-tagsinput.css')}}" rel="stylesheet" type="text/css" />

   
    <link href="{{asset('/fonts/glyphicons-halflings-regular.ttf')}}" rel="stylesheet" type="text/css" />

    
    <style>
        .login-error{
            font-size: 0.8em;
            font-weight: 600;
            text-align: center;
            display: block;
            background-color: lightgray;
            color: #a94442;
            
        }
        .login-error:not(:empty) {
            padding: .2em;
        }
    </style>
    @yield('estilos')
    <style type="text/css">
        a.md-default-theme:not(.md-button), a:not(.md-button) {
            color: #1791E2 !important;
        }

        .pagination>.active>a {
            color: #fff !important;
        }
    </style>
</head>
<body ng-app="dipro" @yield('controller') style="height: auto;">
    
    <div class="loadingContent" aria-hidden="true">
        <div class="loader"></div>
        <span>Cargando. Por favor espere...</span>
    </div>
    <header>
        @include('master._Toolbar')
        <div class="banner">
            <a href="">
                <div class="brand-main">
                    <img src="https://cdn.unimagdalena.edu.co/images/escudo/bg_dark/512.png" />
                    <h1>Sistema Intermediación Laboral</h1>
                </div>
            </a>
            
            <div class="title align-items-center" style="margin-left: 10px; margin-top: 10px;">
               @yield('title')
            </div>
        </div>

    </header>
     <div class="content">
        <div class="left">
            @yield('left')
            <div class="sideLeft">
            <aside class="aside">
                <div class="aside-header">
                    <img class="aside-img" src="{{asset('/img/logoEgresados.png')}}" alt="">
                    <h3 class="aside-heading" style="font-size: 15px;">
                        Bienvenido al Sistema de Intermediación Laboral
                    </h3>
                     <h3 class="aside-heading">
                        <span class="icon">
                            <i class="fa fa-user-circle-o fa-2x"></i></span>
                        <span class="text">{{Auth::user()->identificacion}}</span>
                    </h3>
                </div>
                <div class="aside-body">
                    <div class="aside-bodyDesc">
                    <ul class="aside-list">
                        @if(Auth::check())
                            @if(Auth::user()->getrol->nombre=='Administrador Egresados')
                                <li>
                                    <span class="aside-listIcon col-sm-2"><i class="fa fa-home fa-lg"></i></span>
                                        <a class="col-sm-10" href="{{asset('/sil/index')}}">Inicio</a>
                                </li>
                                <li>
                                    <span class="aside-listIcon col-sm-2"><i class="fa fa-building fa-lg"></i></span>
                                        <a class="col-sm-10" href="{{asset('/adminsil/empresas')}}">Empresas</a>
                                </li>
                                <li>
                                    <span class="aside-listIcon col-sm-2"><i class="fa fa-briefcase fa-lg"></i></span>
                                        <a class="col-sm-10" href="{{asset('/adminsil/ofertas')}}">Ofertas</a>
                                    </li>
                                <li>
                                    <span class="aside-listIcon col-sm-2"><i class="fa fa-id-card-o fa-lg"></i></span>
                                        <a class="col-sm-10" href="{{asset('/sil/hojasdevida')}}">Hojas de vida</a>
                                </li>
                                <li>
                                    <span class="aside-listIcon col-sm-2"><i class="fa fa-line-chart fa-lg"></i></span>
                                        <a class="col-sm-10" href="{{asset('/adminsil/indicadores')}}">Indicadores</a>
                                </li>
                                {{-- <li>
                                    <span class="aside-listIcon col-sm-2"><i class="fa fa-key fa-lg"></i></span>
                                    <a href="{{asset('/home/cambiarclave')}}">Cambiar contraseña</a>
                                </li>    --}}   
                                <li>
                                    <span class="aside-listIcon col-sm-2"><i class="fa fa-comments fa-lg"></i></span>
                                        <a class="col-sm-10" href="{{asset('/novedad')}}">Novedades</a>
                                </li>
                            @endif  
                            @if(Auth::user()->getrol->nombre=='Empresa')
                                @if(false)
                                <li><a href="{{asset('/empresa/usuarios')}}">Usuarios</a></li>
                                <li><a href="{{asset('/empresa/convenio')}}">Convenio</a></li>
                            @endif
                                <li>
                                    <span class="aside-listIcon col-sm-2"><i class="fa fa-home fa-lg"></i></span>
                                        <a class="col-sm-10" href="{{asset('/empresa')}}">Inicio</a>
                                </li>
                                <!-- <li>
                                    <span class="aside-listIcon col-sm-2"><i class="fa fa-comments fa-lg"></i></span>
                                    <a href="{{asset('/empresa/ofertas')}}">Ofertas</a>
                                </li> -->
                                <li>
                                    <span class="aside-listIcon col-sm-2"><i class="fa fa-id-card-o fa-lg"></i></span>
                                    <a href="{{asset('/empresa/hojasdevida')}}">Hojas de vida</a>
                                </li>
                                <li>
                                    <span class="aside-listIcon col-sm-2"><i class="fa fa-key fa-lg"></i></span>
                                    <a href="{{asset('/home/cambiarclave')}}">Cambiar contraseña</a>
                                </li>      
                                <li>
                                    <span class="aside-listIcon col-sm-2"><i class="fa fa-comments fa-lg"></i></span>
                                    <a href="{{asset('/novedad')}}">Contactenos</a>
                                </li>
                            @endif
                            @if(Auth::user()->getrol->nombre=='Graduado')
                                <li>
                                    <span class="aside-listIcon col-sm-2"><i class="fa fa-home fa-lg"></i></span>
                                    <a href="{{asset('/graduado')}}">Inicio</a>
                                </li>
                                <li>
                                    <span class="aside-listIcon col-sm-2"><i class="fa fa-id-card-o fa-lg"></i></span>
                                    <a href="{{asset('/graduado/hojavida')}}">Hoja de vida</a>
                                </li>
                                <!-- <li>
                                    <span class="aside-listIcon col-sm-2"><i class="fa fa-briefcase fa-lg"></i></span>
                                    <a href="{{asset('/graduado/ofertas')}}">Ofertas</a>
                                </li> -->
                                <li>
                                    <span class="aside-listIcon col-sm-2"><i class="fa fa-comments fa-lg"></i></span>
                                    <a href="{{asset('/novedad')}}">Novedades</a>
                                </li>
                                @if(false)
                                <li>
                                    <a href="{{asset('/graduado/reporte')}}">Reporde de postulaciones</a>
                                </li>
                                @endif
                                <li>
                                    <span class="aside-listIcon col-sm-2"><i class="fa fa-cog fa-lg"></i></span>
                                    <a href="{{asset('/graduado/config')}}">Configuraciones</a>
                                </li>
                                <li>
                                    <span class="aside-listIcon col-sm-2"><i class="fa fa-key fa-lg"></i></span>
                                    <a href="{{asset('/home/cambiarclave')}}">Cambiar contraseña</a>
                                </li>                                
                            @endif
                                <li>
                                    <span class="aside-listIcon col-sm-2"><i class="fa fa-sign-in fa-lg"></i></span>
                                       <a class="col-sm-10" href="{{asset('/auth/logout')}}">Cerrar sesión</a>
                                </li>
                
                        @endif        
                    </ul>
                </div>
            </aside>
        </div>
        </div>
        <div class="center">
            <div class="panel">
                <div class="panel-body">
                    @yield('content')
                </div>
            </div>
        </div>
        <div class="right">
            @yield('right')
        </div>
    </div>
    <div class="page">
        
   <!--      <div class="container">
            
            
           
        </div -->>
        
    </div>

    <div id="processing">
        <h6>Procesando. Por favor espere...</h6>
        <div class="progress">
            <div class="progress-bar progress-bar-info progress-bar-striped active" role="progressbar" aria-valuenow="100" aria-valuemin="0" aria-valuemax="100" style="width: 100%">
                <span class="sr-only">100% Complete</span>
            </div>
        </div>
    </div>
    
<!--     <footer>

    </footer>
     -->
    <script src="{{asset('/js/jquery.min.js')}}" type="text/javascript"></script>
    <script type="text/javascript" src="{{asset('/js/scriptJQ.js')}}"></script>
    <script src="{{asset('/js/angular.min.js')}}" type="text/javascript"></script>
    <script src="{{asset('/js/controller.js')}}" type="text/javascript"></script>
    <script src="{{asset('/js/angular-locale_es-es.js')}}"></script>
    <script src="{{asset('/js/angular-input-masks-standalone.min.js')}}"></script>
    <script src="{{asset('/ckeditor/ckeditor.js')}}" type="text/javascript"></script>
    <script src="{{asset('/angular-ckeditor/angular-ckeditor.js')}}"></script>
    <script src="{{asset('/js/moment.min.js')}}" type="text/javascript"></script>
    <script src="{{asset('/js/moment-with-locales.js')}}"></script>
    <script src="{{asset('/sweetalert/dist/sweetalert.min.js')}}" type="text/javascript"></script>
    <script src="{{asset('/table/object-table.js')}}" type="text/javascript"></script>
    
    <script type="text/javascript" src="{{asset('/js/angular-material.min.js')}}"></script>
    <script type="text/javascript" src="{{asset('/js/angular-animate.min.js')}}"></script>
    <script type="text/javascript" src="{{asset('/js/angular-aria.min.js')}}"></script>
    <script type="text/javascript" src="{{asset('/js/angular-messages.min.js')}}"></script>
    <script src="{{asset('/bootstrap-gh-pages/ui-bootstrap-tpls-2.0.2.min.js')}}"></script>
    <script src="{{asset('/js/bootstrap.min.js')}}" type="text/javascript"></script>
    <script src="{{asset('/js/selectize.min.js')}}" type="text/javascript"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.6-rc.0/js/select2.min.js"></script>
    <script src="{{asset('/js/angular-sanitize.js')}}" type="text/javascript"></script>
    <script src="{{asset('/js/bootstrap-select.min.js')}}"  type="text/javascript"></script>
    <script src="{{asset('/js/select.min.js')}}" type="text/javascript"></script>
    <script src="{{asset('/js/layoutScript.js')}}" type="text/javascript"></script>
    <script src="{{asset('/js/ADM-dateTimePicker.min.js')}}" type="text/javascript"></script>
    <script src="{{asset('/js/dirPagination.js')}}" type="text/javascript"></script>
    <script src="{{asset('/js/checklist-model.js')}}" type="text/javascript"></script>
    <script src="{{asset('/js/ng-ckeditor.js')}}" type="text/javascript"></script>
    <script src="{{asset('/js/bootstrap-tagsinput.min.js')}}" type="text/javascript"></script>
    
    @yield('scripts')
    
    

    <script>
        $(document).ready(function () {
            
            $(function () {
                $('[data-toggle="tooltip"]').tooltip({ container: 'body' });
            })

        });
        document.addEventListener("DOMContentLoaded", function (event) {
            $('.loadingContent').delay(1000).fadeOut("fast");
        });
    </script>
    <script>
        $('.regresar').on('click', function (e) {
            window.history.back();
        })
        
    </script>
    @yield('javascript')
</body>
</html>