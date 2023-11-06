<link rel="stylesheet" href="<?= base_url('assets/css/auditorias/aud_gral.css') ?>">
<link rel="stylesheet" href="<?= base_url('assets/css/auditorias/aud_plan_control.css') ?>">
<title>OLDELVAL - Inspección de Control</title>
<div class="container">
    <div class="row" style="box-shadow: 0px 0px 10px 10px rgba(250,250,250,1);">
        <div class="col-md-12" style="border-radius: 10px;background: #fcfdff;border-bottom: 2px solid #f1f1f1;border-top: 2px solid #f1f1f1;">
            <h5 class="text-center" style="padding: 10px;color: #84baeb;font-size: 24px;letter-spacing: 1px;">Inspección de Control - N°<?= $auditoria['id_auditoria']; ?></h5>

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
                <div class="col-xs-12 col-md-3 text-center">
                    <label class="sz_inp fw-semibold text-center">Contratista</label>
                    <input type="text" class="form-control sz_inp" value="<?= isset($auditoria['contratista']) ? $auditoria['contratista'] : 'No Aplica'; ?>" readonly>
                </div>
                <div class="col-xs-12 col-md-3 text-center">
                    <label class="sz_inp fw-semibold text-center">Supervisor Responsable</label>
                    <input type="text" class="form-control sz_inp" value="<?= isset($auditoria['supervisor_responsable']) ? $auditoria['supervisor_responsable'] : 'No Aplica'; ?>" readonly>
                </div>
                <div class="col-xs-12 col-md-3 text-center">
                    <label class="sz_inp fw-semibold text-center">Cantidad del Personal</label>
                    <input type="number" class="form-control sz_inp text-center" value="<?= isset($auditoria['cant_personal']) ? $auditoria['cant_personal'] : 'No Aplica'; ?>" readonly>
                </div>
                <div class="col-xs-12 col-md-3 text-center">
                    <label class="sz_inp fw-semibold text-center">N°Informe</label>
                    <input type="number" class="form-control sz_inp text-center" value="<?= isset($auditoria['num_informe']) ? $auditoria['num_informe'] : 'No Aplica'; ?>" readonly>
                </div>
            </div>
            <div class="row mt-2">
                <div class="col-xs-12 col-md-3 text-center">
                    <label class="sz_inp fw-semibold text-center">Proyecto</label>
                    <input type="text" class="form-control sz_inp" value="<?= isset($auditoria['proyecto']) ? $auditoria['proyecto'] : 'No Aplica'; ?>" readonly>
                </div>
                <div class="col-xs-12 col-md-3 text-center">
                    <label class="sz_inp fw-semibold text-center">Módulo</label>
                    <input type="text" class="form-control sz_inp" value="<?= isset($auditoria['modulo']) ? $auditoria['modulo'] : 'No Aplica'; ?>" readonly>
                </div>
                <div class="col-xs-12 col-md-3 text-center">
                    <label class="sz_inp fw-semibold text-center">Estación de Bombeo</label>
                    <input type="text" class="form-control sz_inp" value="<?= isset($auditoria['estacion']) ? $auditoria['estacion'] : 'No Aplica'; ?>" readonly>
                </div>
                <div class="col-xs-12 col-md-3 text-center">
                    <label class="sz_inp fw-semibold text-center">Sistema de Oleoducto</label>
                    <input type="text" class="form-control sz_inp" value="<?= isset($auditoria['sistema']) ? $auditoria['sistema'] : 'No Aplica'; ?>" readonly>
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
        <div class="card-body card_bloque_body">
            <?php $aux = 1;
            foreach ($auditoria['bloque'] as $bloque) : ?>
                <div class="row" id="bloque_x_pregunta">
                    <div class="div-subtitle_aud">
                        <h6 class="subtitle_aud"><?= $bloque['nombre']; ?></h6>
                    </div>

                    <?php foreach ($bloque['preguntas_rtas'] as $pregunta) : ?>
                        <div style="width: 95%!important; margin: 0 auto;">
                            <div class="row align-items-center mb-3 mt-3 pregunta_rta">

                                <div class="col-xs-12 col-md-8">
                                    <p class="m-0 w-100 content_question p_green">
                                        <b>( <?= $aux; ?> )</b> <?= $pregunta['respuesta'][0]['pregunta']; ?>
                                    </p>
                                </div>

                                <div class="col-xs-12 col-md-4 text-end">
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
        </div>
    </div>
</div>

<section>
    <?php if ($hallazgo != null) : ?>
        <?php $data['h'] = $hallazgo; ?>
        <?= view('auditoria/control/aud_plan_control', $data); ?>
    <?php endif; ?>
</section>

<div class="mt-3 mb-3">
    <a class="btn_modify" href="<?= base_url('auditorias') ?>">Volver</a>
</div>
<br>

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