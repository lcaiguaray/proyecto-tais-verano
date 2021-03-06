@extends('layouts.master')

@section('title', '| Visualizar Indicador')
@section('item-empresas', 'active')

@section('css_after')
    <!-- Page JS Plugins CSS -->
    <link rel="stylesheet" href="{{ asset('assets/plugins/dataTables/DataTables-1.10.24/css/dataTables.bootstrap4.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/plugins/select2/css/select2.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/plugins/flatpickr/flatpickr.css') }}">
@endsection

@section('nav_breadcrumd')
    <ol class="breadcrumb has-arrow bg-light rounded">
        <li class="breadcrumb-item"><a href="{{ route('empresas') }}">Empresas</a></li>
        <li class="breadcrumb-item"><a href="{{ route('empresas.componentes', $empresa->id) }}">Componentes</a></li>
        <li class="breadcrumb-item"><a href="{{ route('indicadores', $empresa->id) }}">Indicadores</a></li>
        <li class="breadcrumb-item active">Visualizar</li>
    </ol>
@endsection

@section('contenido')
    <div class="row">
        <div class="col-lg-8 col-md-8 col-sm-8 col-8">
            <h4><i class="mdi mdi-apple-safari"></i> Visualizar</h4>
            <p class="text-gray">Visualizar Indicador</p>
            <p class="text-gray">Frecuencia: {{ tipo_frecuencias($indicador->frecuencia) }}</p>
        </div>
    </div>
    <div class="row mt-4">
        <div class="col-12 equel-grid">
            <div class="grid">
                <div class="grid-body">
                    <div class="item-wrapper">
                        <ul class="nav nav-pills mb-3" id="pills-tab" role="tablist">
                            <li class="nav-item" role="presentation">
                                <a class="nav-link active" id="pills-datos-tab" data-toggle="pill" href="#pills-datos" role="tab" aria-controls="pills-datos" aria-selected="true">Datos</a>
                            </li>
                            <li class="nav-item" role="presentation">
                                <a class="nav-link" id="pills-fuente-tab" data-toggle="pill" href="#pills-fuente" role="tab" aria-controls="pills-fuente" aria-selected="false">Data Fuente</a>
                            </li>
                            <li class="nav-item" role="presentation">
                                <a class="nav-link" id="pills-tablero-tab" data-toggle="pill" href="#pills-tablero" role="tab" aria-controls="pills-tablero" aria-selected="false">Tablero de Comando</a>
                            </li>
                        </ul>
                        <div class="tab-content" id="pills-tabContent">
                            <div class="tab-pane fade show active" id="pills-datos" role="tabpanel" aria-labelledby="pills-datos-tab">
                                <div class="form-row">
                                    <div class="col-lg-2 col-md-2 col-sm-6">
                                        <div class="form-group">
                                            <label class="font-weight-bold" for="proceso">Tipo</label>
                                            <p>{{ tipo_objetos($indicador->objeto_tipo) }}</p>
                                        </div>
                                    </div>
                                    <div class="col-lg-5 col-md-5 col-sm-6">
                                        <div class="form-group">
                                            <label class="font-weight-bold" for="proceso">Proceso/Subproceso</label>
                                            @if (tipo_objetos($indicador->objeto_tipo) == 'Proceso')
                                                <p>{{ $indicador->proceso->nombre }}</p>
                                            @else
                                                <p>{{ $indicador->subproceso->nombre }}</p>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="col-lg-5 col-md-5 col-sm-6">
                                        <div class="form-group">
                                            <label class="font-weight-bold" for="responsable">Responsable</label>
                                            <p>{{ $indicador->responsable }}</p>
                                        </div>
                                    </div>
                                    <div class="col-lg-6 col-md-6 col-sm-6">
                                        <div class="form-group">
                                            <label class="font-weight-bold" for="descripcion">??Qu?? se desea medir?</label>
                                            <p>{{ $indicador->descripcion }}</p>
                                        </div>
                                    </div>
                                    <div class="col-lg-6 col-md-6 col-sm-6">
                                        <div class="form-group">
                                            <label class="font-weight-bold" for="realizar">??Qu?? realizar?? la medici??n?</label>
                                            <p>{{ $indicador->realizar }}</p>
                                        </div>
                                    </div>
                                    <div class="col-lg-6 col-md-6 col-sm-6">
                                        <div class="form-group">
                                            <label class="font-weight-bold" for="mecanismo">??Qu?? mecanismos de medici??n se utilizar???</label>
                                            <p>{{ $indicador->mecanismo }}</p>
                                        </div>
                                    </div>
                                    <div class="col-lg-6 col-md-6 col-sm-6">
                                        <div class="form-group">
                                            <label class="font-weight-bold" for="tolerancia">??Qu?? tolerancias de desviaci??n podr??n determinarse?</label>
                                            <p>{{ $indicador->tolerancia }}</p>
                                        </div>
                                    </div>
                                    <div class="col-lg-6 col-md-6 col-sm-6">
                                        <div class="form-group">
                                            <label class="font-weight-bold" for="resultados">??Qu?? se har?? con los resultados?</label>
                                            <p>{{ $indicador->resultados }}</p>
                                        </div>
                                    </div>
                                    <div class="col-lg-6 col-md-6 col-sm-6">
                                        <div class="form-group">
                                            <label class="font-weight-bold" for="resultados">Formula</label>
                                            @if (tipo_formulas($indicador->formula) == 'Formula 01')
                                                <p>{{ '[1-('.$indicador->primer_parametro.'/'.$indicador->segundo_parametro.')]*100' }}</p>
                                            @else
                                                @if (tipo_formulas($indicador->formula) == 'Formula 02')
                                                    <p>{{ '('.$indicador->primer_parametro.'/'.$indicador->segundo_parametro.')*100' }}</p>
                                                @else
                                                    <p>&pound;({{ $indicador->primer_parametro }})</p>
                                                @endif
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="tab-pane fade" id="pills-fuente" role="tabpanel" aria-labelledby="pills-fuente-tab">
                                <div class="row mb-4">
                                    <div class="col-lg-8 col-md-8 col-sm-8 col-8">
                                        <h4><i class="mdi mdi-database"></i> Data Fuente</h4>
                                        <p class="text-gray">Lista de datos</p>
                                    </div>
                                    <div class="col-lg-4 col-md-4 col-sm-4 col-4">
                                        <button type="button" class="btn btn-inverse-success float-right float-xs-none has-icon" data-toggle="modal" data-target="#modal-create-fuente" title="Registrar" data-original-title="Registrar">
                                            <i class="mdi mdi-library-plus"></i> Agregar Nuevo
                                        </button>
                                    </div>
                                </div>
                                <div class="table-responsive">
                                    <table id="table_fuentes" class="table" style="width:100%">
                                        <thead>
                                            <tr>
                                                <th class="text-center" style="width: 10%">Valor</th>
                                                <th class="text-center">Fecha</th>
                                                <th class="text-center" style="width: 20%">Acciones</th>
                                            </tr>
                                        </thead>
                                        <tfoot>
                                            <tr>
                                                <th class="text-center" style="width: 10%">Valor</th>
                                                <th class="text-center">Fecha</th>
                                                <th class="text-center" style="width: 15%">Acciones</th>
                                            </tr>
                                        </tfoot>
                                    </table>
                                </div>
                            </div>
                            <div class="tab-pane fade" id="pills-tablero" role="tabpanel" aria-labelledby="pills-tablero-tab">
                                <div class="row mb-4 mt-4">
                                    <div class="col-lg-8 col-md-8 col-sm-8 col-8">
                                        <h4><i class="mdi mdi-view-array"></i> Tablero</h4>
                                    </div>
                                </div>
                                <div class="table-responsive">
                                    <table class="table" style="width:100%">
                                        <tbody>
                                            <tr>
                                                <td class="font-weight-bold" style="width: 30%">INDICADOR</td>
                                                <td>{{ $indicador->nombre }}</td>
                                            </tr>
                                            <tr>
                                                <td class="font-weight-bold" style="width: 30%">RESPONSABLE</td>
                                                <td>{{ $indicador->responsable }}</td>
                                            </tr>
                                            <tr>
                                                <td class="font-weight-bold" style="width: 30%">OBJETIVO</td>
                                                <td>{{ $indicador->objetivo }}</td>
                                            </tr>
                                            <tr>
                                                <td class="font-weight-bold" style="width: 30%">META</td>
                                                <td>{{ $indicador->meta }}</td>
                                            </tr>
                                            <tr>
                                                <td class="font-weight-bold" style="width: 30%">SEM??FORO</td>
                                                <td>
                                                    <p class="mb-2"><span class="badge badge-danger">{{ $indicador->condicion_rojo }}</span></p>
                                                    <p class="mb-2"><span class="badge" style="background-color: yellow;">Entre: {{ $indicador->condicion_rojo.' y '.$indicador->condicion_verde }}</span></p>
                                                    <p class="mb-2"><span class="badge badge-success">{{ $indicador->condicion_verde }}</span></p>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td class="font-weight-bold" style="width: 30%">INICIATIVAS</td>
                                                <td>{{ $indicador->iniciativa }}</td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                                <div class="row mb-4 mt-4">
                                    <div class="col-lg-8 col-md-8 col-sm-8 col-8">
                                        <h4><i class="mdi mdi-chart-pie"></i> Resultados</h4>
                                    </div>
                                    <div class="col-lg-4 col-md-4 col-sm-4 col-4">
                                        <button id="btnActualizar" type="button" class="btn btn-inverse-warning float-right float-xs-none has-icon" title="Actualizar tabla" data-original-title="Actualizar tabla">
                                            <i class="mdi mdi-refresh"></i> Actualizar Resultados
                                        </button>
                                    </div>
                                </div>
                                <div class="table-responsive">
                                    <table id="table_resultados" class="table" style="width:100%">
                                        <thead>
                                            <tr>
                                                <th class="text-center" style="width: 20%">Fecha</th>
                                                <th class="text-center" style="width: 20%">Formula</th>
                                                <th class="text-center" style="width: 20%">Resultado</th>
                                                <th class="text-center" style="width: 20%">Indicador</th>
                                            </tr>
                                        </thead>
                                        <tfoot>
                                            <tr>
                                                <th class="text-center" style="width: 20%">Fecha</th>
                                                <th class="text-center" style="width: 20%">Formula</th>
                                                <th class="text-center" style="width: 20%">Resultado</th>
                                                <th class="text-center" style="width: 20%">Indicador</th>
                                            </tr>
                                        </tfoot>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @include('componentes.indicadores.modals.create-fuente')
    @include('componentes.indicadores.modals.edit-fuente')
    @include('componentes.indicadores.modals.delete-fuente')
