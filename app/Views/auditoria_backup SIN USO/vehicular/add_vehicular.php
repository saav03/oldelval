<!-- Encabezado -->

<style>
    #contratista_v,
    #conductor,
    #titular {
        max-width: 100%;
        padding: 3px;
        border-radius: 15px !important;
        margin-top: -5px;
    }
</style>

<form id="form_aud_checklist">
    <input type="hidden" value="0" name="aud_tipo">
    <div class="row" style="display:flex ; justify-content: center">
        <div class="col-xs-12 col-md-4">
            <div class="form-group mb-3">
                <label class="sz_inp mb-1 fw-semibold">Ingrese la Auditoria a cargar</label>
                <select name="tipo_auditoria" id="tipo_auditoria" class="form-select sz_inp"
                    onchange="getBloqueAuditoriasVehicular(this)">
                    <option value="">-- Seleccione --</option>
                    <?php foreach ($auditorias_checklist as $aud): ?>
                        <option value="<?= $aud['id']; ?>">
                            <?= $aud['nombre']; ?>
                        </option>
                    <?php endforeach ?>
                </select>
            </div>
        </div>
    </div>
    <h5 class="text-center" style="font-size: 17px;" id="text-aud_title_vehicular"><em></em></h5>

    <div class="card" id="datos_generalesVehicular"
        style="border: 1px solid #f6f6f6;box-shadow: 0px 0 30px rgb(179 179 179 / 53%); display: none; ">
        <div class="card-header subtitle"
            style="font-weight: 600; letter-spacing: 1.5px; text-align: center; background-color: #CCFFCC!important;">
            Datos Generales
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-3 col-xs-12 mt-2">
                    <label class="fw-semibold" style="margin-bottom: 6px;" for="area">Contratista
                        <small>(*)</small></label>
                    <select class="sz_inp" name="contratista_v" id="contratista_v" style="width: 100%"
                        name="native-select" data-search="true" data-silent-initial-value-set="true">
                        <?php
                        foreach ($contratistas as $e) {
                            echo "<option value='" . $e['id'] . "'>" . $e['nombre'] . "</option>";
                        }
                        ?>
                    </select>
                </div>
                <div class="col-xs-12 col-md-3 mt-2">
                    <label class="mb-1 fw-semibold" style="margin-bottom: 6px;" for="equipo">Equipo</label>
                    <input type="text" name="equipo" id="equipo" class="form-control sz_inp"
                        placeholder="Ingrese el equipo">
                </div>
                <div class="col-md-3 col-xs-12 mt-2">
                    <label class="fw-semibold" style="margin-bottom: 6px;">Conductor <small>(*)</small></label>
                    <select class="sz_inp" name="conductor" id="conductor" style="width: 100%" name="native-select"
                        data-search="true" data-silent-initial-value-set="true">
                        <?php
                        foreach ($usuarios as $e) {
                            echo "<option value='" . $e['id'] . "'>" . $e['nombre'] . ' ' . $e['apellido'] . "</option>";
                        }
                        ?>
                    </select>
                    <!-- <input type="text" name="conductor" class="form-control sz_inp" placeholder="Ingrese el conductor"> -->
                </div>
                <div class="col-xs-12 col-md-3 mt-2">
                    <label class="fw-semibold" style="margin-bottom: 6px;">Titular</label>
                    <select class="sz_inp" name="titular" id="titular" style="width: 100%" name="native-select"
                        data-search="true" data-silent-initial-value-set="true">
                        <?php
                        foreach ($usuarios as $e) {
                            echo "<option value='" . $e['id'] . "'>" . $e['nombre'] . ' ' . $e['apellido'] . "</option>";
                        }
                        ?>
                    </select>

                </div>
                <div class="col-xs-12 col-md-3 mt-2">
                    <label class="mb-1 fw-semibold">N°Interno</label>
                    <input type="text" name="num_interno" class="form-control sz_inp" placeholder="#">
                </div>
                <div class="col-md-3 col-xs-12 mt-2">
                    <label class="mb-1 fw-semibold" for="area">Proyectos <small>(*)</small></label>
                    <select name="proyecto" id="proyecto" class="form-select sz_inp">
                        <option value="">-- Seleccione --</option>
                        <?php foreach ($proyectos as $gral): ?>
                            <option value="<?= $gral['id']; ?>">
                                <?= $gral['nombre']; ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="col-xs-12 col-md-2 mt-2">
                    <label class="mb-1 fw-semibold">Marca</label>
                    <input type="text" name="marca" class="form-control sz_inp" placeholder="Ingrese la marca">
                </div>
                <div class="col-xs-12 col-md-2 mt-2">
                    <label class="mb-1 fw-semibold">Modelo</label>
                    <input type="text" name="modelo" class="form-control sz_inp" placeholder="Ingrese el modelo">
                </div>
                <div class="col-xs-12 col-md-2 mt-2">
                    <label class="mb-1 fw-semibold">Patente</label>
                    <input type="text" name="patente" class="form-control sz_inp" placeholder="Ingrese la patente">
                </div>
                <div class="col-xs-12 col-md-3 mt-2"></div>
                <div class="col-md-3 col-xs-12">
                    <label class="mb-1 fw-semibold mt-2" for="fecha_hoy">Fecha</label>
                    <input type="date" class="form-control text-center sz_inp simulate_dis" name="fecha_hoy"
                        id="fecha_hoy" value="<?= date('Y-m-d') ?>" readonly>
                </div>
                <div class="col-md-3 col-xs-12">
                    <label class="mb-1 fw-semibold mt-2" for="fecha_hoy">Hora</label>
                    <input type="time" class="form-control text-center sz_inp" name="hora">
                </div>

                <div class="col-xs-12 mt-3 text-center">
                    <label class="mb-1 fw-semibold sz_inp">Tarea que realiza</label>
                    <textarea name="tarea_realiza" cols="10" rows="2" class="form-control sz_inp"
                        placeholder="Ingrese la tarea que realiza"></textarea>
                </div>
            </div>
        </div>
    </div>
    <div id="bloque_respuestas_container_vehicular"></div>
    <div class="card" id="ResultadoInspeccionVehicular"
        style="border: 1px solid #f6f6f6;box-shadow: 0px 0 30px rgb(179 179 179 / 53%); display: none;  ">
        <div class="row" id="datos_extras" style="display: none;">
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
                                        <input type="radio" class="option-input radio" name="resultado_inspeccion"
                                            value="1" />
                                        Satisfactoria
                                    </label>
                                    <label class="label_satisfactoria">
                                        <input type="radio" class="option-input radio" name="resultado_inspeccion"
                                            value="0" />
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
                        <textarea class="form-control sz_inp obs_medidas" name="medidas_implementar" cols="30" rows="5"
                            style="border: 1px solid #f1f1f1;"
                            placeholder="Ingrese las observaciones (Opcional) - Máximo 300 caracteres"></textarea>
                    </div>
                </fieldset>
            </div>
        </div>
    </div>


    <div class="card" id="obs_vehicular_prueba"
        style="border: 1px solid #f6f6f6;box-shadow: 0px 0 30px rgb(179 179 179 / 53%); display:none">
        <div class="card-header subtitle"
            style="font-weight: 600; letter-spacing: 1.5px; text-align: center; background-color: #CCFFCC!important;">
            Observación
        </div>
        <div class="card-body">
            <?= view('auditoria/vehicular/plan_accion_vehicular'); ?>
        </div>
    </div>
    <div id="boton_vehicular" style=" display: none; " class="float-end mb-3 mt-3">
        <button class="btn_modify btnUploadAud" data-id="form_aud_checklist">Crear Nueva Inspección</button>
    </div>
