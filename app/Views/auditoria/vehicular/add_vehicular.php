<!-- Encabezado -->
<form id="form_aud_checklist">
    <input type="hidden" value="0" name="aud_tipo">
    <div class="card" style="border: 1px solid #f6f6f6;box-shadow: 0px 0 30px rgb(179 179 179 / 53%);">
        <div class="card-header subtitle" style="font-weight: 600; letter-spacing: 1.5px; text-align: center; background-color: #CCFFCC!important;">
            Datos Generales
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-xs-12 col-md-3 mt-2">
                    <label class="mb-1 fw-semibold" for="equipo">Equipo</label>
                    <input type="text" name="equipo" id="equipo" class="form-control sz_inp" placeholder="Ingrese el equipo">
                </div>
                <div class="col-md-3 col-xs-12 mt-2">
                    <label class="mb-1 fw-semibold">Conductor <small>(*)</small></label>
                    <select name="conductor" class="form-select sz_inp">
                        <option value="">-- Seleccione --</option>
                        <?php foreach ($usuarios as $gral) : ?>
                            <option value="<?= $gral['id']; ?>"><?= $gral['nombre'] . ' ' . $gral['apellido'] ?></option>
                        <?php endforeach; ?>
                    </select>
                    <!-- <input type="text" name="conductor" class="form-control sz_inp" placeholder="Ingrese el conductor"> -->
                </div>

                <div class="col-xs-12 col-md-2 mt-2">
                    <label class="mb-1 fw-semibold">N°Interno</label>
                    <input type="text" name="num_interno" class="form-control sz_inp" placeholder="#">
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
                <div class="col-xs-12 col-md-3 mt-2">
                    <label class="mb-1 fw-semibold">Titular</label>
                    <select name="titular" class="form-select sz_inp">
                        <option value="">-- Seleccione --</option>
                        <?php foreach ($usuarios as $gral) : ?>
                            <option value="<?= $gral['id']; ?>"><?= $gral['nombre'] . ' ' . $gral['apellido'] ?></option>
                        <?php endforeach; ?>
                    </select>
                    <!-- <input type="text" name="titular" class="form-control sz_inp" placeholder="Ingrese el titular"> -->
                </div>
                <div class="col-md-2 col-xs-12">
                    <label class="mb-1 fw-semibold mt-2" for="fecha_hoy">Fecha</label>
                    <input type="date" class="form-control text-center sz_inp simulate_dis" name="fecha_hoy" id="fecha_hoy" value="<?= date('Y-m-d') ?>" readonly>
                </div>
                <div class="col-md-2 col-xs-12">
                    <label class="mb-1 fw-semibold mt-2" for="fecha_hoy">Hora</label>
                    <input type="time" class="form-control text-center sz_inp" name="hora">
                </div>
                <div class="col-md-3 col-xs-12 mt-2">
                    <label class="mb-1 fw-semibold" for="area">Proyectos <small>(*)</small></label>
                    <select name="proyecto" id="proyecto" class="form-select sz_inp">
                        <option value="">-- Seleccione --</option>
                        <?php foreach ($proyectos as $gral) : ?>
                            <option value="<?= $gral['id']; ?>"><?= $gral['nombre']; ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="col-xs-12 mt-3 text-center">
                    <label class="mb-1 fw-semibold sz_inp">Tarea que realiza</label>
                    <textarea name="tarea_realiza" cols="10" rows="2" class="form-control sz_inp" placeholder="Ingrese la tarea que realiza"></textarea>
                </div>
            </div>
        </div>
    </div>

    <div class="card" style="border: 1px solid #f6f6f6;box-shadow: 0px 0 30px rgb(179 179 179 / 53%);">
        <div class="card-header subtitle" style="font-weight: 600; letter-spacing: 1.5px; text-align: center; background-color: #CCFFCC!important;">
            Auditoría
        </div>
        <div class="card-body">
            <hr style="color: #bbb;">
            <div class="row">
                <div class="col-xs-12 col-md-4">
                    <div class="form-group">
                        <label class="sz_inp mb-1 fw-semibold">Ingrese la Auditoria a cargar</label>
                        <select name="tipo_auditoria" id="tipo_auditoria" class="form-select sz_inp" onchange="getBloqueAuditoriasVehicular(this)">
                            <option value="">-- Seleccione --</option>
                            <?php foreach ($auditorias_checklist as $aud) : ?>
                                <option value="<?= $aud['id']; ?>"><?= $aud['nombre']; ?></option>
                            <?php endforeach ?>
                        </select>
                    </div>
                </div>
            </div>
            <hr style="color: #bbb;">

            <div class="row">
                <h5 class="text-center" style="font-size: 17px;" id="text-aud_title_vehicular"><em>Seleccione una Auditoría para visualizar las preguntas</em></h5>
            </div>

            <div id="bloque_respuestas_container_vehicular"></div>

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
                                            <input type="radio" class="option-input radio" name="resultado_inspeccion" value="1" />
                                            Satisfactoria
                                        </label>
                                        <label class="label_satisfactoria">
                                            <input type="radio" class="option-input radio" name="resultado_inspeccion" value="0" />
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
                            <textarea class="form-control sz_inp obs_medidas" name="medidas_implementar" cols="30" rows="5" style="border: 1px solid #f1f1f1;" placeholder="Ingrese las observaciones (Opcional) - Máximo 300 caracteres"></textarea>
                        </div>
                    </fieldset>
                </div>
            </div>

        </div>

        <br>


    </div>
    <div class="d-inline-block float-end mt-3 mb-3">
        <button class="btn_modify btnUploadAud" data-id="form_aud_checklist">Cargar Auditoría</button>
    </div>
</form>


<script src="<?= base_url("assets/js/auditorias/add_vehicular.js") ?>"></script>