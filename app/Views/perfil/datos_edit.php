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
        <h5 style="text-align: center; font-weight: bold; color: #798eb3;">Información del Usuario</h5>
        <div class="col-xs-12 col-md-3">
            <label for="nombre" class="mb-1 fw-semibold">Nombre <small>(*)</small></label>
            <input type="text" class="form-control sz_inp" value="<?= $nombre_usuario ?>" name="nombre" id="nombre" placeholder="Ingrese nombre...">
        </div>
        <div class="col-xs-12 col-md-3">
            <label for="apellido" class="mb-1 fw-semibold">Apellido <small>(*)</small></label>
            <input type="text" class="form-control sz_inp" value="<?= $apellido ?>" name="apellido" id="apellido" placeholder="Ingrese apellido...">
        </div>
        <div class="col-xs-12 col-md-3">
            <label for="dni" class="mb-1 fw-semibold">DNI <small>(*)</small></label>
            <input type="text" class="form-control sz_inp" value="<?= $dni ?>" name="dni" id="dni" placeholder="Ingrese N°DNI...">
        </div>
        <div class="col-xs-12 col-md-3">
            <label for="dni" class="mb-1 fw-semibold">Fecha de Nacimiento <small><em>(Opcional)</em></small></label>
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
            <input type="text" class="form-control sz_inp" value="<?= $competencia ?>" name="competencia" id="competencia" placeholder="Ingrese su competencia">
        </div>
        <br>
        <div class="col-xs-12 col-md-4">
            <label for="grupos" class="mb-1 fw-semibold">Localidad <small><em>(Opcional)</em></small></label>
            <input type="text" class="form-control sz_inp" value="<?= $localidad ?>" name="localidad" id="localidad" placeholder="Ingrese su localidad">
        </div>
        <br>
        <div class="col-xs-12 col-md-4">
            <label for="grupos" class="mb-1 fw-semibold">Número de Teléfono <small>(*)</small></label>
            <input type="number" class="form-control sz_inp" value="<?= $telefono ?>" name="telefono" id="telefono" placeholder="Ingrese su teléfono">
        </div>
    </div>

    <br>
    <hr>

    <div class="row">
        <h5 style="text-align: center; font-weight: bold; color: #798eb3;">Acceso</h5>
        <br>
        <div class="col-xs-12 col-md-4">
            <label for="nombre" class="mb-1 fw-semibold">Nickname <small><em>(Opcional)</em></small></label>
            <input type="text" class="form-control sz_inp" value="<?= $usuario ?>" name="usuario" id="usuario" placeholder="Ingrese su nickname">
        </div>
        <div class="col-xs-12 col-md-4">
            <label for="nombre" class="mb-1 fw-semibold">Datos del Correo <small>(*)</small></label>
            <input type="text" class="form-control sz_inp" value="<?= $correo ?>" name="correo" id="correo" placeholder="Ingrese su correo">
        </div>
        <div class="col-xs-12 col-md-4">
            <label for="nombre" class="mb-1 fw-semibold">Clave de Acceso</label>
            <input type="password" class="form-control sz_inp" name="password" id="password" placeholder="Ingrese su nueva clave">
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