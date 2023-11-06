<link rel="stylesheet" href="<?= base_url('assets/css/auditorias/add.css') ?>">
<link rel="stylesheet" href="<?= base_url('assets/css/auditorias/aud_gral.css') ?>">
<title>OLDELVAL - Inspección Vehicular</title>
<div class="container">
    <div class="row" style="box-shadow: 0px 0px 10px 10px rgba(250,250,250,1);">
        <div class="col-md-12" style="border-radius: 10px;background: #fcfdff;border-bottom: 2px solid #f1f1f1;border-top: 2px solid #f1f1f1;">
            <h5 class="text-center" style="padding: 10px;color: #84baeb;font-size: 24px;letter-spacing: 1px;">Inspección Vehicular - N° <?= $auditoria['id_auditoria']; ?> </h5>

            <div class="row">
                <div class="col-xs-12 col-md-3"></div>
                <div class="col-xs-12 col-md-3 text-center">
                    <p><b>Usuario Carga</b>: <?= isset($auditoria['usuario_carga']) ? $auditoria['usuario_carga'] : '-'; ?></p>
                </div>
                <div class="col-xs-12 col-md-3 text-center">
                    <p><b>Fecha de Carga</b>: <?= isset($auditoria['fecha_carga_format']) ? $auditoria['fecha_carga_format'] : '--/--/----'; ?></p>
                </div>
                <div class="col-xs-12 col-md-3"></div>
            </div>
        </div>
    </div>
</div>

