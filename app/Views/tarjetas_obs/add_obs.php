<link href="<?= base_url() ?>/assets/css/tarjetaObs/add.css" rel="stylesheet">
<link href="<?= base_url() ?>/assets/css/tarjetaObs/drawAndDrop.css" rel="stylesheet">
<link href="<?= base_url() ?>/assets/css/tarjetaObs/modal_riesgo.css" rel="stylesheet">
<link href="<?= base_url() ?>/assets/css/tarjetaObs/checkAnimado.css" rel="stylesheet">
<link href="<?= base_url() ?>/assets/css/virtual-select.min.css" rel="stylesheet">
<link href="<?= base_url() ?>/assets/css/addFiles.css" rel="stylesheet">
<link href="<?= base_url() ?>/assets/css/fileAdder.css" rel="stylesheet">
<title>OLDELVAL - Tarjeta M.A.S</title>
<div class="container">
    <div class="row">
        <div class="blister-title-container">
            <h4 class="blister-title">Tarjetas M.A.S</h4>
        </div>
    </div>
</div>
<div class="container">
    <div class="row">
        <div class="card card_custom">
            <div class="card-header card_header_custom">
                Carga de Observación
            </div>

            <div class="row mt-2 p-2">
                <form id="form_submit" class="needs-validation" enctype="multipart/form-data" novalidate>
                    <div class="col-xs-12">
                        <p class="subtitle_test">Datos Generales</p>

                        <div class="row mt-2">
                            <div class="col-xs-12 col-md-4">
                                <div>
                                    <label for="contratista" class="sz_inp fw-semibold" style="margin-bottom: 5.1px;">Seleccione la Empresa Observada</label>
                                    <select class="sz_inp" name="contratista" id="contratista" style="width: 100%" name="native-select" data-search="true" data-silent-initial-value-set="true">
                                        <?php
                                        foreach ($contratistas as $e) {
                                            echo  "<option value='" . $e['id'] . "'>" . $e['nombre'] . "</option>";
                                        }
                                        ?>
                                    </select>
                                </div>
                            </div>
                            <div class="col-xs-12 col-md-4">
                                <label for="" class="mb-1 fw-semibold sz_inp">Fecha de Detección <small>(*)</small></label>
                                <input type="date" name="fecha_deteccion" id="fecha_deteccion" class="form-control sz_inp inp_custom" max="<?= date('Y-m-d') ?>" required>
                                <div class="valid-feedback"></div>
                                <div class="invalid-feedback">
                                    La fecha de detección es requerida
                                </div>
                            </div>
                            <div class="col-xs-12 col-md-4">
                                <label for="" class="mb-1 fw-semibold sz_inp">Observador</label>
                                <input type="text" name="observador" id="observador" class="form-control sz_inp simulate_dis inp_custom" placeholder="Observador (Opcional)" value="<?= $this->session->get('nombrecompleto'); ?>" readonly>
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
                                <textarea class="form-control sz_inp inp_custom" name="descripcion" id="descripcion" cols="10" rows="3" placeholder="Breve descripción del acontecimiento (opcional)"></textarea>
                            </div>
                        </div>

                        <br>

                        <div class="row">
                            <div class="col-xs-12 col-md-6">
                                <label for="" class="mb-1 fw-semibold sz_inp">Proyectos <small>(*)</small></label>
                                <select name="proyecto" id="proyecto" class="form-select sz_inp inp_custom" onchange="filtrarModulos(this)" required>
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
                                    <select name="modulo" id="modulo" class="form-select sz_inp inp_custom" disabled required>
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
                                    <select name="estacion_bombeo" id="estacion_bombeo" class="form-select sz_inp inp_custom">
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
                                    <select name="sistema_oleoducto" id="sistema_oleoducto" class="form-select sz_inp inp_custom">
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

                        <p class="subtitle_test">Guía de Detección</p>
                        <div style="width: 90%; margin: 0 auto;">
                            <?php $i = 1;
                            foreach ($indicadores as $ind) : ?>
                                <div>
                                    <div class="row text-center p-2 mt-2 align-items-center">
                                        <div class="col-xs-12 col-md-7" style="text-align: start;">
                                            <div>
                                                <p class="m-0"><small><em><b>(<?= $i; ?>)</b></em></small> <b><?= $ind['nombre']; ?></b> <?= $ind['descripcion']; ?></p>
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

                        <!-- == Acá comienzan los planes de acción (Vista a parte) == -->

                        <section id="contenedor_observaciones"></section>

                        <div class="row mt-4" id="terminos_observacion">
                            <div class="col-xs-12 col-md-12 text-center">
                                <p class="border_obs fw-semibold">En este apartado puede abordar múltiples observaciones, ya sean sugerencias de optimización o reconocimientos positivos.</p>

                                <div class="d-flex justify-content-center">
                                    <button class="btn_modify btn_tipo_obs" id="label_tipo_positivo" for="tipo_positivo" style="margin-right: 2px; width: 100%; max-width: 250px; background-color: #509d50;" onclick="generarObservacionPositiva('contenedor_observaciones')"><i class="fa-solid fa-plus"></i> Observación Positiva</button>
                                  
                                    <button class="btn_modify btn_tipo_obs" id="label_oportunidad_mejora" for="oportunidad_mejora" onclick="generarObservacionMejora('contenedor_observaciones')"><i class="fa-solid fa-plus"></i> Observación con Mejora</button>
                                </div>
                            </div>
                        </div>

                        <!-- Modal Significancia -->
                        <!-- <!?php generarModalSignificancia(); ?> -->

                        <br>

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
    let efectos = <?= json_encode($efectos); ?>;
    let significancia = <?= json_encode($significancia); ?>;
    var aux = 1;
    var contador = 1;
    var responsables_contratista = [];
</script>

<script src="<?= base_url() ?>/assets/js/virtual-select.min.js"></script>
<script src="<?= base_url() ?>/assets/js/addFiles.js"></script>
<script src="<?= base_url() ?>/assets/js/tarjetaObs/add.js"></script>

<script>
    document.querySelector('#contratista').addEventListener('change', function() {
        let id_contratista = this.value;
        responsables_contratista = responsable.filter((e) => e.empresa == id_contratista);

        let selectorResponsables = document.querySelectorAll('.responsable');
        let selectorResponsablesRelevo = document.querySelectorAll('.relevo_responsable');

        // * Selector de responsables (Se resetea cuando escucha un cambio en el selector de contratista)
        selectorResponsables.forEach(e => {
            let options = [];
            responsables_contratista.forEach(r => {
                let objeto = {
                    label: r.nombre + ' ' + r.apellido,
                    value: r.id
                }
                options.push(objeto);
            });
            document.querySelector('#'+e.getAttribute('id')).setOptions(options);
        });

        // * Selector de relevo de responsables (Se resetea cuando escucha un cambio en el selector de contratista)
        selectorResponsablesRelevo.forEach(e => {
            let options = [];
            responsables_contratista.forEach(r => {
                let objeto = {
                    label: r.nombre + ' ' + r.apellido,
                    value: r.id
                }
                options.push(objeto);
            });
            document.querySelector('#'+e.getAttribute('id')).setOptions(options);
        });
    })
</script>
<script src="<?= base_url() ?>/assets/js/tarjetaObs/submit.js"></script>
<script src="<?= base_url() ?>/assets/js/tarjetaObs/add_plan_accion.js"></script>
<script src="<?= base_url() ?>/assets/js/tarjetaObs/add_obs_positiva.js"></script>