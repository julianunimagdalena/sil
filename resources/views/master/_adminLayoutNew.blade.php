<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">
    <meta name="root" content="{{ Request::root() }}">
    <title>@yield('titulo') Universidad del Magdalena</title>
    
    <link rel="shortcut icon" href="https://cdn.unimagdalena.edu.co/images/favicon.ico" type="image/x-icon">
    <link rel="icon" href="https://cdn.unimagdalena.edu.co/images/favicon.ico" type="image/x-icon">
    <link href="https://cdn.unimagdalena.edu.co/code/css/normalize.min.css" rel="stylesheet" />
    <link href="https://code.ionicframework.com/ionicons/2.0.1/css/ionicons.min.css" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdn.unimagdalena.edu.co/code/css/public.min.css" type="text/css" />
    <link href="https://cdn.unimagdalena.edu.co/code/css/public_768.min.css" rel="stylesheet" media="(min-width: 768px)">
    <link href="https://cdn.unimagdalena.edu.co/code/css/public_992.min.css" rel="stylesheet" media="(min-width: 992px)">
    <link href="https://cdn.unimagdalena.edu.co/code/css/public_1200.min.css" rel="stylesheet" media="(min-width: 1200px)">
    <link href="https://cdn.unimagdalena.edu.co/code/css/public_1600.min.css" rel="stylesheet" media="(min-width: 1600px)">
    <link href="https://fonts.googleapis.com/css?family=Open+Sans:400,700|Roboto:400,700" rel="stylesheet">

    <!-- Custom fonts for this template-->
    <link href="{{ asset('page-admin/vendor/fontawesome-free/css/all.min.css') }}" rel="stylesheet" type="text/css">
    <link href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i" rel="stylesheet">
    <!-- Custom styles for this template-->
    

    {{-- <link href="{{asset('/css/customAdmin.css')}}" rel="stylesheet" type="text/css" />
    <link href="{{asset('/css/customStyle2.css')}}" rel="stylesheet" type="text/css" />
    <link href="{{asset('/css/customStyle3.css')}}" rel="stylesheet" type="text/css" />
    <link href="{{asset('/css/customStyle4.css')}}" rel="stylesheet" type="text/css" /> --}}

    <link href="{{asset('/table/object-table-style.css')}}" rel="stylesheet" type="text/css" />
    <link rel="stylesheet" href="{{asset('/css/angular-material.min.css')}}" type="text/css" />
    <link href="{{asset('/css/select.min.css')}}" rel="stylesheet" type="text/css" />
    {{-- <link href="{{asset('/css/bootstrap-select.min.css')}}" rel="stylesheet" type="text/css" /> --}}
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-select@1.13.7/dist/css/bootstrap-select.min.css">
    <link href="{{asset('/css/bootstrap-tagsinput.css')}}" rel="stylesheet" type="text/css" />
    <link rel="stylesheet" href="{{asset('/sweetalert/dist/sweetalert.css')}}" type="text/css" />

    <link href="{{ asset('css/sb-admin-2.min.css') }}" rel="stylesheet">

    

    
    {{-- <link href="{{asset('/css/ADM-dateTimePicker.min.css')}}" rel="stylesheet" type="text/css" /> --}}
    {{-- <link href="{{asset('/css/select.min.css')}}" rel="stylesheet" type="text/css" /> --}}
    {{-- <link rel="stylesheet" href="{{asset('/css/angular-material.min.css')}}" type="text/css" /> --}}
    {{-- <link rel="stylesheet" href="{{asset('/css/style.css')}}" type="text/css" /> --}}
    {{-- <link href="{{asset('/css/customAdmin.css')}}" rel="stylesheet" type="text/css" /> --}}
    {{-- <link href="{{asset('/css/customStyle2.css')}}" rel="stylesheet" type="text/css" /> --}}
    {{-- <link href="{{asset('/css/customStyle3.css')}}" rel="stylesheet" type="text/css" /> --}}
    {{-- <link href="{{asset('/css/customStyle4.css')}}" rel="stylesheet" type="text/css" /> --}}
    <style>
        .icono {
            color: white;
        }

        .table-responsive {
            display: table;
        }

        table {
            font-size: 0.79em;
            text-align: center;
        }

        table * {
            padding: 5px !important;
            vertical-align: middle !important;
        }

        .icono:hover {
            color: #004A87;
        }

        form label {
            font-size: 0.9em;
            font-weight: 800;
        }

        form legend {
            border-bottom: 1px solid #e5e5e5;
        }

        .modal-header {
            background-color: #004a87;
            color: white;
        }

        .modal-header span {
            color: white;
        }

        a.dropdown-item, a.dropdown-item:hover {
            color: black;
        }

        a.dropdown-item:active {
            color: white;
        }

        i.fas.fa-angle-up {
            color: white;
        }

        div.md-list-item-text h3 {
            font-weight: 800 !important;
        }

        .pagination {
            margin: 10px 10px !important;
            display: inline-block;
            padding-left: 0;
            border-radius: 4px;
        }

        .pagination a {
            color: #1791E2 !important;

        }

        .pagination>li {
            display: inline;
        }

        .pagination>li:first-child>a {
            margin-left: 0;
            border-top-left-radius: 4px;
            border-bottom-left-radius: 4px;
            cursor: pointer;
            background-color: #fff;
            border-color: #ddd;
            position: relative;
            float: left;
            padding: 6px 12px;
            line-height: 1.42857143;
            text-decoration: none;
            border: 1px solid #ddd;
        }

        ul.pagination li.active a, ul.pagination li.active a:hover, ul.pagination li.active a:focus {
            background-color: #01579B;
        }

        .pagination>.active>a, .pagination>.active>a:focus, .pagination>.active>a:hover, .pagination>.active>span, .pagination>.active>span:focus, .pagination>.active>span:hover {
            z-index: 3;
            cursor: default;
            border-color: #337ab7;
        }

        .pagination>li>a, .pagination>li>span {
            position: relative;
            float: left;
            padding: 6px 12px;
            margin-left: -1px;
            line-height: 1.42857143;
            border: 1px solid #ddd;
            text-decoration: none;
        }
        .pagination>.active>a {
            color: #fff !important;
        }

        div#logoutModal a.btn-primary:hover {
            color: #fff !important;
        }

        .card-body {
            color: black !important;
        }
    </style>

    @yield('estilos')