<div class="container">
    <div class="card mt-4 mb-0 card_title_initial">
        <div class="card-header card_modif_aud">
            Datos Principales
        </div>
    </div>

    <div class="card">
        <div class="card-body" style="margin: 20px;">
            <div class="row">
            <div class="col-xs-12 col-md-2 text-center">
                    <label class="sz_inp fw-semibold text-center">Contratista</label>
                    <input type="text" class="form-control sz_inp" value="<?= isset($auditoria['contratista']) ? $auditoria['contratista'] : 'No Aplica'; ?>" readonly>
                </div>
                <div class="col-xs-12 col-md-2 text-center">
                    <label class="sz_inp fw-semibold text-center">Equipo</label>
                    <input type="text" class="form-control sz_inp" value="<?= isset($auditoria['equipo']) ? $auditoria['equipo'] : 'No Aplica'; ?>" readonly>
                </div>
                <div class="col-xs-12 col-md-2 text-center">
                    <label class="sz_inp fw-semibold text-center">Conductor</label>
                    <input type="text" class="form-control sz_inp" value="<?= isset($auditoria['conductor']) ? $auditoria['conductor'] : 'No Aplica'; ?>" readonly>
                </div>
                <div class="col-xs-12 col-md-2 text-center">
                    <label class="sz_inp fw-semibold text-center">N°Interno</label>
                    <input type="number" class="form-control sz_inp text-center" value="<?= isset($auditoria['num_interno']) ? $auditoria['num_interno'] : '0'; ?>" readonly>
                </div>
                <div class="col-xs-12 col-md-2 text-center">
                    <label class="sz_inp fw-semibold text-center">Marca</label>
                    <input type="text" class="form-control sz_inp text-center" value="<?= isset($auditoria['marca']) ? $auditoria['marca'] : 'No Aplica'; ?>" readonly>
                </div>
                <div class="col-xs-12 col-md-2 text-center">
                    <label class="sz_inp fw-semibold text-center">Modelo</label>
                    <input type="text" class="form-control sz_inp text-center" value="<?= isset($auditoria['modelo']) ? $auditoria['modelo'] : 'No Aplica'; ?>" readonly>
                </div>
            </div>
            <div class="row mt-2">
                <div class="col-xs-12 col-md-3 text-center">
                    <label class="sz_inp fw-semibold text-center">Patente</label>
                    <input type="text" class="form-control sz_inp" value="<?= isset($auditoria['patente']) ? $auditoria['patente'] : 'No Aplica'; ?>" readonly>
                </div>
                <div class="col-xs-12 col-md-3 text-center">
                    <label class="sz_inp fw-semibold text-center">Titular</label>
                    <input type="text" class="form-control sz_inp" value="<?= isset($auditoria['titular']) ? $auditoria['titular'] : 'No Aplica'; ?>" readonly>
                </div>
                <div class="col-xs-12 col-md-2 text-center">
                    <label class="sz_inp fw-semibold text-center">Fecha</label>
                    <input type="date" class="form-control sz_inp text-center" value="<?= isset($auditoria['fecha']) ? $auditoria['fecha'] : ''; ?>" readonly>
                </div>
                <div class="col-xs-12 col-md-2 text-center">
                    <label class="sz_inp fw-semibold text-center">Hora</label>
                    <input type="time" class="form-control sz_inp text-center" value="<?= isset($auditoria['hora']) ? $auditoria['hora'] : '00:00:00'; ?>" readonly>
                </div>
                <div class="col-xs-12 col-md-2 text-center">
                    <label class="sz_inp fw-semibold text-center">Proyecto</label>
                    <input type="text" class="form-control sz_inp" value="<?= isset($auditoria['proyecto']) ? $auditoria['proyecto'] : 'No Aplica'; ?>" readonly>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="container">
    <div class="card mt-4 mb-0 card_title_initial">
        <div class="card-header card_modif_aud d-flex align-items-center justify-content-between">
            <div>
                Respuestas | Comentarios
            </div>
            <label for="drop_down_1">
                <div class="drop_down">
                    <div id="arrow" class="arrow up"></div>
                </div>
            </label>
        </div>
    </div>

    <input type="checkbox" id="drop_down_1" checked>
    <div class="card card_bloque">
        <div class="card-body card_bloque_body" style="margin: 20px; padding-bottom: 30px;">
            <?php $aux = 1;
            foreach ($auditoria['bloque'] as $bloque) : ?>
                <div class="row" id="bloque_x_pregunta">
                    <div class="div-subtitle_aud_check">
                        <h6 class="subtitle_aud_check"><?= $bloque['nombre']; ?></h6>
                    </div>

                    <?php foreach ($bloque['preguntas_rtas'] as $pregunta) : ?>
                        <div style="width: 95%!important; margin: 0 auto;">
                            <div class="row align-items-center mb-3 mt-3 pregunta_rta">

                                <div class="col-xs-12 col-md-6">
                                    <p class="m-0 w-100 content_question p_green">
                                        <b>( <?= $aux; ?> )</b> <?= $pregunta['respuesta'][0]['pregunta']; ?>
                                    </p>
                                </div>

                                <div class="col-xs-12 col-md-4 text-center">
                                    <div class="pb-2 pt-2">
                                        <div class="btn-group btn-group-toggle" role="group" aria-label="Basic radio toggle button group">
                                            <input type="radio" class="btn-check verde_check" value="1" autocomplete="off" <?= $pregunta['respuesta'][0]['rta'] == '1' ? 'checked' : '' ?>>
                                            <label class="btn verde btnsToggle <?= $pregunta['respuesta'][0]['rta'] != '1' ? 'disabled_btn' : '' ?>">Bien</label>

                                            <input type="radio" class="btn-check rojo_check" value="0" autocomplete="off" <?= $pregunta['respuesta'][0]['rta'] == '0' ? 'checked' : '' ?>>
                                            <label class="btn rojo btnsToggle <?= $pregunta['respuesta'][0]['rta'] != '0' ? 'disabled_btn' : '' ?>">Mal</label>

                                            <input type="radio" class="btn-check amarillo_checked" value="-1" autocomplete="off" <?= $pregunta['respuesta'][0]['rta'] == '-1' ? 'checked' : '' ?>>
                                            <label class="btn amarillo btnsToggle <?= $pregunta['respuesta'][0]['rta'] != '-1' ? 'disabled_btn' : '' ?>">N/A</label>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-xs-12 col-md-2">
                                    <select class="form-select sz_inp text-center" disabled>
                                        <option value="">-- OBS --</option>
                                        <?php foreach ($tipo_obs_vehicular as $obs) :
                                        ?>
                                            <option value="<?= $obs['id']; ?>" <?= $obs['id'] == $pregunta['respuesta'][0]['tipo_obs'] ? 'selected' : '' ?>><?= $obs['tipo']; ?></option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                            </div> <!-- .pregunta_rta -->

                            <?php if (!empty($pregunta['respuesta'][0]['comentario'])) : ?>
                                <div class="row comment-question_rta">
                                    <p class="m-0 p-0 text-center title_comment">Comentario</p>
                                    <textarea class="form-control sz_inp" rows="3" readonly><?= $pregunta['respuesta'][0]['comentario'] ?></textarea>
                                </div> <!-- .comment-question_rta -->
                            <?php endif; ?>
                        </div>
                    <?php $aux++;
                    endforeach; ?>
                </div> <!-- #bloque_x_pregunta -->
            <?php endforeach; ?>

            <div class="row" id="datos_extras">
                <div class="col-xs-12 col-md-6">
                    <fieldset>
                        <legend>
                            Resultado de la Inspección
                        </legend>
                        <div class="row" style="padding: 36.5px 50px;">
                            <div class='content'>
                                <div class="dpx">
                                    <div class='py d-flex'>
                                        <label class="label_satisfactoria" style="margin-right: 20px;">
                                            <input type="radio" class="option-input radio" name="resultado_inspeccion" value="1" disabled <?= $auditoria['resultado_inspeccion'] == 1 ? 'checked' : '' ?> />
                                            Satisfactoria
                                        </label>
                                        <label class="label_satisfactoria">
                                            <input type="radio" class="option-input radio" name="resultado_inspeccion" value="0" disabled <?= $auditoria['resultado_inspeccion'] == 0 ? 'checked' : '' ?> />
                                            No Satisfactoria
                                        </label>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </fieldset>
                </div>

                <div class="col-xs-12 col-md-6">
                    <fieldset>
                        <legend>
                            Observaciones y Medidas a Implementar
                        </legend>
                        <div class="row" style="padding: 10px 50px;">
                            <textarea class="form-control sz_inp obs_medidas" name="medidas_implementar" cols="30" rows="5" style="border: 1px solid #f1f1f1;" placeholder="Ingrese las observaciones (Opcional) - Máximo 300 caracteres"><?= $auditoria['medidas_implementar'] ? $auditoria['medidas_implementar'] : '' ?></textarea>
                        </div>
                    </fieldset>
                </div>
            </div>
        </div>
    </div>
    <section>
        <?php
        if ($hallazgo != null) :

            $data['h'] = $hallazgo; ?>
            <?= view('auditoria/vehicular/aud_plan_vehicular', $data); ?>
        <?php endif; ?>
    </section>


    <div class="mt-3 mb-3">
        <a class="btn_modify" href="<?= base_url('auditorias') ?>">Volver</a>
    </div>
    <br>
</div>

<script>
    const drop_down = document.getElementById('drop_down_1');
    const icon_arrow = document.getElementById('arrow');
    const card_bloque = document.querySelector('.card_bloque');
    const card_bloque_body = document.querySelector('.card_bloque_body');

    if (drop_down.checked) {
        card_bloque.style.maxHeight = card_bloque_body.offsetHeight + 'px';
    }

    drop_down.addEventListener('change', function() {
        if (drop_down.checked) {
            card_bloque.style.maxHeight = card_bloque_body.offsetHeight + 'px';
            icon_arrow.classList.remove('down');
        } else {
            card_bloque.style.maxHeight = '10px';
            icon_arrow.classList.add('down');
        }
    });
</script>