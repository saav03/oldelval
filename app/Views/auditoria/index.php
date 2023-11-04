<?php $permiso_edicion = vista_access('vista_editpermiso'); ?>
<title>OLDELVAL - Auditorías</title>
<link rel="stylesheet" href="<?= base_url('assets/css/auditorias/index.css') ?>">
<div class="container ">
    <div class="row">
        <div class="col-md-12">
            <div class="blister-title-container">
                <h4 class="blister-title">CheckList</h4>
            </div>
        </div>
    </div>

    <div class="card" style="border: 1px solid #f6f6f6; box-shadow: 0px 0 30px rgb(1 41 112 / 5%);">
        <div class="card-header" style="background: white; padding: 16px; font-weight: 600; letter-spacing: 1.5px;">
            Listado de Checklist
        </div>
        <div class="card-body">

            <div class="container">
                <div class="row">
                    <div class="col-md-12">
                        <nav>
                            <div class="nav nav-tabs" role="tablist">
                                <button type="button" id="tabButtons-1"
                                    class="nav-item nav-link btn_aud btn-aud_active active w-25" data-bs-toggle="tab"
                                    data-bs-target="#tabButtons-pane-1" role="tab" aria-controls="tabButtons-pane-1"
                                    aria-selected="true">Control</button>
                                <button type="button" id="tabButtons-2"
                                    class="nav-item nav-link btn_aud btn-aud_active w-25" data-bs-toggle="tab"
                                    data-bs-target="#tabButtons-pane-2" role="tab" aria-controls="tabButtons-pane-2"
                                    aria-selected="false"> Vehicular</button>
                                <button type="button" id="tabButtons-3"
                                    class="nav-item nav-link btn_aud btn-aud_active w-25" data-bs-toggle="tab"
                                    data-bs-target="#tabButtons-pane-3" role="tab" aria-controls="tabButtons-pane-3"
                                    aria-selected="false"> Tarea de Campo</button>
                                <button type="button" id="tabButtons-4"
                                    class="nav-item nav-link btn_aud btn-aud_active w-25" data-bs-toggle="tab"
                                    data-bs-target="#tabButtons-pane-4" role="tab" aria-controls="tabButtons-pane-4"
                                    aria-selected="false"> Auditoria</button>
                            </div>
                        </nav>

                        <style>
                            #toggle_aud_control,
                            #toggle_aud_vehicular,
                            #toggle_aud_tarea_de_campo,
                            #toggle_aud_auditorias{
                                display: none;
                            }

                            .content,
                            .content_vehicular,
                            .content_tarea_de_campo,
                            .content_auditorias {
                                max-height: 0;
                                overflow: hidden;
                                background-color: #f9f9f9;
                                transition: all 0.4s ease-in-out;
                            }

                            #toggle_aud_control:checked~.content {
                                max-height: 500px !important;
                                transition: all 0.4s ease-in-out;
                            }

                            #toggle_aud_vehicular:checked~.content_vehicular {
                                max-height: 500px !important;
                                transition: all 0.4s ease-in-out;
                            }
                            #toggle_aud_tarea_de_campo:checked~.content_tarea_de_campo {
                                max-height: 500px !important;
                                transition: all 0.4s ease-in-out;
                            }
                            #toggle_aud_auditorias:checked~.content_auditorias {
                                max-height: 500px !important;
                                transition: all 0.4s ease-in-out;
                            }
                        </style>
                        </style>
                        <div class="tab-content">
                            <div id="tabButtons-pane-1" class="tab-pane active" role="tabpanel"
                                aria-labelledby="tabButtons-1">
                                <div class="mt-3" id="tabla_auditoria_control">
                                    <div class="row mb-3">
                                        <!-- Parámetros de Búsquede para las Auditorías de Control -->
                                        <div class="col-xs-12">
                                            <div class="text-center mt-2 mb-2"
                                                style="background-color: #e2e8f0; border-radius: 5px;">
                                                <label for="toggle_aud_control"
                                                    class="d-flex justify-content-center align-items-center"
                                                    style="cursor: pointer;">
                                                    <h6
                                                        style="color: #475569; font-weight: bold; font-size: 13.5px; letter-spacing: 1px; padding: 10px 5px 10px 0; margin: 0;">
                                                        Búsqueda Avanzada</h6>
                                                    <i class="fa-solid fa-magnifying-glass"></i>
                                                </label>
                                                <input type="checkbox" id="toggle_aud_control">

                                                <div class="content"
                                                    style="background-color: white; border: 1px solid #e2e8f0;">
                                                    <div class="p-2">
                                                        <div class="row">
                                                            <div class="col-xs-12 col-md-3 text-start">
                                                                <label class="sz_inp fw-semibold mb-1">Modelo</label>
                                                                <select name="modelo_tipo_control"
                                                                    id="modelo_tipo_control" class="form-select sz_inp">
                                                                    <option value="">-- Seleccione --</option>
                                                                    <?php foreach ($auditorias_control as $gral): ?>
                                                                        <option value="<?= $gral['id']; ?>">
                                                                            <?= $gral['nombre']; ?>
                                                                        </option>
                                                                    <?php endforeach; ?>
                                                                </select>
                                                            </div>
                                                            <div class="col-xs-12 col-md-3 text-start">
                                                                <label
                                                                    class="sz_inp fw-semibold mb-1">Contratista</label>
                                                                <select name="contratista" id="contratista"
                                                                    class="form-select sz_inp">
                                                                    <option value="">-- Seleccione --</option>
                                                                    <?php foreach ($contratistas as $gral): ?>
                                                                        <option value="<?= $gral['id']; ?>">
                                                                            <?= $gral['nombre']; ?>
                                                                        </option>
                                                                    <?php endforeach; ?>
                                                                </select>
                                                            </div>
                                                            <div class="col-xs-12 col-md-3 text-start">
                                                                <label class="sz_inp fw-semibold mb-1">Supervisor
                                                                    Responsable</label>
                                                                <input type="text" name="supervisor" id="supervisor"
                                                                    placeholder="Ingrese el Supervisor"
                                                                    class="sz_inp form-control">
                                                            </div>
                                                            <div class="col-xs-12 col-md-3 text-start">
                                                                <label class="sz_inp fw-semibold mb-1">Proyecto</label>
                                                                <select name="proyecto_aud_control"
                                                                    id="proyecto_aud_control"
                                                                    class="form-select sz_inp">
                                                                    <option value="">-- Seleccione --</option>
                                                                    <?php foreach ($proyectos as $gral): ?>
                                                                        <option value="<?= $gral['id']; ?>">
                                                                            <?= $gral['nombre']; ?>
                                                                        </option>
                                                                    <?php endforeach; ?>
                                                                </select>
                                                            </div>
                                                        </div>
                                                        <div class="row mt-2">
                                                            <div class="col-xs-12 col-md-2"></div>
                                                            <div class="col-xs-12 col-md-3 text-start">
                                                                <label class="sz_inp fw-semibold mb-1">Usuario
                                                                    Carga</label>
                                                                <input type="text" id="usuario_carga_control"
                                                                    name="usuario_carga_control"
                                                                    placeholder="Ingrese el Usuario"
                                                                    class="sz_inp form-control">
                                                            </div>
                                                            <div class="col-xs-12 col-md-2 text-start">
                                                                <label class="sz_inp fw-semibold mb-1">Fecha Desde
                                                                    <small>(Fecha Carga)</small></label>
                                                                <input type="date" id="fecha_desde_control"
                                                                    name="fecha_desde_control"
                                                                    class="sz_inp form-control">
                                                            </div>
                                                            <div class="col-xs-12 col-md-2 text-start">
                                                                <label class="sz_inp fw-semibold mb-1">Fecha Hasta
                                                                    <small>(Fecha Carga)</small></label>
                                                                <input type="date" id="fecha_hasta_control"
                                                                    name="fecha_hasta_control"
                                                                    class="sz_inp form-control">
                                                            </div>
                                                        </div>
                                                    </div>

                                                </div>
                                            </div>

                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div id="tabButtons-pane-2" class="tab-pane" role="tabpanel" aria-labelledby="tabButtons-2">
                                <div class="mt-3" id="tabla_auditoria_vehicular">
                                    <div class="row mb-3">
                                        <!-- Parámetros de Búsquede para las Auditorías CheckList Vehicular -->
                                        <div class="col-xs-12">
                                            <div class="text-center mt-2 mb-2"
                                                style="background-color: #e2e8f0; border-radius: 5px;">
                                                <label for="toggle_aud_vehicular"
                                                    class="d-flex justify-content-center align-items-center"
                                                    style="cursor: pointer;">
                                                    <h6
                                                        style="color: #475569; font-weight: bold; font-size: 13.5px; letter-spacing: 1px; padding: 10px 5px 10px 0; margin: 0;">
                                                        Búsqueda Avanzada</h6>
                                                    <i class="fa-solid fa-magnifying-glass"></i>
                                                </label>
                                                <input type="checkbox" id="toggle_aud_vehicular">

                                                <div class="content_vehicular"
                                                    style="background-color: white; border: 1px solid #e2e8f0;">
                                                    <div class="p-2">
                                                        <div class="row">
                                                            <div class="col-xs-12 col-md-1 text-start">
                                                                <label class="sz_inp fw-semibold mb-1">ID</label>
                                                                <input type="text" name="id_aud_vehicular"
                                                                    id="id_aud_vehicular" placeholder="#ID"
                                                                    class="sz_inp form-control">
                                                            </div>
                                                            <div class="col-xs-12 col-md-2 text-start">
                                                                <label class="sz_inp fw-semibold mb-1">Modelo</label>
                                                                <select name="modelo_tipo_vehicular"
                                                                    id="modelo_tipo_vehicular"
                                                                    class="form-select sz_inp">
                                                                    <option value="">-- Seleccione --</option>
                                                                    <?php foreach ($auditorias_checklist as $gral): ?>
                                                                        <option value="<?= $gral['id']; ?>">
                                                                            <?= $gral['nombre']; ?>
                                                                        </option>
                                                                    <?php endforeach; ?>
                                                                </select>
                                                            </div>
                                                            <div class="col-xs-12 col-md-3 text-start">
                                                                <label class="sz_inp fw-semibold mb-1">Equipo</label>
                                                                <input type="text" name="equipo" id="equipo"
                                                                    placeholder="Ingrese el Equipo"
                                                                    class="sz_inp form-control">
                                                            </div>
                                                            <div class="col-xs-12 col-md-3 text-start">
                                                                <label class="sz_inp fw-semibold mb-1">Conductor</label>
                                                                <input type="text" name="conductor" id="conductor"
                                                                    placeholder="Ingrese el Conductor"
                                                                    class="sz_inp form-control">
                                                            </div>
                                                            <div class="col-xs-12 col-md-3 text-start">
                                                                <label class="sz_inp fw-semibold mb-1">N°Interno</label>
                                                                <input type="text" name="num_interno_vehicular"
                                                                    id="num_interno_vehicular"
                                                                    placeholder="Ingrese el N°Interno"
                                                                    class="sz_inp form-control">
                                                            </div>
                                                        </div>

                                                        <div class="row mt-2">
                                                            <div class="col-xs-12 col-md-3 text-start">
                                                                <label class="sz_inp fw-semibold mb-1">Titular</label>
                                                                <input type="text" name="titular" id="titular"
                                                                    placeholder="Ingrese el Titular"
                                                                    class="sz_inp form-control">
                                                            </div>
                                                            <div class="col-xs-12 col-md-3 text-start">
                                                                <label class="sz_inp fw-semibold mb-1">Proyecto</label>
                                                                <select name="proyecto_aud_vehicular"
                                                                    id="proyecto_aud_vehicular"
                                                                    class="form-select sz_inp">
                                                                    <option value="">-- Seleccione --</option>
                                                                    <?php foreach ($proyectos as $gral): ?>
                                                                        <option value="<?= $gral['id']; ?>">
                                                                            <?= $gral['nombre']; ?>
                                                                        </option>
                                                                    <?php endforeach; ?>
                                                                </select>
                                                            </div>
                                                            <div class="col-xs-12 col-md-3 text-start">
                                                                <label class="sz_inp fw-semibold mb-1">Resultado</label>
                                                                <select name="resultado_inspeccion"
                                                                    id="resultado_inspeccion"
                                                                    class="form-select sz_inp">
                                                                    <option value="">-- Seleccione --</option>
                                                                    <option value="1">Satisfactoria</option>
                                                                    <option value="2">No Satisfactoria</option>
                                                                </select>
                                                            </div>
                                                            <div class="col-xs-12 col-md-3 text-start">
                                                                <label class="sz_inp fw-semibold mb-1">Usuario
                                                                    Carga</label>
                                                                <input type="text" name="usuario_carga_vehicular"
                                                                    id="usuario_carga_vehicular"
                                                                    placeholder="Ingrese el Usuario"
                                                                    class="sz_inp form-control">
                                                            </div>
                                                        </div>

                                                        <div class="row mt-2">
                                                            <div class="col-xs-12 col-md-3"></div>
                                                            <div class="col-xs-12 col-md-3 text-start">
                                                                <label class="sz_inp fw-semibold mb-1">Fecha Desde
                                                                    <small>(Fecha Carga)</small></label>
                                                                <input type="date" name="fecha_desde_vehicular"
                                                                    id="fecha_desde_vehicular"
                                                                    placeholder="Ingrese el Usuario"
                                                                    class="sz_inp form-control">
                                                            </div>
                                                            <div class="col-xs-12 col-md-3 text-start">
                                                                <label class="sz_inp fw-semibold mb-1">Fecha Hasta
                                                                    <small>(Fecha Carga)</small></label>
                                                                <input type="date" name="fecha_hasta_vehicular"
                                                                    id="fecha_hasta_vehicular"
                                                                    placeholder="Ingrese el Usuario"
                                                                    class="sz_inp form-control">
                                                            </div>
                                                            <div class="col-xs-12 col-md-3"></div>
                                                        </div>
                                                    </div>

                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div id="tabButtons-pane-3" class="tab-pane " role="tabpanel"
                                aria-labelledby="tabButtons-3">
                                <div class="mt-3" id="tabla_auditoria_tarea_de_campo">
                                    <div class="row mb-3">
                
                                        <div class="col-xs-12">
                                            <div class="text-center mt-2 mb-2"
                                                style="background-color: #e2e8f0; border-radius: 5px;">
                                                <label for="toggle_aud_tarea_de_campo"
                                                    class="d-flex justify-content-center align-items-center"
                                                    style="cursor: pointer;">
                                                    <h6
                                                        style="color: #475569; font-weight: bold; font-size: 13.5px; letter-spacing: 1px; padding: 10px 5px 10px 0; margin: 0;">
                                                        Búsqueda Avanzada</h6>
                                                    <i class="fa-solid fa-magnifying-glass"></i>
                                                </label>
                                                <input type="checkbox" id="toggle_aud_tarea_de_campo">
                                                <div class="content_tarea_de_campo"
                                                    style="background-color: white; border: 1px solid #e2e8f0;">
                                                    <div class="p-2">
                                                        <div class="row">
                                                            <div class="col-xs-12 col-md-3 text-start">
                                                                <label class="sz_inp fw-semibold mb-1">Modelo</label>
                                                                <select name="modelo_tipo_tarea_de_campo"
                                                                    id="modelo_tipo_tarea_de_campo" class="form-select sz_inp">
                                                                    <option value="">-- Seleccione --</option>
                                                                    <?php foreach ($auditorias_tarea_de_campo as $gral): ?>
                                                                        <option value="<?= $gral['id']; ?>">
                                                                            <?= $gral['nombre']; ?>
                                                                        </option>
                                                                    <?php endforeach; ?> 
                                                                </select>
                                                            </div>
                                                            <div class="col-xs-12 col-md-3 text-start">
                                                                <label
                                                                    class="sz_inp fw-semibold mb-1">Contratista</label>
                                                                <select name="contratista_tarea_de_campo" id="contratista_tarea_de_campo"
                                                                    class="form-select sz_inp">
                                                                    <option value="">-- Seleccione --</option>
                                                                    <?php foreach ($contratistas as $gral): ?>
                                                                        <option value="<?= $gral['id']; ?>">
                                                                            <?= $gral['nombre']; ?>
                                                                        </option>
                                                                    <?php endforeach; ?>
                                                                </select>
                                                            </div>
                                                            <div class="col-xs-12 col-md-3 text-start">
                                                                <label class="sz_inp fw-semibold mb-1">Supervisor
                                                                    Responsable</label>
                                                                <input type="text" name="supervisor_tarea_de_campo" id="supervisor_tarea_de_campo"
                                                                    placeholder="Ingrese el Supervisor"
                                                                    class="sz_inp form-control">
                                                            </div>
                                                            <div class="col-xs-12 col-md-3 text-start">
                                                                <label class="sz_inp fw-semibold mb-1">Proyecto</label>
                                                                <select name="proyecto_aud_tarea_de_campo"
                                                                    id="proyecto_aud_tarea_de_campo"
                                                                    class="form-select sz_inp">
                                                                    <option value="">-- Seleccione --</option>
                                                                    <?php foreach ($proyectos as $gral): ?>
                                                                        <option value="<?= $gral['id']; ?>">
                                                                            <?= $gral['nombre']; ?>
                                                                        </option>
                                                                    <?php endforeach; ?>
                                                                </select>
                                                            </div>
                                                        </div>
                                                        <div class="row mt-2">
                                                            <div class="col-xs-12 col-md-2"></div>
                                                            <div class="col-xs-12 col-md-3 text-start">
                                                                <label class="sz_inp fw-semibold mb-1">Usuario
                                                                    Carga</label>
                                                                <input type="text" id="usuario_carga_tarea_de_campo"
                                                                    name="usuario_carga_tarea_de_campo"
                                                                    placeholder="Ingrese el Usuario"
                                                                    class="sz_inp form-control">
                                                            </div>
                                                            <div class="col-xs-12 col-md-2 text-start">
                                                                <label class="sz_inp fw-semibold mb-1">Fecha Desde
                                                                    <small>(Fecha Carga)</small></label>
                                                                <input type="date" id="fecha_desde_tarea_de_campo"
                                                                    name="fecha_desde_tarea_de_campo"
                                                                    class="sz_inp form-control">
                                                            </div>
                                                            <div class="col-xs-12 col-md-2 text-start">
                                                                <label class="sz_inp fw-semibold mb-1">Fecha Hasta
                                                                    <small>(Fecha Carga)</small></label>
                                                                <input type="date" id="fecha_hasta_tarea_de_campo"
                                                                    name="fecha_hasta_tarea_de_campo"
                                                                    class="sz_inp form-control">
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div id="tabButtons-pane-4" class="tab-pane" role="tabpanel" aria-labelledby="tabButtons-4">
                                <div class="mt-3" id="tabla_auditoria_auditoria">
                                    <div class="row mb-3">
                                        <!-- Parámetros de Búsquede para las Auditorías tarea de  control -->
                                        <div class="col-xs-12">
                                            <div class="text-center mt-2 mb-2"
                                                style="background-color: #e2e8f0; border-radius: 5px;">
                                                <label for="toggle_aud_auditorias"
                                                    class="d-flex justify-content-center align-items-center"
                                                    style="cursor: pointer;">
                                                    <h6
                                                        style="color: #475569; font-weight: bold; font-size: 13.5px; letter-spacing: 1px; padding: 10px 5px 10px 0; margin: 0;">
                                                        Búsqueda Avanzada</h6>
                                                    <i class="fa-solid fa-magnifying-glass"></i>
                                                </label>
                                                <input type="checkbox" id="toggle_aud_auditorias">
                                                <div class="content_auditorias"
                                                    style="background-color: white; border: 1px solid #e2e8f0;">
                                                    <div class="p-2">
                                                        <div class="row">
                                                            <div class="col-xs-12 col-md-3 text-start">
                                                                <label class="sz_inp fw-semibold mb-1">Modelo</label>
                                                                <select name="modelo_tipo_auditoria_a"
                                                                    id="modelo_tipo_auditoria_a"
                                                                    class="form-select sz_inp">
                                                                    <option value="">-- Seleccione --</option>
                                                                    <?php foreach ($auditorias_auditoria as $gral): ?>
                                                                        <option value="<?= $gral['id']; ?>">
                                                                            <?= $gral['nombre']; ?>
                                                                        </option>
                                                                    <?php endforeach; ?>
                                                                </select>
                                                            </div>
                                                            <div class="col-xs-12 col-md-3 text-start">
                                                                <label
                                                                    class="sz_inp fw-semibold mb-1">Contratista</label>
                                                                <select name="contratista_auditoria_a"
                                                                    id="contratista_auditoria_a"
                                                                    class="form-select sz_inp">
                                                                    <option value="">-- Seleccione --</option>
                                                                    <?php foreach ($contratistas as $gral): ?>
                                                                        <option value="<?= $gral['id']; ?>">
                                                                            <?= $gral['nombre']; ?>
                                                                        </option>
                                                                    <?php endforeach; ?>
                                                                </select>
                                                            </div>
                                                            <div class="col-xs-12 col-md-3 text-start">
                                                                <label class="sz_inp fw-semibold mb-1">Supervisor
                                                                    Responsable</label>
                                                                <input type="text" name="supervisor_auditoria_a"
                                                                    id="supervisor_auditoria_a"
                                                                    placeholder="Ingrese el Supervisor"
                                                                    class="sz_inp form-control">
                                                            </div>
                                                            <div class="col-xs-12 col-md-3 text-start">
                                                                <label class="sz_inp fw-semibold mb-1">Proyecto</label>
                                                                <select name="proyecto_aud_auditoria_a"
                                                                    id="proyecto_aud_auditoria_a"
                                                                    class="form-select sz_inp">
                                                                    <option value="">-- Seleccione --</option>
                                                                    <?php foreach ($proyectos as $gral): ?>
                                                                        <option value="<?= $gral['id']; ?>">
                                                                            <?= $gral['nombre']; ?>
                                                                        </option>
                                                                    <?php endforeach; ?>
                                                                </select>
                                                            </div>
                                                        </div>
                                                        <div class="row mt-2">
                                                            <div class="col-xs-12 col-md-2"></div>
                                                            <div class="col-xs-12 col-md-3 text-start">
                                                                <label class="sz_inp fw-semibold mb-1">Usuario
                                                                    Carga</label>
                                                                <input type="text" id="usuario_carga_auditoria_a"
                                                                    name="usuario_carga_auditoria_a"
                                                                    placeholder="Ingrese el Usuario"
                                                                    class="sz_inp form-control">
                                                            </div>
                                                            <div class="col-xs-12 col-md-2 text-start">
                                                                <label class="sz_inp fw-semibold mb-1">Fecha Desde
                                                                    <small>(Fecha Carga)</small></label>
                                                                <input type="date" id="fecha_desde_auditoria_a"
                                                                    name="fecha_desde_auditoria_a"
                                                                    class="sz_inp form-control">
                                                            </div>
                                                            <div class="col-xs-12 col-md-2 text-start">
                                                                <label class="sz_inp fw-semibold mb-1">Fecha Hasta
                                                                    <small>(Fecha Carga)</small></label>
                                                                <input type="date" id="fecha_hasta_auditoria_a"
                                                                    name="fecha_hasta_auditoria_a"
                                                                    class="sz_inp form-control">
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            
                        </div> <!-- col end -->
                    </div> <!-- row end -->
                </div>
            </div>
        </div>
    </div>
</div>


<script src="<?= base_url() ?>/assets/js/auditorias/historico.js"></script>