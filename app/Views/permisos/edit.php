<?php $entidad_inputs = isset($permiso) ? $permiso : null; ?>

<?php
$tipo_modulos = ['heading', 'menu', 'index', 'add'];
?>
<title>OLDELVAL - Editar Permiso</title>
<div class="container-fluid">
    <div class="row">
        <div class="blister-title-container">
            <h4 class="blister-title">Edición de Permiso</h4>
        </div>
    </div>
</div>

<div class="container">
    <div class="row">
        <div class="card">
            <div class="card-header" style="background: white; padding: 16px; font-weight: 600; letter-spacing: 1.5px;">
                Datos del permiso
            </div>
            <div class="card-body">
                <form id="formPermiso" method="POST">
                    <input type="hidden" name="id" value="<?= $permiso['id']; ?>">
                    <div class="row">
                        <div class="col-xs-12 col-md-6 mb-2">
                            <label class="mb-1 fw-semibold">Nombre <small>(*)</small></label>
                            <input type="text" class="form-control sz_inp" placeholder="Ingrese el nombre del permiso" name="nombre" id="nombre" value="<?= $entidad_inputs['nombre']; ?>">
                        </div>
                        <div class="col-xs-12 col-md-6 mb-2">
                            <label class="mb-1 fw-semibold">¿Es subpermiso? <small>(*)</small></label>
                            <select name="subpermiso" id="subpermiso" class="form-select sz_inp">
                                <option value="0">-- No es subpermiso --</option>
                                <?php foreach ($permisos_parents as $permiso_item) { ?>
                                    <option <?= isset($entidad_inputs['id_permiso_padre']) && $entidad_inputs['id_permiso_padre'] == $permiso_item['id'] ? 'selected' : '' ?> value="<?= $permiso_item['id'] ?>"><?= $permiso_item['nombre'] ?></option>
                                <?php } ?>
                            </select>
                        </div>
                        <div class="col-xs-12 col-md-6 mb-2">
                            <label class="mb-1 fw-semibold" for="nombre">Colocar debajo de <small>(*)</small></label>
                            <select name="orden_permiso" id="orden_permiso" class="form-select sz_inp">
                                <option value="1">* Primer posición *</option>
                                <?php foreach ($allPermisos as $permiso_item) {
                                    if ($permiso_item['id_permiso_padre'] == 0) { ?>
                                        <option value="<?= ($permiso_item['orden'] + 1) ?>"><?= $permiso_item['nombre'] ?></option>
                                <?php }
                                } ?>
                            </select>
                        </div>
                        <div class="col-xs-12 col-md-6 mb-2">
                            <label class="mb-1 fw-semibold" for="nombre">Tipo de Módulo <small>(*)</small></label>
                            <select name="tipo_modulo" id="tipo_modulo" class="form-select sz_inp">
                                <option value="">-- Seleccione --</option>
                                <option value="add" <?= $permiso['tipo_modulo'] ?>" <?= $permiso['tipo_modulo'] == 'add' ? 'selected' : ''; ?>>Agregar</option>
                                <option value="heading>" <?= $permiso['tipo_modulo'] ?>" <?= $permiso['tipo_modulo'] == 'heading' ? 'selected' : ''; ?>>Encabezado</option>
                                <option value="index" <?= $permiso['tipo_modulo'] ?>" <?= $permiso['tipo_modulo'] == 'index' ? 'selected' : ''; ?>>Index</option>
                                <option value="head" <?= $permiso['tipo_modulo'] == 'head' ? 'selected' : ''; ?>>Menu</option>
                            </select>
                        </div>

                        <div class="d-flex justify-content-end">
                            <button class="btn_modify" name="btnSubmit" id="btnSubmit">
                                Editar Permiso
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        <div class="d-flex justify-content-start">
            <a href="<?= base_url('permisos') ?>" class="btn_modify">Volver al Histórico</a>
        </div>
    </div>
</div>
<script src="<?= base_url('/assets/js/domManipulation.js') ?>"></script>

<script>
    // const permisosElems = <!?= json_encode($permisos) ?>;
    const subPermission = document.getElementById('subpermiso');
    let previous_subpermiso_value = 0;
    const permisosElems = <?= json_encode($allPermisos) ?>;
    const orderSelect = document.getElementById('orden_permiso');
    let firstOrderNumber = 1;
    const genericOrderOpt = el("option", {
        value: 1,
        selected: true
    }, "* Primera Posición *");
</script>
<script>
    /* INITIAL BEHAVIOUR */
    let permission = <?= json_encode($permiso) ?>;
    let my_parent = permission.id_permiso_padre;
    previous_subpermiso_value = my_parent;

    const initialOrderOptions = permisosElems.filter(perm => perm.id_permiso_padre == my_parent);
    if (initialOrderOptions.length > 0) {
        firstOrderNumber = Number.parseInt(initialOrderOptions[0].orden);
    } else {
        firstOrderNumber = 1;
    }
    removeChildren(orderSelect);
    genericOrderOpt.value = firstOrderNumber;
    orderSelect.appendChild(genericOrderOpt);
    initialOrderOptions.forEach(opt => {
        if (opt.id != permission.id) {
            let optionDom = el('option', {
                value: (Number.parseInt(opt.orden) + 1)
            }, opt.nombre);
            if (opt.orden == (permission.orden - 1)) {
                optionDom.selected = true;
            }
            orderSelect.appendChild(optionDom);
        }
    });
</script>

<script src="<?= base_url() ?>/assets/js/permisos/edit.js"></script>