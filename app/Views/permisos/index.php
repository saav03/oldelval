<link href="<?= base_url() ?>/assets/css/permisos/permisos.css" rel="stylesheet">

<div class="container">
    <div class="row">
        <div class="blister-title-container">
            <h4 class="blister-title">Listado de Permisos</h4>
        </div>
    </div>

    <div class="row">
        <div class="col-md-8"></div>
        <div class="col-md-4" style="text-align: end;">
            <a class="btn_modify" style="text-decoration: none;" href="<?= base_url('permisos/add') ?>">Agregar Nuevo Permiso</a>
        </div>
    </div>
    <br>
</div>
<div class="container">
    <div class="row">
        <div class="card">
            <div class="card-header" style="background: white; padding: 16px; font-weight: 600; letter-spacing: 1.5px;">
                Tabla de Permisos
            </div>
            <div class="card-body">
                <div id="table"></div>
            </div>
        </div>
    </div>
</div>

<style>
    #modal_permiso .modal-header {
        padding: 10px;
    }

    #modal_permiso .modal-header h5 {
        margin: 0;
        font-size: 15px;
        letter-spacing: 1.5px;
    }
</style>


<!-- === Modal de visualización o edición del permiso === -->

<div class="modal fade" id="modal_permiso" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h5><em>Visualizar/Editar Permiso</em></h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="formPermiso" method="POST">
                    <div class="row">
                        <div class="col-xs-12 col-md-6 mb-2">
                            <label class="mb-1 fw-semibold">Nombre <small>(*)</small></label>
                            <input type="text" class="form-control sz_inp" placeholder="Ingrese el nombre del permiso" name="nombre" id="nombre">
                        </div>
                        <div class="col-xs-12 col-md-6 mb-2">
                            <label class="mb-1 fw-semibold">¿Es subpermiso? <small>(*)</small></label>
                            <select name="subpermiso" id="subpermiso" class="form-control sz_inp">
                                <option value="0">-- No es subpermiso --</option>
                                <?php foreach ($permisos_parents as $permiso_item) { ?>
                                    <option value="<?= $permiso_item['id'] ?>"><?= $permiso_item['nombre'] ?></option>
                                <?php } ?>
                            </select>
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

                        <input type="hidden" name="id_permiso" id="id_permiso">
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn_mod_close" data-bs-dismiss="modal">Cerrar</button>
                        <button type="submit" class="btn_modify" id="btnEditarPermiso">Editar</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Permisos  -->
<script>
    const permisosElems = <?= json_encode($permisos) ?>;
    let subPermisosOf = <?= json_encode($permisos_parents) ?>;
</script>

<script src="<?= base_url() ?>/assets/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
<script src="<?= base_url() ?>/assets/js/permisos/index.js"></script>