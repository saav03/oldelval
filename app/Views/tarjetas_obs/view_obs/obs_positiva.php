<link href="<?= base_url() ?>/assets/css/tarjetaObs/view_obs.css" rel="stylesheet">
<link href="<?= base_url() ?>/assets/css/tarjetaObs/modal_riesgo.css" rel="stylesheet">
<link href="<?= base_url() ?>/assets/css/tarjetaObs/checkAnimado.css" rel="stylesheet">

<?php
$t = $tarjeta['hallazgo'];
?>

<div>
    <div class="row">
        <div class="col-xs-12 col-md-12">
            <fieldset>
                <legend>
                    Descripción del hallazgo observado
                </legend>
                <div class="row" style="padding: 10px 50px;">
                    <textarea class="form-control sz_inp" name="hallazgo" id="hallazgo" cols="30" rows="5" style="border: 1px solid #f1f1f1;" disabled><?= $t['hallazgo'] ?></textarea>
                </div>
            </fieldset>
        </div>
        <div class="col-xs-12 col-md-12">
            <fieldset style="border-right: 0;">
                <legend style="width: 100%;">
                    Contratista
                </legend>
                <div class="row" style="padding: 4px 50px;">
                    <input class="form-control sz_inp" type="text" id="clasificacion" value="<?= $t['contratista'] ?>" style="border: none;" readonly>
                </div>
            </fieldset>
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
