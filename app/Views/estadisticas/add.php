<link href="<?= base_url() ?>/assets/css/estadisticas/add/add.css" rel="stylesheet">
<title>OLDELVAL - Estadísticas Incidentes</title>
<div class="container">
    <form action="" method="POST" id="form_submit">
        <div class="blister-title-container">
            <h4 class="blister-title">Estadísticas Incidentes</h4>
            <input type="hidden" name="tipo" id="tipo" value="<?= $planilla[0]['id'] ?>">
        </div>
        <div class="row d-flex text-end" style="width:100%;">
            <small style="color:lightgrey"><i>(*)Campo Obligatorio</i></small>
        </div>
        <div class="collapse show" id="collapseFormulario">
            <div class="card card_custom text-center">
                <div class="card-header card_header_custom text-center header-perfil">
                    <h6 style="margin: 0px;" id="titulo"><b>Formulario</b></h6>
                </div>
                <div class="card-body">
                    <?= view('estadisticas/view_add/encabezado') ?>
                </div>
            </div>

            <!-- === Acá Inicia la parte de los Indicadores === -->

            <div class="card card_custom">
                <div class="card-header card_header_custom text-center header-perfil">
                    <h6 style="margin: 0px;"><b>Carga de Datos</b></h6>
                </div>

                <div class="card-body">
                    <div class="justify-content-between d-flex" style="width: 90%; margin: 0 auto;">
                        <h6 class="px-3"><b>Indicadores</b></h6>
                        <h6 class="px-3"><b>Valores</b></h6>
                    </div>
                    <!-- <div id="indicadores-container">Seleccione un Tipo de Planilla</div> -->
                    <?php foreach ($planilla[0]['indicadores'] as $ind) : ?>
                        <div class="row" style="width: 90%;margin: 0 auto;">
                            <div class="div_indicador">
                                <div class="div-personal_ind">
                                    <div class="d-flex align-items-center">
                                        <div class="dot"></div>
                                        <div class="text-start">
                                            <p class="name_indicador inp_custom"><?= $ind['nombre'] ?></p>
                                        </div>
                                    </div>
                                    <div class="div-ind_icono">
                                        <small data-bs-toggle="collapse" data-bs-target="#collapse_ind_gral_<?= $ind['id'] ?>" aria-expanded="false" aria-controls="collapse_ind_gral_<?= $ind['id'] ?>">
                                            Nota
                                            <i class="fas fa-comment"></i>
                                        </small>
                                        <input type="hidden" value="<?= $ind['id'] ?>" name="indicador_gral[<?= $ind['id'] ?>][id_indicador]">
                                        <input type="number" value="0" min="0" name="indicador_gral[<?= $ind['id'] ?>][valor]" id="indicador_gral_<?= $ind['id'] ?>" class="form-control sz_inp text-center inp_custom <?= ($ind['class_indices'] != NULL) ? $ind['class_indices'] : ''; ?>" style="font-size: 12.5px!important;">
                                    </div>
                                </div>
                                <div class="collapse" id="collapse_ind_gral_<?= $ind['id'] ?>">
                                    <div class="form-floating">
                                        <textarea class="form-control inp_custom" id="textarea" name="indicador_gral[<?= $ind['id'] ?>][nota]" rows="2"></textarea>
                                        <label class="mb-1 fw-semibold" for="textarea">Nota:</label>
                                    </div>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>

                    <?php foreach ($planilla[0]['titulos'] as $ind) : ?>
                        <div class="title_indicador">
                            <h6><?= $ind['nombre'] ?></h6>
                        </div>
                        <div class="row">

                            <?php if (isset($ind['subtitulos'])) : ?>
                                <?php foreach ($ind['subtitulos'] as $subt) : ?>

                                    <fieldset>
                                        <legend>
                                            <?= $subt['nombre'] ?>
                                        </legend>

                                        <?php if (isset($subt['indicadores'])) : ?>

                                            <div class="row" style="width: 95%; margin: 10px auto;">
                                                <?php foreach ($subt['indicadores'] as $ind_subt) : ?>
                                                    <div class="div_indicador element_child">
                                                        <div class="div-personal_ind">
                                                            <div class="d-flex align-items-center">

                                                                <div class="dot"></div>
                                                                <div class="text-start">
                                                                    <p class="name_indicador inp_custom"><?= $ind_subt['nombre'] ?></p>
                                                                </div>
                                                            </div>
                                                            <div class="div-ind_icono">
                                                                <small data-bs-toggle="collapse" data-bs-target="#collapse_indSubt<?= $ind_subt['id'] ?>" aria-expanded="false" aria-controls="collapse_indSubt<?= $ind_subt['id'] ?>">
                                                                    Nota
                                                                    <i class="fas fa-comment"></i>
                                                                </small>
                                                                <input type="hidden" value="<?= $ind['id'] ?>" name="indicador_subt[<?= $ind_subt['id'] ?>][id_titulo]">
                                                                <input type="hidden" value="<?= $subt['id'] ?>" name="indicador_subt[<?= $ind_subt['id'] ?>][id_subtitulo]">
                                                                <input type="hidden" value="<?= $ind_subt['id'] ?>" name="indicador_subt[<?= $ind_subt['id'] ?>][id_indicador]">
                                                                <input type="number" value="0" min="0" name="indicador_subt[<?= $ind_subt['id'] ?>][valor]" id="indicador_<?= $ind_subt['id'] ?>" data-id-subt="<?= $subt['id'] ?>" class="form-control sz_inp inp_custom text-center <?= $ind_subt['id'] == 6 ? 'ind_subt_total' : 'all_ind_subts' ?> <?= ($ind_subt['class_indices'] != NULL) ? $ind_subt['class_indices'] : ''; ?>" <?= $ind_subt['id'] == 6 ? 'readonly' : '' ?> style="font-size: 12.5px!important;">
                                                            </div>
                                                        </div>
                                                        <div class="collapse" id="collapse_indSubt<?= $ind_subt['id'] ?>">
                                                            <div class="form-floating">
                                                                <textarea class="form-control inp_custom" name="indicador_subt[<?= $ind_subt['id'] ?>][nota]" id="textarea" rows="2"></textarea>
                                                                <label class="mb-1 fw-semibold" for="textarea">Nota:</label>
                                                            </div>
                                                        </div>
                                                    </div>
                                                <?php endforeach; ?>
                                            </div>
                                    </fieldset>

                                <?php endif; ?>
                            <?php endforeach; ?>
                        <?php endif; ?>
                        </div>

                        <?php if (isset($ind['indicadores'])) : ?>
                            <div class="row" style="width: 90%; margin: 0 auto;">
                                <?php foreach ($ind['indicadores'] as $ind_title) : ?>

                                    <div class="div_indicador">
                                        <div class="div-personal_ind">
                                            <div class="d-flex align-items-center">
                                                <div class="dot"></div>
                                                <div class="text-start">
                                                    <p class="name_indicador inp_custom"><?= $ind_title['nombre'] ?> </p>
                                                </div>
                                            </div>
                                            <div class="div-ind_icono">
                                                <small data-bs-toggle="collapse" data-bs-target="#collapseIndTitle_<?= $ind_title['id'] ?>" aria-expanded="false" aria-controls="collapseIndTitle_<?= $ind_title['id'] ?>">
                                                    Nota
                                                    <i class="fas fa-comment"></i>
                                                </small>
                                                <input type="hidden" value="<?= $ind['id'] ?>" name="indicador_title[<?= $ind_title['id'] ?>][id_titulo]">
                                                <input type="hidden" value="<?= $ind_title['id'] ?>" name="indicador_title[<?= $ind_title['id'] ?>][id_indicador]">
                                                <input type="number" value="0" min="0" name="indicador_title[<?= $ind_title['id'] ?>][valor]" id="indicador_title<?= $ind_title['id'] ?>" class="form-control sz_inp inp_custom text-center <?= ($ind_title['class_indices'] != NULL) ? $ind_title['class_indices'] : ''; ?>" style="font-size: 12.5px!important;">
                                            </div>
                                        </div>
                                        <div class="collapse" id="collapseIndTitle_<?= $ind_title['id'] ?>">
                                            <div class="form-floating">
                                                <textarea class="form-control inp_custom" id="textarea" name="indicador_title[<?= $ind_title['id'] ?>][nota]" rows="2"></textarea>
                                                <label class="mb-1 fw-semibold" for="textarea">Nota:</label>
                                            </div>
                                        </div>
                                    </div>
                                <?php endforeach; ?>
                            </div>
                        <?php endif; ?>

                    <?php endforeach; ?>
                    <br>

                    <div class="title_indicador">
                        <h6>KPIs del Mes</h6>
                    </div>
                    <?php if (isset($planilla['indices'])) : ?>
                        <?php foreach ($planilla['indices'] as $indice) : ?>
                            <div class="row" style="width: 90%; margin: 10px auto;">
                                <div class="div_indicador_view">

                                    <div class="div-personal_ind">
                                        <div class="d-flex align-items-center">
                                            <div class="dot"></div>
                                            <div class="text-start">
                                                <p class="name_indicador inp_custom"><?= $indice['nombre'] ?> <i class="fa-regular fa-circle-question fa-lg i-question_modal" data-id='<?= $indice['id'] ?>' data-bs-toggle="modal" data-bs-target="#modal_indices"></i></p>
                                            </div>
                                        </div>
                                        <div class="div-ind_icono">
                                            <small data-bs-toggle="collapse" data-bs-target="#collapse_indSubt<?= $indice['id'] ?>" aria-expanded="false" aria-controls="collapse_indSubt<?= $indice['id'] ?>">
                                                <i class="fas fa-comment"></i>
                                            </small>
                                            <input type="hidden" value="<?= $indice['id'] ?>" name="indice[<?= $indice['id'] ?>][id_indicador]">
                                            <input type="number" value="0" min="0" name="indice[<?= $indice['id'] ?>][valor]" id="indice_<?= $indice['id'] ?>" data-id-subt="1" class="form-control ind-just_read sz_inp text-center" style="font-size: 12.5px!important;" readonly>
                                        </div>
                                    </div>
                                    <div class="collapse" id="collapse_indSubt<?= $indice['id'] ?>">
                                        <div class="form-floating">
                                            <textarea class="form-control inp_custom" name="indice[<?= $indice['id'] ?>][nota]" id="textarea" rows="2"></textarea>
                                            <label class="mb-1 fw-semibold" for="textarea">Nota:</label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </div>

                <!-- Modal Indice KPI -->
                <div class="modal fade" id="modal_indices" tabindex="-1" aria-hidden="true">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header justify-content-center" style="padding: 7px!important; background-color: lightblue; border: 2px solid white;">
                                <p class="modal-title" style="font-size: 17px;"><i class="fa-solid fa-circle-info fa-sm"></i> Fórmula Utilizada</p>
                            </div>
                            <div class="modal-body" id="indice_contain"></div>
                        </div>
                    </div>
                </div>

                <div class="d-flex justify-content-center mt-3 mb-3">
                    <input type="submit" class="btn_modify" id="btnSubmitEstadistica" value="Cargar Estadística">
                </div>
            </div>

        </div>
    </form>
</div>

<script>
    let contenedor = document.getElementById('indicadores-container');

    const all_ind_subts = document.querySelectorAll('.all_ind_subts');
    let ind_subt_total = document.querySelector('.ind_subt_total');
    for (let i = 0; i < all_ind_subts.length; i++) {

        all_ind_subts[i].addEventListener('change', () => {
            let total = 0;
            for (let j = 0; j < all_ind_subts.length; j++) {
                if (all_ind_subts[j].getAttribute('data-id-subt') == 1) {
                    total = parseInt(total) + parseInt(all_ind_subts[j].value)
                }
            }
            ind_subt_total.value = total;
        });
    }

    let estaciones_estadisticas = <?= json_encode($estaciones); ?>;
    let sistemas_estadisticas = <?= json_encode($sistemas); ?>;
</script>
<script src="<?= base_url() ?>/assets/js/estadisticas/add.js"></script>
<script src="<?= base_url() ?>/assets/js/estadisticas/indices_kpi.js"></script>
<script src="<?= base_url() ?>/assets/js/estadisticas/submit.js"></script>
<script src="<?= base_url() ?>/assets/js/estadisticas/modal_indices.js"></script>