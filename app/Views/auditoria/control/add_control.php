<!-- Encabezado -->
<link href="<?= base_url() ?>/assets/css/addFiles.css" rel="stylesheet">
<link href="<?= base_url() ?>/assets/css/fileAdder.css" rel="stylesheet">
<link href="<?= base_url() ?>/assets/css/virtual-select.min.css" rel="stylesheet">

<form id="form_aud_control">

    <input type="hidden" value="1" name="aud_tipo">

    <div class="card" style="border: 1px solid #f6f6f6;box-shadow: 0px 0 30px rgb(179 179 179 / 53%);">
        <div class="card-header subtitle" style="font-weight: 600; letter-spacing: 1.5px; text-align: center; background-color: #f5e1ce!important;">
            Datos Generales
        </div>
        <div class="card-body">

            <div class="row">
                <div class="col-md-3 col-xs-12 mt-2">
                    <label class="mb-1 fw-semibold" for="area">Contratista <small>(*)</small></label>
                    <select name="contratista" id="contratista" class="form-select sz_inp">
                        <option value="">-- Seleccione --</option>
                        <?php foreach ($contratistas as $gral) : ?>
                            <option value="<?= $gral['id']; ?>"><?= $gral['nombre']; ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="col-md-3 col-xs-12 mt-2">
                    <label class="mb-1 fw-semibold">Supervisor Responsable <small>(*)</small></label>
                    <select name="supervisor_responsable" id="supervisor_responsable" class="form-select sz_inp">
                        <option value="">-- Seleccione --</option>
                        <?php foreach ($usuarios as $gral) : ?>
                            <option value="<?= $gral['id']; ?>"><?= $gral['nombre'] . ' ' . $gral['apellido'] ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="col-md-2 col-xs-12">
                    <label class="mb-1 fw-semibold mt-2" for="fecha_hoy">Fecha Carga</label>
                    <input type="date" class="form-control text-center sz_inp simulate_dis" name="fecha_hoy" value="<?= date('Y-m-d') ?>" readonly>
                </div>
                <div class="col-xs-12 col-md-2 mt-2">
                    <label class="mb-1 fw-semibold">Cantidad del Personal</label>
                    <input type="number" name="cant_personal" class="form-control sz_inp" placeholder="Ingrese la cantidad">
                </div>
                <div class="col-xs-12 col-md-2 mt-2">
                    <label class="mb-1 fw-semibold">N°Informe</label>
                    <input type="text" name="num_informe" class="form-control sz_inp" placeholder="#">
                </div>
                <div class="col-md-3 col-xs-12 mt-2">
                    <label class="mb-1 fw-semibold" for="area">Proyectos <small>(*)</small></label>
                    <select name="proyecto" id="proyecto" class="form-select sz_inp" onchange="filtrarModulos(this)">
                        <option value="">-- Seleccione --</option>
                        <?php foreach ($proyectos as $gral) : ?>
                            <option value="<?= $gral['id']; ?>"><?= $gral['nombre']; ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="col-md-3 col-xs-12 mt-2">
                    <label class="mb-1 fw-semibold" for="area">Módulos</label>
                    <div id="selector_modulos_div">
                        <select name="modulo" id="modulo" class="form-select sz_inp" required>
                            <option value="-1">-- No Aplica --</option>
                        </select>
                    </div>
                </div>
                <div class="col-md-3 col-xs-12 mt-2">
                    <label class="mb-1 fw-semibold" for="area">Estación de Bombeo</label>
                    <div id="selector_estaciones_div">
                        <select name="estacion_bombeo" id="estacion_bombeo" class="form-select sz_inp" onchange="validacionEstaciones(this)">
                            <option value="">-- No Aplica --</option>
                            <?php foreach ($estaciones as $gral) : ?>
                                <option value="<?= $gral['id']; ?>"><?= $gral['nombre']; ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>
                <div class="col-md-3 col-xs-12 mt-2">
                    <label class="mb-1 fw-semibold" for="area">Sistema de Oleoductos</label>
                    <div id="selector_sistemas_div">
                        <select name="sistema" id="sistema" class="form-select sz_inp" onchange="validacionSistemas(this)">
                            <option value="">-- No Aplica --</option>
                            <?php foreach ($sistemas as $gral) : ?>
                                <option value="<?= $gral['id']; ?>"><?= $gral['nombre']; ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="card" style="border: 1px solid #f6f6f6;box-shadow: 0px 0 30px rgb(179 179 179 / 53%);">
        <div class="card-header subtitle" style="font-weight: 600; letter-spacing: 1.5px; text-align: center; background-color: #f5e1ce!important;">
            Auditoría
        </div>
        <div class="card-body">
            <hr style="color: #bbb;">
            <div class="row">
                <div class="col-xs-12 col-md-4">
                    <div class="form-group">
                        <label class="sz_inp mb-1 fw-semibold">Ingrese el Tipo de Auditoría</label>
                        <select name="tipo_auditoria" id="tipo_auditoria" class="form-select sz_inp" onchange="getBloqueAuditorias(this)">
                            <option value="">-- Seleccione --</option>
                            <?php foreach ($auditorias_control as $aud) : ?>
                                <option value="<?= $aud['id']; ?>"><?= $aud['nombre']; ?></option>
                            <?php endforeach ?>
                        </select>
                    </div>
                </div>
            </div>
            <hr style="color: #bbb;">

            <div class="row">
                <h5 class="text-center" style="font-size: 17px;" id="text-aud_title"><em>Seleccione una Auditoría para visualizar las preguntas</em></h5>
            </div>

            <div id="bloque_respuestas_container"></div>

            <br>
        </div>
    </div>

    <div class="card" style="border: 1px solid #f6f6f6;box-shadow: 0px 0 30px rgb(179 179 179 / 53%);">
        <div class="card-header subtitle" style="font-weight: 600; letter-spacing: 1.5px; text-align: center; background-color: #f5e1ce!important;">
            Observación
        </div>
        <div class="card-body">
            <?= view('auditoria/control/plan_accion_control'); ?>
        </div>
    </div>

    <div class="d-inline-block float-end mb-3 mt-3">
        <button class="btn_modify btnUploadAud" data-id="form_aud_control">Cargar Auditoría</button>
    </div>

