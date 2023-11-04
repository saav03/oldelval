<?php $permiso_edicion = vista_access('vista_editpermiso'); ?>

<title>OLDELVAL - Auditorías</title>
<link rel="stylesheet" href="<?= base_url('assets/css/auditorias/index.css') ?>">
<link rel="stylesheet" href="<?= base_url('assets/css/auditorias/edicion.css') ?>">
<div class="container ">
    <div class="row">
        <div class="col-md-12">
            <div class="blister-title-container">
                <h4 class="blister-title">Auditorías Creadas</h4>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-12 anuncio">
            <div class="d-flex">
                <div class="d-flex align-center text-center ms-3 me-3" style="font-size: 18px; text-align: center; color: lightskyblue;">
                    <i class="fa-solid fa-circle-exclamation"></i>
                </div>
                <p class="p-0 m-0 fw-semibold">En este sector, usted podrá seleccionar que auditoría quiere editar y/o eliminar</p>
            </div>
        </div>
    </div>

    <div class="card" style="border: 1px solid #f6f6f6; box-shadow: 0px 0 30px rgb(1 41 112 / 5%);">
        <div class="card-header" style="background: white; padding: 16px; font-weight: 600; letter-spacing: 1.5px;">
            Listado de Auditorías
        </div>
        <div class="card-body">

            <div class="container">
                <div class="row">
                    <div class="col-md-12">
                        <nav>
                            <div class="nav nav-tabs" role="tablist">
                                <button type="button" id="tabButtons-1" class="nav-item nav-link btn_aud btn-aud_active active" data-bs-toggle="tab" data-bs-target="#tabButtons-pane-1" role="tab" aria-controls="tabButtons-pane-1" aria-selected="true">Control</button>
                                <button type="button" id="tabButtons-2" class="nav-item nav-link btn_aud btn-aud_active" data-bs-toggle="tab" data-bs-target="#tabButtons-pane-2" role="tab" aria-controls="tabButtons-pane-2" aria-selected="false"> Vehicular</button>
                                <button type="button" id="tabButtons-3" class="nav-item nav-link btn_aud btn-aud_active" data-bs-toggle="tab" data-bs-target="#tabButtons-pane-3" role="tab" aria-controls="tabButtons-pane-3" aria-selected="false"> Tarea de Campo</button>
                                <button type="button" id="tabButtons-4" class="nav-item nav-link btn_aud btn-aud_active" data-bs-toggle="tab" data-bs-target="#tabButtons-pane-4" role="tab" aria-controls="tabButtons-pane-4" aria-selected="false"> Auditoria</button>
                            </div>
                        </nav>

                        <div class="tab-content">
                            <!-- Auditoría Control -->
                            <div id="tabButtons-pane-1" class="tab-pane active" role="tabpanel" aria-labelledby="tabButtons-1">
                                <div class="mt-3 overflow-auto" id="tabla_auditoria_control">
                                    <table class="table_oldelval">
                                        <thead>
                                            <th>#ID</th>
                                            <th>Nombre</th>
                                            <th>N° Revisión</th>
                                            <th>Estado</th>
                                            <th>Acciones</th>
                                        </thead>

                                        <tbody>
                                            <?php foreach ($auditorias_control as $control) : ?>
                                                <tr>
                                                    <td><?= $control['id'] ?></td>
                                                    <td><?= $control['nombre'] ?></td>
                                                    <td><?= $control['revision'] ?></td>
                                                    <td class="<?= $control['obsoleto'] == 1 ? 'obsoleto' : 'activo' ?>"><?= $control['obsoleto'] == 1 ? 'Obsoleto' : 'Activo'  ?></td>
                                                    <td class="text-center">
                                                        <div>
                                                            <a target="_blank" class="td_acciones" href="<?= base_url() ?>/auditorias/planillas/<?= $control['id'] ?>">Ver</a>
                                                        </div>
                                                    </td>
                                                </tr>
                                            <?php endforeach; ?>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                            <!-- Auditoría Vehicular -->
                            <div id="tabButtons-pane-2" class="tab-pane" role="tabpanel" aria-labelledby="tabButtons-2">
                                <div class="mt-3 overflow-auto" id="tabla_auditoria_vehicular">
                                    <table class="table_oldelval">
                                        <thead>
                                            <th>#ID</th>
                                            <th>Nombre</th>
                                            <th>N° Revisión</th>
                                            <th>Estado</th>
                                            <th>Acciones</th>
                                        </thead>

                                        <tbody>
                                            <?php foreach ($auditorias_checklist as $checklist) : ?>
                                                <tr>
                                                    <td><?= $checklist['id'] ?></td>
                                                    <td><?= $checklist['nombre'] ?></td>
                                                    <td><?= $checklist['revision'] ?></td>
                                                    <td class="<?= $checklist['obsoleto'] == 1 ? 'obsoleto' : 'activo' ?>"><?= $checklist['obsoleto'] == 1 ? 'Obsoleto' : 'Activo'  ?></td>
                                                    <td class="text-center">
                                                        <div>
                                                            <a target="_blank" class="td_acciones" href="<?= base_url() ?>/auditorias/planillas/<?= $checklist['id'] ?>">Ver</a>
                                                        </div>
                                                    </td>
                                                </tr>
                                            <?php endforeach; ?>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                            <!-- Auditoría Tarea de Campo -->
                            <div id="tabButtons-pane-3" class="tab-pane " role="tabpanel" aria-labelledby="tabButtons-3">
                                <div class="mt-3 overflow-auto" id="tabla_auditoria_tarea_de_campo">
                                    <table class="table_oldelval">
                                        <thead>
                                            <th>#ID</th>
                                            <th>Nombre</th>
                                            <th>N° Revisión</th>
                                            <th>Estado</th>
                                            <th>Acciones</th>
                                        </thead>

                                        <tbody>
                                            <?php foreach ($auditorias_tarea_de_campo as $task) : ?>
                                                <tr>
                                                    <td><?= $task['id'] ?></td>
                                                    <td><?= $task['nombre'] ?></td>
                                                    <td><?= $task['revision'] ?></td>
                                                    <td class="<?= $task['obsoleto'] == 1 ? 'obsoleto' : 'activo' ?>"><?= $task['obsoleto'] == 1 ? 'Obsoleto' : 'Activo'  ?></td>
                                                    <td class="text-center">
                                                        <div>
                                                            <a target="_blank" class="td_acciones" href="<?= base_url() ?>/auditorias/planillas/<?= $task['id'] ?>">Ver</a>
                                                        </div>
                                                    </td>
                                                </tr>
                                            <?php endforeach; ?>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                            <!-- Auditoría Auditoría (? -->
                            <div id="tabButtons-pane-4" class="tab-pane" role="tabpanel" aria-labelledby="tabButtons-4">
                                <div class="mt-3 overflow-auto" id="tabla_auditoria_auditoria">
                                    <table class="table_oldelval">
                                        <thead>
                                            <th>#ID</th>
                                            <th>Nombre</th>
                                            <th>N° Revisión</th>
                                            <th>Estado</th>
                                            <th>Acciones</th>
                                        </thead>

                                        <tbody>
                                            <?php foreach ($auditorias_auditoria as $auditoria) : ?>
                                                <tr>
                                                    <td><?= $auditoria['id'] ?></td>
                                                    <td><?= $auditoria['nombre'] ?></td>
                                                    <td><?= $auditoria['revision'] ?></td>
                                                    <td class="<?= $auditoria['obsoleto'] == 1 ? 'obsoleto' : 'activo' ?>"><?= $auditoria['obsoleto'] == 1 ? 'Obsoleto' : 'Activo'  ?></td>
                                                    <td class="text-center">
                                                        <div>
                                                            <a target="_blank" class="td_acciones" href="<?= base_url() ?>/auditorias/planillas/<?= $auditoria['id'] ?>">Ver</a>
                                                        </div>
                                                    </td>
                                                </tr>
                                            <?php endforeach; ?>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div> <!-- col end -->
                    </div> <!-- row end -->
                </div>
            </div>
        </div>
    </div>
</div>


<!-- <script src="<!?= base_url() ?>/assets/js/auditorias_edicion/historico.js"></script> -->
<script src="<?= base_url() ?>/assets/js/auditorias_edicion/edicion.js"></script>