<!-- *** Layout página públicas portal Unimagdalena. VERSION 1.0 26/07/2018. Modificado por: Jorge Luis Pineda Montagut / CIDS *** -->
<!DOCTYPE html>

<html>
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <!-- La información debe sustituirse por la que corresponda a la página o portal. Solo debe ser modificado si es necesario. -->
        <meta name="description" content="Institución de Educación Superior acreditada por alta calidad ubicada en la ciudad de Santa Marta, Magdalena">
        <meta name="keywords" content="Unimagdalena, Universidad del Magdalena, Magdalena, IES, alta calidad, Santa Marta, Educacion Superior, Unimag, Inscripciones Unimag, admisiones unimag, admisiones unimagdalena, estudiantes unimagdalena, oferta academica universidad del magdalena" />
        <meta name="author" content="Centro de Investigación y Desarrollo de Software CIDS, Unimagdalena" />
        <meta name="copyright" content="Universidad del Magdalena, CIDS" />
        <meta property="og:title" content="Página institucional Universidad del Magdalena" />
        <meta property="og:type" content="website" />
        <meta property="og:url" content="https://www.unimagdalena.edu.co" />
        <meta property="og:image" content="https://cdn.unimagdalena.edu.co/images/escudo/bg_light/128.png" />
        <meta property="og:description" content="Institución de Educación Superior acreditada por alta calidad ubicada en la ciudad de Santa Marta, Magdalena"/>
        <title>Sistema de Intermediacion Laboral - Universidad del Magdalena</title>
        <meta name='mobile-web-app-capable' content='yes'>
        <meta name='apple-mobile-web-app-capable' content='yes'>
        <meta name='application-name' content='Universidad del Magdalena'>
        <meta name='apple-mobile-web-app-status-bar-style' content='blue'>
        <meta name='apple-mobile-web-app-title' content='Unimagdalena'>
        <link rel='icon' sizes='192x192' href='https://cdn.unimagdalena.edu.co/images/escudo/bg_light/192.png'>
        <link rel='apple-touch-icon' href='https://cdn.unimagdalena.edu.co/images/escudo/bg_light/192.png'>
        <meta name='msapplication-TileImage' content='https://cdn.unimagdalena.edu.co/images/escudo/bg_light/144.png'>
        <meta name='msapplication-TileColor' content='#004A87'>
        <meta name="theme-color" content="#004A87" />
        <link rel="shortcut icon" href="https://cdn.unimagdalena.edu.co/images/favicon.ico" type="image/x-icon">
        <link rel="icon" href="https://cdn.unimagdalena.edu.co/images/favicon.ico" type="image/x-icon">
        <link href="https://cdn.unimagdalena.edu.co/code/css/normalize.min.css" rel="stylesheet" />
        <link href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" rel="stylesheet" />
        <link href="https://code.ionicframework.com/ionicons/2.0.1/css/ionicons.min.css" rel="stylesheet" />
        <link rel="stylesheet" href="{{asset('/css/angular-material.min.css')}}" type="text/css" />
        <link rel="stylesheet" href="{{asset('/bootstrap-gh-pages/ui-bootstrap-2.0.2-csp.css')}}">

        <link rel="stylesheet" href="{{asset('/css/select.min.css')}}" type="text/css" />
        <link rel="stylesheet" href="https://cdn.unimagdalena.edu.co/code/css/public.min.css" type="text/css" />
        <link href="https://cdn.unimagdalena.edu.co/code/css/public_768.min.css" rel="stylesheet" media="(min-width: 768px)">
        <link href="https://cdn.unimagdalena.edu.co/code/css/public_992.min.css" rel="stylesheet" media="(min-width: 992px)">
        <link href="https://cdn.unimagdalena.edu.co/code/css/public_1200.min.css" rel="stylesheet" media="(min-width: 1200px)">
        <link href="https://cdn.unimagdalena.edu.co/code/css/public_1600.min.css" rel="stylesheet" media="(min-width: 1600px)">
        <link href="https://fonts.googleapis.com/css?family=Open+Sans:400,700|Roboto:400,700" rel="stylesheet">

    
        
        <!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
        <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
        <!--[if lt IE 9]>
          <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
          <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
        <![endif]-->
  
        <style type="text/css">
        a.md-default-theme:not(.md-button), a:not(.md-button) {
            color: inherit !important;
        }

        a.redes {
            color: white !important;
        
        }
    </style>
    </head>
