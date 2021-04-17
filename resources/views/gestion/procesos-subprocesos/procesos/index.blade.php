@extends('layouts.master')

@section('title', '| Procesos')
@section('item-procesos', 'active')

@section('css_after')
    <!-- Page JS Plugins CSS -->
    <link rel="stylesheet" href="{{ asset('assets/plugins/dataTables/DataTables-1.10.24/css/dataTables.bootstrap4.css') }}">
@endsection

@section('nav_breadcrumd')
    <ol class="breadcrumb has-arrow bg-light rounded">
        <li class="breadcrumb-item"><a href="{{ route('procesos') }}">Empresas</a></li>
        <li class="breadcrumb-item active">Procesos</li>
    </ol>
@endsection

@section('contenido')
    <div class="row">
        <div class="col-lg-8 col-md-8 col-sm-8 col-8">
            <h4><i class="mdi mdi-sitemap"></i> Procesos</h4>
            <p class="text-gray">Empresa: <span>{{ $empresa->razon_social }}</span></p>
        </div>
    </div>
    <div class="row mt-4 equel-grid">
        <div class="col-12">
            <div class="grid">
                <div class="grid-body">
                    <p class="card-title text-primary font-weight-bold mb-3">REGISTRAR PROCESO</p>
                    <div class="item-wrapper">
                        <p class="text-muted font-size-sm mb-0">Los campos marcados con * son obligatorios.</p>
                        <form id="formCreate" method="POST" action="">
                            @csrf
                            <div class="form-row">
                                <div class="col-lg-4 col-md-4 col-sm-6">
                                    <div class="form-group">
                                        <label for="tipo">Tipo de Proceso <span class="text-success">*</span></label>
                                        <select class="form-control form-control-sm" id="tipo" name="tipo" required>
                                            @foreach(tipo_procesos() as $tipo)
                                                <option value="{{ $tipo->value }}" {{ (old('tipo') == $tipo->value ? "selected" : "") }}>{{ $tipo->description }}</option>
                                            @endforeach
                                        </select>
                                        <span id="tipo_error" class="invalid-feedback" role="alert" style="display: block;">
                                            <strong></strong>
                                        </span>
                                    </div>
                                </div>
                                <div class="col-lg-8 col-md-8 col-sm-6">
                                    <div class="form-group">
                                        <label for="nombre">Nombre <span class="text-success">*</span></label>
                                        <input type="text" class="form-control form-control-sm" id="nombre" name="nombre" value="{{ old('nombre') }}" maxlength="255" required>
                                        <span id="nombre_error" class="invalid-feedback" role="alert" style="display: block;">
                                            <strong></strong>
                                        </span>
                                    </div>
                                </div>
                                <div class="col-12">
                                    <div class="form-group">
                                        <label for="descripcion">Descripción</label>
                                        <textarea class="form-control form-control-sm" id="descripcion" name="descripcion" rows="2">{{ old('descripcion') }}</textarea>
                                    </div>
                                </div>
                            </div>
                            <div class="d-flex justify-content-end">
                                <button type="submit" class="btn btn-sm btn-primary has-icon"><i class="mdi mdi-content-save"></i> Guardar</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="row mt-4 equel-grid">
        <div class="col-12">
            <div class="grid">
                <div class="grid-body">
                    <p class="card-title text-primary font-weight-bold mb-3">LISTA DE PROCESOS</p>
                    <div class="table-responsive">
                        <table id="table_procesos" class="table" style="width:100%">
                            <thead>
                                <tr>
                                    <th>Nombre</th>
                                    <th class="text-center" style="width: 20%;">Tipo</th>
                                    <th class="text-center" style="width: 10%">Estado</th>
                                    <th class="text-center" style="width: 20%">Acciones</th>
                                </tr>
                            </thead>
                            <tfoot>
                                <tr>
                                    <th>Nombre</th>
                                    <th class="text-center" style="width: 20%;">Tipo</th>
                                    <th class="text-center" style="width: 10%">Estado</th>
                                    <th class="text-center" style="width: 20%">Acciones</th>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @include('gestion.procesos-subprocesos.procesos.modals.edit')
    @include('gestion.procesos-subprocesos.procesos.modals.delete')
    @include('gestion.procesos-subprocesos.procesos.modals.active')
@endsection

