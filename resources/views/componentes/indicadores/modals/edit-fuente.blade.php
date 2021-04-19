<div class="modal fade" id="modal-edit-fuente" tabindex="-1" aria-labelledby="editLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-light">
                <h5 class="modal-title" id="editLabel">Actualizar Data</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form id="formEditFuente" action="" method="POST">
                @csrf
                @method('put')
                <div class="modal-body">
                    <div class="form-row">
                        <div class="col-lg-8 col-md-6 col-sm-12">
                            <div class="form-group">
                                <label for="Efecha">Fecha <span class="text-success">*</span></label>
                                <input type="text" class="fecha-datepicker form-control form-control-sm" id="Efecha" name="Efecha" required>
                            </div>
                        </div>
                        <div class="col-lg-4 col-md-6 col-sm-12">
                            <div class="form-group">
                                <label for="Evalor">Valor <span class="text-success">*</span></label>
                                <input type="text" class="validar_decimal form-control form-control-sm" id="Evalor" name="Evalor" autocomplete="off" onpaste="return false;">
                            </div>
                        </div>
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
