@extends('master.master')

@section('title', 'Cambiar contraseña')

@section('contenido')

<div class="row" ng-controller="GenerarClaveCtrl">

	<div class="col-md-4"></div>
	<div class="col-md-4">
		<form role="form" ng-submit="generarclave()">
			<div class="form-group">
				<label>Nueva contraseña</label>
				<input type="password" ng-model="user.nueva" class="form-control" placeholder="Nueva contraseña">
				<p class="help-block text-danger">
                    @{{ errores['nueva'][0] }}
                </p>
			</div>
			<br>
			<div class="form-group">
				<label>Confirmar contraseña</label>
				<input type="password" ng-model="user.confirmacion" class="form-control" placeholder="Nueva contraseña">
				<p class="help-block text-danger">
                    @{{ errores['confirmacion'][0] }}
                </p>
			</div>
			<br>
			<button type="submit" class="btn btn-success">
				Cambiar contraseña
			</button>
			
		</form>
	</div>
	<div class="col-md-4"></div>
	
</div>

@stop