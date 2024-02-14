<link href="<?= base_url() ?>/assets/css/tarjetaObs/view_obs.css" rel="stylesheet">
<link href="<?= base_url() ?>/assets/css/tarjetaObs/modal_riesgo.css" rel="stylesheet">
<link href="<?= base_url() ?>/assets/css/tarjetaObs/checkAnimado.css" rel="stylesheet">

<?php

$t = $tarjeta['hallazgo'];
$descargos['descargos'] = $tarjeta['hallazgo']['descargos'];

?>
<!-- == En caso de ser el responsable se le va a mostrar la siguiente alerta == -->
<?php
if ($tarjeta['cierre'] == null) : ?>
    <br>
    <?php if ($t['responsable'] == session()->get('id_usuario')) { ?>
        <div class="row">
            <h5 class="text-center" style="color: #645b4a;"><i style="color: #e5af78;" class="fas fa-exclamation-circle icono_alerta"></i> Estimado/da <?= $t['responsable_nombre'] . ' ' . $t['responsable_apellido'] ?>, usted tiene una tarea pendiente a resolver</h5>
        </div>
    <?php } else if ($t['relevo_responsable'] == session()->get('id_usuario')) { ?>
        <div class="row">
            <h5 class="text-center" style="color: #645b4a;"><i style="color: #e5af78;" class="fas fa-exclamation-circle icono_alerta"></i> Estimado/da <?= $t['otro_responsable_nombre'] . ' ' . $t['otro_responsable_apellido'] ?>, usted es relevo de <?= $t['responsable_nombre'] . ' ' . $t['responsable_apellido'] ?> en caso de ser necesario</h5>
        </div>
    <?php }  ?>
<?php endif; ?>
<br>

<div>
    <div class="row">
        <div class="col-xs-12 col-md-6">
            <fieldset>
                <legend>
                    Descripción del hallazgo observado
                </legend>
                <div class="row" style="padding: 10px 50px;">
                    <textarea class="form-control sz_inp" name="hallazgo" id="hallazgo" cols="30" rows="5" style="border: 1px solid #f1f1f1;" disabled><?= $t['hallazgo'] ?></textarea>
                </div>
            </fieldset>
        </div>
        <div class="col-xs-12 col-md-6">
            <fieldset>
                <legend>
                    Plan de Acción
                </legend>
                <div class="row" style="padding: 10px 50px;">
                    <textarea class="form-control sz_inp" name="hallazgo" id="hallazgo" cols="30" rows="5" style="border: 1px solid #f1f1f1;" disabled><?= $t['plan_accion'] ?></textarea>
                </div>
            </fieldset>
        </div>
        <div class="col-xs-12 col-md-3">
            <fieldset style="border-right: 0;">
                <legend style="width: 100%;">
                    Contratista
                </legend>
                <div class="row" style="padding: 1px 50px;">
                    <input class="form-control sz_inp" type="text" id="clasificacion" value="<?= $t['contratista'] ?>" style="border: none;" readonly>
                </div>
            </fieldset>
        </div>
        <div class="col-xs-12 col-md-3">
            <fieldset style="border-right: 0;">
                <legend style="width: 100%;">
                    Responsable
                </legend>
                <div class="row" style="padding: 1px 50px;">
                    <input class="form-control sz_inp" type="text" id="clasificacion" value="<?= $t['responsable_nombre'] . ' ' . $t['responsable_apellido']  ?>" style="border: none;" readonly>
                </div>
            </fieldset>
        </div>

        <div class="col-xs-12 col-md-3">
            <fieldset style="border-right: 0;">
                <legend style="width: 100%;">
                    Relevo de Responsable
                </legend>
                <div class="row" style="padding: 1px 50px;">
                    <?php if ($t['otro_responsable_nombre']) { ?>
                        <input class="form-control sz_inp" type="text" id="clasificacion" value="<?= $t['otro_responsable_nombre'] . ' ' . $t['otro_responsable_apellido']  ?>" style="border: none;" readonly>
                    <?php } else { ?>
                        <input class="form-control sz_inp" type="text" id="clasificacion" value="No Aplica" style="border: none;" readonly>
                    <?php }  ?>
                </div>
            </fieldset>
        </div>

        <div class="col-xs-12 col-md-3">
            <fieldset style="border-right: 0;">
                <legend style="width: 100%;">
                    Fecha de Cierre
                </legend>
                <div class="row" style="padding: 1px 50px;">
                    <input class="form-control sz_inp" type="date" id="fecha_cierre" value="<?= $t['fecha_cierre'] ?>" style="border: none;" readonly>
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

        <div>
            <br>
            <div class="row">
                <p class="txt_adjunto">Sector de Descargos/Respuestas</p>
                <!-- == Botón para agregar descargo (Abre un Modal) == -->
                <?= view('tarjetas_obs/view_obs/descargo_obs', $descargos); ?> <!-- descargo_obs.php -->
                <?php if (is_null($tarjeta['cierre'])) : ?>
                    <?php if ($tarjeta['usuario_carga'] != session()->get('id_usuario') && $tarjeta['hallazgo']['responsable'] == session()->get('id_usuario') || $tarjeta['hallazgo']['relevo_responsable'] == session()->get('id_usuario')) : ?>
                        <div class="row" id="btns_descargos">
                            <div class="text-center">
                                <button class="btn_add_descargo" id="add_descargo" data-bs-toggle="modal" data-bs-target="#modal_add_descargo">Agregar Descargo</button>
                            </div>
                        </div>
                    <?php endif; ?>
                <?php endif; ?>

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