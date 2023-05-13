<link href="<?= base_url() ?>/assets/css/tarjetaObs/view_obs.css" rel="stylesheet">

<!-- == Descargo (Si es que existe) perteneciente a la observación == -->

<?php
$d = $descargos;
$usuario_carga = $tarjeta['usuario_carga'];
?>

<div>
    <?php $i = 0;
    foreach ($descargos as $d) : $i++; ?>
        <div class="card" style="width: 90%; margin: 0 0 0 auto;">
            <div class="card-header d-flex justify-content-between" style="color: #06608b; background-color: lightblue;">
                <div>
                    <p class="m-0 p-o">
                        Respuesta #<?= $i ?>
                    </p>
                </div>
                <div>
                    <p class="m-0 p-o">
                        <?= $d['fecha_hora_motivo'] ?></em> - <?= $d['nombre'] . ' ' . $d['apellido'] ?>
                    </p>
                </div>
            </div>
            <div class="card-body">
                <div class="col-xs-12 col-md-12">
                    <fieldset>
                        <legend>
                            Fundamento de la Respuesta
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
        <br>
        <!-- == Botones de Acciones == -->

        <!-- <!?php if (!is_null($tarjeta['cierre'])) : ?> -->
        <?php if (is_null($d['respuesta'])) { ?>
            <!-- Si el usuario es el mismo que cargó la tarjeta y aún la tarjeta no está cerrada visualiza los botones para aceptar/rechazar los descargos -->
            <?php if ($tarjeta['usuario_carga'] == session()->get('id_usuario') && is_null($tarjeta['cierre'])) : ?>
                <div class="row btns_descargos mb-2" id="btns_descargos" style="margin-left: 44px;">
                    <div style="margin: 15px 0 0 71px;">
                        <button class="btn_mod_success aceptar_descargo" id="aceptar_descargo_<?= $d['id'] ?>" data-id='<?= $d['id'] ?>'>Aceptar Descargo</button>
                        <button class="btn_mod_danger rechazar_descargo" id="rechazar_descargo_<?= $d['id'] ?>" data-id='<?= $d['id'] ?>'>Rechazar Descargo</button>
                    </div>
                </div>
                <!-- == Motivo si se acepta/rechaza un descargo == -->
                <div class="container_motivo" style="display: none;">

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
        <?php } else { ?>
            <!-- == Esto ya es el motivo, si es que ya se aceptó/rechazó el descargo == -->
            <br>
            <?php if ($d['estado'] == 1) { ?> <!-- Aceptó -->

                <div class="card" style="width: 80%; margin: 0 0 0 auto;">
                    <div class="card-header flex-column text-center" style="color: #3ea746; background-color: #a9ffc745;">

                        <div>
                            <p class="m-0 p-o fw-semibold">
                                Descargo Aceptado
                            </p>
                        </div>
                        <div>
                            <p class="m-0 p-o">
                                Aceptado el <?= $d['fecha_hora_respuesta']; ?> por <?= $d['nombre_user_rta'] . ' ' . $d['apellido_user_rta'] ?>
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
                                    <?= $d['respuesta']; ?>
                                </div>
                            </fieldset>
                        </div>
                    </div>
                </div>
                <br>
            <?php } else { ?> <!-- Rechazó -->
                <div class="card" style="width: 80%; margin: 0 0 0 auto;">

                    <div class="card-header flex-column text-center" style="color: #b14040; background-color: #ffa9a914;">

                        <div>
                            <p class="m-0 p-o fw-semibold">
                                Descargo Rechazado
                            </p>
                        </div>
                        <div>
                            <p class="m-0 p-o">
                                Rechazado el <?= $d['fecha_hora_respuesta']; ?> por <?= $d['nombre_user_rta'] . ' ' . $d['apellido_user_rta'] ?>
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
                                    <?= $d['respuesta']; ?>
                                </div>
                            </fieldset>
                        </div>
                    </div>
                </div>
                <br>

            <?php }  ?>
        <?php }  ?>
    <?php endforeach; ?>

</div>

<script src="<?= base_url() ?>/assets/js/helpers.js"></script>
<script>
    let fecha_formateada_respuesta = new Date("<?php echo $d['fecha_hora_respuesta']; ?>");
    fecha_formateada_respuesta = formatearFecha(fecha_formateada_respuesta);
</script>