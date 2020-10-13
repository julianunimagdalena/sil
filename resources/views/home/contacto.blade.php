@extends('master.masterPrincipal')
@section('title', 'Contactenos')
@section('controller','ng-controller="ContatanosCtrl"')
@section('contenido')

<style type="text/css">
	.form-group
	{
		margin-bottom: 25px;
	}

	fieldset
	{
		margin: 15px;
	}	

</style>
	
	<div class="col-sm-12">
		<fieldset>
		    <legend>Contactenos</legend>
			<form role="form" ng-submit="enviar()">
				<div class="form-group col-md-4">
					<label>Nombres</label>
					<input type="text" class="form-control" ng-model="contacto.nombres" placeholder="Nombres">
					<p class="text-danger error" ng-if="errores.nombres.length > 0">
						<span>
							@{{ errores.nombres[0] }}							
						</span>						
					</p>
				</div>
				<div class="form-group col-md-4">
					<label>Apellidos</label>
					<input type="text" class="form-control" ng-model="contacto.apellidos" placeholder="Apellidos">
					<p class="text-danger error" ng-if="errores.apellidos.length > 0">
						<span>
							@{{ errores.apellidos[0] }}
						</span>
					</p>
				</div>

				<div class="form-group col-md-4">
					<label>Correo electrónico</label>
					<input type="email" class="form-control" ng-model="contacto.correo" placeholder="Correo electrónico">
					<p class="text-danger error" ng-if="errores.correo.length > 0">
						<span>
							@{{ errores.correo[0] }}
						</span>
					</p>
				</div>

				<div class="form-group col-md-4">
					<label>Tipo de identificación</label>
					<select class="form-control" ng-model="contacto.tipo_de_identificacion">
						<option value="" hidden="">Seleccionar</option>
						<option value="1">Tarjeta de identidad</option>
						<option value="2">Cédula de ciudadanía</option>
						<option value="3">Cédula de extranjería</option>
					</select>
					<p class="text-danger error" ng-if="errores.tipo_de_identificacion.length > 0">
						<span>
							@{{ errores.tipo_de_identificacion[0] }}
						</span>
					</p>
				</div>
				<div class="form-group col-md-4">
					<label>Número de identificación</label>
					<input type="number" class="form-control" ng-model="contacto.identificacion" placeholder="Identificación">
					<p class="text-danger error" ng-if="errores.identificacion.length > 0">
						<span>
							@{{ errores.identificacion[0] }}
						</span>
					</p>
				</div>

				<div class="form-group col-md-4">
					<label>Número de celular</label>
					<input type="number" class="form-control" ng-model="contacto.celular" placeholder="Número de celular">
					<p class="text-danger error" ng-if="errores.celular.length > 0">
						<span>
							@{{ errores.celular[0] }}
						</span>
					</p>
				</div>
				

				<div class="form-group col-md-12">
					<label>Código de verificación</label>		
					<div class="col-md-12">
						<div class="col-md-3 text-center" style="font-size: 2em; color: red;">@{{contacto.capcha}}</div>
						<div class="col-md-1 text-center" style="font-size: 2em; color: red;"><span class="glyphicon glyphicon-refresh blue pointer" title="Actualizar código" ng-click="refresh()"></span></div>			
						<div class="col-md-8">
							<input type="text" class="form-control" ng-model="contacto.codigo_de_verificacion" placeholder="Código de verificación">	
							<p class="text-danger error" ng-if="errores.codigo_de_verificacion.length > 0">
								<span>
									@{{ errores.codigo_de_verificacion[0] }}
								</span>
							</p>
						</div>
						
					</div>		
				</div>
				<div class="form-group">
					<label>Comentario</label>
					<textarea rows="7" class="form-control" ng-model="contacto.comentario" placeholder="Comentario"></textarea>
					<p class="text-danger error" ng-if="errores.comentario.length > 0">
						<span>
							@{{ errores.comentario[0] }}
						</span>
					</p>
				</div>
				<br>
				<center>
					<button type="submit" class="btn btn-success">Enviar</button>
				</center>
				
			</form>
		</fieldset>
	</div>

		

@endsection