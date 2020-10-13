@extends('master._adminLayoutNew')
@section('titulo', 'Tablero de administrador')
@section('controller','ng-controller ="AdminIndicadoresCtrl"')
@section('title')
    <h2>Tablero de administrador</h2>
@endsection

@section('estilos')

    <style>
        #link-indicadores * {
            color: #fff;
        }

        div.card.shadow.mb-4 h6 {
            color: #004A87 !important;
        }
    </style>
    
@endsection

@section('content')

    <h3 style="color: #004A87; font-weight: 700">INDICADORES</h3>
    @section('tituloVista', 'INDICADORES')
    <br>
    <div class="row">
        <div class="col-sm-6">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Graduados por genero</h6>
                </div>
                <div id="generos-chart" class="card-img" style="width: 100%"></div>
                <div class="card-body">
                    <p><strong>Total: </strong>@{{ datos.numeroGraduados }} Graduados registrados en plataforma SIL.</p>
                </div>
            </div>
        </div>
        <div class="col-sm-6">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Empresas por categoria</h6>
                </div>
                <div id="empresas-chart" class="card-img" style="width: 100%"></div>
                <div class="card-body">
                    <p><strong>Total: </strong>@{{ datos.empresas.length }} Empresas registradas en plataforma SIL.</p>
                </div>
            </div>
        </div>
        <div class="col-sm-6">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Convocatorias por estado</h6>
                </div>
                <div id="convocatorias-chart" class="card-img" style="width: 100%"></div>
                <div class="card-body">
                    <p><strong>Total: </strong>@{{ datos.ofertas.length }} Convocatorias registradas en plataforma SIL.</p>
                </div>
            </div>
        </div>
        <div class="col-sm-6">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Empresas por departamento</h6>
                </div>
                <div id="mapa-chart" class="card-img" style="width: 100%"></div>
                <div class="card-body">
                    <p>* Empresas registradas en plataforma SIL</p>
                </div>
            </div>
        </div>
        <div id="map"></div>
    </div>
    <div>
        <a target="_blank" href="{{ Request::root() }}/adminsil/reporte-empresas" class="btn btn-success" style="color: white !important;">
            <i class="fa fa-file-excel-o fa-lg" style="color: white !important;"></i>
            &nbsp;&nbsp;&nbsp;Generar reporte de empresas
        </a>
    </div>
    
@endsection

@section('scripts')

    <script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
    <script async defer src="https://maps.googleapis.com/maps/api/js?key=AIzaSyAwC3mqLgfatGfJ2_jRlvLi6ftdtWds9Aw">
    </script>

@endsection

