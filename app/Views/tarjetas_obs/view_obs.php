<link href="<?= base_url() ?>/assets/css/tarjetaObs/drawAndDrop.css" rel="stylesheet">
<link href="<?= base_url() ?>/assets/css/tarjetaObs/add.css" rel="stylesheet">
<link href="<?= base_url() ?>/assets/css/tarjetaObs/view_obs.css" rel="stylesheet">

<?php

$t = $tarjeta;

$descargos['descargos'] = isset($tarjeta['hallazgo']['descargos']) ? $tarjeta['hallazgo']['descargos'] : [];
$situacion = $t['situacion'] == 1 ? 'Abierta' : 'Cerrada';

if (!is_null($t['cierre'])) {
    $fecha1 = isset($t['hallazgo']['fecha_cierre']) ? new DateTime($t['hallazgo']['fecha_cierre']) : new DateTime($t['cierre']['fecha_hora_cierre']);
    $fecha2 = new DateTime($t['cierre']['fecha_hora_cierre']);
    $fecha1_tiempo = isset($t['hallazgo']['fecha_cierre']) ? strtotime($t['hallazgo']['fecha_cierre']) : strtotime($t['cierre']['fecha_hora_cierre']);
    $fecha2_tiempo = strtotime($t['cierre']['fecha_hora_cierre']);

    if ($fecha1_tiempo > $fecha2_tiempo) {
        $rta_cierre_obs = true;
        $diff = $fecha1->diff($fecha2);
    } else if ($fecha1_tiempo < $fecha2_tiempo) {
        $rta_cierre_obs = false;
        $diff = $fecha1->diff($fecha2);
    } else if ($fecha1_tiempo == $fecha2_tiempo) {
        $rta_cierre_obs = true;
        $diff = $fecha1->diff($fecha2);
    }
}

?>