<!-- Versión 1.6 28/08/2018 Editado por: Jorge Luis Pineda Montagut / CIDS -->
<header role="banner">
    <div class="toolbar">
        <a id="goto-content" href="#content-main" class="sr-only">Ir al contenido</a>
        <a href="https://www.facebook.com/UniversidadDelMagdalena/" class="btn btn-xs btn-link redes" title="Facebook" target="_blank" rel="noopener noreferrer"><span aria-hidden="true" class="ion-social-facebook"></span><span class="sr-only">Facebook</span></a>
        <a href="https://twitter.com/unimagdalena" class="btn btn-xs btn-link redes" title="Twitter" target="_blank" rel="noopener noreferrer"><span aria-hidden="true" class="ion-social-twitter"></span><span class="sr-only">Twitter</span></a>
        <a href="https://www.instagram.com/unimagdalena/" class="btn btn-xs btn-link redes" title="Instagram" target="_blank" rel="noopener noreferrer"><span aria-hidden="true" class="ion-social-instagram-outline"></span><span class="sr-only">Instagram</span></a>
        <a href="https://www.youtube.com/user/unimagdalenatv" class="btn btn-xs btn-link redes" title="Youtube" target="_blank" rel="noopener noreferrer"><span aria-hidden="true" class="ion-social-youtube"></span><span class="sr-only">Youtube</span></a>
        <!-- Solo habilitar si el portal es capaz de segmentar o filtar el contenido publicado por estamentos. -->
        <!--<div class="estamento-lineal visible-md visible-lg">
            <a href="/?estamento=5" id="enlaceEstamento-5" title="Visualizar la página web como aspirantes">Aspirantes</a>
            <a href="/?estamento=1" id="enlaceEstamento-1" title="Visualizar la página web como estudiante">Estudiantes</a>
            <a href="/?estamento=2" id="enlaceEstamento-2" title="Visualizar la página web como docente">Docentes</a>
            <a href="/?estamento=3" id="enlaceEstamento-3" title="Visualizar la página web como egresado">Egresados</a>
            <a href="/?estamento=4" id="enlaceEstamento-4" title="Visualizar la página web como funcionario">Funcionarios</a>
        </div>
        <form action="/" method="get" class="visible-xs visible-sm">
            <label class="sr-only" for="estamentoUsuario">Seleccionar estamento</label>
            <select onchange = "this.form.submit();" id="estamentoUsuario" name="estamento">
                <option value="5">Aspirantes</option>
                <option value="1">Estudiantes</option>
                <option value="2">Docentes</option>
                <option value="3">Egresados</option>
                <option value="4">Funcionarios</option>
            </select>
        </form> -->
        <div class="btn-group">
            <button type="button" id="menuOpcionesCiudadano" class="btn btn-xs btn-link dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                Ciudadano <span class="caret"></span>
            </button>
            <ul class="dropdown-menu">
                <li>
                    <a role="menuitem" href="/Transparencia">Transparencia y acceso a la información pública</a>
                </li>
                <li>
                    <a role="menuitem" href="https://pse.unimagdalena.edu.co/">Pagos en línea</a>
                </li>
                <li>
                    <a role="menuitem" href="/Publico/PortalNinos">Portal para niños</a>
                </li>
                <li>
                    <a role="menuitem" href="/Publico/Contacto">Ubicación y medios de contacto</a>
                </li>
                <li>
                    <a role="menuitem" href="/Publico/PreguntasFrecuentes">Preguntas frecuentes</a>
                </li>
                <li>
                    <a role="menuitem" href="http://cogui.unimagdalena.edu.co/index.php?option=com_samco&view=pqr&Itemid=867" target="_blank" rel="noopener noreferrer">Peticiones, quejas, reclamos y sugerencias</a>
                </li>
                <li>
                    <a role="menuitem" href="/Publico/ProteccionDatosPersonales">Protección de datos personales</a>
                </li>
                <li>
                    <a role="menuitem" href="/Content/DocumentosSubItems/subitem-20171129151642_181.pdf">Carta de trato digno al ciudadano</a>
                </li>

                <li>
                    <a role="menuitem" href="/Publico/Glosario">Glosario</a>
                </li>
            </ul>
        </div>    
        <a href="https://www.unimagdalena.edu.co/Publico/EnlacesAcceso" class="btn btn-xs btn-lin redes">Mapa de sitio</a>
        <!-- Solo habilitar si el sistema es multi idioma. Reemplazar el action del formulario -->
        <!-- <form action="/Home/ChangeIdioma" method="get" title="Selección de idioma">

            <label class="sr-only" for="langPortal">Seleccionar idioma</label>
            <select id="langPortal" name="idioma" onchange="this.form.submit();">
                <option selected="selected" value="es">Español</option>
                <option value="en">Inglés</option>
            </select>

        </form> -->
        <!-- Solo habilitar si el sistema posee un login o acceso a módulos administrables -->
        
        <!-- <a href="/Home/DashBoard" class="btn btn-xs btn-link hidden-xs" title="Ir a mi perfil"><span class="glyphicon glyphicon-cog"></span><span class="sr-only">Ir al perfil</span></a> -->
        
        <!-- Solo habilitar si el sistema posee acceso a módulos administrables (una vez logueado). Solo mostrar cuando el usuario este logueado. Usar condición de lenguaje servidor -->
        <!-- <a href="/Account/Login" class="btn btn-xs btn-link hidden-xs" title="Iniciar sesión"><span class="glyphicon glyphicon-user"></span><span class="sr-only">Iniciar sesión</span></a> -->
        
    </div>
    <div class="nav-bar">
        <div class="brand">
            <a href="{{Request::root()}}">
                <img src="https://cdn.unimagdalena.edu.co/images/escudo/bg_dark/default.png" alt="Escudo de la Universidad del Magdalena" />
                <h1><span>Sistema Intermediación Laboral</span>Unimagdalena </h1>
            </a>
        </div>
        <div id="navbar-mobile" class="text-center">
            <button type="button" class="btn btn-block btn-primary" title="Menu de navegación"><span aria-hidden="true" class="ion-navicon-round"></span><span class="sr-only">Menú de navegación</span></button>
        </div>
        <nav role="navigation" id="nav-main">
            <ul role="menubar">
                <li>
                    <a role="menuitem" href="https://www.unimagdalena.edu.co">Inicio</a>
                </li>
                <li>
                    <a role="menuitem" href="{{asset('/home/normatividad')}}" aria-haspopup="true" aria-expanded="false">Normatividad</a>
                </li>
                <li>
                    <a role="menuitem" href="{{asset('/home/institucionalidad')}}" rel="noopener noreferrer">Institucionalidad</a>
                </li>
                <li>
                    <a role="menuitem" href="{{asset('/home/inscribete')}}" rel="noopener noreferrer">¿Por qué inscribirse?</a>
                </li>
                <li>
                    <a role="menuitem" href="{{asset('/home/registro')}}">Registrarse</a>
                    
                </li>
                <!-- <li>
                    <a role="menuitem" href="https://www.unimagdalena.edu.co/Internacionalizacion">Internalizacion</a>
                </li> -->
                <li>
                    <a role="menuitem" href="{{asset('/home/contacto')}}">Contáctenos</a>
                </li>    
       
            </ul>
        </nav>
    </div>
