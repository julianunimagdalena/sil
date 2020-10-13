@extends('master.master')
@section('title', 'Inicio')
@section('contenido')
            
    <div class="row" ng-controller="loginCtrl">

        <div class="modal" tabindex="-1" role="dialog" id="restablecer">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Restablecer contraseña</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <form ng-submit="restablecerC()">
                            <div class="form-group">
                                <label >Nombre de usuario</label>
                                <input type="text" class="form-control" ng-model="restablecer.usuario" aria-describedby="emailHelp" placeholder="Nombre de usuario">
                                <small id="emailHelp" class="form-text text-muted text-danger" ng-if="error.usuario != null">@{{ error.usuario[0] }}</small>
                            </div>
                            <div class="form-group">
                                <label >Correo electrónico</label>
                                <input type="email" class="form-control" ng-model="restablecer.correo" aria-describedby="emailHelp2" placeholder="Correo electrónico">
                                <small id="emailHelp2" class="form-text text-muted">
                                    <span ng-if="error.correo == null">Correo electrónico registrado en nuestra plataforma</span>
                                    <span ng-if="error.correo != null" class="text-danger" >@{{ error.correo[0] }}</span>
                                </small>
                            </div>                            
                            <button type="submit" class="btn btn-primary" ng-show="false">
                                Submit
                            </button>
                        </form>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-rojo" data-dismiss="modal">Cancelar</button>
                        <button type="button" class="btn btn-primary" ng-click="restablecerC()" >Restablecer</button>
                    </div>
                </div>
            </div>
        </div>

        
        <div class="col-md-4">
            <fieldset>
                <legend>
                    <div class="row">
                        <div class="col-md-6">
                            Iniciar sesión
                        </div>
                        <div class="col-md-6 text-center">
                            <a class="btn btr-primary-inverted btn-block radius-none" href="{{asset('/home/registro')}}">
                                Registrate
                            </a>
                        </div>
                    </div>
                </legend>
                <div class="row" >
                    <div class="col-md-1"></div>
                    <div class="col-md-11 separator">
                            
                        <form role="form" ng-submit="login()" method="POST">
                            <div class="row control-group">
                                <div class="form-group col-xs-12 floating-label-form-group controls">
                                    <label>Nombre de usuario</label>
                                    <input type="text" class="form-control mi-input" ng-model="usuario.identificacion" placeholder="Nombre de usuario"  >
                                    <p class="help-block text-danger">@{{ errores.identificacion[0]}}</p>
                                </div>
                            </div>
                            <div class="row control-group">
                                <div class="form-group col-xs-12 floating-label-form-group controls">
                                    <label>Contraseña</label>
                                    <input type="password" class="form-control mi-input" ng-model="usuario.password" placeholder="Contraseña">
                                    <p class="help-block text-danger">@{{ errores.password[0] }}</p>
                                </div>
                            </div>
                            <br>
                            <div id="success"></div>
                            <div class="row">
                                <div class="form-group col-xs-12">
                                    <button type="submit" class="btn btn-success btn-lg btn-block radius-none" ng-click="login()">
                                        Entrar joder
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                    <div class="col-md-12 text-center" data-toggle="modal" data-target="#restablecer">
                        <a class="blue pointer">
                            Olvidó su contraseña
                        </a>
                    </div>
                    @if(false)
                    <div class="col-md-12 text-center">
                        <a href="{{asset('/home/cartas')}}" class="blue">
                            Verificación de cartas de presentación
                        </a>
                    </div>
                    @endif
                </div>
            </fieldset>
                
        </div>
    </div>
    <br>
    <br>
@stop