        <style>
            .card_hover:hover {
                background-color: #fbfbfb;
                transition: all .1s ease;
                cursor: pointer;
                transform: scale(1.05);
            }

            .answer-card .card-icon {
                color: yellow;
                background-color: yellow;
            }
        </style>
        <!-- Observaciones Card -->
        <div class="col-md-4">

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
                    <h5 class="card-title">Descargos Pendientes <span id="span_tarjeta">| Tarjeta M.A.S <small>(Total)</small></span></h5>

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

        <div class="col-md-4">

            <div class="card info-card answer-card card_hover">

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

                <div class="card-body" onclick="window.location.replace('<?= base_url('/TarjetaObs/rta_pendientes') ?>')">
                    <h5 class="card-title">Respuestas Pendientes <span id="span_tarjeta">| Tarjeta M.A.S <small>(Total)</small></span></h5>

                    <div class="d-flex align-items-center">
                        <div class="card-icon rounded-circle d-flex align-items-center justify-content-center">
                        <i class="fa-regular fa-comment-dots"></i>
                        </div>
                        <div class="ps-3">
                            <h6 id="cantidad_pendiente"><?= $rta_descargos_pendientes['cantidad'] ?></h6>
                            <span class="text-muted small pt-2 ps-1">de <?= $hallazgo_totales_propios['cantidad'] ?> hallazgos en total</span>
                        </div>
                    </div>
                </div>

            </div>
        </div> <!-- End Observaciones Card -->

        <div class="col-md-4">

            <div class="card info-card success-card card_hover">

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

                <div class="card-body" onclick="window.location.replace('<?= base_url('/TarjetaObs/completadas') ?>')">
                    <h5 class="card-title">Completadas <span id="span_tarjeta">| Tarjeta M.A.S <small>(Total)</small></span></h5>

                    <div class="d-flex align-items-center">
                        <div class="card-icon rounded-circle d-flex align-items-center justify-content-center">
                        <i class="fa-solid fa-book-open"></i>
                        </div>
                        <div class="ps-3">
                            <h6 id="cantidad_pendiente"><?= $get_total_tarjetas_propias_cerradas['cantidad'] ?></h6> <span class="text-muted small pt-2 ps-1">de <?= $get_total_tarjetas_propias['cantidad'] ?> tarjetas en total</span>
                        </div>
                    </div>
                </div>

            </div>
        </div> <!-- End Observaciones Card -->

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