<div class="container">
    <div class="card" style="border: 1px solid #f6f6f6;box-shadow: 0px 0 30px rgb(179 179 179 / 53%);">
        <div class="card-header" style="background: white; padding: 16px; font-weight: 600; letter-spacing: 1.5px; text-align: center;">
            Tarjeta de Observación N°<?= $t['id_tarjeta'] ?> - <?= $situacion ?>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-xs-12 col-md-4">
                    <p class="subtitle">Observador</p>
                    <p class="content_text"><?= $t['observador']; ?></p>
                </div>
                <div class="col-xs-12 col-md-4">
                    <p class="subtitle">Fecha de Detección</p>
                    <p class="content_text"><?= $t['fecha_deteccion']; ?></p>
                </div>
                <div class="col-xs-12 col-md-4">
                    <p class="subtitle">Tipo de Observación</p>
                    <p class="content_text"><?= $t['observacion'] == 2 ? 'Posibilidad de mejora' : 'Positiva'; ?></p>
                </div>
            </div>
            <br>
            <div class="row">
                <div class="col-xs-12">
                    <p class="subtitle">Descripción Corta / Titulo</p>
                    <p class="content_text"><?= $t['tar_descripcion'] ?></p>
                </div>
            </div>
            <br>
            <div class="row">
                <div class="row">
                    <div class="col-xs-12 col-md-6">
                        <p class="subtitle">Proyecto</p>
                        <p class="content_text"><?= $t['proyecto'] ?></p>
                    </div>
                    <div class="col-xs-12 col-md-6">
                        <p class="subtitle">Módulo</p>
                        <p class="content_text"><?= $t['modulo'] ? $t['modulo'] : 'No Aplica' ?></p>
                    </div>
                    <div class="col-xs-12 col-md-6 mt-3">
                        <p class="subtitle">Estación de Bombeo</p>
                        <p class="content_text"><?= isset($t['estacion']) ? $t['estacion'] : 'No Aplica' ?></p>
                    </div>
                    <div class="col-xs-12 col-md-6 mt-3">
                        <p class="subtitle">Sistema de Oleoducto</p>
                        <p class="content_text"><?= isset($t['sistema']) ? $t['sistema'] : 'No Aplica' ?></p>
                    </div>
                </div>
            </div>

            <?php if ($t['observadores'] != NULL) : ?>
                <br>
                <p class="subtitle">Otros Observadores</p>

                <section>
                    <div class="d-flex justify-content-evenly">
                        <?php foreach ($t['observadores'] as $observador) : ?>
                            <div class="text-center mt-3" style="border-left: 2px solid lightgray; border-right: 2px solid lightgray;">
                                <p class="m-0" style="padding: 0 20px;"><?= $observador['observador']; ?></p>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </section>
            <?php endif; ?>
        </div>
    </div>

    <div class="card" style="border: 1px solid #f6f6f6;box-shadow: 0px 0 30px rgb(179 179 179 / 53%);">
        <div class="card-header subtitle" style="font-weight: 600; letter-spacing: 1.5px; text-align: center;">
            Guía de Detección
        </div>
        <div class="card-body">
            <?php $i = 1;
            foreach ($indicadores as $ind) : ?>
                <div class="d-flex justify-content-between align-items-center pb-2 pt-2" style="width: 90%;margin: 0 auto; border-bottom: 1px solid lightgray; font-size: 13px;">
                    <div>
                        <p class="m-0"><small><em><b>(<?= $i; ?>)</b></em></small> <?= $ind['nombre_indicador']; ?></p>
                    </div>
                    <div class="btn-group btn-group-toggle" role="group" aria-label="Basic radio toggle button group">
                        <input type="radio" class="btn-check verde_check" value="1" autocomplete="off" <?= ($ind['rta'] == '1') ? 'checked' : ''; ?>>
                        <label class="btn verde <?= ($ind['rta'] != '1') ? 'simulate_dis' : ''; ?>" style="pointer-events: none;">Bien</label>

                        <input type="radio" class="btn-check rojo_check" value="0" autocomplete="off" <?= ($ind['rta'] == '0') ? 'checked' : ''; ?>>
                        <label class="btn rojo <?= ($ind['rta'] != '0') ? 'simulate_dis' : ''; ?>" style="pointer-events: none;">Mal</label>

                        <input type="radio" class="btn-check amarillo_checked" value="-1" autocomplete="off" <?= ($ind['rta'] == '-1') ? 'checked' : ''; ?>>
                        <label class="btn amarillo <?= ($ind['rta'] != '-1') ? 'simulate_dis' : ''; ?>" style="pointer-events: none;">N/A</label>
                    </div>
                </div>
            <?php $i++;
            endforeach; ?>
        </div>
    </div>

    <?php if (isset($t['hallazgo'])): ?>
    <div class="card" style="border: 1px solid #f6f6f6;box-shadow: 0px 0 30px rgb(179 179 179 / 53%);">
        <div class="card-header subtitle" style="font-weight: 600; letter-spacing: 1.5px; text-align: center;">
            Observación
        </div>
        <div class="card-body">
            <?php if (!empty($t['hallazgo'])) : ?>
                <?php if ($t['observacion'] == 2) { ?>
                    <!-- == Si es una observación con posibilidad de mejora, se visualiza == -->
                    <?= view('tarjetas_obs/view_obs/obs_mejora', $tarjeta) ?> <!-- obs_mejora.php -->
                <?php } else { ?>
                    <!-- == Si es una observación con posibilidad positiva, se visualiza == -->
                    <?= view('tarjetas_obs/view_obs/obs_positiva') ?>
                <?php }  ?>
            <?php endif; ?>
        </div>
    </div>
    <?php endif; ?>

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
                                <textarea name="new_descargo" id="new_descargo" cols="30" rows="4" class="form-control sz_inp" placeholder="Ingrese la respuesta deseada"></textarea>
                                <input type="hidden" name="id_hallazgo" id="id_hallazgo_descargo" value="<?= isset($t['hallazgo']['id']) ? $t['hallazgo']['id'] : '' ?>">
                            </div>
                            <div class="col-xs-12 col-md-12 mt-3">
                                <div id="container_adj"></div>
                                <div id="gallery"></div>
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

    <!-- == Modal para cerrar observación == -->
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
                                <textarea name="motivo_cierre_obs" id="motivo_cierre_obs" cols="30" rows="4" class="form-control sz_inp" placeholder="Ingrese el motivo por el cual cierra la observación"></textarea>
                                <input type="hidden" name="descargos_id[]" id="descargos_id">
                                <input type="hidden" name="cierre_forzado" id="cierre_forzado" value="0">
                                <input type="hidden" name="id_hallazgo" value="<?= isset($t['hallazgo']['id']) ? $t['hallazgo']['id'] : '' ?>">
                                <input type="hidden" name="id_tarjeta_close" value="<?= $t['id_tarjeta'] ?>">
                            </div>

                        </div>

                    </div>
                    <div class="modal-footer">

                        <button type="button" class="btn_mod_close" data-bs-dismiss="modal">Cancelar</button>

                        <!-- == Con este btn se genera un envío de Ajax == -->
                        <!-- <button type="submit" class="btn_modify" id="btn_add_descargo">Enviar Respuesta</button> -->
                        <input type="submit" id="btn_cerrar_obs" class="btn_modify" value="Cerrar Observación">
                    </div>
                </form>
            </div>
        </div>
    </div>

    <br>
    <!-- == Cerrar la observación cuando las respuestas estén concluidas == -->
    <?php if (!is_null($tarjeta['cierre'])) { ?>
        <!-- == Fundamento de la observación si es que ya finalizó == -->

        <div class="card">
            <div class="card-header" style="font-weight: 600; letter-spacing: 1.5px; text-align: center; background-color: #d7d7d7;">
                Tarjeta de Observación Cerrada (<?= $rta_cierre_obs ? 'A tiempo' : 'Fuera de Tiempo' ?>)
            </div>
            <div class="card-body">
                <?php if ($tarjeta['cierre']['cierre_manual']) : ?>
                    <div class="text-center mt-2 mb-2">

                        <small><em>(Hubieron descargos sin responder)</em></small>
                    </div>
                <?php endif; ?>

                <div class="text-center">
                    <span class="text-center">Cerrada el día <?= $tarjeta['cierre']['fecha_hora_cierre'] ?> por <?= $tarjeta['cierre']['responsable_nombre'] . ' ' . $tarjeta['cierre']['responsable_apellido'] ?> (<?= $diff->days ?> <?= $rta_cierre_obs ? 'días antes' : 'días atrasados' ?>)</span>
                </div>

                <div class="col-xs-12 col-md-12">
                    <fieldset>
                        <legend>
                            Fundamento del Cierre
                        </legend>
                        <div class="row" style="padding: 10px 50px;">
                            <?= $tarjeta['cierre']['motivo'] ?>
                        </div>
                    </fieldset>
                </div>
            </div>
        </div>
    <?php } else { ?>
        <?php if ($tarjeta['situacion'] != 0 && $tarjeta['usuario_carga'] == session()->get('id_usuario')) : ?>
            <div>
                <button class="btn_modify" id="btn_cerrar_obs_completo">Cerrar Observación</button>
            </div>
        <?php endif; ?>
    <?php } ?>

    <div class="d-flex justify-content-end">
        <a href="<?= base_url('TarjetaObs'); ?>" class="btn_modify btn_volver">Volver</a>
    </div>
</div>

</div>


</div>
<script src="<?= base_url() ?>/assets/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>

<script>
    let modal_cierre_obs = new bootstrap.Modal('#modal_cierre_obs');
    let descargos = <?= json_encode($descargos['descargos']); ?>;
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
            modal_cierre_obs.show();
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
                    modal_cierre_obs.show();
                } else {
                    document.getElementById('cierre_forzado').value = 0;
                }
            })
        }

    });
</script>

<script>
    function prevenirDefault(event) {
        event.preventDefault();
    }
    // let riesgo = <!?= json_encode($tarjeta['hallazgo']['riesgo']) ?>;
    // let riesgo_fila = <!?= json_encode($tarjeta['hallazgo']['riesgo_fila']) ?>;
</script>

<script src="<?= base_url() ?>/assets/js/tarjetaObs/view_obs/modal_riesgo_selected.js"></script>
<script src="<?= base_url() ?>/assets/js/tarjetaObs/fileDropAdder.js"></script>
<script src="<?= base_url() ?>/assets/js/tarjetaObs/view_obs/descargo.js"></script>

<script>
    let arrayImgs = [];
    let container_adj = document.getElementById('container_adj');
    new fileDropAdder(container_adj, 'prueba', 'gallery').init();
</script>