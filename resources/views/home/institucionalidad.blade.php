@extends('master.masterPrincipal')

@section('title', 'Institucionalidad')

@section('contenido')


<!-- <div ng-cloak>
    <md-content>
        <md-tabs md-dynamic-height md-border-bottom>
            <md-tab label="Direccionamiento estratético">
                <md-content class="md-padding">                            
                    <iframe src="{{asset('/pdf/Direccionamiento_estrategico.pdf')}}" style="width:100%; height:700px; "></iframe>
                </md-content>
            </md-tab>
            <md-tab label="Proyecto educativo institucional">
                <md-content class="md-padding">
                    <iframe src="{{asset('/pdf/PEI.pdf')}}" style="width:100%; height:700px; "></iframe>
                </md-content>
            </md-tab>
            <md-tab label="Organigrama">
                <md-content class="md-padding">
                    <iframe src="{{asset('/pdf/Organigrama.pdf')}}" style="width:100%; height:700px; "></iframe>
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
         <a href="#institucional1" aria-controls="institucional1" target="_self" role="tab" data-toggle="tab">
         <i class="Tabs-navIcon glyphicon glyphicon-info-sign"></i> Direccionamiento estratético
         </a>
      </li>
      <li  role="presentation" class="Tabs-nav">
         <a href="#institucional2" aria-controls="institucional2" target="_self" aria-hidden="true" role="tab" data-toggle="tab">
         <i class="Tabs-navIcon glyphicon glyphicon-info-sign"></i> Proyecto educativo institucional
         </a>
      </li>
      <li  role="presentation" class="Tabs-nav">
         <a href="#institucional3" aria-controls="institucional3" target="_self" aria-hidden="true" role="tab" data-toggle="tab">
         <i class="Tabs-navIcon glyphicon glyphicon-info-sign"></i> Organigrama
         </a>
      </li>
   </ul>
   <div class="tab-content Tabs-content">
      <div role="tabpanel" class="tab-pane Tabs-pane active" id="institucional1">
        <input type="hidden" ng-model="id" ng-init="" />
        <div class="row">
           <div class="col-xs-12">
              <div class="panel-group Panel-group">
                <div class="body">
                    <div class="row"> 
                    </div>
                       <iframe src="{{asset('/pdf/Direccionamiento_estrategico.pdf')}}" style="width:100%; height:700px; "></iframe>
                       </div>
                    </div>
            </div>
        </div>
        </div>
        <div role="tabpanel" class="tab-pane Tabs-pane" id="institucional2">
            <input type="hidden" ng-model="id" ng-init="" />
            <div class="row">
               <div class="col-xs-12">
                  <div class="panel-group Panel-group">
                    <div class="body">
                        <div class="row"> 
                        </div>
                           <iframe src="{{asset('/pdf/PEI.pdf#zoom=100&page=2')}}" style="width:100%; height:700px; "></iframe>
                           </div>
                        </div>
                </div>
            </div>
        </div>
         <div role="tabpanel" class="tab-pane Tabs-pane" id="institucional3">
                    <input type="hidden" ng-model="id" ng-init="" />
                    <div class="row">
                       <div class="col-xs-12">
                          <div class="panel-group Panel-group">
                            <div class="body">
                                <div class="row"> 
                                </div>
                                   <iframe src="{{asset('/pdf/Organigrama.pdf#zoom=80')}}" style="width:100%; height:700px; "></iframe>
                                   </div>
                                </div>
                        </div>
                    </div>
                </div>
            </div>
   </div>
</div>

@stop