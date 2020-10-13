@extends('master.master')

@section('title', 'Prácticas')

@section('contenido')

<div class="row" ng-controller="EstLegalizarCtrl">
    
    <div class="col-md-2"></div>
    <div class="col-md-8">
        @if(Session('success') != null)
        <div class="alert alert-success alert-dismissable">
            <button type="button" class="close" data-dismiss="alert">&times;</button>
            {{ Session('success') }}
        </div>
        @endif
        @if(Session('error') != null)
        <div class="alert alert-danger alert-dismissable">
            <button type="button" class="close" data-dismiss="alert">&times;</button>
            {{ Session('error') }}
        </div>
        @endif
        <fieldset>
            <legend>Legalizar prácticas</legend>
            <form role="form" action="/estudiante/legalizar" method="POST" enctype="multipart/form-data">
                @if($oferta->arl)
                <div class="form-group">
                    <label>Aseguradora de ARL</label>
                    <input type="text" class="form-control" name="nombre_arl" placeholder="Aseguradora de ARL" value="{{ old('nombre_arl') }}">
                    @if($errors->first('nombre_arl'))<p class="help-block text-danger">{{$errors->first('nombre_arl')}}</p>@endif
                </div>
                <br>
                <div class="form-group">
                    <label for="ejemplo_archivo_1">Certificado ARL</label>
                    <input type="file" name="certificado_arl">
                    <p class="help-block">Solo formato pdf y tamaño menor o igual a 1MB.</p>
                    @if($errors->first('certificado_arl'))<p class="help-block text-danger">{{$errors->first('certificado_arl')}}</p>@endif
                </div>
                <br>
                @endif
                <div class="form-group">
                    <label for="ejemplo_archivo_1">Certificado de salud</label>
                    <input type="file" name="certificado_salud">
                    <p class="help-block">Solo formato pdf y tamaño menor o igual a 1MB.</p>
                    @if($errors->first('certificado_salud'))<p class="help-block text-danger">{{$errors->first('certificado_salud')}}</p>@endif
                </div>
                <br>
                <div class="form-group">
                    <label>Horario</label>
                    <textarea name="horario" class="form-control noresize" placeholder="Horario en el que realiza la práctica" value="{{ old('horario') }}" id="horario" cols="30" rows="5"></textarea>
                    @if($errors->first('horario'))<p class="help-block text-danger">{{$errors->first('horario')}}</p>@endif
                </div>
                <br>
                <div class="checkbox">
                    <label>
                        <input type="checkbox" name="aprobacion_estudiante"> 
                        Confirmo que la información diligenciada durante mi proceso de prácticas es verídica.
                        <p class="help-block">Esta confirmación se tiene en cuenta como su firma.</p>
                        @if($errors->first('aprobacion_estudiante'))<p class="help-block text-danger">{{$errors->first('aprobacion_estudiante')}}</p>@endif
                    </label>
                </div>
                <button type="submit" value="Submit" class="btn btn-success">
                    Guardar
                </button>
            </form>
        </fieldset>
            
        
    </div>
    <div class="col-md-2"></div>
    
</div>

@stop