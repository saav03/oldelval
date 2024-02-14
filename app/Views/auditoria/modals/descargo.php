<!-- == Modal para agregar descargo == -->
<div class="modal fade" id="modal_add_descargo" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <form id="form_descargo" method="POST">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="exampleModalLabel">Nuevo descargo hacia la observación</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-xs-12 col-md-12">
                            <label for="new_descargo" class="mb-1 fw-semibold sz_inp">Respuesta / Descargo</label>
                            <textarea name="new_descargo" id="new_descargo" cols="30" rows="4" class="form-control sz_inp" placeholder="Ingrese la respuesta deseada"></textarea>
                            <input type="hidden" name="id_hallazgo" id="id_hallazgo_descargo">
                        </div>
                        <div class="col-xs-12 col-md-12 mt-3">
                            <div id="gallery_descargos" class="adj_gallery" style="margin-left: 10px;"></div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn_mod_close" data-bs-dismiss="modal">Cancelar Respuesta</button>
                    <!-- == Con este btn se genera un envío de Ajax == -->
                    <input type="submit" id="btn_add_descargo" class="btn_modify" value="Enviar Respuesta">
                </div>
            </form>
        </div>
    </div>
</div>