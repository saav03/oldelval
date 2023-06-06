<link href="<?= base_url() ?>/assets/css/tarjetaObs/add.css" rel="stylesheet">
<link href="<?= base_url() ?>/assets/css/tarjetaObs/drawAndDrop.css" rel="stylesheet">
<link href="<?= base_url() ?>/assets/css/tarjetaObs/modal_riesgo.css" rel="stylesheet">
<link href="<?= base_url() ?>/assets/css/tarjetaObs/checkAnimado.css" rel="stylesheet">
<link href="<?= base_url() ?>/assets/css/virtual-select.min.css" rel="stylesheet">
<link href="<?= base_url() ?>/assets/css/addFiles.css" rel="stylesheet">
<link href="<?= base_url() ?>/assets/css/fileAdder.css" rel="stylesheet">

<div class="container">
    <div class="row">
        <div class="blister-title-container">
            <h4 class="blister-title">Tarjetas de Observaciones</h4>
        </div>
    </div>
</div>
<div class="container">
    <div class="row">
        <div class="card">
            <div class="card-header" style="background: white; padding: 16px; font-weight: 600; letter-spacing: 1.5px;">
                Carga de Observación
            </div>

            <div class="row mt-2">
                <form id="form_submit" class="needs-validation" enctype="multipart/form-data" novalidate>
                    <div class="col-xs-12">
                        <p class="subtitle">Datos Generales</p>

                        <div class="row mt-2">
                            <div class="col-xs-12 col-md-4">
                                <div>
                                    <label for="contratista" class="sz_inp fw-semibold" style="margin-bottom: 5.1px;">Seleccione la Contratista</label>
                                    <select class="sz_inp" name="contratista" id="contratista" style="width: 100%" name="native-select" data-search="true" data-silent-initial-value-set="true">
                                        <?php
                                        foreach ($contratistas as $e) {
                                            echo  "<option value='" . $e['id'] . "'>" . $e['nombre'] . "</option>";
                                        }
                                        ?>
                                    </select>
                                </div>
                            </div>
                            <div class="col-xs-12 col-md-2">
                                <label for="" class="mb-1 fw-semibold sz_inp">Fecha de Detección <small>(*)</small></label>
                                <input type="date" name="fecha_deteccion" id="fecha_deteccion" class="form-control sz_inp" max="<?= date('Y-m-d') ?>" required>
                                <div class="valid-feedback"></div>
                                <div class="invalid-feedback">
                                    La fecha de detección es requerida
                                </div>
                            </div>

                            <div class="col-xs-12 col-md-3 text-center">
                                <label for="" class="mb-1 fw-semibold sz_inp text-center">Situación de la Observación <small>(*)</small></label>
                                <div class="btn-group btn-group-toggle" style="width: 80%;" role="group" aria-label="">
                                    <input id="abierta" type="radio" name="situacion" class="btn-check blanco_check" value="1" autocomplete="off">
                                    <label class="btn blanco btnsToggle" for="abierta">Abierta</label>

                                    <input id="cerrada" type="radio" name="situacion" class="btn-check blanco_check" value="0" autocomplete="off">
                                    <label class="btn blanco btnsToggle" for="cerrada">Cerrada</label>
                                </div>
                            </div>
                            <div class="col-xs-12 col-md-3">
                                <label for="" class="mb-1 fw-semibold sz_inp">Observador</label>

                                <input type="text" name="observador" id="observador" class="form-control sz_inp simulate_dis" placeholder="Observador (Opcional)" value="<?= $this->session->get('nombrecompleto'); ?>" readonly>
                            </div>
                        </div>

                        <section id="seccion_observadores">
                            <div class="row text-center mt-4 obs-new_title" style="display: none;">
                                <h6 class="fw-semibold">Nuevos Observadores</h6>
                            </div>
                            <div class="row d-flex justify-content-end">
                                <div class="mt-2" id="container_observadores"></div>
                            </div>

                        </section>

                        <div class="row d-flex justify-content-end">
                            <div class="col-xs-12 col-md-4 d-flex justify-content-end mt-2">
                                <button id="btn_add_obs">¿Desea agregar un observador? <i class="fa-solid fa-circle-plus"></i></button>
                            </div>
                        </div>

                        <br>

                        <div class="row">
                            <div class="col-xs-12">
                                <label for="" class="mb-1 fw-semibold sz_inp">Tarea Observada <span><small>(Texto Libre)</small></span></label>
                                <textarea class="form-control sz_inp" name="descripcion" id="descripcion" cols="10" rows="3" placeholder="Breve descripción del acontecimiento (opcional)"></textarea>
                            </div>
                        </div>

                        <br>

                        <div class="row">
                            <div class="col-xs-12 col-md-6">
                                <label for="" class="mb-1 fw-semibold sz_inp">Proyectos <small>(*)</small></label>
                                <select name="proyecto" id="proyecto" class="form-select sz_inp" onchange="filtrarModulos(this)" required>
                                    <option value="">-- Seleccione --</option>
                                    <?php foreach ($proyectos as $p) : ?>
                                        <option value="<?= $p['id'] ?>"><?= $p['nombre'] ?></option>
                                    <?php endforeach; ?>
                                </select>
                                <div class="valid-feedback"></div>
                                <div class="invalid-feedback">
                                    El proyecto es requerido
                                </div>
                            </div>

                            <div class="col-xs-12 col-md-6">
                                <label for="" class="mb-1 fw-semibold sz_inp">Modulos</label>
                                <div id="selector_modulos_div">
                                    <select name="modulo" id="modulo" class="form-select sz_inp" disabled required>
                                        <option value="">-- Seleccione --</option>
                                    </select>
                                    <div class="valid-feedback"></div>
                                    <div class="invalid-feedback">
                                        El módulo es requerido
                                    </div>
                                </div>
                            </div>

                            <div class="col-xs-12 col-md-6">
                                <label for="" class="mb-1 mt-3 fw-semibold sz_inp">Estaciones de Bombeo</label>
                                <div id="selector_estaciones_div">
                                    <select name="estacion_bombeo" id="estacion_bombeo" class="form-select sz_inp">
                                        <option value="">-- Seleccione --</option>
                                        <?php foreach ($estaciones as $e) : ?>
                                            <option value="<?= $e['id'] ?>"><?= $e['nombre'] ?></option>
                                        <?php endforeach; ?>
                                    </select>
                                    <div class="valid-feedback"></div>
                                    <div class="invalid-feedback">
                                        La estación de bombeo es requerida
                                    </div>
                                </div>

                            </div>

                            <div class="col-xs-12 col-md-6">
                                <label for="" class="mb-1 mt-3 fw-semibold sz_inp">Sistema de Oleoductos</label>
                                <div id="selector_sistemas_div">
                                    <select name="sistema_oleoducto" id="sistema_oleoducto" class="form-select sz_inp">
                                        <option value="">-- Seleccione --</option>
                                        <?php foreach ($sistemas as $s) : ?>
                                            <option value="<?= $s['id'] ?>"><?= $s['nombre'] ?></option>
                                        <?php endforeach; ?>
                                    </select>
                                    <div class="valid-feedback"></div>
                                    <div class="invalid-feedback">
                                        El sistema de oleoducto es requerido
                                    </div>
                                </div>
                            </div>

                        </div>

                        <br>

                        <p class="subtitle">Guía de Detección</p>
                        <div style="width: 90%; margin: 0 auto;">
                            <?php $i = 1;
                            foreach ($indicadores as $ind) : ?>
                                <div>
                                    <div class="row text-center p-2 mt-2 align-items-center">
                                        <div class="col-xs-12 col-md-7" style="text-align: start;">
                                            <div>
                                                <p class="m-0"><small><em><b>(<?= $i; ?>)</b></em></small> <b><?= $ind['nombre']; ?></b>  <?= $ind['descripcion']; ?></p>
                                            </div>
                                        </div>
                                        <div class="col-xs-12 col-md-3 text-center">
                                            <div class="btn-group btn-group-toggle" role="group" aria-label="Basic radio toggle button group">
                                                <input type="radio" class="btn-check verde_check" name="guia_deteccion[<?= $ind['id'] ?>][rta]" value="1" id="btn_bien_<?= $ind['id'] ?>" autocomplete="off">
                                                <label class="btn verde" for="btn_bien_<?= $ind['id'] ?>">Bien</label>

                                                <input type="radio" class="btn-check rojo_check" name="guia_deteccion[<?= $ind['id'] ?>][rta]" value="0" id="btn_mal_<?= $ind['id'] ?>" autocomplete="off">
                                                <label class="btn rojo" for="btn_mal_<?= $ind['id'] ?>">Mal</label>

                                                <input type="radio" class="btn-check amarillo_checked" name="guia_deteccion[<?= $ind['id'] ?>][rta]" value="-1" id="btn_na_<?= $ind['id'] ?>" autocomplete="off" checked>
                                                <label class="btn amarillo" for="btn_na_<?= $ind['id'] ?>">N/A</label>
                                            </div>
                                        </div>
                                        <div class="col-xs-12 col-md-2" style="text-align: end!important;">
                                            <div class="div-ind_icono">
                                                <small data-bs-toggle="collapse" data-bs-target="#collapse_guia<?= $ind['id'] ?>" aria-expanded="false" aria-controls="collapse_guia<?= $ind['id'] ?>">
                                                    Comentario
                                                    <i class="fas fa-comment"></i>
                                                </small>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="collapse" id="collapse_guia<?= $ind['id'] ?>">
                                        <div class="form-floating">
                                            <textarea class="form-control" name="guia_deteccion[<?= $ind['id'] ?>][comentario]" id="textarea" rows="2"></textarea>
                                            <label class="mb-1 fw-semibold" for="textarea">Nota:</label>
                                        </div>
                                    </div>
                                </div>
                            <?php $i++;
                            endforeach; ?>
                        </div>

                        <br>
                        <br>

                        <!-- == Acá comienzan los planes de acción (Vista a parte) == -->

                        <div class="row" id="terminos_observacion">
                            <div class="col-xs-12 col-md-12 text-center">
                                <p class="border_obs fw-semibold">¿Cómo evaluaría la observación actual en términos positivos / oportunidades de mejora?</p>
                                <div id="alerta_obs_estado" class="mb-2 fw-semibold" style="letter-spacing: .4px; color: #9A4D4D;">
                                    <span><small>Primero debe seleccionar si el estado de la observación es abierta o cerrada</small></span>
                                </div>
                                <div class="d-flex justify-content-center">
                                    <label class="btn_modify btn_tipo_obs" id="label_tipo_positivo" disabled for="tipo_positivo" style="margin-right: 2px; width: 100%; max-width: 200px;">Positivo</label>
                                    <input type="radio" name="tipo_observacion" disabled id="tipo_positivo" value="1" hidden>
                                    <label class="btn_modify btn_tipo_obs" id="label_oportunidad_mejora" disabled for="oportunidad_mejora" style="margin-left: 2px; width: 100%;  max-width: 200px;">Oportunidad de Mejora</label>
                                    <input type="radio" name="tipo_observacion" disabled id="oportunidad_mejora" value="2" hidden>
                                </div>
                            </div>
                        </div>

                        <div class="row mt-3">
                            <div class="col-xs-12 col-md-6">
                                <fieldset style="border-right: none;">
                                    <legend class="w-100">
                                        Efecto / Impacto
                                    </legend>
                                    <div class="p-3 pt-1">
                                        <label for="efecto_impacto" class="mb-2 sz_inp fw-semibold">Seleccione el efecto o impacto</label>
                                        <select class="sz_inp rounded-select" name="efecto_impacto[]" id="efecto_impacto" style="width: 100%" multiple name="native-select" data-search="true" data-silent-initial-value-set="true">
                                            <?php
                                            foreach ($efectos as $e) {
                                                echo  "<option value='" . $e['id'] . "'>" . $e['nombre'] . "</option>";
                                            }
                                            ?>
                                        </select>
                                    </div>
                                </fieldset>
                            </div>
                            <div class="col-xs-12 col-md-6">
                                <fieldset style="border-right: none;">
                                    <legend class="w-100 d-flex align-items-center">
                                        Riesgos Observados
                                        <div class="contain-question_icon" data-bs-toggle="modal" data-bs-target="#modal_significancia">
                                            <div class="question-icon">
                                                <span>?</span>
                                            </div>
                                        </div>
                                    </legend>
                                    <div class="text-center" style="padding: 19px 0!important;">
                                        <div class="btn-group btn-group-toggle" style="width: 80%; margin-top: 3px;" role="group" aria-label="">
                                            <input id="aceptable" disabled type="checkbox" name="significancia[]" class="btn-check btn_check_significancia blanco_check" value="1" autocomplete="off">
                                            <label class="btn blanco btnsToggle riesgos" for="aceptable">Aceptable</label>

                                            <input id="moderado" disabled type="checkbox" name="significancia[]" class="btn-check btn_check_significancia verde_check" value="2" autocomplete="off">
                                            <label class="btn verde btnsToggle riesgos" for="moderado">Moderado</label>

                                            <input id="significativo" disabled type="checkbox" name="significancia[]" class="btn-check btn_check_significancia amarillo_checked" value="3" autocomplete="off">
                                            <label class="btn amarillo btnsToggle riesgos" for="significativo">Significativo</label>

                                            <input id="intolerable" disabled type="checkbox" name="significancia[]" class="btn-check btn_check_significancia rojo_check" value="4" autocomplete="off">
                                            <label class="btn rojo btnsToggle riesgos" for="intolerable">Intolerable</label>
                                        </div>
                                    </div>
                                </fieldset>
                            </div>
                        </div>

                        <!-- Modal Significancia -->
                        <div class="modal fade" id="modal_significancia" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                            <div class="modal-dialog modal-lg">
                                <div class="modal-content">
                                    <div class="modal-header d-flex align-items-center justify-content-between">
                                        <p class="p-0 m-0 fw-semibold">Descripción de Riesgos (Significancia)</p>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                    </div>
                                    <div class="modal-body">
                                        <table width="100%" style="border: 2px solid #dfdfdf;">
                                            <thead>
                                                <th style="width: 30%; border: 2px solid #dfdfdf; padding: 3px;">Significancia</th>
                                                <th class="text-center" style="width: 70%; border: 2px solid #dfdfdf; padding: 3px;"">Descripción</th>
                                            </thead>

                                            <tbody>
                                                <tr style=" border-bottom: 1px solid #f1f1f1;">
                                                <td style="background-color: #f9f9f9; font-weight: bold; text-align: center; letter-spacing: 1px; border-radius: 0; border-right: 2px solid #dfdfdf;">Aceptable</td>
                                                <td>
                                                    <b>No afecta o afecta levemente. </b><br>
                                                    Lesión menor resuelta a través de primeros auxilios (Ej: lesiones superficiales, cortes leves, irritación ocular por polvo, magulladuras pequeñas). Sin perdidas o daños materiales
                                                </td>
                                                </tr>
                                                <tr style="border-bottom: 1px solid #f1f1f1;">
                                                    <td style="background-color: #d9ffd9; font-weight: bold; text-align: center; letter-spacing: 1px; border-radius: 0; border-right: 2px solid #dfdfdf;">Moderado</td>
                                                    <td>
                                                        <b>Afecta con consecuencias que son reversibles.</b><br>
                                                        Lesión con pérdida de días o restricción de tareas (Ej.: esguinces, fracturas planas, quemaduras menores) Pérdidas o daños materiales bajos
                                                    </td>
                                                </tr>
                                                <tr style="border-bottom: 1px solid #f1f1f1;">
                                                    <td style="background-color: #fffcd9; font-weight: bold; text-align: center; letter-spacing: 1px; border-radius: 0; border-right: 2px solid #dfdfdf;">Significativo</td>
                                                    <td>
                                                        <b>Afecta con consecuencias que no son reversibles o son reversibles con tratamientos medicos.</b><br>
                                                        Lesión severa con incapacidad laboral permanente total/parcial o que requiere de tratamiento médico de complejidad (Ej.: enfermedades profesionales, quemaduras con tratamientos complejos, fracturas expuestas, amputaciones, etc.) Pérdidas o daños materiales de escala apreciable.
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td style="background-color: #f5b4b4; font-weight: bold; text-align: center; letter-spacing: 1px; border-radius: 0; border-right: 2px solid #dfdfdf;">Intolerable</td>
                                                    <td>
                                                        <b>Afecta con consecuencias muy graves o que puede desencadenar en muertes.</b><br>
                                                        Muerte de una o más personas, o de algún tercero. Perdida total o reconstrucción de Instalaciones.
                                                    </td>
                                                </tr>
                                                </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="row mt-3" id="generar_reconocimiento" style="display: none;">
                            <div class="col-xs-12 col-md-12 text-center">
                                <p class="border_obs fw-semibold">¿Desea destacar un reconocimiento positivo?</p>
                                <div class="d-flex justify-content-center">
                                    <label class="btn_modify" id="si_ejecutar_reconocimiento" style="margin-right: 2px; width: 100%; max-width: 70px;">Si</label>
                                    <label class="btn_modify" id="no_ejecutar_reconocimiento" style="margin-left: 2px; width: 100%;  max-width: 70px;">No</label>
                                    <input type="hidden" name="destacar_reconocimiento" id="destacar_reconocimiento" value="">
                                </div>
                            </div>
                        </div>

                        <div class="row mt-3" id="obs_cerrada_sin_plan" style="display: none;">
                            <div class="col-xs-12 col-md-12 text-center">
                                <p class="fw-semibold mb-1">La Observación actual se registrará en su estado 'Cerrado'</p>
                                <p class="fw-semibold">Por lo tanto, no habrá plan de acción para su ejecución</p>
                            </div>
                        </div>

                        <br>

                        <!-- Todos los Planes de acción  -->

                        <section id="section_plan_accion" style="display: none;">
                            <?= view('tarjetas_obs/plan_accion') ?>
                        </section>

                        <section id="section_obs_positiva" style="display: none;">
                            <?= view('tarjetas_obs/reconocimiento') ?>
                        </section>

                        <br>

                        <div class="modal fade" id="matrizRiesgoModal" role="dialog">
                            <div class="modal-dialog modal-lg" style="max-width: 1417px;">
                                <div class="modal-content">
                                    <div class="modal-body" style="overflow-x:auto;">
                                        <?= $tabla; ?>
                                    </div>
                                    <input type="hidden" id="currentDesvioRiesgo">
                                    <div class="modal-footer">
                                        <button type="button" class="btn_mod_danger" data-bs-dismiss="modal">Confirmar</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div hidden>
                            <div class='wrapper' id="checkis">
                                <svg class='checkmark' xmlns='http://www.w3.org/2000/svg' viewBox='0 0 52 52'>
                                    <circle class='checkmark__circle' cx='26' cy='26' r='25' fill='none' />
                                    <path class='checkmark__check' fill='none' d='M14.1 27.2l7.1 7.2 16.7-16.8' />
                                </svg>
                            </div>
                        </div>

                    </div>

                    <div class="d-flex justify-content-end">
                        <input type="submit" id="btnSubmit" class="btn_modify" value="Cargar Observación">
                    </div>
                    <br>

                </form>
            </div>
        </div>

    </div>
