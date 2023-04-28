<style>
    .foto-perfil {
        width: 120px;
        height: 120px;
        border-radius: 50px;
    }

    .nombre-perfil {
        padding: 15px;
    }

    .input-perfil {
        border: none;
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
    }

    .d-none {
        display: none;
    }

    .c-perfil {
        box-shadow: none;
        border: 1px solid #f4f4f4 !important;
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
</style>

<script src="<?= base_url('assets/js/dynamic-table-cells-editable.js') ?>"></script>

<?php 
// echo '<pre>';
// var_dump($datos_basicos[0]);
// echo '</pre>';
// exit;
?>

<div class="container">
    <div class="row">
        <div class="col-md-4 col-xs-12">
            <div class="container_secondary">
                <?= view('usuario/sidebar', $datos_basicos[0]) ?>
            </div>
        </div>
        <div class="col-md-7 col-xs-12">
            <div id="p-d">
                <?= view('usuario/datos', $datos_basicos[0]) ?>
            </div>
            <div id="p-s" class="d-none">
                <?= view('usuario/ajustes', $datos_basicos[0]) ?>
            </div>
            <div id="p-a" class="d-none">
                <?= view('usuario/otros_ajustes', $datos_basicos[0]) ?>
            </div>
            <div id="p-h" class="d-none">
                <?= view('usuario/ingresos', $datos_basicos[0]) ?>
            </div>
            <div id="p-p" class="d-none">
                <?= view('usuario/permisos', $datos_basicos[0]) ?>
            </div>
        </div>
    </div>
</div>