<link href="<?= base_url() ?>/assets/css/virtual-select.min.css" rel="stylesheet">
<title>OLDELVAL - Responsables</title>
<style>
    #usuario_responsable,
    #empresa {
        max-width: 100%;
        padding: 3px;
        border-radius: 15px !important;
        margin-top: -5px;
        border: none;
    }

    .label_selected {
        line-height: 25px;
        cursor: pointer;
    }

    .label_selected input {
        margin-bottom: 4px;
    }

    .option-input {
        -webkit-appearance: none;
        -moz-appearance: none;
        -ms-appearance: none;
        -o-appearance: none;
        appearance: none;
        position: relative;
        top: 13.33333px;
        right: 0;
        bottom: 0;
        left: 0;
        height: 25px;
        width: 25px;
        transition: all 0.15s ease-out 0s;
        background: #cbd1d8;
        border: none;
        color: #fff;
        cursor: pointer;
        display: inline-block;
        margin-right: 0.5rem;
        outline: none;
        position: relative;
        /* z-index: 1000; */
    }

    .option-input:hover {
        background: #9faab7;
    }

    .option-input:checked {
        background: #5596d4;
    }

    .option-input:checked::before {
        width: 25px;
        height: 25px;
        display: flex;
        content: "✔";
        font-size: 15px;
        font-weight: bold;
        position: absolute;
        align-items: center;
        justify-content: center;
    }

    .option-input:checked::after {
        -webkit-animation: click-wave 0.65s;
        -moz-animation: click-wave 0.65s;
        animation: click-wave 0.65s;
        background: #5596d4;
        content: "";
        display: block;
        position: relative;
        z-index: 100;
    }

    .option-input.radio {
        border-radius: 15%;
    }

    .option-input.radio::after {
        border-radius: 50%;
    }

    @keyframes click-wave {
        0% {
            height: 25px;
            width: 25px;
            opacity: 0.35;
            position: relative;
        }

        100% {
            height: 200px;
            width: 200px;
            margin-left: -80px;
            margin-top: -80px;
            opacity: 0;
        }
    }
</style>
<div class="container">
    <div class="row">
        <div class="col-md-12">
            <div class="blister-title-container">
                <h4 class="blister-title">Responsables Empresas</h4>
            </div>
        </div>
    </div>

    <div class="card" style="border: 1px solid #f6f6f6; box-shadow: 0px 0 30px rgb(1 41 112 / 5%);">
        <div class="card-header" style="background: white; padding: 16px; font-weight: 600; letter-spacing: 1.5px;">
            Listado de todos los responsables de las empresas
        </div>
        <p>
        <div class="p-3 border text-center" data-bs-toggle="collapse" href="#collapseExample" role="button" aria-expanded="false" aria-controls="collapseExample">
            Asignar nuevo responsable
        </div>

        </p>
        <div class="collapse" id="collapseExample">
            <form id="form_responsable_empresa" method="POST">
                <div class="row p-4">
                    <div class="col-xs-12 col-md-6">
                        <label class="sz_inp mb-1 fw-semibold">Usuarios del Sistema</label>
                        <select name="usuario_responsable[]" id="usuario_responsable" class="form-select" data-search="true" data-silent-initial-value-set="true" multiple name="native-select">
                            <?php
                            foreach ($usuarios as $e) {
                                echo "<option value='" . $e['id'] . "'>" . $e['nombre'] . ' ' . $e['apellido'] . "</option>";
                            }
                            ?>
                        </select>
                    </div>
                    <div class="col-xs-12 col-md-6">
                        <label class="sz_inp mb-1 fw-semibold">Empresas</label>
                        <select name="empresa" id="empresa" class="form-select" data-search="true" data-silent-initial-value-set="true">
                            <?php
                            foreach ($empresas as $e) {
                                echo "<option value='" . $e['id'] . "'>" . $e['nombre'] . "</option>";
                            }
                            ?>
                        </select>
                    </div>

                    <div class="row">
                        <p class="text-center mb-0 fw-semibold mt-3">Por favor, seleccione los campos de abajo (Si corresponde uno o ambos)</p>
                        <div class="col-xs-12 col-md-6 text-center">
                            <label class="label_selected">
                                <input class="option-input radio" type="checkbox" name="responsable_tarjeta_mas" value="1">¿Será responsable para la Tarjeta M.A.S?
                            </label>
                        </div>
                        <div class="col-xs-12 col-md-6 text-center">
                            <label class="label_selected">
                                <input class="option-input radio" type="checkbox" name="responsable_inspecciones" value="1">¿Será responsable para las Inspecciones?
                            </label>
                        </div>
                    </div>

                    <div class="col-xs-12 col-md-4"></div>
                    <div class="col-xs-12 col-md-4 text-center mt-3">
                        <button id="submit_generar_responsable" class="btn_modify">Generar responsable</button>
                    </div>
                    <div class="col-xs-12 col-md-4"></div>
                </div>
            </form>
        </div>

        <div class="card-body">
            <div id="tabla_responsable_empresas"></div>
        </div>
    </div>
</div>
<script src="<?= base_url() ?>/assets/js/virtual-select.min.js"></script>
<script>
    VirtualSelect.init({
        ele: '#usuario_responsable',
        placeholder: 'Seleccione el responsable',
    });
    document.getElementById('usuario_responsable').setValue(0)
    VirtualSelect.init({
        ele: '#empresa',
        placeholder: 'Seleccione la empresa',
    });
    document.getElementById('empresa').setValue(0)
</script>
<script src="<?= base_url() ?>/assets/js/responsable_empresas/historico.js"></script>

<!-- Genera la carga de la relación entre un responsable y la empresa -->
<script>
    let form_responsable_empresa = document.getElementById('form_responsable_empresa');
    let submit_generar_responsable = document.querySelector('#submit_generar_responsable');

    submit_generar_responsable.addEventListener('click', e => {
        e.preventDefault();
        let form = new FormData(form_responsable_empresa);
        customConfirmationButton(
            "Carga de Responsables",
            "¿Confirma asignar al usuario/s a la empresa seleccionada?",
            "Confirmar",
            "Cancelar",
            "swal_edicion"
        ).then((result) => {
            if (result.isConfirmed) {
                fetch(`${GET_BASE_URL()}/responsable/add`, {
                        method: 'POST',
                        body: form
                    }).then(response => {
                        return response.json()
                    })
                    .then(data => {
                        if (data.error) {
                            customShowErrorAlert(null, data.message, 'swal_edicion');
                        } else {
                            customSuccessAlert(
                                "Asignación de Responsable Exitosa",
                                "Los datos fueron cargados correctamente",
                                "swal_edicion"
                            ).then((result) => {
                                if (result.isConfirmed) {
                                    window.location.reload();
                                }
                            });
                        }
                        console.log('Datos del formulario enviado: ', data)
                    })
            }
        });

    })
</script>