@extends('master.master')

@section('title', 'Inicio')

@section('contenido')

<div class="row" ng-controller="programaIndexCtrl">
    
    <div class="col-md-1"></div>
    <div class="col-md-10">
        <div class="col-md-12">
            <b>Programa:</b> @{{ programa.nombre}} <br>
            <b>Código programa:</b> @{{ programa.codigoPrograma}} <br>
            <b>Código de prácticas:</b> @{{ programa.codigoPracticas}} <br>
            <span class="blue pointer" ng-click="mostrar=!mostrar">Actualizar código de prácticas</span> <br><br>
            <form role="form" class="form-inline" ng-if="mostrar" ng-submit="cambiarCodigoPRacticas()">
                <div class="form-group">
                    <input type="text" ng-model="programa.codigo_practicas" placeholder="Código de prácticas" class="form-control"/>
                    <p class="help-block text-danger">
                        @{{ errores.codigo_practicas[0] }}
                    </p>
                </div>
                
                <input type="submit" value="Actualizar" class="btn btn-success"/>
            </form>
            
            
        </div>
        <table  object-table
        	data = "practicantes"
        	display = "10"
        	headers = "Código, Nombres, Apellidos,Programa,Modalidad, Empresa"
        	fields = "getempresa.nit,getempresa.nombre,getmunicipio.getdepartamento.getpais.nombre,getmunicipio.getdepartamento.nombre,getmunicipio.nombre, getempresa.getestadodipro.nombre"
        	sorting = "compound"
        	editable = "false"
        	resize="false"
        	drag-columns="false">
            <tbody>
                <td>
                    @{{ ::item.codigo }}
                </td>
                <td>
                    @{{ ::item.getpersona.nombres | uppercase }}
                </td>
                <td>
                    @{{ ::item.getpersona.apellidos | uppercase }}
                </td>
                <td>
                    @{{ ::item.getprograma.nombre | uppercase }}
                </td>
                <td>
                    @{{ ::item.getpracticas[ item.getpracticas.length - 1].getmodalidad.nombre | uppercase }}
                </td>
                <td>
                    @{{ ::item.getpostulaciones[ item.getpostulaciones.length - 1].getoferta.getsede.getempresa.nombre | uppercase }}
                </td>
            </tbody>
        </table>
    </div>
    <div class="col-md-1"></div>
    
</div>

@stop