@endsection

@section('js_after')
    <!-- Page JS Plugins JS -->
    <script src="{{ asset('assets/plugins/dataTables/DataTables-1.10.24/js/jquery.dataTables.js') }}"></script>
    <script src="{{ asset('assets/plugins/dataTables/DataTables-1.10.24/js/dataTables.bootstrap4.js') }}"></script>
    <script src="{{ asset('assets/plugins/select2/js/select2.full.js') }}"></script>
    <script src="{{ asset('assets/plugins/flatpickr/flatpickr.js') }}"></script>
    <script src="{{ asset('assets/plugins/flatpickr/l10n/es.js') }}"></script>
    <script>
        const table = $('#table_fuentes')
        const tableResultados = $('#table_resultados')
        let valTipoFormula = {!! json_encode($indicador->formula) !!}
        let IDData = null

        $(document).ready(function(){
            constructDatatable(false)
            constructDatatableResultado(false)
            $(".select2_form").select2({
                placeholder: "Seleccionar",
                allowClear: false,
                width: '100%',
            });

            $('.fecha-datepicker').flatpickr({
                dateFormat: "d/m/Y",
                locale: 'es',
                maxDate: "today",
                altInputClass: 'text-danger',
            });

            // SHOW MODALS
            $('#modal-edit-fuente').on('show.bs.modal', showModalEditFuente)
            $('#modal-delete-fuente').on('show.bs.modal', showModalDeleteFuente)

            // FORMULARIOS
            $('#formCreateFuente').on('submit', submitFormCreateFuente)
            $('#formEditFuente').on('submit', submitFormEditFuente)
            $('#formDeleteFuente').on('submit', submitFormDeleteFuente)

            $('#btnActualizar').on('click', function(){
                constructDatatableResultado(true)
            })
            $('.validar_numerico').on('keypress', validaNumerico)
            $('.validar_decimal').on('keypress', validarDecimal)
        });

        // DATATABLE
        function constructDatatable(isDestroy) {
            let enlace = "{{ route('datafuente.datatable_datos', ':id') }}"
            enlace = enlace.replace(':id', {!! json_encode($indicador->id) !!})
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
                    url: '{{ asset("datatable_espa??ol.json") }}'
                },
                "info": false,
                "order": [[ 1, "asc" ]],
                "columns": [
                    {data: 'primer_parametro', class: 'text-center'},
                    {data: 'fecha', class: 'text-center'},
                    {data: 'actions', class: 'text-center'}
                ]
            });
        }

        function constructDatatableResultado(isDestroy) {
            let enlace = "{{ route('datafuente.datatable_resultados', ':id') }}"
            enlace = enlace.replace(':id', {!! json_encode($indicador->id) !!})
            if (isDestroy)
                tableResultados.DataTable().destroy();

            tableResultados.DataTable({
                responsive: true,
                autoWidth: false,
                pageLength: 20,
                lengthMenu: [[20, 50, 100], [20, 50, 100]],
                processing:true,
                "ajax": enlace,
                language: {
                    url: '{{ asset("datatable_espa??ol.json") }}'
                },
                "info": false,
                "order": [[ 0, "asc" ]],
                "columns": [
                    {data: 'fecha', class: 'text-center'},
                    {data: 'formula', class: 'text-center'},
                    {data: 'resultado', class: 'text-center'},
                    {data: 'indicador', class: 'text-center'}
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
        function showModalEditFuente(event){
            var button = $(event.relatedTarget) // Button that triggered the modal
            IDData = button.data('id')
            let primero = button.data('primero')
            let segundo = button.data('segundo')
            let fecha = button.data('fecha')

            $('#Eprimer_parametro').removeClass('is-invalid')
            $('#Esegundo_parametro').removeClass('is-invalid')

            var modal = $(this)
            modal.find('#Efecha').val(fecha).trigger('change')
            modal.find('#Eprimer_parametro').val(primero)
            if(valTipoFormula != 'F3') modal.find('#Esegundo_parametro').val(segundo)
        }

        function showModalDeleteFuente(event) {
            var button = $(event.relatedTarget) // Button that triggered the modal
            IDData = button.data('id')
            let valor = button.data('valor')
            let fecha = button.data('fecha')
            
            var modal = $(this)
            modal.find('#mensaje_delete').text(valor + ' y de fecha: ' + fecha)
        }
        // END MODALS

        // SUBMITS
        function submitFormCreateFuente(e){
            e.preventDefault()
            let enlace = "{{ route('datafuente.store', ':id') }}"
            enlace = enlace.replace(':id', {!! json_encode($indicador->id) !!})
            let valPrimerParametro = null
            let valSegundoParametro = null
            if(valTipoFormula != 'F3'){
                valPrimerParametro = $('#Cprimer_parametro').val()
                valSegundoParametro = $('#Csegundo_parametro').val()
                
                if(Number(valSegundoParametro) >= Number(valPrimerParametro)){
                    if(Number(valSegundoParametro) != 0){
                        $('#Cprimer_parametro').removeClass('is-invalid')
                        $('#Csegundo_parametro').removeClass('is-invalid')
                    }else{
                        $('#Cprimer_parametro').addClass('is-invalid')
                        $('#Csegundo_parametro').addClass('is-invalid')
                        customNotification('<span class="font-weight-bold"><i class="mdi mdi-bell"></i> El segundo parametro debe ser diferente de 0.</span>', 'sunset', 'error')
                        return false;
                    }
                }else{
                    $('#Cprimer_parametro').addClass('is-invalid')
                    $('#Csegundo_parametro').addClass('is-invalid')
                    customNotification('<span class="font-weight-bold"><i class="mdi mdi-bell"></i> El segundo parametro debe ser mayor que el primero.</span>', 'sunset', 'error')
                    return false;
                }
            }

            $.ajax({
                type: "POST",
                url: enlace,
                data: $('#formCreateFuente').serialize(),
                success: function(response){
                    if(!response.error){
                        $('#Cfecha').val('')
                        $('#Cprimer_parametro').val('')
                        $('#Csegundo_parametro').val('')
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

        function submitFormEditFuente(e){
            e.preventDefault()
            let enlace = "{{ route('datafuente.update', ':id') }}"
            enlace = enlace.replace(':id', IDData)
            let valPrimerParametro = null
            let valSegundoParametro = null
            if(valTipoFormula != 'F3'){
                valPrimerParametro = $('#Eprimer_parametro').val()
                valSegundoParametro = $('#Esegundo_parametro').val()
                
                if(Number(valSegundoParametro) >= Number(valPrimerParametro)){
                    if(Number(valSegundoParametro) != 0){
                        $('#Eprimer_parametro').removeClass('is-invalid')
                        $('#Esegundo_parametro').removeClass('is-invalid')
                    }else{
                        $('#Eprimer_parametro').addClass('is-invalid')
                        $('#Esegundo_parametro').addClass('is-invalid')
                        customNotification('<span class="font-weight-bold"><i class="mdi mdi-bell"></i> El segundo parametro debe ser diferente de 0.</span>', 'sunset', 'error')
                        return false;
                    }
                }else{
                    $('#Eprimer_parametro').addClass('is-invalid')
                    $('#Esegundo_parametro').addClass('is-invalid')
                    customNotification('<span class="font-weight-bold"><i class="mdi mdi-bell"></i> El segundo parametro debe ser mayor que el primero.</span>', 'sunset', 'error')
                    return false;
                }
            }

            $.ajax({
                type: "PUT",
                url: enlace,
                data: $('#formEditFuente').serialize(),
                success: function(response){
                    if(!response.error){
                        $('#Efecha').val('')
                        $('#Eprimer_parametro').val('')
                        $('#Esegundo_parametro').val('')
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

        function submitFormDeleteFuente(e){
            e.preventDefault();
            let enlace = "{{ route('datafuente.delete', ':id') }}"
            enlace = enlace.replace(':id', IDData)

            $.ajax({
                type: "PUT",
                url: enlace,
                data: $('#formDeleteFuente').serialize(),
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