<?php $entidad_inputs = isset($menu) ? $menu : null; ?>
<?php $permiso_actual = isset($permiso) ? $permiso[0] : null; ?>

<link href="<?= base_url() ?>/assets/css/virtual-select.min.css" rel="stylesheet">
<link href="<?= base_url() ?>/assets/css/style.css" rel="stylesheet">
<div class="row">
    <div class="col-12 col-md-6">
        <div class="row">
            <div class="col-xs-12 p-2">
                <div class="form-group">
                    <label for="name" class="mb-1 fw-semibold sz_inp">Nombre</label>
                    <input type="text" name="name" id="name" class="form-control sz_inp inp_custom" placeholder="Ingrese el nombre del menú" value="<?= setValue('nombre', $entidad_inputs) ?>" required>
                </div>
            </div>
            <div class="col-xs-12 p-2">
                <div class="form-group">
                    <label for="separator" class="fw-semibold sz_inp">Es Separador</label>
                    <input type="checkbox" name="separator" id="separator" value=1 <?= isset($entidad_inputs['is_heading']) && $entidad_inputs['is_heading'] == 1 ? 'checked' : '' ?>>
                </div>
            </div>
            <div class="col-xs-12 p-2">
                <div class="form-group">
                    <label for="submenu" class="mb-1 fw-semibold sz_inp">Submenu de</label>
                    <select name="submenu" id="submenu" class="form-control sz_inp inp_custom" required>
                        <option value="0">--No es submenu--</option>
                        <?php foreach ($possible_menu_parents as $menu_item) { ?>
                            <option <?= isset($entidad_inputs['id_menu_padre']) && $entidad_inputs['id_menu_padre'] == $menu_item['id'] ? 'selected' : '' ?> value="<?= $menu_item['id'] ?>"><?= $menu_item['nombre'] ?></option>
                        <?php } ?>
                    </select>
                </div>
            </div>
            <div class="col-xs-12 p-2">
                <div class="form-group">
                    <label for="order" class="mb-1 fw-semibold sz_inp">Colocar debajo de</label>
                    <select name="order" id="order" class="form-control sz_inp inp_custom" required>
                        <option value="1">* Primera Posici&oacute;n *</option>
                        <?php foreach ($allMenues as $menu_item) {
                            if ($menu_item['id_menu_padre'] == 0) { ?>
                                <option value="<?= ($menu_item['orden'] + 1) ?>"><?= $menu_item['nombre'] ?></option>
                        <?php }
                        } ?>
                    </select>
                    <!-- <input style="width: 30%" type="number" name="order" id="order" class="form-control" min=1 value="<?= setValue('orden', $entidad_inputs) ?>" required> -->
                </div>
            </div>
            <div class="col-xs-12 p-2">
                <div class="form-group">
                    <label for="path" class="mb-1 fw-semibold sz_inp">URL en el Sistema</label>
                    <div class="input-group mb-3">
                        <div class="input-group-prepend">
                            <span class="input-group-text sz_inp inp_custom" id="url-addon"><?= base_url() ?></span>
                        </div>
                        <input type="text" name="path" id="path" aria-describedby="url-addon" class="form-control sz_inp inp_custom" placeholder="" value="<?= setValue('ruta', $entidad_inputs) ?>">
                    </div>
                </div>
            </div>
            <div class="col-xs-12 p-2">
                <div class="form-group">
                    <label for="icon" class="mb-1 fw-semibold">Icono</label>
                    <div class="input-group mb-3">
                        <div class="input-group-prepend">
                            <span class="input-group-text sz_inp" id="icon-prefix">fas fa-fw fa-</span>
                        </div>
                        <input type="text" name="icon" id="icon" aria-describedby="icon-prefix" class="form-control sz_inp inp_custom" placeholder="fas fa-fw fa-" value="<?= setValue('icono', $entidad_inputs) ?>">
                        <div class="input-group-append">
                            <span class="input-group-text sz_inp" id="icon_preview"><i class="fas fa-fw fa-<?= isset($entidad_inputs['icono']) ? $entidad_inputs['icono'] : '' ?>"></i></span>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xs-12 p-2">
                <label for="" class="mb-1 fw-semibold sz_inp">¿A qué permiso pertenece el menú?</label>
                <br>
                <select name="permiso" id="permiso" data-search="true" name="native-select" class="sz_inp edit_select inp_custom" data-silent-initial-value-set="true">
                    <?php foreach ($permisos as $p) : ?>
                        <option value="<?= $p['id'] ?>"><?= $p['nombre'] ?></option>
                    <?php endforeach; ?>

                    <!-- <!?php if ($permiso_actual == null) : ?>
                        <option value="-1" data-bs-toggle="modal" data-bs-target="#exampleModal">Agregar nuevo permiso</option>
                    <!?php endif; ?> -->
                </select>
            </div>
        </div>
    </div>
