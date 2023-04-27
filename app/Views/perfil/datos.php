<div class="container_primary">
                <div class="card c-perfil">
                    <div class="card-header text-center header-perfil">
                        <h5 style="margin: 0px;"><b>Perfil</b></h5>
                    </div>
                    <div class="card-body">
                        <div class="row border-perfil-top">
                            <div class="col-4">
                                <p class="text-muted p-perfil m-0">Nombre</p>
                            </div>
                            <div class="col-8"><input type="text" class="form-control sz_inp input-perfil" value="<?= $nombre_usuario ?>" readonly></div>
                        </div>
                        <div class="row border-perfil">
                            <div class="col-4">
                                <p class="text-muted p-perfil m-0">Apellido</p>
                            </div>
                            <div class="col-8"><input type="text" class="form-control input-perfil sz_inp" value="<?= $apellido ?>" readonly></div>
                        </div>
                        <div class="row border-perfil">
                            <div class="col-4">
                                <p class="text-muted p-perfil m-0">DNI</p>
                            </div>
                            <div class="col-8"><input type="text" class="form-control input-perfil sz_inp" value="<?= $dni ?>" readonly></div>
                        </div>
                        <div class="row border-perfil">
                            <div class="col-4">
                                <p class="text-muted p-perfil m-0">Fecha de Nacimiento</p>
                            </div>
                            <div class="col-8"><input type="date" class="form-control input-perfil sz_inp" value="<?= $fecha_nacimiento ?>" readonly></div>
                        </div>
                        <div class="row border-perfil">
                            <div class="col-4">
                                <p class="text-muted p-perfil m-0">Empresa</p>
                            </div>
                            <div class="col-8"><input type="text" class="form-control input-perfil sz_inp" value="<?= isset($nombre_empresa) ? $nombre_empresa : 'Blister Technologies' ?>" readonly></div>
                        </div>
                        <div class="row border-perfil">
                            <div class="col-4">
                                <p class="text-muted p-perfil m-0">Competencia</p>
                            </div>
                            <div class="col-8"><input type="text" class="form-control input-perfil sz_inp" value="<?= $competencia ?>" readonly></div>
                        </div>
                    </div>
                    <div class="card-header text-center header-perfil">
                    <h5 style="margin: 0px;"><b>Datos Extras</b></h5>
                    </div>
                    <div class="card-body">
                    <div class="row border-perfil-top">
                            <div class="col-4">
                                <p class="text-muted p-perfil m-0">Localidad</p>
                            </div>
                            <div class="col-8"><input type="text" class="form-control input-perfil sz_inp" value="<?= $localidad ?>" readonly></div>
                        </div>
                        <div class="row border-perfil">
                            <div class="col-4">
                                <p class="text-muted p-perfil m-0">Número de Teléfono</p>
                            </div>
                            <div class="col-8"><input type="number" class="form-control input-perfil sz_inp" value="<?= $telefono ?>" readonly></div>
                        </div>
                    </div>
                    <div class="card-footer text-muted text-center header-perfil">
                        Ultima Modificación: <?= $fecha_modificacion ? $fecha_modificacion : "-- -- --"; ?>hs
                    </div>
                </div>
            </div>