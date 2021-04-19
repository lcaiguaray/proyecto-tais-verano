@extends('layouts.master')

@section('title', '| Registrar Asignación')
@section('item-asignacion', 'active')

@section('css_after')
    <!-- Page JS Plugins CSS -->
    <link rel="stylesheet" href="{{ asset('assets/plugins/select2/css/select2.css') }}">
@endsection

@section('nav_breadcrumd')
    <ol class="breadcrumb has-arrow bg-light rounded">
        <li class="breadcrumb-item"><a href="{{ route('asignaciones') }}">Asignaciones</a></li>
        <li class="breadcrumb-item active">Registrar</li>
    </ol>
@endsection

@section('contenido')
    <div class="row">
        <div class="col-lg-8 col-md-8 col-sm-8 col-8">
            <h4><i class="mdi mdi-human-handsup"></i> Registrar</h4>
            <p class="text-gray">Nueva Asignación</p>
        </div>
    </div>
    <div class="row mt-4">
        <div class="col-12 equel-grid">
            <div class="grid">
                <div class="grid-body">
                    <div class="item-wrapper">
                        <p class="text-muted font-size-sm mb-0">Los campos marcados con * son obligatorios.</p>
                        <form action="{{ route('asignaciones.store') }}" method="POST">
                            @csrf
                            <div class="form-row">
                                <div class="col-lg-6 col-md-6 col-sm-12">
                                    <div class="form-group">
                                        <label for="empresa">Empresa <span class="text-success">*</span></label>
                                        <select class="select2_form form-control form-control-sm {{ $errors->has('empresa') ? ' is-invalid' : '' }}" id="empresa" name="empresa" style="width: 100%;" required>
                                            <option></option>
                                            @foreach($empresas as $empresa)
                                                <option value="{{ $empresa->id }}" {{ (old('empresa') == $empresa->id ? "selected" : "") }}>{{ $empresa->razon_social }}</option>
                                            @endforeach
                                        </select>
                                        @if ($errors->has('empresa'))
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $errors->first('empresa') }}</strong>
                                            </span>
                                        @endif
                                    </div>
                                </div>
                                <div class="col-lg-6 col-md-6 col-sm-12">
                                    <div class="form-group">
                                        <label for="usuario">Usuarios <span class="text-success">*</span></label>
                                        <select class="select2_form form-control form-control-sm {{ $errors->has('usuario') ? ' is-invalid' : '' }}" id="usuario" name="usuario" style="width: 100%;" required>
                                            <option></option>
                                        </select>
                                        @if ($errors->has('usuario'))
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $errors->first('usuario') }}</strong>
                                            </span>
                                        @endif
                                        <input type="hidden" id="old_usuario" name="old_usuario" value="{{ old('old_usuario') }}">
                                    </div>
                                </div>
                                <div class="col-12">
                                    <div class="form-group">
                                        <label for="descripcion">Descripcion</label>
                                        <textarea class="form-control form-control-sm {{ $errors->has('descripcion') ? ' is-invalid' : '' }}" id="descripcion" name="descripcion" rows="2" required>{{ old('descripcion') }}</textarea>
                                        @if ($errors->has('descripcion'))
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $errors->first('descripcion') }}</strong>
                                            </span>
                                        @endif
                                    </div>
                                </div>
                            </div>
                            <div class="d-flex justify-content-end">
                                <a href="{{ route('asignaciones') }}" class="btn btn-sm btn-secondary has-icon mr-2"><i class="mdi mdi-close"></i> Cancelar</a>
                                <button type="submit" class="btn btn-sm btn-primary has-icon"><i class="mdi mdi-content-save"></i> Guardar</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('js_after')
    <!-- Page JS Plugins JS -->
    <script src="{{ asset('assets/plugins/select2/js/select2.full.js') }}"></script>
    <script>
        let Notificacion = {!! json_encode(session('notificacion')) !!}

        $(document).ready(function(){
            $(".select2_form").select2({
                placeholder: "Seleccionar",
                allowClear: false,
                width: '100%',
            });

            $('#empresa').on('change', cargarUsuarios)

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

        function cargarUsuarios() {
            let valEmpresa = this.value

            $.ajax({
                type: "POST",
                url: "{{ route('asignaciones.cargar_usuarios') }}",
                data: {
                    '_token': '{{ @csrf_token() }}',
                    'id': valEmpresa,
                    'usuario': ''
                },
                success: function(response){
                    $("#usuario").empty()
                    if(!response.error){
                        var old = $("#old_usuario").val();
                        if (old !== undefined && old !== null && old !== "") {
                            $("#usuario").select2({
                                data: response.datos
                            }).val(old).trigger('change');
                        } else {
                            $("#usuario").select2({
                                data: response.datos
                            }).val($('#usuario').find(':selected').val()).trigger('change');
                        }
                    }else{
                        customNotification(response.message, response.theme, response.type)
                    }
                },
                error: function(error){
                    console.log(error)
                }
            });
        }
    </script>
@endsection