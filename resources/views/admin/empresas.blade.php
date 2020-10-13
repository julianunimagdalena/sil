@extends('master._adminLayoutNew')
@section('titulo', 'Tablero de administrador')
@section('controller','ng-controller ="EmpresaCtrl"')
@section('title')
    <h2>Tablero de administrador</h2>
@endsection

@section('estilos')
    <style type="text/css">
        .ui-select-bootstrap .ui-select-choices-row.active>a {
            color: #fff !important;
        }

        .table-responsive {
            display: table;
        }

        h1 {
            text-transform: uppercase;
            font-weight: 600
        }

        #link-empresas * {
            color: #fff;
        }
    </style>
@endsection

@section('content')

        @if(Session('error') != null)
            <div class="alert alert-danger alert-dismissable">
                <button type="button" class="close" data-dismiss="alert">&times;</button>
                {{ Session('error') }}
            </div>
        @endif
         
        <h3 style="color: #004A87; font-weight: 700">DATOS DE EMPRESAS</h3>
        @section('tituloVista', 'DATOS DE EMPRESAS')
        <div class="row">

        <div class="col-md-12 table-responsive" style="overflow-x: auto">
            <table  object-table
                    data = "empresas"
                    display = "10"
                    headers = "Nit, Nombre, País,Departamento,Municipio, Estado, Acciones"
                    fields = "getempresa.nit,getempresa.nombre,getmunicipio.getdepartamento.getpais.nombre,getmunicipio.getdepartamento.nombre,getmunicipio.nombre, getempresa.getestadodipro.nombre"
                    sorting = "compound"
                    editable = "false"
                    resize="false"
                    drag-columns="false">
                    <tbody>
                        <tr ng-show="item.getempresa.getconvenios[item.getempresa.getconvenios.length - 1].getestado.nombre == 'Suscrito'">
                            <td>
                                @{{ ::item.getempresa.nit }}
                            </td>
                            <td>
                                @{{ ::item.getempresa.nombre | uppercase }}
                            </td>
                            <td>
                                @{{ ::item.getmunicipio.getdepartamento.getpais.nombre | uppercase }}
                            </td>
                            <td>
                                @{{ ::item.getmunicipio.getdepartamento.nombre | uppercase }}
                            </td>
                            <td>
                                @{{ ::item.getmunicipio.nombre | uppercase }}
                            </td>
                            <td>
                                @if(session('rol')->nombre=='Administrador Dippro')
                                    @{{ ::item.getempresa.getestadodipro.nombre | uppercase }}
                                @elseif(session('rol')->nombre=='Administrador Egresados')
                                    @{{ ::item.getempresa.getestadosil.nombre | uppercase }}
                                @endif
                            </td>
                            <td class="text-center">
                                <span class="fas fa-search" title="Ver detalles de la empresa" data-toggle="modal" data-target="#detallesEmpresa" data-id="@{{item.getempresa.id}}"
                                    style="color:black !important;"></span>
                                <a href="@{{ $owner.raiz }}/adminsil/descargarnit/@{{ item.getempresa.id }}" target="_blank">
                                    <span class="fas fa-download" title="Descargar nit" style="color:black !important;"></span>
                                </a>
                                <span data-id="@{{ ::item.getempresa.id }}" 
                                data-estado="@if(session('rol')->nombre=='Administrador Dippro') @{{ ::item.getempresa.getestadodipro.nombre }} @elseif(session('rol')->nombre=='Administrador Egresados') @{{ ::item.getempresa.getestadosil.nombre }} @endif" 
                                data-toggle="modal" data-target="#cambiarestado" class="fas fa-pen" title="Cambiar estado"
                                style="color:black !important;"></span>
                            </td>
                        </tr>
                        <tr ng-show="item.getempresa.getconvenios[item.getempresa.getconvenios.length - 1].getestado.nombre != 'Suscrito'">
                            <td>
                                @{{ ::item.getempresa.nit }}
                            </td>
                            <td>
                                @{{ ::item.getempresa.nombre | uppercase }}
                            </td>
                            <td>
                                @{{ ::item.getmunicipio.getdepartamento.getpais.nombre | uppercase }}
                            </td>
                            <td>
                                @{{ ::item.getmunicipio.getdepartamento.nombre | uppercase }}
                            </td>
                            <td>
                                @{{ ::item.getmunicipio.nombre | uppercase }}
                            </td>
                            <td>
                                @if(session('rol')->nombre=='Administrador Dippro')
                                    @{{ ::item.getempresa.getestadodipro.nombre | uppercase }}
                                @elseif(session('rol')->nombre=='Administrador Egresados')
                                    @{{ ::item.getempresa.getestadosil.nombre | uppercase }}
                                @endif
                            </td>
                            <td class="text-center">
                                <span class="fas fa-search" title="Ver detalles de la empresa" data-toggle="modal" data-target="#detallesEmpresa" data-id="@{{item.getempresa.id}}"></span>
                                <a href="@{{ $owner.raiz }}/adminsil/descargarnit/@{{ item.getempresa.id }}" target="_blank" style="color: #000;">
                                    <span class="fas fa-download" title="Descargar nit"></span>
                                </a>
                                <span data-id="@{{ ::item.getempresa.id }}" data-estado="@if(session('rol')->nombre=='Administrador Dippro') @{{ ::item.getempresa.getestadodipro.nombre }} @elseif(session('rol')->nombre=='Administrador Egresados') @{{ ::item.getempresa.getestadosil.nombre }} @endif" data-toggle="modal" data-target="#cambiarestado" class="glyphicon fas fa-pen" title="Cambiar estado"></span>
                            </td>
                        </tr>
                    </tbody>
                </table>
        <div class="alert alert-warning" role="alert" ng-if="usuarios.length == 0">
            No hay usuarios almacenados en el sistema
        </div>
        <div class="alert alert-warning" role="alert" ng-if="usuarios.length > 0 && ( usuarios | filter: search).length == 0">
            No hay resultados para la búsqueda ingresada
        </div>
    </div>
    </div>
                
                
       <!---------MODAL----------->          
            <div id="cambiarestado" class="modal fade" role="dialog">
                <div class="modal-dialog">
            
                    <!-- Modal content-->
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="exampleModalLabel">CAMBIAR ESTADO</h5>
                            <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">×</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <form role="form" class="form-horizontal" ng-submit="cambiarEstado()">
                                
                                <div class="form-group">
                                    <label class="col-lg-2" for="rol">Estados</label>
                                    <div class="col-lg-12">
                                        <input type="hidden" value="@{{empresa.id}}" ng-model="empresa.id"/>
                                        {{-- <ui-select ng-model="empresa.estado" ng-change="empresa.motivo_cancelacion=null">
                                            <ui-select-match>
                                                <span ng-bind="$select.selected.nombre"></span>
                                            </ui-select-match>
                                            <ui-select-choices repeat="item in (estados | filter: $select.search) track by item.id">
                                                <span ng-bind="item.nombre"></span>
                                            </ui-select-choices>
                                        </ui-select> --}}
                                        <select class="form-control" ng-model="empresa.estado" ng-change="empresa.motivo_cancelacion=null" ng-options="i.nombre for i in estados">
                                            <option value="" selected>Seleccione una opción</option>
                                        </select>
                                        <p class="help-block text-danger">
                                            @{{ errores['estado.id'][0] }}
                                        </p>
                                    </div>
                                </div>
                                <div class="form-group" ng-if="empresa.estado.nombre == 'RECHAZADA'">
                                    <label class="col-lg-6">Motivo de cancelacion</label>
                                    <div class="col-lg-12">
                                        <textarea class="form-control" ng-model="empresa.motivo_cancelacion"></textarea>
                                    </div>
                                </div>
                                <button type="submit" ng-if="false"></button>
                            </form>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-rojo" data-dismiss="modal">Cerrar</button>
                            <button type="submit" class="btn btn-success" ng-click="cambiarEstado()" >Guardar</button>
                        </div>
                    </div>
            
                </div>
            </div>
            
            
        
        <div class="col-md-1"></div>
        
        <div id="detallesEmpresa" class="modal fade" role="dialog">
            <div class="modal-dialog modal-lg" style="width:900px;">
        
                <!-- Modal content-->
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">DETALLES</h5>
                        <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">×</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <form role="form" class="form-horizontal">
                            <div class="row">
                                <div class="col-md-12">
                                    <fieldset>
                                        <legend>
                                            <b style="font-size:18px;">Datos de la empresa</b>
                                        </legend>
                                    </fieldset>
                                </div>
                                <div class="form-group col-md-4">
                                    <label class="col-lg-12" for="rol">Nombre</label>
                                    <div class="col-lg-12">
                                        <p>
                                            @{{ empresa.nombre }}
                                        </p>
                                    </div>
                                </div>
                                <div class="form-group col-md-4">
                                    <label class="col-lg-12" for="rol">Tipo de documento</label>
                                    <div class="col-lg-12">
                                        <p>
                                            @{{ empresa.gettiponit.nombre }}
                                        </p>
                                    </div>
                                </div>
                                <div class="form-group col-md-4">
                                    <label class="col-lg-12" for="rol">Nit</label>
                                    <div class="col-lg-12">
                                        <p>
                                            @{{ empresa.nit }}
                                        </p>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="form-group col-md-4">
                                    <label class="col-lg-12" for="rol">Correo</label>
                                    <div class="col-lg-12">
                                        <p>
                                            @{{ empresa.getsedes[0].correo }}
                                        </p>
                                    </div>
                                </div>
                                <div class="form-group col-md-4">
                                    <label class="col-lg-12" for="rol">Pagina web</label>
                                    <div class="col-lg-12">
                                        <p>
                                            @{{ empresa.paginaWeb }}
                                        </p>
                                    </div>
                                </div>
                                <div class="form-group col-md-4">
                                    <label class="col-lg-12" for="rol">Teléfono</label>
                                    <div class="col-lg-12">
                                        <p>
                                            @{{ empresa.getsedes[0].telefono }}
                                        </p>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="form-group col-md-6">
                                    <label class="col-lg-12" for="rol">Dirección</label>
                                    <div class="col-lg-12">
                                        <p>
                                            @{{ empresa.getsedes[0].getmunicipio.getdepartamento.getpais.nombre }},
                                            @{{ empresa.getsedes[0].getmunicipio.getdepartamento.nombre }},
                                            @{{ empresa.getsedes[0].getmunicipio.nombre }} - 
                                            @{{ empresa.getsedes[0].direccion }}
                                        </p>
                                    </div>
                                </div>
                                <div class="form-group col-md-6">
                                    <label class="col-lg-12" for="rol">Tipo de empleador</label>
                                    <div class="col-lg-12">
                                        <p>
                                            @{{ empresa.gettipoempleador.nombre }}
                                        </p>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="form-group col-md-12">
                                    <label class="col-lg-12" for="rol">Actividad económica</label>
                                    <div class="col-lg-12">
                                        <p>
                                            @{{ empresa.getactividadeconomica.nombre }}
                                        </p>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-12">
                                    <fieldset>
                                        <legend>
                                            <b style="font-size:18px;">
                                                Datos del representante legal
                                            </b>
                                        </legend>
                                    </fieldset>
                                </div>
                                <div class="form-group col-md-6">
                                    <label class="col-lg-12" for="rol">Nombre</label>
                                    <div class="col-lg-12">
                                        <p>
                                            @{{ empresa.getrepresentante.nombres }}
                                            @{{ empresa.getrepresentante.apellidos }}
                                        </p>
                                    </div>
                                </div>
                                <div class="form-group col-md-6">
                                    <label class="col-lg-12" for="rol">Identificación</label>
                                    <div class="col-lg-12">
                                        <p>
                                            @{{ empresa.getrepresentante.identificacion }}
                                        </p>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="row">
                                <div class="col-md-12">
                                    <fieldset>
                                        <legend>
                                            <b style="font-size:18px;">
                                                Información del contacto
                                            </b>
                                        </legend>
                                    </fieldset>
                                </div>
                                <div class="form-group col-md-6">
                                    <label class="col-lg-12" for="rol">Nombre</label>
                                    <div class="col-lg-12">
                                        <p>
                                            @{{ empresa.getsedes[0].getusuarios[0].getuser.nombres }}
                                            @{{ empresa.getsedes[0].getusuarios[0].getuser.apellidos }}
                                        </p>
                                    </div>
                                </div>
                                <div class="form-group col-md-6">
                                    <label class="col-lg-12" for="rol">Identificación</label>
                                    <div class="col-lg-12">
                                        <p>
                                            @{{ empresa.getsedes[0].getusuarios[0].getuser.identificacion }}
                                        </p>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="form-group col-md-6">
                                    <label class="col-lg-12" for="rol">Celular</label>
                                    <div class="col-lg-12">
                                        <p>
                                            @{{ empresa.getsedes[0].getusuarios[0].getuser.celular }}
                                        </p>
                                    </div>
                                </div>
                                <div class="form-group col-md-6">
                                    <label class="col-lg-12" for="rol">Correo</label>
                                    <div class="col-lg-12">
                                        <p>
                                            @{{ empresa.getsedes[0].getusuarios[0].getuser.correo }}
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-rojo" data-dismiss="modal">Cerrar</button>
                    </div>
                </div>
            </div>
        </div>
 
    
@endsection

