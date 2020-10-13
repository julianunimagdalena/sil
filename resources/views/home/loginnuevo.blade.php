@extends('master.masterPrincipal')
@section('title', 'Inicio')
@section('contenido')

<article id="carousel">
    <div class="carousel">
        <div id="carousel-main" class="carousel slide" data-ride="carousel">
            <!-- Indicators -->

                <ol class="carousel-indicators">
                    
                        <li data-target="#carousel-main" data-slide-to="0" class=""></li>
                        <li data-target="#carousel-main" data-slide-to="1" class=""></li>
                       <!--  <li data-target="#carousel-main" data-slide-to="2" class=""></li>
                        <li data-target="#carousel-main" data-slide-to="3" class=""></li>
                        <li data-target="#carousel-main" data-slide-to="4" class=""></li> -->
                        <li data-target="#carousel-main" data-slide-to="5" class="active"></li>
                </ol>
            <!-- Wrapper for slides -->
            <div class="carousel-inner">
                <div class="item">
                    <img src="{{asset('/img/slider/img1.png')}}" alt="Ceremonia de Graduación">
                        <div class="carousel-caption container">
                            <p>07/09/2018</p>
                                <h2></h2>
                        </div>
                        
                        </div>
                        <div class="item">
                            <img src="{{asset('/img/slider/img2.png')}}" alt="UNIMAGDALENA conmemoró Día del Ingeniero">
                                <div class="carousel-caption container">
                                        <p>27/08/2018</p>
                                        <h2>UNIMAGDALENA conmemoró Día del Ingeniero</h2>
                                </div>
                        </div>
                        <div class="item active">
                            <img src="{{asset('/img/slider/img2.png')}}" alt="Lanzamiento de Maestría en Antropología">
                                <div class="carousel-caption container">
                                        <p>27/08/2018</p>
                                        <h2>Lanzamiento de Maestría en Antropología</h2>
                                </div>
                        </div>
                <ul id="links-navigation">
                    <li>
                        <a href="#news" title="Noticias" role="button" aria-label="Noticias">
                            <span class="ion-ios-paper"></span> <span>Noticias</span>
                        </a>
                    </li>
                    <li>
                        <a href="#multimedia" title="Multimedia" role="button" aria-label="Multimedia">
                            <span class="ion-play"></span> <span>Multimedia</span>
                        </a>
                    </li>
                    <li>
                        <a href="#events" title="Eventos" role="button" aria-label="Eventos">
                            <span class="ion-android-calendar"></span> <span>Eventos</span>
                        </a>
                    </li>
                    <li>
                        <a href="#announcements" title="Avisos" role="button" aria-label="Avisos">
                            <span class="ion-android-notifications"></span> <span>Avisos</span>
                        </a>
                    </li>
                </ul>
                
            </div>
            <!-- Controls -->
            <a class="left carousel-control" href="#carousel-main" role="button" data-slide="prev">
                <span class="glyphicon glyphicon-chevron-left" aria-hidden="true"></span>
                <span class="sr-only">Anterior</span>
            </a>
            <a class="right carousel-control" href="#carousel-main" role="button" data-slide="next">
                <span class="glyphicon glyphicon-chevron-right" aria-hidden="true"></span>
                <span class="sr-only">Siguiente</span>
            </a>
        </div>
    </div>
</article>
<!-- <section id="indicadores">
    <div class="container">

     
            <div class="bx-indicadores" style="max-width: 1000px;"><div class="bx-viewport" style="width: 100%; overflow: hidden; position: relative; height: 85px;"><ul id="indicador-listado" style="width: 5215%; position: relative; transition-duration: 0s; transform: translate3d(-1000px, 0px, 0px);"><li style="float: left; list-style: none; position: relative; width: 250px;" class="bx-clone" aria-hidden="true">
                        <p><span class="indicador-number">18.225</span>Estudiantes de pregrado</p>
                    </li><li style="float: left; list-style: none; position: relative; width: 250px;" class="bx-clone" aria-hidden="true">
                        <p><span class="indicador-number">362</span>Estudiantes de postgrado</p>
                    </li><li style="float: left; list-style: none; position: relative; width: 250px;" class="bx-clone" aria-hidden="true">
                        <p><span class="indicador-number">44.758</span>Graduados</p>
                    </li><li style="float: left; list-style: none; position: relative; width: 250px;" class="bx-clone" aria-hidden="true">
                        <p><span class="indicador-number">45</span>Convenios internacionales</p>
                    </li>
                    <li style="float: left; list-style: none; position: relative; width: 250px;" aria-hidden="false">
                        <p><span class="indicador-number">12.326</span>
