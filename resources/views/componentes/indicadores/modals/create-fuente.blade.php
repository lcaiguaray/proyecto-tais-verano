<div class="modal fade" id="modal-create-fuente" tabindex="-1" aria-labelledby="editLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-light">
                <h5 class="modal-title" id="editLabel">Registrar Nueva Data</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form id="formCreateFuente" action="" method="POST">
                @csrf
                <div class="modal-body">
                    <div class="form-row">
                        <div class="col-12">
                            <div class="form-group">
                                <label for="Cfecha">Fecha <span class="text-success">*</span></label>
                                <input type="text" class="fecha-datepicker form-control form-control-sm" id="Cfecha" name="Cfecha" required>
                            </div>
                        </div>
                        <div class="col-lg-6 col-md-6 col-sm-12">
                            <div class="form-group">
                                <label for="Cprimer_parametro">{{ $indicador->primer_parametro }} <span class="text-success">*</span></label>
                                <input type="text" class="validar_decimal form-control form-control-sm" id="Cprimer_parametro" name="Cprimer_parametro" autocomplete="off" onpaste="return false;" placeholder="Primer parametro" required>
                            </div>
                        </div>
                        @if ($indicador->formula != 'F3')
                            <div class="col-lg-6 col-md-6 col-sm-12">
                                <div class="form-group">
                                    <label for="Csegundo_parametro">{{ $indicador->primer_parametro }} <span class="text-success">*</span></label>
                                    <input type="text" class="validar_decimal form-control form-control-sm" id="Csegundo_parametro" name="Csegundo_parametro" autocomplete="off" onpaste="return false;" placeholder="Segundo parametro" required>
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-sm btn-secondary has-icon" data-dismiss="modal"><i class="mdi mdi-close"></i> Cancelar</button>
                    <button type="submit" class="btn btn-sm btn-primary has-icon"><i class="mdi mdi-content-save"></i> Aceptar</button>
                </div>
            </form>
        </div>
    </div>
  </div>
