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

<form id="form_aud_auditoria">

    <input type="hidden" value="1" name="aud_tipo">


    <div class="row" style="display:flex ; justify-content: center">
        <div class="col-xs-12 col-md-4">
            <div class="form-group mb-3">
                <label class="sz_inp mb-1 fw-semibold">Ingrese el Tipo de Auditoría</label>
                <select name="tipo_auditoria_a" id="tipo_auditoria_a" class="form-select sz_inp"
                    onchange="getBloqueAuditorias_a(this)">
                    <option value="">-- Seleccione --</option>
                    <?php foreach ($auditorias_auditoria as $aud): ?>
                        <option value="<?= $aud['id']; ?>">
                            <?= $aud['nombre']; ?>
                        </option>
                    <?php endforeach ?>
                </select>
            </div>
        </div>
    </div>
    <br>


    <h5 class="text-center" style="font-size: 17px;" id="text-aud_title_a"><em></em></h5>
   <div class="card" id="datosgenerales_a"
        style="border: 1px solid #f6f6f6;box-shadow: 0px 0 30px rgb(179 179 179 / 53%); display:none">
        <div class="card-header subtitle"
            style="font-weight: 600; letter-spacing: 1.5px; text-align: center; background-color: #fffaa3a1!important;">
            Datos Generales
        </div>
        <div class="card-body">

            <div class="row">
                <div class="col-md-3 col-xs-12 mt-2">
                    <div>
                        <label class="mb-1 fw-semibold" for="area">Contratista <small>(*)</small></label>
                        <select class="sz_inp" name="contratista_a" id="contratista_a" style="width: 100%"
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
                    <select class="sz_inp" name="supervisor_responsable_a" id="supervisor_responsable_a"
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
                    <input type="date" class="form-control text-center sz_inp simulate_dis" name="fecha_hoy_a"
                        value="<?= date('Y-m-d') ?>" readonly>
                </div>
                <div class="col-xs-12 col-md-2 mt-2">
                    <label class="mb-1 fw-semibold">Cantidad del Personal</label>
                    <input type="number" name="cant_personal_a" class="form-control sz_inp"
                        placeholder="Ingrese la cantidad">
                </div>
                <div class="col-xs-12 col-md-2 mt-2">
                    <label class="mb-1 fw-semibold">N°Informe</label>
                    <input type="text" name="num_informe_a" class="form-control sz_inp" placeholder="#">
                </div>
                <div class="col-md-3 col-xs-12 mt-2">
                    <label class="mb-1 fw-semibold" for="area">Proyectos <small>(*)</small></label>
                    <select name="proyecto_a" id="proyecto_a" class="form-select sz_inp"
                        onchange="filtrarModulos_a(this)">
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
                    <div id="selector_modulos_div_a">
                        <select name="modulo_a" id="modulo_a" class="form-select sz_inp" required>
                            <option value="-1">-- No Aplica --</option>
                        </select>
                    </div>
                </div>
                 <div class="col-md-3 col-xs-12 mt-2">
                    <label class="mb-1 fw-semibold" for="area">Estación de Bombeo</label>
                    <div id="selector_estaciones_div_a">
                        <select name="estacion_bombeo_a" id="estacion_bombeo_a" class="form-select sz_inp"
                            onchange="validacionEstaciones_a(this)">
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
                    <div id="selector_sistemas_div_a">
                        <select name="sistema_a" id="sistema_a" class="form-select sz_inp"
                            onchange="validacionSistemas_a(this)">
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
    <div id="bloque_respuestas_container_a"></div>

    <br> 



    <div class="card" id="obs_a"
        style="border: 1px solid #f6f6f6;box-shadow: 0px 0 30px rgb(179 179 179 / 53%); display:none">
        <div class="card-header subtitle"
            style="font-weight: 600; letter-spacing: 1.5px; text-align: center; background-color:#fffaa3a1!important;">
            Observación
        </div>
        <div class="card-body">
            <?= view('auditoria/auditoria/plan_accion_auditoria'); ?>
        </div>
    </div>

    <div id="boton_a" class="float-end mb-3 mt-3" style="display:none">
        <button class="btn_modify btnUploadAud" data-id="form_aud_auditoria">Crear Nueva Inspección</button>
    </div> 

</form>
 <script>
    new addFiles(document.getElementById("gallery_a"), 'adj_observacion').init();
</script> 
 <script>
    /* == Estos son para los selectores dinámicos en el encabezado == */
    let estaciones_estadisticas_a = <?= json_encode($estaciones); ?>;
    let sistemas_estadisticas_a = <?= json_encode($sistemas); ?>;
</script> 







<script src="<?= base_url("assets/js/auditorias/add_auditoria.js") ?>"></script>
<script src="<?= base_url("assets/js/auditorias/add_auditoria_select.js") ?>"></script> 





<!-- Los selectores que vienen del plugin Virtual Select de JS -->
 <script>
    VirtualSelect.init({
        ele: '#contratista_a',
        placeholder: 'Seleccione la contratista',
    });

    VirtualSelect.init({
        ele: '#efecto_impacto_a',
        placeholder: 'Seleccione uno o mas efectos',
    });

    VirtualSelect.init({
        ele: '#supervisor_responsable_a',
        placeholder: 'Seleccione el supervisor',
    });

    VirtualSelect.init({
        ele: '#responsable_plan_a',
        placeholder: 'Seleccione el responsable',
    });

    VirtualSelect.init({
        ele: '#relevo_responsable_plan_a',
        placeholder: 'Seleccione el relevo',
    });

    document.getElementById('contratista_a').setValue(0);
    document.getElementById('supervisor_responsable_a').setValue(0);
    document.getElementById('responsable_plan_a').setValue(0);
    document.getElementById('relevo_responsable_plan_a').setValue(0);
</script> 

<!-- Los botones 'Si'/'No' del plan de acción (Si Corresponde) -->
 <script>
    const btn_yes_a = document.getElementById("btn_yes_a");
    const btn_no_a = document.getElementById("btn_no_a");

    const toggle_plan_a = document.getElementById("toggle_plan_a");
    const oportunidad_mejora_a = document.getElementById("oportunidad_mejora_a");

    btn_yes_a.addEventListener("click", (e) => {
        e.preventDefault();
        console.log("Botón 'Si' clickeado");
        btn_yes_a.classList.add("btn_checked");
        btn_no_a.classList.remove("btn_checked");
        oportunidad_mejora_a.value = 1;
        toggle_plan_a.checked = true;
    });
    btn_no_a.addEventListener("click", (e) => {
        e.preventDefault();
     
        btn_no_a.classList.add("btn_checked");
        btn_yes_a.classList.remove("btn_checked");
        oportunidad_mejora_a.value = 0;
        toggle_plan_a.checked = false;
    }); 
</script>