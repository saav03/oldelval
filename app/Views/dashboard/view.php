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
  <div class="pagetitle">

    <?php
    if (date('H') > 4 && date('H') <= 12) { ?>
      <h1 class="row-dashboard-forced-white">¡Buenos días <?= $this->session->get('nombre') ?>! <i class="fa-solid fa-mug-hot"></i></h1>
    <?php } else if (date('H') > 12 && date('H') <= 19) { ?>
      <h1 class="row-dashboard-forced-white">¡Buenas tardes <?= $this->session->get('nombre') ?>! <i class="fa-solid fa-mountain-sun"></i></h1>
    <?php } else { ?>
      <h1 class="row-dashboard-forced-white">¡Buenas noches <?= $this->session->get('nombre') ?>! <i class="fa-solid fa-moon"></i></h1>
    <?php } ?>
    <h5 class="row-dashboard-forced-white">Estas son las últimas novedades</h5>

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

  <section class="section dashboard">
    <div class="row">

      <!-- Left side columns -->
      <div class="col-lg-8">
        <div class="row">

          <!-- OBS Card -->
          <?= view('dashboard/components/obs_cards'); ?>
          <!-- End OBS Card -->

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
</div>
<script src="<?= base_url() ?>/assets/js/dashboard/graphic.js"></script>
<script src="<?= base_url() ?>/assets/js/dashboard/graphic_card.js"></script>