</div>

<script>
    let estaciones = <?= json_encode($estaciones); ?>;
    let sistemas = <?= json_encode($sistemas); ?>;
    let clasificaciones = <?= json_encode($clasificaciones); ?>;
    let tipo_hallazgo = <?= json_encode($tipo_hallazgo); ?>;
    let contratista = <?= json_encode($contratistas); ?>;
    let responsable = <?= json_encode($responsables); ?>;
</script>

<script src="<?= base_url() ?>/assets/js/addFiles.js"></script>
<script src="<?= base_url() ?>/assets/js/virtual-select.min.js"></script>
<script src="<?= base_url() ?>/assets/js/tarjetaObs/modal_riesgo.js"></script>
<script src="<?= base_url() ?>/assets/js/tarjetaObs/fileDropAdder.js"></script>
<script src="<?= base_url() ?>/assets/js/tarjetaObs/fileDropAdderPositive.js"></script>
<script src="<?= base_url() ?>/assets/js/tarjetaObs/add.js"></script>
<script src="<?= base_url() ?>/assets/js/tarjetaObs/submit.js"></script>
<script src="<?= base_url() ?>/assets/js/tarjetaObs/add_plan_accion.js"></script>
<script src="<?= base_url() ?>/assets/js/tarjetaObs/add_obs_positiva.js"></script>

<script>
    new addFiles(document.getElementById("gallery"), 'adj_observacion').init();
</script>
<script>
    new addFiles(document.getElementById("gallery_reconocimiento"), 'adj_observacion_positive').init();
</script>