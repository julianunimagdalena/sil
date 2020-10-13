@extends('master.master')

@section('title', 'Ofertas')
@section('contenido')
    <style type="text/css">
        .modal-dialog
        {
            width:900px;
        }
    </style>
    <div class="row" ng-controller="EmpOfertasCtrl">

        <div class="col-md-1"></div>
        <div class="col-md-10">
            <a class="btn btn-primary" href="{{asset('/empresa/crearoferta')}}" style="color:white;">
                Nueva oferta
            </a>
             
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
            	drag-columns="false">
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
                    <td>
                        @{{ ::item.getestado.nombre }}
                    </td>
                    <td class="text-center">
                        <span title="Detalles" class="glyphicon glyphicon-search blue" data-id="@{{ ::item.id }}" data-toggle="modal" data-target="#mdldetalles"></span>                         
                        <a href="@{{ $owner.raiz }}/empresa/crearoferta/@{{ ::item.id }}" ng-show="item.getestado.nombre == 'Por aprobar' || item.getestado.nombre == 'Errada' || item.getestado.nombre == 'Publicada'">
                            <span class="glyphicon glyphicon-pencil blue" title="Editar"></span>    
                        </a>
                        <span class="glyphicon glyphicon-trash blue" title="Eliminar" ng-show="item.getestado.nombre == 'Por aprobar'" ng-click="$owner.eliminarOferta(item.id)"></span>
                    </td>
                </tbody>
            </table>
            
            <!--//////////////////////////////////////////////////////////////////////-->
            
            <div id="mdldetalles" class="modal fade" role="dialog" style="overflow:scroll;">
                <div class="modal-dialog">
            
                    <!-- Modal content-->
                    <div class="modal-content">
                        
                        <div class="modal-body row">

                            <div ng-cloak>
                                <md-content style="margin-top:-15px; border-top-right-radius: 5px; border-top-left-radius: 5px;">
                                    <md-tabs md-dynamic-height md-border-bottom>
                                        <md-tab label="Detalles">
                                            <md-content class="md-padding">
                                                <div ng-if="oferta.gettipo.nombre =='Graduados'">
                                                    <div class="col-md-3">
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
                                                    <div class="col-md-3">
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
                                                    <div class="col-md-3" >
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
                                                    <div class="col-md-3" >
                                                        <md-content>
                                                            <md-list>
                                                                <md-list-item class="md-3-line">
                                                                    <div class="md-list-item-text" >
                                                                        <h3>Experiencia</h3>
                                                                        <p style="color:#5a5a5e;">  
                                                                            @{{ oferta.getexperiencia.nombre}}
                                                                        </p>                         
                                                                    </div>                                        
                                                                    <md-divider></md-divider>
                                                                </md-list-item>
                                                            </md-list>
                                                        </md-content>
                                                    </div>
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
                                                    
                                                <div class="col-md-3">
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
                                                <div class="col-md-3">
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
                                                <div class="col-md-3">
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
                                                <div class="col-md-3">
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
                                                <div class="col-md-6" ng-if="oferta.gettipo.nombre =='Graduados'">
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
                                                <div class="col-md-6" ng-if="oferta.gettipo.nombre =='Graduados'">
                                                    <md-content>
                                                        <md-list>
                                                            <md-list-item class="md-3-line">
                                                                <div class="md-list-item-text" >
                                                                    <h3>Herramientas informáticas</h3>        
                                                                    <p style="color:#5a5a5e;">
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
                                            </md-content>
                                        </md-tab>                                        
                                        <md-tab label="Postulados" ng-if="oferta.getpostulados.length > 0">
                                            <md-content class="md-padding">
                                                <table  object-table
                                                    data = "oferta.getpostulados"
                                                    display = "7"
                                                    headers = "Nombres, Apellidos, Programa,Estado empresa,Estado estudiante, Acciones"
                                                    fields = "getestudiante.getpersona.nombres,getestudiante.getpersona.apellidos,getestudiante.getprograma.nombre"
                                                    sorting = "compound"
                                                    editable = "false"
                                                    resize="false"
                                                    drag-columns="false">
                                                    <tbody>
                                                        <td>
                                                            @{{ ::item.getpersona.nombres }}
                                                        </td>
                                                        <td>
                                                            @{{ ::item.getpersona.apellidos | uppercase }}
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
                                                            <a href="@{{ $owner.raiz }}/empresa/verperfil/@{{::item.getpersona.id}}" target="_blank">
                                                                <span class="glyphicon glyphicon-list-alt" title="Ver hoja de vida"></span> 
                                                            </a>
                                                            &nbsp;
                                                            <span class="glyphicon glyphicon-ok blue" title="Selecccionar" ng-click="$owner.seleccionarPostulado(item.id)" ng-show="item.getestadoempresa.nombre == 'Postulado'"></span>
                                                            <!--<span class="glyphicon glyphicon-remove" title="Eliminar selección" ng-click="$owner.eliminarSeleccion(item.id)" ng-show="item.getestadoempresa.nombre == 'Seleccionado'"></span>-->
                                                        </td>
                                                    </tbody>
                                                </table>
                                            </md-content>
                                        </md-tab>
                                    </md-tabs>
                                </md-content>
                            </div>


                                                                                
                            
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-rojo" data-dismiss="modal" ng-click="cancelarFamiliar()">Cerrar</button>
                        </div>
                    </div>
            
                </div>
            </div>
            
        </div>
        <div class="col-md-1"></div>
    </div>
@stop