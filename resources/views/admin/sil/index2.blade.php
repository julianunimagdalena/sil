@extends('master._adminLayout')

@section('title', 'Index')

@section('content')

<div class="row" >
	<!-- ng-controller="SilIndexCtrl" -->
<style type="text/css">
	a
	{
		color:#fff !important;		
	}
	a:hover
	{
		text-decoration: none;		
	}
</style>
	<div class="col-md-2"></div>
	<div class="col-md-8" style="text-align: center;">
		<div class="col-md-4">
			<a href="{{asset('/sil/usuarios')}}" class="fa-2x">				
				<i class="fa fa-5x fa-users" aria-hidden="true"></i>
			</a>
			<br>
			<a href="{{asset('/sil/usuarios')}}">
				<b class="blue">
					Administrar usuarios
				</b>
			</a>				
		</div>
		<div class="col-md-4">
			<a href="{{asset('/adminsil/empresas')}}" class="fa-2x">
				<i class="fa fa-5x fa fa-building" aria-hidden="true"></i>
			</a>
			<br>
			<a href="{{asset('/adminsil/empresas')}}">
				<b class="blue">
					Administrar empresas				
				</b>
			</a>				
		</div>				
		<div class="col-md-4">
			<a href="{{asset('/adminsil/ofertas')}}" class="fa-2x">
				<i class="fa fa-5x fa-briefcase" aria-hidden="true"></i>
			</a>
			<br>
			<a href="{{asset('/adminsil/ofertas')}}">
				<b class="blue">
					Ofertas laborales			
				</b>
			</a>				
		</div>
		<div class="col-md-4">
			<a href="{{asset('/sil/hojasdevida')}}" class="fa-2x">
				<i class="fa fa-5x fa-id-card-o" aria-hidden="true"></i>
			</a>
			<br>
			<a href="{{asset('/sil/hojasdevida')}}">
				<b class="blue">
					Hoja de vida				
				</b>
			</a>
		</div>		
		<div class="col-md-4">
			<a href="{{asset('/novedad')}}" class="fa-2x">
				<i class="fa fa-5x fa-comments" aria-hidden="true"></i>
			</a>
			<br>
			<a href="{{asset('/novedad')}}">
				<b class="blue">
					Novedades				
				</b>
			</a>				
		</div>
		
	</div>
	<div class="col-md-2"></div>
	
</div>

@stop