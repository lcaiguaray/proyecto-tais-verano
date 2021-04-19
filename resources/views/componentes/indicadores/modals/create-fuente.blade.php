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
                        <div class="col-lg-8 col-md-6 col-sm-12">
                            <div class="form-group">
                                <label for="Cfecha">Fecha <span class="text-success">*</span></label>
                                <input type="text" class="fecha-datepicker form-control form-control-sm" id="Cfecha" name="Cfecha" required>
                            </div>
                        </div>
                        <div class="col-lg-4 col-md-6 col-sm-12">
                            <div class="form-group">
                                <label for="Cvalor">Valor <span class="text-success">*</span></label>
                                <input type="text" class="validar_decimal form-control form-control-sm" id="Cvalor" name="Cvalor" autocomplete="off" onpaste="return false;">
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
