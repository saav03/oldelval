<!-- Encabezado -->
<link href="<?= base_url() ?>/assets/css/addFiles.css" rel="stylesheet">
<link href="<?= base_url() ?>/assets/css/fileAdder.css" rel="stylesheet">
<link href="<?= base_url() ?>/assets/css/virtual-select.min.css" rel="stylesheet">


<style>
    #contratista,
    #supervisor_responsable {
        max-width: 100%;
        padding: 3px;
        border-radius: 15px !important;
        margin-top: -5px;
    }
</style>

<form id="form_aud_tarea_de_campo">
    <input type="hidden" value="1" name="aud_tipo">

    <div class="row" style="display:flex ; justify-content: center">
        <div class="col-xs-12 col-md-4">
            <div class="form-group mb-3">
                <label class="sz_inp mb-1 fw-semibold">Ingrese el Tipo de Auditoría</label>
                <select name="tipo_auditoria_t" id="tipo_auditoria_t" class="form-select sz_inp"
                    onchange="getBloqueAuditorias_t(this)">
                    <option value="">-- Seleccione --</option>
                    <?php foreach ($auditorias_tarea_de_campo as $aud): ?>
                        <option value="<?= $aud['id']; ?>">
                            <?= $aud['nombre']; ?>
                        </option>
                    <?php endforeach ?>
                </select>
            </div>
        </div>
    </div>
    <br>

    <h5 class="text-center" style="font-size: 17px;" id="text-aud_title_t"><em></em></h5>
    <div class="card" id="datosgenerales_tarea"
        style="border: 1px solid #f6f6f6;box-shadow: 0px 0 30px rgb(179 179 179 / 53%); display:none">
        <div class="card-header subtitle"
            style="font-weight: 600; letter-spacing: 1.5px; text-align: center; background-color: #f5ceceab!important;">
            Datos Generales
        </div>
        <div class="card-body">

            <div class="row">
                <div class="col-md-3 col-xs-12 mt-2">
                    <div>
                        <label class="mb-1 fw-semibold" for="area">Contratista <small>(*)</small></label>
                        <select class="sz_inp" name="contratista_t" id="contratista_t" style="width: 100%"
                            name="native-select" data-search="true" data-silent-initial-value-set="true">
                            <?php
                            foreach ($contratistas as $e) {
                                echo "<option value='" . $e['id'] . "'>" . $e['nombre'] . "</option>";
                            }
                            ?>
                        </select>
                    </div>

                </div>
                <div class="col-md-3 col-xs-12 mt-2">
                    <label class="mb-1 fw-semibold">Supervisor Responsable <small>(*)</small></label>
                    <select class="sz_inp" name="supervisor_responsable_t" id="supervisor_responsable_t"
                        style="width: 100%" name="native-select" data-search="true"
                        data-silent-initial-value-set="true">
                        <?php
                        foreach ($usuarios as $e) {
                            echo "<option value='" . $e['id'] . "'>" . $e['nombre'] . ' ' . $e['apellido'] . "</option>";
                        }
                        ?>
                    </select>
                </div>
                <div class="col-md-2 col-xs-12">
                    <label class="mb-1 fw-semibold mt-2" for="fecha_hoy">Fecha Carga</label>
                    <input type="date" class="form-control text-center sz_inp simulate_dis" name="fecha_hoy_tarea"
                        value="<?= date('Y-m-d') ?>" readonly>
                </div>
                <div class="col-xs-12 col-md-2 mt-2">
                    <label class="mb-1 fw-semibold">Cantidad del Personal</label>
                    <input type="number" name="cant_personal_t" class="form-control sz_inp"
                        placeholder="Ingrese la cantidad">
                </div>
                <div class="col-xs-12 col-md-2 mt-2">
                    <label class="mb-1 fw-semibold">N°Informe</label>
                    <input type="text" name="num_informe_t" class="form-control sz_inp" placeholder="#">
                </div>
                <div class="col-md-3 col-xs-12 mt-2">
                    <label class="mb-1 fw-semibold" for="area">Proyectos <small>(*)</small></label>
                    <select name="proyecto_t" id="proyecto_t" class="form-select sz_inp"
                        onchange="filtrarModulos_t(this,'modulo_tarea_campo')">
                        <option value="">-- Seleccione --</option>
                        <?php foreach ($proyectos as $gral): ?>
                            <option value="<?= $gral['id']; ?>">
                                <?= $gral['nombre']; ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="col-md-3 col-xs-12 mt-2">
                    <label class="mb-1 fw-semibold" for="area">Módulos</label>
                    <div id="selector_modulos_div_t">
                        <select name="modulo_tarea_campo" id="modulo_tarea_campo" class="form-select sz_inp" required>
                            <option value="-1">-- No Aplica --</option>
                        </select>
                    </div>
                </div>
                <div class="col-md-3 col-xs-12 mt-2">
                    <label class="mb-1 fw-semibold" for="area">Estación de Bombeo</label>
                    <div id="selector_estaciones_div_t">
                        <select name="estacion_bombeo_t" id="estacion_bombeo_t" class="form-select sz_inp"
                            onchange="validacionEstaciones_t(this)">
                            <option value="">-- No Aplica --</option>
                            <?php foreach ($estaciones as $gral): ?>
                                <option value="<?= $gral['id']; ?>">
                                    <?= $gral['nombre']; ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>
                <div class="col-md-3 col-xs-12 mt-2">
                    <label class="mb-1 fw-semibold" for="area">Sistema de Oleoductos</label>
                    <div id="selector_sistemas_div_t">
                        <select name="sistema_t" id="sistema_t" class="form-select sz_inp"
                            onchange="validacionSistemas_t(this)">
                            <option value="">-- No Aplica --</option>
                            <?php foreach ($sistemas as $gral): ?>
                                <option value="<?= $gral['id']; ?>">
                                    <?= $gral['nombre']; ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div id="bloque_respuestas_container_t"></div>

    <br>

    <div class="card" id="obs_tarea"
        style="border: 1px solid #f6f6f6;box-shadow: 0px 0 30px rgb(179 179 179 / 53%); display:none">
        <div class="card-header subtitle"
            style="font-weight: 600; letter-spacing: 1.5px; text-align: center; background-color:#f5ceceab!important;">
            Observación
        </div>
        <div class="card-body">
            <?= view('auditoria/tarea_de_campo/plan_accion_tarea_de_campo'); ?>
        </div>
    </div>

    <div id="boton_tarea" style="display :none" class="float-end mb-3 mt-3">
        <button class="btn_modify btnUploadAud" data-id="form_aud_tarea_de_campo">Crear Nueva Inspección</button>
    </div>