</header>

<body ng-app="dipro" @yield('controller')>
    <div class="loadingContent" aria-hidden="true">
        <div class="loader"></div>
        <span>Cargando. Por favor espere...</span>
    </div>
<section class="container" >
            @yield('contenido')
</section>

    
    <!-- Cuerpo interno de la página -->
    <!-- Cabecera institucional -->
     <!-- Pie de página institucional -->

        <script   src="https://code.jquery.com/jquery-2.2.3.min.js"   integrity="sha256-a23g1Nt4dtEYOj7bR+vTu7+T8VP13humZFBJNIYoEJo=" crossorigin="anonymous"></script>
        <script type="text/javascript" src="{{asset('/js/scriptJQ.js')}}"></script>
        <script type="text/javascript" src="{{asset('/js/angular.min.js')}}"></script>
        <script type="text/javascript" src="{{asset('/js/controller.js')}}"></script>
        <script src="{{asset('/js/angular-locale_es-es.js')}}"></script>
        <script src="{{asset('/js/angular-input-masks-standalone.min.js')}}"></script>
        <script src="{{asset('/ckeditor/ckeditor.js')}}"></script>
        <script src="{{asset('/angular-ckeditor/angular-ckeditor.js')}}"></script>
        <script src="{{asset('/js/moment.min.js')}}"></script>
        <script src="{{asset('/js/moment-with-locales.js')}}"></script>  
        <script type="text/javascript" src="{{asset('/sweetalert/dist/sweetalert.min.js')}}"></script>
        <link rel="stylesheet" href="{{asset('/sweetalert/dist/sweetalert.css')}}" type="text/css" />
        <link rel="stylesheet" href="{{asset('/table/object-table-style.css')}}" type="text/css" />
        <script type="text/javascript" src="{{asset('/table/object-table.js')}}"></script>
        <script type="text/javascript" src="{{asset('/js/select.min.js')}}"></script>
        <link rel="stylesheet" href="{{asset('/font-awesome/css/font-awesome.min.css')}}">
        
        <script type="text/javascript" src="{{asset('/js/angular-material.min.js')}}"></script>
        <script type="text/javascript" src="{{asset('/js/angular-animate.min.js')}}"></script>
        <script type="text/javascript" src="{{asset('/js/angular-aria.min.js')}}"></script>
        <script type="text/javascript" src="{{asset('/js/angular-messages.min.js')}}"></script>
        <script src="{{asset('/bootstrap-gh-pages/ui-bootstrap-tpls-2.0.2.min.js')}}"></script>
        <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
        <script src="https://cdn.unimagdalena.edu.co/code/js/main.min.js" async></script>
        
        
    <script type="text/javascript">
        document.addEventListener("DOMContentLoaded", function (event) {
            
            $('.loadingContent').delay(250).fadeOut("fast");
        });
        
    </script>
    <script defer>
        function searchForm(event) {
            event.preventDefault(); // disable normal form submit behavior
            var win = window.open("https://www.google.com.co/search?q=site:http://www.unimagdalena.edu.co+" + document.globalSearchForm.search.value, '_blank');
            win.focus();
            
            return false; // prevent further bubbling of event
        }
    </script>


