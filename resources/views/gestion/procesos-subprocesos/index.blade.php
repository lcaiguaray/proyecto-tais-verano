@extends('layouts.master')

@section('title', '| Procesos')
@section('item-procesos', 'active')

@section('css_after')
    <!-- Page JS Plugins CSS -->
    <link rel="stylesheet" href="{{ asset('assets/plugins/dataTables/DataTables-1.10.24/css/dataTables.bootstrap4.css') }}">
@endsection

@section('contenido')
    <div class="row">
        <div class="col-lg-8 col-md-8 col-sm-8 col-8">
            <h4><i class="mdi mdi-buffer"></i> Empresas</h4>
            <p class="text-gray">Lista de empresas</p>
        </div>
    </div>
    <div class="row mt-4 equel-grid">
        <div class="col-12">
            <div class="grid">
                <div class="grid-body">
                    <div class="table-responsive">
                        <table id="table_empresas" class="table" style="width:100%">
                            <thead>
                                <tr>
                                    <th class="text-center" style="width: 15%">RUC</th>
                                    <th style="width: 30%">Razon Social</th>
                                    <th style="width: 30%">Email</th>
                                    <th class="text-center" style="width: 10%">Estado</th>
                                    <th class="text-center" style="width: 15%">Acciones</th>
                                </tr>
                            </thead>
                            <tfoot>
                                <tr>
                                    <th class="text-center" style="width: 15%">RUC</th>
                                    <th style="width: 30%">Razon Social</th>
                                    <th style="width: 30%">Email</th>
                                    <th class="text-center" style="width: 10%">Estado</th>
                                    <th class="text-center" style="width: 15%">Acciones</th>
                                </tr>
                            </tfoot>
                        </table>
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
    <script>
        const table = $('#table_empresas')
        let Notificacion = {!! json_encode(session('notificacion')) !!}

        $(document).ready( function () {
            constructDatatable(false);
            
            if(Notificacion) customNotification(Notificacion.message, Notificacion.theme, Notificacion.type)
        });

        // DATATABLE
        function constructDatatable(isDestroy) {
            if (isDestroy)
                table.DataTable().destroy();

            table.DataTable({
                responsive: true,
                autoWidth: false,
                pageLength: 20,
                lengthMenu: [[20, 50, 100], [20, 50, 100]],
                processing:true,
                "ajax": "{{ route('procesos.datatable_empresas') }}",
                language: {
                    url: '{{ asset("datatable_español.json") }}'
                },
                "info": false,
                "order": [[ 1, "asc" ]],
                "columns": [
                    {data: 'ruc', class: 'text-center'},
                    {data: 'razon_social'},
                    {data: 'email'},
                    {data: 'activo', class: 'text-center'},
                    {data: 'actions', class: 'text-center'}
                ]
            });
        }
        // END DATATABLE
        
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