</form>

<script>
    new addFiles(document.getElementById("gallery_t"), 'adj_observacion').init();
</script>
<script>
    /* == Estos son para los selectores dinámicos en el encabezado == */
    let estaciones_estadisticas_t = <?= json_encode($estaciones); ?>;
    let sistemas_estadisticas_t = <?= json_encode($sistemas); ?>;
</script>




<script src="<?= base_url("assets/js/auditorias/add_tarea_campo_select.js") ?>"></script>
<script src="<?= base_url("assets/js/auditorias/add_tarea_de_campo.js") ?>"></script>





<script>
    VirtualSelect.init({
        ele: '#contratista_t',
        placeholder: 'Seleccione la contratista',
    });

    VirtualSelect.init({
        ele: '#efecto_impacto_t',
        placeholder: 'Seleccione uno o mas efectos',
    });

    VirtualSelect.init({
        ele: '#supervisor_responsable_t',
        placeholder: 'Seleccione el supervisor',
    });

    VirtualSelect.init({
        ele: '#responsable_plan_t',
        placeholder: 'Seleccione el responsable',
    });

    VirtualSelect.init({
        ele: '#relevo_responsable_plan_t',
        placeholder: 'Seleccione el relevo',
    });

    document.getElementById('contratista_t').setValue(0);
    document.getElementById('supervisor_responsable_t').setValue(0);
    document.getElementById('responsable_plan_t').setValue(0);
    document.getElementById('relevo_responsable_plan_t').setValue(0);
</script>

 <!-- Los botones 'Si'/'No' del plan de acción (Si Corresponde) -->
<script>
    const btn_yes_t = document.getElementById("btn_yes_t");
    const btn_no_t = document.getElementById("btn_no_t");

    const toggle_plan_t = document.getElementById("toggle_plan_t");
    const oportunidad_mejora_t = document.getElementById("oportunidad_mejora_t");

    btn_yes_t.addEventListener("click", (e) => {
        e.preventDefault();
        console.log("Botón 'Si' clickeado");
        btn_yes_t.classList.add("btn_checked");
        btn_no_t.classList.remove("btn_checked");
        oportunidad_mejora_t.value = 1;
        toggle_plan_t.checked = true;
    });
    btn_no_t.addEventListener("click", (e) => {
        e.preventDefault();
        console.log("Botón 'No' clickeado");
        btn_no_t.classList.add("btn_checked");
        btn_yes_t.classList.remove("btn_checked");
        oportunidad_mejora_t.value = 0;
        toggle_plan_t.checked = false;
    });
</script> 