</div>

<style>
    #modalPermisos .modal-header {
        padding: 10px;
    }

    #modalPermisos .modal-header h5 {
        margin: 0;
        font-size: 15px;
    }
</style>

<!-- Modal -->
<div class="modal fade" id="modalPermisos" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5><em>Nuevo Permiso</em></h5>

                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="col-xs-12">
                    <label class="mb-1 fw-semibold" for="nombre">Nombre Permiso <small>(*)</small></label>
                    <input type="text" class="form-control sz_inp" name="nombre_permiso" id="nombre_permiso" placeholder="Ingrese el permiso...">
                </div>
                <br>
                <div class="col-xs-12">
                    <label class="mb-1 fw-semibold" for="nombre">¿Es subpermiso? <small></small></label>
                    <select name="subpermiso" id="subpermiso" class="form-control sz_inp">
                        <option value="0">-- No es subpermiso --</option>
                        <?php foreach ($permisos_parents as $permiso_item) { ?>
                            <option value="<?= $permiso_item['id'] ?>"><?= $permiso_item['nombre'] ?></option>
                        <?php } ?>
                    </select>
                </div>
                <br>
                <div class="col-xs-12">
                    <label class="mb-1 fw-semibold" for="nombre">Colocar debajo de <small>(*)</small></label>
                    <select name="orden_permiso" id="orden_permiso" class="form-control sz_inp">
                        <option value="1">* Primer posición *</option>
                        <?php foreach ($permisos as $permiso_item) :
                            if ($permiso_item['id_permiso_padre'] == 0) { ?>
                                <option value="<?= ($permiso_item['orden'] + 1) ?>"><?= $permiso_item['nombre'] ?></option>
                        <?php }
                        endforeach; ?>
                    </select>
                </div>
                <br>
                <div class="col-xs-12">
                    <label class="mb-1 fw-semibold" for="nombre">Tipo de Módulo <small>(*)</small></label>
                    <select name="tipo_modulo" id="tipo_modulo" class="form-control sz_inp">
                        <option value="">-- Seleccione --</option>
                        <option value="heading">Encabezado</option>
                        <option value="head">Menú</option>
                        <option value="add">Agregar</option>
                        <option value="index">Histórico</option>
                    </select>
                </div>
            </div>
            <div class="modal-footer">
                <!-- <button type="button" id="btnModalCerrar" class="btn_mod_danger" data-bs-dismiss="modal">Cerrar</button> -->
                <button type="button" class="btn_modify" data-bs-dismiss="modal">Guardar</button>
            </div>
        </div>
    </div>
</div>

<script src="<?= base_url() ?>/assets/js/virtual-select.min.js"></script>
<script src="<?= base_url() ?>/assets/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>

<!-- Selector de permisos -->
<script>
    let modelPermisos = new bootstrap.Modal('#modalPermisos');
    let permiso_actual = <?= json_encode($permiso_actual); ?>;
    VirtualSelect.init({
        ele: '#permiso',
        placeholder: 'Seleccione un permiso',
        selectedValue: 4
    });

    if (permiso_actual != null) {
        document.getElementById('permiso').setValue(permiso_actual.id_permiso);
    } else {
        document.getElementById('permiso').setValue('');
    }

    document.querySelector('#permiso').addEventListener('change', function() {
        let id_permiso = this.value;
        if (id_permiso == -1) {
            modelPermisos.show();
        }
    });
