@extends('layouts.master')

@section('title', '| Registrar Empresa')
@section('item-empresas', 'active')

@section('css_before')
    <!-- Page JS Plugins CSS -->
    <link rel="stylesheet" href="{{ asset('assets/plugins/flatpickr/flatpickr.css') }}">
@endsection

@section('nav_breadcrumd')
    <ol class="breadcrumb has-arrow bg-light rounded">
        <li class="breadcrumb-item"><a href="{{ route('usuarios') }}">Usuarios</a></li>
        <li class="breadcrumb-item active">Registrar</li>
    </ol>
@endsection

@section('contenido')
    <div class="row">
        <div class="col-lg-8 col-md-8 col-sm-8 col-8">
            <h4><i class="mdi mdi-account-multiple"></i> Registrar</h4>
            <p class="text-gray">Nuevo Usuario</p>
        </div>
    </div>
    <div class="row mt-4">
        <div class="col-12 equel-grid">
            <div class="grid">
                <div class="grid-body">
                    <div class="item-wrapper">
                        <p class="text-muted font-size-sm mb-0">Los campos marcados con * son obligatorios.</p>
                        <form action="{{ route('usuarios.store') }}" method="POST">
                            @csrf
                            <div class="form-row">
                                <div class="col-lg-3 col-md-3 col-sm-6">
                                    <div class="form-group">
                                        <label for="dni">DNI <span class="text-success">*</span></label>
                                        <input type="text" class="form-control form-control-sm validar_numerico {{ $errors->has('dni') ? ' is-invalid' : '' }}" id="dni" name="dni" value="{{ old('dni') }}" maxlength="11" autocomplete="off" required>
                                        @if ($errors->has('dni'))
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $errors->first('dni') }}</strong>
                                            </span>
                                        @endif
                                    </div>
                                </div>
                                <div class="col-lg-3 col-md-3 col-sm-6">
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
                                <div class="col-lg-3 col-md-3 col-sm-6">
                                    <div class="form-group">
                                        <label for="apellido_paterno">Apellido Paterno <span class="text-success">*</span></label>
                                        <input type="text" class="form-control form-control-sm {{ $errors->has('apellido_paterno') ? ' is-invalid' : '' }}" id="apellido_paterno" name="apellido_paterno" value="{{ old('apellido_paterno') }}" maxlength="255" required>
                                        @if ($errors->has('apellido_paterno'))
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $errors->first('apellido_paterno') }}</strong>
                                            </span>
                                        @endif
                                    </div>
                                </div>
                                <div class="col-lg-3 col-md-3 col-sm-6">
                                    <div class="form-group">
                                        <label for="apellido_materno">Apellido Materno <span class="text-success">*</span></label>
                                        <input type="text" class="form-control form-control-sm {{ $errors->has('apellido_materno') ? ' is-invalid' : '' }}" id="apellido_materno" name="apellido_materno" value="{{ old('apellido_materno') }}" maxlength="255" required>
                                        @if ($errors->has('apellido_materno'))
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $errors->first('apellido_materno') }}</strong>
                                            </span>
                                        @endif
                                    </div>
                                </div>
                                <div class="col-lg-3 col-md-3 col-sm-12">
                                    <div class="form-group">
                                        <label for="fecha_nacimiento">Fecha de Nacimiento</label>
                                        <input type="text" class="fecha-datepicker form-control form-control-sm {{ $errors->has('fecha_nacimiento') ? ' is-invalid' : '' }}" id="fecha_nacimiento" name="fecha_nacimiento" value="{{ old('fecha_nacimiento') }}">
                                        @if ($errors->has('fecha_nacimiento'))
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $errors->first('fecha_nacimiento') }}</strong>
                                            </span>
                                        @endif
                                    </div>
                                </div>
                                <div class="col-lg-3 col-md-3 col-sm-6">
                                    <div class="form-group">
                                        <label for="telefono">Telefono</label>
                                        <input type="text" class="form-control form-control-sm validar_numerico {{ $errors->has('telefono') ? ' is-invalid' : '' }}" id="telefono" name="telefono" value="{{ old('telefono') }}" maxlength="20" autocomplete="off">
                                        @if ($errors->has('telefono'))
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $errors->first('telefono') }}</strong>
                                            </span>
                                        @endif
                                    </div>
                                </div>
                                <div class="col-lg-3 col-md-3 col-sm-6">
                                    <div class="form-group">
                                        <label for="sexo">Sexo <span class="text-success">*</span></label>
                                        <select class="form-control form-control-sm {{ $errors->has('sexo') ? ' is-invalid' : '' }}" id="sexo" name="sexo" required>
                                            <option value="M" {{ (old('sexo') == 'M' ? "selected" : "") }}>Masculino</option>
                                            <option value="F" {{ (old('sexo') == 'F' ? "selected" : "") }}>Femenino</option>
                                        </select>
                                        @if ($errors->has('sexo'))
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $errors->first('sexo') }}</strong>
                                            </span>
                                        @endif
                                    </div>
                                </div>
                                <div class="col-lg-3 col-md-3 col-sm-6">
                                    <div class="form-group">
                                        <label for="rol">Rol <span class="text-success">*</span></label>
                                        <select class="form-control form-control-sm {{ $errors->has('rol') ? ' is-invalid' : '' }}" id="rol" name="rol" required>
                                            <option value="Administrador">Administrador</option>
                                        </select>
                                        @if ($errors->has('rol'))
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $errors->first('rol') }}</strong>
                                            </span>
                                        @endif
                                    </div>
                                </div>
                                <div class="col-lg-6 col-md-6 col-sm-12">
                                    <div class="form-group">
                                        <label for="email">Email <span class="text-success">*</span></label>
                                        <input type="email" class="form-control form-control-sm {{ $errors->has('email') ? ' is-invalid' : '' }}" id="email" name="email" value="{{ old('email') }}" required>
                                        @if ($errors->has('email'))
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $errors->first('email') }}</strong>
                                            </span>
                                        @endif
                                    </div>
                                </div>
                                <div class="col-lg-6 col-md-6 col-sm-12">
                                    <div class="form-group">
                                        <label for="direccion">Dirección</label>
                                        <input type="text" class="form-control form-control-sm {{ $errors->has('direccion') ? ' is-invalid' : '' }}" id="direccion" name="direccion" value="{{ old('direccion') }}">
                                        @if ($errors->has('direccion'))
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $errors->first('direccion') }}</strong>
                                            </span>
                                        @endif
                                    </div>
                                </div>
                            </div>
                            <div class="d-flex justify-content-end">
                                <a href="{{ route('usuarios') }}" class="btn btn-sm btn-secondary has-icon mr-2"><i class="mdi mdi-close"></i> Cancelar</a>
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
    <script src="{{ asset('assets/plugins/flatpickr/flatpickr.js') }}"></script>
    <script src="{{ asset('assets/plugins/flatpickr/l10n/es.js') }}"></script>
    <script>
        $(document).ready(function(){
            $('.fecha-datepicker').flatpickr({
                dateFormat: "d/m/Y",
                locale: 'es',
                maxDate: "today",
                altInputClass: 'text-danger'
            });

            $('.validar_numerico').on('keypress', validaNumerico)
        });
        function validaNumerico(event) {
            if(event.charCode >= 48 && event.charCode <= 57) return true;
            else return false;
        }
    </script>
@endsection