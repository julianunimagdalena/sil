@extends('master._adminLayoutNew')
@section('titulo', 'Tablero de Graduado - ')
@section('controller','ng-controller ="GraduadoConfigCtrl"')
@section('title')
    <h2>Tablero de Graduado</h2>
@endsection

@section('estilos')

	<style>
		table {
  			font-family: arial, sans-serif;
  			border-collapse: collapse;
  			width: 100%;
		}

		td, th {
		  border: 1px solid #dddddd;
		  text-align: left;
		  padding: 8px;
		  font-weight: bold; 
		}

		#link-config * {
			color: #fff;
		}
	</style>
  
@endsection

@section('content')

<h3 style="color: #004A87; font-weight: 700">
   CONFIGURACION DE LA CUENTA
    @section('tituloVista', 'CONFIGURACION DE LA CUENTA')
</h3>
<br>
{{-- <div class="row">
	<div class="col-sm-2"></div>
	<div class="col-sm-8">
		<table class="table" >
			<tr>
				<td>
					¿Hacer publica su hoja de vida para que las empresas pueden ver sus datos básicos?
				</td>
				<td>
					<input type="radio" name="hojavida" value="1" ng-click="visibilidadHojavida()" ng-model="config.getuser.gethojadevida[0].activa"> Si
					<input type="radio" name="hojavida" value="0" ng-click="visibilidadHojavida()" ng-model="config.getuser.gethojadevida[0].activa"> No
				</td>
				<td>
					<div class="custom-control custom-switch ml-auto">
						<input type="checkbox" class="custom-control-input" ng-true-value="'1'" ng-click="visibilidadHojavida()" ng-false-value="'0'" ng-model="config.getuser.gethojadevida[0].activa" id="activa">
						<label class="custom-control-label" for="activa"></label>
					</div>
				</td>
			</tr>
			<tr>
				<td>
					¿Recibir correos electrónicos con ofertas de empleo?
				</td>
				<td>
					<input type="radio" name="correos" value="1" ng-change="recibirMails()" ng-model="config.getuser.recibir_mails"> Si
					<input type="radio" name="correos" value="0" ng-change="recibirMails()" ng-model="config.getuser.recibir_mails"> No
				</td>
			</tr>
			<tr>
				<td>
					¿Estado actual de la cuenta?
				</td>
				<td>
					<input type="radio" name="cuenta" value="1" ng-model="config.activo"> Activa
					<input type="radio" name="cuenta" value="0" ng-model="config.activo" ng-change="cuenta()"> Inactiva
				</td>
			</tr>
		</table>
	</div>
	<div class="col-sm-2"></div>	
	
</div> --}}
<div class="container">
	<form>
		<div class="form-group d-flex">
			<label>¿Hacer publica su hoja de vida para que las empresas pueden ver sus datos básicos?</label>
			<div class="custom-control custom-switch ml-3">
				<input type="checkbox" class="custom-control-input" ng-true-value="'1'" ng-click="visibilidadHojavida()" ng-false-value="'0'" ng-model="config.getuser.gethojadevida[0].activa" id="activa">
				<label class="custom-control-label" for="activa"></label>
			</div>
		</div>
		<div class="form-group d-flex">
			<label>¿Recibir correos electrónicos con ofertas de empleo?</label>
			<div class="custom-control custom-switch ml-3">
				<input type="checkbox" class="custom-control-input" ng-click="recibirMails()" ng-model="config.getuser.recibir_mails" id="recibir_mails">
				<label class="custom-control-label" for="recibir_mails"></label>
			</div>
		</div>
	{{-- 	<div class="form-group d-flex">
			<label>¿Estado actual de la cuenta?</label>
			<div class="custom-control custom-switch ml-3">
				<input type="checkbox" class="custom-control-input" ng-click="recibirMails()" ng-true-value="'1'" ng-false-value="'0'" ng-model="config.activo" id="activo">
				<label style="font-weight: 400;" class="custom-control-label" for="activo">@{{ config.activo == '1' ? 'Activa':'Inactiva' }}</label>
			</div>
		</div> --}}
	</form>
</div>

@endsection