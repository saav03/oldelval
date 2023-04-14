<link href="<?= base_url() ?>/assets/css/tarjetaObs/view_obs.css" rel="stylesheet">
<link href="<?= base_url() ?>/assets/css/tarjetaObs/modal_riesgo.css" rel="stylesheet">
<link href="<?= base_url() ?>/assets/css/tarjetaObs/checkAnimado.css" rel="stylesheet">

<?php

$t = $tarjeta['hallazgo'];

?>
<!-- == En caso de ser el responsable se le va a mostrar la siguiente alerta == -->
<br>
<?php if ($t['responsable'] == session()->get('id_usuario')) : ?>
    <div class="row">
        <h5 class="text-center" style="color: #645b4a;"><i style="color: #e5af78;" class="fas fa-exclamation-circle icono_alerta"></i> Estimado/da <?= $t['responsable_nombre'] . ' ' . $t['responsable_apellido'] ?>, usted tiene una tarea pendiente a resolver</h5>
    </div>
<?php endif; ?>
<br>

<div id="caja_plan_accion">
    <div class="row p-3" id="contenedor_plan">
        <div class="col-xs-12 col-md-6">
            <label class="mb-1 fw-semibold sz_inp">Hallazgo </label>
            <textarea class="form-control sz_inp" name="hallazgo" id="hallazgo" cols="30" rows="5"><?= $t['hallazgo'] ?></textarea>
        </div>
        <div class="col-xs-12 col-md-6">
            <label class="mb-1 fw-semibold sz_inp">Plan de Acción </label>
            <textarea class="form-control sz_inp" id="accion_recomendacion" cols="30" rows="5" readonly><?= $t['accion_recomendacion'] ?></textarea>
        </div>
        <div class="col-xs-12 col-md-4 mt-3">
            <label class="mb-1 fw-semibold sz_inp">Clasificación de Hallazgo</label>
            <input class="form-control sz_inp" type="text" id="clasificacion" value="<?= $t['clasificacion'] ?>" readonly>
        </div>
        <div class="col-xs-12 col-md-4 mt-3">
            <label class="mb-1 fw-semibold sz_inp">Tipo de Hallazgo</label>
            <input class="form-control sz_inp" type="text" id="tipo_hallazgo" value="<?= $t['tipo'] ?>" readonly>
        </div>
        <div class="col-xs-12 col-md-4 mt-3">
            <div class="row">
                <div class="col-xs-12 col-md-6"><label class="mb-1 fw-semibold sz_inp">Significancia</label>
                    <input class="form-control sz_inp" name="riesgo" id="riesgo" readonly="true">
                </div>
                <div class="col-xs-12 col-md-6">
                    <button class="btn_riesgo" data-bs-target="#matrizRiesgoModal" data-bs-toggle="modal" onclick="prevenirDefault(event)">Ver Riesgo</button>
                </div>
            </div>
        </div>
        <div class="col-xs-12 col-md-4 mt-3">
            <label class="mb-1 fw-semibold sz_inp">Contratista</label>
            <input class="form-control sz_inp" type="text" id="contratista" value="<?= $t['contratista'] ?>" readonly>
        </div>
        <div class="col-xs-12 col-md-4 mt-3">
            <label class="mb-1 fw-semibold sz_inp">Responsable</label>
            <input class="form-control sz_inp" type="text" id="responsable" value="<?= $t['responsable_nombre'] . ' ' . $t['responsable_apellido']  ?>" readonly>
        </div>
        <div class="col-xs-12 col-md-4 mt-3">
            <label class="mb-1 fw-semibold sz_inp">Fecha de Cierre</label>
            <input class="form-control sz_inp" type="date" value="<?= $t['fecha_cierre'] ?>" id="fecha_cierre" readonly>
        </div>

        <div>
            <br>
            <div class="row">
                <p class="txt_adjunto">Adjuntos</p>

                <?php if (!empty($t['adjuntos'])) { ?>
                    <?php foreach ($t['adjuntos'] as $adj) : ?>
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
</div>

<div class="modal fade" id="matrizRiesgoModal" role="dialog">
    <div class="modal-dialog modal-lg" style="max-width: 1417px;">
        <div class="modal-content">
            <div class="modal-body" style="overflow-x:auto;">
                <?= $tabla; ?>
            </div>
            <input type="hidden" id="currentDesvioRiesgo">
            <div class="modal-footer">
                <button type="button" class="btn_mod_danger" data-bs-dismiss="modal">Confirmar</button>
            </div>
        </div>
    </div>
</div>
<div hidden>
    <div class='wrapper' id="checkis">
        <svg class='checkmark' xmlns='http://www.w3.org/2000/svg' viewBox='0 0 52 52'>
            <circle class='checkmark__circle' cx='26' cy='26' r='25' fill='none' />
            <path class='checkmark__check' fill='none' d='M14.1 27.2l7.1 7.2 16.7-16.8' />
        </svg>
    </div>
</div>