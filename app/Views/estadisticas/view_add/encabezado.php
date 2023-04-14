<div class="row">
    <div class="col-md-3 col-xs-12 mt-2">
        <label class="mb-1 fw-semibold" for="area">Contratista <small>(*)</small></label>
        <select name="contratista" id="contratista" class="form-select sz_inp">
            <option value="">-- Seleccione --</option>
            <?php foreach ($contratistas as $c) : ?>
                <option value="<?= $c['id'] ?>"><?= $c['nombre'] ?></option>
            <?php endforeach ?>
        </select>
    </div>
    <div class="col-md-3 col-xs-12 mt-2">
        <label class="mb-1 fw-semibold" for="periodo">Periodo <small>(*)</small></label>
        <select class="form-select sz_inp text-center" name="periodo" id="periodo">
            <option value="">--Seleccione Periodo--</option>
            <?php foreach ($periodos as $p) : ?>
                <?php if (isset($p['atrasado'])) { ?>
                    <option value="pas_<?= $p['id'] ?>"><?= $p['nombre_mes'] . '/' . $p['anio'] . ' (' . $p['atrasado'] . ')' ?></option>
                <?php } else { ?>
                    <option value="pre_<?= $p['id'] ?>"><?= $p['nombre_mes'] . '/' . $p['anio']?></option>
                <?php }  ?>
            <?php endforeach; ?>
        </select>
    </div>
    <div class="col-md-2 col-xs-12">
        <label class="mb-1 fw-semibold mt-2" for="fecha_hoy">Fecha Carga<small>(*)</small></label>
        <input type="date" class="form-control text-center sz_inp" name="fecha_hoy" id="fecha_hoy" value="<?= date('Y-m-d') ?>" readonly>
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
            <select name="modulo" id="modulo" class="form-select sz_inp" disabled required>
                <option value="">-- Seleccione --</option>
            </select>
        </div>
    </div>
    <div class="col-md-4 col-xs-12 mt-2">
        <label class="mb-1 fw-semibold" for="area">Estación de Bombeo <small>(*)</small></label>
        <div id="selector_estaciones_div">

            <select name="estacion_bombeo" id="estacion_bombeo" class="form-select sz_inp">
                <option value="">-- Seleccione --</option>
                <?php foreach ($estaciones as $e) : ?>
                    <option value="<?= $e['id'] ?>"><?= $e['nombre'] ?></option>
                <?php endforeach ?>
            </select>
        </div>
    </div>
    <div class="col-md-4 col-xs-12 mt-2">
        <label class="mb-1 fw-semibold" for="area">Sistema de Oleoductos <small>(*)</small></label>
        <select name="sistema" id="sistema" class="form-select sz_inp">
            <option value="">-- Seleccione --</option>
            <?php foreach ($sistemas as $s) : ?>
                <option value="<?= $s['id'] ?>"><?= $s['nombre'] ?></option>
            <?php endforeach ?>
        </select>
    </div>
</div>

<script>
    let estaciones = <?= json_encode($estaciones); ?>;
</script>
