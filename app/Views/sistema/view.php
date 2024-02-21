<title>OLDELVAL - Sistema</title>

<section class="container" style="height: 100vh;">
    <div class="blister-title-container">
        <h4 class="blister-title mb-0">Acceso a Mantenimiento</h4>
    </div>
    <div class="text-center">
        <h6>En este apartado, podrá colocar el sistema en mantenimiento en caso de haber cambios que cargar</h6>
    </div>

    <div class="card card_custom" style="border: none;">
        <div class="card-header card_header_custom text-center header-perfil">
            <h6 style="margin: 0px;"><b>Datos</b></h6>
        </div>
        <div class="card-body">
            <div class="row">
                <?php if ($maintenance['status']) : ?>
                    <div class="text-center">
                        <p class="fw-semibold"><i class="fa-solid fa-circle-exclamation"></i> Actualmente ya hay un cartel de mantenimiento</p>
                    </div>
                <?php endif; ?>
                <div class="col-md-6 col-xs-12">
                    <label class="mb-1 fw-semibold sz_inp" for="nombre">Hora <small>(Horario estimado del mantenimiento)</small></label>
                    <input type="time" class="form-control sz_inp inp_custom" name="hora" id="hora" <?= $maintenance['status'] == 1 ? 'disabled' : '' ?>>
                </div>
                <div class="col-md-6 col-xs-12">
                    <label class="mb-1 fw-semibold sz_inp" for="nombre">Fecha <small>(Fecha estimada del mantenimiento)</small></label>
                    <input type="date" class="form-control sz_inp inp_custom" name="fecha" id="fecha" <?= $maintenance['status'] == 1 ? 'disabled' : '' ?>>
                </div>

                <?php if (!$maintenance['status']) : ?>
                    <div class="d-flex justify-content-center mt-2">
                        <button type="button" class="btn_modify" id="btnLoadMaintenance" style="width:250px;">Informar Mantenimiento</button>
                    </div>
                <?php else: ?>
                    <div class="d-flex justify-content-center mt-2">
                        <button type="button" class="btn_mod_danger" id="btnRemoveMaintenance" style="width:350px;">Remover cartel de Mantenimiento</button>
                    </div>
                <?php endif; ?>

            </div>
        </div>
    </div>

</section>

<script src="<?= base_url() ?>/assets/js/sistema/maintenance.js"></script>
