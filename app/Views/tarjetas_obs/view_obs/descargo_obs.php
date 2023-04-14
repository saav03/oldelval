<link href="<?= base_url() ?>/assets/css/tarjetaObs/view_obs.css" rel="stylesheet">

<!-- == Descargo (Si es que existe) perteneciente a la observación == -->

<?php
$d = $descargos;
$usuario_carga = $tarjeta['usuario_carga'];
?>

<div>
    <?php $i = 0;
    foreach ($descargos as $d) : $i++; ?>
        <div class="d-flex flex-row align-items-center">
            <div class="col-xs-12 col-md-1"></div>
            <div class="col-xs-12 col-md-11">
                <div class="content_descargo">
                    <div class="row">
                        <div>
                            <!-- <p class="subtitle">Respuesta #<!?= $i ?> <em><!?= $d['fecha_hora_motivo'] ?></em> - <!?= $d['nombre'] . ' ' . $d['apellido'] ?> </p> -->

                            <p class="subtitle d-flex justify-content-between">
                                <span>Respuesta #<?= $i ?></span>
                                <span><em><?= $d['fecha_hora_motivo'] ?></em> - <?= $d['nombre'] . ' ' . $d['apellido'] ?></span>
                            </p>

                        </div>
                        <div class="col-xs-12 col-md-12 mt-3">
                            <p class="content_text" style="width: 100%;"><?= $d['motivo'] ?></p>
                        </div>

                        <br>

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
                </div> <!-- .content-descargo -->
            </div>

        </div>

        <!-- == Botones de Acciones == -->
        <?php if (is_null($d['respuesta'])) { ?>
            <?php if ($tarjeta['usuario_carga'] == session()->get('id_usuario')) : ?>
                <div class="row" id="btns_descargos" style="margin-left: 23px;">
                    <div style="margin: 15px 0 0 71px;">
                        <button class="btn_mod_success" id="aceptar_descargo" data-id='<?= $d['id'] ?>'>Aceptar Descargo</button>
                        <button class="btn_mod_danger" id="rechazar_descargo" data-id='<?= $d['id'] ?>'>Rechazar Descargo</button>
                    </div>
                </div>
            <?php endif; ?>
        <?php } else { ?>
            <!-- == Esto ya es el motivo, si es que ya se aceptó/rechazó el descargo == -->
            <br>
            <?php if ($d['estado'] == 1) { ?> <!-- Aceptó -->
                
                <div class="div_aceptado mt-3 mb-3">
                    <div class="text-center">
                        <h6>Descargo Aceptado</h6>
                        <p>Aceptado el <?= $d['fecha_hora_respuesta']; ?> por <?= $d['nombre_user_rta'] . ' ' . $d['apellido_user_rta'] ?></p>
                    </div>
                    <div>
                        <span><em>Descripción de la Respuesta:</em></span>
                    </div>
                    <br>
                    <div>
                        <p>' <?= $d['respuesta']; ?> '</p>
                    </div>
                </div>
            <?php } else { ?> <!-- Rechazó -->

                <div class="div_rechazado mt-3 mb-3">
                    <div class="text-center">
                        <h6>Descargo Rechazado</h6>
                        <p>Rechazado el <?= $d['fecha_hora_respuesta']; ?> por <?= $d['nombre_user_rta'] . ' ' . $d['apellido_user_rta'] ?></p>

                    </div>
                    <div>
                        <span><em>Descripción de la Respuesta:</em></span>
                    </div>
                    <br>
                    <div>
                        <p>' <?= $d['respuesta']; ?> '</p>
                    </div>
                </div>

            <?php }  ?>

        <?php }  ?>
    <?php endforeach; ?>
    <?php if (is_null($tarjeta['cierre'])) : ?>
        <?php if ($usuario_carga != session()->get('id_usuario')) : ?>
            <div class="row" id="btns_descargos">
                <div class="col-xs-12 col-md-1"></div>
                <div class="col-xs-12 col-md-11">
                    <div class="mt-3">
                        <button class="btn_modify" id="add_descargo" data-bs-toggle="modal" data-bs-target="#modal_add_descargo">Agregar Nuevo Descargo</button>
                    </div>
                </div>
            </div>
        <?php endif; ?>
    <?php endif; ?>
</div>

<!-- == Motivo si se acepta/rechaza un descargo == -->
<br>
<div id="container_motivo" style="display: none;">

    <form id="form_motivo_descargo" method="POST">
        <div class="row">
            <div class="col-xs-12 col-md-1"></div>
            <div class="col-xs-12 col-md-10" style="width: 80%;">
                <label class="mb-1 fw-semibold sz_inp" id="label_motivo"></label>
                <input type="hidden" name="tipo_rta_descargo" id="tipo_rta_descargo">
                <input type="hidden" name="inp_id_descargo" id="inp_id_descargo">
                <textarea class="form-control sz_inp" name="rta_descargo" id="motivo_descargo" cols="30" rows="3" placeholder="Ingrese el motivo.." required></textarea>
            </div>
            <div class="col-xs-12 col-md-1"></div>
        </div>
        <br>
        <div class="row">
            <div class="col-md-9"></div>
            <div class="col-xs-12 col-md-3">
                <button class="btn_mod_success" id="aceptar_motivo_descargo">Aceptar</button>
                <button class="btn_mod_danger" id="cancelar_motivo_descargo">Cancelar</button>
            </div>
        </div>
    </form>

</div>

<script src="<?= base_url() ?>/assets/js/helpers.js"></script>
<script>
    let fecha_formateada_respuesta = new Date("<?php echo $d['fecha_hora_respuesta']; ?>");
    fecha_formateada_respuesta = formatearFecha(fecha_formateada_respuesta);
</script>