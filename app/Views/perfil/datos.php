<div class="container_primary">
                <div class="card c-perfil">
                    <div class="card-header text-center header-perfil">
                        <h5 style="margin: 0px;"><b>Perfil</b></h5>
                    </div>
                    <div class="card-body">
                        <div class="row border-perfil-top">
                            <div class="col-4">
                                <p class="text-muted p-perfil">Nombre</p>
                            </div>
                            <div class="col-8"><input type="text" class="form-control input-perfil" value="<?= $nombre_usuario ?>" readonly></div>
                        </div>
                        <div class="row border-perfil">
                            <div class="col-4">
                                <p class="text-muted p-perfil">Apellido</p>
                            </div>
                            <div class="col-8"><input type="text" class="form-control input-perfil" value="<?= $apellido ?>" readonly></div>
                        </div>
                        <div class="row border-perfil">
                            <div class="col-4">
                                <p class="text-muted p-perfil">DNI</p>
                            </div>
                            <div class="col-8"><input type="text" class="form-control input-perfil" value="<?= $dni ?>" readonly></div>
                        </div>
                        <div class="row border-perfil">
                            <div class="col-4">
                                <p class="text-muted p-perfil">Fecha de Nacimiento</p>
                            </div>
                            <div class="col-8"><input type="date" class="form-control input-perfil" value="<?= $fecha_nacimiento ?>" readonly></div>
                        </div>
                        <div class="row border-perfil">
                            <div class="col-4">
                                <p class="text-muted p-perfil">Competencia</p>
                            </div>
                            <div class="col-8"><input type="text" class="form-control input-perfil" value="<?= $competencia ?>" readonly></div>
                        </div>
                    </div>
                    <div class="card-header text-center header-perfil">
                    <h5 style="margin: 0px;"><b>Datos Extras</b></h5>
                    </div>
                    <div class="card-body">
                    <div class="row border-perfil-top">
                            <div class="col-4">
                                <p class="text-muted p-perfil">Localidad</p>
                            </div>
                            <div class="col-8"><input type="text" class="form-control input-perfil" value="<?= $localidad ?>" readonly></div>
                        </div>
                        <div class="row border-perfil">
                            <div class="col-4">
                                <p class="text-muted p-perfil">Número de Teléfono</p>
                            </div>
                            <div class="col-8"><input type="number" class="form-control input-perfil" value="<?= $telefono ?>" readonly></div>
                        </div>
                    </div>
                    <div class="card-footer text-muted text-center header-perfil">
                        Ultima Modificación: <?= $fecha_modificacion ? $fecha_modificacion : "-- -- --"; ?>hs
                    </div>
                </div>
            </div>