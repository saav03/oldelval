<link href="<?= base_url('assets/css/style.css') ?>" rel="stylesheet">
<link href="<?= base_url() ?>/assets/css/virtual-select.min.css" rel="stylesheet">

<style>
    .title_data {
        border-left: 4px solid cornflowerblue;
        padding-left: 10px;
        margin-bottom: 15px;
    }

    .perfil {
        box-shadow: 0px 0px 11px 0px rgb(237 232 232);
        background-color: white;
        text-align: center;
        padding: 10px;
    }

    .nav-perfil {
        color: #608ad0;
        background: white;
        margin: 5px 0;
        border-radius: 10px;
        transition: all .3s ease-in-out;
    }
    .nav-perfil:hover {
        background-color: #f5f5f5;
        color: #608ad0;
    }
</style>

<aside>
    <div class="perfil">
        <div class="foto-perfil">
            <img src="<?= session()->get('img_perfil') != "" ? base_url('uploads/fotosperfil/' . session()->get('img_perfil')) : base_url('assets/images/perfil/no-pic.png'); ?>" alt="" class="foto-perfil rounded-circle">
        </div>
        <div class="nombre-perfil">
            <h6>Bienvenido a tu perfil <?= session()->get('nombre') ?></h6>
        </div>
    </div>
    <ul class="nav flex-column side-style">
        <li class="nav-item profile-item">
            <a class="nav-link active nav-perfil fw-semibold" aria-current="page" name="p-d" href="#p-d">Información de usuario</a>
        </li>
        <li class="nav-item profile-item">
            <a class="nav-link nav-perfil fw-semibold" href="#p-s" name="p-s">Ajustes de acceso y seguridad</a>
        </li>
        <li class="nav-item profile-item">
            <a class="nav-link nav-perfil fw-semibold" href="#p-a" name="p-a">Otros Ajustes</a>
        </li>
        <li class="nav-item profile-item">
            <a class="nav-link nav-perfil fw-semibold" href="#p-h" name="p-h">Historial de ingresos</a>
        </li>
    </ul>
    <?php if (acceso('edicion_perfil')) : ?>
        <div class="mt-2 mb-2" style="margin-left: 15px;">
            <button data-bs-toggle="modal" data-bs-target="#exampleModal" id="btnModalUsuario" class="btn_modify" data-id="<!?= $datos_basicos[0]['id_usuario'] ?>">Editar mis datos</button>
        </div>
    <?php endif; ?>
</aside>

<!-- Modal -->
<div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <form id="form_edit" method="POST">

                <div class="modal-body" style="padding: 0;">
                    <div class="container mt-3">
                        <h5 class="title_data">Edición de Usuario</h5>
                        <!-- Nav tabs -->
                        <ul class="nav nav-tabs" role="tablist">
                            <li class="nav-item">
                                <a class="nav-link active" data-bs-toggle="tab" href="#info_user">Información del Usuario</a>
                            </li>
                        </ul>

                        <!-- Paneles para editar el usuario -->
                        <div class="tab-content">
                            <?= view('perfil/datos_edit') ?>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn_mod_close" style="font-size: 14px;" data-bs-dismiss="modal">Cerrar</button>
                    <button type="button" class="btn_modify" id="btnEditUsuario">Editar Mis Datos</button>
                </div>
            </form>
        </div>
    </div>
</div>

<style>
    @media (max-width: 768px) {
        .side-style {
            margin-bottom: 10px;
            border-radius: 10px;
        }
    }
</style>
<script src="<?= base_url() ?>/assets/js/virtual-select.min.js"></script>

<!-- Submit a los datos de edición de usuario -->
<script>
    function editarUsuario(checkedIds, unCheckedIds) {
        let form = new FormData(document.getElementById('form_edit'));

        return $.ajax({
            type: "POST",
            url: "<?= base_url('perfil/editUser') ?>",
            data: form,
            processData: false,
            contentType: false,
            beforeSend: function() {
                loadingAlert();
            },
        });
    }
    document.getElementById('btnEditUsuario').addEventListener('click', function(event) {
        event.preventDefault();
        let checkedIds = arbol.getCheckedNodes();
        let unCheckedIds = arbol.getUnCheckedNodes();
        customConfirmationButton('Edición de Usuario', '¿Confirma la edición del usuario?', 'Editar', 'Cancelar', 'swal_edicion')
            .then((result) => {
                if (result.isConfirmed) {
                    editarUsuario(checkedIds, unCheckedIds)
                        .done(function(data) {
                            // window.location.reload();
                        })
                    customSuccessAlert('Edición Exitosa', 'El usuario se modificó con éxito', 'swal_edicion');
                }
            })
    });
</script>

<script>
    let data_moment = "p-d";
    const data_items = document.getElementsByClassName('nav-perfil');
    for (let i = 0; i < data_items.length; i++) {
        data_items[i].addEventListener('click', () => {
            if (data_moment != data_items[i].name) {
                document.getElementById(data_moment).classList.add('d-none');
                document.getElementById(data_items[i].name).classList.remove('d-none');
                data_moment = data_items[i].name;
            }
        })
    }
</script>