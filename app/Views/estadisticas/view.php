<link href="<?= base_url() ?>/assets/css/estadisticas/add/add.css" rel="stylesheet">
<link href="<?= base_url() ?>/assets/css/estadisticas/add/view.css" rel="stylesheet">
<?php

switch ($estadistica[0]['tipo']) {
    case '1':
        $tipo_estadistica = 'Incidentes';
        break;
    case '2':
        $tipo_estadistica = 'Capacitaciones';
        break;
    case '3':
        $tipo_estadistica = 'Gestión Vehicular';
        break;
}

?>
<div class="container">
    <div class="row">
        <div class="col-md-12">
            <h5 class="subtitle-view">Estadistica <?= $tipo_estadistica ?> N°<?= $estadistica[0]['id_estadistica'] ?></h5>
        </div>
    </div>

    <p class="text-end"><span><em>Periodo: </em><?= $estadistica[0]['periodo'] ?></span></p>
    <div class="card" style="border: 1px solid #f6f6f6; box-shadow: 0px 0 30px rgb(1 41 112 / 5%);">
        <div class="card-body">
            <?= view('estadisticas/view_encabezado') ?>
        </div>

        <div class="row data_est">
            <p>Datos de la Estadística</p>
        </div>

        <div class="indicadores_valor p-3">
            <div>
                Indicador
            </div>
            <div style="text-align: right;">
                Valor
            </div>
        </div>

        <?php foreach ($estadistica[0]['indicadores'] as $ind) : ?>
            <div class="row" style="width: 95%; margin: 0 auto;">
                <div class="div_indicador_view">
                    <div class="div-personal_ind">
                        <div class="text-start">
                            <p class="name_indicador"><?= $ind['nombre'] ?></p>
                        </div>
                        <div class="div-ind_icono">
                            <?php if ($ind['nota'] != '') : ?>
                                <small data-bs-toggle="collapse" data-bs-target="#collapse_ind_gral_<?= $ind['id_indicador'] ?>" aria-expanded="false" aria-controls="collapse_ind_gral_1">
                                    <i class="fas fa-comment"></i>
                                </small>
                            <?php endif; ?>
                            <input type="hidden" value="1" name="indicador_gral[<?= $ind['id_indicador'] ?>][id_indicador]">
                            <input type="number" value="<?= $ind['valor'] ?>" min="0" name="indicador_gral[<?= $ind['id_indicador'] ?>][valor]" id="indicador_gral_<?= $ind['id_indicador'] ?>" class="form-control sz_inp text-center ind-just_read" style="font-size: 12.5px!important;" readonly>
                        </div>
                    </div>
                    <div class="collapse" id="collapse_ind_gral_<?= $ind['id_indicador'] ?>">
                        <div class="form-floating">
                            <textarea class="form-control" id="textarea" name="indicador_gral[<?= $ind['id_indicador'] ?>][nota]" rows="2"></textarea>
                            <label class="mb-1 fw-semibold" for="textarea">Nota:</label>
                        </div>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>

        <?php foreach ($estadistica[0]['titulos'] as $ind) : ?>
            <div class="title_indicador">
                <h6><?= $ind['nombre'] ?></h6>
            </div>

            <?php if (isset($ind['subtitulos'])) : ?>
                <?php foreach ($ind['subtitulos'] as $subt) : ?>
                    <div class="row mt-3 contenedor-ind_subt">
                        <div class="col-xs-12 text-start">
                            <h6 class="subt_view"><?= $subt['nombre'] ?></h6>
                        </div>
                        <?php if (isset($subt['indicadores'])) : ?>
                            <div class="row" style="width: 95%; margin: 10px auto;">
                                <?php foreach ($subt['indicadores'] as $ind_subt) : ?>
                                    <div class="div_indicador_view">

                                        <div class="div-personal_ind">
                                            <div class="d-flex align-items-center">
                                                <div class="dot"></div>
                                                <div class="text-start">
                                                    <p class="name_indicador"><?= $ind_subt['nombre'] ?></p>
                                                </div>
                                            </div>
                                            <div class="div-ind_icono">
                                                <?php if ($ind_subt['nota'] != '') : ?>
                                                    <small data-bs-toggle="collapse" data-bs-target="#collapse_indSubt<?= $ind_subt['id_indicador'] ?>" aria-expanded="false" aria-controls="collapse_indSubt<?= $ind_subt['id_indicador'] ?>">
                                                        <i class="fas fa-comment"></i>
                                                    </small>
                                                <?php endif; ?>
                                                <input type="hidden" value="1" name="indicador_subt[<?= $ind_subt['id_indicador'] ?>][id_titulo]">
                                                <input type="hidden" value="1" name="indicador_subt[<?= $ind_subt['id_indicador'] ?>][id_subtitulo]">
                                                <input type="hidden" value="1" name="indicador_subt[<?= $ind_subt['id_indicador'] ?>][id_indicador]">
                                                <input type="number" value="<?= $ind_subt['valor'] ?>" min="0" name="indicador_subt[<?= $ind_subt['id_indicador'] ?>][valor]" id="indicador_<?= $ind_subt['id_indicador'] ?>" data-id-subt="<?= $subt['id'] ?>" class="form-control ind-just_read sz_inp text-center" style="font-size: 12.5px!important;" readonly>
                                            </div>
                                        </div>
                                        <div class="collapse" id="collapse_indSubt<?= $ind_subt['id_indicador'] ?>">
                                            <div class="form-floating" style="font-size: 12.5px!important;">
                                                <textarea style="font-size: 12.5px!important;" class="form-control ind-just_read" name="indicador_subt[<?= $ind_subt['id_indicador'] ?>][nota]" id="textarea" rows="2" readonly><?= $ind_subt['nota'] ?></textarea>
                                                <label class="mb-1 fw-semibold" for="textarea">Nota:</label>
                                            </div>
                                        </div>
                                    </div>
                                <?php endforeach; ?>
                            </div>
                        <?php endif; ?>
                    </div>
                <?php endforeach; ?>

            <?php endif; ?>

            <?php if (isset($ind['indicadores'])) : ?>
                <?php foreach ($ind['indicadores'] as $ind_title) : ?>
                    <?php
                    $estilo = '';
                    $txt = '';

                    if ($ind_title['id_indicador'] == 31) {
                        $estilo = 'border-left: 6px solid #7cc37c;border-radius: 3px; margin-bottom: 5px;';
                        $txt = '<small><em> (Bien)</em></small>';
                    } else if ($ind_title['id_indicador'] == 32) {
                        $estilo = 'border-left: 6px solid #f9d76a;border-radius: 3px; margin-bottom: 5px;';
                        $txt = '<small><em> (Regular)</em></small>';
                    } else if ($ind_title['id_indicador'] == 33) {
                        $estilo = 'border-left: 6px solid #bf6262;border-radius: 3px; margin-bottom: 5px;';
                        $txt = '<small><em> (Mal)</em></small>';
                    }
                    ?>
                    <div class="row" style="width: 95%; margin: 0 auto;">
                        <div class="div_indicador_view" style="<?= $estilo ?>">
                            <div class="div-personal_ind">
                                <div class="text-start">
                                    <p class="name_indicador"><?= $ind_title['nombre'] . $txt ?></p>
                                </div>
                                <div class="div-ind_icono">
                                    <?php if ($ind_title['nota'] != '') : ?>
                                        <small data-bs-toggle="collapse" data-bs-target="#collapse_ind_gral_<?= $ind_title['id_indicador'] ?>" aria-expanded="false" aria-controls="collapse_ind_gral_<?= $ind_title['id_indicador'] ?>">
                                            <i class="fas fa-comment"></i>
                                        </small>
                                    <?php endif; ?>
                                    <input type="hidden" value="1" name="indicador_gral[<?= $ind_title['id_indicador'] ?>][id_indicador]">
                                    <input type="number" value="<?= $ind_title['valor'] ?>" min="0" name="indicador_gral[<?= $ind_title['id_indicador'] ?>][valor]" id="indicador_gral_<?= $ind_title['id_indicador'] ?>" class="form-control sz_inp text-center ind-just_read" style="font-size: 12.5px!important;" readonly>
                                </div>
                            </div>
                            <div class="collapse" id="collapse_ind_gral_<?= $ind_title['id_indicador'] ?>">
                                <div class="form-floating" style="font-size: 12.5px!important;">
                                    <textarea style="font-size: 12.5px!important;" class="form-control ind-just_read" id="textarea" name="indicador_gral[<?= $ind_title['id_indicador'] ?>][nota]" rows="2"><?= $ind_title['nota'] ?></textarea>
                                    <label class="mb-1 fw-semibold" for="textarea">Nota:</label>
                                </div>
                            </div>
                        </div>
                    </div>

                <?php endforeach; ?>
            <?php endif; ?>

        <?php endforeach; ?>

        <br>

        <div class="row data_est">
            <p>KPIs del Mes</p>
        </div>

        <?php if (isset($estadistica['indices'])) : ?>
            <?php foreach ($estadistica['indices'] as $indice) : ?>
                <div class="row" style="width: 95%; margin: 10px auto;">
                    <div class="div_indicador_view">

                        <div class="div-personal_ind">
                            <div class="d-flex align-items-center">
                                <div class="dot"></div>
                                <div class="text-start">
                                    <p class="name_indicador"><?= $indice['nombre'] ?></p>
                                </div>
                                <!-- <i class="fas fa-question answer" data-bs-toggle="modal" data-bs-target="#modal_answer"></i> -->
                            </div>
                            <div class="div-ind_icono">
                                <?php if ($indice['nota'] != '') : ?>
                                    <small data-bs-toggle="collapse" data-bs-target="#collapse_indSubt<?= $indice['id_indicador'] ?>" aria-expanded="false" aria-controls="collapse_indSubt<?= $indice['id_indicador'] ?>">
                                        <i class="fas fa-comment"></i>
                                    </small>
                                <?php endif; ?>
                                <input type="number" value="<?= $indice['valor'] ?>" min="0" name="indicador_subt[<?= $indice['id_indicador'] ?>][valor]" id="indicador_<?= $indice['id_indicador'] ?>" data-id-subt="1" class="form-control ind-just_read sz_inp text-center" style="font-size: 12.5px!important;" readonly>
                            </div>
                        </div>
                        <div class="collapse" id="collapse_indSubt<?= $indice['id_indicador'] ?>">
                            <div class="form-floating" style="font-size: 12.5px!important;">
                                <textarea style="font-size: 12.5px!important;" class="form-control" name="indicador_subt[<?= $indice['id_indicador'] ?>][nota]" id="textarea" rows="2"><?= $indice['nota'] ?></textarea>
                                <label class="mb-1 fw-semibold" for="textarea">Nota:</label>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php endif; ?>


        <!-- Muestro los adjuntos en caso de existir (Solamente para las capacitaciones) -->
        <?php if (isset($estadistica['adjuntos'])) { ?>
            <br>

            <div class="row data_est">
                <p>Adjuntos</p>
            </div>
            <?php if ($estadistica['adjuntos']) { ?>
                <?php foreach ($estadistica['adjuntos'] as $adj) : ?>
                    <section style="width: 90%; margin: 0 auto;">
                        <div class="row justify-content-center" id="container_uploads">
                            <div class="row align-items-center mb-3" style="box-shadow: 0px 0px 6px 0px rgb(239 236 236); border-radius: 10px;">
                                <div class="d-flex col-xs-12 col-md-10 justify-content-between">
                                    <div class="d-flex justify-content-evenly align-items-center">
                                        <?= generarIconUpload($adj['adjunto']); ?>
                                    </div>
                                    <textarea cols="30" rows="2" class="form-control sz_inp" style="width: 70%;border: 1px solid #f3f3f3;" readonly><?= !empty($adj['descripcion']) ? $adj['descripcion'] : 'No se cargó una descripción' ?></textarea>
                                </div>
                                <div class="col-xs-12 col-md-2 text-center" title="Descargar adjunto">
                                    <a class="btn-del_inc" href="<?= base_url("uploads/estadisticas/" . $adj['adjunto']) ?>" download><i class="fa-solid fa-download" style="font-size: 20px!important;"></i></a>
                                </div>
                            </div>
                        </div>
                    </section>
                <?php endforeach; ?>
            <?php } else { ?>
                <div class="row">
                    <p class="text-center m-0"><em>No se han cargado adjuntos</em></p>
                </div>
            <?php } ?>

        <?php }  ?>

        <!-- Modal -->
        <div class="modal fade" id="modal_answer" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h6 style="margin: 0;"><i class="fas fa-info-circle"></i> Fórmula Utilizada</h6>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <h6 class="text-center">Indice de Incidencia de Accidentes Personales (II AP)</h6>
                        <br>
                        <p class="m-0 text-center" style="font-size: 12px; font-weight: bold;">(Trabajadores accidentados x 1000) / trabajadores expuestos</p>
                        <br>
                        <p class="m-0 text-center" style="font-size: 12px;">Expresa la cantidad de trabajadores afectados por accidentes in-itinere, lumbalgias, accidentes operativos con y sin días perdidos y accidentes no operativos, en un período de un año, por cada mil trabajadores expuestos.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="d-flex justify-content-end">
    <button class="btn_modify" onclick="window.location.href = `${GET_BASE_URL()}/estadisticas`">Volver</button>
</div>
</div>