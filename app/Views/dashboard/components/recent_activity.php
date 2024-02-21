<div class="card">
    <!-- <div class="filter">
        <a class="icon" href="#" data-bs-toggle="dropdown"><i class="bi bi-three-dots"></i></a>
        <ul class="dropdown-menu dropdown-menu-end dropdown-menu-arrow">
            <li class="dropdown-header text-start">
                <h6>Filter</h6>
            </li>

            <li><a class="dropdown-item" href="#">Today</a></li>
            <li><a class="dropdown-item" href="#">This Month</a></li>
            <li><a class="dropdown-item" href="#">This Year</a></li>
        </ul>
    </div> -->

    <div class="card-body">
        <h5 class="card-title">Actividad Reciente</h5>
        <div class="activity">

            <?php foreach ($actividad_reciente as $act) : 
            
            if ($act['id_accion'] == 1) {
                $clase = 'success';
            } else if ($act['id_accion'] == 2) {
                $clase = 'danger';
            } else {
                $clase = 'primary';
            }
                
            ?>
                <div class="activity-item d-flex">
                    <div class="activite-label"><?= $act['tiempo'] ?></div>
                    <!-- ACA TE QUEDASTE | Tenés que terminar la actividad reciente y también 'Tus Observaciones' -->
                    <i class='bi bi-circle-fill activity-badge text-<?= $clase ?> align-self-start'></i>
                    <div class="activity-content">
                        <?= $act['comentario'] ?>
                        <!-- Martin Lagos aprobó el <label class="fw-bold text-dark">Hallazgo #3</label> en la <a href="#" class="fw-bold text-dark">Auditoria #21</a>. -->
                    </div>
                </div>
            <?php endforeach; ?>

            <!-- <div class="activity-item d-flex">
                <div class="activite-label">56 min</div>
                <i class='bi bi-circle-fill activity-badge text-danger align-self-start'></i>
                <div class="activity-content">
                    <a href="#" class="text-dark">Nueva Observacion</a> Negativa generada por <label class="fw-bold text-dark"> Leandro Nogal</label>
                </div>
            </div>

            <div class="activity-item d-flex">
                <div class="activite-label">2 hrs</div>
                <i class='bi bi-circle-fill activity-badge text-primary align-self-start'></i>
                <div class="activity-content">
                    Alta de Usuario #92 Tomas Echeverría
                </div>
            </div>

            <div class="activity-item d-flex">
                <div class="activite-label">1 día</div>
                <i class='bi bi-circle-fill activity-badge text-success align-self-start'></i>
                <div class="activity-content">
                    Martin Lagos aprobó el <label class="fw-bold text-dark">Hallazgo #2</label> en la <a href="#" class="fw-bold text-dark">Auditoria #19</a>
                </div>
            </div>

            <div class="activity-item d-flex">
                <div class="activite-label">2 día</div>
                <i class='bi bi-circle-fill activity-badge text-warning align-self-start'></i>
                <div class="activity-content">
                    Apertura de Carga Estadistica Mensual
                </div>
            </div>

            <div class="activity-item d-flex">
                <div class="activite-label">3 día</div>
                <i class='bi bi-circle-fill activity-badge text-success align-self-start'></i>
                <div class="activity-content">
                    Nueva Auditoria LOD1 generada por Estefano Denmark
                </div>
            </div>

            <div class="activity-item d-flex">
                <div class="activite-label">3 día</div>
                <i class='bi bi-circle-fill activity-badge text-success align-self-start'></i>
                <div class="activity-content">
                Nueva Auditoria Trabajo Seguro generada por Estefano Denmark
                </div>
            </div>
            <div class="activity-item d-flex">
                <div class="activite-label">2 sem</div>
                <i class='bi bi-circle-fill activity-badge text-muted align-self-start'></i>
                <div class="activity-content">
                    Baja de Usuario #54 Nicolas Ruiz
                </div>
            </div> -->

        </div>

    </div>
</div>