</script>
<script>
    /*  DYNAMIC STUFF  */
    const separator = document.getElementById('separator');
    const submenu = document.getElementById('submenu');
    const icon = document.getElementById('icon');
    const path = document.getElementById('path');
    const icon_preview = document.getElementById('icon_preview');
    let previous_submenu_value = 0;
    //Lara 12/2/22 Cambiado Orden manual por -> Debajo de.
    const orderSelect = document.getElementById('order');
    const menuElems = <?= json_encode($allMenues) ?>;
    let submenuesOf = <?= json_encode($possible_menu_parents) ?>;
    let firstOrderNumber = 1;
    const genericOrderOpt = el("option", {
        value: 1,
        selected: true
    }, "* Primera Posición *");

    //
    separator.onchange = () => {
        if (submenu.value == 0) {
            iconManipulation();
        }
        toggleReadOnly(path);
    }

    submenu.onchange = () => {
        if ((previous_submenu_value == 0 && submenu.value > 0) || (previous_submenu_value > 0 && submenu.value == 0)) {
            if (!separator.checked) {
                iconManipulation();
            }
        }
        previous_submenu_value = submenu.value;
        //Lara 12/2/22
        submenuesOf = menuElems.filter(menu => menu.id_menu_padre == previous_submenu_value);
        if (submenuesOf.length > 0)
            firstOrderNumber = Number.parseInt(submenuesOf[0].orden);
        else
            firstOrderNumber = 1;
        removeChildren(orderSelect);
        genericOrderOpt.value = firstOrderNumber;
        orderSelect.appendChild(genericOrderOpt);
        submenuesOf.forEach(opt => orderSelect.appendChild(el('option', {
            value: (Number.parseInt(opt.orden) + 1)
        }, opt.nombre)));
    }
    icon.onblur = () => {
        let iconToShow = el(`i.fas fa-fw fa-${icon.value}`);
        removeChildren(icon_preview);
        icon_preview.appendChild(iconToShow);
    }

    function iconManipulation() {
        toggleInputAvailability(icon);
        if (icon.value == "") {
            removeChildren(icon_preview);
        }
    }
</script>

<!-- Permisos  -->
<script>
    const subpermiso = document.getElementById('subpermiso');
    let previos_subpermiso_value = 0;
    //Lara 12/2/22 Cambiado Orden manual por -> Debajo de.
    const orderSelectPermiso = document.getElementById('orden_permiso');
    const permisosElems = <?= json_encode($permisos) ?>;
    let subPermisosOf = <?= json_encode($permisos_parents) ?>;
    let firstOrderNumberPermiso = 1;
    const genericOrderOptPermiso = el("option", {
        value: 1,
        selected: true
    }, "* Primera Posición *");

    subpermiso.onchange = () => {
        if ((previos_subpermiso_value == 0 && subpermiso.value > 0) || (previos_subpermiso_value > 0 && subpermiso.value == 0)) {
            if (!separator.checked) {
                iconManipulation();
            }
        }
        previos_subpermiso_value = subpermiso.value;
        //Lara 12/2/22
        subPermisosOf = permisosElems.filter(permiso => permiso.id_permiso_padre == previos_subpermiso_value);
        if (subPermisosOf.length > 0)
            firstOrderNumber = Number.parseInt(subPermisosOf[0].orden);
        else
            firstOrderNumber = 1;
        removeChildren(orderSelectPermiso);
        genericOrderOptPermiso.value = firstOrderNumber;
        orderSelectPermiso.appendChild(genericOrderOptPermiso);
        subPermisosOf.forEach(opt => orderSelectPermiso.appendChild(el('option', {
            value: (Number.parseInt(opt.orden) + 1)
        }, opt.nombre)));
    }
</script>