<div class="container_primary">
    <div class="card c-perfil">
        <div class="card-header text-center header-perfil">
            <h5 style="margin: 0px;"><b>Perfil</b></h5>
        </div>
        <div class="card-body">
            <div class="row border-perfil-top">
                <div class="col-4">
                    <p class="m-0 text-muted p-perfil fw-semibold">Nombre</p>
                </div>
                <div class="col-8"><input type="text" class="form-control input-perfil sz_inp" value="<?= $nombre_usuario ?>" readonly></div>
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
                <div class="col-8"><input type="text" class="form-control input-perfil sz_inp" value="<?= $competencia ? $competencia : 'No se ha asignado competencia' ?>" readonly></div>
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
            Ultima Modificación: <?= isset($last_mod['fecha_hora_format']) ? $last_mod['fecha_hora_format'] : "-- / -- / -- "; ?> <?= isset($last_mod['nombre_editor']) ? 'hs por ' . $last_mod['nombre_editor'] . ' ' . $last_mod['apellido_editor'] : '' ?>
        </div>
    </div>

    <div class="card p-2">
        <div class="text-center">
            <p class="p-0 m-0 fw-semibold">¿Desea notificarle las credenciales de ingreso al usuario?</p>
            <button class="btn_modify mt-2" data-bs-toggle="modal" data-bs-target="#modal_credenciales">Generar Envío de Credenciales</button>
        </div>
    </div>
</div>

<!-- Modal Credenciales de Ingreso -->
<div class="modal fade" id="modal_credenciales" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h6 class="modal-title" id="exampleModalLabel">Credenciales de Ingreso</h6>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form method="POST" id="form_credenciales">
                    <input type="hidden" name="id_usuario" value="<?= $id_usuario ?>">
                    <div class="row">
                        <div class="col-xs-12">
                            <label class="mb-1 fw-semibold sz_inp">Correo</label>
                            <input type="mail" class="form-control sz_inp inp_custom" name="correo" value="<?= $correo; ?>">
                        </div>
                        <div class="col-xs-12">
                            <label class="mb-1 mt-2 fw-semibold sz_inp">Descripción del mensaje a enviar</label>
                            <textarea class="form-control sz_inp inp_custom" name="mensaje" cols="30" rows="5">Le informamos que se le han otorgado las credenciales de acceso necesarias para iniciar sesión en nuestro sistema. A continuación, encontrará la información de inicio de sesión que le permitirá acceder a todas las funcionalidades y recursos disponibles</textarea>
                        </div>
                        <div class="col-xs-12">
                            <label class="mb-1 mt-2 fw-semibold sz_inp">Clave de acceso</label>
                            <input type="text" class="form-control sz_inp inp_custom" name="clave" placeholder="Ingrese la clave de acceso del usuario">
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn_mod_close" data-bs-dismiss="modal">Cerrar</button>
                <button type="button" class="btn_modify" id="btn_submit_credenciales">Enviar Correo</button>
            </div>
        </div>
    </div>
</div>
<script src="<?= base_url("assets/js/usuario/submit_credenciales.js") ?>"></script>