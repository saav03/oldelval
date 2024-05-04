<title>OLDELVAL - Usuarios</title>

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
            <div class="row mb-3">
                <!-- Parámetros de Búsquede para las Auditorías de Control -->
                <div class="col-xs-12">
                    <div class="text-center mt-2 mb-2" style="background-color: #e2e8f0; border-radius: 5px;">
                        <label class="d-flex justify-content-center align-items-center" style="cursor: pointer;" data-bs-toggle="collapse" href="#collapseExample" role="button" aria-expanded="false" aria-controls="collapseExample">
                            <h6 style="color: #475569; font-weight: bold; font-size: 13.5px; letter-spacing: 1px; padding: 10px 5px 10px 0; margin: 0;">
                                Búsqueda Avanzada</h6>
                            <i class="fa-solid fa-magnifying-glass"></i>
                        </label>
                    </div>
                </div>

                <div class="collapse" id="collapseExample">
                    <div class="content" style="background-color: white; border: 1px solid #e2e8f0;">
                        <div class="p-2">
                            <div class="row">
                                <div class="col-xs-12 col-md-2 text-start">
                                    <label class="sz_inp fw-semibold mb-1">ID Usuario</label>
                                    <input type="text" id="id_usuario_f" placeholder="#ID" class="sz_inp form-control">
                                </div>

                                <div class="col-xs-12 col-md-3 text-start">
                                    <label class="sz_inp fw-semibold mb-1">Nombre</label>
                                    <input type="text" id="nombre_f" placeholder="Ingrese el nombre" class="sz_inp form-control">
                                </div>

                                <div class="col-xs-12 col-md-3 text-start">
                                    <label class="sz_inp fw-semibold mb-1">Apellido</label>
                                    <input type="text" id="apellido_f" placeholder="Ingrese el apellido" class="sz_inp form-control">
                                </div>

                                <div class="col-xs-12 col-md-3 text-start">
                                    <label class="sz_inp fw-semibold mb-1">Correo</label>
                                    <input type="text" id="correo_f" placeholder="Ingrese el correo" class="sz_inp form-control">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div id="users"></div>
        </div>
    </div>
    <script>
        const getFormData = () => {
            const form = new FormData();
            form.append(
                "id_usuario",
                document.getElementById("id_usuario_f").value
            );
            form.append(
                "nombre",
                document.getElementById("nombre_f").value
            );
            form.append(
                "apellido",
                document.getElementById("apellido_f").value
            );
            form.append(
                "correo",
                document.getElementById("correo_f").value
            );
            return form;
        };

        const getUsuario = (pageNumber, pageSize) => {
            return fetch(`${GET_BASE_URL()}/api/usuarios/get/${pageNumber}/${pageSize}`, {
                method: 'POST',
                body: getFormData(),

            });
        }
        const getUsuariosTotales = () => {
            return fetch(`${GET_BASE_URL()}/api/usuarios/getTotal`, {
                method: 'POST',
                body: getFormData(),

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
                        usuario = el('p.p-0 m-0', {
                            style: 'color: lightgray; font-style: italic;'
                        }, 'Sin Usuario');
                    }
                    return usuario;
                }
            }, "nombre", "apellido", {
                key: (row) => {
                    let correo;
                    if (row['correo']) {
                        correo = row['correo'];
                    } else {
                        correo = el('p.p-0 m-0', {
                            style: 'color: lightgray; font-style: italic;'
                        }, 'No se ha ingresado un correo');
                    }
                    return correo;
                }
            }, "grupo", "creador", "fecha_creacion", {
                key: (row) => {
                    let btn;

                    if (row['activo'] == 1) {
                        btn = el("button.btn-desactivar", el("i.fas fa-ban"));
                    } else {
                        btn = el("button.btn-activar", el("i.fas fa-check"));
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
        dynTable.addFilterField(document.getElementById("id_usuario_f"));
        dynTable.addFilterField(document.getElementById("nombre_f"));
        dynTable.addFilterField(document.getElementById("apellido_f"));
        dynTable.addFilterField(document.getElementById("correo_f"));
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