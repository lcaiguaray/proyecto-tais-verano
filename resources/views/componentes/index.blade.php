@extends('layouts.master')

@section('title', '| Componentes')
@section('item-empresas', 'active')

@section('css_after')
    <!-- Page JS Plugins CSS -->
@endsection

@section('nav_breadcrumd')
    <ol class="breadcrumb has-arrow bg-light rounded">
        <li class="breadcrumb-item"><a href="{{ route('empresas') }}">Empresas</a></li>
        <li class="breadcrumb-item active">Componentes</li>
    </ol>
@endsection

@section('contenido')
    <div class="row">
        <div class="col-lg-8 col-md-8 col-sm-8 col-8">
            <h4><i class="mdi mdi-folder"></i> Componentes</h4>
            <p class="text-gray">Empresa: <span>{{ $empresa->razon_social }}</span></p>
        </div>
    </div>
    <div class="row mt-4">
        <div class="col-md-3 col-sm-6 col-6 equel-grid">
            <div class="grid">
                <div class="grid-body text-gray">
                    <div class="d-flex justify-content-between font-weight-bold">
                        <a href="{{ route('mapa_procesos', $empresa->id) }}" class="text-decoration-none text-reset">
                            <p><i class="mdi mdi-map"></i> Mapa de Procesos</p>
                        </a>
                        <span class="badge badge-primary">4</span>
                    </div>
                    <div class="wrapper w-50 mt-4">
                        <canvas height="45" id="stat-line_1"></canvas>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3 col-sm-6 col-6 equel-grid">
            <div class="grid">
                <div class="grid-body text-gray">
                    <div class="d-flex justify-content-between font-weight-bold">
                        <a href="{{ route('procesos', $empresa->id) }}" class="text-decoration-none text-reset">
                            <p><i class="mdi mdi-sitemap"></i> Procesos</p>
                        </a>
                        <span class="badge badge-primary">4</span>
                    </div>
                    <div class="wrapper w-50 mt-4">
                        <canvas height="45" id="stat-line_2"></canvas>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('js_after')
    <!-- Page JS Plugins JS -->
    <script>
        let Notificacion = {!! json_encode(session('notificacion')) !!}
        let IDMapaProceso = null

        $(document).ready( function () {
            
            if(Notificacion) customNotification(Notificacion.message, Notificacion.theme, Notificacion.type)
        });
        
        // NOTIFICATION
        function customNotification(mensaje, theme, type){
            new Noty({
                text: mensaje,
                theme: theme,
                type: type,
                timeout: 4500,
                progressBar: true,
                animation: {
                    open: 'animated bounceInRight', // Animate.css class names
                    close: 'animated bounceOutRight' // Animate.css class names
                }
            }).show();
        }
        // END NOTIFICATION
    </script>
@endsection