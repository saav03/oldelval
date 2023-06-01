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
    <div class="card" style="border: 1px solid #f6f6f6;box-shadow: 0px 0 30px rgb(179 179 179 / 39%);">
        <div class="card-body">
            <?= view('estadisticas/view_encabezado') ?>
        </div>
    </div>
</div>

<div class="card" style="border: 1px solid #f6f6f6;box-shadow: 0px 0 30px rgb(179 179 179 / 39%);">
    <div class="card-header subtitle" style="font-weight: 600; letter-spacing: 1.5px; text-align: center; margin: 0;">
        Datos de la Estadística
    </div>
    <div class="card-body">
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
                            <textarea class="form-control" id="textarea" name="indicador_gral[<?= $ind['id_indicador'] ?>][nota]" rows="2" readonly></textarea>
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
                                    <textarea style="font-size: 12.5px!important;" class="form-control ind-just_read" id="textarea" name="indicador_gral[<?= $ind_title['id_indicador'] ?>][nota]" rows="2" readonly><?= $ind_title['nota'] ?></textarea>
                                    <label class="mb-1 fw-semibold" for="textarea">Nota:</label>
                                </div>
                            </div>
                        </div>
                    </div>

                <?php endforeach; ?>
            <?php endif; ?>

        <?php endforeach; ?>
    </div>
</div>

<div class="card" style="border: 1px solid #f6f6f6;box-shadow: 0px 0 30px rgb(179 179 179 / 39%);">
    <div class="card-header subtitle" style="font-weight: 600; letter-spacing: 1.5px; text-align: center; margin: 0;">
        KPIs del Mes
    </div>
    <div class="card-body">
        <?php if (isset($estadistica['indices'])) : ?>
            <?php foreach ($estadistica['indices'] as $indice) :
            ?>
                <div class="row" style="width: 95%; margin: 10px auto;">
                    <?php if ($indice['id_indicador'] == 14) {
                        $id_indice_ifaap = $indice['id_indicador']; ?>
                        <div class="row" style="margin-left: 5px; letter-spacing: .4px;">
                            <span><small><em> <b>Agregado por</b>: <?= $indice['nombre_u_creacion'] . ' ' . $indice['apellido_u_creacion']; ?> | <b>Fecha de carga</b>: <?= $indice['fecha_carga_format']; ?></em></small> </span>
                        </div>
                    <?php } else {
                        $id_indice_ifaap = ''; ?>
                    <?php } ?>
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
                                <textarea style="font-size: 12.5px!important;" class="form-control" name="indicador_subt[<?= $indice['id_indicador'] ?>][nota]" id="textarea" rows="2" readonly><?= $indice['nota'] ?></textarea>
                                <label class="mb-1 fw-semibold" for="textarea">Nota:</label>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php endif; ?>

        <br>

        <?php if (acceso('vista_editpermiso') && $id_indice_ifaap != 14 && $estadistica[0]['tipo'] == 1) : ?>
            <div class="card">
                <div class="card-header" data-bs-toggle="collapse" href="#collapseExample" role="button" aria-expanded="false" aria-controls="collapseExample" style="background:#e5e5e5; border-bottom: 0;">
                    Ingresar Indice de Frecuencia de Accidentes de Alto Potencial (IF AAP)
                    </a>
                </div>
                <div class="collapse" id="collapseExample">
                    <div class="card-body" style="border: 1px solid lightgray;border-top: 0;border-radius: 0 0 4px 4px;">

                        <div class="row align-items-center">
                            <div class="col-xs-12 col-md-3">
                                <label class="mb-1 fw-semibold">N°Accidente operativo de alto potencial</label>
                                <input type="number" class="form-control sz_inp" id="num_accidente_alto_potencial" placeholder="N°Accidente operativo de alto potencial">
                            </div>
                            <div class="col-xs-12 col-md-1 text-center" style="margin-top: 20px;">
                                <label></label>
                                x
                            </div>
                            <div class="col-xs-12 col-md-2">
                                <label></label>
                                <input type="number" class="form-control sz_inp simulate_dis text-center" value="1000000" name="millon_alto_potencial" readonly>
                            </div>
                            <div class="col-xs-12 col-md-1 text-center" style="margin-top: 20px;">
                                <label></label>
                                ÷
                            </div>
                            <div class="col-xs-12 col-md-2">
                                <label class="mb-1 fw-semibold">Horas hombre trabajadas</label>
                                <input type="number" class="form-control sz_inp simulate_dis text-center" id="num_horas_trabajadas" value="<?= $estadistica[0]['indicadores'][1]['valor'] ?>" readonly>
                            </div>
                            <div class="col-xs-12 col-md-1 text-center " style="margin-top: 20px;">
                                <label></label>
                                =
                            </div>
                            <div class="col-xs-12 col-md-2">
                                <label class="mb-1 fw-semibold">Resultado</label>
                                <input type="number" id="resultado_indice" class="form-control sz_inp simulate_dis text-center" readonly>
                            </div>
                        </div>
                        <hr>
                        <div class="row">
                            <div class="col-xs-12">
                                <label class="mb-1 fw-semibold">Motivo</label>
                                <textarea id="motivo_indice_ifaap" cols="10" rows="3" class="form-control sz_inp" placeholder="Ingrese el motivo por el cual se agrega este índice (Opcional)"></textarea>
                            </div>
                        </div>

                        <div class="d-flex justify-content-end mt-2">
                            <div>
                                <input type="button" value="Agregar" id="btnSubmitIndice">
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        <?php endif; ?>
    </div>
