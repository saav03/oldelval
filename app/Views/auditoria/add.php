<link rel="stylesheet" href="<?= base_url('assets/css/auditorias/add.css') ?>">


<div class="container">
    <div class="row">
        <div class="blister-title-container">
            <h4 class="blister-title">Nueva Auditoría</h4>
        </div>
    </div>

    <!-- Encabezado -->
    <div class="card text-center">
        <div class="card-header text-center header-perfil" style="background-color: #00b8e645 !important;">
            <h6 style="margin: 0px;" id="titulo"><b>Datos Generales</b></h6>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-3 col-xs-12 mt-2">
                    <label class="mb-1 fw-semibold" for="area">Contratista <small>(*)</small></label>
                    <select name="contratista" id="contratista" class="form-select sz_inp">
                        <option value="">-- Seleccione --</option>
                    </select>
                </div>
                <div class="col-md-3 col-xs-12 mt-2">
                    <label class="mb-1 fw-semibold" for="periodo">Periodo <small>(*)</small></label>
                    <select class="form-select sz_inp text-center" name="periodo" id="periodo">
                        <option value="">--Seleccione Periodo--</option>
                    </select>
                </div>
                <div class="col-md-2 col-xs-12">
                    <label class="mb-1 fw-semibold mt-2" for="fecha_hoy">Fecha Carga<small>(*)</small></label>
                    <input type="date" class="form-control text-center sz_inp" name="fecha_hoy" id="fecha_hoy" value="<?= date('Y-m-d') ?>" readonly>
                </div>
                <div class="col-md-4 col-xs-12 mt-2">
                    <label class="mb-1 fw-semibold" for="area">Proyectos <small>(*)</small></label>
                    <select name="proyecto" id="proyecto" class="form-select sz_inp">
                        <option value="">-- Seleccione --</option>
                    </select>
                </div>
                <div class="col-md-4 col-xs-12 mt-2">
                    <label class="mb-1 fw-semibold" for="area">Módulos <small>(*)</small></label>
                    <div id="selector_modulos_div">
                        <select name="modulo" id="modulo" class="form-select sz_inp" disabled required>
                            <option value="">-- Seleccione --</option>
                        </select>
                    </div>
                </div>
                <div class="col-md-4 col-xs-12 mt-2">
                    <label class="mb-1 fw-semibold" for="area">Estación de Bombeo <small>(*)</small></label>
                    <div id="selector_estaciones_div">

                        <select name="estacion_bombeo" id="estacion_bombeo" class="form-select sz_inp">
                            <option value="">-- Seleccione --</option>

                        </select>
                    </div>
                </div>
                <div class="col-md-4 col-xs-12 mt-2">
                    <label class="mb-1 fw-semibold" for="area">Sistema de Oleoductos <small>(*)</small></label>
                    <select name="sistema" id="sistema" class="form-select sz_inp">
                        <option value="">-- Seleccione --</option>
                    </select>
                </div>
            </div>
        </div>
    </div>

    <div class="card">
        <div class="card-header text-center header-perfil" style="background-color: #00b8e645 !important;">
            <h6 style="margin: 0px;"><b>Auditoría #1</b></h6>
        </div>
        <div class="card-body">
            <div id="bloque_subtitulos_preguntas">
                <p style="text-align: center; font-style: italic;">Seleccione una LOD-1 para poder visualizar las preguntas</p>
                <div class="col-xs-12 contenedor_preguntas">
                    <div class="col-xs-12 container_bloque">
                        <div class="row" style="display: flex; justify-content: space-around; align-items: center;">
                            <div class="col-xs-12 col-sm-4">
                                <div class="form-group">
                                    <p style="font-size: 14px; margin: 0;">1.1 Disp. N° 166/06 y N° 192/07. ¿La instalación cuenta con cartelería? </p>
                                </div>
                            </div>
                            <div class="col-sm-4 col-xs-12 seccion_respuestas">
                                <div class="form-group">
                                    <div class="btn-group btn-group" role="group">
                                        <label class="btn verde btn-default left_btn btn_switches" title="Cumple">
                                            <input type="radio" value="1" class="sz_inp" name="bloque_respuestas[1][respuestas][1]">
                                            <strong style="font-size: 14px;">Si</strong>
                                        </label>
                                        <label class="btn rojo btn-default middle_btn btn_switches" title="No Cumple">
                                            <input type="radio" value="0" class="sz_inp" name="bloque_respuestas[1][respuestas][1]">
                                            <strong style="font-size: 14px;">No</strong>
                                        </label>
                                        <label class="btn naranja btn-default right_btn btn_switches active" title="No Aplica">
                                            <input type="radio" value="-1" class="sz_inp" name="bloque_respuestas[1][respuestas][1]">
                                            <strong style="font-size: 14px;">N/A</strong>
                                        </label>
                                    </div>
                                </div>
                            </div>
                            <div class="col-sm-4 col-xs-12" style="text-align: end;">
                                <div class="form-group">
                                    <button class="btn_modify btn-info collapsed sz_inp" title="Agregar Comentario" type="button" data-bs-toggle="collapse" data-bs-target="#comentarios_preguntas-1" aria-expanded="false" aria-controls="footwear">
                                        <i class="fa fa-angle-down"></i>Comentar
                                    </button>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-xs-12 col-sm-12 hidden_sec collapse" id="comentarios_preguntas-1" aria-expanded="false" style="border-radius: 15px; height: 10px;">
                                <label>Comentario:</label>
                                <textarea class="form-control sz_inp" rows="3" maxlength="500" name="comentarios_preguntas[1]" placeholder="Observación (Opcional - Máximo 500 Caracteres)" style="resize: vertical; border: 2px solid lightgray;"></textarea>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- <script src="<!?= base_url("assets/js/base_url.js") ?>"></script> -->
<!-- <script src="<!?= base_url('assets/js/redom.min.js') ?>"></script> -->

<script>
    const btn_switches = document.querySelectorAll('.btn_switches');

    for (let i = 0; i < btn_switches.length; i++) {

        btn_switches[i].addEventListener('click', () => {
            for (let j = 0; j < btn_switches.length; j++) {
                btn_switches[j].classList.remove('active');
            }
            btn_switches[i].classList.add('active')
        });
    }
</script>

<!-- <script>
    const {
        el,
        mount,
        setChildren,
        unmount,
        setAttr
    } = redom;
</script> -->
<!-- 
<script src="<!?= base_url("assets/js/customAlerts.js") ?>"></script>
<script src="<!?= base_url("assets/js/lod_ambiente/add_lod/add.js") ?>"></script>
<script src="<!?= base_url("assets/js/lod_ambiente/add_lod/submit.js") ?>"></script> -->