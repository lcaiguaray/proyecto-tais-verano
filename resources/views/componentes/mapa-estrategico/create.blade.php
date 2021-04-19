@extends('layouts.master')

@section('title', '| Registrar Estrategias')
@section('item-empresas', 'active')

@section('css_after')
    <!-- Page JS Plugins CSS -->
    <link rel="stylesheet" href="{{ asset('assets/plugins/select2/css/select2.css') }}">
@endsection

@section('nav_breadcrumd')
    <ol class="breadcrumb has-arrow bg-light rounded">
        <li class="breadcrumb-item"><a href="{{ route('empresas') }}">Empresas</a></li>
        <li class="breadcrumb-item"><a href="{{ route('empresas.componentes', $empresa->id) }}">Componentes</a></li>
        <li class="breadcrumb-item"><a href="{{ route('estrategias', $empresa->id) }}">Estratégias</a></li>
        <li class="breadcrumb-item active">Registrar</li>
    </ol>
@endsection

@section('contenido')
    <div class="row">
        <div class="col-lg-8 col-md-8 col-sm-8 col-8">
            <h4><i class="mdi mdi-table-large"></i> Registrar</h4>
            <p class="text-gray">Nueva Estratégia</p>
        </div>
    </div>
    <div class="row mt-4">
        <div class="col-12 equel-grid">
            <div class="grid">
                <div class="grid-body">
                    <div class="item-wrapper">
                        <p class="text-muted font-size-sm mb-0">Los campos marcados con * son obligatorios.</p>
                        <form action="{{ route('estrategias.store') }}" method="POST">
                            @csrf
                            <div class="form-row">
                                <div class="col-lg-6 col-md-6 col-sm-6">
                                    <div class="form-group">
                                        <label for="mapa_proceso">Mapa de Proceso <span class="text-success">*</span></label>
                                        <select class="select2_form form-control form-control-sm {{ $errors->has('mapa_proceso') ? ' is-invalid' : '' }}" id="mapa_proceso" name="mapa_proceso" required>
                                            <option></option>
                                            @foreach($empresa->mapa_proceso->where('activo', true) as $mapa_proceso)
                                                <option value="{{ $mapa_proceso->id }}" {{ (old('mapa_proceso') == $mapa_proceso->id ? "selected" : "") }}>{{ $mapa_proceso->nombre }}</option>
                                            @endforeach
                                        </select>
                                        @if ($errors->has('mapa_proceso'))
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $errors->first('mapa_proceso') }}</strong>
                                            </span>
                                        @endif
                                    </div>
                                </div>
                                <div class="col-lg-6 col-md-6 col-sm-6">
                                    <div class="form-group">
                                        <label for="tipo_objeto">Tipo <span class="text-success">*</span></label>
                                        <select class="select2_form form-control form-control-sm {{ $errors->has('tipo_objeto') ? ' is-invalid' : '' }}" id="tipo_objeto" name="tipo_objeto" style="width: 100%;" required>
                                            <option></option>
                                            @foreach(tipo_objetos() as $item)
                                                <option value="{{ $item->value }}" {{ (old('tipo_objeto') == $item->value ? "selected" : "") }}>{{ $item->description }}</option>
                                            @endforeach
                                        </select>
                                        @if ($errors->has('tipo_objeto'))
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $errors->first('tipo_objeto') }}</strong>
                                            </span>
                                        @endif
                                    </div>
                                </div>
                                <div class="col-12">
                                    <div class="form-group">
                                        <label for="objeto_id">Proceso/Subproceso <span class="text-success">*</span></label>
                                        <select class="select2_form form-control form-control-sm {{ $errors->has('objeto_id') ? ' is-invalid' : '' }}" id="objeto_id" name="objeto_id" style="width: 100%;" required>
                                            <option></option>
                                        </select>
                                        @if ($errors->has('objeto_id'))
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $errors->first('objeto_id') }}</strong>
                                            </span>
                                        @endif
                                        <input type="hidden" id="old_objeto_id" name="old_objeto_id" value="{{ old('old_objeto_id') }}">
                                    </div>
                                </div>
                                <div class="col-lg-6 col-md-6 col-sm-6">
                                    <div class="form-group">
                                        <label for="nombre">Descripción <span class="text-success">*</span></label>
                                        <input type="text" class="form-control form-control-sm {{ $errors->has('nombre') ? ' is-invalid' : '' }}" id="nombre" name="nombre" value="{{ old('nombre') }}" maxlength="255" required>
                                        @if ($errors->has('nombre'))
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $errors->first('nombre') }}</strong>
                                            </span>
                                        @endif
                                    </div>
                                </div>
                                <div class="col-lg-6 col-md-6 col-sm-6">
                                    <div class="form-group">
                                        <label for="tipo_estrategia">Tipo Estratégia <span class="text-success">*</span></label>
                                        <select class="select2_form form-control form-control-sm {{ $errors->has('tipo_estrategia') ? ' is-invalid' : '' }}" id="tipo_estrategia" name="tipo_estrategia" style="width: 100%;" required>
                                            <option></option>
                                            @foreach(tipo_estrategias() as $item)
                                                <option value="{{ $item->value }}" {{ (old('tipo_estrategia') == $item->value ? "selected" : "") }}>{{ $item->description }}</option>
                                            @endforeach
                                        </select>
                                        @if ($errors->has('tipo_estrategia'))
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $errors->first('tipo_estrategia') }}</strong>
                                            </span>
                                        @endif
                                    </div>
                                </div>
                                <div class="col-lg-6 col-md-6 col-sm-6">
                                    <div class="form-group form-check">
                                        <input type="checkbox" class="form-check-input" id="ligado" name="ligado">
                                        <label class="form-check-label" for="ligado">Ligado al proceso/subproceso</label>
                                    </div>
                                </div>
                            </div>
                            <div class="d-flex justify-content-end">
                                <a href="{{ route('estrategias', $empresa->id) }}" class="btn btn-sm btn-secondary has-icon mr-2"><i class="mdi mdi-close"></i> Cancelar</a>
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
        $(document).ready(function(){
            $(".select2_form").select2({
                placeholder: "Seleccionar",
                allowClear: false,
                width: '100%',
            });

            $('#mapa_proceso').on('change', changeTipo)
            $('#tipo_objeto').on('change', cargarDatosObjeto)
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

        function changeTipo(){
            let valTipo = $('#tipo_objeto').val()

            if(valTipo) $('#tipo_objeto').trigger('change')
        }

        function cargarDatosObjeto(){
            let valTipo = this.value
            let valMapa = $('#mapa_proceso').val()

            $.ajax({
                type: "POST",
                url: "{{ route('indicadores.cargar_datos_objeto') }}",
                data: {
                    '_token': '{{ @csrf_token() }}',
                    'objeto': valTipo,
                    'mapa': valMapa
                },
                success: function(response){
                    $("#objeto_id").empty()
                    if(!response.error){
                        var old = $("#old_objeto_id").val();
                        if (old !== undefined && old !== null && old !== "") {
                            $("#objeto_id").select2({
                                data: response.datos
                            }).val(old).trigger('change');
                        } else {
                            $("#objeto_id").select2({
                                data: response.datos
                            }).val($('#objeto_id').find(':selected').val()).trigger('change');
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