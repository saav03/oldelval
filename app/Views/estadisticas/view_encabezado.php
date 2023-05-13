<?php if ($estadistica[0]['atrasado']) : ?>
    <div class="row est_fuera_tiempo">
        <p>Estadística cargada fuera de tiempo</p>
    </div>
<?php endif; ?>
<div class="row p-3">
    <div class="col-xs-12 col-md-4 text-center">
        <div class="divBox">
            <div class="divBox_title">
                <p>Cargada Por</p>
            </div>
            <div class="divBox_contain">
                <input type="text" class="form-control inp_estilos" value="<?= $estadistica[0]['u_carga_nombre'] . ' ' . $estadistica[0]['u_carga_apellido'] ?>" readonly>
            </div>
        </div>
    </div>
    <div class="col-xs-12 col-md-4 text-center">
        <div class="divBox">
            <div class="divBox_title">
                <p>Contratista</p>
            </div>
            <div class="divBox_contain">
                <select name="contratista" id="contratista" class="form-control sz_inp inp_estilos" disabled>
                    <?php foreach ($contratistas as $c) :
                        if ($c['id'] == $estadistica[0]['contratista']) { ?>
                            <option value="<?= $c['id'] ?>" selected><?= $c['nombre'] ?></option>
                        <?php } else { ?>
                            <option value="<?= $c['id'] ?>"><?= $c['nombre'] ?></option>
                        <?php } ?>
                    <?php endforeach ?>
                </select>

            </div>
        </div>
    </div>
    <div class="col-xs-12 col-md-4 text-center">
        <div class="divBox">
            <div class="divBox_title">
                <p>Fecha Carga</p>
            </div>
            <div class="divBox_contain">
                <input type="date" class="form-control inp_estilos" name="fecha_carga" id="fecha_carga" value="<?= $estadistica[0]['f_fecha_hora_carga'] ?>" disabled>
            </div>
        </div>
    </div>
    <div class="col-xs-12 col-md-6 text-center">
        <div class="divBox">
            <div class="divBox_title">
                <p>Proyecto</p>
            </div>
            <div class="divBox_contain">
                <select name="proyecto" id="proyecto" class="form-control sz_inp inp_estilos" disabled>
                    <?php foreach ($proyectos as $p) :
                        if ($p['id'] == $estadistica[0]['proyecto']) { ?>
                            <option value="<?= $p['id'] ?>" selected><?= $p['nombre'] ?></option>
                        <?php } else { ?>
                            <option value="<?= $p['id'] ?>"><?= $p['nombre'] ?></option>
                        <?php } ?>
                    <?php endforeach ?>
                </select>
            </div>
        </div>
    </div>
    <div class="col-xs-12 col-md-6 text-center">
        <div class="divBox">
            <div class="divBox_title">
                <p>Módulo</p>
            </div>
            <div class="divBox_contain">
                <select name="modulo" id="modulo" class="form-control sz_inp inp_estilos" disabled>
                    <?php foreach ($modulos as $m) :
                        if ($m['id'] == $estadistica[0]['modulo']) { ?>
                            <option value="<?= $m['id'] ?>" selected><?= $m['nombre'] ?></option>
                        <?php } else if ($estadistica[0]['modulo'] == -1) { ?>
                            <option>No Aplica</option>
                        <?php } ?>
                    <?php endforeach ?>
                </select>
            </div>
        </div>
    </div>
    <div class="col-xs-12 col-md-6 text-center">
        <div class="divBox">
            <div class="divBox_title">
                <p>Estación</p>
            </div>
            <div class="divBox_contain">
                <select name="estacion" id="estacion" class="form-control sz_inp inp_estilos" disabled>
                    <?php if ($estadistica[0]['estacion'] != null) { ?>
                        <?php foreach ($estaciones as $e) :
                            if ($e['id'] == $estadistica[0]['estacion']) { ?>
                                <option value="<?= $e['id'] ?>" selected><?= $e['nombre'] ?></option>
                            <?php } else if ($estadistica[0]['estacion'] == -1) { ?>
                                <option>No Aplica</option>
                            <?php } ?>
                        <?php endforeach ?>
                    <?php } else { ?>
                        <option value="">No se especificó</option>
                    <?php }  ?>
                </select>
            </div>
        </div>
    </div>
    <div class="col-xs-12 col-md-6 text-center">
        <div class="divBox">
            <div class="divBox_title">
                <p>Sistema de Oleoducto</p>
            </div>
            <div class="divBox_contain">
                <select name="estacion" id="estacion" class="form-control sz_inp inp_estilos" disabled>
                    <?php if ($estadistica[0]['sistema'] != null) { ?>

                        <?php foreach ($sistemas as $s) :
                            if ($s['id'] == $estadistica[0]['sistema']) { ?>
                                <option value="<?= $s['id'] ?>" selected><?= $s['nombre'] ?></option>
                            <?php } else if ($estadistica[0]['sistema'] == -1) { ?>
                                <option>No Aplica</option>
                            <?php } ?>
                        <?php endforeach ?>

                    <?php } else { ?>
                        <option value="">No se especificó</option>
                    <?php }  ?>
                </select>
            </div>
        </div>
    </div>