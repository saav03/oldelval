<link href="<?= base_url() ?>/assets/css/tarjetaObs/add.css" rel="stylesheet">
<link href="<?= base_url() ?>/assets/css/tarjetaObs/drawAndDrop.css" rel="stylesheet">
<link href="<?= base_url() ?>/assets/css/tarjetaObs/modal_riesgo.css" rel="stylesheet">
<link href="<?= base_url() ?>/assets/css/tarjetaObs/checkAnimado.css" rel="stylesheet">

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

            <div class="row">
                <form id="form_submit" class="needs-validation" enctype="multipart/form-data" novalidate>
                    <div class="col-xs-12">
                        <p class="subtitle">Datos Generales</p>

                        <div class="row">
                            <div class="col-xs-12 col-md-4">
                                <label for="" class="mb-1 fw-semibold sz_inp">Fecha de Detección <small>(*)</small></label>
                                <input type="date" name="fecha_deteccion" id="fecha_deteccion" class="form-control sz_inp" max="<?= date('Y-m-d') ?>" required>
                                <div class="valid-feedback"></div>
                                <div class="invalid-feedback">
                                    La fecha de detección es requerida
                                </div>
                            </div>
                            <div class="col-xs-12 col-md-2">
                                <label for="" class="mb-1 fw-semibold sz_inp">Tipo de Observación <small>(*)</small></label>
                                <select name="tipo_obs" id="tipo_obs" onchange="listenTipoObs(this)" class="form-select sz_inp" required>
                                    <option value="">-- Seleccione --</option>
                                    <option value="1">Positiva</option>
                                    <option value="2">Oportunidad de Mejora</option>
                                </select>
                                <div class="valid-feedback"></div>
                                <div class="invalid-feedback">
                                    El tipo de observación es requerido
                                </div>
                            </div>
                            <div class="col-xs-12 col-md-2">
                                <label for="" class="mb-1 fw-semibold sz_inp">Situación Observación <small>(*)</small></label>
                                <select name="situacion" id="situacion" class="form-select sz_inp" required>
                                    <option value="1">Abierta</option>
                                    <option value="0">Cerrada</option>
                                </select>
                            </div>
                            <div class="col-xs-12 col-md-4">
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
                            <!--
                            <div class="row d-flex justify-content-end">
                                <div class="mt-2">
                                    <div class="row d-flex justify-content-end observadores_inps mt-2">
                                        <div class="col-xs-12 col-md-4 d-flex">
                                            <button class="obs-btn_trash"><i class="fa-solid fa-trash"></i></button>
                                            <input type="text" class="form-control sz_inp" value="Luciano Colombo">
                                        </div>
                                    </div>

                                    <div class="row d-flex justify-content-end observadores_inps mt-2">
                                        <div class="col-xs-12 col-md-4 d-flex">
                                            <button class="obs-btn_trash"><i class="fa-solid fa-trash"></i></button>
                                            <input type="text" class="form-control sz_inp" value="Luciano Colombo">
                                        </div>
                                    </div>
                                </div>
                            </div> -->
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
                        <?php $i = 1;
                        foreach ($indicadores as $ind) : ?>
                            <div class="d-flex justify-content-between align-items-center pb-2 pt-2" style="width: 90%;margin: 0 auto; border-bottom: 1px solid lightgray; font-size: 13px;">
                                <div>
                                    <p class="m-0"><small><em><b>(<?= $i; ?>)</b></em></small> <?= $ind['nombre']; ?></p>
                                </div>
                                <div class="btn-group btn-group-toggle" role="group" aria-label="Basic radio toggle button group">
                                    <input type="radio" class="btn-check verde_check" name="btn_indicador[<?= $ind['id'] ?>]" value="1" id="btn_bien_<?= $ind['id'] ?>" autocomplete="off">
                                    <label class="btn verde" for="btn_bien_<?= $ind['id'] ?>">Bien</label>

                                    <input type="radio" class="btn-check rojo_check" name="btn_indicador[<?= $ind['id'] ?>]" value="0" id="btn_mal_<?= $ind['id'] ?>" autocomplete="off">
                                    <label class="btn rojo" for="btn_mal_<?= $ind['id'] ?>">Mal</label>

                                    <input type="radio" class="btn-check amarillo_checked" name="btn_indicador[<?= $ind['id'] ?>]" value="-1" id="btn_na_<?= $ind['id'] ?>" autocomplete="off" checked>
                                    <label class="btn amarillo" for="btn_na_<?= $ind['id'] ?>">N/A</label>
                                </div>
                            </div>
                        <?php $i++;
                        endforeach; ?>

                        <br>
                        <br>

                        <!-- == Acá comienzan los planes de acción (Vista a parte) == -->

                        <div class="row" id="btns_add_plan_accion" style="display: none;">
                            <div class="col-xs-12 col-md-4 text-center">
                                <p class="border_obs">La observación actual ¿Posee acción inmediata?</p>
                                <div class="d-flex justify-content-center">
                                    <button class="btn_modify" id="btn_yes" style="margin-right: 2px;">Si</button>
                                    <button class="btn_modify" id="btn_no" style="margin-left: 2px;">No</button>
                                </div>
                            </div>
                        </div>

                        <!-- Modal Matriz de Riesgo -->
                        <!-- <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#exampleModal">
                            Launch demo modal
                        </button>

                        <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                            <div class="modal-dialog modal-xl">
                                <div class="modal-content">

                                    <div class="modal-header">
                                        <div class="d-flex align-items-center">
                                            <h1 class="modal-title fs-5" id="exampleModalLabel" style="border-right: 2px solid #d6dbe2; padding-right: 15px; width: 21%; color: #475569;">Gestión de Riesgo</h1>
                                            <p class="mb-0" style="margin-left: 20px;">
                                                Se apoya en el concepto de ser una fuente fidedigna de información sobre la cual tomar decisiones, priorizar acciones y definir los cursos de acción de la organización.
                                            </p>
                                        </div>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                    </div>

                                    <div class="modal-body">
                                        <div id="carouselExampleControls" class="carousel slide" data-bs-ride="false" style="position: relative;">
                                            <div class="carousel-inner">
                                                <div class="carousel-item active">
                                                    <div style="margin: 0 auto;">
                                                        <!?= view('tarjetas_obs/matriz_riesgo/tabla_impacto') ?>
                                                    </div>
                                                </div>
                                                <div class="carousel-item">
                                                    <div style="margin: 0 auto;">
                                                        hola
                                                    </div>
                                                </div>
                                                <div class="carousel-item">
                                                    <div style="margin: 0 auto;">
                                                        hola
                                                    </div>
                                                </div>
                                            </div>
                                            <button class="carousel-control-prev" type="button" data-bs-target="#carouselExampleControls" data-bs-slide="prev" style="width: 5%; border-radius: 10px 0 0 10px; height: 205px; margin: auto 0; position: absolute; left: -71px;">
                                                <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                                                <span class="visually-hidden">Previous</span>
                                            </button>
                                            <button class="carousel-control-next" type="button" data-bs-target="#carouselExampleControls" data-bs-slide="next" style="width: 5%; border-radius: 0 10px 10px 0; height: 205px; margin: auto 0; position: absolute; right: -71px;">
                                                <span class="carousel-control-next-icon" aria-hidden="true"></span>
                                                <span class="visually-hidden">Next</span>
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div> -->

                        <div class="row" id="btns_obs_positive" style="display: none;">
                            <div class="col-xs-12 col-md-4 text-center">
                                <p class="border_obs">La observación actual ¿Posee una hallazgo positivo?</p>
                                <div class="d-flex justify-content-center">
                                    <button class="btn_modify" id="btn_yes_positive" style="margin-right: 2px;">Si</button>
                                    <button class="btn_modify" id="btn_no_positive" style="margin-left: 2px;">No</button>
                                </div>
                            </div>
                        </div>

                        <input type="hidden" name="posee_obs" id="posee_obs" value="0"> <!-- Que esté en 0 significa que no hay (por defecto) -->

                        <br>

                        <!-- Todos los Planes de acción  -->

                        <section id="section_plan_accion">
                        </section>

                        <section id="section_obs_positiva">
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

<script src="<?= base_url() ?>/assets/js/tarjetaObs/modal_riesgo.js"></script>
<script src="<?= base_url() ?>/assets/js/tarjetaObs/fileDropAdder.js"></script>
<script src="<?= base_url() ?>/assets/js/tarjetaObs/fileDropAdderPositive.js"></script>
<script src="<?= base_url() ?>/assets/js/tarjetaObs/add.js"></script>
<script src="<?= base_url() ?>/assets/js/tarjetaObs/submit.js"></script>
<script src="<?= base_url() ?>/assets/js/tarjetaObs/add_plan_accion.js"></script>
<script src="<?= base_url() ?>/assets/js/tarjetaObs/add_obs_positiva.js"></script>
<!-- <script src="<?= base_url() ?>/assets/js/tarjetaObs/drawAndDrop.js"></script> -->