</form>

<script>
    /* == Estos son para los selectores dinámicos en el encabezado == */
    let estaciones_estadisticas = <?= json_encode($estaciones); ?>;
    let sistemas_estadisticas = <?= json_encode($sistemas); ?>;
</script>

<script src="<?= base_url() ?>/assets/js/virtual-select.min.js"></script>
<script src="<?= base_url() ?>/assets/js/addFiles.js"></script>
<script src="<?= base_url("assets/js/auditorias/add.js") ?>"></script>
<script src="<?= base_url("assets/js/auditorias/add_control.js") ?>"></script>
<script>
    new addFiles(document.getElementById("gallery"), 'adj_observacion').init();
</script>

<!-- Los selectores que vienen del plugin Virtual Select de JS -->
<script>
    VirtualSelect.init({
        ele: '#efecto_impacto',
        placeholder: 'Seleccione uno o mas efectos',
    });

    VirtualSelect.init({
        ele: '#contratista_plan',
        placeholder: 'Seleccione la contratista',
    });

    VirtualSelect.init({
        ele: '#responsable_plan',
        placeholder: 'Seleccione el responsable',
    });

    VirtualSelect.init({
        ele: '#relevo_responsable_plan',
        placeholder: 'Seleccione el relevo',
    });

    document.getElementById('contratista_plan').setValue(0);
    document.getElementById('responsable_plan').setValue(0);
    document.getElementById('relevo_responsable_plan').setValue(0);
</script>

<!-- Los botones 'Si'/'No' del plan de acción (Si Corresponde) -->
<script>
    const btn_yes = document.getElementById("btn_yes");
    const btn_no = document.getElementById("btn_no");

    const toggle_plan = document.getElementById("toggle_plan");
    const oportunidad_mejora = document.getElementById("oportunidad_mejora");

    btn_yes.addEventListener("click", (e) => {
        e.preventDefault();
        btn_yes.classList.add("btn_checked");
        btn_no.classList.remove("btn_checked");
        oportunidad_mejora.value = 1;
        toggle_plan.checked = true;
    });
    btn_no.addEventListener("click", (e) => {
        e.preventDefault();
        btn_no.classList.add("btn_checked");
        btn_yes.classList.remove("btn_checked");
        oportunidad_mejora.value = 0;
        toggle_plan.checked = false;
    });
</script>