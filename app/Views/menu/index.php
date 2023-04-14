<div class="container">
    <div class="row">
        <!-- <div class="col-md-4">
            <h4></h4>
        </div> -->
        <div class="blister-title-container">
            <h4 class="blister-title">Listado de elementos del Menu</h4>
        </div>
    </div>

    <div class="row">
        <div class="col-md-8"></div>
        <div class="col-md-4" style="text-align: end;">
            <a class="btn_modify" style="text-decoration: none;" href="<?= base_url('/Menu/viewAdd') ?>">Agregar Nuevo Men&uacute;</a>
        </div>
    </div>
    <br>
</div>
<div class="container">
    <div class="row">
        <div class="card">
            <div class="card-header" style="background: white; padding: 16px; font-weight: 600; letter-spacing: 1.5px;">
                Tabla de Menu
            </div>
            <div class="card-body">
                <div id="table"></div>
            </div>
        </div>
    </div>
</div>
<script>
    const getElements = (pageNumber, pageSize) => {
        return fetch(`${GET_BASE_URL()}/api/menu/get/${pageNumber}/${pageSize}`, {
            method: 'POST',
        });
    }
    const getElementsTotales = () => {
        return fetch(`${GET_BASE_URL()}/api/menu/getTotal`, {
            method: 'POST',
        });
    }

    const tableOptions = {
        tableCSSClass: 'table_oldelval',
        pageSize: 20,
        getDataCallback: getElements,
        totalDataCallback: getElementsTotales,
        tableHeader: ["ID", "Nombre", "Creador", "Fecha Creacion", "Acciones"],
        tableCells: ["id", "nombre", "creador", "fecha_creacion", {
            key: (row) => {
                let botonDesactivar;
                let div;
                let grandRow;
                let id = row['id'];
                let nombre = row['nombre'];
                botonDesactivar = el('button.btn-desactivar', {
                    'type': 'button',
                    'title': 'Desactivar',
                }, el("i.fas fa-ban"));
                div = el('div.col-xs-12 col-md-12');
                grandRow = el('div.row');
                botonDesactivar.onclick = () => {
                    botonDesactivar.disabled = true;
                    botonDesactivar.classList.add('disabled');
                    showConfirmationButton().then((result) => {
                        if (result.isConfirmed) {
                            desactivarUsuario(id)
                                .done(function(data) {
                                    successAlert("Se ha desactivado el Menu.").then((result) => {
                                        if (result.isConfirmed) {
                                            window.location.replace("<?= base_url('usuarios') ?>");
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
        clickableRow: {
            url: `${GET_BASE_URL()}/Menu/view/`,
            rowId: 'id',
        },
    }
    dynTable = new DynamicTableCellsEditable(document.getElementById("table"), tableOptions);
    dynTable.init();


    function desactivarUsuario(id) {
        return $.ajax({
            type: "POST",
            data: new FormData(),
            url: "<?= base_url('Usuario/desactivarUsuario/') ?>" + id,
            processData: false,
            contentType: false,
            beforeSend: function() {
                loadingAlert();
            },
        });
    }
</script>