</body>
<!-- Versión 1.6 28/08/2018 Editado por: Jorge Luis Pineda Montagut / CIDS -->
<footer class="footerUM" style="margin-top: 0;">
    <div class="container">
        <div class="logosGeneral">
            <img id="selloUnimagdalena" src="https://cdn.unimagdalena.edu.co/images/escudo/bg_dark/default.png" alt="escudo de la Universidad del Magdalena" />
            <img id="yearsUnimagdalena" src="https://cdn.unimagdalena.edu.co/images/years_96.png" alt="escudo de los años que tiene la Universidad del Magdalena" />
            <img id="selloAcreditacion" src="https://cdn.unimagdalena.edu.co/images/acreditacion/default-border.png" alt="Marca de acreditación de alta calidad" />
            <img id="selloColombia" src="https://cdn.unimagdalena.edu.co/images/escudo_colombia.png" alt="Escudo de colombia" />
            <img id="sellosCalidad" class="img-responsive" src="https://cdn.unimagdalena.edu.co/images/calidad/bg-dark/default.png" alt="Sellos de calidad" />
        </div>
    </div>
    <div class="container">
        <div id="enlacesInteres">
            <div class="row">
                <div class="col-xs-12 col-md-3 col-sm-6 footerColum">
                    <h3 class="tituloColum">ENLACES DE INTERÉS</h3>
                    <ul >
                        <li>
                            <a style="color:white;" href="http://estrategia.gobiernoenlinea.gov.co" target="_blank" rel="noopener noreferrer">Gobierno en línea</a>
                        </li>
                        <li>
                            <a style="color:white;" href="http://www.mineducacion.gov.co/1759/w3-channel.html" target="_blank" rel="noopener noreferrer">Ministerio de Educación</a>
                        </li>
                        <li>
                            <a style="color:white;" href="https://www.unimagdalena.edu.co/Publico/Mecanismos">Mecanismos de control y vigilancia</a>
                        </li>
                        <li>
                            <a style="color:white;" href="http://aprende.colombiaaprende.edu.co/estudiantes2016" target="_blank" rel="noopener noreferrer">Colombia Aprende</a>
                        </li>
                        <li>
                            <a style="color:white;" href="https://portal.icetex.gov.co/Portal/" target="_blank" rel="noopener noreferrer">Icetex</a>
                        </li>
                        <li>
                            <a style="color:white;" href="http://www.colciencias.gov.co" target="_blank" rel="noopener noreferrer">Colciencias</a>
                        </li>
                        <li>
                            <a style="color:white;" href="http://www.renata.edu.co/index.php" target="_blank" rel="noopener noreferrer">Renata</a>
                        </li>
                        <li>
                            <a style="color:white;" href="http://www.universia.net.co" target="_blank" rel="noopener noreferrer">Universia</a>
                        </li>
                        <li>
                            <a style="color:white;" href="https://www.encuestafacil.com/universia/UnivGenerica.aspx" target="_blank" rel="noopener noreferrer">universia.encuestafacil </a>
                        </li>
                    </ul>
                </div>
                <div  style="z-index: 1;" class="col-xs-12 col-md-3 col-sm-6 footerColum">
                    <h3 class="tituloColum">ATENCIÓN AL CIUDADANO</h3>
                    <ul>
                        <li>
                            <a style="color:white;" role="menuitem" href="/Transparencia">Transparencia y acceso a la información pública</a>
                        </li>
                        <li>
                            <a style="color:white;" role="menuitem" href="https://pse.unimagdalena.edu.co/">Pagos en línea</a>
                        </li>
                        <li>
                            <a style="color:white;" role="menuitem" href="/Publico/PortalNinos">Portal para niños</a>
                        </li>
                        <li>
                            <a style="color:white;" role="menuitem" href="/Publico/Contacto">Ubicación y medios de contacto</a>
                        </li>
                        <li>
                            <a style="color:white;" role="menuitem" href="/Publico/PreguntasFrecuentes">Preguntas frecuentes</a>
                        </li>
                        <li>
                            <a style="color:white;" role="menuitem" href="http://cogui.unimagdalena.edu.co/index.php?option=com_samco&view=pqr&Itemid=867" target="_blank" rel="noopener noreferrer">Peticiones, quejas, reclamos y sugerencias</a>
                        </li>
                        <li>
                            <a style="color:white;" role="menuitem" href="/Publico/ProteccionDatosPersonales">Protección de datos personales</a>
                        </li>
                        <li>
                            <a style="color:white;" role="menuitem" href="/Content/DocumentosSubItems/subitem-20171129151642_181.pdf">Carta de trato digno al ciudadano</a>
                        </li>
                
                        <li>
                            <a style="color:white;" role="menuitem" href="/Publico/Glosario">Glosario</a>
                        </li>
                    </ul>

                </div>
                <div class="col-xs-12 col-md-3 col-sm-6 footerColum">
                    <h3 class="tituloColum">INFORMACIÓN GENERAL</h3>
                    <ul>
                        <li>
                            <a style="color:white;" href="https://www.unimagdalena.edu.co/Content/Public/Docs/reglamento_estudiantil.pdf" target="_blank" rel="noopener noreferrer">Reglamento estudiantil</a>
                        </li>
                        <li>
                            <a style="color:white;" href="https://admisiones.unimagdalena.edu.co/eventos/index.jsp" target="_blank" rel="noopener noreferrer">Calendario académico</a>
                        </li>
                        <li>
                            <a style="color:white;" href="https://www.unimagdalena.edu.co/Publico/ProteccionDatosPersonales">Protección de datos personales</a>
                        </li>
                        <li>
                            <a style="color:white;" href="https://www.unimagdalena.edu.co/Publico/InformesGestion">Informes de gestión</a>
                        </li>
                    </ul>
                </div>
                <div class="col-xs-12 col-md-3 col-sm-6 footerColum">
                    <h3 class="tituloColum">SERVICIOS</h3>
                    <ul>
                        <li>
                            <a style="color:white;" href="http://bienestar.unimagdalena.edu.co" target="_blank" rel="noopener noreferrer">Bienestar universitario</a>
                        </li>
                        <li>
                            <a style="color:white;" href="https://www.unimagdalena.edu.co/UnidadesOrganizativas/Dependencia/9">Recursos educativos</a>
                        </li>
                        <li>
                            <a style="color:white;" href="https://www.unimagdalena.edu.co/UnidadesOrganizativas/Dependencia/6">Biblioteca Germán Bula Meyer</a>
                        </li>
                        <li>
                            <a style="color:white;" href="http://consultorio.unimagdalena.edu.co" target="_blank" rel="noopener noreferrer">Consultorio jurídico y centro de conciliación</a>
                        </li>
                        <li>
                            <a style="color:white;" href="https://www.unimagdalena.edu.co/UnidadesOrganizativas/Dependencia/4">Cartera</a>
                        </li>
                        <li>
                            <a style="color:white;" href="https://pse.unimagdalena.edu.co">Pagos en línea</a>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
    <div id="footer-bottom">
        <div class="container text-center">

            <div class="row">
                <div class="col-xs-12 text-center">
                    <h3 class="tituloColum">INFORMACIÓN DE CONTACTO</h3>
                    <ul>
                        <li>Línea Gratuita Nacional: 01 8000 516060. PBX: (57 - 5) 4217940</li>
                        <li><a style="color:white;" href="https://goo.gl/maps/tad2rQS5Jqj" target="_blank" rel="noopener noreferrer">Dirección: Carrera 32 No 22 – 08 Santa Marta D.T.C.H. - Colombia. Código Postal No. 470004</a></li>
                        <li><a style="color:white;" href="mailto:contacto@unimagdalena.edu.co" target="_blank" rel="noopener noreferrer">Correo electrónico: ciudadano@unimagdalena.edu.co</a></li>
                    </ul>

                </div>
                <div class="col-xs-12 text-center">
                    <p class="infoContacto">La Universidad del Magdalena está sujeta a inspección y vigilancia por el Ministerio de Educación Nacional.</p>
                    <p>Desarrollado por el Centro de Investigación y Desarrollo de Software CIDS - Unimagdalena © 2018<p>
                        <a href="#goto-content" id="goto-up" class="sr-only">Regresar al inicio</a>
                </div>
            </div>
        </div>
    </div>

</footer>

</html>
