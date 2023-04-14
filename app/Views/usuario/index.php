    <div class="container">
        <div class="row">
            <div class="blister-title-container">
                <h4 class="blister-title">Listado de Usuarios</h4>
            </div>
            <!-- <h3>Listado de Usuarios</h3> -->
        </div>
        <div class="card">
            <div class="card-body">
                <div id="users"></div>
            </div>
        </div>
    </div>
    <script>
        const getUsuario = (pageNumber, pageSize) => {
            return fetch(`${GET_BASE_URL()}/api/usuarios/get/${pageNumber}/${pageSize}`, {
                method: 'POST',
            });
        }
        const getUsuariosTotales = () => {
            return fetch(`${GET_BASE_URL()}/api/usuarios/getTotal`, {
                method: 'POST',
            });
        }

        const tableOptions = {
            tableCSSClass: 'table_oldelval',
            pageSize: 20,
            getDataCallback: getUsuario,
            totalDataCallback: getUsuariosTotales,
            tableHeader: ["ID", "Usuario", "Nombre", "Apellido", "Correo", "Grupos", "Creador", "Fecha Creacion", 'Acciones'],
            tableCells: ["id", "usuario", "nombre", "apellido", "correo", "grupo", "creador", "fecha_creacion", {
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
                    div = el('div.col-xs-12 col-md-12', {
                        'style': 'text-align: center;'
                    });
                    grandRow = el('div.row');
                    botonDesactivar.onclick = () => {
                        botonDesactivar.disabled = true;
                        botonDesactivar.classList.add('disabled');
                        showConfirmationButton().then((result) => {
                            if (result.isConfirmed) {
                                desactivarUsuario(id)
                                    .done(function(data) {
                                        successAlert("Se ha desactivado el Usuario.").then((result) => {
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
                url: `${GET_BASE_URL()}/Usuario/view/`,
                rowId: 'id',
            },
        }
        dynTable = new DynamicTableCellsEditable(document.getElementById("users"), tableOptions);
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