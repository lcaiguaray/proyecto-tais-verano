@extends('layouts.master')

@section('title', '| Registrar Indicador')
@section('item-empresas', 'active')

@section('css_after')
    <!-- Page JS Plugins CSS -->
    <link rel="stylesheet" href="{{ asset('assets/plugins/select2/css/select2.css') }}">
@endsection

@section('nav_breadcrumd')
    <ol class="breadcrumb has-arrow bg-light rounded">
        <li class="breadcrumb-item"><a href="{{ route('empresas') }}">Empresas</a></li>
        <li class="breadcrumb-item"><a href="{{ route('empresas.componentes', $empresa->id) }}">Componentes</a></li>
        <li class="breadcrumb-item"><a href="{{ route('indicadores', $empresa->id) }}">Indicadores</a></li>
        <li class="breadcrumb-item active">Registrar</li>
    </ol>
@endsection

@section('contenido')
    <div class="row">
        <div class="col-lg-8 col-md-8 col-sm-8 col-8">
            <h4><i class="mdi mdi-apple-safari"></i> Registrar</h4>
            <p class="text-gray">Nuevo Indicador</p>
        </div>
    </div>
    <div class="row mt-4">
        <div class="col-12 equel-grid">
            <div class="grid">
                <div class="grid-body">
                    <div class="item-wrapper">
                        <p class="text-muted font-size-sm mb-0">Los campos marcados con * son obligatorios.</p>
                        <form id="formCreate" action="{{ route('indicadores.store') }}" method="POST">
                            @csrf
                            <div class="form-row">
                                <div class="col-lg-8 col-md-8 col-sm-12">
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
                                                <input type="hidden" id="old_objeto_id" name="old_objeto_id" value="{{ old('objeto_id') }}">
                                            </div>
                                        </div>
                                        <div class="col-lg-6 col-md-6 col-sm-6">
                                            <div class="form-group">
                                                <label for="nombre">Nombre <span class="text-success">*</span></label>
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
                                                <label for="responsable">Responsable <span class="text-success">*</span></label>
                                                <input type="text" class="form-control form-control-sm {{ $errors->has('responsable') ? ' is-invalid' : '' }}" id="responsable" name="responsable" value="{{ old('responsable') }}" maxlength="255" required>
                                                @if ($errors->has('responsable'))
                                                    <span class="invalid-feedback" role="alert">
                                                        <strong>{{ $errors->first('responsable') }}</strong>
                                                    </span>
                                                @endif
                                            </div>
                                        </div>
                                        <div class="col-lg-6 col-md-6 col-sm-6">
                                            <div class="form-group">
                                                <label for="meta">Meta <span class="text-success">*</span></label>
                                                <input type="text" class="validar_decimal form-control form-control-sm {{ $errors->has('meta') ? ' is-invalid' : '' }}" id="meta" name="meta" value="{{ old('meta') }}" onpaste="return false;" autocomplete="off" maxlength="10" required>
                                                @if ($errors->has('meta'))
                                                    <span class="invalid-feedback" role="alert">
                                                        <strong>{{ $errors->first('meta') }}</strong>
                                                    </span>
                                                @endif
                                            </div>
                                        </div>
                                        <div class="col-lg-6 col-md-6 col-sm-6">
                                            <div class="form-group">
                                                <label for="objetivo">Objetivo <span class="text-success">*</span></label>
                                                <textarea class="form-control form-control-sm {{ $errors->has('objetivo') ? ' is-invalid' : '' }}" id="objetivo" name="objetivo" rows="2" required>{{ old('objetivo') }}</textarea>
                                                @if ($errors->has('objetivo'))
                                                    <span class="invalid-feedback" role="alert">
                                                        <strong>{{ $errors->first('objetivo') }}</strong>
                                                    </span>
                                                @endif
                                            </div>
                                        </div>
                                        <div class="col-lg-6 col-md-6 col-sm-6">
                                            <div class="form-group">
                                                <label for="descripcion">¿Qué desea medir? <span class="text-success">*</span></label>
                                                <textarea class="form-control form-control-sm {{ $errors->has('descripcion') ? ' is-invalid' : '' }}" id="descripcion" name="descripcion" rows="2" required>{{ old('descripcion') }}</textarea>
                                                @if ($errors->has('descripcion'))
                                                    <span class="invalid-feedback" role="alert">
                                                        <strong>{{ $errors->first('descripcion') }}</strong>
                                                    </span>
                                                @endif
                                            </div>
                                        </div>
                                        <div class="col-lg-6 col-md-6 col-sm-6">
                                            <div class="form-group">
                                                <label for="realizar">¿Qué realizará la medición? <span class="text-success">*</span></label>
                                                <textarea class="form-control form-control-sm {{ $errors->has('realizar') ? ' is-invalid' : '' }}" id="realizar" name="realizar" rows="2" required>{{ old('realizar') }}</textarea>
                                                @if ($errors->has('realizar'))
                                                    <span class="invalid-feedback" role="alert">
                                                        <strong>{{ $errors->first('realizar') }}</strong>
                                                    </span>
                                                @endif
                                            </div>
                                        </div>
                                        <div class="col-lg-6 col-md-6 col-sm-6">
                                            <div class="form-group">
                                                <label for="mecanismo">¿Qué mecanismos de medición se utilizará? <span class="text-success">*</span></label>
                                                <textarea class="form-control form-control-sm {{ $errors->has('mecanismo') ? ' is-invalid' : '' }}" id="mecanismo" name="mecanismo" rows="2" required>{{ old('mecanismo') }}</textarea>
                                                @if ($errors->has('mecanismo'))
                                                    <span class="invalid-feedback" role="alert">
                                                        <strong>{{ $errors->first('mecanismo') }}</strong>
                                                    </span>
                                                @endif
                                            </div>
                                        </div>
                                        <div class="col-lg-6 col-md-6 col-sm-6">
                                            <div class="form-group">
                                                <label for="tolerancia">¿Qué tolerancias de desviación podrán determinarse? <span class="text-success">*</span></label>
                                                <textarea class="form-control form-control-sm {{ $errors->has('tolerancia') ? ' is-invalid' : '' }}" id="tolerancia" name="tolerancia" rows="2" required>{{ old('tolerancia') }}</textarea>
                                                @if ($errors->has('tolerancia'))
                                                    <span class="invalid-feedback" role="alert">
                                                        <strong>{{ $errors->first('tolerancia') }}</strong>
                                                    </span>
                                                @endif
                                            </div>
                                        </div>
                                        <div class="col-lg-6 col-md-6 col-sm-6">
                                            <div class="form-group">
                                                <label for="resultados">¿Qué se hará con los resultados? <span class="text-success">*</span></label>
                                                <textarea class="form-control form-control-sm {{ $errors->has('resultados') ? ' is-invalid' : '' }}" id="resultados" name="resultados" rows="2" required>{{ old('resultados') }}</textarea>
                                                @if ($errors->has('resultados'))
                                                    <span class="invalid-feedback" role="alert">
                                                        <strong>{{ $errors->first('resultados') }}</strong>
                                                    </span>
                                                @endif
                                            </div>
                                        </div>
                                        <div class="col-lg-6 col-md-6 col-sm-6">
                                            <div class="form-group">
                                                <label for="iniciativa">Iniciativas <span class="text-success">*</span></label>
                                                <textarea class="form-control form-control-sm {{ $errors->has('iniciativa') ? ' is-invalid' : '' }}" id="iniciativa" name="iniciativa" rows="2" required>{{ old('iniciativa') }}</textarea>
                                                @if ($errors->has('iniciativa'))
                                                    <span class="invalid-feedback" role="alert">
                                                        <strong>{{ $errors->first('iniciativa') }}</strong>
                                                    </span>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-lg-4 col-md-4 col-sm-12">
                                    <div class="form-row">
                                        <div class="col-12">
                                            <div class="form-group">
                                                <label for="frecuencia">Frecuencia <span class="text-success">*</span></label>
                                                <select class="select2_form form-control form-control-sm {{ $errors->has('frecuencia') ? ' is-invalid' : '' }}" id="frecuencia" name="frecuencia" style="width: 100%;" required>
                                                    <option></option>
                                                    @foreach(tipo_frecuencias() as $item)
                                                        <option value="{{ $item->value }}" {{ (old('frecuencia') == $item->value ? "selected" : "") }}>{{ $item->description }}</option>
                                                    @endforeach
                                                </select>
                                                @if ($errors->has('frecuencia'))
                                                    <span class="invalid-feedback" role="alert">
                                                        <strong>{{ $errors->first('frecuencia') }}</strong>
                                                    </span>
                                                @endif
                                            </div>
                                        </div>
                                        <div class="col-12">
                                            <div class="form-group">
                                                <label for="formula">Formula <span class="text-success">*</span></label>
                                                <select class="select2_form form-control form-control-sm {{ $errors->has('formula') ? ' is-invalid' : '' }}" id="formula" name="formula" style="width: 100%;" required>
                                                    <option></option>
                                                    @foreach(tipo_formulas() as $item)
                                                        <option value="{{ $item->value }}" {{ (old('formula') == $item->value ? "selected" : "") }}>{{ $item->description }}</option>
                                                    @endforeach
                                                </select>
                                                @if ($errors->has('formula'))
                                                    <span class="invalid-feedback" role="alert">
                                                        <strong>{{ $errors->first('formula') }}</strong>
                                                    </span>
                                                @endif
                                            </div>
                                        </div>
                                        <div class="col-12">
                                            <div id="show_formula1" class="form-group">
                                                <div class="mb-3">
                                                    <img src="{{ asset('assets/images/formulas/complemento.jpeg') }}" class="rounded mx-auto d-block" style="width: 100%;" alt="Formula">
                                                </div>
                                                <div class="row">
                                                    <div class="col-12">
                                                        <div class="form-group">
                                                            <label for="formula1_parametro1">Nombre Parametro 1 <span class="text-success">*</span></label>
                                                            <input type="text" class="form-control form-control-sm {{ $errors->has('formula1_parametro1') ? ' is-invalid' : '' }}" id="formula1_parametro1" name="formula1_parametro1" value="{{ old('formula1_parametro1') }}" maxlength="255">
                                                            @if ($errors->has('formula1_parametro1'))
                                                                <span class="invalid-feedback" role="alert">
                                                                    <strong>{{ $errors->first('formula1_parametro1') }}</strong>
                                                                </span>
                                                            @endif
                                                        </div>
                                                    </div>
                                                    <div class="col-12">
                                                        <div class="form-group">
                                                            <label for="formula1_parametro2">Nombre Parametro 2 <span class="text-success">*</span></label>
                                                            <input type="text" class="form-control form-control-sm {{ $errors->has('formula1_parametro2') ? ' is-invalid' : '' }}" id="formula1_parametro2" name="formula1_parametro2" value="{{ old('formula1_parametro2') }}" maxlength="255">
                                                            @if ($errors->has('formula1_parametro2'))
                                                                <span class="invalid-feedback" role="alert">
                                                                    <strong>{{ $errors->first('formula1_parametro2') }}</strong>
                                                                </span>
                                                            @endif
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div id="show_formula2" class="form-group">
                                                <div class="mb-3">
                                                    <img src="{{ asset('assets/images/formulas/porcentual.jpeg') }}" class="rounded mx-auto d-block" style="width: 100%;" alt="Formula">
                                                </div>
                                                <div class="row">
                                                    <div class="col-12">
                                                        <div class="form-group">
                                                            <label for="formula2_parametro1">Nombre Parametro 1 <span class="text-success">*</span></label>
                                                            <input type="text" class="form-control form-control-sm {{ $errors->has('formula2_parametro1') ? ' is-invalid' : '' }}" id="formula2_parametro1" name="formula2_parametro1" value="{{ old('formula2_parametro1') }}" maxlength="255">
                                                            @if ($errors->has('formula2_parametro1'))
                                                                <span class="invalid-feedback" role="alert">
                                                                    <strong>{{ $errors->first('formula2_parametro1') }}</strong>
                                                                </span>
                                                            @endif
                                                        </div>
                                                    </div>
                                                    <div class="col-12">
                                                        <div class="form-group">
                                                            <label for="formula2_parametro2">Nombre Parametro 2 <span class="text-success">*</span></label>
                                                            <input type="text" class="form-control form-control-sm {{ $errors->has('formula2_parametro2') ? ' is-invalid' : '' }}" id="formula2_parametro2" name="formula2_parametro2" value="{{ old('formula2_parametro2') }}" maxlength="255">
                                                            @if ($errors->has('formula2_parametro2'))
                                                                <span class="invalid-feedback" role="alert">
                                                                    <strong>{{ $errors->first('formula2_parametro2') }}</strong>
                                                                </span>
                                                            @endif
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div id="show_formula3" class="form-group">
                                                <div class="mb-3">
                                                    <img src="{{ asset('assets/images/formulas/porcentual.jpeg') }}" class="rounded mx-auto d-block" style="width: 100%;" alt="Formula">
                                                </div>
                                                <div class="row">
                                                    <div class="col-12">
                                                        <div class="form-group">
                                                            <label for="formula3_parametro1">Nombre Parametro 1 <span class="text-success">*</span></label>
                                                            <input type="text" class="form-control form-control-sm {{ $errors->has('formula3_parametro1') ? ' is-invalid' : '' }}" id="formula3_parametro1" name="formula3_parametro1" value="{{ old('formula3_parametro1') }}" maxlength="255">
                                                            @if ($errors->has('formula3_parametro1'))
                                                                <span class="invalid-feedback" role="alert">
                                                                    <strong>{{ $errors->first('formula3_parametro1') }}</strong>
                                                                </span>
                                                            @endif
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <p class="text-primary">SEMÁFORO</p>
                                        <div class="col-12">
                                            <div class="form-group">
                                                <label for="tipo_condicion">Condición <span class="text-success">*</span></label>
                                                <select class="select2_form form-control form-control-sm {{ $errors->has('tipo_condicion') ? ' is-invalid' : '' }}" id="tipo_condicion" name="tipo_condicion" style="width: 100%;" required>
                                                    <option></option>
                                                    @foreach(tipo_condiciones_indicador() as $item)
                                                        <option value="{{ $item->value }}" {{ (old('tipo_condicion') == $item->value ? "selected" : "") }}>{{ $item->description }}</option>
                                                    @endforeach
                                                </select>
                                                @if ($errors->has('tipo_condicion'))
                                                    <span class="invalid-feedback" role="alert">
                                                        <strong>{{ $errors->first('tipo_condicion') }}</strong>
                                                    </span>
                                                @endif
                                            </div>
                                        </div>
                                        <div class="col-12">
                                            <div class="form-group">
                                                <label for="condicion_rojo"><span class="badge badge-danger">  </span> <span id="span_condicion_rojo">Menos de</span> <span class="text-success">*</span></label>
                                                <input type="text" class="validar_decimal form-control form-control-sm {{ $errors->has('condicion_rojo') ? ' is-invalid' : '' }}" id="condicion_rojo" name="condicion_rojo" value="{{ old('condicion_rojo') }}" onpaste="return false;" autocomplete="off" maxlength="10" required>
                                                @if ($errors->has('condicion_rojo'))
                                                    <span class="invalid-feedback" role="alert">
                                                        <strong>{{ $errors->first('condicion_rojo') }}</strong>
                                                    </span>
                                                @endif
                                            </div>
                                        </div>
                                        <div class="col-12">
                                            <div class="form-group">
                                                <label for="condicion_verde"><span class="badge badge-success">  </span> <span id="span_condicion_verde">Más de</span> <span class="text-success">*</span></label>
                                                <input type="text" class="validar_decimal form-control form-control-sm {{ $errors->has('condicion_verde') ? ' is-invalid' : '' }}" id="condicion_verde" name="condicion_verde" value="{{ old('condicion_verde') }}" onpaste="return false;" autocomplete="off" maxlength="10" required>
                                                @if ($errors->has('condicion_verde'))
                                                    <span class="invalid-feedback" role="alert">
                                                        <strong>{{ $errors->first('condicion_verde') }}</strong>
                                                    </span>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="d-flex justify-content-end">
                                <a href="{{ route('indicadores', $empresa->id) }}" class="btn btn-sm btn-secondary has-icon mr-2"><i class="mdi mdi-close"></i> Cancelar</a>
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

            $('.validar_numerico').on('keypress', validaNumerico)
            $('.validar_decimal').on('keypress', validarDecimal)

            $('#mapa_proceso').on('change', changeTipo)
            $('#tipo_objeto').on('change', cargarDatosObjeto)
            $('#formula').on('change', changeFormula)
            $('#tipo_condicion').on('change', changeCondicion)

            initDefault()
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

        function initDefault(){
            $('#show_formula1').hide()
            $('#show_formula2').hide()
            $('#show_formula3').hide()
            let valObjetoID = $('#old_objeto_id').val()
            let valFormula = $('#formula').val()
            let valCondicion = $('#tipo_condicion').val()
            if(valCondicion) $('#tipo_condicion').trigger('change')
            if(valObjetoID) $('#tipo_objeto').trigger('change')
            if(valFormula){
                let F1_para1 = $('#formula1_parametro1').val()
                let F1_para2 = $('#formula1_parametro2').val()
                let F2_para1 = $('#formula2_parametro1').val()
                let F2_para2 = $('#formula2_parametro2').val()
                let F3_para1 = $('#formula3_parametro1').val()
                $('#formula').trigger('change')
                if(valFormula == 'F1'){
                    $('#formula1_parametro1').val(F1_para1)
                    $('#formula1_parametro2').val(F1_para2)
                }
                if(valFormula == 'F2'){
                    $('#formula2_parametro1').val(F2_para1)
                    $('#formula2_parametro2').val(F2_para2)
                }
                if(valFormula == 'F3'){
                    $('#formula3_parametro1').val(F3_para1)
                }
            }
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

        function changeTipo(){
            let valTipo = $('#tipo_objeto').val()

            if(valTipo) $('#tipo_objeto').trigger('change')
        }

        function changeCondicion(){
            let valTipo = $(this).val()
            if(valTipo == 'CME'){
                $('#span_condicion_rojo').text('Menos de')
                $('#span_condicion_verde').text('Más de')
            }else{
                $('#span_condicion_rojo').text('Más de')
                $('#span_condicion_verde').text('Menos de')
            }
        }

        function changeFormula(){
            let valFormula = this.value
            $('#show_formula1').hide()
            $('#show_formula2').hide()
            $('#show_formula3').hide()
            $('#formula1_parametro1').attr('required', false)
            $('#formula1_parametro2').attr('required', false)
            $('#formula2_parametro1').attr('required', false)
            $('#formula2_parametro2').attr('required', false)
            $('#formula3_parametro1').attr('required', false)
            $('#formula1_parametro1').val('')
            $('#formula1_parametro2').val('')
            $('#formula2_parametro1').val('')
            $('#formula2_parametro2').val('')
            $('#formula3_parametro1').val('')

            if(valFormula == 'F1'){
                $('#show_formula1').show()
                $('#formula1_parametro1').attr('required', true)
                $('#formula1_parametro2').attr('required', true)
            }
            if(valFormula == 'F2'){
                $('#show_formula2').show()
                $('#formula2_parametro1').attr('required', true)
                $('#formula2_parametro2').attr('required', true)
            }
            if(valFormula == 'F3'){
                $('#show_formula3').show()
                $('#formula3_parametro1').attr('required', true)
            }
        }

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