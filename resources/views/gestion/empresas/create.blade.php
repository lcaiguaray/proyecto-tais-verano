@extends('layouts.master')

@section('title', '| Registrar Empresa')
@section('item-empresas', 'active')

@section('css_after')
    <!-- Page JS Plugins CSS -->
@endsection

@section('nav_breadcrumd')
    <ol class="breadcrumb has-arrow bg-light rounded">
        <li class="breadcrumb-item"><a href="{{ route('empresas') }}">Empresas</a></li>
        <li class="breadcrumb-item active">Registrar</li>
    </ol>
@endsection

@section('contenido')
    <div class="row">
        <div class="col-lg-8 col-md-8 col-sm-8 col-8">
            <h4><i class="mdi mdi-buffer"></i> Registrar</h4>
            <p class="text-gray">Nueva Empresa</p>
        </div>
    </div>
    <div class="row mt-4">
        <div class="col-12 equel-grid">
            <div class="grid">
                <div class="grid-body">
                    <div class="item-wrapper">
                        <p class="text-muted font-size-sm mb-0">Los campos marcados con * son obligatorios.</p>
                        <form action="{{ route('empresas.store') }}" method="POST">
                            @csrf
                            <div class="form-row">
                                <div class="col-lg-6 col-md-6 col-sm-12">
                                    <div class="form-group">
                                        <label for="razonsocial">Razon Social <span class="text-success">*</span></label>
                                        <input type="text" class="form-control form-control-sm {{ $errors->has('razonsocial') ? ' is-invalid' : '' }}" id="razonsocial" name="razonsocial" value="{{ old('razonsocial') }}" maxlength="255" required>
                                        @if ($errors->has('razonsocial'))
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $errors->first('razonsocial') }}</strong>
                                            </span>
                                        @endif
                                    </div>
                                </div>
                                <div class="col-lg-3 col-md-3 col-sm-6">
                                    <div class="form-group">
                                        <label for="ruc">RUC <span class="text-success">*</span></label>
                                        <input type="text" class="form-control form-control-sm validar_numerico {{ $errors->has('ruc') ? ' is-invalid' : '' }}" id="ruc" name="ruc" value="{{ old('ruc') }}" maxlength="11" autocomplete="off" required>
                                        @if ($errors->has('ruc'))
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $errors->first('ruc') }}</strong>
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
                                <a href="{{ route('empresas') }}" class="btn btn-sm btn-secondary has-icon mr-2"><i class="mdi mdi-close"></i> Cancelar</a>
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
    <script>
        $(document).ready(function(){
            $('.validar_numerico').on('keypress', validaNumerico)
        });
        function validaNumerico(event) {
            if(event.charCode >= 48 && event.charCode <= 57) return true;
            else return false;
        }
    </script>
@endsection