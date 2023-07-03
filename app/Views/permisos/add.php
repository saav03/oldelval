<div class="container-fluid">
    <div class="row">
        <div class="blister-title-container">
            <h4 class="blister-title">Nuevo Permiso</h4>
        </div>
    </div>
</div>

<div class="container">
    <div class="row">
        <div class="card">
            <div class="card-header" style="background: white; padding: 16px; font-weight: 600; letter-spacing: 1.5px;">
                Ingrese los datos del permiso
            </div>
            <div class="card-body">
                <form id="formPermiso" method="POST">
                    <div class="row">
                        <div class="col-xs-12 col-md-4 mb-2">
                            <label class="mb-1 fw-semibold">Nombre <small>(*)</small></label>
                            <input type="text" class="form-control sz_inp" placeholder="Ingrese el nombre del permiso" name="nombre" id="nombre">
                        </div>
                        <div class="col-xs-12 col-md-4 mb-2">
                            <label class="mb-1 fw-semibold">¿Es subpermiso? <small>(*)</small></label>
                            <select name="subpermiso" id="subpermiso" class="form-control sz_inp">
                                <option value="0">-- No es subpermiso --</option>
                                <?php foreach ($permisos_parents as $permiso_item) { ?>
                                    <option value="<?= $permiso_item['id'] ?>"><?= $permiso_item['nombre'] ?></option>
                                <?php } ?>
                            </select>
                        </div>
                        <div class="col-xs-12 col-md-4 mb-2">
                            <label class="mb-1 fw-semibold">Módulo <small>(*)</small></label>
                            <input type="text" class="form-control sz_inp" placeholder="Ingrese el nombre del módulo" name="modulo" id="modulo">
                        </div>
                        <div class="col-xs-12 col-md-6 mb-2">
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
                        <div class="col-xs-12 col-md-6 mb-2">
                            <label class="mb-1 fw-semibold" for="nombre">Tipo de Módulo <small>(*)</small></label>
                            <select name="tipo_modulo" id="tipo_modulo" class="form-control sz_inp">
                                <option value="">-- Seleccione --</option>
                                <option value="heading">Encabezado</option>
                                <option value="head">Menú</option>
                                <option value="add">Agregar</option>
                                <option value="index">Histórico</option>
                            </select>
                        </div>

                        <div class="d-flex justify-content-end">
                            <button class="btn_modify" name="btnSubmit" id="btnSubmit">
                                Agregar Permiso
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
        
        <style>
            a {
                text-decoration: none;
            }
            a:hover {
                color: white;
            }
        </style>

        <div class="d-flex justify-content-start">
            <a href="<?= base_url('permisos') ?>" class="btn_modify">Volver al Histórico</a>
        </div>
    </div>
</div>

<!-- Permisos  -->
<script>
    const permisosElems = <?= json_encode($permisos) ?>;
    let subPermisosOf = <?= json_encode($permisos_parents) ?>;
</script>

<script src="<?= base_url() ?>/assets/js/permisos/add.js"></script>