</head>

<body id="page-top" style="color: black;" ng-app="dipro" ng-init="genero='{{ Auth::user()->getuser->getgenero ? Auth::user()->getuser->getgenero->nombre:'MASCULINO' }}'" @yield('controller')>

    {{-- CARGANDO --}}
    <div class="loadingContent" aria-hidden="true">
        <div class="loader"></div>
        <span>Cargando. Por favor espere...</span>
    </div>

    <div class="sidebar-brand-text mx-3 text-nowrap d-none d-md-block" style="position: absolute; left: 57px; text-transform: uppercase; letter-spacing: .05rem; font-weight: 800; color: white; top: 11px; z-index: 99;">
        <img src="{{ asset('img/logo.png') }}" alt="Logo Universidad del Magdalena" width="50">
        Sistema de Intermediacion Laboral
    </div>

    <!-- Page Wrapper -->
    <div id="wrapper">
        <!-- Sidebar -->
        <ul class="navbar-nav bg-gradient-primary sidebar sidebar-dark accordion" id="accordionSidebar" style="background-color: #004A87; background-image: none;">
            <!-- Sidebar - Brand -->
            <a class="sidebar-brand d-flex align-items-center" style="color: white;" href="">
                <div class="sidebar-brand-icon">
                    <!-- <i class="fas fa-laugh-wink"></i> -->
                    <img alt="Logo Unimagdalena" src="{{asset('img/sil.png')}}" width="50">
                </div>
                {{-- <div class="sidebar-brand-text mx-3 text-nowrap" style="position: absolute; left: 65px;">Sistema de Intermediacion Laboral</div> --}}
            </a>
       
            <!-- Divider -->
            <hr class="sidebar-divider my-0">
            <!-- Nav Item - Dashboard -->
            <li class="nav-item active">
                @if(session('rol')->nombre=='Administrador Egresados')
                    <a class="nav-link" href="{{asset('/sil/index')}}">
                        <i class="fas fa-fw fa-home"></i>
                        <span>Inicio</span>
                    </a>
                @endif
                @if(session('rol')->nombre=='Graduado')
                    <a class="nav-link" href="{{asset('/graduado')}}">
                        <i class="fas fa-fw fa-home"></i>
                        <span>Inicio</span>
                    </a>
                @endif
                @if(session('rol')->nombre=='Empresa')
                    <a class="nav-link" href="{{asset('/empresa')}}">
                        <i class="fas fa-fw fa-home"></i>
                        <span>Inicio</span>
                    </a>
                @endif
            </li>
            <!-- Divider -->
            <hr class="sidebar-divider">
            <!-- Heading -->
            <div class="sidebar-heading">
                Opciones
            </div>
            <!-- Nav Item - Pages Collapse Menu -->
            <li class="nav-item">
                @if(session('rol')->nombre=='Administrador Egresados')
                    <a id="link-usuarios" class="nav-link" href="{{asset('/sil/index')}}">
                        <i class="fas fa-fw fa-users"></i>
                        <span>Datos de usuarios</span>
                    </a>
                    <a id="link-empresas" class="nav-link" href="{{asset('/adminsil/empresas')}}">
                        <i class="fas fa-fw fa-building"></i>
                        <span>Empresas</span>
                    </a>
                    <a id="link-ofertas" class="nav-link" href="{{asset('/adminsil/ofertas')}}">
                        <i class="fas fa-fw fa-briefcase"></i>
                        <span>Ofertas</span>
                    </a>
                    <a id="link-hojasvida" class="nav-link" href="{{asset('/sil/hojasdevida')}}">
                        <i class="far fa-fw fa-id-card"></i>
                        <span>Hojas de vida</span>
                    </a>
                    <a id="link-indicadores" class="nav-link" href="{{asset('/adminsil/indicadores')}}">
                        <i class="fas fa-fw fa-chart-line"></i>
                        <span>Indicadores</span>
                    </a>
                @endif
                @if(session('rol')->nombre=='Graduado')
                    <a id="link-ofertas" class="nav-link" href="{{asset('/graduado')}}">
                        <i class="fas fa-fw fa-suitcase"></i>
                        <span>Ofertas</span>
                    </a>
                    <a id="link-hojavida" class="nav-link" href="{{asset('/graduado/hojavida')}}">
                        <i class="fas fa-fw fa-edit"></i>
                        <span>Hoja de vida</span>
                    </a>
                    <a id="link-config" class="nav-link" href="{{asset('/graduado/config')}}">
                        <i class="fas fa-fw fa-cog"></i>
                        <span>Configuracion de la cuenta</span>
                    </a>
                    {{-- <a id="link-clave" class="nav-link" href="{{asset('/home/cambiarclave')}}">
                        <i class="fas fa-fw fa-key"></i>
                        <span>Cambiar contraseña</span>
                    </a> --}}
                @endif
                @if(session('rol')->nombre=='Empresa')
                    <a id="link-ofertas" class="nav-link" href="{{asset('/empresa')}}">
                        <i class="fas fa-fw fa-suitcase"></i>
                        <span>Ofertas</span>
                    </a>
                    <a id="link-hojasvida" class="nav-link" href="{{asset('/empresa/hojasdevida')}}">
                        <i class="fas fa-fw fa-edit"></i>
                        <span>Hojas de vida</span>
                    </a>
                    <a id="link-clave" class="nav-link" href="{{asset('/home/cambiarclave')}}">
                        <i class="fas fa-fw fa-key"></i>
                        <span>Cambiar contraseña</span>
                    </a>
                
                @endif

                <hr class="sidebar-divider">
                <!-- Heading -->
                <div class="sidebar-heading">
                    Novedades
                </div>
                <a id="link-novedad" class="nav-link" href="{{asset('/novedad')}}">
                    <i class="fas fa-fw fa-comments"></i>
                    <span>Novedades</span>
                </a>
                <!-- <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapseTwo" aria-expanded="true" aria-controls="collapseTwo">
                    <i class="fas fa-fw fa-cog"></i>
                    <span>Components</span>
                </a>
                <div id="collapseTwo" class="collapse" aria-labelledby="headingTwo" data-parent="#accordionSidebar">
                    <div class="bg-white py-2 collapse-inner rounded">
                        <h6 class="collapse-header">Custom Components:</h6>
                        <a class="collapse-item" href="buttons.html">Buttons</a>
                        <a class="collapse-item" href="cards.html">Cards</a>
                    </div>
                </div> -->
            </li>
            <!-- Nav Item - Utilities Collapse Menu -->
            <!-- <li class="nav-item">
                <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapseUtilities" aria-expanded="true" aria-controls="collapseUtilities">
                    <i class="fas fa-fw fa-wrench"></i>
                    <span>Utilities</span>
                </a>
                <div id="collapseUtilities" class="collapse" aria-labelledby="headingUtilities" data-parent="#accordionSidebar">
                    <div class="bg-white py-2 collapse-inner rounded">
                        <h6 class="collapse-header">Custom Utilities:</h6>
                        <a class="collapse-item" href="utilities-color.html">Colors</a>
                        <a class="collapse-item" href="utilities-border.html">Borders</a>
                        <a class="collapse-item" href="utilities-animation.html">Animations</a>
                        <a class="collapse-item" href="utilities-other.html">Other</a>
                    </div>
                </div>
            </li> -->
            <!-- Divider -->
            <hr class="sidebar-divider d-none d-md-block">
            <!-- Sidebar Toggler (Sidebar) -->
            <div class="text-center d-none d-md-inline">
                <button class="rounded-circle border-0" id="sidebarToggle"></button>
            </div>
        </ul>
        <!-- End of Sidebar -->
        <!-- Content Wrapper -->
        <div id="content-wrapper" class="d-flex flex-column" style="background-color: #ffff;">
            <!-- Main Content -->
            <div id="content">
                <!-- Topbar -->
                <nav class="navbar navbar-expand navbar-light bg-white topbar mb-4 static-top shadow" style="background-color: #004A87 !important; border-bottom: 3px solid #D17900; border-radius: 0px;">
                    <!-- Sidebar Toggle (Topbar) -->
                    <button id="sidebarToggleTop" class="btn btn-link d-md-none rounded-circle mr-3 icono">
                        <i class="fa fa-bars"></i>
                    </button>
                    <!-- Topbar Navbar -->
                    {{-- <p style="font-weight: 800; color: white; margin-bottom: 0; position: absolute; left: 0;">SISTEMA INTERMEDIACION LABORAL</p> --}}
                    <ul class="navbar-nav ml-auto">
                        <!-- Nav Item - Search Dropdown (Visible Only XS) -->
                        <li class="nav-item dropdown no-arrow d-sm-none">
                            <a class="nav-link dropdown-toggle" href="#" id="searchDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                <i class="fas fa-search fa-fw"></i>
                            </a>
                            <!-- Dropdown - Messages -->
                            <div class="dropdown-menu dropdown-menu-right p-3 shadow animated--grow-in" aria-labelledby="searchDropdown">
                                <form class="form-inline mr-auto w-100 navbar-search">
                                    <div class="input-group">
                                        <input type="text" class="form-control bg-light border-0 small" placeholder="Search for..." aria-label="Search" aria-describedby="basic-addon2">
                                        <div class="input-group-append">
                                            <button class="btn btn-primary" type="button">
                                                <i class="fas fa-search fa-sm"></i>
                                            </button>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </li>
                        <li class="nav-item dropdown no-arrow mx-1">
                            <a class="nav-link dropdown-toggle" href="#" id="alertsDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                @yield('tituloVista')
                            </a>
                        </li>
                        <div class="topbar-divider d-none d-sm-block"></div>
                        <!-- Nav Item - User Information -->
                        <li class="nav-item dropdown no-arrow">
                            <a class="nav-link dropdown-toggle" href="#" id="userDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                <span class="mr-2 d-none d-lg-inline text-gray-600 small" style="color: #d1d3e2 !important;">{{ session('rol')->nombre == 'Empresa' ? Auth::user()->getsede->getempresa->nombre : Auth::user()->getuser->nombres.' '.Auth::user()->getuser->apellidos}}</span>
                                <i class="fas fa-user"></i>
                            </a>
                            <!-- Dropdown - User Information -->
                            <div class="dropdown-menu dropdown-menu-right shadow animated--grow-in" aria-labelledby="userDropdown">
                                <a class="dropdown-item" href="#" data-toggle="modal" data-target="#logoutModal">
                                    <i class="fas fa-sign-out-alt fa-sm fa-fw mr-2 text-gray-400"></i>
                                    Cerrar sesion
                                </a>
                            </div>
                        </li>
                    </ul>
                </nav>
                <!-- End of Topbar -->
                <!-- Begin Page Content -->
                <div class="container-fluid">
                    @yield('content')
                </div>
                <!-- /.container-fluid -->
            </div>
            <!-- End of Main Content -->
            <!-- Footer -->
            <footer class="sticky-footer bg-white" style="border-top: none; margin-top: 20px; background-color: #ddd !important; color: #888; padding: 15px 0;">
                <div class="container my-auto">
                    <div class="copyright text-center my-auto">
                        <span>Centro de Egresados - Unimagdalena 2019</span>
                    </div>
                </div>
            </footer>
            <!-- End of Footer -->
        </div>
        <!-- End of Content Wrapper -->
    </div>
    <!-- End of Page Wrapper -->
    <!-- Scroll to Top Button-->
    <a class="scroll-to-top rounded" href="#page-top">
        <i class="fas fa-angle-up"></i>
    </a>
    <!-- Logout Modal-->
    <div class="modal fade" id="logoutModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">¿Seguro qué desea cerrar sesion?</h5>
                    <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
                <div class="modal-body">Selecciona "Cerrar sesion" si deseas salir del aplicativo.</div>
                <div class="modal-footer">
                    <button class="btn btn-secondary" type="button" data-dismiss="modal">Cancelar</button>
                    <a class="btn btn-primary" href="{{asset('/auth/logout')}}">Cerrar sesion</a>
                </div>
            </div>
        </div>
    </div>

    <!-- Bootstrap core JavaScript-->
    <script src="{{ asset('page-admin/vendor/jquery/jquery.min.js') }}"></script>
    <script src="{{ asset('page-admin/vendor/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
    <!-- Core plugin JavaScript-->
    <script src="{{ asset('page-admin/vendor/jquery-easing/jquery.easing.min.js') }}"></script>
    <!-- Custom scripts for all pages-->
    <script src="{{ asset('page-admin/js/sb-admin-2.min.js') }}"></script>
    <!-- Page level plugins -->


    
    {{-- ANGULAR JS --}}
    <script src="{{asset('/js/angular.min.js')}}" type="text/javascript"></script>

    {{-- OBJECT TABLE --}}
    <script src="{{ asset('/table/object-table.js') }}" type="text/javascript"></script>

    {{-- ANGULAR MATERIAL --}}
    <script type="text/javascript" src="{{asset('/js/angular-animate.min.js')}}"></script>
    <script type="text/javascript" src="{{asset('/js/angular-aria.min.js')}}"></script>
    <script type="text/javascript" src="{{asset('/js/angular-messages.min.js')}}"></script>
    <script type="text/javascript" src="{{asset('/js/angular-material.min.js')}}"></script>

    {{-- UI SELECT --}}
    <script src="{{asset('/js/selectize.min.js')}}" type="text/javascript"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.6-rc.0/js/select2.min.js"></script>
    <script src="{{asset('/js/angular-sanitize.js')}}" type="text/javascript"></script>
    <script src="{{asset('/js/select.min.js')}}" type="text/javascript"></script>

    {{-- BOOTSTRAP SELECT --}}
    {{-- <script src="{{asset('/js/bootstrap-select.min.js')}}"  type="text/javascript"></script> --}}
    <script src="https://cdn.jsdelivr.net/npm/bootstrap-select@1.13.7/dist/js/bootstrap-select.min.js"></script>

    {{-- UI BOOTSTRAP --}}
    <script src="{{asset('/bootstrap-gh-pages/ui-bootstrap-tpls-2.0.2.min.js')}}"></script>

    {{-- UI MASK --}}
    <script src="{{asset('/js/angular-input-masks-standalone.min.js')}}"></script>
    @yield('scripts')
    
    {{-- SWEET ALERT --}}
    <script src="{{asset('/sweetalert/dist/sweetalert.min.js')}}" type="text/javascript"></script>

    {{-- MOMENT.JS --}}
    <script src="{{asset('/js/moment.min.js')}}" type="text/javascript"></script>
    <script src="{{asset('/js/moment-with-locales.js')}}"></script>

    {{-- CUSTOM JS --}}
    <script src="{{asset('/js/controller.js')}}" type="text/javascript"></script>

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
</body>

</html>