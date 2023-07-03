<div class="row" id="div-reconocimiento">
    <div class="col-xs-12 col-md-12">
        <fieldset style="border-right: none;">
            <legend class="w-100">
                Descripción de lo observado
            </legend>
            <div class="p-3 pt-1">
                <textarea style="border: 1px solid #f1f1f1;" name="observado" cols="30" rows="5" class="form-control sz_inp inp_custom" placeholder="Ingrese lo observado"></textarea>
            </div>
        </fieldset>
    </div>
    <div class="col-xs-12 col-md-6">
        <fieldset style="border-right: none;">
            <legend class="w-100">
                Responsable
            </legend>
            <div class="p-3 pt-1">
                <label for="responsable" class="mb-2 sz_inp fw-semibold">Responsable a notificar por el reconocimiento</label>
                <select class="sz_inp" name="responsable_reconocimiento" id="responsable_reconocimiento" style="width: 100%" name="native-select" data-search="true" data-silent-initial-value-set="true">
                    <?php
                    foreach ($responsables as $e) {
                        echo  "<option value='" . $e['id'] . "'>" . $e['nombre'] .  " " . $e['apellido'] . "</option>";
                    }
                    ?>
                </select>
            </div>
        </fieldset>
    </div>
    <div class="col-xs-12 mt-2">
        <fieldset style="border-right: none;">
            <legend class="w-100">
                Cargar Adjuntos
            </legend>
            <div class="p-3 pb-1 pt-1">
                <label class="sz_inp fw-semibold">Adjuntos</label>
            </div>
            <div id="gallery_reconocimiento" class="adj_gallery" style="margin-left: 10px;"></div>
        </fieldset>
    </div>
</div>