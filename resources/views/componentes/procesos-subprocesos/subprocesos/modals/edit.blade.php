<div class="modal fade" id="modal-edit" tabindex="-1" aria-labelledby="editLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-light">
                <h5 class="modal-title" id="editLabel">Actualizar Subproceso</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form id="formEdit" action="" method="POST">
                @csrf
                @method('put')
                <div class="modal-body">
                    <div class="form-row">
                        <div class="col-12">
                            <div class="form-group">
                                <label for="Enombre">Nombre <span class="text-success">*</span></label>
                                <input type="text" class="form-control form-control-sm" id="Enombre" name="Enombre" maxlength="255" required>
                                <span id="Enombre_error" class="invalid-feedback" role="alert" style="display: block;">
                                    <strong></strong>
                                </span>
                            </div>
                        </div>
                        <div class="col-12">
                            <div class="form-group">
                                <label for="Edescripcion">Descripción</label>
                                <textarea class="form-control form-control-sm" id="Edescripcion" name="Edescripcion" rows="2"></textarea>
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
