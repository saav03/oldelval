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
            foreach ($bloque as $b) : ?>
                <div class="row" id="bloque_x_pregunta">
                    <div class="div-subtitle_aud">
                        <h6 class="subtitle_aud"><?= $b['nombre']; ?></h6>
                    </div>

                    <?php foreach ($b['preguntas_rtas'] as $pregunta) : ?>
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