<div class="container_primary">
    <div class="card c-perfil">
        <div class="card-header text-center header-perfil">
            <h5 style="margin: 0px;"><b>Perfil</b></h5>
        </div>
        <div class="card-body">
            <div class="row border-perfil-top">
                <div class="col-4">
                    <p class="text-muted p-perfil m-0 fw-semibold">Nombre</p>
                </div>
                <div class="col-8"><input type="text" class="form-control sz_inp input-perfil" value="<?= $nombre_usuario ?>" readonly></div>
            </div>
            <div class="row border-perfil">
                <div class="col-4">
                    <p class="text-muted p-perfil m-0 fw-semibold">Apellido</p>
                </div>
                <div class="col-8"><input type="text" class="form-control input-perfil sz_inp" value="<?= $apellido ?>" readonly></div>
            </div>
            <div class="row border-perfil">
                <div class="col-4">
                    <p class="text-muted p-perfil m-0 fw-semibold">DNI</p>
                </div>
                <div class="col-8"><input type="text" class="form-control input-perfil sz_inp" value="<?= $dni ? $dni : 'No se ha asignado un DNI' ?>" readonly></div>
            </div>
            <div class="row border-perfil">
                <div class="col-4">
                    <p class="text-muted p-perfil m-0 fw-semibold">Fecha de Nacimiento</p>
                </div>
                <div class="col-8"><input type="date" class="form-control input-perfil sz_inp" value="<?= $fecha_nacimiento ?>" readonly></div>
            </div>
            <div class="row border-perfil">
                <div class="col-4">
                    <p class="text-muted p-perfil m-0 fw-semibold">Empresa</p>
                </div>
                <div class="col-8"><input type="text" class="form-control input-perfil sz_inp" value="<?= isset($nombre_empresa) ? $nombre_empresa : 'Blister Technologies' ?>" readonly></div>
            </div>
            <div class="row border-perfil">
                <div class="col-4">
                    <p class="text-muted p-perfil m-0 fw-semibold">Competencia</p>
                </div>
                <div class="col-8"><input type="text" class="form-control input-perfil sz_inp" value="<?= $competencia ? $competencia : 'No se ha asignado una competencia' ?>" readonly></div>
            </div>
        </div>
        <div class="card-header text-center header-perfil">
            <h5 style="margin: 0px;"><b>Datos Extras</b></h5>
        </div>
        <div class="card-body">
            <div class="row border-perfil-top">
                <div class="col-4">
                    <p class="text-muted p-perfil m-0 fw-semibold">Localidad</p>
                </div>
                <div class="col-8"><input type="text" class="form-control input-perfil sz_inp" value="<?= $localidad ? $localidad : 'No se ha asignado una localidad' ?>" readonly></div>
            </div>
            <div class="row border-perfil">
                <div class="col-4">
                    <p class="text-muted p-perfil m-0 fw-semibold">Número de Teléfono</p>
                </div>
                <?php if ($telefono) : ?>
                    <div class="col-8"><input type="number" class="form-control input-perfil sz_inp" value="<?= $telefono ?>" readonly></div>
                <?php else : ?>
                    <div class="col-8"><input type="text" class="form-control input-perfil sz_inp" value="No se ha asignado un teléfono" readonly></div>
                <?php endif; ?>
            </div>
        </div>
        <div class="card-footer text-muted text-center header-perfil">
            Ultima Modificación: <?= $fecha_modificacion ? $fecha_modificacion : "-- -- --"; ?>hs
        </div>
    </div>
</div>