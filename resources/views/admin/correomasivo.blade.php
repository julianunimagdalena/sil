@extends('master.master')

@section('title', 'Envío de correos')

@section('contenido')


<div class="row" ng-controller="CorreoMasivoCtrl">
    
    <div class="col-md-1"></div>
    <div class="col-md-10">
        <form role="form" ng-submit="enviar()">
            <div class="form-group">
                <label>Roles</label>
                <ui-select multiple ng-model="correo.roles" sortable="true" close-on-select="false" ng-change="changeRoles()">
                    <ui-select-match placeholder="Seleccionar">@{{$item.nombre}}</ui-select-match>
                    <ui-select-choices repeat="item in (roles | filter: $select.search) track by item.id">
                        <small>
                            @{{item.nombre}}
                        </small>
                    </ui-select-choices>
                </ui-select>
                <p class="help-block text-danger">
                    @{{ errores.roles[0] }}
                </p>
            </div>
            <div class="form-group" ng-if="mostrar">
                <label>Programas</label>
                <ui-select multiple ng-model="correo.programas" sortable="true" close-on-select="false" ng-change="changeProgramas()">
                    <ui-select-match placeholder="Seleccionar">@{{$item.nombre}}</ui-select-match>
                    <ui-select-choices repeat="item in (programas | filter: $select.search) track by item.id">
                        <small>
                            @{{item.nombre}}
                        </small>
                    </ui-select-choices>
                </ui-select>
                <p class="help-block text-danger">
                    @{{ errores.programas[0] }}
                </p>
            </div>
            <div class="form-group">
                <label>Usuarios</label>
                <ui-select multiple ng-model="correo.usuarios" sortable="true" close-on-select="false">
                    <ui-select-match placeholder="Seleccionar">@{{$item.getuser.correo}}</ui-select-match>
                    <ui-select-choices repeat="item in (usuarios | filter: $select.search) track by item.id">
                        <small>
                            @{{item.getuser.nombres+' '+item.getuser.apellidos+' '+item.getuser.correo}}
                        </small>
                    </ui-select-choices>
                </ui-select>
                <p class="help-block text-danger">
                    @{{ errores.usuarios[0] }}
                </p>
            </div>
            <div class="form-group">
                <label>Asunto</label>
                <input type="text" class="form-control" placeholder="Asunto" ng-model="correo.asunto">
                <p class="help-block text-danger">
                    @{{ errores.asunto[0] }}
                </p>
            </div>
            <hr>
            <div class="form-group">
                <label >
                    Adjuntar archivo
                </label>
                <input type="file" uploader-model="correo.file_archivo" class="form-control file">
                <p class="help-block text-danger">
                    @{{ errores['file_archivo'][0] }}
                </p>
            </div>
            <div class="form-group">
                <label></label>
                <div ckeditor="options" ng-model="correo.contenido" ready="onReady()"></div>
                <p class="help-block text-danger">
                    @{{ errores.contenido[0] }}
                </p>
            </div>
            <button class="btn btn-success" type="submit">Enviar</button>
        </form>
    </div>
    <div class="col-md-1"></div>
</div>

@stop