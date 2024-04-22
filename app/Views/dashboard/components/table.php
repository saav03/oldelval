<style>
    td:hover {
        cursor: pointer;
    }
</style>
<?php if (vista_access('index_auditoria')) : ?>

<div class="col-12">
    <div class="card recent-sales overflow-auto">

        <div class="card-body">
            <h5 class="card-title">Respuestas de Observaciones <span>| Inspecciones</span></h5>

            <table class="table table-borderless datatable">
                <thead>
                    <tr>
                        <th scope="col">ID#</th>
                        <th scope="col">Responsable</th>
                        <th scope="col">Tipo Auditoria</th>
                        <th scope="col">Respuesta</th>
                        <th scope="col">Estado</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($observacionesInspecciones as $obs) : ?>
                        <tr>
                            <th scope="row"><a href="#">#<?= $obs['id_auditoria'] ?> </a></th>
                            <td><?= $obs['responsable'] ?></td>
                            <td>
                                <?php
                                switch ($obs['auditoria']) {
                                    case '1':
                                        echo 'Inspección de Control';
                                        break;
                                    case '2':
                                        echo 'Inspección Vehicular';
                                        break;
                                    case '3':
                                        echo 'Inspección de Obra';
                                        break;
                                    case '4':
                                        echo 'Inspección de Auditoría';
                                        break;
                                }
                                ?>
                            </td>
                            <td><?= $obs['respuesta'] ? $obs['respuesta'] : '<em>No hay respuestas aún</em>' ?></td>
                            <td>
                                <?php
                                if ($obs['estado'] == 0) {
                                    $class = 'warning';
                                    $estado = 'Pendiente';
                                } else if ($obs['estado'] == 1) {
                                    $class = 'success';
                                    $estado = 'Aprobado';
                                } else if ($obs['estado'] == 2) {
                                    $class = 'danger';
                                    $estado = 'Rechazado';
                                }
                                ?>
                                <span class="badge bg-<?= $class ?>"><?= $estado ?></span>
                            </td>
                        </tr>
                    <?php endforeach; ?>

                    <!-- <tr>
                        <th scope="row"><a href="#">#2147</a></th>
                        <td>Lucas Kesser</td>
                        <td>Transporte pesado</td>
                        <td>Esperando checklist vehicular</td>
                        <td><span class="badge bg-warning">Pendiente</span></td>
                    </tr>
                    <tr>
                        <th scope="row"><a href="#">#2049</a></th>
                        <td>Andrea Lagos</td>
                        <td>LOD1</td>
                        <td>Cumple</td>
                        <td><span class="badge bg-success">Aprobado</span></td>
                    </tr>
                    <tr>
                        <th scope="row"><a href="#">#2644</a></th>
                        <td>Agustin Gadriel</td>
                        <td>Levantamiento de cargas</td>
                        <td>No cumple con las reglas establecidas</td>
                        <td><span class="badge bg-danger">Rechazado</span></td>
                    </tr>
                    <tr>
                        <th scope="row"><a href="#">#2644</a></th>
                        <td>Andrea Lagos</td>
                        <td>LOD1</td>
                        <td>Cumple</td>
                        <td><span class="badge bg-success">Aprobado</span></td>
                    </tr> -->
                </tbody>
            </table>
        </div>
    </div>
</div>
<?php endif; ?>
