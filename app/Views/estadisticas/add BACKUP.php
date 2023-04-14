<style>
    .input-perfil {
        background-color: #fff;
        margin-top: -5px;
    }

    .border-perfil {
        position: relative;
        margin: 0 40px;
        padding: 13px 0;
        overflow: hidden;
        border-top: 1px solid #f2f2f2;

    }

    .border-perfil-top {
        position: relative;
        margin: 0 40px;
        padding: 13px 0;
        overflow: hidden;
    }

    .header-perfil {
        border: none;
        padding: 10px 0px;
        margin-bottom: 15px;
    }

    .check-perfil {
        width: 3rem !important;
        height: 1.5rem;
    }

    .check-row {
        width: 100%;
        display: flex;
        text-align: end;
    }

    .row {
        padding: 5px 0px;
    }
</style>
<div class="container">
    <form action="" method="POST" id="form-add">
        <div class="blister-title-container">
            <h4 class="blister-title">Agregar Formulario de Estadisticas</h4>
        </div>
        <div class="row d-flex text-end" style="width:100%;">
            <small style="color:lightgrey"><i>(*)Campo Obligatorio</i></small>
        </div>
        <div class="card c-perfil">
            <div class="card-header text-center header-perfil" style="background-color: #00b8e624 !important;">
                <h5 style="margin: 0px;"><b>Tipo de Formulario</b></h5>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-4 col-xs-12">
                        <label class="mb-1 fw-semibold" for="tipo_form">Seleccione <small>(*)</small></label>
                        <select class="form-select sz_inp text-center" name="tipo_form" id="tipo_form">
                            <option value="">--Tipo de Formulario--</option>
                            <?php foreach ($tipos as $t) { ?>
                                <option value="<?= $t['id'] ?>"><?= $t['nombre'] ?></option>
                            <?php } ?>
                        </select>
                    </div>
                </div>
            </div>
        </div>
        <div class="collapse" id="collapseFormulario">
            <div class="card text-center" style="border: none;">
                <div class="card-header text-center header-perfil" style="background-color: #00b8e624 !important;">
                    <h5 style="margin: 0px;" id="titulo"><b>Formulario</b></h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-4 col-xs-12">
                            <label class="mb-1 fw-semibold" for="area">Cliente <small>(*)</small></label>
                            <input type="text" class="form-control sz_inp" name="area" id="area" placeholder="Ingrese el Cliente...">
                        </div>
                        <div class="col-md-4 col-xs-12">
                            <label class="mb-1 fw-semibold" for="periodo">Periodo <small>(*)</small></label>
                            <select class="form-select sz_inp text-center" name="periodo" id="periodo">
                                <option value="">--Seleccione Periodo--</option>
                                <option value="1">Enero/2023</option>
                                <option value="2">Febrero/2023</option>
                                <option value="3">Marzo/2023</option>
                            </select>
                        </div>
                        <div class="col-md-4 col-xs-12">
                            <label class="mb-1 fw-semibold" for="fecha_hoy">Fecha Carga<small>(*)</small></label>
                            <input type="date" class="form-control sz_inp" name="fecha_hoy" id="fecha_hoy" value="<?= date('Y-m-d') ?>" disabled>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-4 col-xs-12">
                            <label class="mb-1 fw-semibold" for="area">Área <small>(*)</small></label>
                            <input type="text" class="form-control sz_inp" name="area" id="area" placeholder="Ingrese el Área...">
                        </div>
                        <div class="col-md-4 col-xs-12">
                            <label class="mb-1 fw-semibold" for="locacion">Locación <small>(*)</small></label>
                            <input type="text" class="form-control sz_inp" name="locacion" id="locacion" placeholder="Ingrese la Locación">
                        </div>
                    </div>

                    <br>
                    <div class="card-header header-perfil">
                        <div class="justify-content-between d-flex">
                            <h5 class="px-3"><b>Indicadores</b></h5>
                            <h5 class="px-3"><b>Valores</b></h5>
                        </div>

                    </div>
                    <div class="card-body">
                        <div id="indicadores-container">Seleccione un Tipo de Planilla</div>
                    </div>
                    <div class="card-header text-center header-perfil">
                        <h5 style="margin: 0px;"><b>Datos Extras</b></h5>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-12 col-xs-12">
                                <div class="form-floating">
                                    <textarea class="form-control  sz_inp" placeholder="Leave a comment here" id="textarea" style="height: 80px"></textarea>
                                    <label class="mb-1 fw-semibold" for="textarea">Comentario:</label>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card-footer" style="background-color: #00b8e624 !important;">
                    <div class="row justify-content-center">
                        <button type="button" class="btn btn-primary form-control sz_inp" style="width:100px ;" id="buttonAddUsuario">Agregar</button>
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>

<script>
    let tipos = <?= json_encode($tipos) ?>;
    let contenedor = document.getElementById('indicadores-container');

    let contador_preguntas = 1;
    $('#tipo_form').change(function() {
        $('#collapseFormulario').collapse()
        let tipo_sel = tipos.find(el => el.id == $(this).val());
        contador_preguntas = 1;
        deleteDomChildren(contenedor);
        generarPreguntas(tipo_sel.preguntas, contenedor);
    });
</script>