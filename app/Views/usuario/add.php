<link href="<?= base_url() ?>/assets/css/virtual-select.min.css" rel="stylesheet">
<style>
    .input-perfil {
        background-color: #fff;
        margin-top: -5px;
    }

    .border-perfil {
        position: relative;
        margin: 0 40px;
        padding: 10px 0;
        overflow: hidden;
        border-top: 1px solid #f2f2f2;

    }

    .border-perfil-top {
        position: relative;
        margin: 0 40px;
        padding: 13px 0;
        overflow: hidden;
    }

    .header-perfil {
        border: none;
        padding: 10px 0px;
        margin-bottom: 15px;
    }

    .check-perfil {
        width: 3rem !important;
        height: 1.5rem;
    }

    .check-row {
        width: 100%;
        display: flex;
        text-align: end;
    }

    .row {
        padding: 5px 0px;
    }

    #grupos {
        max-width: 100%;
        padding: 3px;
        border-radius: 15px;
        margin-top: -10px;
    }

    #profileDisplay {
        display: block;
        width: 140px;
        height: 140px;
        object-fit: cover;
        margin: 0px auto;
        border-radius: 50%;
    }

    .img-placeholder {
        width: 140px;
        object-fit: cover;
        color: white;
        background: black;
        opacity: .0;
        border-radius: 50%;
        z-index: 2;
        position: absolute;
        left: 50%;
        transform: translateX(-50%);
        display: none;
    }

    .img-div:hover .img-placeholder {
        display: block;
        cursor: pointer;
    }
</style>
<div class="container">
    <form action="" method="POST" id="form-add">
        <div class="blister-title-container">
            <h4 class="blister-title">Agregar Nuevo Usuario</h4>
        </div>
        <div class="row d-flex text-end" style="width:100%;">
            <small style="color:gray; letter-spacing: .5px;"><i>(*) Campo Obligatorio</i></small>
        </div>
        <div class="card card_custom" style="border: none;">
            <div class="card-header card_header_custom text-center header-perfil">
                <h6 style="margin: 0px;"><b>Datos</b></h6>
            </div>
            <div class="card-body">
                <div class="row justify-content-center mb-2">
                    <div class="col-xs-12 col-md-2 text-center">
                        <span class="rounded">
                            <div class="text-center img-placeholder " onClick="triggerClick()">
                            </div>
                            <img style="cursor: pointer;" src="<?= base_url('assets/images/perfil/no-pic.png') ?>" onClick="triggerClick()" id="profileDisplay">
                        </span>
                        <input type="file" name="profileImage" size="20" onChange="displayImage(this)" id="profileImage" class="form-control" style="display: none;">
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-4 col-xs-12">
                        <label class="mb-1 fw-semibold sz_inp" for="nombre">Apellido <small>(*)</small></label>
                        <input type="text" class="form-control sz_inp inp_custom" name="apellido" id="apellido" placeholder="Ingrese Apellido">
                    </div>
                    <div class="col-md-4 col-xs-12">
                        <label class="mb-1 fw-semibold sz_inp" for="nombre">Nombre <small>(*)</small></label>
                        <input type="text" class="form-control sz_inp inp_custom" name="nombre" id="nombre" placeholder="Ingrese nombre">
                    </div>
                    <div class="col-md-4 col-xs-12">
                        <label class="mb-1 fw-semibold sz_inp" for="nombre">DNI</label>
                        <input type="number" class="form-control sz_inp inp_custom" name="dni" id="dni" placeholder="Ingrese dni">
                    </div>
                </div>
                <div class="row">
                    <div class="col-xs-12 col-md-2"></div>
                    <div class="col-md-4 col-xs-12">
                        <label class="mb-1 fw-semibold sz_ibp" for="nombre">Fecha de nacimiento</label>
                        <input type="date" class="form-control sz_inp inp_custom" name="fec_nac" id="fec_nac">
                    </div>
                    <div class="col-md-4 col-xs-12">
                        <label class="mb-1 fw-semibold sz_inp" for="nombre">Empresa</label>
                        <select name="empresa" id="empresa" class="form-select sz_inp inp_custom">
                            <option value="">-- Seleccione --</option>
                            <?php foreach ($empresas as $e) : ?>
                                <option value="<?= $e['id'] ?>"><?= $e['nombre'] ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="col-xs-12 col-md-2"></div>
                </div>
            </div>
        </div>

        <div class="card card_custom" style="border: none;">

            <div class="card-header card_header_custom text-center header-perfil">
                <h6 style="margin: 0px;"><b>Ingreso</b></h6>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-4 col-xs-12">
                        <label class="mb-1 fw-semibold sz_inp" for="correo">Correo corporativo <small>(*)</small></label>
                        <input type="email" class="form-control sz_inp inp_custom" name="correo" id="correo" placeholder="Ingrese correo...">
                    </div>
                    <div class="col-md-4 col-xs-12">
                        <div class="mb-1 fw-semibold sz_inp" style="display: flex;justify-content: space-between;"><label for="clave">Clave de acceso <small>(*)</small></label> <label for=""><a href="#" style="text-decoration:none ;" id="dni_as_pass"><small style="color:cornflowerblue">Colocar DNI como clave</small></a></label></div>
                        <input type="password" class="form-control sz_inp inp_custom" name="clave" id="clave" placeholder="Ingrese Clave...">
                    </div>
                    <div class="col-md-4 col-xs-12">
                        <label class="mb-1 fw-semibold sz_inp" for="nombre">Grupo</label>
                        <div class="row" style="width:100%">
                            <div class="col-12">
                                <select class="sz_inp" name="grupos[]" id="grupos" style="width: 350px" multiple name="native-select" data-search="false" data-silent-initial-value-set="true">
                                    <?php
                                    foreach ($grupos as $grupo) {
                                        echo  "<option value='" . $grupo['id'] . "'>" . $grupo['nombre'] . "</option>";
                                    }
                                    ?>
                                </select>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="card card_custom" style="border: none;">

            <div class="card-header card_header_custom text-center header-perfil">
                <h6 style="margin: 0px;"><b>Datos Extras</b></h6>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-4 col-xs-12">
                        <label class="mb-1 fw-semibold sz_inp" for="nombre">Localidad</label>
                        <input type="text" class="form-control sz_inp inp_custom" name="localidad" id="localidad" placeholder="Ingrese localidad...">
                    </div>
                    <div class="col-md-4 col-xs-12">
                        <label class="mb-1 fw-semibold sz_inp" for="nombre">Numero de Telefono</label>
                        <input type="number" class="form-control sz_inp inp_custom" name="telefono" id="telefono" placeholder="Ingrese Numero...">
                    </div>
                </div>
            </div>
        </div>

        <div class="card card_custom" style="border: none;">
            <div class="card-header card_header_custom text-center header-perfil">
                <h6 style="margin: 0px;"><b>Permisos del Usuario</b></h6>
            </div>
            <div class="row">
                <div id="tree" class="inp_custom"></div>
            </div>
        </div>
    </form>
