<div class="row" id="btns_add_plan_accion">
    <div class="col-xs-12 col-md-12 text-center">
        <p class="border_obs">La Auditoría actual ¿Posee una observación a tratar?</p>
        <div class="d-flex justify-content-center">
            <button class="btn_toggle_plan" id="btn_yes_a" style="margin-right: 2px;">Si</button>
            <button class="btn_toggle_plan" id="btn_no_a" style="margin-left: 2px;">No</button>
            <input type="hidden" name="oportunidad_mejora_a" id="oportunidad_mejora_a">
        </div>
    </div>
</div>
<input type="checkbox" id="toggle_plan_a">
<div class="row container_plan_a" id="container_plan_a"> <!-- #container_plan -->
    <div class="row">
        <div class="col-xs-12 col-md-6">
            <fieldset style="border-right: none;">
                <legend class="w-100">
                    Hallazgo
                </legend>
                <div class="p-3 pt-1">
                    <textarea style="border: 1px solid #f1f1f1;" name="hallazgo_a" cols="30" rows="5"
                        class="form-control sz_inp" placeholder="Ingrese el hallazgo observado"></textarea>
                </div>
            </fieldset>
        </div>
        <div class="col-xs-12 col-md-6">
            <fieldset style="border-right: none;">
                <legend class="w-100">
                    Plan de Acción
                </legend>
                <div class="p-3 pt-1">
                    <textarea style="border: 1px solid #f1f1f1;" name="plan_accion_a" cols="30" rows="5"
                        class="form-control sz_inp" placeholder="Ingrese el plan de acción "></textarea>
                </div>
            </fieldset>
        </div>
    </div>
    <div class="row mt-2">
        <div class="col-xs-12 col-md-6">
            <fieldset style="border-right: none;">
                <legend class="w-100">
                    Efecto / Impacto
                </legend>
                <div class="p-3 pt-1">
                    <label for="efecto_impacto_a" class="mb-2 sz_inp fw-semibold">Seleccione el efecto o impacto</label>
                    <select class="sz_inp rounded-select " name="efecto_impacto_a[]" id="efecto_impacto_a"
                        style="width: 100%" multiple name="native-select" data-search="true"
                        data-silent-initial-value-set="true">
                        <?php
                        foreach ($efectos_impactos as $e) {
                            echo "<option value='" . $e['id'] . "'>" . $e['nombre'] . "</option>";
                        }
                        ?>
                    </select>
                </div>
            </fieldset>
        </div>
        <div class="col-xs-12 col-md-6">
            <fieldset style="border-right: none;">
                <legend class="w-100 d-flex align-items-center">
                    Riesgos Observados
                    <div class="contain-question_icon" data-bs-toggle="modal" data-bs-target="#modal_significancia">
                        <div class="question-icon">
                            <span>?</span>
                        </div>
                    </div>
                </legend>
                <div class="text-center" style="padding: 19px 0!important;">
                    <div class="btn-group btn-group-toggle" style="width: 80%;" role="group" aria-label="">
                        <input id="aceptable_a" type="checkbox" name="significancia_a[]" class="btn-check blanco_check"
                            value="1" autocomplete="off">
                        <label class="btn blanco btnsToggle riesgos" for="aceptable_a">Aceptable</label>

                        <input id="moderado_a" type="checkbox" name="significancia_a[]" class="btn-check verde_check"
                            value="2" autocomplete="off">
                        <label class="btn verde btnsToggle riesgos" for="moderado_a">Moderado</label>

                        <input id="significativo_a" type="checkbox" name="significancia_a[]"
                            class="btn-check amarillo_checked" value="3" autocomplete="off">
                        <label class="btn amarillo btnsToggle riesgos" for="significativo_a">Significativo</label>

                        <input id="intolerable_a" type="checkbox" name="significancia_a[]" class="btn-check rojo_check"
                            value="4" autocomplete="off">
                        <label class="btn rojo btnsToggle riesgos" for="intolerable_a">Intolerable</label>
                    </div>

                </div>
            </fieldset>
        </div>
    </div>

    <div class="col-xs-12 col-md-4">
        <fieldset style="border-right: none;">
            <legend class="w-100">
                Responsable
            </legend>
            <div class="p-3 pt-1">
                <label for="responsable_plan_a" class="mb-2 sz_inp fw-semibold">Responsable de dar tratamiento</label>
                <select class="sz_inp" name="responsable_plan_a" id="responsable_plan_a" style="width: 100%"
                    name="native-select" data-search="true" data-silent-initial-value-set="true">
                    <?php
                    foreach ($usuarios as $e) {
                        echo "<option value='" . $e['id'] . "'>" . $e['nombre'] . " " . $e['apellido'] . "</option>";
                    }
                    ?>
                </select>
            </div>
        </fieldset>
    </div>
    <div class="col-xs-12 col-md-4">
        <fieldset style="border-right: none;">
            <legend class="w-100">
                Relevo Responsable
            </legend>
            <div class="p-3 pt-1">
                <label for="relevo_responsable_plan" class="mb-2 sz_inp fw-semibold">Relevo de Responsable
                    <em><small>(Si Corresponde)</small></em></label>
                <select class="sz_inp" name="relevo_responsable_plan_a" id="relevo_responsable_plan_a"
                    style="width: 100%" name="native-select" data-search="true" data-silent-initial-value-set="true">
                    <?php
                    foreach ($usuarios as $e) {
                        echo "<option value='" . $e['id'] . "'>" . $e['nombre'] . " " . $e['apellido'] . "</option>";
                    }
                    ?>
                </select>
            </div>
        </fieldset>
    </div>

    <div class="col-xs-12 col-md-4">
        <fieldset style="border-right: none;">
            <legend class="w-100">
                Fecha de Cierre
            </legend>
            <div class="p-3 pt-1">
                <label for="fecha_cierre_a" class="mb-2 sz_inp fw-semibold">Fecha Cierre</label>
                <input type="date" class="form-control sz_inp" name="fecha_cierre_a" id="fecha_cierre_a"
                    min="<?= date('Y-m-d') ?>">
            </div>
        </fieldset>
    </div>
    <div class="col-xs-12 mt-3">
        <fieldset style="border-right: none;">
            <legend class="w-100">
                Cargar Adjuntos
            </legend>
            <div class="p-3 pb-1 pt-1">
                <label class="sz_inp fw-semibold">Adjuntos</label>
            </div>
            <div id="gallery_a" class="adj_gallery"></div>
        </fieldset>
    </div>
</div> 