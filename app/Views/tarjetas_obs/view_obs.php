<link href="<?= base_url() ?>/assets/css/tarjetaObs/drawAndDrop.css" rel="stylesheet">
<link href="<?= base_url() ?>/assets/css/tarjetaObs/add.css" rel="stylesheet">
<link href="<?= base_url() ?>/assets/css/tarjetaObs/view_obs.css" rel="stylesheet">

<?php
$t = $tarjeta;
$descargos['descargos'] = $tarjeta['hallazgo']['descargos'];
$situacion = $t['situacion'] == 1 ? 'Abierta' : 'Cerrada';

if (!is_null($t['cierre'])) {
    $fecha1 = new DateTime($t['hallazgo']['fecha_cierre']);
    $fecha2 = new DateTime($t['cierre']['fecha_hora_cierre']);
    $fecha1_tiempo = strtotime($t['hallazgo']['fecha_cierre']);
    $fecha2_tiempo = strtotime($t['cierre']['fecha_hora_cierre']);

    if ($fecha1_tiempo > $fecha2_tiempo) {
        $rta_cierre_obs = true;
        $diff = $fecha1->diff($fecha2);
    } else {
        $rta_cierre_obs = false;
        $diff = $fecha1->diff($fecha2);
    }
}

?>

<div class="container ">
    <div class="card" style="border: 1px solid #f6f6f6; box-shadow: 0px 0 30px rgb(1 41 112 / 5%);">
        <div class="card-header" style="background: white; padding: 16px; font-weight: 600; letter-spacing: 1.5px;">
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
                        <p class="content_text"><?= isset($t['estacion']) ? $t['estacion'] : 'No se especificó' ?></p>
                    </div>
                    <div class="col-xs-12 col-md-6 mt-3">
                        <p class="subtitle">Sistema de Oleoducto</p>
                        <p class="content_text"><?= isset($t['sistema']) ? $t['sistema'] : 'No se especificó' ?></p>
                    </div>
                </div>
            </div>

            <br>

            <p class="subtitle">Indicadores</p>

            <div class="row text-center">
                <?php if (count($indicadores) > 0) { ?>
                    <?php foreach ($indicadores as $indicador) : ?>
                        <div class="col-xs-12 col-md-3 p-4">
                            <p><i class="far fa-check-square" style="margin-right: 3px;"></i><?= $indicador['nombre'] ?></p>
                        </div>
                    <?php endforeach; ?>
                <?php } else { ?>
                    <div class="col-xs-12 col-md-12">
                        <p class="text-center"><em>No se han seleccionado indicadores</em></p>
                    </div>
                <?php }  ?>
            </div>

            <br>

            <!-- == Plan de Acción == -->
            <div class="row">
                <h3 class="text-center observacion">Observación</h3>
            </div>

            <?php if (!empty($t['hallazgo'])) : ?>
                <?php if ($t['observacion'] == 2) { ?>
                    <!-- == Si es una observación con posibilidad de mejora, se visualiza == -->
                    <?= view('tarjetas_obs/view_obs/obs_mejora', $tarjeta) ?> <!-- obs_mejora.php -->
                <?php } else { ?>
                    <!-- == Si es una observación con posibilidad positiva, se visualiza == -->
                    <?= view('tarjetas_obs/view_obs/obs_positiva') ?>
                <?php }  ?>
            <?php endif; ?>

            <!-- == Botón para agregar descargo (Abre un Modal) == -->
            <?php if (empty($t['hallazgo']['descargos'])) {

                if ($t['situacion'] != 0) { ?>
                    <?php if ($t['usuario_carga'] != session()->get('id_usuario') && $t['hallazgo']['responsable'] == session()->get('id_usuario')) : ?>
                        <div class="row" id="btns_descargos">
                            <div style="margin: 15px 0 0 71px;">
                                <button class="btn_modify" id="add_descargo" data-bs-toggle="modal" data-bs-target="#modal_add_descargo">Agregar Descargo</button>
                            </div>
                        </div>
                    <?php endif; ?>

                <?php } ?>

            <?php } else { ?>
                <!-- == Si es que ya existe una respuesta a esta observación, se visualiza esta vista == -->
                <?= view('tarjetas_obs/view_obs/descargo_obs', $descargos) ?> <!-- descargo_obs.php -->
            <?php }  ?>

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
                                        <input type="hidden" name="id_hallazgo" id="id_hallazgo_descargo" value="<?= $t['hallazgo']['id'] ?>">
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
                <div>
                    <div class="col-xs-12 col-md-12">
                        <h5 class="text-center">Tarjeta de Observación Cerrada (<?= $rta_cierre_obs ? 'A tiempo' : 'Fuera de Tiempo' ?>)</h5>
                        <div class="text-center">
                            <span class="text-center">Cerrada el día <?= $tarjeta['cierre']['fecha_hora_cierre'] ?> por <?= $tarjeta['cierre']['responsable_nombre'] . ' ' . $tarjeta['cierre']['responsable_apellido'] ?> (<?= $diff->days ?> <?= $rta_cierre_obs ? 'días antes' : 'días atrasados' ?>)</span>
                        </div>

                        <!-- <br> -->
                        <!-- <div class="text-center">
                        <ul>
                            <li>¿Se solicitó diás de extensión?</li>
                        </ul>
                    </div> -->

                        <br>

                        <div class="text-center">
                            <p><em>Fundamento</em></p>
                            <div style="background-color: lightblue; width: 60%; margin: 0 auto; border-radius: 10px; padding: 10px;">
                                <p class="m-0">
                                    <?= $tarjeta['cierre']['motivo'] ?>
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            <?php } else { ?>
                <?php if ($tarjeta['situacion'] != 0 && $tarjeta['usuario_carga'] == session()->get('id_usuario')) : ?>
                    <div>
                        <button class="btn_modify" id="btn_cerrar_obs_completo">Cerrar Observación</button>
                    </div>
                <?php endif; ?>
            <?php }  ?>

        </div>

    </div>

    <div class="d-flex justify-content-end">
        <a href="<?= base_url('TarjetaObs'); ?>" class="btn_modify btn_volver" >Volver</a>
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
                    modal_cierre_obs.show();
                }
            })
        }

    });
</script>

<script>
    function prevenirDefault(event) {
        event.preventDefault();
    }
    let riesgo = <?= json_encode($tarjeta['hallazgo']['riesgo']) ?>;
    let riesgo_fila = <?= json_encode($tarjeta['hallazgo']['riesgo_fila']) ?>;
</script>

<script src="<?= base_url() ?>/assets/js/tarjetaObs/view_obs/modal_riesgo_selected.js"></script>
<script src="<?= base_url() ?>/assets/js/tarjetaObs/fileDropAdder.js"></script>
<script src="<?= base_url() ?>/assets/js/tarjetaObs/view_obs/descargo.js"></script>

<script>
    let arrayImgs = [];
    let container_adj = document.getElementById('container_adj');
    new fileDropAdder(container_adj, 'prueba', 'gallery').init();
</script>