</div>

<!-- Muestro los adjuntos en caso de existir (Solamente para las capacitaciones) -->
<?php if (isset($estadistica['adjuntos'])) { ?>
    <div class="card" style="border: 1px solid #f6f6f6;box-shadow: 0px 0 30px rgb(179 179 179 / 39%);">
        <div class="card-header subtitle" style="font-weight: 600; letter-spacing: 1.5px; text-align: center; margin: 0;">
            Adjuntos
        </div>
        <div class="card-body">
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
        </div>
    </div>

<?php }  ?>
<div class="d-flex justify-content-end">
    <button class="btn_modify" onclick="window.location.href = `${GET_BASE_URL()}/estadisticas`">Volver</button>
</div>
<br>

<script>
    /*
====================
Submit Indice IF APP
====================
*/

    let id_estadistica = <?= json_encode($estadistica[0]['id_estadistica']); ?>;

    const num_accidente_alto_potencial = document.getElementById('num_accidente_alto_potencial');
    const cant_horas_hombres_trabajadas = document.getElementById('num_horas_trabajadas');
    const resultado_indice = document.getElementById('resultado_indice');
    const btnSubmitIndice = document.getElementById('btnSubmitIndice');

    num_accidente_alto_potencial.addEventListener('change', e => {
        let total = 0;
        total = (parseInt(num_accidente_alto_potencial.value) * 1000000) / parseInt(cant_horas_hombres_trabajadas.value);
        resultado_indice.value = total.toFixed(2);
    });

    function submitResultadoIndice(form) {
        return $.ajax({
            type: "POST",
            url: GET_BASE_URL() + "/Estadistica/submitIndiceIFAAP",
            data: form,
            processData: false,
            contentType: false,
            beforeSend: function() {
                loadingAlert();
            },
        });
    }

    btnSubmitIndice.addEventListener('click', e => {
        e.preventDefault();
        let form = new FormData();
        form.append('id_estadistica', id_estadistica);
        form.append('valor', resultado_indice.value);
        form.append('motivo', document.getElementById('motivo_indice_ifaap').value);
        customConfirmationButton(
            "Cargar Indice IFAAP",
            "¿Confirma la carga del índice?",
            "Cargar",
            "Cancelar",
            "swal_edicion"
        ).then((result) => {
            if (result.isConfirmed) {
                submitResultadoIndice(form)
                    .done(function(data) {
                        customSuccessAlert(
                            "Registro Exitoso",
                            "El Indice se registró correctamente",
                            "swal_edicion"
                        ).then((result) => {
                            if (result.isConfirmed) {
                                window.location.reload();
                            }
                        });
                    })
                    .fail((err, textStatus, xhr) => {
                        let errors = Object.values(JSON.parse(err.responseText));
                        errors = errors.join(". ");
                        customShowErrorAlert(null, errors, 'swal_edicion');
                    });
            }
        });

    });
</script>