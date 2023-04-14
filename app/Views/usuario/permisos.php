<style>
    .btn-edit_normal,
    .btn-edit_cancelar,
    .btn-edit_submit {
        border: none;
        display: flex;
        justify-content: initial;
        align-items: center;
        border-radius: 5px;
        text-align: left;
        padding: 0px;
        color: white;
        font-size: 13px;
    }

    .btn-edit_normal svg,
    .btn-edit_cancelar svg,
    .btn-edit_submit svg {
        padding: 8px 12px;
        border-radius: 5px 0 0 5px;
        margin-right: 5px;
    }

    .btn-edit_normal {
        background: #67abf1;
    }

    .btn-edit_normal svg {
        background: #408ddd;
    }

    .btn-edit_cancelar {
        background: #ff3a3a;
    }

    .btn-edit_cancelar svg {
        background: #f10000;
    }

    .btn-edit_submit {
        background-color: #71e571;
    }

    .btn-edit_submit svg {
        background-color: #0abf0a63;
    }

    .btn-edit_normal p,
    .btn-edit_cancelar p,
    .btn-edit_submit p {
        margin: 0;
        padding-right: 20px;
        font-weight: 500;
        letter-spacing: 0.5px;
    }

    .sector_botones {
        display: flex;
        justify-content: space-between;
    }
</style>

<div class="container_primary">
    <form id="form_permisos">
        <div class="card c-perfil">
            <div id="div_permisos" style="opacity: 0.5; background: #cccccc0d;">
                <div class="card-header text-center header-perfil">
                    <h5 style="margin: 0px;"><b>Grupos del Usuario</b></h5>
                </div>
                <div class="card-body">

                    <input type="hidden" name="id_usuario" id="id_usuario" value="<?= $datos_basicos[0]['id_usuario'] ?>">

                    <div id="container_permisos" class="row border-perfil-top" style="pointer-events: none;">
                        <div class="col-4">
                            <p class="text-muted p-perfil">Grupos del Usuario</p>
                        </div>
                        <div class="row" style="width: 100%;">

                            <div class="col-12">
                                <select class="sz_inp edit_select" name="grupos[]" id="grupos" multiple name="native-select" data-search="false" data-silent-initial-value-set="true">
                                    <?php foreach ($grupos as $grupo) {
                                        echo "<option value='" . $grupo['id'] . "'>" . $grupo['nombre'] . "</option>";
                                    } ?>
                                </select>
                            </div>

                        </div>
                        <div class="card-header text-center header-perfil">
                            <h5 style="margin: 0px;"><b>Permisos</b></h5>
                        </div>
                        <div class="row">
                            <div class="col-12">
                                <div class="row">
                                    <div id="tree"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div style="padding: 20px;">
                <button class="btn-edit_normal">
                    <i class="fas fa-edit"></i>
                    <p>Editar</p>
                </button>
                <div id="sector_botones" style="display: none;">
                    <button class="btn-edit_cancelar">
                        <i class="fas fa-times"></i>
                        <p>Cancelar</p>
                    </button>
                    <button class="btn-edit_submit">
                        <i class="fas fa-check"></i>
                        <p>Enviar</p>
                    </button>
                </div>
            </div> <!-- Botones -->
        </div>
    </form>
</div>

<script>
    VirtualSelect.init({
        ele: '#grupos',
        placeholder: 'Seleccione uno o mas grupos',
    });
</script>

<!-- == Se dibuja el Arbol y se setean los grupos si es que existen en el selector de grupos == -->
<script>
    /* === Información de la Base de Datos === */
    // let menu_tree = <!?= json_encode($permisos); ?>;

    /* Estos permisos originales no se van a modificar */
    // let permisos_originales = <!?= json_encode($permisos_usuario); ?>;

    /** == Permisos del usuario provenientes de 'gg_rel_usuario_permiso' == */
    let permisos_usuario = <?= json_encode($permisos_usuario); ?>;

    let id_usuario = <?= json_encode($datos_basicos[0]['id_usuario']); ?>;
    
    /* == Grupos pertenecientes al usuario == */
    let grupos_usuario = <?= json_encode($grupos_usuario); ?>;
</script>

<script src="<?= base_url() ?>/assets/js/usuario/edit.js"></script>