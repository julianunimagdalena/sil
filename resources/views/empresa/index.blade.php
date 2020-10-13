@extends('master._adminLayoutNew')
@section('titulo', 'Tablero de empresa - ')
@section('controller','ng-controller ="EmpOfertasCtrl"')
@section('title')
    <h2>Tablero de empresa</h2>
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
@endsection

@section('content')

<!-- <div class="row" ng-controller="GraduadoIndexCtrl"> </div>-->
        
<h3 style="color: #004A87; font-weight: 700">OFERTAS</h3>
@section('tituloVista', 'OFERTAS')
        <div class="row">
    		<div class="col-md-12 table-responsive" style="overflow-x: auto">             
            @if(session('content'))
            <br><br>
            <div class="alert alert-danger alert-dismissable">
                <button type="button" class="close" data-dismiss="alert">&times;</button>
                <strong>¡Error!</strong> {{ session('content') }}
            </div>
            @endif
            
            <!--//////////////////////////////////////////////////////////////////////-->
            
            <table  object-table
            	data = "ofertas"
            	display = "10"
            	headers = "Tipo, Nombre cargo, Vacantes, Salario, Fecha cierre, Estado, Acciones"
            	fields = "gettipo.nombre,nombre,vacantes,salario,fechaCierre,estado"
            	sorting = "compound"
            	editable = "false"
            	resize="false"
            	drag-columns="false"
                ng-if="ofertas.length != 0">
                <thead>
                    <tr>
                        <th ng-click="sortBy('nombre')" ng-class="headerIsSortedClass('Nombre cargo')" class="sortable">
                            Tipo
                        </th>
                        <th ng-click="sortBy('nombre')" ng-class="headerIsSortedClass('Nombre cargo')" class="sortable">
                            Nombre cargo
                        </th>
                        <th ng-click="sortBy('nombre')" ng-class="headerIsSortedClass('Nombre cargo')" class="sortable">
                            Vacantes
                        </th>
                        <th ng-click="sortBy('nombre')" ng-class="headerIsSortedClass('Nombre cargo')" class="sortable">
                            
                            @if($ambas)
                                Salario / Remuneración
                            @elseif($sil)
                                Salario
                            @elseif($dipro)
                                Remuneración
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
                        @{{ ::item.gettipo.nombre }}
                    </td>
                    <td>
                        @{{ ::item.nombre | uppercase }}
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
                        <span title="Detalles" class="fas fa-search" data-id="@{{ ::item.id }}" data-toggle="modal" data-target="#mdldetalles"></span>                         
                        <a href="@{{ $owner.raiz }}/empresa/crearoferta/@{{ ::item.id }}" ng-show="item.getestado.nombre == 'Por aprobar' || item.getestado.nombre == 'Errada' || item.getestado.nombre == 'Publicada'" style="color: #004a87 !important">
                            <span class="fas fa-edit" title="Editar"></span>    
                        </a>
                        <span class="fas fa-trash-o" title="Eliminar" ng-show="item.getestado.nombre == 'Por aprobar'" ng-click="$owner.eliminarOferta(item.id)" style="color: #EF5350 !important"></span>
                    </td>
                </tbody>
            </table>
            <p ng-if="ofertas.length == 0">
                No hay ofertas registradas.
            </p>
            <a class="btn btn-primary" href="{{asset('/empresa/crearoferta')}}" style="color:white !important; background: #00a65a">
                Nueva oferta
            </a>

        </div>

    </div>    
            <!--//////////////////////////////////////////////////////////////////////-->      
            <div id="mdldetalles" class="modal fade" role="dialog" style="overflow:scroll;">
                <div class="modal-dialog modal-lg">
                    <!-- Modal content-->
                    <div class="modal-content">
                        <div class="modal-body row">             
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
                                                                        @{{ oferta.getsalario.rango}}
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
                                                                    <p style="color:#5a5a5e;">@{{ oferta.getcontrato.nombre}}</p>
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
                                                                        @{{ oferta.getexperiencia ? oferta.getexperiencia.nombre:oferta.experiencia }}
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
                                                                        @{{ oferta.getmunicipio.getdepartamento.getpais.nombre}}
                                                                        -
                                                                        @{{ oferta.getmunicipio.nombre}}
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
                                                                    <p style="color:#5a5a5e;">@{{ oferta.vacantes}}</p>
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
                                                                    <p style="color:#5a5a5e;">@{{ oferta.fechaCierre | date:'dd/MM/yyyy'}}</p>
                                                                </div>                                        
                                                                <md-divider></md-divider>
                                                            </md-list-item>
                                                        </md-list>
                                                    </md-content>
                                                </div>
                                                <div class="col-md-12" ng-if="oferta.gettipo.nombre =='Graduados'">
                                                    <md-content>
                                                        <md-list>
                                                            <md-list-item class="md-3-line">
                                                                <div class="md-list-item-text" >
                                                                    <h3>Programas</h3>                         
                                                                    <p style="color:#5a5a5e;">
                                                                        <span ng-repeat="item in oferta.getprogramas">
                                                                            @{{ item.nombre | uppercase}}; 
                                                                        </span>
                                                                        
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
                                                                        <span ng-repeat="item in oferta.getprogramas">
                                                                            @{{ item.nombre | uppercase}}; 
                                                                        </span>
                                                                        
                                                                    </p>
                                                                </div>                                        
                                                                <md-divider></md-divider>
                                                            </md-list-item>
                                                        </md-list>
                                                    </md-content>
                                                </div>
                                                <div class="col-md-12" ng-if="oferta.gettipo.nombre =='Graduados'">
                                                    <md-content>
                                                        <md-list>
                                                            <md-list-item class="md-3-line">
                                                                <div class="md-list-item-text" >
                                                                    <h3>Herramientas informáticas</h3>        
                                                                    <p style="color:#5a5a5e; text-align: justify;">
                                                                        @{{ oferta.herramientasInformaticas || 'No aplica'}}
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
                                                                    <p style="color:#5a5a5e; text-align: justify">
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
                                                                    <p style="color:#5a5a5e; text-align: justify">
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
                                                                   <p style="color:#5a5a5e; text-align: justify">
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
                                                                   <h3 ng-if="oferta.estado == 3" style="color: red;">Motivo de cancelacion</h3>
                                                                   <h3 ng-if="oferta.estado == 6" style="color: red;">Motivo de errado</h3>
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
                                        <md-tab label="Postulados" ng-if="oferta.getpostulados.length > 0">
                                            <md-content class="md-padding">
                                                <table object-table
                                                    data = "oferta.getpostulados"
                                                    display = "7"
                                                    headers = "Nombre completo, Programa,Estado empresa,Estado estudiante, Acciones"
                                                    fields = "getestudiante.getpersona.nombres,getestudiante.getpersona.apellidos,getestudiante.getprograma.nombre"
                                                    sorting = "compound"
                                                    editable = "false"
                                                    resize="false"
                                                    drag-columns="false">
                                                    <tbody>
                                                        <td>
                                                            @{{ ::item.getpersona.nombres + ' ' + item.getpersona.apellidos | uppercase }}
                                                        </td>
                                                        <td>
                                                            @{{ ::item.programas }}
                                                        </td>
                                                        <td>
                                                            @{{ ::item.getestadoempresa.nombre }}
                                                        </td>
                                                        <td>
                                                            @{{ ::item.getestadoestudiante.nombre }}
                                                        </td>
                                                        <td class="text-center">
                                                            <span class="fas fa-check-square" title="Invitar" ng-click="$owner.seleccionarPostulado(item.id, item.getpersona.nombres+' '+item.getpersona.apellidos)" ng-show="item.getestadoempresa.nombre == 'Postulado'"></span>
                                                            &nbsp;
                                                            <span class="fas fa-check" title="Escoger postulado"ng-click="$owner.aceptarPostulado(item, item.getpersona.nombres+' '+item.getpersona.apellidos)" ng-show="item.getestadoempresa.nombre == 'Seleccionado' && item.getestadoestudiante.nombre == 'Aceptó'"></span>
                                                            &nbsp;
                                                            <a href="@{{ $owner.raiz }}/empresa/verperfil/@{{::item.getpersona.id}}/@{{item.idOferta}}" target="_blank">
                                                                <span class="fas fa-id-card" style="color: black;" title="Ver hoja de vida"></span> 
                                                            </a>
                                                            <!--<span class="glyphicon glyphicon-remove" title="Eliminar selección" ng-click="$owner.eliminarSeleccion(item.id)" ng-show="item.getestadoempresa.nombre == 'Seleccionado'"></span>-->
                                                        </td>
                                                    </tbody>
                                                </table>
                                            </md-content>
                                        </md-tab>
                                    </md-tabs>
                                </md-content>
                      


                                                                                
                            
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-rojo" data-dismiss="modal" ng-click="cancelarFamiliar()">Cerrar</button>
                        </div>
                    </div>
            
                </div>
            </div>
@endsection