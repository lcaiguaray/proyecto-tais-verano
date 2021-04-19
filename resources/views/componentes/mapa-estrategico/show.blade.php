@extends('layouts.master')

@section('title', '| Visualizar Indicador')
@section('item-empresas', 'active')

@section('css_after')
    <!-- Page JS Plugins CSS -->
    <link rel="stylesheet" href="{{ asset('assets/plugins/dataTables/DataTables-1.10.24/css/dataTables.bootstrap4.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/plugins/select2/css/select2.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/plugins/flatpickr/flatpickr.css') }}">
@endsection

@section('nav_breadcrumd')
    <ol class="breadcrumb has-arrow bg-light rounded">
        <li class="breadcrumb-item"><a href="{{ route('empresas') }}">Empresas</a></li>
        <li class="breadcrumb-item"><a href="{{ route('empresas.componentes', $empresa->id) }}">Componentes</a></li>
        <li class="breadcrumb-item"><a href="{{ route('estrategias', $empresa->id) }}">Estratégias</a></li>
        <li class="breadcrumb-item active">Visualizar</li>
    </ol>
@endsection

@section('contenido')
    <div class="row">
        <div class="col-lg-8 col-md-8 col-sm-8 col-8">
            <h4><i class="mdi mdi-table-large"></i> Visualizar</h4>
            <p class="text-gray">Visualizar Mapa</p>
        </div>
    </div>
    <div class="row mt-4">
        <div class="col-12 equel-grid">
            <div class="grid">
                <div class="grid-body">
                    <div class="item-wrapper">
                        <div class="form-row mb-4">
                            <div class="col-12">
                                <p>Ligado al Proceso/SubProceso <span class="badge badge-warning"> </span></p>
                            </div>
                            <div class="col-12">
                                <p>No Ligado al Proceso/SubProceso <span class="badge badge-secondary"> </span></p>
                            </div>
                        </div>
                        <div class="table-responsive">
                            <table class="table">
                                <tbody>
                                    <tr>
                                        <td class="font-weight-bold text-center text-primary" style="width: 20%;">FINANCIERA</td>
                                        <td>
                                            <div class="form-row">
                                                @foreach ($objeto->estrategia->where('tipo_estrategia', 'F') as $item)
                                                    <div class="col-3 equel-grid">
                                                        <div class="grid-body text-gray rounded-lg shadow bg-{{ ($item->ligado) ? 'warning' : 'secondary' }}">
                                                            <small class="font-weight-bold text-wrap text-break text-center">{{ $item->nombre }}</small>
                                                        </div>
                                                    </div>
                                                @endforeach
                                            </div>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="font-weight-bold text-center text-primary" style="width: 20%;">CLIENTES</td>
                                        <td>
                                            <div class="form-row">
                                                @foreach ($objeto->estrategia->where('tipo_estrategia', 'C') as $item)
                                                    <div class="col-3 equel-grid">
                                                        <div class="grid-body text-gray rounded-lg shadow bg-{{ ($item->ligado) ? 'warning' : 'secondary' }}">
                                                            <small class="font-weight-bold text-wrap text-break text-center">{{ $item->nombre }}</small>
                                                        </div>
                                                    </div>
                                                @endforeach
                                            </div>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="font-weight-bold text-center text-primary" style="width: 20%;">PROCESOS INTERNOS</td>
                                        <td>
                                            <div class="form-row">
                                                @foreach ($objeto->estrategia->where('tipo_estrategia', 'PI') as $item)
                                                    <div class="col-3 equel-grid">
                                                        <div class="grid-body text-gray rounded-lg shadow bg-{{ ($item->ligado) ? 'warning' : 'secondary' }}">
                                                            <small class="font-weight-bold text-wrap text-break text-center">{{ $item->nombre }}</small>
                                                        </div>
                                                    </div>
                                                @endforeach
                                            </div>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="font-weight-bold text-center text-primary" style="width: 20%;">APRENDIZAJE Y CRECIMIENTO</td>
                                        <td>
                                            <div class="form-row">
                                                @foreach ($objeto->estrategia->where('tipo_estrategia', 'AC') as $item)
                                                    <div class="col-3 equel-grid">
                                                        <div class="grid-body text-gray rounded-lg shadow bg-{{ ($item->ligado) ? 'warning' : 'secondary' }}">
                                                            <small class="font-weight-bold text-wrap text-break text-center">{{ $item->nombre }}</small>
                                                        </div>
                                                    </div>
                                                @endforeach
                                            </div>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('js_after')
    <!-- Page JS Plugins JS -->
    <script src="{{ asset('assets/plugins/dataTables/DataTables-1.10.24/js/jquery.dataTables.js') }}"></script>
    <script src="{{ asset('assets/plugins/dataTables/DataTables-1.10.24/js/dataTables.bootstrap4.js') }}"></script>
    <script src="{{ asset('assets/plugins/select2/js/select2.full.js') }}"></script>
    <script src="{{ asset('assets/plugins/flatpickr/flatpickr.js') }}"></script>
    <script src="{{ asset('assets/plugins/flatpickr/l10n/es.js') }}"></script>
    <script>
        const table = $('#table_fuentes')
        let IDData = null

        $(document).ready(function(){

            $('.validar_numerico').on('keypress', validaNumerico)
            $('.validar_decimal').on('keypress', validarDecimal)
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

        function validaNumerico(event) {
            if(event.charCode >= 48 && event.charCode <= 57) return true;
            else return false;
        }

        function validarDecimal(e){
            let key = window.Event ? e.which : e.keyCode;    
            let letra = String.fromCharCode(key);
            let value = $(this).val() + letra;
            let preg = /^([0-9]+\.?[0-9]{0,2})$/;
            
            if(key >= 48 && key <= 57){
                if(preg.test(value)) return true;
                else return false;
            }else{
                if(key == 46){
                    if(preg.test(value)) return true;
                    else return false;
                }else{
                    return false;
                }
            }
        }
    </script>
@endsection