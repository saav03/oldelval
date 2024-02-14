<link rel="stylesheet" href="<?= base_url('assets/css/auditorias/add.css') ?>">
<link rel="stylesheet" href="<?= base_url('assets/css/auditorias/aud_gral.css') ?>">
<link href="<?= base_url() ?>/assets/css/virtual-select.min.css" rel="stylesheet">
<link href="<?= base_url() ?>/assets/css/addFiles.css" rel="stylesheet">
<link href="<?= base_url() ?>/assets/css/fileAdder.css" rel="stylesheet">

<title>OLDELVAL - Nuevo Checklist</title>
<div class="container">
    <div class="row">
        <div class="blister-title-container">
            <h4 class="blister-title">Nuevo Checklist - Inspecciones</h4>
        </div>
    </div>

    <div class="card" style="border: 1px solid #f6f6f6;box-shadow: 0px 0 30px rgb(179 179 179 / 53%);">
        <div class="card-header subtitle" style="font-weight: 600; letter-spacing: 1.5px; text-align: center;">
            Seleccione que tipo de Inspección va a generar
        </div>

        <div class="card-body">
            <div class="row">
                <div class="col-xs-12 col-md-2"></div>
                <div class="col-xs-12 col-md-8 text-center">
                    <label class="sz_inp fw-semibold">Seleccione el Tipo</label>
                    <div class="p-3 pt-1 text-center">

                        <input id="control" type="radio" name="seleccion_checklist" class="btn-check btn_gral_check" value="1" autocomplete="off">
                        <label class="btn blanco btnsToggle riesgos" for="control">Control</label>

                        <input id="vehicular" type="radio" name="seleccion_checklist" class="btn-check btn_gral_check" value="2" autocomplete="off">
                        <label class="btn blanco btnsToggle riesgos" for="vehicular">Vehicular</label>

                        <input id="tarea_de_campo" type="radio" name="seleccion_checklist" class="btn-check btn_gral_check" value="3" autocomplete="off">
                        <label class="btn blanco btnsToggle riesgos" for="tarea_de_campo">Tarea de
                            Campo</label>
                        <input id="auditoria" type="radio" name="seleccion_checklist" class="btn-check btn_gral_check" value="4" autocomplete="off">
                        <label class="btn blanco btnsToggle riesgos" for="auditoria"> Auditoria</label>

                    </div>
                </div>
                <div class="col-xs-12 col-md-2"></div>
            </div>
        </div>
    </div>

    <section class="pb-3" id="checklist"></section>

    <!-- Modal Significancia -->
    <?php generarModalSignificancia(); ?>
    <?php generarModalConsecuenciaAmbiental(); ?>
</div>

<!-- Datos de PHP formateados a JSON -->
<script>
    let today = <?= json_encode(date('Y-m-d')); ?>;
    let contratistas = <?= json_encode($contratistas); ?>;
    let usuarios = <?= json_encode($usuarios); ?>;
    let proyectos = <?= json_encode($proyectos); ?>;
    let estaciones = <?= json_encode($estaciones); ?>;
    let sistemas = <?= json_encode($sistemas); ?>;
    let estaciones_estadisticas = <?= json_encode($estaciones); ?>;
    let sistemas_estadisticas = <?= json_encode($sistemas); ?>;

    // Tarjeta OBS
    let clasificaciones = <?= json_encode($clasificaciones); ?>;
    let tipo_hallazgo = <?= json_encode($tipo_hallazgo); ?>;
    let contratista = <?= json_encode($contratistas); ?>;
    let responsable = <?= json_encode($responsables); ?>;
    let efectos = <?= json_encode($efectos); ?>;
    let significancia = <?= json_encode($significancia); ?>;
    var aux = 1;
    var contador = 1;
    var responsables_contratista = [];
</script>
<script src="<?= base_url() ?>/assets/js/tarjetaObs/add_plan_accion.js"></script>
<script src="<?= base_url() ?>/assets/js/tarjetaObs/add_obs_positiva.js"></script>
<script src="<?= base_url("assets/js/auditorias/createBloque.js") ?>"></script>

<script>
    const seleccion_checklist = document.getElementsByName('seleccion_checklist');
    seleccion_checklist.forEach(s => {
        s.addEventListener('change', e => createBloque(s))
    });
</script>

<script src="<?= base_url() ?>/assets/js/addFiles.js"></script>
<script src="<?= base_url() ?>/assets/js/virtual-select.min.js"></script>
<script src="<?= base_url("assets/js/auditorias/add.js") ?>"></script>
<script src="<?= base_url("assets/js/auditorias/submit_add_planilla.js") ?>"></script>