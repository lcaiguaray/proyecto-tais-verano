@extends('layouts.master')

@section('title', '| Asignación')
@section('item-asignacion', 'active')

@section('css_after')
    <!-- Page JS Plugins CSS -->
    <link rel="stylesheet" href="{{ asset('assets/plugins/dataTables/DataTables-1.10.24/css/dataTables.bootstrap4.css') }}">
@endsection

@section('contenido')
    <div class="row">
        <div class="col-lg-8 col-md-8 col-sm-8 col-8">
            <h4><i class="mdi mdi-human-handsup"></i> Asignaciones</h4>
            <p class="text-gray">Lista de asignaciones</p>
        </div>
        <div class="col-lg-4 col-md-4 col-sm-4 col-4">
            <a href="{{ route('asignaciones.create') }}" class="btn btn-inverse-success float-right float-xs-none has-icon"><i class="mdi mdi-library-plus"></i> Agregar Nuevo</a>
        </div>
    </div>
    <div class="row mt-4 equel-grid">
        <div class="col-12">
            <div class="grid">
                <div class="grid-body">
                    <div class="table-responsive">
                        <table id="table_asignaciones" class="table" style="width:100%">
                            <thead>
                                <tr>
                                    <th></th>
                                    <th>Usuario</th>
                                    <th class="text-center" style="width: 10%">Estado</th>
                                    <th class="text-center" style="width: 15%">Acciones</th>
                                </tr>
                            </thead>
                            <tfoot>
                                <tr>
                                    <th></th>
                                    <th>Usuario</th>
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

    @include('gestion.asignacion.modals.delete')
    @include('gestion.asignacion.modals.active')
@endsection

@section('js_after')
    <!-- Page JS Plugins JS -->
    <script src="{{ asset('assets/plugins/dataTables/DataTables-1.10.24/js/jquery.dataTables.js') }}"></script>
    <script src="{{ asset('assets/plugins/dataTables/DataTables-1.10.24/js/dataTables.bootstrap4.js') }}"></script>
    <script>
        const table = $('#table_asignaciones')
        let Notificacion = {!! json_encode(session('notificacion')) !!}
        let IDAsignacion = null

        $(document).ready( function () {
            constructDatatable(false);

            // SHOW MODALS
            $('#modal-delete').on('show.bs.modal', showModalDelete)
            $('#modal-active').on('show.bs.modal', showModalActive)

            // FORMULARIOS
            $('#formDelete').on('submit', submitFormDelete);
            $('#formActive').on('submit', submitFormActive);
            
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
                "ajax": "{{ route('asignaciones.datatable_datos') }}",
                language: {
                    url: '{{ asset("datatable_español.json") }}'
                },
                "info": false,
                "columns": [
                    {data: 'empresa_id'},
                    {data: 'usuario_id'},
                    {data: 'activo', class: 'text-center'},
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
                                '<tr class="group font-weight-bold table-success font-size-sm"><td colspan="3">'+group+'</td></tr>'
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
            IDAsignacion = button.data('id')
            var nombre = button.data('nombre')
            
            var modal = $(this)
            modal.find('#mensaje_delete').text(nombre)
        }

        function showModalActive(event) {
            var button = $(event.relatedTarget) // Button that triggered the modal
            IDAsignacion = button.data('id')
            var razon_social = button.data('nombre')

            var modal = $(this)
            modal.find('#mensaje_active').text(razon_social)
        }
        // END MODALS

        // SUBMITS
        function submitFormDelete(e){
            e.preventDefault();
            let enlace = "{{ route('asignaciones.delete', ':id') }}"
            enlace = enlace.replace(':id', IDAsignacion)

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

        function submitFormActive(e){
            e.preventDefault();
            let enlace = "{{ route('asignaciones.active', ':id') }}"
            enlace = enlace.replace(':id', IDAsignacion)

            $.ajax({
                type: "PUT",
                url: enlace,
                data: $('#formActive').serialize(),
                success: function(response){
                    if(response.error){
                        $('.modal').modal('hide')
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