<link href="<?= base_url() ?>/assets/css/tarjetaObs/drawAndDrop.css" rel="stylesheet">
<link href="<?= base_url() ?>/assets/css/tarjetaObs/add.css" rel="stylesheet">
<link href="<?= base_url() ?>/assets/css/tarjetaObs/view_obs.css" rel="stylesheet">
<link href="<?= base_url() ?>/assets/css/addFiles.css" rel="stylesheet">
<link href="<?= base_url() ?>/assets/css/fileAdder.css" rel="stylesheet">

<?php
$t = $tarjeta;
$situacion = $t['situacion'] == 1 ? 'Abierta' : 'Cerrada';
?>

<title>OLDELVAL | Tarjeta #<?= $t['id_tarjeta'] ?></title>

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
                    <p class="subtitle">Contratista</p>
                    <p class="content_text"><?= $t['contratista'] ?></p>
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

            <div style="width: 90%; margin: 0 auto;">

                <?php $i = 1;
                foreach ($indicadores as $ind) : ?>
                    <div>
                        <div class="row text-center p-2 mt-2 align-items-center">
                            <div class="col-xs-12 col-md-7" style="text-align: start;">
                                <div>
                                    <p class="m-0"><small><em><b>(<?= $i; ?>)</b></em></small> <b><?= $ind['nombre']; ?></b> <?= $ind['descripcion']; ?></p>
                                </div>
                            </div>
                            <div class="col-xs-12 col-md-3 text-center">
                                <div class="btn-group btn-group-toggle" role="group" aria-label="Basic radio toggle button group">
                                    <input type="radio" class="btn-check verde_check" value="1" autocomplete="off" <?= ($ind['rta'] == '1') ? 'checked' : ''; ?>>
                                    <label class="btn verde <?= ($ind['rta'] != '1') ? 'simulate_dis' : ''; ?>" style="pointer-events: none;">Bien</label>

                                    <input type="radio" class="btn-check rojo_check" value="0" autocomplete="off" <?= ($ind['rta'] == '0') ? 'checked' : ''; ?>>
                                    <label class="btn rojo <?= ($ind['rta'] != '0') ? 'simulate_dis' : ''; ?>" style="pointer-events: none;">Mal</label>

                                    <input type="radio" class="btn-check amarillo_checked" value="-1" autocomplete="off" <?= ($ind['rta'] == '-1') ? 'checked' : ''; ?>>
                                    <label class="btn amarillo <?= ($ind['rta'] != '-1') ? 'simulate_dis' : ''; ?>" style="pointer-events: none;">N/A</label>
                                </div>
                            </div>
                            <div class="col-xs-12 col-md-2" style="text-align: end!important;">
                                <?php if ($ind['comentario']) : ?>
                                    <div class="div-ind_icono">
                                        <small data-bs-toggle="collapse" data-bs-target="#collapse_guia<?= $i ?>" aria-expanded="false" aria-controls="collapse_guia<?= $i ?>">
                                            Comentario
                                            <i class="fas fa-comment"></i>
                                        </small>
                                    </div>
                                <?php endif; ?>
                            </div>
                        </div>
                        <?php if ($ind['comentario']) : ?>
                            <div class="collapse" id="collapse_guia<?= $i ?>">
                                <div class="mt-2" style="width: 90%; margin: 0 auto;">
                                    <p class="m-0 p-0 text-center fw-semibold">Comentario</p>
                                    <textarea class="form-control sz_inp" rows="3" style="border: 1px solid #f1f1f1;" readonly><?= $ind['comentario']; ?></textarea>
                                </div>
                            </div>
                        <?php endif; ?>
                    </div>
                <?php $i++;
                endforeach; ?>
            </div>
        </div>
    </div>

    <!-- Observaciones tanto positivas como de mejora -->
    <div class="card" style="border: 1px solid #f6f6f6;box-shadow: 0px 0 30px rgb(179 179 179 / 53%);">
        <div class="card-header subtitle" style="font-weight: 600; letter-spacing: 1.5px; text-align: center;">
            Observaciones
        </div>

        <?php foreach ($t['hallazgos'] as $h) : ?>
            <?php if ($h['id_tipo'] == 2) : ?>
                <!-- Observación de Mejora -->
                <?php $data['h'] = $h ?>
                <?= view('tarjetas_obs/obs_mejora', $data); ?> <!-- obs_mejora.php -->
            <?php else : ?>
                <!-- Observación Positiva -->
                <?php $data['h'] = $h ?>
                <?= view('tarjetas_obs/obs_positiva', $data); ?> <!-- obs_positiva.php -->
            <?php endif; ?>
        <?php endforeach; ?>
    </div>
    <!-- Finalizar el cierre de la Tarjeta M.A.S una vez que todos los hallazgos estén resueltos -->
    <?php if ($t['situacion'] == 0) : ?>
        <div class="card">
            <div class="card-header" style="font-weight: 600; letter-spacing: 1.5px; text-align: center; background-color: #d7d7d7;">
                Tarjeta M.A.S Cerrada
            </div>
            <div class="card-body">
                <div class="text-center">
                    <span class="text-center">Cerrada el día <?= $t['cierre']['fecha_cierre_format'] ?> por <?= $t['cierre']['responsable_cierre'] ?></span>
                </div>

                <div class="col-xs-12 col-md-12">
                    <fieldset>
                        <legend>
                            Fundamento del Cierre
                        </legend>
                        <div class="row" style="padding: 10px 50px;">
                            <?= $t['cierre']['motivo'] ?>
                        </div>
                    </fieldset>
                </div>
            </div>
        </div>
    <?php else : ?>
        <?php if ($t['hallazgos_pendientes']['cantidad_sin_resolver'] == 0) : ?>
            <div class="row">
                <div class="col-xs-12 col-md-4"></div>
                <div class="col-xs-12 col-md-4 d-flex align-items-center">
                    <button class="w-100 d-flex justify-content-center align-items-center btn_cierre" data-bs-toggle="modal" data-bs-target="#modal_cierre_obs">
                        <i class="fa-solid fa-check me-2"></i> Finalizar Cierre de Tarjeta
                    </button>
                </div>
                <div class="col-xs-12 col-md-4"></div>
            </div>
        <?php endif; ?>
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
                                <input type="hidden" name="id_hallazgo" id="id_hallazgo_descargo">
                            </div>
                            <div class="col-xs-12 col-md-12 mt-3">
                                <div id="gallery_descargos" class="adj_gallery" style="margin-left: 10px;"></div>
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

    <div class="d-flex justify-content-end">
        <a href="<?= base_url('TarjetaObs'); ?>" class="btn_modify btn_volver">Volver</a>
    </div>
</div>

<script>
    // * Al momento de agregar un descargo, seteo el id del hallazgo en el formulario del modal
    // * Así puedo tener una referencia a que hallazgo le agrego el descargo
    const btnAddDescargos = document.querySelectorAll('.add_descargo');
    for (let i = 0; i < btnAddDescargos.length; i++) {
        btnAddDescargos[i].addEventListener('click', e => {
            e.preventDefault();
            let inpIdHallazgo = document.getElementById('id_hallazgo_descargo');
            inpIdHallazgo.value = (e.target).getAttribute('data-id-hallazgo');
        })
    }
</script>

<script src="<?= base_url() ?>/assets/js/tarjetaObs/view_obs/descargo.js"></script>
<script src="<?= base_url() ?>/assets/js/addFiles.js"></script>
<script>
    new addFiles(document.getElementById("gallery_descargos"), 'adj_descargo').init();
</script>