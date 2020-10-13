@extends('master.masterPrincipal')
@section('title', 'Inicio')
@section('controller','ng-controller="loginCtrl"')

@section('contenido')

<style>
    li[role="presentation"].active {
        border-bottom: 2px solid #c08e2d;
    }
</style>

<main id="content-main">
    <div class="container">
        <div class="row">
            <div class="col-xs-12 col-md-8 col-lg-9">
                <article>
                    <div class="container">
                        <div class="row">
                            <div class="col-xs-12 col-md-5">
                               <h2>Información</h2>
                                <!-- <p>Vinculado a la red de prestadores del Servicio Público de Empleo. Autorizado por la Resolución N° 687 de agosto 22 de 2017.</p>
                                <img src="{{asset('/img/serviciomin.png')}}" width="100%">-->
                                    <div class="alert alert-info text-left">
                                        <strong>Egresado!</strong> Tu usuario es el número de documento y contraseña generada cuando realizo el registro.
                                        <br>
                                        <strong>Empresa!</strong> Tu usuario es el número de Nit y contraseña generada cuando realizo el registro.
                                    </div>
                            </div>
                            <div class="col-xs-12 col-md-7">
                                <h2>Acceso</h2>
                                <div class="Tabs">
                                    <ul class="nav nav-tabs Tabs-navs" role="tablist">
                                        <li role="presentation" class="Tabs-nav login tabs-nav-login active">
                                            <a id="tab-login" href="#login" target="_self" aria-controls="login" role="tab" data-toggle="tab">
                                                <i class="fa fa-sign-in fa-lg" aria-hidden="true"></i> Iniciar sesión
                                            </a>
                                        </li>
                                        <li role="presentation" class="Tabs-nav recuperarContraseña tabs-nav-Index">
                                            <a id="tab-recuperarContraseña" href="#recuperarContraseña" target="_self" aria-controls="recuperarContraseña" role="tab" data-toggle="tab">
                                             <i class="fa fa-unlock-alt fa-lg" aria-hidden="true"></i> Recuperar Contraseña
                                             </a>
                                        </li>
                                        <li role="presentation" class="Tabs-nav registrate tabs-nav-Indexex">
                                            <a id="tab-Registrate" href="{{asset('/home/registro')}}">
                                             <i class="fa fa-hand-pointer-o fa-lg" aria-hidden="true"></i> Registrate
                                             </a>
                                        </li>
                                    </ul>
                                        <div class="tab-content Tabs-content">
                                            <div role="tabpanel" class="tab-pane Tabs-pane tab-login active" id="login" style="border: 1px solid #ddd; border-top: none; padding-bottom: 15px;">
                                                <input type="hidden" ng-model="id" ng-init="" />
                                                <div class="row">
                                                    <div class="col-xs-8 col-xs-offset-2">
                                                        <div class="card padding login text-center">
                                                            <form role="form" ng-submit="login()" method="POST">
                                                                <br>
                                                                <div class="form-group">
                                                                    <label class="sr-only" for="text">Correo electrónico</label>
                                                                    <input type="text" class="form-control mi-input" ng-model="usuario.identificacion" placeholder="Usuario">

                                                                </div>
                                                                <div class="form-group">
                                                                    <label class="sr-only" for="Password">Contraseña</label>
                                                                    <input type="password" class="form-control mi-input" ng-model="usuario.password" placeholder="Contraseña">
                                                                </div>
                                                                <div class="form-group" ng-if="roles.length > 0">
                                                                    <label>Seleccione un rol</label>
                                                                    <select class="form-control" ng-model="usuario.rol" ng-options="item as item.nombre for item in roles">
                                                                        <option value="" selected hidden>Seleccione una opción</option>
                                                                    </select>
                                                                </div>
                                                                <div class="form-row">
                                                                    <div class="text-center">
                                                                        <button class="btn btn-success" type="submit">
                                                                            <i class="fa fa-sign-in fa-lg">
                                                                            </i> Iniciar sesión
                                                                        </button>
                                                                    </div>
                                                                </div>
                                                            </form>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div role="tabpanel" class="tab-pane Tabs-pane tab-recuperaContraseña" id="recuperarContraseña" style="border: 1px solid #ddd; border-top: none; padding-bottom: 15px;">
                                                <div class="row">
                                                    <div class="col-xs-8 col-xs-offset-2">
                                                        <div class="card padding login text-center">
                                                            <br>
                                                            <form ng-submit="restablecerC()">
                                                                <div class="form-group">
                                                                    <input type="text" class="form-control" ng-model="restablecer.usuario" aria-describedby="emailHelp" placeholder="Usuario">
                                                                    <small id="emailHelp" class="form-text text-muted text-danger" ng-if="error.usuario != null">@{{ error.usuario[0] }}</small>
                                                                </div>
                                                                <div class="form-group">
                                                                    <input type="email" class="form-control" ng-model="restablecer.correo" aria-describedby="emailHelp2" placeholder="Correo electrónico">
                                                                    <small id="emailHelp2" class="form-text text-muted">
                                                                        <span ng-if="error.correo == null">Correo electrónico registrado en nuestra plataforma</span>
                                                                        <span ng-if="error.correo != null" class="text-danger" >@{{ error.correo[0] }}</span>
                                                                    </small>
                                                                </div>
                                                                <div class="text-center">
                                                                    <button class="btn btn-success" type="submit">
                                                                        <i class="fa fa-sign-in fa-lg">
                                                                        </i> Restablecer
                                                                    </button>
                                                                </div>
                                                            </form>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                </div>
                            </div>
                        </div>
                        <br>
                        <hr>
                        <h4 style="text-align: center;">Vinculado a la red de prestadores del Servicio Público de Empleo. Autorizado por la Resolución N° 687 de agosto 22 de 2017.</h4>
                        <div class="row">
                            <div class="col-xs-2"></div>
                            <div class="col-xs-8" style="text-align:center;" >
                               <img src="{{asset('/img/ServiEmpleo.png')}}" width="100%">
                            </div>
                            <div class="col-xs-2"></div>
                        </div>
                        <br><br>
                    </div>
                </article>
            </div>
        </div>
    </div>
</main>

<!------MODALL--------->
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
                            <button type="submit" class="btn btn-primary" ng-show="false">Submit</button>
                        </form>
                    </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-rojo" data-dismiss="modal">Cancelar</button>
                    <button type="button" class="btn btn-primary" ng-click="restablecerC()" >Restablecer</button>
                </div>
            </div>
        </div>
</div>

@stop