Inscritos 2018 I</p>
                    </li>
                    <li style="float: left; list-style: none; position: relative; width: 250px;" aria-hidden="false">
                        <p><span class="indicador-number">18.225</span>Estudiantes de pregrado</p>
                    </li>
                    <li style="float: left; list-style: none; position: relative; width: 250px;" aria-hidden="false">
                        <p><span class="indicador-number">362</span>Estudiantes de postgrado</p>
                    </li>
                    <li style="float: left; list-style: none; position: relative; width: 250px;" aria-hidden="true">
                        <p><span class="indicador-number">44.758</span>Graduados</p>
                    </li>
                    <li style="float: left; list-style: none; position: relative; width: 250px;" aria-hidden="true">
                        <p><span class="indicador-number">45</span>Convenios internacionales</p>
                    </li>
               
            <li style="float: left; list-style: none; position: relative; width: 250px;" class="bx-clone" aria-hidden="true">
                        <p><span class="indicador-number">12.326</span>
Inscritos 2018 I</p>
                    </li><li style="float: left; list-style: none; position: relative; width: 250px;" class="bx-clone" aria-hidden="true">
                        <p><span class="indicador-number">18.225</span>Estudiantes de pregrado</p>
                    </li><li style="float: left; list-style: none; position: relative; width: 250px;" class="bx-clone" aria-hidden="true">
                        <p><span class="indicador-number">362</span>Estudiantes de postgrado</p>
                    </li><li style="float: left; list-style: none; position: relative; width: 250px;" class="bx-clone" aria-hidden="true">
                        <p><span class="indicador-number">44.758</span>Graduados</p>
                    </li></ul></div></div>
       
    </div>
</section>
<style type="text/css">
    /*Indicadores*/
#indicadores{
    background-color: #A56000;
    border-top: 2px solid #A5CA00; 
    color: #FFFFFF;
    padding: .5% 1rem;
    margin-bottom: 1rem;
    position:relative;
}
#indicadores:after {
    content: "";
    position: absolute;
    top: 100%;
    left: calc(50% - .5rem);
    width: 0;
    height: 0;
    border-left: 25px solid transparent;
    border-right: 25px solid transparent;
    border-top: 1rem solid #004A87;
}
#indicadores .indicador-logos {
    display:-webkit-inline-box;
    display: -moz-inline-box;
    display: inline-flex;
    align-items:center;
    background-color: #A5CA00;
    padding: 1rem;
    position: relative;
    height: 100px;
    margin: -.5% 0;
    margin-bottom: 1rem;
    justify-content: center;
    border-bottom-left-radius: 10px;
    border-bottom-right-radius: 10px;
}
#indicadores .indicador-logos a {
    display: block;
    height: 100%;
    width: auto;
}

#indicadores .indicador-logos img, #indicadores .indicador-logos a img {
    height: 100%;
}

#indicadores .indicador-logos, #indicadores #indicador-listado {
    width: 100%;
}
#indicadores #indicador-listado p{
    font-family: 'Leelawadee', sans-serif;
    font-weight: bold;
    margin-top: .5rem;
}
#indicadores #indicador-listado p .indicador-number {
    display: block;
    font-size: 2rem;
    color: white;
    text-shadow: 0px 1px 3px rgba(0,0,0,.4);
    font-family: 'LeelawadeeBold', sans-serif;
}

</style> -->

@stop