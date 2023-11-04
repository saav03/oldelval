<link href="<?= base_url() ?>/assets/css/addFiles.css" rel="stylesheet">
<link href="<?= base_url() ?>/assets/css/fileAdder.css" rel="stylesheet">

<div class="container">
    <div class="card mt-4 mb-0 card_title_initial">
        <div class="card-header card_modif_aud">
            Observación
        </div>
    </div>

    <div class="card" style="padding: 10px 35px 35px 35px;">
        <div class="card-body" style="margin: 0px 20px 20px 20px;">
            <?php if ($h['id_responsable'] == session()->get('id_usuario') && is_null($h['cierre'])) { ?>
                <div class="row">
                    <h5 class="text-center" style="color: #645b4a;"><i style="color: #e5af78;"
                            class="fas fa-exclamation-circle icono_alerta"></i> Estimado/da
                        <?= $h['responsable'] ?>, usted tiene una tarea pendiente a resolver
                    </h5>
                </div>
            <?php } else if ($h['id_relevo'] == session()->get('id_usuario') && is_null($h['cierre'])) { ?>
                    <div class="row">
                        <h5 class="text-center" style="color: #645b4a;"><i style="color: #e5af78;"
                                class="fas fa-exclamation-circle icono_alerta"></i> Estimado/da
                        <?= $h['relevo'] ?>, usted es relevo de
                        <?= $h['responsable'] ?> en caso de ser necesario
                        </h5>
                    </div>
            <?php } ?>
            <div class="row">
                <div class="col-xs-12 col-md-6">
                    <fieldset style="border-right: none;">
                        <legend class="w-100">
                            Hallazgo
                        </legend>
                        <div class="p-3 pt-1">
                            <textarea style="border: none;" name="hallazgo" cols="30" rows="5"
                                class="form-control sz_inp"
                                readonly><?= $h['hallazgo'] ? $h['hallazgo'] : ''; ?></textarea>
                        </div>
                    </fieldset>
                </div>
                <div class="col-xs-12 col-md-6">
                    <fieldset style="border-right: none;">
                        <legend class="w-100">
                            Plan de Acción
                        </legend>
                        <div class="p-3 pt-1">
                            <textarea style="border: none;" cols="30" rows="5" class="form-control sz_inp"
                                readonly><?= $h['plan_accion'] ? $h['plan_accion'] : ''; ?></textarea>
                        </div>
                    </fieldset>
                </div>
                <div class="col-xs-12 col-md-12">
                    <fieldset style="border-right: none;">
                        <legend class="w-100">
                            Efecto / Impacto
                        </legend>
                        <div class="p-3 pt-1">
                            <div class="row mt-3 justify-content-between" style="flex-wrap: wrap;">
                                <?php foreach ($h['efectos'] as $efecto): ?>
                                    <div class="col-xs-12 col-md-4 text-center">
                                        <p class="p-0"><i class="fa-solid fa-circle-check"></i>
                                            <?= $efecto['nombre_efecto']; ?>
                                        </p>
                                    </div>
                                <?php endforeach; ?>
                            </div>
                        </div>
                    </fieldset>
                </div>
                <div class="col-xs-12 col-md-6">
                    <fieldset style="border-right: none;">
                        <legend class="w-100">
                            Riesgos Observados
                        </legend>

                        <div class="p-3 pt-1 text-center">
                            <div class="btn-group btn-group-toggle" style="width: 80%;" role="group" aria-label="">
                                <input id="aceptable" type="checkbox" name="significancia[]"
                                    class="btn-check blanco_check" value="1" autocomplete="off" disabled>
                                <label class="btn blanco btnsToggle riesgos" for="aceptable">Aceptable</label>

                                <input id="moderado" type="checkbox" name="significancia[]"
                                    class="btn-check verde_check" value="2" autocomplete="off" disabled>
                                <label class="btn verde btnsToggle riesgos" for="moderado">Moderado</label>

                                <input id="significativo" type="checkbox" name="significancia[]"
                                    class="btn-check amarillo_checked" value="3" autocomplete="off" disabled>
                                <label class="btn amarillo btnsToggle riesgos" for="significativo">Significativo</label>

                                <input id="intolerable" type="checkbox" name="significancia[]"
                                    class="btn-check rojo_check" value="4" autocomplete="off" disabled>
                                <label class="btn rojo btnsToggle riesgos" for="intolerable">Intolerable</label>
                            </div>
                        </div>
                    </fieldset>
                </div>
                <div class="col-xs-12 col-md-6">
                    <fieldset style="border-right: none;">
                        <legend class="w-100">
                            Responsable de dar tratamiento
                        </legend>
                        <div class="p-3 pt-1">
                            <input type="text" class="form-control sz_inp"
                                value="<?= $h['responsable'] ? $h['responsable'] : ''; ?>" readonly>
                        </div>
                    </fieldset>
                </div>
                <div class="col-xs-12 col-md-6">
                    <fieldset style="border-right: none;">
                        <legend class="w-100">
                            Relevo Responsable <em><small>(Si Corresponde)</small></em>
                        </legend>
                        <div class="p-3 pt-1">
                            <input type="text" class="form-control sz_inp"
                                value="<?= $h['relevo'] ? $h['relevo'] : 'No Aplica'; ?>" readonly>
                        </div>
                    </fieldset>
                </div>
                <div class="col-xs-12 col-md-6">
                    <fieldset style="border-right: none;">
                        <legend class="w-100">
                            Fecha de Cierre
                        </legend>
                        <div class="p-3 pt-1">
                            <input type="text" class="form-control sz_inp text-center"
                                value="<?= $h['fecha_cierre_format'] ? $h['fecha_cierre_format'] : ''; ?>" readonly>
                        </div>
                    </fieldset>
                </div>
            </div>
        </div>
        <div>
            <div class="row">
                <p class="txt_adjunto">Adjuntos</p>
                <?php if (!empty($h['adjuntos'])) { ?>
                    <?php foreach ($h['adjuntos'] as $adj): ?>
                        <div class="col-xs-12 col-md-4 text-center">
                            <a href="<?= base_url("uploads/auditorias/hallazgos") . '/' . $adj['adjunto'] ?>" target="_blank">
                                <img id="img_descargo"
                                    src="<?= base_url("uploads/auditorias/hallazgos") . '/' . $adj['adjunto'] ?>" alt="">
                            </a>

                            <?php if ($adj['desc_adjunto'] != '') { ?>
                                <p class="mt-2">
                                    <?= $adj['desc_adjunto'] ?>
                                </p>
                            <?php } else { ?>
                                <p class="mt-2"><em>El adjunto no posee descripción</em></p>
                            <?php } ?>
                        </div>
                    <?php endforeach; ?>
                <?php } else { ?>
                    <p class="text-center"><em>No se agregaron adjuntos a la observación</em></p>
                <?php } ?>
            </div>
        </div>

        <div>
            <br>
            <div class="row">
                <p class="txt_adjunto">Sector de Descargos/Respuestas</p>
                <!-- == Botón para agregar descargo (Abre un Modal) == -->
                <?php
                $descargos['descargos'] = $h['descargos'];
                ?>
                <?= view('auditoria/auditoria/aud_descargos', $descargos) ?>
                <?php if (is_null($h['cierre'])): ?>
                    <?php if ($h['id_usuario_carga'] != session()->get('id_usuario') && $h['id_responsable'] == session()->get('id_usuario') || $h['id_relevo'] == session()->get('id_usuario')): ?>
                        <div class="row" id="btns_descargos">
                            <div style="margin: 15px 0 0 71px;">
                                <button class="btn_modify" id="add_descargo" data-bs-toggle="modal"
                                    data-bs-target="#modal_add_descargo">Agregar Descargo</button>
                            </div>
                        </div>
                    <?php endif; ?>
                <?php endif; ?>
            </div>
        </div>

        <!-- == Cerrar la observación cuando las respuestas estén concluidas == -->
        <?php if (!is_null($h['cierre'])) { ?>
            <!-- == Fundamento de la observación si es que ya finalizó == -->

            <div class="card">
                <div class="card-header"
                    style="font-weight: 600; letter-spacing: 1.5px; text-align: center; background-color: #d7d7d7;">
                    Tarjeta de Observación Cerrada (
                    <?= $h['tiempo']['rta_cierre_obs'] ? 'A tiempo' : 'Fuera de Tiempo' ?>)
                </div>
                <div class="card-body">
                    <?php if ($h['cierre']['cierre_manual']): ?>
                        <div class="text-center mt-2 mb-2">

                            <small><em>(Hubieron descargos sin responder)</em></small>
                        </div>
                    <?php endif; ?>

                    <div class="text-center">
                        <span class="text-center">Cerrada el día
                            <?= $h['cierre']['fecha_hora_cierre'] ?> por
                            <?= $h['cierre']['usuario_cierre'] ?> (
                            <?= $h['tiempo']['diff']->days ?>
                            <?= $h['tiempo']['rta_cierre_obs'] ? 'días antes' : 'días atrasados' ?>)
                        </span>
                    </div>

                    <div class="col-xs-12 col-md-12">
                        <fieldset>
                            <legend>
                                Fundamento del Cierre
                            </legend>
                            <div class="row" style="padding: 10px 50px;">
                                <?= $h['cierre']['motivo'] ?>
                            </div>
                        </fieldset>
                    </div>
                </div>
            </div>

        <?php } else { ?>
            <?php if ($h['id_usuario_carga'] == session()->get('id_usuario')): ?>
                <div>
                    <button class="btn_modify" id="btn_cerrar_obs_completo" <?= empty($h['descargos']) ? 'disabled title="Deben haber descargos antes de cerrar esta observación"' : ''; ?>>Cerrar Observación</button>
                    <?php if (empty($h['descargos'])): ?>
                        <br>
                        <span class="fw-semibold"><small><em>(Deben haber descargos para poder cerrar ésta
                                    observación)</em></small></span>
                    <?php endif; ?>
                </div>
            <?php endif; ?>
        <?php } ?>
    </div>
