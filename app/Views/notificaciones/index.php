<title>OLDELVAL - Notificaciones</title>
<link rel="stylesheet" href="<?= base_url('assets/css/auditorias/index.css') ?>">
<link rel="stylesheet" href="<?= base_url('assets/css/notificacion/notificacion.css') ?>">
<div class="container ">
    <div class="row">
        <div class="col-md-12">
            <div class="blister-title-container">
                <h4 class="blister-title">Todas mis notificaciones</h4>
            </div>
        </div>
    </div>

    <div class="card" style="border: 1px solid #f6f6f6; box-shadow: 0px 0 30px rgb(1 41 112 / 5%);">
        <div class="card-header d-flex justify-content-between align-items-center" style="background: white; padding: 16px; font-weight: 600; letter-spacing: 1.5px;">
            Listado de mis notificaciones
            <button id="btnSetearNotisLeidas" class="btn_modify">Marcar todo como leído</button>
        </div>
        <div class="card-body">

            <?php foreach ($mis_notificaciones as $n) : ?>
                <?php if ($n['leido']) : ?>
                    <div class="card card_no_check" onclick="redirectNotificacion(<?php echo htmlspecialchars(json_encode($n)); ?>)">
                        <div class="card-header"><?= $n['titulo'] ?></div>
                        <div class="card-body">
                            <?= $n['descripcion'] ? $n['descripcion'] : 'No se ha generado una descripción' ?>
                        </div>
                    </div>
                <?php else : ?>
                    <div class="card card_check" onclick="redirectNotificacion(<?php echo htmlspecialchars(json_encode($n)); ?>)">
                        <div class="card-header d-flex justify-content-between align-items-center">
                            <?= $n['titulo'] ?>
                            <div class="noti_pendiente"></div>
                        </div>
                        <div class="card-body">
                            <?= $n['descripcion'] ? $n['descripcion'] : 'No se ha generado una descripción' ?>
                        </div>
                    </div>
                    <hr>
                <?php endif; ?>
            <?php endforeach; ?>

        </div>
    </div>
</div>

<script>
    
    /**
     * Setea a la notificación como leída y también redirige el usuario a donde sea que esté la URL
     */
    function redirectNotificacion(e) {

        if (e.visto == 1) {
            window.location.replace(`${GET_BASE_URL()}/${e.url}${e.id_opcionales}`);
        } else {

            let form = new FormData();
            form.append('id_notificacion', e.id);

            fetch(`${GET_BASE_URL()}/notificacion_leida`, {
                    method: 'POST',
                    body: form
                }).then((response) => response.json())
                .then((data) => {
                    console.log(data)
                    if (data) {
                        window.location.replace(`${GET_BASE_URL()}/${e.url}${e.id_opcionales}`);
                    }
                })
        }
    }

    // Setea todas las notificaciones como leidas
    const btnSetearNotisLeidas = document.getElementById('btnSetearNotisLeidas');
    btnSetearNotisLeidas.addEventListener('click', e => {
        alert('Seteando todas las notificaciones!');

        let form = new FormData();

        fetch(`${GET_BASE_URL()}/all_notis_leidas`, {
                method: 'POST',
                body: form
            })
            .then((data) => {
                window.location.reload();
            })
    })
</script>