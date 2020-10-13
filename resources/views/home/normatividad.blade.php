@extends('master.masterPrincipal')

@section('title', 'Normatividad')

@section('contenido')


<!-- <div ng-cloak>
    <md-content>
        <md-tabs md-dynamic-height md-border-bottom>
            <md-tab label="Normativa A">
                <md-content class="md-padding">                            
                    <iframe src="{{asset('/pdf/resolucion_sil.pdf')}}" style="width:100%; height:700px; "></iframe>
                </md-content>
            </md-tab>
            <md-tab label="Normativa B">
                <md-content class="md-padding">
                    <iframe src="{{asset('/pdf/Resolución_511_de_2017.pdf')}}" style="width:100%; height:700px; "></iframe>
                </md-content>
            </md-tab>
            
        </md-tabs>
    </md-content>
</div>
         -->
<div class="container">
    <br><br>
    <div class="Tabs">
   <ul class="nav nav-tabs Tabs-navs" role="tablist">
      <li  role="presentation" class="Tabs-nav active">
         <a href="#normativa1" aria-controls="normativa1" target="_self" role="tab" data-toggle="tab">
         <i class="Tabs-navIcon glyphicon glyphicon-info-sign"></i> Normativa A
         </a>
      </li>
      <li  role="presentation" class="Tabs-nav">
         <a href="#normativa2" aria-controls="normativa2" target="_self" aria-hidden="true" role="tab" data-toggle="tab">
         <i class="Tabs-navIcon glyphicon glyphicon-info-sign"></i> Normativa B
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
                       <iframe src="{{asset('/pdf/resolucion_sil.pdf')}}" style="width:100%; height:700px; "></iframe>
                       </div>
                    </div>
            </div>
        </div>
        </div>
        <div role="tabpanel" class="tab-pane Tabs-pane" id="normativa2">
            <input type="hidden" ng-model="id" ng-init="" />
            <div class="row">
               <div class="col-xs-12">
                  <div class="panel-group Panel-group">
                    <div class="body">
                        <div class="row"> 
                        </div>
                           <iframe src="{{asset('/pdf/Resolución_511_de_2017.pdf#zoom=100&page=2')}}" style="width:100%; height:700px; "></iframe>
                           </div>
                        </div>
                </div>
            </div>
        </div>

    </div>
   </div>
</div>

@stop