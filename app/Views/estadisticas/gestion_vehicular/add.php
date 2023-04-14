<link href="<?= base_url() ?>/assets/css/estadisticas/add/add.css" rel="stylesheet">
<div class="container">
    <form action="" method="POST" id="form_submit">
        <div class="blister-title-container">
            <h4 class="blister-title">Estadísticas Gestión Vehicular</h4>
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
            <!-- <div class="card">
                <div class="card-header text-center header-perfil" style="background-color: #00b8e645 !important;">
                    <h6 style="margin: 0px;"><b>Carga de Datos</b></h6>
                </div>
            </div> -->
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
                                                                <input type="number" value="0" min="0" name="indicador_subt[<?= $ind_subt['id'] ?>][valor]" id="indicador_<?= $ind_subt['id'] ?>" data-id-subt="<?= $subt['id'] ?>" class="form-control sz_inp text-center  <?= $ind_subt['id'] == 21 || $ind_subt['id'] == 22 ? 'ind_subt_total' : 'all_ind_subts' ?>" <?= $ind_subt['id'] == 21 || $ind_subt['id'] == 22 ? 'readonly' : '' ?> style="font-size: 12.5px!important;">
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
                                                <input type="number" value="0" min="0" name="indicador_title[<?= $ind_title['id'] ?>][valor]" id="indicador_title<?= $ind_title['id'] ?>" class="form-control  sz_inp text-center" style="font-size: 12.5px!important;">
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
                                                    <p class="name_indicador"><?= $indice['nombre'] ?></p>
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
                        <div class="d-flex justify-content-center mt-3 mb-3">
                            <input type="submit" class="btn btn_modify" id="btnSubmitEstadistica" value="Cargar Estadística">
                        </div>
                    <?php endforeach; ?>
                    <br>
                </div>
            </div>
    </form>
</div>
<script>
    /*
===========================
Indices de las Estadísticas
GESTION VEHICULAR
===========================
*/
    /* == (IFAV) == */
    const ind_acc_vehicular_17 = document.getElementById("indicador_17");
    const ind_acc_vehicular_19 = document.getElementById("indicador_19");
    ind_acc_vehicular_17.classList.add('ind_acc_vehicular');
    ind_acc_vehicular_19.classList.add('ind_acc_vehicular');

    const ind_km_recorridos_18 = document.getElementById("indicador_18");
    const ind_km_recorridos_20 = document.getElementById("indicador_20");

    ind_km_recorridos_18.classList.add('ind_km_recorridos');
    ind_km_recorridos_20.classList.add('ind_km_recorridos');

    let ifav = document.getElementById("indice_23");

    const all_ind_acc_vehiculares = document.querySelectorAll('.ind_acc_vehicular');
    const all_ind_km_recorridos = document.querySelectorAll('.ind_km_recorridos');

    let total_acc_vehiculares = document.getElementById("indicador_21");
    let total_km_recorridos = document.getElementById("indicador_22");

    let total_accidentes, total_kilometros;

    for (let i = 0; i < all_ind_acc_vehiculares.length; i++) {

        all_ind_acc_vehiculares[i].addEventListener('change', () => {
            let total;
            total_accidentes = 0;
            total_kilometros = 0;

            for (let j = 0; j < all_ind_acc_vehiculares.length; j++) {
                total_accidentes = parseInt(total_accidentes) + parseInt(all_ind_acc_vehiculares[j].value)
            }
            for (let j = 0; j < all_ind_km_recorridos.length; j++) {
                total_kilometros = parseInt(total_kilometros) + parseInt(all_ind_km_recorridos[j].value)
            }

            total_acc_vehiculares.value = parseInt(total_accidentes);
            total_km_recorridos.value = parseInt(total_kilometros);

            /* == Fórmula de IFAV == */
            total = (total_accidentes * 1000000) / total_kilometros;
            ifav.value = total.toFixed(2);
        });
    }

    for (let i = 0; i < all_ind_km_recorridos.length; i++) {

        all_ind_km_recorridos[i].addEventListener('change', () => {
            let total;
            total_kilometros = 0;
            total_accidentes = 0;

            for (let j = 0; j < all_ind_km_recorridos.length; j++) {
                total_kilometros = parseInt(total_kilometros) + parseInt(all_ind_km_recorridos[j].value)
            }
            for (let j = 0; j < all_ind_acc_vehiculares.length; j++) {
                total_accidentes = parseInt(total_accidentes) + parseInt(all_ind_acc_vehiculares[j].value)
            }

            total_acc_vehiculares.value = parseInt(total_accidentes);
            total_km_recorridos.value = parseInt(total_kilometros);

            /* == Fórmula de IFAV == */
            total = (total_accidentes * 1000000) / total_kilometros;
            ifav.value = total.toFixed(2);
        });
    }
</script>

<script src="<?= base_url() ?>/assets/js/estadisticas/add.js"></script>
<script src="<?= base_url() ?>/assets/js/estadisticas/submit.js"></script>
