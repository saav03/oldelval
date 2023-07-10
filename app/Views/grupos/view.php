<title>OLDELVAL - Grupo <?= $grupo['nombre'] ?></title>
<div class="container">
    <div class="row p-1">
        <div class="col-md-4">
            <h4>Grupo: <b style="color: dodgerblue;"><?= $grupo['nombre'] ?> <?= '(#' . $grupo['id'] . ')' ?></b></h4>
        </div>
    </div>
    <form id="new_permission" method="POST">
        <div class="row">
            <div class="col-xs-12 col-md-4">
                <div class="card mt-3">
                    <div class="card-body" id="tree">

                    </div>
                </div>
            </div>

            <div class="col-xs-12 col-md-8">

                <div class="card mt-3">
                    <div class="card-body">
                        <h5>Listado de usuarios pertenecientes a este grupo</h5>
                        <hr>
                        <div id="table"></div>
                    </div>
                </div>
            </div>
        </div>
        <input type="hidden" name="id_grupo" value="<?= $grupo['id'] ?>">
        <div class="d-flex justify-content-end">
            <input type="submit" class="btn_modify text-center" style="cursor: pointer;" name="btn_edit_permisos" id="btn_edit_permisos" value="Modificar Permisos">
        </div>
    </form>

</div>

<!-- Crea el Árbol de Permisos -->
<script>
    let permisos = <?= json_encode($permisos); ?>;
    let arbol;

    $(document).ready(function() {
        arbol = $('#tree').tree({
            textField: 'nombre',
            cascadeCheck: false,
            checkedField: 'pertenece',
            primaryKey: 'id',
            uiLibrary: 'bootstrap5',
            dataSource: permisos,
            checkboxes: true
        });
    });
</script>

<script>
    const getElement = (pageNumber, pageSize) => {
        return fetch(`${GET_BASE_URL()}/api/permisos/get/<?= $grupo['id'] ?>/${pageNumber}/${pageSize}`, {
            method: 'POST',
        });
    }
    const getAllElements = () => {
        return fetch(`${GET_BASE_URL()}/api/permisos/getTotal/<?= $grupo['id'] ?>`, {
            method: 'POST',
        });
    }

    const tableOptions = {
        tableCSSClass: 'table table-bordered table-hover table-striped action-table',
        pageSize: 20,
        getDataCallback: getElement,
        totalDataCallback: getAllElements,
        tableHeader: ["ID rel", "Nombre", "Separador", "Menu Padre", "Orden", "Creador", "Acciones"],
        tableCells: ["id", "nombre", "head", "menu_padre", "orden", "creador", {
            key: (row) => {
                let botonDesactivar;
                let div;
                let grandRow;
                let id = row['id'];
                let nombre = row['nombre'];
                botonDesactivar = el('button.btn btn-danger', {
                    'type': 'button',
                    'title': 'Desactivar',
                    'style': 'background-color: #D5302B; margin-left: 20px;'
                }, el("i.fa fa-times-circle"));
                div = el('div.col-xs-12 col-md-12');
                grandRow = el('div.row');
                botonDesactivar.onclick = () => {
                    botonDesactivar.disabled = true;
                    botonDesactivar.classList.add('disabled');
                    showConfirmationButton().then((result) => {
                        if (result.isConfirmed) {
                            desactivarPermiso(id, nombre)
                                .done(function(data) {
                                    successAlert("Se ha desactivado el Permiso.").then((result) => {
                                        if (result.isConfirmed) {
                                            window.location.replace("<?= base_url('/Grupo/view') . "/" . $grupo['id'] ?>");
                                        }
                                    })
                                })
                                .fail((err, textStatus, xhr) => {
                                    let errors = JSON.parse(err.responseText);
                                    errors = errors.join(". ");
                                    showErrorAlert(null, errors);
                                    botonDesactivar.disabled = false;
                                    botonDesactivar.classList.remove('disabled');
                                })
                        } else {
                            canceledActionAlert();
                            botonDesactivar.disabled = false;
                            botonDesactivar.classList.remove('disabled');
                        }
                    });
                }
                mount(div, botonDesactivar);
                mount(grandRow, div);

                return grandRow;
            },
            noClickableRow: true
        }],
        pager: {
            totalEntitiesText: "Cantidad de Resultados",
        },
        clickableRow: false,
    }
    // dynTable = new DynamicTableCellsEditable(document.getElementById("table"), tableOptions);
    // dynTable.init();

    function desactivarPermiso(id, nombre) {
        let form = new FormData();
        form.append("id", id);
        form.append("nombre", nombre);
        return $.ajax({
            type: "POST",
            data: form,
            url: "<?= base_url('/Permisos/disablePermission') ?>",
            processData: false,
            contentType: false,
            beforeSend: function() {
                loadingAlert();
            },
        });
    }
</script>

<script>
    document.getElementById('new_permission').addEventListener('submit', () => {
        event.preventDefault();
        let checkedIds = arbol.getCheckedNodes();
        let unCheckedIds = arbol.getUnCheckedNodes();
        const buttonSubmit = document.getElementById('btn_edit_permisos');
        buttonSubmit.disabled = true;
        buttonSubmit.classList.add('disabled');
        showConfirmationButton().then((result) => {
            if (result.isConfirmed) {
                cargar(checkedIds, unCheckedIds)
                    .done(function(data) {
                        successAlert("Se ha registrado su solicitud.").then((result) => {
                            if (result.isConfirmed) {
                                window.location.replace("<?= base_url('/Grupo/view/' . $grupo['id']) ?>");
                            }
                        })
                    })
                    .fail((err, textStatus, xhr) => {
                        let errors = Object.values(JSON.parse(err.responseText));
                        errors = errors.join('. ');
                        showErrorAlert(null, errors);
                        buttonSubmit.disabled = false;
                        buttonSubmit.classList.remove('disabled');
                    })
            } else {
                canceledActionAlert();
                buttonSubmit.disabled = false;
                buttonSubmit.classList.remove('disabled');
            }
        });
    });

    function cargar(checkedIds, unCheckedIds) {
        let form = new FormData(document.getElementById('new_permission'));

        for (let i = 0; i < checkedIds.length; i++) {
            form.append('permisos_checked[]', checkedIds[i]);
        }
        for (let i = 0; i < unCheckedIds.length; i++) {
            form.append('permisos_unchecked[]', unCheckedIds[i]);
        }

        return $.ajax({
            type: "POST",
            url: "<?= base_url('/addNewPermissionGroup') ?>",
            data: form,
            processData: false,
            contentType: false,
            beforeSend: function() {
                loadingAlert();
            },
        });
    }
</script>