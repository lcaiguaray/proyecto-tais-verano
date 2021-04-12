@extends('layouts.master')

@section('title', '| Usuarios')
@section('item-usuarios', 'active')

@section('css_before')
    <!-- Page JS Plugins CSS -->
    <link rel="stylesheet" href="{{ asset('assets/plugins/dataTables/DataTables-1.10.24/css/dataTables.bootstrap4.css') }}">
@endsection

@section('contenido')
    <div class="row">
        <div class="col-lg-8 col-md-8 col-sm-8 col-8">
            <h4><i class="mdi mdi-account-multiple"></i> Usuarios</h4>
            <p class="text-gray">Lista de usuarios</p>
        </div>
        <div class="col-lg-4 col-md-4 col-sm-4 col-4">
            <a href="{{ route('usuarios.create') }}" class="btn btn-inverse-success float-right float-xs-none has-icon"><i class="mdi mdi-library-plus"></i> Agregar Nuevo</a>
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
                                    <th class="text-center" style="width: 10%">DNI</th>
                                    <th style="width: 35%">Nombre</th>
                                    <th style="width: 30%">Email</th>
                                    <th class="text-center" style="width: 10%">Estado</th>
                                    <th class="text-center" style="width: 15%">Acciones</th>
                                </tr>
                            </thead>
                            <tfoot>
                                <tr>
                                    <th class="text-center" style="width: 10%">DNI</th>
                                    <th style="width: 35%">Nombre</th>
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

    @include('gestion.empresas.modals.delete')
    @include('gestion.empresas.modals.active')
@endsection

@section('js_after')
    <!-- Page JS Plugins JS -->
    <script src="{{ asset('assets/plugins/dataTables/DataTables-1.10.24/js/jquery.dataTables.js') }}"></script>
    <script src="{{ asset('assets/plugins/dataTables/DataTables-1.10.24/js/dataTables.bootstrap4.js') }}"></script>
    <script>
        const table = $('#table_empresas')
        let Notificacion = {!! json_encode(session('notificacion')) !!}
        let IDUsuario = null

        $(document).ready( function () {
            constructDatatable(false);

            // SHOW MODALS
            $('#delete').on('show.bs.modal', showModalDelete)
            $('#active').on('show.bs.modal', showModalActive)

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
                "ajax": "{{ route('usuarios.datatable_datos') }}",
                language: {
                    url: '{{ asset("datatable_español.json") }}'
                },
                "info": false,
                "order": [[ 1, "asc" ]],
                "columns": [
                    {data: 'dni', class: 'text-center'},
                    {data: 'nombre'},
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

        // MODALS
        function showModalDelete(event) {
            var button = $(event.relatedTarget) // Button that triggered the modal
            IDUsuario = button.data('id')
            var razon_social = button.data('nombre')
            
            var modal = $(this)
            modal.find('#mensaje_delete').text(razon_social)
        }

        function showModalActive(event) {
            var button = $(event.relatedTarget) // Button that triggered the modal
            IDUsuario = button.data('id')
            var razon_social = button.data('nombre')

            var modal = $(this)
            modal.find('#mensaje_active').text(razon_social)
        }
        // END MODALS

        // SUBMITS
        function submitFormDelete(e){
            e.preventDefault();
            let enlace = "{{ route('usuarios.delete', ':id') }}"
            enlace = enlace.replace(':id', IDUsuario)

            $.ajax({
                type: "PUT",
                url: enlace,
                data: $('#formDelete').serialize(),
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

        function submitFormActive(e){
            e.preventDefault();
            let enlace = "{{ route('usuarios.active', ':id') }}"
            enlace = enlace.replace(':id', IDUsuario)

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