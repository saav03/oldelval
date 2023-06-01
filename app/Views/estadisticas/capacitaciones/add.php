<link href="<?= base_url() ?>/assets/css/estadisticas/add/add.css" rel="stylesheet">

<?php
/* echo '<pre>';
var_dump($planilla);
echo '</pre>';
exit; */
?>

<div class="container">
    <form action="" method="POST" id="form_submit">
        <div class="blister-title-container">
            <h4 class="blister-title">Estadísticas Capacitaciones</h4>
            <input type="hidden" name="tipo" id="tipo" value="<?= $planilla[0]['id'] ?>">
        </div>
        <div class="row d-flex text-end" style="width:100%;">
            <small style="color:lightgrey"><i>(*)Campo Obligatorio</i></small>
        </div>

        <div>
            <div class="card text-center">
                <div class="card-header text-center header-perfil" style="background-color: #00b8e645 !important;">
                    <h6 style="margin: 0px;" id="titulo"><b>Formulario</b></h6>
                </div>
                <div class="card-body">
                    <?= view('estadisticas/view_add/encabezado') ?>
                </div>
            </div>

            <!-- === Acá Inicia la parte de los Indicadores === -->

            <div class="card">
                <div class="card-header text-center header-perfil" style="background-color: #00b8e645 !important;">
                    <h6 style="margin: 0px;"><b>Carga de Datos</b></h6>
                </div>
                <div class="card-body">
                    <div class="justify-content-between d-flex" style="width: 90%; margin: 0 auto;">
                        <h6 class="px-3"><b>Indicadores</b></h6>
                        <h6 class="px-3"><b>Valores</b></h6>
                    </div>

                    <?php foreach ($planilla[0]['indicadores'] as $ind) : ?>
                        <div class="row" style="width: 90%;margin: 0 auto;">
                            <div class="div_indicador">
                                <div class="div-personal_ind">
                                    <div class="d-flex align-items-center">
                                        <div class="dot"></div>
                                        <div class="text-start">
                                            <p class="name_indicador"><?= $ind['nombre'] ?></p>
                                        </div>
                                    </div>
                                    <div class="div-ind_icono">
                                        <small data-bs-toggle="collapse" data-bs-target="#collapse_ind_gral_<?= $ind['id'] ?>" aria-expanded="false" aria-controls="collapse_ind_gral_<?= $ind['id'] ?>">
                                            Nota
                                            <i class="fas fa-comment"></i>
                                        </small>
                                        <input type="hidden" value="<?= $ind['id'] ?>" name="indicador_gral[<?= $ind['id'] ?>][id_indicador]">
                                        <input type="number" value="0" min="0" name="indicador_gral[<?= $ind['id'] ?>][valor]" id="indicador_gral_<?= $ind['id'] ?>" class="form-control sz_inp text-center " style="font-size: 12.5px!important;">
                                    </div>
                                </div>
                                <div class="collapse" id="collapse_ind_gral_<?= $ind['id'] ?>">
                                    <div class="form-floating">
                                        <textarea class="form-control" id="textarea" name="indicador_gral[<?= $ind['id'] ?>][nota]" rows="2"></textarea>
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
                                                                    <p class="name_indicador"><?= $ind_subt['nombre'] ?></p>
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
                                                                <input type="number" value="0" min="0" name="indicador_subt[<?= $ind_subt['id'] ?>][valor]" id="indicador_<?= $ind_subt['id'] ?>" data-id-subt="<?= $subt['id'] ?>" class="form-control sz_inp text-center">
                                                            </div>
                                                        </div>
                                                        <div class="collapse" id="collapse_indSubt<?= $ind_subt['id'] ?>">
                                                            <div class="form-floating">
                                                                <textarea class="form-control" name="indicador_subt[<?= $ind_subt['id'] ?>][nota]" id="textarea" rows="2"></textarea>
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
                                                    <p class="name_indicador"><?= $ind_title['nombre'] ?> </p>
                                                </div>
                                            </div>
                                            <div class="div-ind_icono">
                                                <small data-bs-toggle="collapse" data-bs-target="#collapseIndTitle_<?= $ind_title['id'] ?>" aria-expanded="false" aria-controls="collapseIndTitle_<?= $ind_title['id'] ?>">
                                                    Nota
                                                    <i class="fas fa-comment"></i>
                                                </small>
                                                <input type="hidden" value="<?= $ind['id'] ?>" name="indicador_title[<?= $ind_title['id'] ?>][id_titulo]">
                                                <input type="hidden" value="<?= $ind_title['id'] ?>" name="indicador_title[<?= $ind_title['id'] ?>][id_indicador]">
                                                <input type="number" value="0" min="0" name="indicador_title[<?= $ind_title['id'] ?>][valor]" id="indicador_title<?= $ind_title['id'] ?>" class="form-control  sz_inp text-center <?= $ind_title['id'] == 30 ? 'ind_subt_total' : '' ?>" style="font-size: 12.5px!important;" <?= $ind_title['id'] == 30 ? 'readonly' : '' ?>>
                                            </div>
                                        </div>
                                        <div class="collapse" id="collapseIndTitle_<?= $ind_title['id'] ?>">
                                            <div class="form-floating">
                                                <textarea class="form-control" id="textarea" name="indicador_title[<?= $ind_title['id'] ?>][nota]" rows="2"></textarea>
                                                <label class="mb-1 fw-semibold" for="textarea">Nota:</label>
                                            </div>
                                        </div>
                                    </div>
                                <?php endforeach; ?>
                            </div>
                        <?php endif; ?>
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
                                                    <p class="name_indicador"><?= $indice['nombre'] ?> <i class="fa-regular fa-circle-question fa-lg i-question_modal" data-id='<?= $indice['id'] ?>' data-bs-toggle="modal" data-bs-target="#modal_indices"></i></p>
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
                                                <textarea class="form-control" name="indice[<?= $indice['id'] ?>][nota]" id="textarea" rows="2"></textarea>
                                                <label class="mb-1 fw-semibold" for="textarea">Nota:</label>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        <?php endif; ?>

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

                        <!-- Adjuntar archivo -->
                        <section style="width: 90%; margin: 0 auto;">
                            <div class="row justify-content-center" id="container_uploads">
                                <!-- <div class="row align-items-center mb-3" style="box-shadow: 0px 0px 6px 0px rgb(239 236 236); border-radius: 10px;">
                                    <div class="d-flex col-xs-12 col-md-10 justify-content-between">
                                        <div class="d-flex justify-content-evenly align-items-center">
                                            <img class="img_clip" src="<!?= base_url('assets/img/Word.svg') ?>" style="margin-right: 20px;" alt="Clip cargar archivo">
                                            <p class="m-0">Nombre archivo.doc</p>
                                        </div>
                                        <textarea cols="30" rows="2" class="form-control sz_inp" placeholder="Ingrese una breve descripción (Opcional)" style="width: 70%;border: 1px solid #f3f3f3;"></textarea>
                                    </div>
                                    <div class="col-xs-12 col-md-2 text-center" title="Eliminar adjunto">
                                        <button class="btn-del_inc"><i class="fa-solid fa-trash-can"></i></button>
                                    </div>
                                </div> -->
                                <!-- <div class="row align-items-center mb-3" style="box-shadow: 0px 0px 6px 0px rgb(239 236 236); border-radius: 10px;">
                                    <div class="d-flex col-xs-12 col-md-10 justify-content-between">
                                        <div class="d-flex justify-content-evenly align-items-center">
                                            <img class="img_clip" src="<!?= base_url('assets/img/Excel.svg') ?>" style="margin-right: 20px;" alt="Clip cargar archivo">
                                            <p class="m-0">Nombre archivo.xls</p>
                                        </div>
                                        <textarea cols="30" rows="2" class="form-control sz_inp" placeholder="Ingrese una breve descripción (Opcional)" style="width: 70%;border: 1px solid #f3f3f3;"></textarea>
                                    </div>
                                    <div class="col-xs-12 col-md-2 text-center" title="Eliminar adjunto">
                                        <button class="btn-del_inc"><i class="fa-solid fa-trash-can"></i></button>
                                    </div>
                                </div> -->
                                <!-- <div class="row align-items-center mb-3" style="box-shadow: 0px 0px 6px 0px rgb(239 236 236); border-radius: 10px;">
                                    <div class="d-flex col-xs-12 col-md-10 justify-content-between">
                                        <div class="d-flex justify-content-evenly align-items-center">
                                            <img class="img_clip" src="<!?= base_url('assets/img/PDF.svg') ?>" style="margin-right: 20px;" alt="Clip cargar archivo">
                                            <p class="m-0">Nombre archivo.pdf</p>
                                        </div>
                                        <textarea cols="30" rows="2" class="form-control sz_inp" placeholder="Ingrese una breve descripción (Opcional)" style="width: 70%;border: 1px solid #f3f3f3;"></textarea>
                                    </div>
                                    <div class="col-xs-12 col-md-2 text-center" title="Eliminar adjunto">
                                        <button class="btn-del_inc"><i class="fa-solid fa-trash-can"></i></button>
                                    </div>
                                </div> -->
                                <!-- <div class="row">
                                    <div class="d-flex adj_file">
                                        <div class="div_img_clip">
                                            <i class="fa-solid fa-cloud-arrow-up"></i>
                                        </div>
                                        <div class="adjuntar_archivo">
                                            <p>Adjuntar Archivo</p>
                                        </div>
                                    </div>
                                    <p class="mt-2">Máximo 3 archivos por carga - Formato permitido: .pdf o .doc | .docx</p>
                                </div> -->
                            </div>
                        </section>

                        <div class="d-flex justify-content-center mt-3 mb-3">
                            <input type="submit" class="btn btn_modify" id="btnSubmitEstadistica" value="Cargar Estadística">
                        </div>
                    <?php endforeach; ?>
                    <br>
                </div>
            </div>
    </form>
