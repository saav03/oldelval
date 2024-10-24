<link href="<?= base_url() ?>/assets/css/tarjetaObs/index.css" rel="stylesheet">
<title>OLDELVAL - Pendientes</title>
<div class="container">
    <div class="row">
        <div class="col-md-12">
            <div class="blister-title-container">
                <h4 class="blister-title">Tarjetas M.A.S - Pendientes</h4>
            </div>
        </div>
    </div>

    <div class="card" style="border: 1px solid #f6f6f6; box-shadow: 0px 0 30px rgb(1 41 112 / 5%);">
        <div class="card-header" style="background: white; padding: 16px; font-weight: 600; letter-spacing: 1.5px;">
            Listado de todas las Tarjetas
        </div>
        <div class="card-body">
            <div id="tabla_tarjetas"></div>
        </div>
    </div>
    
    <hr>

    <div class="row">
        <div class="col-md-12">
            <div class="blister-title-container">
                <h4 class="blister-title">Inspecciones - Pendientes</h4>
            </div>
        </div>
    </div>

    <div class="card" style="border: 1px solid #f6f6f6; box-shadow: 0px 0 30px rgb(1 41 112 / 5%);">
        <div class="card-header" style="background: white; padding: 16px; font-weight: 600; letter-spacing: 1.5px;">
            Listado de todas las Inspecciones
        </div>
        <div class="card-body">
            <div id="tabla_inspecciones"></div>
        </div>
    </div>
</div>

<script>
    let pendiente = 1;
</script>
<script src="<?= base_url() ?>/assets/js/tarjetaObs/index/historico.js"></script>
<script src="<?= base_url() ?>/assets/js/auditorias/index_pendientes.js"></script>
