<style>
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
<div id="info_user" class="container tab-pane active"><br>
    <div class="row">
        <h5 style="text-align: center; font-weight: bold; color: #798eb3;">Información del Usuario</h5>
        <div class="row justify-content-center mb-2">
            <div class="col-xs-12 col-md-2 text-center">
                <span class="rounded">
                    <div class="text-center img-placeholder " onClick="triggerClick()">
                    </div>
                    <img style="cursor: pointer;" src="<?= $imagen_perfil != NULL ? base_url('uploads/fotosPerfil/') . '/' . $imagen_perfil : base_url('assets/images/perfil/no-pic.png'); ?>" onClick="triggerClick()" id="profileDisplay">
                </span>
                <input type="file" name="profileImage" size="20" onChange="displayImage(this)" id="profileImage" class="form-control" style="display: none;">
            </div>
        </div>
        <div class="col-xs-12 col-md-3">
            <label for="nombre" class="mb-1 fw-semibold">Nombre</label>
            <input type="text" class="form-control sz_inp" value="<?= $nombre_usuario ?>" name="nombre" id="nombre" placeholder="Ingrese nombre...">
        </div>
        <div class="col-xs-12 col-md-3">
            <label for="apellido" class="mb-1 fw-semibold">Apellido</label>
            <input type="text" class="form-control sz_inp" value="<?= $apellido ?>" name="apellido" id="apellido" placeholder="Ingrese apellido...">
        </div>
        <div class="col-xs-12 col-md-3">
            <label for="dni" class="mb-1 fw-semibold">DNI</label>
            <input type="text" class="form-control sz_inp" value="<?= $dni != 0 ? $dni : '' ?>" name="dni" id="dni" placeholder="Ingrese N°DNI...">
        </div>
        <div class="col-xs-12 col-md-3">
            <label for="dni" class="mb-1 fw-semibold">Fecha de Nacimiento</label>
            <input type="date" class="form-control sz_inp" value="<?= $fecha_nacimiento ?>" name="fecha_nacimiento" id="fecha_nacimiento">
        </div>

        <input type="hidden" name="id_usuario" value="<?= $id_usuario ?>">
    </div>

    <br>
    <hr>

    <div class="row">

        <h5 style="text-align: center; font-weight: bold; color: #798eb3;">Datos Extras</h5>

        <div class="col-xs-12 col-md-4">
            <label for="grupos" class="mb-1 fw-semibold">Competencia <small><em>(Opcional)</em></small></label>
            <input type="text" class="form-control sz_inp" value="<?= $competencia; ?>" name="competencia" id="competencia" placeholder="Ingrese su competencia">
        </div>
        <br>
        <div class="col-xs-12 col-md-4">
            <label for="grupos" class="mb-1 fw-semibold">Localidad</label>
            <input type="text" class="form-control sz_inp" value="<?= $localidad ?>" name="localidad" id="localidad" placeholder="Ingrese su localidad">
        </div>
        <br>
        <div class="col-xs-12 col-md-4">
            <label for="grupos" class="mb-1 fw-semibold">Número de Teléfono</label>
            <input type="text" class="form-control sz_inp" value="<?= $telefono != 0 ? $telefono : ''  ?>" name="telefono" id="telefono" placeholder="Ingrese su teléfono">
        </div>
    </div>

    <br>
    <hr>

    <div class="row">
        <h5 style="text-align: center; font-weight: bold; color: #798eb3;">Acceso</h5>
        <br>
        <div class="col-xs-12 col-md-3">
            <label for="nombre" class="mb-1 fw-semibold">Nickname</label>
            <input type="text" class="form-control sz_inp" value="<?= $usuario ?>" name="usuario" id="usuario" placeholder="Ingrese su nickname">
        </div>
        <div class="col-xs-12 col-md-3">
            <label for="nombre" class="mb-1 fw-semibold">Datos del Correo</label>
            <input type="text" class="form-control sz_inp" value="<?= $correo ?>" name="correo" id="correo" placeholder="Ingrese su correo">
        </div>
        <div class="col-xs-12 col-md-3">
            <label for="nombre" class="mb-1 fw-semibold">Clave de Acceso</label>
            <input type="password" class="form-control sz_inp" name="password" id="password" placeholder="Ingrese su nueva clave">
        </div>
        <div class="col-xs-12 col-md-3 text-center">
            <label class="mb-1 fw-semibold">¿Puede ser SuperAdmin?</label>
            <br>
            <input type="checkbox" name="superadmin" value="1" <?= $superadmin == 1 ? 'checked' : ''; ?> style="transform: scale(1.3);">
        </div>
    </div>
</div>
<br>

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