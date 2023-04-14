<div class="container_primary">
    <div class="card c-perfil">
        <div class="card-header text-center header-perfil">
            <h5 style="margin: 0px;"><b>Acceso</b></h5>
        </div>
        <div class="card-body">
            <div class="row border-perfil-top">
                <div class="col-4">
                    <p class="text-muted p-perfil">Nickname</p>
                </div>
                <div class="col-8">
                    <input type="text" class="form-control input-perfil" value="<?= $usuario ?>" readonly>
                </div>
            </div>
            <div class="row border-perfil">
                <div class="col-4">
                    <p class="text-muted p-perfil">Direccion de Correo</p>
                </div>
                <div class="col-8">
                    <input type="text" class="form-control input-perfil" value="<?= $correo ?>" readonly>
                </div>
            </div>
        </div>
        <div class="card-header text-center header-perfil">
            <h5 style="margin: 0px;"><b>Clave de acceso</b></h5>
        </div>
        <div class="card-body">
            <div class="row border-perfil-top">
                <div class="col-4">
                    <p class="text-muted p-perfil">Clave</p>
                </div>
                <div class="col-8"><input type="password" class="form-control input-perfil" value="aaaaaaaaa" readonly></div>
            </div>
        </div>
        <div class="card-footer text-muted text-center header-perfil">
            Ultima Modificación: <?= $fecha_modificacion ? $fecha_modificacion : "-- -- --"; ?>hs
        </div>
    </div>
</div>