<div class="row" id="div-plan_accion">
    <div class="col-xs-12 col-md-6">
        <fieldset style="border-right: none;">
            <legend class="w-100">
                Hallazgo
            </legend>
            <div class="p-3 pt-1">
                <textarea style="border: 1px solid #f1f1f1;" name="hallazgo" cols="30" rows="5" class="form-control sz_inp" placeholder="Ingrese el hallazgo observado"></textarea>
            </div>
        </fieldset>
    </div>
    <div class="col-xs-12 col-md-6">
        <fieldset style="border-right: none;">
            <legend class="w-100">
                Plan de Acción
            </legend>
            <div class="p-3 pt-1">

                <textarea style="border: 1px solid #f1f1f1;" name="plan_accion" cols="30" rows="5" class="form-control sz_inp" placeholder="Ingrese el plan de acción "></textarea>
            </div>
        </fieldset>
    </div>
    <div class="col-xs-12 col-md-3">
        <fieldset style="border-right: none;">
            <legend class="w-100">
                Contratista
            </legend>
            <div class="p-3 pt-1">
                <label for="contratista_plan" class="mb-2 sz_inp fw-semibold">Seleccione la Contratista</label>
                <select class="sz_inp" name="contratista_plan" id="contratista_plan" style="width: 100%" name="native-select" data-search="true" data-silent-initial-value-set="true">
                    <?php
                    foreach ($contratistas as $e) {
                        echo  "<option value='" . $e['id'] . "'>" . $e['nombre'] . "</option>";
                    }
                    ?>
                </select>
            </div>
        </fieldset>
    </div>
    <div class="col-xs-12 col-md-3">
        <fieldset style="border-right: none;">
            <legend class="w-100">
                Responsable
            </legend>
            <div class="p-3 pt-1">
                <label for="responsable" class="mb-2 sz_inp fw-semibold">Responsable de dar tratamiento</label>
                <select class="sz_inp" name="responsable" id="responsable" style="width: 100%" name="native-select" data-search="true" data-silent-initial-value-set="true">
                    <?php
                    foreach ($responsables as $e) {
                        echo  "<option value='" . $e['id'] . "'>" . $e['nombre'] .  " " . $e['apellido'] . "</option>";
                    }
                    ?>
                </select>
            </div>
        </fieldset>
    </div>
    <div class="col-xs-12 col-md-3">
        <fieldset style="border-right: none;">
            <legend class="w-100">
                Relevo Responsable
            </legend>
            <div class="p-3 pt-1">
                <label for="relevo_responsable" class="mb-2 sz_inp fw-semibold">Relevo de Responsable <em><small>(Si Corresponde)</small></em></label>
                <select class="sz_inp" name="relevo_responsable" id="relevo_responsable" style="width: 100%" name="native-select" data-search="true" data-silent-initial-value-set="true">
                    <?php
                    foreach ($responsables as $e) {
                        echo  "<option value='" . $e['id'] . "'>" . $e['nombre'] .  " " . $e['apellido'] . "</option>";
                    }
                    ?>
                </select>
            </div>
        </fieldset>
    </div>
    <div class="col-xs-12 col-md-3">
        <fieldset style="border-right: none;">
            <legend class="w-100">
                Fecha de Cierre
            </legend>
            <div class="p-3 pt-1">
                <label for="fecha_cierre" class="mb-2 sz_inp fw-semibold">Fecha Cierre</label>
                <input type="date" class="form-control sz_inp" name="fecha_cierre" id="fecha_cierre" min="<?= date('Y-m-d') ?>">
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
            <div id="gallery" class="adj_gallery" style="margin-left: 10px;"></div>
        </fieldset>
    </div>
</div>