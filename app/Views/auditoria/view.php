<link rel="stylesheet" href="<?= base_url('assets/css/auditorias/aud_gral.css') ?>">
<link rel="stylesheet" href="<?= base_url('assets/css/auditorias/aud_plan_control.css') ?>">

<title>OLDELVAL - <?= $auditoria['inspeccion'] ?></title>
<div class="container">
    <div class="row" style="box-shadow: 0px 0px 10px 10px rgba(250,250,250,1);">
        <div class="col-md-12" style="border-radius: 10px;background: #fcfdff;border-bottom: 2px solid #f1f1f1;border-top: 2px solid #f1f1f1;">
            <h5 class="text-center" style="padding: 10px;color: #84baeb;font-size: 24px;letter-spacing: 1px;"><?= $auditoria['inspeccion'] ?> - N°<?= $auditoria['id_auditoria']; ?></h5>

            <div class="row">
                <div class="col-xs-12 col-md-3"></div>
                <div class="col-xs-12 col-md-3 text-center">
                    <p><b>Usuario Carga</b>: <?= isset($auditoria['usuario_carga']) ? $auditoria['usuario_carga'] : '-'; ?></p>
                </div>
                <div class="col-xs-12 col-md-3 text-center">
                    <p><b>Fecha de Carga</b>: <?= isset($auditoria['fecha_carga_format']) ? $auditoria['fecha_carga_format'] : '--/--/----'; ?></p>
                </div>
                <div class="col-xs-12 col-md-3"></div>
            </div>
        </div>
    </div>
</div>

<div class="container">
    <div class="card mt-4 mb-0 card_title_initial">
        <div class="card-header card_modif_aud">
            Datos Principales
        </div>
    </div>

    <?php if ($auditoria['auditoria'] == 2) : ?>
        <div class="card">
            <div class="card-body" style="margin: 20px;">
                <div class="row">
                    <div class="col-xs-12 col-md-2 text-center">
                        <label class="sz_inp fw-semibold text-center">Contratista</label>
                        <input type="text" class="form-control sz_inp" value="<?= isset($auditoria['contratista']) ? $auditoria['contratista'] : 'No Aplica'; ?>" readonly>
                    </div>
                    <div class="col-xs-12 col-md-2 text-center">
                        <label class="sz_inp fw-semibold text-center">Equipo</label>
                        <input type="text" class="form-control sz_inp" value="<?= isset($auditoria['equipo']) ? $auditoria['equipo'] : 'No Aplica'; ?>" readonly>
                    </div>
                    <div class="col-xs-12 col-md-2 text-center">
                        <label class="sz_inp fw-semibold text-center">Conductor</label>
                        <input type="text" class="form-control sz_inp" value="<?= isset($auditoria['conductor']) ? $auditoria['conductor'] : 'No Aplica'; ?>" readonly>
                    </div>
                    <div class="col-xs-12 col-md-2 text-center">
                        <label class="sz_inp fw-semibold text-center">N° Interno</label>
                        <input type="text" class="form-control sz_inp" value="<?= isset($auditoria['num_interno']) ? $auditoria['num_interno'] : 'No Aplica'; ?>" readonly>
                    </div>
                    <div class="col-xs-12 col-md-2 text-center">
                        <label class="sz_inp fw-semibold text-center">Marca</label>
                        <input type="text" class="form-control sz_inp" value="<?= isset($auditoria['marca']) ? $auditoria['marca'] : 'No Aplica'; ?>" readonly>
                    </div>
                    <div class="col-xs-12 col-md-2 text-center">
                        <label class="sz_inp fw-semibold text-center">Modelo</label>
                        <input type="text" class="form-control sz_inp" value="<?= isset($auditoria['modelo']) ? $auditoria['modelo'] : 'No Aplica'; ?>" readonly>
                    </div>
                    <div class="col-xs-12 col-md-3 text-center">
                        <label class="sz_inp fw-semibold text-center">Patente</label>
                        <input type="text" class="form-control sz_inp" value="<?= isset($auditoria['patente']) ? $auditoria['patente'] : 'No Aplica'; ?>" readonly>
                    </div>
                    <div class="col-xs-12 col-md-3 text-center">
                        <label class="sz_inp fw-semibold text-center">Supervisor Responsable</label>
                        <input type="text" class="form-control sz_inp" value="<?= isset($auditoria['supervisor_responsable']) ? $auditoria['supervisor_responsable'] : 'No Aplica'; ?>" readonly>
                    </div>
                    <div class="col-xs-12 col-md-2 text-center">
                        <label class="sz_inp fw-semibold text-center">Fecha</label>
                        <input type="date" class="form-control sz_inp" value="<?= $auditoria['fecha_carga'] ?>" readonly>
                    </div>
                    <div class="col-xs-12 col-md-2 text-center">
                        <label class="sz_inp fw-semibold text-center">Hora</label>
                        <input type="time" class="form-control sz_inp" value="<?= $auditoria['hora'] ?>" readonly>
                    </div>
                    <div class="col-xs-12 col-md-2 text-center">
                        <label class="sz_inp fw-semibold text-center">Proyecto</label>
                        <input type="text" class="form-control sz_inp" value="<?= $auditoria['proyecto'] ?>" readonly>
                    </div>
                </div>
            </div>
        </div>
    <?php else : ?>
        <div class="card">
            <div class="card-body" style="margin: 20px;">
                <div class="row">
                    <div class="col-xs-12 col-md-4 text-center">
                        <label class="sz_inp fw-semibold text-center">Contratista</label>
                        <input type="text" class="form-control sz_inp" value="<?= isset($auditoria['contratista']) ? $auditoria['contratista'] : 'No Aplica'; ?>" readonly>
                    </div>
                    <div class="col-xs-12 col-md-4 text-center">
                        <label class="sz_inp fw-semibold text-center">Supervisor Responsable</label>
                        <input type="text" class="form-control sz_inp" value="<?= isset($auditoria['supervisor_responsable']) ? $auditoria['supervisor_responsable'] : 'No Aplica'; ?>" readonly>
                    </div>
                    <div class="col-xs-12 col-md-4 text-center">
                        <label class="sz_inp fw-semibold text-center">Cantidad del Personal</label>
                        <input type="number" class="form-control sz_inp text-center" value="<?= isset($auditoria['cant_personal']) ? $auditoria['cant_personal'] : 'No Aplica'; ?>" readonly>
                    </div>
                </div>
                <div class="row mt-2">
                    <div class="col-xs-12 col-md-3 text-center">
                        <label class="sz_inp fw-semibold text-center">Proyecto</label>
                        <input type="text" class="form-control sz_inp" value="<?= isset($auditoria['proyecto']) ? $auditoria['proyecto'] : 'No Aplica'; ?>" readonly>
                    </div>
                    <div class="col-xs-12 col-md-3 text-center">
                        <label class="sz_inp fw-semibold text-center">Módulo</label>
                        <input type="text" class="form-control sz_inp" value="<?= isset($auditoria['modulo']) ? $auditoria['modulo'] : 'No Aplica'; ?>" readonly>
                    </div>
                    <div class="col-xs-12 col-md-3 text-center">
                        <label class="sz_inp fw-semibold text-center">Estación de Bombeo</label>
                        <input type="text" class="form-control sz_inp" value="<?= isset($auditoria['estacion']) ? $auditoria['estacion'] : 'No Aplica'; ?>" readonly>
                    </div>
                    <div class="col-xs-12 col-md-3 text-center">
                        <label class="sz_inp fw-semibold text-center">Sistema de Oleoducto</label>
                        <input type="text" class="form-control sz_inp" value="<?= isset($auditoria['sistema']) ? $auditoria['sistema'] : 'No Aplica'; ?>" readonly>
                    </div>
                </div>
            </div>
        </div>
    <?php endif; ?>