</div>

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
                            <textarea name="new_descargo" id="new_descargo" cols="30" rows="4"
                                class="form-control sz_inp" placeholder="Ingrese la respuesta deseada"></textarea>
                            <input type="hidden" name="id_hallazgo" id="id_hallazgo_descargo"
                                value="<?= isset($h['id_hallazgo']) ? $h['id_hallazgo'] : '' ?>">
                            <input type="hidden" name="tipo_obs" value="4">
                        </div>
                        <div class="col-xs-12 col-md-12 mt-3">
                            <div id="gallery" class="adj_gallery"></div>
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

<!-- == Modal para cerrar el hallazgo de la auditoría == -->
<div class="modal fade" id="modal_cierre_obs" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <form id="form_cierre_obs" method="POST">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="exampleModalLabel">Motivo de cierre de la observación</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-xs-12 col-md-12">
                            <label for="motivo_cierre_obs" class="mb-1 fw-semibold sz_inp">Motivo de Cierre</label>
                            <textarea name="motivo_cierre_obs" id="motivo_cierre_obs" cols="30" rows="4"
                                class="form-control sz_inp"
                                placeholder="Ingrese el motivo por el cual cierra la observación"></textarea>
                            <input type="hidden" name="descargos_id[]" id="descargos_id">
                            <input type="hidden" name="cierre_forzado" id="cierre_forzado" value="0">
                            <input type="hidden" name="id_hallazgo"
                                value="<?= isset($h['id_hallazgo']) ? $h['id_hallazgo'] : '' ?>">
                        </div>

                    </div>

                </div>
                <div class="modal-footer">

                    <button type="button" class="btn_mod_close" data-bs-dismiss="modal">Cancelar</button>
                    <!-- == Con este btn se genera un envío de Ajax == -->
                    <!-- <button type="submit" class="btn_modify" id="btn_add_descargo">Enviar Respuesta</button> -->
                    <input type="button" id="btn_cerrar_obs" class="btn_modify" value="Cerrar Observación">
                    <input type="button" id="abrir_modal_cierre" data-bs-toggle="modal"
                        data-bs-target="#modal_cierre_obs" hidden>

                </div>
            </form>
        </div>
    </div>
