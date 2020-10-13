@extends('master.masterPrincipal')

@section('title', '¿Por qué inscribirse?')

@section('contenido')


<!-- <div ng-cloak>
    <md-content>
        <md-tabs md-dynamic-height md-border-bottom>
            <md-tab label="¿POR QUÉ INSCRIBIRSE?">
                <md-content class="md-padding">                            
                    <p>Registra tú Hoja de Vida en el sistema de Intermediación Laboral de la Universidad del Magdalena es la oportunidad de darte a conocer a las empresas en el mundo laboral. </p>

                    <p>Encontrar oportunidades laborales es una de las actividades mas complejas para las personas hoy en día, por esta razón nuestra meta es suplir esas necesidades con compromiso manteniendo la comunicación entre los graduados de la Universidad del Magdalena y las empresas inscritas. </p>

                    <p><b>Aspectos importantes:</b></p>

                    <ol>
                        <li>Mantener la hoja de vida actualizada.</li>
                        <li>Revisar las notificaciones del sistema de intermediación laboral en el correo.</li>
                        <li>Utilizar los filtros para encontrar las ofertas requeridas.</li>
                        <li>Postularse a las ofertas que se ajusten a su perfil.</li>
                    </ol>
                </md-content>
            </md-tab>
        </md-tabs>
    </md-content>
</div> -->
<div class="container">
    <br><br>
    <div class="Tabs">
   <ul class="nav nav-tabs Tabs-navs" role="tablist">
      <li  role="presentation" class="Tabs-nav active">
         <a href="#normativa1" aria-controls="normativa1" target="_self" role="tab" data-toggle="tab">
         <i class="Tabs-navIcon glyphicon glyphicon-info-sign"></i> Normativa A
         </a>
      </li>
   </ul>
   <div class="tab-content Tabs-content">
      <div role="tabpanel" class="tab-pane Tabs-pane active" id="normativa1">
        <input type="hidden" ng-model="id" ng-init="" />
        <div class="row">
           <div class="col-xs-12">
              <div class="panel-group Panel-group">
                <div class="body">
                    <div class="row"> 
                    </div>
                       <p>
                           <p>Registra tú Hoja de Vida en el sistema de Intermediación Laboral de la Universidad del Magdalena es la oportunidad de darte a conocer a las empresas en el mundo laboral. </p>

                    <p>Encontrar oportunidades laborales es una de las actividades mas complejas para las personas hoy en día, por esta razón nuestra meta es suplir esas necesidades con compromiso manteniendo la comunicación entre los graduados de la Universidad del Magdalena y las empresas inscritas. </p>

                    <p><b>Aspectos importantes:</b></p>

                    <ol>
                        <li>Mantener la hoja de vida actualizada.</li>
                        <li>Revisar las notificaciones del sistema de intermediación laboral en el correo.</li>
                        <li>Utilizar los filtros para encontrar las ofertas requeridas.</li>
                        <li>Postularse a las ofertas que se ajusten a su perfil.</li>
                    </ol>
                       </p>
                       </div>
                    </div>
            </div>
        </div>
        </div>
    </div>
   </div>
</div>

@stop