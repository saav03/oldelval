 <!-- Inspecciones Card -->
 <div class="col-xxl-6 col-xl-12">
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
        <div class="col-xxl-6 col-xl-12">

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