</div>

<script src="<?= base_url() ?>/assets/js/addFiles.js"></script>
<script src="<?= base_url() ?>/assets/js/auditorias/descargo.js"></script>

<!-- Checkea los checkbox de la significancia según los que trae de BD -->
<script>
    let significancia = <?= json_encode($h['significancia']); ?>;
    let checkbox_significancia = document.getElementsByName('significancia[]');

    for (let i = 0; i < checkbox_significancia.length; i++) {
        for (let j = 0; j < significancia.length; j++) {
            if (checkbox_significancia[i].value == significancia[j].id_significancia) {
                checkbox_significancia[i].checked = true;
                checkbox_significancia[i].disabled = false;
            }
        }
    }
</script>

<!-- Modal de Cierre del hallazgo -->
<script>
    let abrir_modal_cierre = document.getElementById('abrir_modal_cierre');
    let descargos = <?= json_encode($h['descargos']); ?>;
    let rta_descargo = true;

    const btn_cerrar_obs_completo = document.getElementById('btn_cerrar_obs_completo');

    btn_cerrar_obs_completo.addEventListener('click', () => {
        descargos.forEach(d => {
            if (d.respuesta == null) {
                rta_descargo = false;
            }
        });

        if (rta_descargo) {
            document.getElementById('cierre_forzado').value = 0;
            abrir_modal_cierre.click();
        } else {
            customConfirmationButton(
                "Descargos sin Respuestas",
                "Aún hay descargos sin responder. ¿Está seguro de cerrar ésta observación?",
                "Estoy seguro",
                "Cancelar",
                "swal_edicion"
            ).then((result) => {
                if (result.isConfirmed) {
                    document.getElementById('cierre_forzado').value = 1;
                    abrir_modal_cierre.click();
                } else {
                    document.getElementById('cierre_forzado').value = 0;
                }
            })
        }

    });
</script>

<script>
    let arrayImgs = [];
    new addFiles(document.getElementById("gallery"), 'adj_descargo').init();
</script>