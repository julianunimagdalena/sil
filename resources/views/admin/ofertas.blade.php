@extends('master._adminLayoutNew')
@section('titulo', 'Tablero de administrador')
@section('controller','ng-controller ="AdminOfertasCtrl"')
@section('title')
    <h2>Tablero de administrador</h2>
@endsection

@section('left')
  
@endsection

@section('estilos')
<style>
    .eye {
        height: 10px;
        width: 100%;
        border-radius: 20px;
        margin-top: 4px;
        margin-right: 7px;
    }
    .Publicada {
        background-color: #AED581;
    }

    .Rechazada {
        background-color: #EF5350;
    }

    .Por.aprobar {
        background-color: #FFEE58;
    }

    .Errada {
        background-color: #BDBDBD;
    }

    .Finalizada {
        background-color: #03A9F4;
    }

    .Cancelada {
        background-color: #FFA726;
    }

    #link-ofertas * {
        color: #fff;
    }
</style>
<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.10.19/css/dataTables.bootstrap4.min.css">
@endsection

@section('content')

<h3 style="color: #004A87; font-weight: 700">DATOS DE OFERTAS</h3>
@section('tituloVista', 'DATOS DE OFERTAS')
  <div class="row">
        <div class="col-md-12 table-responsive" style="overflow-x: auto">
                @if(session('success'))
                <div class="alert alert-success alert-dismissable">
                    <button type="button" class="close" data-dismiss="alert">&times;</button>
                    <strong>¡Exito!</strong> {{ session('success') }}
                </div>
                @endif
                
                @if(session('error'))
                <div class="alert alert-danger alert-dismissable">
                    <button type="button" class="close" data-dismiss="alert">&times;</button>
                    <strong>¡Error!</strong> {{ session('error') }}
                </div>
                @endif
                
                @if(session('content'))
                <div class="alert alert-danger alert-dismissable">
                    <button type="button" class="close" data-dismiss="alert">&times;</button>
                    <strong>¡Error!</strong> {{ session('content') }}
                </div>
                @endif
                
                @if($soloDipro)
                <a class="btn btn-primary" href="{{asset('/adminsil/crearoferta')}}" style="color:white;">
                    Crear oferta
                </a>
                @endif
                
                <table  object-table
                    data = "ofertas"
                    display = "10"
                    headers = "Nombre cargo, Programas, Vacantes, Salario, Fecha cierre, Estado, Acciones"
                    fields = "nombre,programas vacantes,salario,fechaCierre,estado"
                    sorting = "compound"
                    editable = "false"
                    resize="false"
                    drag-columns="false">
                    <thead>
                        <tr>
                            <th ng-click="sortBy('nombre')" ng-class="headerIsSortedClass('Nombre cargo')" class="sortable">
                                Nombre cargo
                            </th>
                            <th ng-click="sortBy('nombre')" ng-class="headerIsSortedClass('Nombre cargo')" class="sortable">
                                Programas
                            </th>
                            <th ng-click="sortBy('nombre')" ng-class="headerIsSortedClass('Nombre cargo')" class="sortable">
                                Vacantes
                            </th>
                            <th ng-click="sortBy('nombre')" ng-class="headerIsSortedClass('Nombre cargo')" class="sortable">
                                
                                @if($soloDipro)
                                    Remuneración
                                @elseif($soloSil)
                                    Salario
                                @endif
                            </th>
                            <th ng-click="sortBy('nombre')" ng-class="headerIsSortedClass('Nombre cargo')" class="sortable">
                                Fecha cierre
                            </th>
                            <th ng-click="sortBy('nombre')" ng-class="headerIsSortedClass('Nombre cargo')" class="sortable">
                                Estado
                            </th>
                            <th ng-click="sortBy('nombre')" ng-class="headerIsSortedClass('Nombre cargo')" class="sortable">
                                Acciones
                            </th>
                        </tr>
                    </thead>
                    <tbody>
                        <td>
                            @{{ ::item.nombre | uppercase }}
                        </td>
                        <td>
                            @{{ ::item.programas }}
                        </td>
                        <td>
                            @{{ ::item.vacantes }}
                        </td>
                        <td>                            
                            @{{ ::item.salario | salarioOferta}}
                        </td>
                        <td>
                            @{{ ::item.fechaCierre | date:'dd/MM/yyyy' }}
                        </td>
                        <td class="text-center">
                            @{{ ::item.getestado.nombre }}
                            <div class="@{{::item.getestado.nombre}} eye"></div>
                        </td>
                        <td class="text-center">
                            <a>
                                <span class="fas fa-pen" title="Cambiar estado" data-estado="@{{::item.getestado}}" data-mensaje="@{{::item.mensaje_estado || ''}}" data-id="@{{::item.id}}" data-toggle="modal" data-target="#cambiarestado" ng-show="item.creada_por_dipro == 0"></span>
                            </a>                       
                            
                            
                            <a href="@{{ $owner.raiz }}/adminsil/crearoferta/@{{::item.id}}" ng-show="item.creada_por_dipro == 1">
                                <span class="fas fa-edit" title="Editar oferta"></span>
                            </a>

                            <a>
                                <i class="fas fa-pencil-alt" data-id="@{{ item.id }}" title="Editar oferta" ng-show="item.oferta_egresados==1" data-toggle="modal" data-target="#ofertaModal"></i>
                            </a>
                            
                            <a>
                                <span class="fas fa-search" title="Detalles" data-id="@{{ ::item.id }}" data-toggle="modal" data-target="#detallesOferta" ng-show="item.oferta_egresados!=1" ></span>
                            </a>
                            
                        </td>
                    </tbody>
                </table>

                <button class="btn btn-primary" ng-click="abrirModalOferta()">Crear oferta laboral</button>
        </div>
  </div>  
             
            
            <!--modal-->
            
            <div id="cambiarestado" class="modal fade" role="dialog">
                <div class="modal-dialog">
            
                    <!-- Modal content-->
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="exampleModalLabel">Cambiar estado</h5>
                            <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">×</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <form role="form" class="form-horizontal" ng-submit="cambiarEstado()">
                                
                                <div class="form-group">
                                    <label class="col-lg-2" for="rol">Estados</label>
                                    <div class="col-lg-12">
                                        <select class="form-control" ng-model="oferta.estado" ng-disabled="oferta.d" ng-options="i.nombre for i in estados">
                                            <option value="" selected>Seleccione una opción</option>
                                        </select>
                                        <p class="help-block text-danger">
                                            @{{ erroresEstado['estado.id'][0] }}
                                        </p>
                                    </div>
                                    <div ng-if="oferta.estado.nombre=='Rechazada' || oferta.estado.nombre=='Errada'">
                                        <label class="col-lg-6">Motivo</label>
                                        <div class="col-lg-12">
                                            <textarea ng-disabled="!oferta.m" class="form-control" ng-model="oferta.mensaje" style="width: 100%; height: 80px;"></textarea>
                                            <p class="help-block text-danger">
                                                @{{ erroresEstado['mensaje'][0] }}
                                            </p>
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-rojo" data-dismiss="modal">Cerrar</button>
                            <button ng-show="!oferta.d" type="submit" class="btn btn-success" ng-click="cambiarEstado()" >Guardar</button>
                        </div>
                    </div>
            
                </div>
            </div>
            
            <!-- Modal -->
            <div id="detallesOferta" class="modal fade" role="dialog" style="overflow:scroll;">
                <div class="modal-dialog modal-lg">
            
                    <!-- Modal content-->
                    <div class="modal-content">
                        
                        <div class="modal-body row">

                            {{-- <div ng-cloak> --}}
                                <md-content style="margin-top:-15px; border-top-right-radius: 5px; border-top-left-radius: 5px; width: 100%;">
                                    <md-tabs md-dynamic-height md-border-bottom>
                                        <md-tab label="Detalles">
                                            <md-content class="md-padding">
                                                <div class="row">
                                                    <div class="col-md-4" ng-if="oferta.gettipo.nombre =='Graduados'">
                                                        <md-content>
                                                            <md-list>
                                                                <md-list-item class="md-3-line">
                                                                    <div class="md-list-item-text" >
                                                                        <h3>Empresa</h3>                           
                                                                        <p style="color:#5a5a5e;">@{{ oferta.empresa }}</p>
                                                                    </div>                                        
                                                                    <md-divider></md-divider>
                                                                </md-list-item>
                                                            </md-list>
                                                        </md-content>
                                                    </div>
                                                    <div class="col-md-4" ng-if="oferta.gettipo.nombre =='Graduados'">
                                                        <md-content>
                                                            <md-list>
                                                                <md-list-item class="md-3-line">
                                                                    <div class="md-list-item-text" >
                                                                        <h3>Cargo</h3>                           
                                                                        <p style="color:#5a5a5e;">@{{ oferta.nombre}}</p>
                                                                    </div>                                        
                                                                    <md-divider></md-divider>
                                                                </md-list-item>
                                                            </md-list>
                                                        </md-content>
                                                    </div>
                                                    <div class="col-md-4" ng-if="oferta.gettipo.nombre =='Graduados'">
                                                        <md-content>
                                                            <md-list>
                                                                <md-list-item class="md-3-line">
                                                                    <div class="md-list-item-text" >
                                                                        <h3>Salario</h3>                           
                                                                        <p style="color:#5a5a5e;" >  
                                                                            @{{ oferta.salario }}
                                                                        </p>
                                                                    </div>                                        
                                                                    <md-divider></md-divider>
                                                                </md-list-item>
                                                            </md-list>
                                                        </md-content>
                                                    </div>
                                                    <div class="col-md-4" ng-if="oferta.gettipo.nombre =='Graduados'">
                                                        <md-content>
                                                            <md-list>
                                                                <md-list-item class="md-3-line">
                                                                    <div class="md-list-item-text" >
                                                                        <h3>Tipo de contratación</h3>                           
                                                                        <p style="color:#5a5a5e;">@{{ oferta.tipo_contrato }}</p>
                                                                    </div>                                        
                                                                    <md-divider></md-divider>
                                                                </md-list-item>
                                                            </md-list>
                                                        </md-content>
                                                    </div>
                                                    <div class="col-md-4" ng-if="oferta.gettipo.nombre =='Graduados'">
                                                        <md-content>
                                                            <md-list>
                                                                <md-list-item class="md-3-line">
                                                                    <div class="md-list-item-text" >
                                                                        <h3>Experiencia</h3>
                                                                        <p style="color:#5a5a5e;">  
                                                                            @{{ oferta.getexperiencia ? oferta.getexperiencia.nombre : oferta.experiencia }}
                                                                        </p>                         
                                                                    </div>                                        
                                                                    <md-divider></md-divider>
                                                                </md-list-item>
                                                            </md-list>
                                                        </md-content>
                                                    </div>
                                                    

                                                    <div ng-if="oferta.gettipo.nombre =='Practicantes'">
                                                        <div class="col-md-6">
                                                            <md-content>
                                                                <md-list>
                                                                    <md-list-item class="md-3-line">
                                                                        <div class="md-list-item-text" >
                                                                            <h3>Cargo</h3>                           
                                                                            <p style="color:#5a5a5e;">@{{ oferta.nombre}}</p>
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
                                                                        <div class="md-list-item-text" >
                                                                            <h3>Salario</h3>        
                                                                            <p style="color:#5a5a5e;" >  
                                                                                $@{{ oferta.salario | currency:'':0 }}
                                                                            </p>
                                                                        </div>                                        
                                                                        <md-divider></md-divider>
                                                                    </md-list-item>
                                                                </md-list>
                                                            </md-content>
                                                        </div>
                                                    </div>
                                                        
                                                    <div class="col-md-4">
                                                        <md-content>
                                                            <md-list>
                                                                <md-list-item class="md-3-line">
                                                                    <div class="md-list-item-text" >
                                                                        <h3>Oferta dirigida a</h3>                 
                                                                        <p style="color:#5a5a5e;">@{{ oferta.gettipo.nombre}}</p>
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
                                                                    <div class="md-list-item-text" >
                                                                        <h3>Lugar</h3>                                           
                                                                        <p style="color:#5a5a5e;">
                                                                            @{{ oferta.lugar }}
                                                                        </p>
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
                                                                    <div class="md-list-item-text" >
                                                                        <h3>Vacantes</h3>                                           
                                                                        <p style="color:#5a5a5e;">@{{ oferta.vacantes }}</p>
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
                                                                    <div class="md-list-item-text" >
                                                                        <h3>Fecha de cierre</h3>                                           
                                                                        <p style="color:#5a5a5e;">@{{ oferta.fecha_cierre | date:'dd/MM/yyyy' }}</p>
                                                                    </div>                                        
                                                                    <md-divider></md-divider>
                                                                </md-list-item>
                                                            </md-list>
                                                        </md-content>
                                                    </div>
                                                    <div class="col-md-6" ng-if="oferta.gettipo.nombre =='Graduados'">
                                                        <md-content>
                                                            <md-list>
                                                                <md-list-item class="md-3-line">
                                                                    <div class="md-list-item-text" >
                                                                        <h3>Programas</h3>                         
                                                                        <p style="color:#5a5a5e;">
                                                                            @{{ oferta.programas }}
                                                                        </p>
                                                                    </div>                                        
                                                                    <md-divider></md-divider>
                                                                </md-list-item>
                                                            </md-list>
                                                        </md-content>
                                                    </div>
                                                    <div class="col-md-12" ng-if="oferta.gettipo.nombre =='Practicantes'">
                                                        <md-content>
                                                            <md-list>
                                                                <md-list-item class="md-3-line">
                                                                    <div class="md-list-item-text" >
                                                                        <h3>Programas</h3>                         
                                                                        <p style="color:#5a5a5e;">
                                                                            @{{ oferta.programas }}
                                                                        </p>
                                                                    </div>                                        
                                                                    <md-divider></md-divider>
                                                                </md-list-item>
                                                            </md-list>
                                                        </md-content>
                                                    </div>
                                                    <div class="col-md-6" ng-if="oferta.gettipo.nombre =='Graduados'">
                                                        <md-content>
                                                            <md-list>
                                                                <md-list-item class="md-3-line">
                                                                    <div class="md-list-item-text" >
                                                                        <h3>Herramientas informáticas</h3>        
                                                                        <p style="color:#5a5a5e;">
                                                                            @{{ oferta.herramientas || 'No aplica'}}
                                                                        </p>
                                                                    </div>                                        
                                                                    <md-divider></md-divider>
                                                                </md-list-item>
                                                            </md-list>
                                                        </md-content>
                                                    </div>
                                                    <div class="col-md-12" >
                                                        <md-content>
                                                            <md-list>
                                                                <md-list-item class="md-3-line">
                                                                    <div class="md-list-item-text" >
                                                                        <h3>Perfil</h3>        
                                                                        <p style="color:#5a5a5e;">
                                                                            @{{ oferta.perfil }}
                                                                        </p>
                                                                    </div>                                        
                                                                    <md-divider></md-divider>
                                                                </md-list-item>
                                                            </md-list>
                                                        </md-content>
                                                    </div>
                                                    <div class="col-md-12" >
                                                        <md-content>
                                                            <md-list>
                                                                <md-list-item class="md-3-line">
                                                                    <div class="md-list-item-text" >
                                                                        <h3>Funciones</h3>        
                                                                        <p style="color:#5a5a5e;">
                                                                            @{{ oferta.funciones }}
                                                                        </p>
                                                                    </div>
                                                                </md-list-item>
                                                            </md-list>
                                                        </md-content>
                                                    </div>
                                                    <div class="col-md-12" >
                                                       <md-content>
                                                           <md-list>
                                                               <md-list-item class="md-3-line">
                                                                   <div class="md-list-item-text" >
                                                                       <h3>Obervaciones</h3>        
                                                                       <p style="color:#5a5a5e;">
                                                                           @{{ oferta.observaciones }}
                                                                       </p>
                                                                   </div>
                                                               </md-list-item>
                                                           </md-list>
                                                       </md-content>
                                                   </div>
                                                   <div ng-if="oferta.mensaje_estado" class="col-md-12" >
                                                       <md-content>
                                                           <md-list>
                                                               <md-list-item class="md-3-line">
                                                                   <div class="md-list-item-text" >
                                                                       <h3 style="color: red;">Motivo de cancelacion</h3>        
                                                                       <p style="color:#5a5a5e;">
                                                                           @{{ oferta.mensaje_estado }}
                                                                       </p>
                                                                   </div>
                                                               </md-list-item>
                                                           </md-list>
                                                       </md-content>
                                                   </div>
                                               </div>
                                            </md-content>
                                        </md-tab>
                                        <md-tab label="Postulados">
                                            <md-content class="md-padding">
                                                <table class="table dtable">
                                                    <thead>
                                                        <th>Identificacion</th>
                                                        <th>Nombre</th>
                                                        <th>Programas</th>
                                                        <th>Estado estudiante</th>
                                                        <th>Estado empresa</th>
                                                    </thead>
                                                    <tbody ng-repeat="item in oferta.postulados">
                                                        <td>@{{ item.identificacion }}</td>
                                                        <td>@{{ item.nombre }}</td>
                                                        <td>@{{ item.programas }}</td>
                                                        <td>@{{ item.estado }}</td>
                                                        <td>@{{ item.estado_empresa }}</td>
                                                    </tbody>
                                                </table>
                                            </md-content>
                                        </md-tab>
                                    </md-tabs>
                                </md-content>
                            {{-- </div> --}}
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-rojo" data-dismiss="modal" ng-click="cancelarFamiliar()">Cerrar</button>
                        </div>
                    </div>
            
                </div>
            </div>
            <div id="detallesOfert" class="modal fade" role="dialog">
                <div class="modal-dialog">
            
                    <!-- Modal content-->
                    <div class="modal-content">
                        <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal">&times;</button>
                            <!--<h4 class="modal-title">Detalles de oferta</h4>-->
                            <h4 class="modal-title">@{{oferta.nombre}}</h4>
                        </div>
                        <div class="modal-body">
                            <!---->
                            <fieldset ng-if="{{$soloDipro}}">
                                <legend> <span>Jefe inmediato: </span> @{{oferta.jefe.nombre | uppercase}}</legend>
                            </fieldset>
                            
                            <!--<fieldset>-->
                            <!--    <legend> <span>Nombre del cargo: </span> @{{oferta.nombre}}</legend>-->
                            <!--</fieldset>-->
                            
                            <fieldset>
                                <legend> <span>Vacantes: </span> @{{oferta.vacantes}}</legend>
                            </fieldset>
                            
                            <fieldset>
                                <legend> <span>Fecha de cierre: </span> @{{oferta.fechacierre | date:'dd/MM/yyyy'}} </legend>
                            </fieldset>
                            
                            <fieldset ng-if="{{$soloDipro}}">
                                <legend> <span>Remuneración: </span> @{{oferta.salario | salarioOferta}}</legend>
                            </fieldset>
                            
                            <div ng-if="{{$soloSil}}">
                                <fieldset>
                                    <legend> <span>Salario: </span> @{{oferta.salario.rango }}</legend>
                                </fieldset>
                                
                                <fieldset>
                                    <legend> <span>Tipo de contrato: </span> @{{oferta.contrato.nombre}} </legend>
                                </fieldset>
                            </div>
                            
                            <div ng-if="{{$soloDipro}}">
                                <fieldset>
                                    <legend> 
                                        <span>Salud: </span> 
                                        <label ng-if="oferta.salud == 1">Si </label>
                                        <label ng-if="oferta.salud == 0">No </label>
                                        &nbsp;&nbsp;&nbsp;&nbsp;
                                        <span>ARL: </span> 
                                        <label ng-if="oferta.arl == 1">Si </label>
                                        <label ng-if="oferta.arl == 0">No </label>
                                        &nbsp;&nbsp;&nbsp;&nbsp;
                                        <a href="@{{ raiz }}/adminsil/cartaarl/@{{ oferta.id }}" target="_blank" ng-if="oferta.arl == 0 && oferta.carta != null">
                                            Descargar carta de justificaión del no pago de ARL
                                        </a>
                                    </legend>
                                </fieldset>
                            </div>
                            
                            <div ng-if="{{$soloSil}}">
                                <fieldset ng-if="oferta.experiencia != null">
                                    <legend> <span>Experiencia laboral: </span> @{{ oferta.experiencia }} </legend>
                                </fieldset>
                                
                                <fieldset ng-if="oferta.informaticas != null">
                                    <legend> <span>Herramientas informáticas: </span></legend>
                                    <p class="margen">
                                        @{{ oferta.informaticas }}    
                                    </p>
                                </fieldset>
                            </div>
                            
                            <fieldset>
                                <legend> <span>Perfil: </span> </legend>
                                <p class="margen">
                                    @{{ oferta.perfil }}
                                </p>
                            </fieldset>
                            <fieldset ng-if="oferta.observaciones != null">
                                <legend> <span>Observaciones: </span> </legend>
                                <p class="margen">
                                    @{{ oferta.observaciones }}
                                </p>
                            </fieldset>
                            
                            <fieldset>
                                <legend> <span>Programas: </span> </legend>
                                <ui-select multiple ng-model="oferta.programas" sortable="true" close-on-select="false" ng-disabled="true">
                                    <ui-select-match placeholder="Seleccione los programas">@{{$item.nombre}}</ui-select-match>
                                    <ui-select-choices repeat="programa in (formulario.programas | filter: $select.search) track by programa.id">
                                        <small>
                                            @{{programa.nombre}}
                                        </small>
                                    </ui-select-choices>
                                </ui-select>
                            </fieldset>
                            
                            <!---->
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-rojo" data-dismiss="modal">Cerrar</button>
                        </div>
                    </div>
            
                </div>
            </div>

