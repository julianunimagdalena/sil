@extends('master._adminLayoutNew')
@section('titulo', 'Tablero de empresa - ')
@section('controller','ng-controller="EmpCrearofertaCtrl"')
@section('title')
    <h2>Tablero de empresa</h2>
@endsection

@section('left')
  
@endsection

@section('content')

<style type="text/css">
   /*a.md-default-theme:not(.md-button), a:not(.md-button) {
       color: inherit !important;
   }*/
</style>
<h3 style="color: #004A87; font-weight: 700">CREAR OFERTAS</h3>
@section('tituloVista', 'CREAR OFERTAS')

<div class="container-fluid">
    <div class="row" ng-init="soloDipro = {{ $soloDipro }}">
       <div class="col-12" ng-init=" soloSil = {{ $soloSil }}">
            <form role="form" ng-submit="guardarOferta()">
               @if(isset($id))
               <input type="hidden" ng-model="oferta.id" ng-init="oferta.id = {{$id}}"/>
               @endif
              <div class="panel-group Panel-group">
                 <div class="body">
                    <div class="row">
                       <div class="col-12">
                          <div class="row" ng-if="{{ Auth::user()->getsede->getempresa->getestadodipro->nombre == 'ACEPTADA' && Auth::user()->getsede->getempresa->getestadosil->nombre == 'ACEPTADA' }}">
                           <div class="form-group" >
                               <label >Tipo de oferta</label>
                               {{-- <select class="form-control" ng-model="oferta.tipo"></select> --}}
                               <div class="col-lg-12">
                                   <ui-select ng-model="oferta.tipo" ng-disabled="oferta.getestado.nombre == 'Publicada'" >
                                       <ui-select-match placeholder="Seleccionar">
                                           <span ng-bind="$select.selected.nombre"></span>
                                       </ui-select-match>
                                       <ui-select-choices repeat="item in (formulario.tipooferta | filter: $select.search) track by item.id">
                                           <span ng-bind="item.nombre"></span>
                                       </ui-select-choices>
                                   </ui-select>                    
                                   <p class="help-block text-danger">
                                       @{{ errores['tipo.id'][0] }}
                                   </p>
                               </div>
                           </div> 
                               </div>
                          <div class="row">
                             <div class="col-sm-4">
                                <div class="form-group" >
                                    <label>País</label>
                                    <select class="form-control" ng-model="form.pais" ng-options="i.nombre for i in formulario.paises track by i.id" ng-disabled="oferta.getestado.nombre == 'Publicada'">
                                        <option value="" selected hidden>Seleccione un opción</option>
                                    </select>
                                    <p class="help-block text-danger">@{{ errores['pais.id'][0] }}</p>
                                </div>
                             </div>
                             <div class="col-sm-4">
                                <div class="form-group" >
                                    <label>Departamento</label>
                                    <select class="form-control" ng-model="form.departamento" ng-options="i.nombre for i in form.pais.departamentos track by i.id" ng-disabled="oferta.getestado.nombre == 'Publicada'">
                                        <option value="" selected hidden>Seleccione un opción</option>
                                    </select>
                                    <p class="help-block text-danger">@{{ errores['departamento.id'][0] }}</p>
                                </div>
                             </div>
                             <div class="col-sm-4">
                                <div class="form-group" >
                                    <label>Ciudad o municipio</label>
                                    <select class="form-control" ng-model="oferta.getmunicipio" ng-options="i.nombre for i in form.departamento.municipios track by i.id" ng-disabled="oferta.getestado.nombre == 'Publicada'">
                                        <option value="" selected hidden>Seleccione un opción</option>
                                    </select>
                                    <p class="help-block text-danger">@{{ errores['departamento.id'][0] }}</p>
                                </div>
                         
                             
                             </div>   
                          </div>
                          <div class="row">
                               <div class="col-sm-3" ng-if="oferta.tipo.nombre == 'Practicantes' || soloDipro">
                                <div class="form-group">
                                    <label>Jefe inmediato</label>
                                        <ui-select ng-model="oferta.jefe" ng-disabled="oferta.getestado.nombre == 'Publicada'">
                                            <ui-select-match placeholder="Seleccionar">
                                                <span ng-bind="$select.selected.nombre"></span>
                                            </ui-select-match>
                                            <ui-select-choices repeat="item in (formulario.jefes | filter: $select.search) track by item.id">
                                                <span ng-bind="item.nombre"></span>
                                            </ui-select-choices>
                                        </ui-select>
                                        <p class="help-block text-danger">
                                            @{{ errores['jefe.id'][0] }}
                                        </p>
                                    </div>
                              </div>
                              <div class="col-sm-3" ng-if="oferta.tipo.nombre == 'Practicantes' || soloDipro">
                                    <div class="form-group" >
                                        <label>Remuneración</label>
                                            <input type="text" ng-model="oferta.salario" ng-disabled="oferta.getestado.nombre == 'Publicada'" class="form-control" placeholder="Remuneración" ng-if="!oferta.pordefinir" ui-money-mask="0"/>
                                            <p class="help-block text-danger">
                                                @{{ errores.salario[0] }} 
                                            </p>
                                    </div>
                              </div>
                              <div class="col-sm-3" ng-if="oferta.tipo.nombre == 'Practicantes' || soloDipro">
                                    <div class="form-group">
                                        <label>Salud: </label>
                                            <label>Si <input type="checkbox" ng-disabled="oferta.getestado.nombre == 'Publicada'" ng-model="oferta.saluds" ng-change="saluds()" /></label>&nbsp;&nbsp;&nbsp;
                                            <label>No <input type="checkbox" ng-disabled="oferta.getestado.nombre == 'Publicada'" ng-model="oferta.saludn" ng-change="saludn()" /></label>
                                            <p class="help-block text-danger">
                                                @{{ errores.salud[0] }}
                                            </p>
                                    </div>
                              </div>
                              <div class="col-sm-3" ng-if="oferta.tipo.nombre == 'Practicantes' || soloDipro">
                                    <div class="form-group">
                                        <label>ARL: </label>
                                            <label>Si <input type="checkbox" ng-disabled="oferta.getestado.nombre == 'Publicada'" ng-model="oferta.arls" ng-change="arls()" /></label>&nbsp;&nbsp;&nbsp;
                                            <label>No <input type="checkbox" ng-disabled="oferta.getestado.nombre == 'Publicada'" ng-model="oferta.arln" ng-change="arln()" /></label>
                                            <p class="help-block">
                                                Se sugiere que por norma de ministerio de trabajo marque si
                                            </p>
                                            <p class="help-block text-danger">
                                                @{{ errores.arl[0] }}
                                            </p>
                                    </div>
                              </div>
                          </div>
                          <div class="row">
                                <div class="col-sm-3">
                                    <div class="form-group">
                                        <label>Nombre del cargo</label>
                                        <input type="text" ng-model="oferta.nombre" ng-disabled="oferta.getestado.nombre == 'Publicada'" class="form-control" placeholder="Nombre del cargo"/>
                                        <p class="help-block text-danger">
                                            @{{ errores.nombre[0] }}
                                        </p>
                                    </div>
                                </div>
                                <div class="col-sm-3">
                                      <div class="form-group">
                                        <label>Vacantes</label>
                                            <input type="text" ng-model="oferta.vacantes" class="form-control" placeholder="Vacantes"/>
                                            <p class="help-block text-danger">@{{ errores.vacantes[0] }}</p>
                                      </div>
                                </div>
                                <div class="col-sm-3">
                                    <div class="form-group">
                                        <label>Fecha de cierre</label>
                                            <input type="date" ng-model="oferta.fecha_cierre" class="form-control" placeholder="Fecha de cierre"/>
                                            <p class="help-block text-danger">
                                                @{{ errores.fecha_cierre[0] }}
                                            </p>
                                    </div>
                                </div>
                                  <div class="col-sm-3">
                                 <div class="form-group" ng-if="oferta.tipo.nombre == 'Graduados' || soloSil">
                                    <label>Salario</label>
                                    <select class="form-control" ng-model="oferta.salario" ng-options="i.rango for i in formulario.salarios track by i.id" ng-disabled="oferta.getestado.nombre == 'Publicada'">
                                        <option value="" selected hidden>Seleccione una opción</option>
                                    </select>
                                    <p class="help-block text-danger">
                                        @{{ errores['salario.id'][0] }}
                                    </p>
                                </div>
                            </div>
                          </div>
                          <div class="row">
                               <div class="col-sm-4">
                                <div class="form-group">
                                    <label>Programas</label>
                                    <select multiple  class="form-control bselect" data-live-search="true" ng-model="oferta.programas" ng-options="i.nombre for i in formulario.programas track by i.id" ng-disabled="oferta.getestado.nombre == 'Publicada'"></select>
                                    <small class="text-muted">
                                      @{{ oferta.programas.length }} programa@{{ oferta.programas.length != 1 ? 's':'' }} seleccionado@{{ oferta.programas.length != 1 ? 's':'' }}
                                    </small>
                                    <p class="help-block text-danger">
                                        @{{ errores.programas[0] }}
                                    </p>
                                </div>
                            </div>
                            <div class="col-sm-4" ng-if="oferta.tipo.nombre == 'Graduados' || soloSil">
                                 <div class="form-group" >
                                    <label>Tipo de contrato</label>
                                    <select class="form-control" ng-model="oferta.contrato" ng-options="i.nombre for i in formulario.contratos track by i.id" ng-disabled="oferta.getestado.nombre == 'Publicada'">
                                        <option value="" selected hidden>Seleccione una opción</option>
                                    </select>
                                    <p class="help-block text-danger">
                                        @{{ errores['contrato.id'][0] }}
                                    </p>
                                </div>
                            </div>  
                         
                            <div class="col-sm-4" ng-if="oferta.tipo.nombre == 'Graduados' || soloSil">
                                 <div class="form-group">
                                    <label>Experiencia laboral</label>
                                    <select class="form-control" ng-model="oferta.getexperiencia" ng-options="i.nombre for i in formulario.experiencias track by i.id" ng-disabled="oferta.getestado.nombre == 'Publicada'">
                                        <option value="" selected hidden>Seleccione una opción</option>
                                    </select>                   
                                    <p class="help-block text-danger">
                                        @{{ errores['getexperiencia.id'][0] }}
                                    </p>
                                </div>
                            </div>
                          </div>
                          <div class="row">
                              <div class="col-sm-6">
                                   <div class="form-group">
                                    <label>Perfil</label>
                                        <textarea class="form-control noresize" ng-disabled="oferta.getestado.nombre == 'Publicada'" rows="5" ng-model="oferta.perfil"></textarea>
                                        <p class="help-block text-danger">
                                            @{{ errores.perfil[0] }}
                                        </p>
                                </div>
                              </div>
                              <div class="col-sm-6" ng-if="oferta.tipo.nombre == 'Graduados' || soloSil">
                                   <div class="form-group">
                                        <label>Herramientas informáticas</label>
                                            <textarea class="form-control noresize" ng-disabled="oferta.getestado.nombre == 'Publicada'" rows="5" ng-model="oferta.informaticas" placeholder="Suministrar informacion"></textarea>
                                            <p class="help-block text-danger">
                                                @{{ errores.informaticas[0] }}
                                            </p>
                                    </div>
                              </div>
                              
                          </div>
                          <div class="row">
                              <div class="col-sm-6">
                                    <div class="form-group">
                                        <label>Funciones</label>
                                            <textarea class="form-control noresize" ng-disabled="oferta.getestado.nombre == 'Publicada'" rows="5" ng-model="oferta.funciones"></textarea>
                                            <p class="help-block text-danger">
                                                @{{ errores.funciones[0] }}
                                            </p>
                                    </div>
                              </div>
                              <div class="col-sm-6">
                                  <div class="form-group">
                                        <label>Observaciones</label>
                                            <textarea class="form-control noresize" ng-disabled="oferta.getestado.nombre == 'Publicada'" rows="5" ng-model="oferta.observaciones"></textarea>    
                                            <p class="help-block text-danger">
                                                @{{ errores.observaciones[0] }}
                                            </p>
                                    </div>
                              </div>
                          </div>
                          <div class="row" ng-if="oferta.tipo.nombre =='Practicantes' && oferta.arln">
                              <div class="col-sm-6">
                                  <div class="form-group">
                                    <label>Debe subir una carta firmada por el representante legal de la empresa, donde explique porqué no va a pagar arl</label>
                                        <!--ng-model="oferta.carta"-->
                                        <input type="file" uploader-model="oferta.file">
                                        <p class="help-block">La carta debe ser formato PDF y pesar máximo 1MB.</p>
                                        <p class="help-block text-danger">
                                            @{{ errores.carta[0] }}
                                        </p>
                                </div>
                              </div>
                          </div>

                       </div>
                    </div>
                    <button type="submit" class="btn btn-success">
                        Guardar
                    </button>
                 </div>

              </div>

            </form>
       </div>
    </div>
</div>
    
@endsection
