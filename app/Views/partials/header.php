<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta content="width=device-width, initial-scale=1.0" name="viewport">

  <title>OldelVal - SEO</title>
  <meta content="" name="description">
  <meta content="" name="keywords">

  <!-- Favicons -->
  <link href="<?= base_url() ?>/assets/img/favicon.png" rel="icon">
  <link href="<?= base_url() ?>/assets/img/apple-touch-icon.png" rel="apple-touch-icon">

  <!-- Google Fonts -->
  <link href="https://fonts.gstatic.com" rel="preconnect">
  <link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Quicksand:wght@400;500;600&display=swap" rel="stylesheet">
  <!-- <link href="https://fonts.googleapis.com/css?family=Open+Sans:300,300i,400,400i,600,600i,700,700i|Nunito:300,300i,400,400i,600,600i,700,700i|Poppins:300,300i,400,400i,500,500i,600,600i,700,700i" rel="stylesheet"> -->

  <!-- Vendor CSS Files -->
  <link href="<?= base_url() ?>/assets/vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">
  <link href="<?= base_url() ?>/assets/vendor/bootstrap-icons/bootstrap-icons.css" rel="stylesheet">
  <link href="<?= base_url() ?>/assets/vendor/boxicons/css/boxicons.min.css" rel="stylesheet">
  <link href="<?= base_url() ?>/assets/vendor/quill/quill.snow.css" rel="stylesheet">
  <link href="<?= base_url() ?>/assets/vendor/quill/quill.bubble.css" rel="stylesheet">
  <link href="<?= base_url() ?>/assets/vendor/remixicon/remixicon.css" rel="stylesheet">
  <link href="<?= base_url() ?>/assets/vendor/simple-datatables/style.css" rel="stylesheet">

  <!-- Template Main CSS File -->
  <link href="<?= base_url() ?>/assets/css/style.css" rel="stylesheet">

    <!-- blister_table.css -->
    <link href="<?= base_url() ?>/assets/css/blister_table.css" rel="stylesheet">

  <!-- Bootstrap 5 -->
  <link rel="stylesheet" href="<?= base_url('assets/bootstrap-5.2.3/css/bootstrap.min.css') ?>">

  <!-- Font awesome v6.2 -->
  <!-- our project just needs Font Awesome Solid + Brands -->
  <link href="<?= base_url() ?>/assets/fontawesome/css/fontawesome.css" rel="stylesheet">
  <link href="<?= base_url() ?>/assets/fontawesome/css/brands.css" rel="stylesheet">
  <link href="<?= base_url() ?>/assets/fontawesome/css/solid.css" rel="stylesheet">
  
  <!-- Tree -->
  <link href="<?= base_url() ?>/assets/css/gijgo.min.css" rel="stylesheet">

  <!-- Our project just needs Font Awesome Solid + Brands -->
  <script defer src="<?= base_url() ?>/assets/fontawesome/js/brands.js"></script>
  <script defer src="<?= base_url() ?>/assets/fontawesome/js/solid.js"></script>
  <script defer src="<?= base_url() ?>/assets/fontawesome/js/fontawesome.js"></script>

  <!-- Custom scripts for all pages-->
  <!-- MOST USED SCRIPTS START-->
  <script src=<?= base_url('assets/sweetalert2/sweetalert2.all.min.js') ?>></script>
  <script src="<?= base_url('assets/js/base_url_php.js') ?>"></script> <!-- URL BASE PARA SCRIPTS -->
  <script src="<?= base_url() ?>/assets/js/domManipulation.js"></script>
  <script src="<?= base_url() ?>/assets/js/clickable-row.js"></script>
  <script src="<?= base_url() ?>/assets/js/editForms.js"></script>
  <script src="<?= base_url() ?>/assets/js/dibujarPreguntas.js"></script>
  <script src="<?= base_url() ?>/assets/js/generarPreguntas.js"></script>
  <script src="<?= base_url() ?>/assets/js/deleteDomChildren.js"></script>
  <script src="<?= base_url() ?>/assets/js/simpleFormBehaviour.js"></script>
  <script src="<?= base_url() ?>/assets/js/simpleActivationBehaviour.js"></script>
  <script>
    SET_BASE_URL("<?= base_url() ?>");
  </script>
  <script src="<?= base_url('assets/js/redom/redom.min.js') ?>"></script>
  <script>
    const {
      el,
      mount,
      unmount,
      setChildren,
      setAttr,
    } = redom;
  </script>
  <link rel="stylesheet" href="<?= base_url('assets/css/paginator.css') ?>">
  <script src="<?= base_url('assets/jquery/jquery-3.6.0.min.js') ?>"></script>
  <script src="<?= base_url('assets/js/generalFunctions.js') ?>"></script>
  <script src="<?= base_url('assets/js/dynamic-table.js') ?>"></script>
  <script src="<?= base_url('assets/js/dynamic-table-cells-editable.js') ?>"></script>

</head>

<style>
  .table-responsive {
    overflow-x: hidden;
  }

  .clickable-row:hover {
    cursor: pointer;
  }
</style>

<body>
  <!-- ======= Header ======= -->
  <header id="header" class="header fixed-top d-flex align-items-center">
    <?= view('partials/topbar') ?>
  </header><!-- End Header -->

  <!-- ======= Sidebar ======= -->
  <aside id="sidebar" class="sidebar">
    <?= view('partials/sidebar'); ?>
  </aside><!-- End Sidebar-->

  <!-- ======= Main div ======= -->
  <style>
    .main_background {
      background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='4' height='4' viewBox='0 0 4 4'%3E%3Cpath fill='%23000000' fill-opacity='0.075' d='M1 3h1v1H1V3zm2-2h1v1H3V1z'%3E%3C/path%3E%3C/svg%3E")!important;
      height: 100%!important;
    }
  </style>
  <div id="main" class="main main_background">