</div>

<div class="row justify-content-center">
    <button type="button" class="btn_modify" style="width:150px;" id="buttonAddUsuario">Agregar Usuario</button>
</div>

<script>
    function triggerClick(e) {
        document.getElementById('profileImage').click();
    }

    function displayImage(e) {
        if (e.files[0]) {
            var reader = new FileReader();
            reader.onload = function(e) {
                document.getElementById('profileDisplay').setAttribute('src', e.target.result);
            }
            reader.readAsDataURL(e.files[0]);
        }
    }
</script>

<script>
    let menu_tree = <?= json_encode($permisos); ?>;
    let permisos, arbol;
    $(document).ready(function() {
        arbol = $('#tree').tree({
            textField: 'nombre',
            cascadeCheck: true,
            checkedField: 'pertenece',
            primaryKey: 'id',
            uiLibrary: 'bootstrap5',
            dataSource: menu_tree,
            checkboxes: true
        });
    });
</script>
<script src="<?= base_url() ?>/assets/js/virtual-select.min.js"></script>
<script src="<?= base_url() ?>/assets/js/usuario/submit.js"></script>
<script>
    document.getElementById('dni_as_pass').addEventListener('click', () => {
        document.getElementById('clave').value = document.getElementById('dni').value;
        document.getElementById('clave').focus();
    });
</script>
<script>
    VirtualSelect.init({
        ele: '#grupos',
        placeholder: 'Seleccione uno o mas grupos',
    });

    /* Primero realizo la consulta por cada cambio que escuche en el selector del grupo */
    let form;
    let datosPermiso = [];

    document.querySelector('#grupos').addEventListener('change', function() {
        let id_grupos = this.value;

        form = new FormData();

        const getPermisos = async () => {

            if (id_grupos.length > 0) {
                for (let i = 0; i < id_grupos.length; i++) {
                    form.append('grupos[]', id_grupos[i])
                }
            } else {
                form.append('grupos[]', 0)
            }
            const response = await fetch(
                `${GET_BASE_URL()}/getPermisos`, {
                    method: 'POST',
                    body: form
                }
            );

            const data = await response.json();
            datosPermiso = data;
            return data;
        };

        (async () => {
            await getPermisos();
            arbol.destroy();

            /* for (let i = 0; i < menu_tree.length; i++) {
                menu_tree[i].pertenece = 0;
            } */
            // Aquellos permisos checkeados que son iguales a los permisos de BD le coloco el check (pertenece en 1)
            /* for (let i = 0; i < datosPermiso.length; i++) {
                if (datosPermiso[i].id == menu_tree[i].id && menu_tree[i].pertenece == 0) {
                    menu_tree[i].pertenece = 1;
                }
            } */

            // Reinicio el árbol y le seteo los permisos ya checkeados que pertenecen a esos grupos
            arbol = $('#tree').tree({
                textField: 'nombre',
                cascadeCheck: false,
                checkedField: 'pertenece',
                primaryKey: 'id',
                uiLibrary: 'bootstrap5',
                dataSource: datosPermiso,
                checkboxes: true
            });
        })();
    });
</script>