</form>

<script>
    new addFiles(document.getElementById("gallery_v"), 'adj_observacion').init();
</script>

<!-- Los selectores que vienen del plugin Virtual Select de JS -->
<script>
    VirtualSelect.init({
        ele: '#contratista_v',
        placeholder: 'Seleccione la contratista',
    });

    VirtualSelect.init({
        ele: '#conductor',
        placeholder: 'Seleccione el conductor',
    });

    VirtualSelect.init({
        ele: '#titular',
        placeholder: 'Seleccione el titular',
    });

    VirtualSelect.init({
        ele: '#efecto_impacto_v',
        placeholder: 'Seleccione uno o mas efectos',
    });

    VirtualSelect.init({
        ele: '#responsable_plan_v',
        placeholder: 'Seleccione el responsable',
    });

    VirtualSelect.init({
        ele: '#relevo_responsable_plan_v',
        placeholder: 'Seleccione el relevo',
    });

    document.getElementById('contratista_v').setValue(0);
    document.getElementById('conductor').setValue(0);
    document.getElementById('titular').setValue(0);
    document.getElementById('responsable_plan_v').setValue(0);
    document.getElementById('relevo_responsable_plan_v').setValue(0);
</script>

<!-- Los botones 'Si'/'No' del plan de acción (Si Corresponde) -->
<script>
    const btn_yes_v = document.getElementById("btn_yes_v");
    const btn_no_v = document.getElementById("btn_no_v");

    const toggle_plan_v = document.getElementById("toggle_plan_v");
    const oportunidad_mejora_v = document.getElementById("oportunidad_mejora_v");

    btn_yes_v.addEventListener("click", (e) => {
        e.preventDefault();
        btn_yes_v.classList.add("btn_checked");
        btn_no_v.classList.remove("btn_checked");
        oportunidad_mejora_v.value = 1;
        toggle_plan_v.checked = true;
    });
    btn_no_v.addEventListener("click", (e) => {
        e.preventDefault();
        btn_no_v.classList.add("btn_checked");
        btn_yes_v.classList.remove("btn_checked");
        oportunidad_mejora_v.value = 0;
        toggle_plan_v.checked = false;
    });
</script>

<script src="<?= base_url("assets/js/auditorias/add_vehicular.js") ?>"></script>