<!DOCTYPE html>
<html lang="en">

<head>
    <style>
        body {
            background-image: url(<?= base_url("assets/images/login/background-01.jpg") ?>) !important;
            background-size: cover !important;
            background-position-y: center !important;
            /* font-family: 'Poppins' !important; */
            font-family: 'Quicksand', sans-serif !important;
            font-size: 14px;
        }

        .container {
            width: 100vw;
            height: 100vh;
            padding-top: 25vh;
            padding-bottom: 25vh;
        }

        .logo-img {
            width: 330px !important;
            height: 60px !important;
            margin-left: -40px;
        }

        .inp {
            padding: 7px 10px !important;
        }

        .img_cont {
            margin-top: -20px;
            padding-bottom: 30px;
        }

        .pad-ing {
            padding: 2rem !important;
        }

        @media (max-width: 600px) {
            .inp {
                padding: 5px 0px !important;
                margin: 0px -10px !important;
            }

            .pad-ing {
                padding: 1rem !important;
            }
        }
    </style>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="robots" content="noindex, nofollow">
    <meta name="author" content="">
    <link href="assets/img/logo.png" rel="shortcut icon">
    <title>OldelVal</title>

    <!-- Custom fonts for this template-->
    <script src="https://kit.fontawesome.com/e6769ba270.js" crossorigin="anonymous"></script>
    <link href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i" rel="stylesheet">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@100;400&display=swap" rel="stylesheet">

    <!--    Bootstrap 5     -->
    <link rel="stylesheet" href="<?= base_url('assets/bootstrap-5.2.3/css/bootstrap.min.css') ?>">
    <!-- Custom styles for this template-->
    <link href="<?= base_url('assets/css/style.css') ?>" rel="stylesheet">

</head>

<body>

    <div class="container">
        <!-- Outer Row -->
        <div class="row justify-content-center" style="margin-top:-50px;">
            <div class="col-xs-12 col-md-5">
                <div class="card o-hidden border-0 shadow-lg p-3 my-5" style="border-radius: 15px;">
                    <div class="card-body p-0">
                        <!-- Nested Row within Card Body -->
                        <div class="row">
                            <div class="col-xs-12 col-md-12">
                                <div class="pad-ing">
                                    <div class="row justify-content-center img_cont">
                                        <img src="<?= base_url('/assets/images/login/logo_name.png') ?>" alt="Logo empresa oldelval" class="logo-img">
                                    </div>
                                    <div class="text-center">
                                        <h1 class="h4 text-gray-900 mb-4">Bienvenido</h1>
                                    </div>
                                    <form class="user" action="<?= base_url('/index.php/Login/checklogin') ?>" method="post" class="inp" style="text-align:center">
                                        <p class="login_error text-center"><?= session()->getFlashdata('login_error') ?  session()->getFlashdata('login_error') : "" ?></p>
                                        <div class="form-group inp">
                                            <input type="usuario" class="form-control form-control-user sz_inp" id="usuario" name="usuario" aria-describedby="usuarioHelp" placeholder="Ingrese correo corporativo..." autofocus required>
                                        </div>
                                        <div class="form-group inp">
                                            <input type="password" class="form-control form-control-user sz_inp" name="password" id="password" placeholder="Contraseña" required>
                                        </div>
                                        <input type="submit" class="btn_modify" id="submit" value="Ingresar">
                                    </form>
                                    <hr>
                                    <div class="text-center">
                                        <a class="small" href="cambio_contrasena">Olvidé mi contraseña</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src=<?= base_url('assets/popperjs/popperjs-2.9.2.min.js') ?>></script>
    <script src=<?= base_url('assets/jquery/jquery-3.6.0.min.js') ?>></script>
    <script src=<?= base_url('assets/bootstrap-5.2.3/js/bootstrap.bundle.min.js') ?>></script>
    <!-- Custom scripts for all pages-->
    <script src=<?= base_url('assets/js/main.js') ?>></script>

</body>

</html>