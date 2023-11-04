<link rel="stylesheet" href="<?= base_url('assets/css/auditorias/add_planilla.css') ?>">
<link rel="stylesheet" href="<?= base_url('assets/css/auditorias/aud_gral.css') ?>">
<div class="container">
    <div class="row">
        <div class="blister-title-container">
            <h4 class="blister-title">Crear Nueva CheckList</h4>
        </div>
    </div>

    <form id="form_create_aud">
        <div class="card" style="border: 1px solid #f6f6f6;box-shadow: 0px 0 30px rgb(179 179 179 / 53%);">
            <div class="card-header subtitle" style="font-weight: 600; letter-spacing: 1.5px; text-align: center; background-color: #f5e1ce!important;">
                Complete los siguientes datos
            </div>
            <div class="card-body" style="background:#fffdfc;">
                <div class="row">
                    <div class="col-xs-12 col-md-6">
                        <div class="form-group">
                            <label class="mb-1 fw-semibold">Ingrese el nombre que va a tener la auditoría</label>
                            <input type="text" class="form-control sz_inp" name="title_auditoria" placeholder="Ingrese el nombre">
                        </div>
                    </div>
                    <div class="col-xs-12 col-md-6">
                        <div class="form-group">
                            <label class="mb-1 fw-semibold">Ingrese que tipo de Auditoria va a realizar</label>
                            <select name="tipo_aud" class="form-select sz_inp">
                                <option value="">-- Seleccione --</option>
                                <option value="1">Auditoría de Control</option>
                                <option value="0">Auditoría CheckList Vehicular</option>
                                <option value="3">Auditoría CheckList Tarea de Campo</option>
                                <option value="4">Auditoría CheckList Auditoria</option>
                            </select>
                        </div>
                    </div>
                </div>

                <br>

                <hr style="color: #bbb;">

                <div id="container_bloque_preguntas"></div>

                <div class="row">
                    <div class="col-xs-12 col-md-4">
                        <button id="add_bloque_preguntas">&#x271a; Agregar Bloque de Preguntas</button>
                    </div>
                </div>

                <div class="row">
                    <div class="d-flex justify-content-end">
                        <button id="btnSubmitCrearAuditoria" class="btn_modify">Crear Nueva Auditoría</button>
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>

<script src="<?= base_url("assets/js/auditorias/create_planilla.js") ?>"></script>
<script src="<?= base_url("assets/js/auditorias/submit_crear_planilla.js") ?>"></script>