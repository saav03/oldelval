<link href="<?= base_url() ?>/assets/css/tarjetaObs/view_obs.css" rel="stylesheet">
<link href="<?= base_url() ?>/assets/css/tarjetaObs/modal_riesgo.css" rel="stylesheet">
<link href="<?= base_url() ?>/assets/css/tarjetaObs/checkAnimado.css" rel="stylesheet">

<?php
$t = $tarjeta['hallazgo'];
?>

<div id="caja_plan_accion_positiva">
    <div class="row p-3" id="contenedor_plan_positivo">
        <div class="col-xs-12 col-md-12">
            <label class="mb-1 fw-semibold sz_inp">Descripción de lo Observado</label>
            <textarea class="form-control sz_inp" name="hallazgo" id="hallazgo" cols="30" rows="5"><?= $t['hallazgo'] ?></textarea>
        </div>
        <div class="col-xs-12 col-md-6 mt-3">
            <label class="mb-1 fw-semibold sz_inp">Clasificación de Hallazgo</label>
            <input class="form-control sz_inp" type="text" id="clasificacion" value="<?= $t['clasificacion'] ?>" readonly>
        </div>
        <div class="col-xs-12 col-md-6 mt-3">
            <label class="mb-1 fw-semibold sz_inp">Contratista</label>
            <input class="form-control sz_inp" type="text" id="contratista" value="<?= $t['contratista'] ?>" readonly>
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