@section('js_after')
    <!-- Page JS Plugins JS -->
    <script src="{{ asset('assets/plugins/dataTables/DataTables-1.10.24/js/jquery.dataTables.js') }}"></script>
    <script src="{{ asset('assets/plugins/dataTables/DataTables-1.10.24/js/dataTables.bootstrap4.js') }}"></script>
    <script>
        const table = $('#table_procesos')
        let Notificacion = {!! json_encode(session('notificacion')) !!}
        let IDProceso = null

        $(document).ready( function () {
            constructDatatable(false);

            // SHOW MODALS
            $('#edit').on('show.bs.modal', showModalEdit)
            $('#delete').on('show.bs.modal', showModalDelete)
            $('#active').on('show.bs.modal', showModalActive)

            // FORMULARIOS
            $('#formCreate').on('submit', submitFormCreate)
            $('#formEdit').on('submit', submitFormEdit)
            $('#formDelete').on('submit', submitFormDelete)
            $('#formActive').on('submit', submitFormActive)
            
            if(Notificacion) customNotification(Notificacion.message, Notificacion.theme, Notificacion.type)
        });

        // DATATABLE
        function constructDatatable(isDestroy) {
            let enlace = "{{ route('procesos.datatable_datos', ':id') }}"
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
                "order": [[ 1, "asc" ]],
                "columns": [
                    {data: 'nombre'},
                    {data: 'tipo', class: 'text-center'},
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
        function showModalEdit(event){
            var button = $(event.relatedTarget) // Button that triggered the modal
            IDProceso = button.data('id')
            let nombre = button.data('nombre')
            let descripcion = button.data('descripcion')
            let tipo = button.data('tipo')
            
            var modal = $(this)
            modal.find('#Edescripcion').val('')
            modal.find('#Etipo').val(tipo).trigger('change')
            modal.find('#Enombre').val(nombre)
            modal.find('#Edescripcion').val(descripcion)
        }

        function showModalDelete(event) {
            var button = $(event.relatedTarget) // Button that triggered the modal
            IDProceso = button.data('id')
            var nombre = button.data('nombre')
            
            var modal = $(this)
            modal.find('#mensaje_delete').text(nombre)
        }

        function showModalActive(event) {
            var button = $(event.relatedTarget) // Button that triggered the modal
            IDProceso = button.data('id')
            var nombre = button.data('nombre')

            var modal = $(this)
            modal.find('#mensaje_active').text(nombre)
        }
        // END MODALS

        // SUBMITS
        function submitFormCreate(e){
            e.preventDefault();
            let enlace = "{{ route('procesos.store', ':id') }}"
            enlace = enlace.replace(':id', {!! json_encode($empresa->id) !!})

            // VALIDAR CAMPOS
            let objTipo = $('#tipo')
            let objNombre = $('#nombre')

            if(!objNombre.val()){
                objNombre.addClass('is-invalid')
                $('#nombre_error strong').text('El nombre del proceso es obligatorio.')
                return false;
            }else{
                objNombre.removeClass('is-invalid')
                $('#nombre_error strong').text('')
            }

            if(!objTipo.val()){
                objTipo.addClass('is-invalid')
                $('#tipo_error strong').text('El tipo de proceso es obligatorio.')
                return false;
            }else{
                objTipo.removeClass('is-invalid')
                $('#tipo_error strong').text('')
            }

            if(objNombre.val().length > 255){
                objNombre.addClass('is-invalid')
                $('#nombre_error strong').text('El nombre del proceso es obligatorio.')
                return false;
            }else{
                objNombre.removeClass('is-invalid')
                $('#nombre_error strong').text('')
            }

            $.ajax({
                type: "POST",
                url: enlace,
                data: $('#formCreate').serialize(),
                success: function(response){
                    if(!response.error){
                        $('#tipo option:first-child').attr('selected', 'selected')
                        $('#nombre').val('')
                        $('#descripcion').val('')
                        $('#tipo').removeClass('is-invalid')
                        $('#nombre').removeClass('is-invalid')
                        constructDatatable(true)
                        customNotification(response.message, response.theme, response.type)
                    }else{
                        customNotification(response.message, response.theme, response.type)
                    }
                },
                error: function(error){
                    console.log(error)
                }
            });
        }

        function submitFormEdit(e){
            e.preventDefault();
            let enlace = "{{ route('procesos.update', ':id') }}"
            enlace = enlace.replace(':id', {!! json_encode($empresa->id) !!})

            // VALIDAR CAMPOS
            let objTipo = $('#Etipo')
            let objNombre = $('#Enombre')

            if(!objNombre.val()){
                objNombre.addClass('is-invalid')
                $('#nombre_error strong').text('El nombre del proceso es obligatorio.')
                return false;
            }else{
                objNombre.removeClass('is-invalid')
                $('#nombre_error strong').text('')
            }

            if(!objTipo.val()){
                objTipo.addClass('is-invalid')
                $('#tipo_error strong').text('El tipo de proceso es obligatorio.')
                return false;
            }else{
                objTipo.removeClass('is-invalid')
                $('#tipo_error strong').text('')
            }

            if(objNombre.val().length > 255){
                objNombre.addClass('is-invalid')
                $('#nombre_error strong').text('El nombre del proceso es obligatorio.')
                return false;
            }else{
                objNombre.removeClass('is-invalid')
                $('#nombre_error strong').text('')
            }

            $.ajax({
                type: "PUT",
                url: enlace,
                data: $('#formEdit').serialize(),
                success: function(response){
                    if(!response.error){
                        $('#Etipo option:first-child').attr('selected', 'selected')
                        $('#Enombre').val('')
                        $('#Edescripcion').val('')
                        $('#Etipo').removeClass('is-invalid')
                        $('#Enombre').removeClass('is-invalid')
                        constructDatatable(true)
                        $('.modal').modal('hide')
                        customNotification(response.message, response.theme, response.type)
                    }else{
                        customNotification(response.message, response.theme, response.type)
                    }
                },
                error: function(error){
                    console.log(error)
                }
            });
        }

        function submitFormDelete(e){
            e.preventDefault();
            let enlace = "{{ route('procesos.delete', ':id') }}"
            enlace = enlace.replace(':id', IDProceso)

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
            let enlace = "{{ route('procesos.active', ':id') }}"
            enlace = enlace.replace(':id', IDProceso)

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