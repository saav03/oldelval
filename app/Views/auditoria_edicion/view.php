<title>OLDELVAL - Edición Auditoría</title>

<link href="<?= base_url() ?>/assets/css/auditorias/edicion.css" rel="stylesheet">

<!-- Encabezado -->
<section class="container">
    <form method="POST" id="form_submit">
        <input type="hidden" value="<?= esc($auditoria[0]['id']) ?>" name="id_auditoria">
        <input type="hidden" value="<?= esc($auditoria[0]['revision']) ?>" name="revision">
        <input type="hidden" value="<?= esc($auditoria[0]['tipo_aud']) ?>" name="tipo_aud">
        <div class="row">
            <div class="card card_custom">

                <!-- Título y Revisión -->
                <div class="title_revision card-header card_header_custom">
                    <div class="d-flex align-items-center justify-content-between">
                        <div class="d-flex align-items-center">
                            <div class="d-flex flex-row align-items-center">
                                <div class="point"></div>
                                <h5 class="p-0 m-0"><?= esc($auditoria[0]['nombre']) ?></h5>
                            </div>
                            <span class="ms-3">Revisión <?= esc($auditoria[0]['revision']) ?></span>
                        </div>
                        <?php if ($auditoria[0]['obsoleto'] != 1) : ?>
                            <div id="block_btn_actions">
                                <button class="btn_action btn_edition" data-bs-toggle="modal" data-bs-target="#modal_habilitacion_edicion"><i class="fa-solid fa-pen"></i> Modo de Edición</button>
                            </div>
                        <?php endif; ?>
                    </div> <!-- .card-header -->
                </div>

                <!-- Preguntas -->
                <div id="block_questions">

                    <!-- En caso de ya ser obsoleta la Auditoría se va a mostrar el cartel -->
                    <?php if ($auditoria[0]['obsoleto'] == 1) : ?>
                        <article class="d-flex align-items-center my-4 ms-5 me-5 alert_revision">
                            <div class="p-3">
                                <i class="fa-solid fa-circle-exclamation fs-5"></i>
                            </div>

                            <p class="p-3 m-0">
                                La versión más reciente de la Inspección ha sido revisada y actualizada, lo cual conlleva que ésta versión ya no estará disponible para cargar o editar la planilla. Le recomendamos utilizar la última revisión para asegurar la precisión y consistencia de los datos.
                            </p>
                        </article>
                    <?php endif; ?>

                    <!-- Título de la Auditoría (Si se desea editar) -->
                    <div class="text-center mt-3 p-3">
                        <h6>Título de la Auditoría</h6>
                        <input type="text" class="form-control text-center title_auditoria" value="<?= esc($auditoria[0]['nombre']) ?>" name="titulo_auditoria" disabled>
                    </div>

                    <?php $aux = 0;
                    foreach ($auditoria['bloque_preguntas'] as $bloque) : ?>
                        <div class="row mt-2 p-3">
                            <div>
                                <input type="text" class="form-control subt_question" value="<?= esc($bloque['nombre']) ?>" name="bloque[<?= $aux ?>][subtitulo]" disabled>
                                <!-- <h6 class="subt_question mb-3"><!?= esc($bloque['nombre']) ?></h6> -->
                                <div class="section_questions" id="section_questions_<?= esc($aux) ?>">
                                    <?php foreach ($bloque['preguntas'] as $preguntas) : ?>
                                        <article class="d-flex mb-2">
                                            <textarea id="text-area_question" class="form-control sz_inp inp_pregunta" rows="1" name="bloque[<?= $aux ?>][preguntas_editadas][]" disabled><?= esc($preguntas['pregunta']) ?></textarea>
                                            <button class="ms-3 btn_del_question" style="display: none;" data-id="<?= esc($preguntas['id']) ?>" onclick="deleteQuestion(this)"><i class="fa-solid fa-trash"></i></button>
                                        </article>
                                    <?php endforeach; ?>

                                </div> <!-- #section_questions -->
                                <div class="justify-content-center mt-3 add_question">
                                    <button class="btn_add_question" data-id="<?= $aux ?>"><i class="fa-solid fa-circle-plus"></i></button>
                                </div>
                            </div>
                        </div>

                        <div class="ps-3 pe-3">
                            <hr>
                        </div>
                    <?php $aux++;
                    endforeach; ?>

                    <section id="section_nuevos_bloques"></section> <!-- #section_nuevos_bloques -->

                    <div class="row mt-2 p-4 div-new_bloque" style="display: none;">
                        <button id="add_new_bloque" onclick="addNewQuestion()">Agregar Nuevo Bloque</button>
                    </div>



                    <!-- <div class="row mt-2 p-3">
                        <div>
                            <h6 class="subt_question mb-3">Ejemplo Título</h6>
                            <div class="section_questions" id="section_questions_2">
                                <article class="d-flex mb-2">
                                    <input type="text" class="form-control sz_inp inp_pregunta" value="¿Lorem ipsum dolor sit amet consectetur adipisicing elit. Numquam, quidem??" disabled>
                                    <button class="ms-3 btn_del_question" data-id="3" onclick="deleteQuestion(this)"><i class="fa-solid fa-trash"></i></button>
                                </article>
                                <article class="d-flex mb-2">
                                    <input type="text" class="form-control sz_inp inp_pregunta" value="¿Lorem ipsum dolor sit amet consectetur adipisicing elit. Numquam, quidem??" disabled>
                                    <button class="ms-3 btn_del_question" data-id="4" onclick="deleteQuestion(this)"><i class="fa-solid fa-trash"></i></button>
                                </article>
                            </div> 
                            <div class="justify-content-center mt-3 add_question">
                                <button class="btn_add_question" data-id="2"><i class="fa-solid fa-circle-plus"></i></button>
                            </div>
                        </div>
                    </div> -->
                </div>
            </div>
        </div>

        <div class="d-flex justify-content-between">
            <a href="<?= base_url('auditorias/planillas') ?>" class="btn_modify" style="text-decoration: none;">Volver</a>
            <div id="visible_btn_submit">
                <input type="submit" class="btn_modify" id="btn_submit" value="Cargar Actualización">
            </div>
        </div>
    </form>
</section>


<!-- Modal Alerta para la Habilitación de Edición -->
<div class="modal fade" id="modal_habilitacion_edicion" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header p-2">
                <h6 class="m-0 p-1">Modal de Edición Habilitado</h6>
            </div>
            <div class="modal-body text-center">

                <span class="alert_important"><i class="fa-solid fa-circle-exclamation"></i> ¡Importante!</span>

                <div class="content_modal">
                    <p>Se informa que el modo de edición se encuentra habilitado, lo que significa que cualquier cambio realizado será registrado como una nueva revisión.</p>
                    <p class="mb-0"><b>¡Recuerde guardar los cambios al final de la planilla!</b></p>
                </div>

            </div>
            <div class="modal-footer">
                <button type="button" class="btn_modify btn_modal" data-bs-dismiss="modal">Entiendo</button>
            </div>
        </div>
    </div>
</div>

<script>
    var section_aux = <?= json_encode($aux); ?>; // Básicamente es un contador (Cuenta los que vienen de BD y luego se suman acá)
</script>
<script src="<?= base_url('/assets/js/auditorias/edicion.js') ?>"></script>
