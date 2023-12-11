<div class="card-body">
    <fieldset class="fieldset_positivo fieldset_add_hallazgo">
        <legend class="w-100 legend_positivo legend_add"><?= $h['tipo_obs'] ?> #<?= $h['id_hallazgo'] ?> - <em><?= $h['aspecto'] ?></legend>

        <div class="row p-3">
            <!-- Efecto / Impacto -->
            <div class="col-xs-12">
                <fieldset style="border-right: none;">
                    <legend class="w-100">
                        Efecto / Impacto
                    </legend>
                    <div class="p-3 pt-1">
                        <div class="row mt-3 justify-content-between" style="flex-wrap: wrap;">
                            <?php if (count($h['efectos']) > 0) : ?>
                                <?php foreach ($h['efectos'] as $f) : ?>
                                    <div class="col-xs-12 col-md-4 text-center">
                                        <p class="p-0"><i class="fa-solid fa-circle-check"></i><?= $f['nombre_efecto'] ?></p>
                                    </div>
                                <?php endforeach; ?>

                            <?php else : ?>
                                <div class="col-xs-12 col-md-4 text-center">
                                    <p class="p-0"><i class="fa-solid fa-circle-check"></i><em>No se agregaron efectos</em></p>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>
                </fieldset>
            </div>

            <!-- Riesgos Observados -->
            <?php if ($h['tipo_aspecto'] == 1) : ?>
                <!-- Riesgo Observado de Seguridad y Salud -->
                <div class="col-xs-12">
                    <fieldset style="border-right: none;">
                        <legend class="w-100">
                            Riesgos Observados
                        </legend>

                        <div class="p-3 pt-1 text-center">
                            <div class="btn-group btn-group-toggle" style="width: 80%;" role="group" aria-label="">
                                <input id="aceptable" type="checkbox" class="btn-check blanco_check" value="1" autocomplete="off" disabled <?= $h['significancia'] == 1 ? 'checked' : '' ?>>
                                <label class="btn blanco btnsToggle riesgos" for="aceptable">Aceptable</label>

                                <input id="moderado" type="checkbox" class="btn-check verde_check" value="2" autocomplete="off" disabled <?= $h['significancia'] == 2 ? 'checked' : '' ?>>
                                <label class="btn verde btnsToggle riesgos" for="moderado">Moderado</label>

                                <input id="significativo" type="checkbox" class="btn-check amarillo_checked" value="3" autocomplete="off" disabled <?= $h['significancia'] == 3 ? 'checked' : '' ?>>
                                <label class="btn amarillo btnsToggle riesgos" for="significativo">Significativo</label>

                                <input id="intolerable" type="checkbox" class="btn-check rojo_check" value="4" autocomplete="off" disabled <?= $h['significancia'] == 4 ? 'checked' : '' ?>>
                                <label class="btn rojo btnsToggle riesgos" for="intolerable">Intolerable</label>
                            </div>
                        </div>
                    </fieldset>
                </div>

            <?php else : ?>
                <!-- Riesgo Observado de Impacto Ambiental -->
                <div class="col-xs-12">
                    <fieldset style="border-right: none;">
                        <legend class="w-100">
                            Riesgos Observados
                        </legend>

                        <div class="p-3 pt-1 text-center">
                            <div class="btn-group btn-group-toggle" style="width: 80%;" role="group">
                                <input id="aceptable" type="checkbox" class="btn-check blanco_check" disabled <?= $h['significancia'] == 5 ? 'checked' : '' ?>>
                                <label class="btn blanco btnsToggle riesgos" for="aceptable">Baja</label>

                                <input id="moderado" type="checkbox" class="btn-check verde_check" disabled <?= $h['significancia'] == 6 ? 'checked' : '' ?>>
                                <label class="btn verde btnsToggle riesgos" for="moderado">Media</label>

                                <input id="significativo" type="checkbox" class="btn-check amarillo_checked" disabled <?= $h['significancia'] == 7 ? 'checked' : '' ?>>
                                <label class="btn amarillo btnsToggle riesgos" for="significativo">Alta</label>

                                <input id="intolerable" type="checkbox" class="btn-check rojo_check" disabled <?= $h['significancia'] == 8 ? 'checked' : '' ?>>
                                <label class="btn rojo btnsToggle riesgos" for="intolerable">Muy Alta</label>
                            </div>
                        </div>
                    </fieldset>
                </div>
            <?php endif; ?>

            <!-- Hallazgo -->
            <div class="col-xs-12 col-md-6">
                <fieldset style="border-right: 0;">
                    <legend style="width: 100%;">
                        Descripción del hallazgo observado
                    </legend>
                    <div class="row" style="padding: 10px 50px;">
                        <textarea class="form-control sz_inp" cols="30" rows="5" style="border: 1px solid #f1f1f1;" disabled><?= $h['hallazgo'] ?></textarea>
                    </div>
                </fieldset>
            </div>

            <!-- Responsable -->
            <div class="col-xs-12 col-md-6">
                <fieldset style="border-right: 0;">
                    <legend style="width: 100%;">
                        Responsable Notificado
                    </legend>
                    <div class="row" style="padding: 1px 50px;">
                        <input class="form-control sz_inp" type="text" value="<?= $h['usuario_responsable'] ?>" style="border: none;" readonly>
                    </div>
                </fieldset>
            </div>

            <!-- Adjuntos -->
            <div>
                <div class="row">
                    <p class="txt_adjunto">Adjuntos</p>

                    <?php if (!empty($h['adjuntos'])) { ?>
                        <?php foreach ($h['adjuntos'] as $adj) : ?>
                            <div class="col-xs-12 col-md-4 text-center">
                                <a href="<?= base_url("uploads/tarjetaObs/") . '/' . $adj['adjunto'] ?>" target="_blank">
                                    <img id="img_descargo" src="<?= base_url("uploads/tarjetaObs/") . '/' . $adj['adjunto'] ?>" alt="">
                                </a>

                                <?php if ($adj['desc_adjunto'] != '') { ?>
                                    <p class="mt-2"><?= $adj['desc_adjunto'] ?></p>
                                <?php } else { ?>
                                    <p class="mt-2"><em>El adjunto no posee descripción</em></p>
                                <?php }  ?>
                            </div>
                        <?php endforeach; ?>
                    <?php } else { ?>
                        <p class="text-center"><em>No se agregaron adjuntos a la observación</em></p>
                    <?php }  ?>

                </div>
            </div>
        </div>

    </fieldset>
</div>