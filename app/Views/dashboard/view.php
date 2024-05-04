<title>OLDELVAL - Dashboard</title>
<style>
  .maintenance {
    background-color: #edc46d;
    border: 1px solid #e3af51;
    padding: 10px;
    border-radius: 4px;
    margin-bottom: 10px;
  }

  .maintenance svg {
    font-size: 30px;
  }
</style>

<!-- Mantenimiento -->
<?php if ($maintenance['status']) : ?>
  <?= view('dashboard/components/maintenance'); ?>
<?php endif; ?>

<div class="row row-dashboard"></div>
<div class="container-fluid">
  <?php if (session()->getFlashdata('no_access')) { ?>
    <div class="row">
      <div class="alert alert-danger alert-dismissible fade show" role="alert">
        <?= session()->getFlashdata('no_access'); ?>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
      </div>
    </div>
  <?php } ?>
  <div class="pagetitle" style="display: flex; align-items: center; justify-content: space-between;">

    <div>
      <?php
      if (date('H') > 4 && date('H') <= 12) { ?>
        <h1 class="row-dashboard-forced-white" style="font-weight: bold;">¡Buenos días <?= $this->session->get('nombre') ?>! <i class="fa-solid fa-mug-hot"></i></h1>
      <?php } else if (date('H') > 12 && date('H') <= 19) { ?>
        <h1 class="row-dashboard-forced-white">¡Buenas tardes <?= $this->session->get('nombre') ?>! <i class="fa-solid fa-mountain-sun"></i></h1>
      <?php } else { ?>
        <h1 class="row-dashboard-forced-white">¡Buenas noches <?= $this->session->get('nombre') ?>! <i class="fa-solid fa-moon"></i></h1>
      <?php } ?>
      <h5 class="row-dashboard-forced-white">Estas son las últimas novedades del sistema</h5>
    </div>

    <style>
      .table_obs_riesgo_seguridad {
        font-weight: bold;
      }

      .table_obs_riesgo_seguridad td {
        padding: 5px;
      }

      .table_obs_riesgo_seguridad th {
        padding: 10px;
        border-bottom: 2px solid lightgray;
      }

      .table_obs_riesgo_seguridad .btn_style_alert {
        border: none;
        padding: 7px;
        width: 100%;
        background-color: transparent;
        transition: all .2s ease-in-out;
      }

      .table_obs_riesgo_seguridad .btn_style_alert:hover {
        background-color: #f5f5f5;
      }

      .table_obs_riesgo_seguridad .aceptable {
        color: #A9D8DF;
        background-color: #f9f9f9;
      }

      .table_obs_riesgo_seguridad .aceptable_td {
        padding: 0;
      }

      .table_obs_riesgo_seguridad .aceptable_td button {
        border-bottom: 2px solid #A9D8DF;
        border-radius: 0 0 0 5px !important;
      }


      .table_obs_riesgo_seguridad .moderado {
        color: #66b572;
        background-color: #f9f9f9;
        padding: 0;
      }

      .table_obs_riesgo_seguridad .moderado_td {
        padding: 0;
      }

      .table_obs_riesgo_seguridad .moderado_td button {
        border-bottom: 2px solid #66b572;
      }

      .table_obs_riesgo_seguridad .significativo {
        color: #E4CA4B;
        background-color: #f9f9f9;
      }

      .table_obs_riesgo_seguridad .significativo_td {
        padding: 0;
      }

      .table_obs_riesgo_seguridad .significativo_td button {
        border-bottom: 2px solid #E4CA4B;
      }

      .table_obs_riesgo_seguridad .intolerable {
        color: #DD8F60;
        background-color: #f9f9f9;
      }

      .table_obs_riesgo_seguridad .intolerable_td {
        padding: 0;
      }

      .table_obs_riesgo_seguridad .intolerable_td button {
        border-bottom: 2px solid #DD8F60;
      }

      .card_h_obs_seguridad {
        background-color: #ebebeb;
        font-weight: bold;
        color: #656565;
        border: 1px solid #d9d9d9;
        border-bottom: 0;
      }

      .rotate {
        transition: all .3s ease-in-out;
      }

      .rotate svg {
        transform: rotate(180deg);
      }
    </style>



    <?php if (false) {
      //Se comenta el nav debajo del dashboard, si es necesario implementar en un futuro Tom 27-2-23
    ?>
      <nav>
        <ol class="breadcrumb breadcrumb-forced">
          <li class="breadcrumb-item"><a class="row-dashboard-forced-white" href="<?= base_url() ?>">Home</a></li>
          <li class="breadcrumb-item active row-dashboard-forced-white">Dashboard</li>
        </ol>
      </nav>
    <?php } ?>
  </div><!-- End Page Title -->

  <hr>

  <div class="card" style="width: 80%; margin: 10px auto 20px auto; box-shadow: 0px 0px 5px 0px rgba(209,209,209,1);">
    <div class="card-header card_h_obs_seguridad text-center" id="collapse_obs_overdue" data-bs-toggle="collapse" href="#collapse_obs_seguridad" role="button" aria-expanded="false" aria-controls="collapse_obs_seguridad">
      Mis Observaciones Abiertas Vencidas (Tarjeta M.A.S) <span id="arrow_down_obs"><i class="fa-solid fa-chevron-down"></i></span>
    </div>
    <div class="collapse collapse_obs_overdue" id="collapse_obs_seguridad" style="width: 100%;">
      <div class="card-body p-0">
        <table class="table_obs_riesgo_seguridad" style="width: 100%;">
          <thead class="text-center">
            <tr>
              <th colspan="4" style="border-right: 2px solid lightgray; width: 50%;">Observaciones con Riesgo en Seguridad y Salud</th>
              <th colspan="4" style="width: 50%;">Observaciones con Impacto Ambiental</th>
            </tr>
          </thead>
          <tbody class="text-center">
            <tr>
              <th class="aceptable">Aceptable</th>
              <th class="moderado">Moderado</th>
              <th class="significativo">Significativo</th>
              <th class="intolerable" style="border-right: 2px solid lightgray;">Intolerable</th>
              <th class="aceptable">Baja</th>
              <th class="moderado">Media</th>
              <th class="significativo">Alta</th>
              <th class="intolerable">Muy Alta</th>
            </tr>

            <tr>
              <td class="aceptable_td">
                <button class="btn_style_alert" onclick="filterObsOverdue('aceptable')">5</button>
              </td>
              <td class="moderado_td">
                <button class="btn_style_alert" onclick="filterObsOverdue('moderado')">5</button>
              </td>
              <td class="significativo_td">
                <button class="btn_style_alert" onclick="filterObsOverdue('significativo')">5</button>
              </td>
              <td class="intolerable_td" style="border-right: 2px solid lightgray;">
                <button class="btn_style_alert" onclick="filterObsOverdue('intolerable')">5</button>
              </td>
              <td class="aceptable_td">
                <button class="btn_style_alert" style="border-radius: 0!important;" onclick="filterObsOverdue('baja')">5</button>
              </td>
              <td class="moderado_td">
                <button class="btn_style_alert" onclick="filterObsOverdue('media')">5</button>
              </td>
              <td class="significativo_td">
                <button class="btn_style_alert" onclick="filterObsOverdue('alta')">5</button>
              </td>
              <td class="intolerable_td">
                <button class="btn_style_alert" style="border-radius: 0 0 5px 0!important;" onclick="filterObsOverdue('muy_alta')">5</button>
              </td>
            </tr>
          </tbody>
        </table>
      </div>
    </div>
  </div>


  <section class="section dashboard">
    <div class="d-flex gap-3 w-full">
      <!-- OBS Card -->
      <?= view('dashboard/components/obs_cards'); ?>
      <!-- End OBS Card -->
    </div>


    <div class="row">

      <!-- Left side columns -->
      <div class="col-lg-8">
        <div class="row">

          <!-- <!?= view('dashboard/components/obs_cards2'); ?> -->

          <!-- Graphic -->
          <?= view('dashboard/components/graphic'); ?>
          <!-- End Graphic -->

          <!-- Recent Sales -->
          <?= view('dashboard/components/table'); ?>
          <!-- End Recent Sales -->
        </div>
      </div><!-- End Left side columns -->

      <!-- Right side columns -->
      <div class="col-lg-4">

        <!-- Recent Activity -->
        <?= view('dashboard/components/recent_activity'); ?>
        <!-- End Recent Activity -->

        <!-- Budget Report -->
        <!--?= view('dashboard/components/graphic2'); ?-->
        <!-- End Budget Report -->

        <!-- Website Traffic -->
        <?= view('dashboard/components/graphic3'); ?>
        <!-- End Website Traffic -->
      </div><!-- End Right side columns -->
  </section>

  </main><!-- End #main -->


  <!-- Button trigger modal -->
  <button type="button" class="btn btn-primary" id="btn_open_modal_h_overdue" data-bs-toggle="modal" data-bs-target="#modalHallazgosOverdue" hidden></button>

  <style>
    .table_obs_overdue {
      width: 100%;
    }

    .table_obs_overdue .th_title {
      background-color: #f9f9f9;
      padding: 10px 0;
      letter-spacing: .5px;
    }

    .table_obs_overdue .tr_head {
      border-bottom: 2px solid lightgray;
    }

    .table_obs_overdue .tr_head th {
      padding: 6px 4px;
    }

    .table_obs_overdue .tbody tr {
      border-bottom: 1px solid lightgray;
    }

    .table_obs_overdue .tbody tr:hover {
      background-color: #f9f9f9;
    }
  </style>

  <!-- Modal de Hallazgos Vencidos -->
  <div class="modal fade" id="modalHallazgosOverdue" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl">
      <div class="modal-content" style="border-radius: 5px 5px 0 0;">
        <div class="modal-header">
          <h6 class="modal-title" id="exampleModalLabel">Tarjeta M.A.S | Observaciones Abiertas Vencidas</h6>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body p-0">
          <table class="table_obs_overdue">
            <thead>
              <tr>
                <th colspan="6" class="text-center th_title">Observaciones con Significancia <span id="table_overdue_significancia">'Aceptable'</span></th>
              </tr>
              <tr class="tr_head">
                <th>#ID Hallazgo</th>
                <th>#ID Tarjeta</th>
                <th>Hallazgo</th>
                <th>Plan de Acción</th>
                <th>Fecha de Cierre</th>
                <th>Responsable</th>
              </tr>
            </thead>

            <tbody class="tbody" id="table_tbody_obs_overdue">
              <tr>
                <td>1</td>
                <td>5</td>
                <td>Lorem ipsum dolor sit amet.</td>
                <td>Lorem ipsum dolor sit amet.</td>
                <td>Mirko Dinamarca</td>
              </tr>
            </tbody>
          </table>
        </div>
      </div>
    </div>
  </div>

</div>
<script src="<?= base_url() ?>/assets/js/dashboard/graphic.js"></script>
<script src="<?= base_url() ?>/assets/js/dashboard/graphic_card.js"></script>

<script>
  let hallazgos_vencidos = <?= json_encode($hallazgos_vencidos); ?>;
</script>
<script src="<?= base_url() ?>/assets/js/dashboard/hallazgos_vencidos.js"></script>
<script>
  document.addEventListener('DOMContentLoaded', e => {
    filterObsOverdue('', true)
  })
</script>

<script>
  const collapse_obs_overdue = document.getElementById('collapse_obs_overdue')
  const arrow_down_obs = document.getElementById('arrow_down_obs')
  const class_collapse_obs_overdue = document.querySelector('.collapse_obs_overdue')

  collapse_obs_overdue.addEventListener('click', e => {
    arrow_down_obs.classList.toggle('rotate')
  })
</script>