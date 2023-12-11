<div class="card-body">
    <fieldset class="fieldset_mejora fieldset_add_hallazgo">
        <legend class="w-100 legend_mejora legend_add"><?= $h['tipo_obs'] ?> #<?= $h['id_hallazgo'] ?> - <em><?= $h['aspecto'] ?></em></legend>

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

            <!-- Plan de Acción -->
            <div class="col-xs-12 col-md-6">
                <fieldset style="border-right: 0;">
                    <legend style="width: 100%;">
                        Plan de acción
                    </legend>
                    <div class="row" style="padding: 10px 50px;">
                        <textarea class="form-control sz_inp" cols="30" rows="5" style="border: 1px solid #f1f1f1;" disabled><?= $h['plan_accion'] ?></textarea>
                    </div>
                </fieldset>
            </div>

            <!-- Responsable -->
            <div class="col-xs-12 col-md-4">
                <fieldset style="border-right: 0;">
                    <legend style="width: 100%;">
                        Responsable
                    </legend>
                    <div class="row" style="padding: 1px 50px;">
                        <input class="form-control sz_inp" type="text" value="<?= $h['usuario_responsable'] ?>" style="border: none;" readonly>
                    </div>
                </fieldset>
            </div>

            <!-- Segundo Responsable -->
            <div class="col-xs-12 col-md-4">
                <fieldset style="border-right: 0;">
                    <legend style="width: 100%;">
                        Segundo Responsable
                    </legend>
                    <div class="row" style="padding: 1px 50px;">
                        <input class="form-control sz_inp" type="text" id="clasificacion" value="<?= $h['relevo_responsable'] != null ? $h['relevo_responsable'] : 'No Aplica' ?>" style="border: none;" readonly>
                    </div>
                </fieldset>
            </div>

            <!-- Fecha de Cierre -->
            <div class="col-xs-12 col-md-4">
                <fieldset style="border-right: 0;">
                    <legend style="width: 100%;">
                        Fecha de Cierre
                    </legend>
                    <div class="row" style="padding: 1px 50px;">
                        <input class="form-control sz_inp" type="text" value="<?= $h['fecha_cierre_f'] ?>" style="border: none;" readonly>
                    </div>
                </fieldset>
            </div>
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

        <!-- Sector de descargos y/o Respuestas -->
        <div>
            <div class="row">
                <p class="txt_adjunto">Sector de Descargos/Respuestas</p>

                <?php if (count($h['descargos']) > 0) : ?>
                    <?php $i = 1;
                    foreach ($h['descargos'] as $d) : ?>
                        <div class="card" style="width: 90%; margin: 0 10px 0 auto; box-shadow: 4px 4px 5px 0px rgba(189,186,186,0.75); border: 1px solid rgba(189,186,186,0.75)!important;">
                            <div class="card-header d-flex justify-content-between" style="color: #252525; background-color: white; font-weight: 500; letter-spacing: .5px;">
                                <div>
                                    <p class="m-0 p-o">
                                        Respuesta #<?= $i ?>
                                    </p>
                                </div>
                                <div>
                                    <p class="m-0 p-o">
                                        <?= $d['fecha_hora_motivo'] ?> - <?= $d['nombre'] . ' ' . $d['apellido'] ?>
                                    </p>
                                </div>
                            </div>
                            <div class="card-body">
                                <div class="col-xs-12 col-md-12">
                                    <fieldset>
                                        <legend>
                                            Fundamento Motivo
                                        </legend>
                                        <div class="row" style="padding: 10px 50px;">
                                            <?= $d['motivo'] ?>
                                        </div>
                                    </fieldset>
                                </div>

                                <div>
                                    <br>
                                    <div class="row">
                                        <p class="txt_adjunto">Adjuntos</p>
                                        <?php if (count($d['descargos_adj']) > 0) { ?>
                                            <?php foreach ($d['descargos_adj'] as $d_adj) : ?>

                                                <div class="col-xs-12 col-md-4 text-center">
                                                    <img id="img_descargo" src="<?= base_url("uploads/tarjetaObs/descargos/" . $d_adj['adjunto']) ?>">
                                                    <?php if ($d_adj['desc_adjunto'] != '') { ?>
                                                        <p class="mt-2"><?= $d_adj['desc_adjunto'] ?></p>
                                                    <?php } else { ?>
                                                        <p class="mt-2"><em>El adjunto no posee descripción</em></p>
                                                    <?php }  ?>
                                                </div>

                                            <?php endforeach; ?>
                                        <?php } else { ?>
                                            <p class="mt-2 text-center"><em>No se cargaron adjuntos para esta respuesta</em></p>
                                        <?php }  ?>
                                    </div>
                                </div>

                            </div>
                        </div>

                        <?php if ($d['respuesta'] == null) : ?>
                            <?php if (session()->get('superadmin') || $h['id_usuario_carga'] == session()->get('id_usuario')) : ?>
                                <div class="row btns_descargos mb-2" id="btns_descargos" style="margin-left: 44px;">
                                    <div style="margin: 15px 0 0 71px;">
                                        <button class="btn_mod_success aceptar_descargo" id="aceptar_descargo_<?= $d['id'] ?>" data-id='<?= $d['id'] ?>'>Aceptar Descargo</button>
                                        <button class="btn_mod_danger rechazar_descargo" id="rechazar_descargo_<?= $d['id'] ?>" data-id='<?= $d['id'] ?>'>Rechazar Descargo</button>
                                    </div>
                                </div>
                            <?php endif; ?>

                            <!-- == Motivo si se acepta/rechaza un descargo == -->
                            <div class="container_motivo mt-3" style="display: none;">

                                <form id="form_motivo_descargo_<?= $d['id'] ?>" method="POST">
                                    <div class="row">
                                        <div class="col-xs-12 col-md-2"></div>
                                        <div class="col-xs-12 col-md-10">
                                            <label class="mb-1 fw-semibold sz_inp label_motivo" id="label_motivo"></label>
                                            <input type="hidden" class="tipo_rta_descargo" name="tipo_rta_descargo">
                                            <input type="hidden" class="inp_id_descargo" name="inp_id_descargo">
                                            <textarea class="form-control sz_inp" name="rta_descargo" id="motivo_descargo" cols="30" rows="3" placeholder="Ingrese el motivo.." required></textarea>
                                        </div>
                                    </div>
                                    <br>
                                    <div class="row">
                                        <div class="col-md-10"></div>
                                        <div class="col-xs-12 col-md-2 mb-2">
                                            <button class="btn_mod_success aceptar_motivo_descargo">Aceptar</button>
                                            <button class="btn_mod_danger cancelar_motivo_descargo">Cancelar</button>
                                        </div>
                                    </div>
                                </form>

                            </div>

                        <?php endif; ?>

                        <!-- Descargo Aceptado -->
                        <?php if ($d['respuesta'] != null && $d['estado'] == 1) { ?>
                            <div class="card mt-3 p-0" style="width: 80%; margin: 0 8px 0 auto; box-shadow: 4px 4px 5px 0px rgba(189,186,186,0.75); border-top: 1px solid #beebc2!important; border-right: 1px solid rgba(189,186,186,0.75)!important; border-bottom: 1px solid rgba(189,186,186,0.75)!important; border-left: 1px solid #beebc2!important;">
                                <div class="card-header flex-column text-center" style="color: #3ea746; background-color: #f5fff5;">

                                    <div>
                                        <p class="m-0 p-o fw-semibold">
                                            Descargo Aceptado
                                        </p>
                                    </div>
                                    <div>
                                        <p class="m-0 p-o">
                                            Aceptado el día <?= $d['fecha_hora_respuesta'] ?> por <?= $d['nombre_user_rta'] . ' ' . $d['apellido_user_rta'] ?>
                                        </p>
                                    </div>
                                </div>
                                <div class="card-body">
                                    <div class="col-xs-12 col-md-12">
                                        <fieldset>
                                            <legend>
                                                Descripción de la Respuesta
                                            </legend>
                                            <div class="row" style="padding: 10px 50px;">
                                                <?= $d['respuesta'] ?>
                                            </div>
                                        </fieldset>
                                    </div>
                                </div>
                            </div>
                        <?php } else if ($d['respuesta'] != null && $d['estado'] == 2) { ?>
                            <!-- Descargo Rechazado -->
                            <div class="card mt-3 mb-3 p-0" style="width: 80%; margin: 0 8px 0 auto; box-shadow: 4px 4px 5px 0px rgba(189,186,186,0.75); border-top: 1px solid #912d2d1c!important; border-right: 1px solid rgba(189,186,186,0.75)!important; border-bottom: 1px solid rgba(189,186,186,0.75)!important; border-left: 1px solid #912d2d1c!important;">
                                <div class="card-header flex-column text-center" style="color: #a73e3e; background-color: #912d2d1c;">

                                    <div>
                                        <p class="m-0 p-o fw-semibold">
                                            Descargo Rechazado
                                        </p>
                                    </div>
                                    <div>
                                        <p class="m-0 p-o">
                                            Rechazado el día <?= $d['fecha_hora_respuesta'] ?> por <?= $d['nombre_user_rta'] . ' ' . $d['apellido_user_rta'] ?>
                                        </p>
                                    </div>
                                </div>
                                <div class="card-body">
                                    <div class="col-xs-12 col-md-12">
                                        <fieldset>
                                            <legend>
                                                Descripción de la Respuesta
                                            </legend>
                                            <div class="row" style="padding: 10px 50px;">
                                                <?= $d['respuesta'] ?>
                                            </div>
                                        </fieldset>
                                    </div>
                                </div>
                            </div>

                        <?php } ?>
                    <?php $i++;
                    endforeach; ?>
                    <!-- TODO | Arreglar un toque este código, está feito y no anda como debe lpm. ¡Urgente no funciona! -->
                    <?php $check_add_new_descargo = false; ?>
                    <?php foreach ($h['descargos'] as $descargo) : ?>
                        <?php
                        if ($descargo['estado'] == 2) :
                            if ($descargo['respuesta'] != null && ($descargo['estado'] == 0 || $descargo['estado'] == 2)) {
                                $check_add_new_descargo = true;
                            }
                        endif;
                        ?>
                    <?php endforeach; ?>

                    <?php if ($check_add_new_descargo) : ?>
                        <div class="row mt-3" id="btns_descargos">
                            <div class="text-center">
                                <button class="btn_add_descargo add_descargo" id="add_descargo" data-id-hallazgo="<?= $h['id_hallazgo'] ?>" data-bs-toggle="modal" data-bs-target="#modal_add_descargo">Agregar Nuevo Descargo</button>
                            </div>
                        </div>
                    <?php endif; ?>

                <?php else : ?>
                    <!-- Lo tiene que ver solamente el administrador, el responsable del hallazgo y/o también si hay algun relevo -->
                    <?php if (session()->get('superadmin') || $h['id_responsable'] == session()->get('id_usuario') || !is_null($h['id_relevo_responsable']) && $h['id_relevo_responsable'] == session()->get('id_usuario')) : ?>
                        <div class="row" id="btns_descargos">
                            <div class="text-center">
                                <button class="btn_add_descargo add_descargo" id="add_descargo" data-id-hallazgo="<?= $h['id_hallazgo'] ?>" data-bs-toggle="modal" data-bs-target="#modal_add_descargo">Agregar Descargo</button>
                            </div>
                        </div>
                    <?php endif; ?>
                <?php endif; ?>
            </div>
        </div>
        <br>
    </fieldset>
</div>