<div class="modal fade" id="modal-active" tabindex="-1" aria-labelledby="activeLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header bg-light">
                <h5 class="modal-title" id="activeLabel">Habilitar Mapa de proceso</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form id="formActive" action="" method="POST">
                @csrf
                @method('put')
                <div class="modal-body">
                    <p class="text-center">¿Está seguro que desea habilitar el mapa de proceso:
                        <span id="mensaje_active" class="font-weight-bold"></span>?
                    </p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-sm btn-secondary has-icon" data-dismiss="modal"><i class="mdi mdi-close"></i> Cancelar</button>
                    <button type="submit" class="btn btn-sm btn-primary has-icon"><i class="mdi mdi-content-save"></i> Aceptar</button>
                </div>
            </form>
        </div>
    </div>
  </div>
