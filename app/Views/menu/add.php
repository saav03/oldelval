<style>
    .input-group-text {
        border-radius: 5px 0px 0px 5px;
    }

    #icon_preview {
        height: 100%;
        border-radius: 0px 5px 5px 0px;

    }
</style>
<link href="<?= base_url() ?>/assets/css/gijgo.min.css" rel="stylesheet">

<div class="container-fluid">
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Nuevo Item de Menu</h1>
    </div>
    <div class="row">
        <div class="col-xl-12 col-lg-12">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary" style="font-weight: 600;">Completar datos</h6>
                </div>
                <div class="card-body">
                    <form method="post" id="addMenuForm" action="<?= base_url('/Menu/add') ?>">
                        <div class="container">
                            <?= $inputs ?>
                        </div>
                        <div class="row justify-content-end">
                            <div class="col-sm-3 col-xs-12">
                                <input type="submit" name="buttonAdd" id="buttonAdd" class="btn_modify" value="Agregar">
                            </div>
                        </div>
                    </form>
                </div>
            </div>

            <div class="container-fluid">
                <div id="arbolito"></div>
            </div>
        </div>
    </div>
</div>
<script src="<?= base_url('/assets/js/gijgo.js') ?>"></script>

<script>
    const formulario = document.getElementById('addMenuForm');
    const confirmButtonData = {
        text: "Volver a Menúes",
        url: GET_BASE_URL() + "Menu",
        appendResult: false
    };
    const cancelButtonData = {
        text: "Seguir Agregando",
        url: GET_BASE_URL() + "/Menu/viewAdd",
        appendResult: false
    };
    addSubmitHandler(formulario, "buttonAdd", confirmButtonData, cancelButtonData);
</script>