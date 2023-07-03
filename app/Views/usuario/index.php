    <style>
        .btn_desactivar,
        .btn_activar {
            border: none;
            border-radius: 50%;
            font-size: 18px;
            background-color: #bfbfbf;
            color: white;
            cursor: pointer;
            transition: all .2s;
        }

        .btn_desactivar:hover {
            background-color: #ff6b6b;
        }

        .btn_activar:hover {
            background-color: #99d990;
        }
    </style>

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
            tableCells: ["id", {
                key: (row) => {
                    let usuario;
                    if (row['usuario']) {
                        usuario = row['usuario'];
                    } else {
                        usuario = el('p.p-0 m-0', {style: 'color: lightgray; font-style: italic;'}, 'Sin Usuario');
                    }
                    return usuario;
                }
            }, "nombre", "apellido", {
                key: (row) => {
                    let correo;
                    if (row['correo']) {
                        correo = row['correo'];
                    } else {
                        correo = el('p.p-0 m-0', {style: 'color: lightgray; font-style: italic;'}, 'No se ha ingresado un correo');
                    }
                    return correo;
                }
            }, "grupo", "creador", "fecha_creacion", {
                key: (row) => {
                    let btn;

                    if (row['activo'] == 1) {
                        btn = el("button.btn_desactivar", el("i.fas fa-ban"));
                    } else {
                        btn = el("button.btn_activar", el("i.fas fa-check"));
                    }
                    btn.setAttribute("data-id", row["id"]);
                    btn.setAttribute("onclick", "changeStateUser(this)");

                    return btn;
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

        function changeStateUser(e) {
            let id_usuario = e.getAttribute("data-id");
            customConfirmationButton(
                "Actualizar Estado Usuario",
                "¿Confirma actualizar su estado?",
                "Actualizar",
                "Cancelar",
                "swal_edicion"
            ).then((result) => {
                if (result.isConfirmed) {
                    fetch(`${GET_BASE_URL()}/Usuario/changeStateUser/${id_usuario}`, {
                            method: "POST",
                        })
                        .then(response => response.json())
                    window.location.replace(GET_BASE_URL() + "/all_users");
                }
            });
        }
    </script>