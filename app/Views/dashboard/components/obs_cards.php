        <style>
            .card_hover:hover {
                background-color: #fbfbfb;
                transition: all .1s ease;
                cursor: pointer;
                transform: scale(1.05);
            }
        </style>
        <!-- Observaciones Card -->
        <div class="col-xxl-4 col-md-6">

            <div class="card info-card warning-card card_hover">

                <div class="filter">
                    <a class="icon" href="#" data-bs-toggle="dropdown"><i class="bi bi-three-dots"></i></a>
                    <ul class="dropdown-menu dropdown-menu-end dropdown-menu-arrow">
                        <li class="dropdown-header text-start">
                            <h6>Filtros</h6>
                        </li>

                        <li>
                            <button class="dropdown-item" onclick="filterPendientes('hoy')">Hoy</button>
                        </li>

                        <li>
                            <button class="dropdown-item" onclick="filterPendientes('mes')">Este mes</button>
                        </li>
                        <li>
                            <button class="dropdown-item" onclick="filterPendientes('year')">Este año</button>
                        </li>
                    </ul>
                </div>

                <div class="card-body" onclick="window.location.replace('<?= base_url('/TarjetaObs/pendientes') ?>')">
                    <h5 class="card-title">Pendientes <span id="span_tarjeta">| Tarjeta M.A.S <small>(Total)</small></span></h5>

                    <div class="d-flex align-items-center">
                        <div class="card-icon rounded-circle d-flex align-items-center justify-content-center">
                            <i class="fa-solid fa-exclamation"></i>
                        </div>
                        <div class="ps-3">
                            <h6 id="cantidad_pendiente"><?= $obs_tarjeta_pendiente['principal']['cantidad'] ?></h6>
                            <span class="text-warning small pt-1 fw-bold" id="porcentaje_pendiente"> <?= $obs_tarjeta_pendiente['principal']['porcentaje'] ?> %</span> <span class="text-muted small pt-2 ps-1">de <?= $obs_tarjeta_pendiente['principal']['total'] ?> total</span>
                        </div>
                    </div>
                </div>

            </div>
        </div> <!-- End Observaciones Card -->

        <!-- Inspecciones Card -->
        <div class="col-xxl-4 col-md-6">
            <div class="card info-card success-card card_hover" >

                <div class="filter">
                    <a class="icon" href="#" data-bs-toggle="dropdown"><i class="bi bi-three-dots"></i></a>
                    <ul class="dropdown-menu dropdown-menu-end dropdown-menu-arrow">
                        <li class="dropdown-header text-start">
                            <h6>Filtros</h6>
                        </li>

                        <li><button class="dropdown-item" onclick="filterInspecciones('hoy')">Hoy</button></li>
                        <li><button class="dropdown-item" onclick="filterInspecciones('mes')">Este mes</button></li>
                        <li><button class="dropdown-item" onclick="filterInspecciones('year')">Este año</button></li>
                    </ul>
                </div>

                <div class="card-body" onclick="window.location.replace('<?= base_url('/auditorias') ?>')">
                    <h5 class="card-title">Realizados <span id="span_inspecciones">| Inspecciones <small>(Mensual)</small></span></h5>


                    <div class="d-flex align-items-center">
                        <div class="card-icon rounded-circle d-flex align-items-center justify-content-center">
                            <i class="fa-solid fa-list-check"></i>
                        </div>
                        <div class="ps-3">
                            <h6 id="cantidad_inspecciones"><?= $inspecciones['total'] ?></h6>
                            <span class="text-success small pt-1 fw-bold"><?= $inspecciones['este_mes'] ?></span> <span class="text-muted small pt-2 ps-1">Este mes</span>

                        </div>
                    </div>
                </div>

            </div>
        </div><!-- End Inspecciones Card -->

        <!-- Estadísticas Card -->
        <div class="col-xxl-4 col-xl-12">

            <div class="card info-card info-card card_hover">

                <div class="filter">
                    <a class="icon" href="#" data-bs-toggle="dropdown"><i class="bi bi-three-dots"></i></a>
                    <ul class="dropdown-menu dropdown-menu-end dropdown-menu-arrow">
                        
                        <li class="dropdown-header text-start">
                            <h6>Filtros</h6>
                        </li>

                        <li><button class="dropdown-item" onclick="filterEstadisticas('hoy')">Hoy</button></li>
                        <li><button class="dropdown-item" onclick="filterEstadisticas('mes')">Este mes</button></li>
                        <li><button class="dropdown-item" onclick="filterEstadisticas('year')">Este año</button></li>
                    </ul>
                </div>

                <div class="card-body" onclick="window.location.replace('<?= base_url('/estadisticas') ?>')">
                    <h5 class="card-title">Carga Mensual <span id="span_estadistica">| Estadistica <small>(Mensual)</small></span></h5>

                    <div class="d-flex align-items-center">
                        <div class="card-icon rounded-circle d-flex align-items-center justify-content-center">
                            <i class="fa-regular fa-calendar"></i>
                        </div>
                        <div class="ps-3">
                            <h6 id="cantidad_estadistica"><?= $estadisticas['principal']['este_mes'] ?></h6>
                            <span class="text-success small pt-1 fw-bold"><?= $estadisticas['hoy']['cantidad'] ?></span> <span class="text-muted small pt-2 ps-1">Planillas cargadas hoy</span>

                        </div>
                    </div>

                </div>
            </div>

        </div><!-- End Estadísticas Card -->

        <script>
            /* Observaciones Pendientes */
            let cantidad_pendiente_hoy = <?= json_encode($obs_tarjeta_pendiente['hoy']['cantidad']); ?>;
            let porcentaje_pendiente_hoy = <?= json_encode($obs_tarjeta_pendiente['hoy']['porcentaje']); ?>;

            let cantidad_pendiente_mes = <?= json_encode($obs_tarjeta_pendiente['mes']['cantidad']); ?>;
            let porcentaje_pendiente_mes = <?= json_encode($obs_tarjeta_pendiente['mes']['porcentaje']); ?>;

            let cantidad_pendiente_year = <?= json_encode($obs_tarjeta_pendiente['year']['cantidad']); ?>;
            let porcentaje_pendiente_year = <?= json_encode($obs_tarjeta_pendiente['year']['porcentaje']); ?>;

            /* Inspecciones */
            let cantidad_inspecciones_hoy = <?= json_encode($inspecciones['hoy']['cantidad']); ?>;
            let cantidad_inspecciones_mes = <?= json_encode($inspecciones['mes']['cantidad']); ?>;
            let cantidad_inspecciones_year = <?= json_encode($inspecciones['year']['cantidad']); ?>;

            /* Estadísticas */
            let cantidad_estadistica_hoy = <?= json_encode($estadisticas['hoy']['cantidad']); ?>;
            let cantidad_estadistica_mes = <?= json_encode($estadisticas['mes']['cantidad']); ?>;
            let cantidad_estadistica_year = <?= json_encode($estadisticas['year']['cantidad']); ?>;
        </script>