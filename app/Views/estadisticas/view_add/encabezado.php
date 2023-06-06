<div class="row">
    <div class="col-md-4 col-xs-12 mt-2">
        <?php if ($this->session->get('empresa') == 1 && acceso('add_cargar_est_empresa')) { ?>

            <div id="container_selectEmpresas">
                <div class="d-flex justify-content-evenly">
                    <label class="mb-1 fw-semibold">Empresa <small>(*)</small></label>
                    <label class="mb-1 fw-semibold" id="btn_selectEmpresa"><small><em><u id="selectEmpresaContent">Seleccionar Otra</u></em></small></label>
                </div>

                <select class="form-select sz_inp" id="selector_oldelval" disabled>
                    <option value="<?= $contratista[0]['id'] ?>"><?= $contratista[0]['nombre'] ?></option>
                </select>
                <input type="hidden" name="contratista" id="inp_oldelval" value="<?= $contratista[0]['id'] ?>">

                <select name="contratista" id="selector_all_empresas" style="display: none;" class="form-select sz_inp" disabled>
                    <option value="">-- Seleccione --</option>
                    <?php foreach ($contratistas as $c) : ?>
                        <option value="<?= $c['id'] ?>"><?= $c['nombre'] ?></option>
                    <?php endforeach ?>
                </select>
            </div>

        <?php } else if (!acceso('add_cargar_est_empresa') && $this->session->get('empresa') != 0) { ?>
            <label class="mb-1 fw-semibold" for="area">Empresa <small>(*)</small></label>

            <select class="form-select sz_inp" name="contratista" disabled>
                <option value="<?= $contratista[0]['id'] ?>"><?= $contratista[0]['nombre'] ?></option>
            </select>
        <?php } else {  ?>
            <label class="mb-1 fw-semibold" for="area">Empresa <small>(*)</small></label>

            <select name="contratista" id="contratista" value="<?= $this->session->get('empresa') ? $this->session->get('empresa') : '' ?>" class="form-select sz_inp">
                <option value="">-- Seleccione --</option>
                <?php foreach ($contratistas as $c) : ?>
                    <option value="<?= $c['id'] ?>"><?= $c['nombre'] ?></option>
                <?php endforeach ?>
            </select>
        <?php }   ?>
    </div>

    <div class="col-md-4 col-xs-12 mt-2">
        <label class="mb-1 fw-semibold" for="periodo">Periodo <small>(*)</small></label>
        <select class="form-select sz_inp text-center" name="periodo" id="periodo">
            <option value="">--Seleccione Periodo--</option>
            <?php foreach ($periodos as $p) : ?>
                <?php if (isset($p['atrasado'])) { ?>
                    <option value="pas_<?= $p['id'] ?>"><?= $p['nombre_mes'] . '/' . $p['anio'] . ' (' . $p['atrasado'] . ')' ?></option>
                <?php } else { ?>
                    <option value="pre_<?= $p['id'] ?>"><?= $p['nombre_mes'] . '/' . $p['anio'] ?></option>
                <?php }  ?>
            <?php endforeach; ?>
        </select>
    </div>

    <div class="col-md-4 col-xs-12">
        <label class="mb-1 fw-semibold mt-2" for="fecha_hoy">Fecha Carga<small>(*)</small></label>
        <input type="date" class="form-control text-center sz_inp simulate_dis" name="fecha_hoy" id="fecha_hoy" value="<?= date('Y-m-d') ?>" readonly>
    </div>

    <div class="col-md-4 col-xs-12 mt-2">
        <label class="mb-1 fw-semibold" for="area">Proyectos <small>(*)</small></label>
        <select name="proyecto" id="proyecto" class="form-select sz_inp" onchange="filtrarModulos(this)">
            <option value="">-- Seleccione --</option>
            <?php foreach ($proyectos as $p) : ?>
                <option value="<?= $p['id'] ?>"><?= $p['nombre'] ?></option>
            <?php endforeach ?>
        </select>
    </div>

    <div class="col-md-4 col-xs-12 mt-2">
        <label class="mb-1 fw-semibold" for="area">Módulos <small>(*)</small></label>
        <div id="selector_modulos_div">
            <select name="modulo" id="modulo" class="form-select sz_inp simulate_dis" readonly required>
                <option value="-1">-- No Aplica --</option>
            </select>
        </div>
    </div>

    <div class="col-md-4 col-xs-12 mt-2">
        <label class="mb-1 fw-semibold" for="area">Estación de Bombeo</label>
        <div id="selector_estaciones_div">
            <select name="estacion_bombeo" id="estacion_bombeo" class="form-select sz_inp" onchange="validacionEstaciones(this)">
                <option value="">-- No Aplica --</option>
                <?php foreach ($estaciones as $e) : ?>
                    <option value="<?= $e['id'] ?>"><?= $e['nombre'] ?></option>
                <?php endforeach ?>
            </select>
        </div>
    </div>

    <!-- | LO DESACTIVE POR SOLICITUD EN LA REUNIÓN 24.05.23 | -->
    <!-- <div class="col-md-4 col-xs-12 mt-2">
        <label class="mb-1 fw-semibold" for="area">Sistema de Oleoductos</label>
        <div id="selector_sistemas_div">
            <select name="sistema" id="sistema" class="form-select sz_inp" onchange="validacionSistemas(this)">
                <option value="">-- No Aplica --</option>
                <!?php foreach ($sistemas as $s) : ?>
                    <option value="<!?= $s['id'] ?>"><!?= $s['nombre'] ?></option>
                <!?php endforeach ?>
            </select>
        </div>
    </div> -->

</div>

<script>
    let btn_selectEmpresa = document.getElementById('btn_selectEmpresa');
    let selectEmpresaContent = document.getElementById('selectEmpresaContent');
    let inp_oldelval = document.getElementById('inp_oldelval');
    let selector_oldelval = document.getElementById('selector_oldelval');
    let selector_all_empresas = document.getElementById('selector_all_empresas');

    if (btn_selectEmpresa) {
        btn_selectEmpresa.addEventListener('click', () => {

            if (selector_oldelval.style.display != 'none') {
                selector_oldelval.style.display = 'none';
                selector_all_empresas.style.display = 'block';
                selector_all_empresas.removeAttribute('disabled');
                inp_oldelval.setAttribute('disabled', true);
                selectEmpresaContent.textContent = 'Seleccionar Oldelval';
            } else {
                selectEmpresaContent.textContent = 'Seleccionar Otra';
                selector_oldelval.style.display = 'block';
                selector_all_empresas.style.display = 'none';
            }

        });
    }

    let estaciones = <?= json_encode($estaciones); ?>;
</script>