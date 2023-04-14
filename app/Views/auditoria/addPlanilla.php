<link rel="stylesheet" href="<?= base_url('assets/css/auditorias/add.css') ?>">

<div class="container">
    <div class="row">
        <div class="blister-title-container">
            <h4 class="blister-title">Crear Nueva Auditoría</h4>
        </div>
    </div>

    <!-- Encabezado -->
    <div class="card">
        <!-- <div class="card-header text-center header-perfil" style="background-color: #00b8e645 !important;">
            <h6 style="margin: 0px;" id="titulo"><b>Datos Generales</b></h6>
        </div> -->

        <div class="card-body">
            <div class="col-xs-12 col-md-4">
                <div class="form-group">
                    <label class="mb-1 fw-semibold">Ingrese el tipo de auditoría</label>
                    <input type="text" class="form-control sz_inp" placeholder="Ingrese el tipo">
                </div>
            </div>
        </div>
    </div>

    <div class="card">
        <div class="card-header text-center" style="background-color: #00b8e645 !important;">
            <h6 style="margin: 0px;" id="titulo"><b>Complete los siguientes datos</b></h6>
        </div>

        <div class="card-body">

        </div>
    </div>
</div>

<div class="right_col" role="main">
    <div class="page-title">
        <div class="title_left">
            <h3>Crear LOD-1 Medio Ambiente</h3>
        </div>
    </div>

    <div class="x_panel">
        <h4>Complete los siguientes datos</h4>
        <hr>

        <form id="form_preguntas" method="POST">
            <div class="row">
                <div class="col-xs-12 col-md-4">
                    <div class="form-group">
                        <label for="">Ingrese el tipo de LOD-1</label>
                        <input type="text" class="form-control" name="title_lod" placeholder="Ingrese el tipo">
                    </div>
                </div>
            </div>
            <hr>
            <div id="bloque_preguntas"></div>
            <div class="row">
                <div class="col-xs-12 col-md-4">
                    <button id="add_bloque_preguntas">&#x271a; Agregar Bloque de Preguntas</button>
                </div>
            </div>

            <div style="display: flex; justify-content: flex-end; margin-top: 30px;">
                <input class="btn btn-primary" id="btn_submit_lod1" type="submit" value="Crear Nuevo LOD-1">
            </div>
        </form>

    </div>
</div>

<script src="<?= base_url("assets/js/auditorias/addPlanilla.js") ?>"></script>