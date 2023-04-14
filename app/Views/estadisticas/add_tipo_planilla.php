<style>
    .indicadores {
        background-color: #f5f5f5;
        border-radius: 10px;
        padding: 10px;
    }
</style>
<div class="container">
    <form action="" method="POST" id="form-add-planilla">
        <div class="blister-title-container">
            <h4 class="blister-title">Agregar un Nuevo Tipo de Formulario <b>(Estadisticas HSE)</b></h4>
        </div>
        <div class="row d-flex text-end" style="width:100%;">
            <small style="color:lightgrey"><i>(*)Campo Obligatorio</i></small>
        </div>

        <hr>
        <div class="card c-perfil">
            <div class="card-header text-center header-perfil" style="background-color: #00b8e640 !important;">
                <h5 style="margin: 0px;"><b>Datos</b></h5>
            </div>
            <div class="card-body">
                <div class="col-4">
                    <div class="form-group">
                        <label class="mb-1 fw-semibold sz_inp" for="tipo_plan">Tipo de Formulario <small>(*)</small></label>
                        <input type="text" name="tipo_plan" id="tipo_plan" class="form-control sz_inp" placeholder="Ingrese el nombre de el Formulario" required>
                    </div>
                </div>
                <hr>
                <!-- <div class="form-group indicadores">
                    <!-- <label class="mb-1 fw-semibold sz_inp mb-2" for="contenedor-indicadores">Indicadores</label>
                    <div class="row" id="contenedor-indicadores"></div>
                    <button id="addIndicador" style="background-color:#e5f3ff; font-size: 14px;" type="button" class="btn btn-info">&#10010; Agregar Indicador</button>
                </div> 
                    <label class="mb-1 fw-semibold sz_inp mb-2" for="contenedor-indicadores">Indicadores</label>
                    <div class="row" id="contenedor-indicadores"></div>
                </div> -->
                <div class="row" style="border-left: 5px solid lightblue;border-right: 5px solid lightblue;border-radius: 8px;">
                    <div class="col-xs-12 col-md-6 p-3">
                        <label class="mb-1 fw-semibold sz_inp">Titulo <small>(opcional)</small></label>
                        <input type="text" name="" id="" class="form-control sz_inp" placeholder="Ingrese el titulo">
                    </div>
                    <div class="col-xs-12 col-md-6 p-3">
                        <label class="mb-1 fw-semibold sz_inp">Subtitulo <small>(opcional)</small></label>
                        <input type="text" name="" id="" class="form-control sz_inp" placeholder="Ingrese el subtitulo">
                    </div>
                    <div class="row">

                    </div>
                    <div class="text-center mb-3 mt-3">
                        <button class="btn_modify">&#10010; Agregar Indicador</button>
                    </div>
                </div>
                <br>
                <button id="addIndicador" style="background-color:#e5f3ff; font-size: 14px;" type="button" class="btn btn-info">&#10010; Agregar sección</button>
            </div>
        </div>
    </form>
    <div class="row justify-content-center">
        <button type="button" class="btn_modify sz_inp" style="width:100px; font-size: 14px;" id="buttonAddFormulario">Agregar</button>
    </div>
</div>
<script src="<?= base_url("assets/js/customAlerts.js") ?>"></script>

<script>
    //Indicadores
    let count = 1;
    let contenedor = document.getElementById('contenedor-indicadores');
    document.getElementById('addIndicador').addEventListener('click', () => {
        let aux = count < 10 ? "#0" + count : "#" + count;
        let span = el('span.input-group-text numerador sz_inp', {
            'data-total': 'indicadores',

        }, aux);
        let input = el('textarea.form-control sz_inp', {
            'placeholder': "Escribir Indicador...",
            'style': 'width:90%;resize: vertical;',
            'required': 'true',
            'aria-label': 'Escribir Indicador...',
            'aria-describedby': 'botonElim',
            'name': 'indicadores[]'
        });
        let buttonElim = el('button.btn.btn-outline-secondary btn-danger', {
            'style': 'color:white; border-color: transparent;'
        }, el('i.fa-solid fa-trash-can'));
        let div_input = el("div.input-group mb-3");
        let row = el('div.row');
        buttonElim.addEventListener('click', () => {
            row.remove();
            let cant = document.getElementsByClassName('numerador');
            for (let i = 0; i < cant.length; i++) {
                cant[i].innerHTML = i < 10 ? "#0" + (i + 1) : "#" + (i + 1);
            }
            count = cant.length + 1;

        });
        mount(div_input, span);
        mount(div_input, input);
        mount(div_input, buttonElim);
        mount(row, div_input);
        mount(contenedor, row);
        count++;
    });
</script>
<script>
    function cargarFormulario() {
        return $.ajax({
            type: "POST",
            url: "<?= base_url('addNewFormulario') ?>",
            data: new FormData(document.getElementById('form-add-planilla')),
            processData: false,
            contentType: false,
            beforeSend: function() {
                loadingAlert();
            },
        });
    }
    document.getElementById('buttonAddFormulario').addEventListener('click', function(event) {
        event.preventDefault();
        const buttonSubmitUsuario = document.getElementById('buttonAddFormulario');
        buttonSubmitUsuario.disabled = true;
        buttonSubmitUsuario.classList.add('disabled');
        showConfirmationButton().then((result) => {
            if (result.isConfirmed) {
                cargarFormulario()
                    .done(function(data) {
                        successAlert("Se ha registrado su solicitud.").then((result) => {
                            if (result.isConfirmed) {
                                window.location.replace("<?= base_url('/all_users') ?>");
                            }
                        })
                    })
                    .fail((err, textStatus, xhr) => {
                        let errors = Object.values(JSON.parse(err.responseText));
                        errors = errors.join('. ');
                        showErrorAlert(null, errors);
                        buttonSubmitUsuario.disabled = false;
                        buttonSubmitUsuario.classList.remove('disabled');
                    })
            } else {
                canceledActionAlert();
                buttonSubmitUsuario.disabled = false;
                buttonSubmitUsuario.classList.remove('disabled');
            }
        });
    });
</script>