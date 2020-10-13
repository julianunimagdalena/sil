@extends('master._adminLayoutNew')
@section('titulo', 'Cambiar contraseña')
@section('controller','ng-controller ="CambiarClaveCtrl"')
@section('title')
    @if(Auth::check())
        @if (session('rol')->nombre == 'Administrador Egresados')
            <h2>Tablero de administrador</h2>
        @endif
        @if (session('rol')->nombre == 'Graduado')
            <h2>Tablero de graduado</h2>
        @endif
        @if (session('rol')->nombre == 'Empresa')
            <h2>Tablero de Empresa</h2>
        @endif

    @endif

@endsection

@section('estilos')
<style>
    .md-tabs.md-default-theme .md-tab.md-active, md-tabs .md-tab.md-active{
        color:white; 
    }

    #link-clave * {
        color: #fff;
    }
</style>

@endsection

@section('left')
  
@endsection

@section('content')
@section('tituloVista', 'CAMBIAR CONTRASEÑA')
<h3 style="color: #004A87; font-weight: 700">CAMBIAR CONTRASEÑA</h3>

<br>
<div class="row">
	<div class="col-sm-4"></div>
    <div class="col-md-4">
        <div ng-cloak>
            <md-content class="bordes" >
                <md-tabs md-dynamic-height md-border-bottom style="width:100%;border-radius: 10px;background-color: #004a87;">
                    <md-tab style="color: white !important;" label="Cambiar contraseña" >
                        <md-content class="md-padding" style="background-color: #f1f2f9;">
                            <form role="form" ng-submit="cambioclave()">
                                <div class="col-md-12">
                                    <md-input-container class="md-block" flex-gt-sm>
                                        <label >Contraseña actual</label>
                                        <input type="password" ng-model="user.actual">
                                        <p class="help-block text-danger" style="color: red">
                                            @{{ errores['actual'][0] }}
                                        </p>
                                    </md-input-container> 
                                </div>
                                <div class="col-md-12">
                                     <md-input-container class="md-block"flex-gt-sm>
                                        <label>Nueva contraseña</label>
                                        <input type="password" ng-model="user.nueva">
                                        <p class="help-block text-danger" style="color: red">
                                            @{{ errores['nueva'][0] }}
                                        </p>
                                    </md-input-container>
                                </div>
                                <div class="col-md-12">
                                    <md-input-container class="md-block" flex-gt-sm>
                                        <label>Confirmar contraseña</label>
                                        <input type="password" ng-model="user.confirmacion">
                                        <p class="help-block text-danger" style="color: red">
                                            @{{ errores['confirmacion'][0] }}
                                        </p>
                                    </md-input-container> 
                                </div>
                                <div class="col-lg-12">
                                    <button type="submit" class="btn btn-success">
                                        Cambiar contraseña
                                    </button>
                                </div>
                            </form>
                        </md-content>
                    </md-tab>
                </md-tabs>
            </md-content>
        </div>
    </div>
	<div class="col-sm-4"></div>
</div>

@endsection