</div>

<script src="<?= base_url() ?>/assets/js/estadisticas/uploadFile.js"></script>

<script>
    new uploadFile(document.getElementById('container_uploads'), 'adj_capacitaciones', {
        maxFileNumber: 3
    }).init();
</script>

<script>
    /* const inp_mul_hs = document.querySelectorAll('.inp_mul_hs');
    let ind_total_hs_capacitaciones = document.querySelector('.ind_total_hs_capacitaciones');

    for (let i = 0; i < inp_mul_hs.length; i++) {
        inp_mul_hs[i].addEventListener('change', () => {
            let total = 0;
            let cant_participantes = inp_mul_hs[0];
            let hs_capacitaciones = inp_mul_hs[1];
            total = parseInt(cant_participantes.value) * parseInt(hs_capacitaciones.value);
            ind_total_hs_capacitaciones.value = total;
        });
    } */
</script>

<script>
    /* == (Indice de Capacitación IC) == */
    const ind_cant_participantes = document.getElementById("indicador_24");
    const ind_hs_capacitacion = document.getElementById("indicador_25");
    const ind_hs_trabajadas = document.getElementById("indicador_36");
    let ic = document.getElementById("indice_34");
    
    const ind_total_hs_capacitacion = document.getElementById("indicador_30");

    /* == (Horas Objetivo de Capacitación) == */
    let hora_objetivo = document.getElementById("indice_35");

    ind_hs_trabajadas.addEventListener("change", () => {
        let hs_trabajadas = parseInt(ind_hs_trabajadas.value);
        let hs_capacitaciones = parseInt(ind_hs_capacitacion.value);
        let total = (hs_capacitaciones / hs_trabajadas) * 100;
        ic.value = total.toFixed(2);
        hora_objetivo.value = (hs_trabajadas * 0.01).toFixed(2);
    });
    ind_hs_capacitacion.addEventListener("change", () => {
        let hs_trabajadas = parseInt(ind_hs_trabajadas.value);
        let hs_capacitaciones = parseInt(ind_hs_capacitacion.value);
        let total = (hs_capacitaciones / hs_trabajadas) * 100;
        ic.value = total.toFixed(2);
        ind_total_hs_capacitacion.value = (parseInt(ind_hs_capacitacion.value) * parseInt(ind_cant_participantes.value));
    });

    ind_cant_participantes.addEventListener("change", () => {
        ind_total_hs_capacitacion.value = (parseInt(ind_hs_capacitacion.value) * parseInt(ind_cant_participantes.value));
    });

    let estaciones_estadisticas = <?= json_encode($estaciones); ?>;
    let sistemas_estadisticas = <?= json_encode($sistemas); ?>;
</script>
<script src="<?= base_url() ?>/assets/js/estadisticas/add.js"></script>
<script src="<?= base_url() ?>/assets/js/estadisticas/submit.js"></script>
<script src="<?= base_url() ?>/assets/js/estadisticas/modal_indices.js"></script>