@extends('layouts.master')

@section('title', '| Indicadores')
@section('item-empresas', 'active')

@section('css_after')
    <!-- Page JS Plugins CSS -->
    <link rel="stylesheet" href="{{ asset('assets/plugins/dataTables/DataTables-1.10.24/css/dataTables.bootstrap4.css') }}">
@endsection

@section('nav_breadcrumd')
    <ol class="breadcrumb has-arrow bg-light rounded">
        <li class="breadcrumb-item"><a href="{{ route('empresas') }}">Empresas</a></li>
        <li class="breadcrumb-item"><a href="{{ route('empresas.componentes', $empresa->id) }}">Componentes</a></li>
        <li class="breadcrumb-item active">Indicadores</li>
    </ol>
@endsection

@section('contenido')
    <div class="row">
        <div class="col-lg-8 col-md-8 col-sm-8 col-8">
            <h4><i class="mdi mdi-apple-safari"></i> Indicadores</h4>
            <p class="text-gray">Lista de indicadores</p>
        </div>
        <div class="col-lg-4 col-md-4 col-sm-4 col-4">
            <a href="{{ route('indicadores.create', $empresa->id) }}" class="btn btn-inverse-success float-right float-xs-none has-icon"><i class="mdi mdi-library-plus"></i> Agregar Nuevo</a>
        </div>
    </div>
    <div class="row mt-4 equel-grid">
        <div class="col-12">
            <div class="grid">
                <div class="grid-body">
                    <div class="table-responsive">
                        <table id="table_indicadores" class="table" style="width:100%">
                            <thead>
                                <tr>
                                    <th></th>
                                    <th class="text-center" style="width: 20%">Nombre</th>
                                    <th class="text-center" style="width: 15%">Responsable</th>
                                    <th class="text-center" style="width: 30%">Tipo</th>
                                    <th class="text-center" style="width: 15%">Proceso/Subproceso</th>
                                    <th class="text-center" style="width: 20%">Acciones</th>
                                </tr>
                            </thead>
                            <tfoot>
                                <tr>
                                    <th></th>
                                    <th class="text-center" style="width: 20%">Nombre</th>
                                    <th class="text-center" style="width: 15%">Responsable</th>
                                    <th class="text-center" style="width: 30%">Tipo</th>
                                    <th class="text-center" style="width: 15%">Proceso/Subproceso</th>
                                    <th class="text-center" style="width: 15%">Acciones</th>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @include('componentes.indicadores.modals.delete')

@endsection

@section('js_after')
    <!-- Page JS Plugins JS -->
    <script src="{{ asset('assets/plugins/dataTables/DataTables-1.10.24/js/jquery.dataTables.js') }}"></script>
    <script src="{{ asset('assets/plugins/dataTables/DataTables-1.10.24/js/dataTables.bootstrap4.js') }}"></script>
    <script>
        const table = $('#table_indicadores')
        let Notificacion = {!! json_encode(session('notificacion')) !!}
        let IDIndicador = null

        $(document).ready( function () {
            constructDatatable(false)

            // SHOW MODALS
            $('#modal-delete').on('show.bs.modal', showModalDelete)

            // FORMULARIOS
            $('#formDelete').on('submit', submitFormDelete)

            if(Notificacion) customNotification(Notificacion.message, Notificacion.theme, Notificacion.type)
        });

        // DATATABLE
        function constructDatatable(isDestroy) {
            let enlace = "{{ route('indicadores.datatable_datos', ':id') }}"
            enlace = enlace.replace(':id', {!! json_encode($empresa->id) !!})
            if (isDestroy)
                table.DataTable().destroy();

            table.DataTable({
                responsive: true,
                autoWidth: false,
                pageLength: 20,
                lengthMenu: [[20, 50, 100], [20, 50, 100]],
                processing:true,
                "ajax": enlace,
                language: {
                    url: '{{ asset("datatable_español.json") }}'
                },
                "info": false,
                "columns": [
                    {data: 'mapa_proceso_id', class: 'text-center'},
                    {data: 'nombre', class: 'text-center text-wrap'},
                    {data: 'responsable', class: 'text-center text-wrap'},
                    {data: 'objeto_tipo', class: 'text-center text-wrap'},
                    {data: 'objeto_id', class: 'text-center text-wrap'},
                    {data: 'actions', class: 'text-center'}
                ],
                "columnDefs": [{
                    "targets": [ 0 ],
                    "visible": false
                }],
                "drawCallback": function ( settings ) {
                    var api = this.api();
                    var rows = api.rows({page:'current'}).nodes();
                    var last=null;
        
                    api.column(0, {page:'current'}).data().each( function ( group, i ) {
                        if ( last !== group ) {
                            $(rows).eq( i ).before(
                                '<tr class="group font-weight-bold table-success font-size-sm"><td colspan="5">'+group+'</td></tr>'
                            );
        
                            last = group;
                        }
                    });
                }
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

        // MODALS
        function showModalDelete(event) {
            var button = $(event.relatedTarget) // Button that triggered the modal
            IDIndicador = button.data('id')
            var nombre = button.data('nombre')
            
            var modal = $(this)
            modal.find('#mensaje_delete').text(nombre)
        }
        // END MODALS

        // SUBMITS
        function submitFormDelete(e){
            e.preventDefault();
            let enlace = "{{ route('empresas.delete', ':id') }}"
            enlace = enlace.replace(':id', IDIndicador)

            $.ajax({
                type: "PUT",
                url: enlace,
                data: $('#formDelete').serialize(),
                success: function(response){
                    if(response.error){
                        $('.modal').modal('hide')
                        customNotification(response.message, response.theme, response.type)
                    }else{
                        constructDatatable(true)
                        $('.modal').modal('hide')
                        customNotification(response.message, response.theme, response.type)
                    }
                },
                error: function(error){
                    console.log(error)
                }
            });
        }
        // END SUBMITS
    </script>
@endsection