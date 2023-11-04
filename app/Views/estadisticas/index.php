<?php $permiso_edicion = vista_access('vista_editpermiso'); ?>
<title>OLDELVAL - Estadísticas HSE</title>
<div class="container ">
  <div class="row">
    <div class="col-md-12">
      <div class="blister-title-container">
        <h4 class="blister-title">Estadisticas HSE</h4>
      </div>
    </div>
  </div>

  <style>
    .btn_edit {
      border: none;
      background: #ff7373;
      display: flex;
      justify-content: initial;
      align-items: center;
      border-radius: 100px;
      text-align: left;
      padding: 0px;
      color: white;
      font-size: 13px;
    }

    .btn_edit svg {
      background: #ff374a;
      padding: 5px 7px;
      border-radius: 100%;
      margin-right: 5px;
    }

    .btn_edit p {
      margin: 0;
      padding-right: 20px;
      font-weight: 500;
      letter-spacing: 0.5px;
    }

    .btn_desactivar,
    .btn_activar {
      border: none;
      border-radius: 50%;
      font-size: 18px;
      background-color: #bfbfbf;
      color: white;
      cursor: pointer;
      transition: all .2s;
    }

    .btn_desactivar:hover {
      background-color: #ff6b6b;
    }

    .btn_activar:hover {
      background-color: #99d990;
    }
  </style>
  
  <div class="card" style="border: 1px solid #f6f6f6; box-shadow: 0px 0 30px rgb(1 41 112 / 5%);">
    <div class="card-header" style="background: white; padding: 16px; font-weight: 600; letter-spacing: 1.5px;">
      Listado de Planillas
    </div>
    <div class="card-body">
      <div id="tabla_estadisticas"></div>
    </div>
  </div>
</div>
<script>
  let permiso_edicion = <?= json_encode($permiso_edicion); ?>
</script>
<script src="<?= base_url() ?>/assets/js/estadisticas/historico.js"></script>