</div>

<!-- Bloque de Preguntas y Respuestas -->
<?= view('auditoria/components/bloque_rtas', $auditoria); ?>

<?php if (count($auditoria['hallazgos']) > 0) : ?>
    <div class="container">
        <div class="card mt-4 mb-0 card_title_initial">
            <div class="card-header card_modif_aud">
                Observaciones
            </div>
        </div>

        <div class="card">
            <div class="card-body">
                <?php foreach ($auditoria['hallazgos'] as $h) : ?>
                    <?php if ($h['id_tipo'] == 2) : ?>
                        <?php $data['h'] = $h;
                        ?>
                        <?= view('auditoria/components/obs_mejora', $data); ?>
                        <br>
                    <?php else : ?>
                        <?php $data['h'] = $h; ?>
                        <?= view('auditoria/components/obs_positiva', $data); ?>
                        <br>
                    <?php endif; ?>
                <?php endforeach; ?>
            </div>
        </div>
        <!-- Boton de volver -->
        <div>
            <a href="<?= base_url('auditorias') ?>" class="btn_modify">Volver al Histórico</a>
        </div>
    </div>
<?php endif; ?>

<!-- Modales -->
<?= view('auditoria/modals/descargo'); ?>

<!-- Dropdown que minimiza las ventanas -->
<script>
    const drop_down = document.getElementById('drop_down_1');
    const icon_arrow = document.getElementById('arrow');
    const card_bloque = document.querySelector('.card_bloque');
    const card_bloque_body = document.querySelector('.card_bloque_body');

    if (drop_down.checked) {
        card_bloque.style.maxHeight = card_bloque_body.offsetHeight + 'px';
    }

    drop_down.addEventListener('change', function() {
        if (drop_down.checked) {
            card_bloque.style.maxHeight = card_bloque_body.offsetHeight + 'px';
            icon_arrow.classList.remove('down');
        } else {
            card_bloque.style.maxHeight = '10px';
            icon_arrow.classList.add('down');
        }
    });
</script>

<script>
    // * Al momento de agregar un descargo, seteo el id del hallazgo en el formulario del modal
    // * Así puedo tener una referencia a que hallazgo le agrego el descargo
    const btnAddDescargos = document.querySelectorAll('.add_descargo');
    for (let i = 0; i < btnAddDescargos.length; i++) {
        btnAddDescargos[i].addEventListener('click', e => {
            e.preventDefault();
            let inpIdHallazgo = document.getElementById('id_hallazgo_descargo');
            inpIdHallazgo.value = (e.target).getAttribute('data-id-hallazgo');
        })
    }
</script>
<script src="<?= base_url() ?>/assets/js/auditorias/descargo.js"></script>
<script src="<?= base_url() ?>/assets/js/addFiles.js"></script>
<script>
    new addFiles(document.getElementById("gallery_descargos"), 'adj_descargo').init();
</script>