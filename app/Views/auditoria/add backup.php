<style>
    .seccion_respuestas .btn.naranja.active {
        border: 1px solid #c79a30;
        background-color: #fff4c9;
    }


    .seccion_respuestas .btn.verde.active {
        border: 1px solid #135c10;
        background-color: #88bf65;
    }

    .seccion_respuestas .btn.rojo.active {
        border: 1px solid #7a1a1a;
        background-color: #f26363;
    }

    .seccion_respuestas .btn {
        border: 1px solid #a6a5a4;
        background-color: #efefef;
    }

    .seccion_respuestas .btn:not(.active) i {
        color: #545454 !important;
    }

    .left_btn {
        padding: 10px 12px;
        margin: 0px 0px !important;
        border-radius: 7px 0px 0px 7px;
        border: none !important;
    }

    .middle_btn {
        padding: 10px 15px;
        margin: 0px 0px !important;
        border-top: none !important;
        border-bottom: none !important;
    }

    .right_btn {
        padding: 10px 12px;
        margin: 0px 0px !important;
        border-radius: 0px 7px 7px 0px;
        border: none !important;
    }

    .active {
        box-shadow: inset 0 3px 5px rgba(0, 0, 0, .125);
    }
</style>

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
            <div>

                <div class="d-flex justify-content-between align-items-center">
                    <div class="form-group">
                        <p class="pregunta-text m-0">1. ¿El conductor posee una licencia vigente y válida para el tipo de vehiculo que maneja?</p>
                    </div>
                    <div class="seccion_respuestas">
                        <div class="form-group">
                            <div class="btn-group btn-group-toggle" data-toggle="buttons">
                                <label class="btn verde btn-default left_btn sz_inp" title="Cumple">
                                    <input type="radio" value="1" name="preguntas[1]">
                                    <i class="fa-lg fa fa-check" aria-hidden="true" style="color: rgb(19, 92, 16);"></i>
                                </label>

                                <label class="btn rojo btn-default middle_btn sz_inp" title="No Cumple">
                                    <input type="radio" value="0" name="preguntas[1]">
                                    <i class="fa-lg fa fa-times" aria-hidden="true" style="color: rgb(122, 26, 26);"></i>
                                </label>

                                <label class="btn naranja btn-default right_btn active sz_inp" title="No Aplica">
                                    <input type="radio" value="-1" name="preguntas[1]" checked>
                                    <i class="fa-lg fa fa-minus" aria-hidden="true" style="color: orange;"></i>
                                </label>
                            </div>
                        </div>
                    </div>
                </div>
                <br>
                <div class="form-group">
                    <input type="text" class="form-control sz_inp" placeholder="Comentario (Opcional - 300 caracteres)">
                </div>
            </div>

            <div>
                <div class="d-flex justify-content-between align-items-center mt-3">
                    <div class="form-group">
                        <p class="pregunta-text m-0">2. ¿Están bien asegurados todos los elementos de la cabina? (todos los elementos debidamente asegurados)</p>
                    </div>
                    <div class="seccion_respuestas">
                        <div class="form-group">
                            <div class="btn-group btn-group-toggle" data-toggle="buttons">
                                <label class="btn verde btn-default left_btn sz_inp" title="Cumple">
                                    <input type="radio" value="1" name="preguntas[2]">
                                    <i class="fa-lg fa fa-check" aria-hidden="true" style="color: rgb(19, 92, 16);"></i>
                                </label>

                                <label class="btn rojo btn-default middle_btn sz_inp" title="No Cumple">
                                    <input type="radio" value="0" name="preguntas[2]">
                                    <i class="fa-lg fa fa-times" aria-hidden="true" style="color: rgb(122, 26, 26);"></i>
                                </label>

                                <label class="btn naranja btn-default right_btn sz_inp active" title="No Aplica">
                                    <input type="radio" value="-1" name="preguntas[2]" checked>
                                    <i class="fa-lg fa fa-minus" aria-hidden="true" style="color: orange;"></i>
                                </label>
                            </div>
                        </div>
                    </div>
                </div>
                <br>
                <div class="form-group">
                    <input type="text" class="form-control sz_inp" placeholder="Comentario (Opcional - 300 caracteres)">
                </div>
            </div>
        </div>
    </div>
</div>

</div>