@extends('layouts.master')

@section('title', '| Empresas')
@section('item-empresas', 'active')

@section('css_after')
    <!-- Page JS Plugins CSS -->
@endsection

@section('contenido')
    <div class="row">
        <div class="col-lg-8 col-md-8 col-sm-8 col-8">
            <h4>Empresas</h4>
            <p class="text-gray">Lista de empresas</p>
        </div>
        <div class="col-lg-4 col-md-4 col-sm-4 col-4">
            <a href="{{ route('empresas.create') }}" class="btn btn-inverse-success float-right float-xs-none has-icon"><i class="mdi mdi-library-plus"></i> Agregar Nuevo</a>
        </div>
    </div>
    <div class="row mt-4 equel-grid">
        <div class="col-12">
            <div class="grid">
                <div class="grid-body">
                    <div class="table-responsive">
                        <div class="col-md-4 mb-3 float-right">
                            <input class="form-control form-control-sm light-table-filter" data-table="order-table" type="text" placeholder="Buscar">
                        </div>
                        <table class="table table-sm order-table">
                            <thead>
                                <tr>
                                    <th class="text-center" style="width: 15%">RUC</th>
                                    <th style="width: 30%">Razon Social</th>
                                    <th>Email</th>
                                    <th>Estado</th>
                                    <th>Acciones</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($empresas as $empresa)
                                    <tr>
                                        <td class="text-center">{{ $empresa->ruc }}</td>
                                        <td>{{ $empresa->razon_social }}</td>
                                        <td>{{ $empresa->email }}</td>
                                        <td>
                                            @if ($empresa->activo)
                                                <span class="badge badge-success">Activo</span>
                                            @else
                                                <span class="badge badge-danger">No Activo</span>
                                            @endif
                                        </td>
                                        <td>
                                            @if ($empresa->activo)
                                                <a href="{{ route('empresas.edit', $empresa->id) }}" class="btn btn-sm action-btn btn-inverse-info" title="Editar" data-original-title="Editar">
                                                    <i class="mdi mdi-lead-pencil"></i>
                                                </a>
                                                <button type="button" class="btn btn-sm action-btn btn-inverse-danger" data-toggle="modal" data-target="#delete" title="Deshabilitar" data-original-title="Deshabilitar" data-id="{{ $empresa->id }}" data-nombre="{{ $empresa->razon_social }}">
                                                    <i class="mdi mdi-arrow-down-bold-hexagon-outline"></i>
                                                </button>
                                            @else
                                                <button type="button" class="btn btn-sm action-btn btn-inverse-success" data-toggle="modal" data-target="#active" title="Habilitar" data-original-title="Habilitar" data-id="{{ $empresa->id }}" data-nombre="{{ $empresa->razon_social }}">
                                                    <i class="mdi mdi-arrow-up-bold-hexagon-outline"></i>
                                                </button>
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
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
    <script type="text/javascript">
        (function(document) {
          'use strict';
    
          var LightTableFilter = (function(Arr) {
    
            var _input;
    
            function _onInputEvent(e) {
              _input = e.target;
              var tables = document.getElementsByClassName(_input.getAttribute('data-table'));
              Arr.forEach.call(tables, function(table) {
                Arr.forEach.call(table.tBodies, function(tbody) {
                  Arr.forEach.call(tbody.rows, _filter);
                });
              });
            }
    
            function _filter(row) {
              var text = row.textContent.toLowerCase(), val = _input.value.toLowerCase();
              row.style.display = text.indexOf(val) === -1 ? 'none' : 'table-row';
            }
    
            return {
              init: function() {
                var inputs = document.getElementsByClassName('light-table-filter');
                Arr.forEach.call(inputs, function(input) {
                  input.oninput = _onInputEvent;
                });
              }
            };
          })(Array.prototype);
    
          document.addEventListener('readystatechange', function() {
            if (document.readyState === 'complete') {
              LightTableFilter.init();
            }
          });
    
        })(document);
    </script>
    <script>
        $(document).ready( function () {
            $('#delete').on('show.bs.modal', showModalDelete)
            $('#active').on('show.bs.modal', showModalActive)
        });
        
        function showModalDelete(event) {
            var button = $(event.relatedTarget) // Button that triggered the modal
            var id = button.data('id')
            var razon_social = button.data('nombre')

            let enlace = "{{ route('empresas.delete', ':id') }}"
            enlace = enlace.replace(':id', id)

            var modal = $(this)
            modal.find('#mensaje_delete').text(razon_social)
            modal.find('#formDelete').attr('action', enlace)
        }
        function showModalActive(event) {
            var button = $(event.relatedTarget) // Button that triggered the modal
            var id = button.data('id')
            var razon_social = button.data('nombre')

            let enlace = "{{ route('empresas.active', ':id') }}"
            enlace = enlace.replace(':id', id)

            var modal = $(this)
            modal.find('#mensaje_active').text(razon_social)
            modal.find('#formActive').attr('action', enlace)
        }
    </script>
@endsection