{{-- MODAL DE CREAR OFERTA LABORAL --}}
<div class="modal fade" id="ofertaModal" tabindex="-1" role="dialog" aria-labelledby="ofertaModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="ofertaModalLabel">Crear oferta laboral</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form ng-submit="guardarOfertaLaboral()">
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group">
                                <label>Empresa</label>
                                <input type="text" class="form-control" ng-model="oferta_form.empresa" placeholder="Empresa" ng-change="errors.oferta_form.empresa=undefined">
                                <small class="text-danger" ng-if="errors.oferta_form.empresa">@{{ errors.oferta_form.empresa[0] }}</small>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label>Correo de la empresa</label>
                                <input type="email" class="form-control" ng-model="oferta_form.correo_empresa" placeholder="Correo" ng-change="errors.oferta_form.correo_empresa=undefined">
                                <small class="text-danger" ng-if="errors.oferta_form.correo_empresa">@{{ errors.oferta_form.correo_empresa[0] }}</small>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label>Cargo</label>
                                <input type="text" class="form-control" ng-model="oferta_form.nombre" placeholder="Cargo" ng-change="errors.oferta_form.nombre=undefined">
                                <small class="text-danger" ng-if="errors.oferta_form.nombre">@{{ errors.oferta_form.nombre[0] }}</small>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Vacantes</label>
                                <input type="number" class="form-control" ng-model="oferta_form.vacantes" placeholder="Vacantes" ng-change="errors.oferta_form.vacantes=undefined">
                                <small class="text-danger" ng-if="errors.oferta_form.vacantes">@{{ errors.oferta_form.vacantes[0] }}</small>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Fecha de cierre</label>
                                <input type="date" class="form-control" ng-model="oferta_form.fecha_cierre" placeholder="Fecha de cierre" ng-change="errors.oferta_form.fecha_cierre=undefined">
                                <small class="text-danger" ng-if="errors.oferta_form.fecha_cierre">@{{ errors.oferta_form.fecha_cierre[0] }}</small>
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <label>Programas</label>
                        <select multiple class="form-control bselect" data-live-search="true" ng-model="oferta_form.programas" ng-options="item.nombre for item in datos.programas track by item.id" ng-change="errors.oferta_form.programas=undefined"></select>
                        <small class="text-danger" ng-if="errors.oferta_form.programas">@{{ errors.oferta_form.programas[0] }}</small>
                    </div>
                    <ul>
                        <li ng-repeat="prog in oferta_form.programas">@{{ prog.nombre }}</li>
                    </ul>
                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group">
                                <label>Salario</label>
                                <select class="form-control" ng-model="oferta_form.salario" ng-options="item.id as item.rango for item in datos.salarios" ng-change="errors.oferta_form.salario=undefined">
                                    <option value="" selected hidden>Seleccione una opción</option>
                                </select>
                                <small class="text-danger" ng-if="errors.oferta_form.salario">@{{ errors.oferta_form.salario[0] }}</small>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label>Tipo de contrato</label>
                                <select class="form-control" ng-model="oferta_form.tipo_contrato" ng-options="item.id as item.nombre for item in datos.tipos_contrato" ng-change="errors.oferta_form.tipo_contrato=undefined">
                                    <option value="" selected hidden>Seleccione una opción</option>
                                </select>
                                <small class="text-danger" ng-if="errors.oferta_form.tipo_contrato">@{{ errors.oferta_form.tipo_contrato[0] }}</small>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label>Experiencia</label>
                                <select class="form-control" ng-model="oferta_form.experiencia" ng-options="item.id as item.nombre for item in datos.experiencias" ng-change="errors.oferta_form.experiencia=undefined">
                                    <option value="" selected hidden>Seleccione una opción</option>
                                </select>
                                <small class="text-danger" ng-if="errors.oferta_form.experiencia">@{{ errors.oferta_form.experiencia[0] }}</small>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Departamento</label>
                                <select class="form-control" ng-model="oferta_form.departamento" ng-options="item.nombre for item in datos.departamentos" ng-change="errors.oferta_form.departamento=undefined">
                                    <option value="" selected hidden>Seleccione una opción</option>
                                </select>
                                <small class="text-danger" ng-if="errors.oferta_form.departamento">@{{ errors.oferta_form.departamento[0] }}</small>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Municipio</label>
                                <select class="form-control" ng-model="oferta_form.municipio" ng-options="item.id as item.nombre for item in oferta_form.departamento.municipios" ng-change="errors.oferta_form.municipio=undefined">
                                    <option value="" selected hidden>Seleccione una opción</option>
                                </select>
                                <small class="text-danger" ng-if="errors.oferta_form.municipio">@{{ errors.oferta_form.municipio[0] }}</small>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Perfil</label>
                                <textarea rows="5" class="form-control" ng-model="oferta_form.perfil" placeholder="Perfil" ng-change="errors.oferta_form.perfil=undefined"></textarea>
                                <small class="text-danger" ng-if="errors.oferta_form.perfil">@{{ errors.oferta_form.perfil[0] }}</small>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Funciones</label>
                                <textarea rows="5" class="form-control" ng-model="oferta_form.funciones" placeholder="Funciones" ng-change="errors.oferta_form.funciones=undefined"></textarea>
                                <small class="text-danger" ng-if="errors.oferta_form.funciones">@{{ errors.oferta_form.funciones[0] }}</small>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Observaciones</label>
                                <textarea rows="5" class="form-control" ng-model="oferta_form.observaciones" placeholder="Observaciones" ng-change="errors.oferta_form.observaciones=undefined"></textarea>
                                <small class="text-danger" ng-if="errors.oferta_form.observaciones">@{{ errors.oferta_form.observaciones[0] }}</small>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Herramientas informáticas</label>
                                <textarea rows="5" class="form-control" ng-model="oferta_form.herramientas" placeholder="Herramientas informáticas" ng-change="errors.oferta_form.herramientas=undefined"></textarea>
                                <small class="text-danger" ng-if="errors.oferta_form.herramientas">@{{ errors.oferta_form.herramientas[0] }}</small>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-light" data-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-primary">Guardar</button>
                </div>
            </form>
        </div>
    </div>
</div>
            
        
@endsection

@section('scripts')
    <script type="text/javascript" src="//cdn.datatables.net/1.10.19/js/jquery.dataTables.min.js"></script>
    <script type="text/javascript" src="https://cdn.datatables.net/1.10.19/js/dataTables.bootstrap4.min.js"></script>
@endsection