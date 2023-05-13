/*
=======================
Histórico Permisos index.php 
=======================
*/

const getElements = (pageNumber, pageSize) => {
  return fetch(`${GET_BASE_URL()}/api/permisos/get/${pageNumber}/${pageSize}`, {
    method: "POST",
  });
};
const getElementsTotales = () => {
  return fetch(`${GET_BASE_URL()}/api/permisos/getTotal`, {
    method: "POST",
  });
};

const tableOptions = {
  tableCSSClass: "table_oldelval",
  pageSize: 20,
  getDataCallback: getElements,
  totalDataCallback: getElementsTotales,
  tableHeader: [
    "ID",
    "Nombre",
    "Tipo",
    "Creador",
    "Fecha Creacion",
    "Estado",
    "Acciones",
  ],
  tableCells: [
    "id_permiso",
    "nombre_permiso",
    {
      key: (row) => {
        let tipo;
        if (row["tipo_permiso"] == "heading") {
          tipo = "Encabezado";
        } else if (row["tipo_permiso"] == "head") {
          tipo = "Menú";
        } else if (row["tipo_permiso"] == "add") {
          tipo = "Agregar";
        } else if (row["tipo_permiso"] == "index") {
          tipo = "Histórico";
        }
        return tipo;
      },
    },
    "usuario_creador",
    {
      key: (row) => {
        let fecha;
        if (row["fecha_creacion"] == null) {
          fecha = "-";
        } else {
          fecha = row["fecha_creacion"];
        }
        return fecha;
      },
    },
    {
      key: (row) => {
        let div;
        if (row["estado"] == 1) {
          div = el("div.td-estado_act", {}, "Activo");
        } else {
          div = el("div.td-estado_des", {}, "Inactivo");
        }
        return div;
      },
    },
    {
      key: (row) => {
        let div;
        let grandRow;
        let id = row["id_permiso"];
        let nombre = row["nombre_permiso"];
        div = el("div.d-flex justify-content-center");
        grandRow = el("div");

        if (row["estado"] == 1) {
          let botonDesactivar;

          botonDesactivar = el(
            "button.btn_desactivar",
            {
              type: "button",
              title: "Desactivar",
            },
            el("i.fas fa-ban")
          );

          botonDesactivar.onclick = () => {
            botonDesactivar.disabled = true;
            botonDesactivar.classList.add("disabled");
            customConfirmationButton(
              "Desactivar Permiso",
              "¿Confirma desactivar este permiso?",
              "Desactivar",
              "Cancelar",
              "swal_edicion"
            ).then((result) => {
              if (result.isConfirmed) {
                desactivar(id, nombre)
                  .done(function (data) {
                    customSuccessAlert(
                      "Carga Exitosa",
                      "El permiso se desactivó correctamente",
                      "swal_edicion"
                    ).then((result) => {
                      if (result.isConfirmed) {
                        window.location.replace(GET_BASE_URL() + "/permisos");
                      }
                    });
                  })
                  .fail((err, textStatus, xhr) => {
                    let errors = JSON.parse(err.responseText);
                    errors = errors.join(". ");
                    showErrorAlert(null, errors);
                    botonDesactivar.disabled = false;
                    botonDesactivar.classList.remove("disabled");
                  });
              } else {
                botonDesactivar.disabled = false;
                botonDesactivar.classList.remove("disabled");
              }
            });
          };
          mount(div, botonDesactivar);
        } else {
          let botonActivar;

          botonActivar = el(
            "button.btn_activar",
            {
              type: "button",
              title: "Activar",
            },
            el("i.fas fa-check")
          );

          botonActivar.onclick = () => {
            botonActivar.disabled = true;
            botonActivar.classList.add("disabled");

            customConfirmationButton(
              "Activar Permiso",
              "¿Confirma activar este permiso?",
              "Activar",
              "Cancelar",
              "swal_edicion"
            ).then((result) => {
              if (result.isConfirmed) {
                activarPermiso(id, nombre)
                  .done(function (data) {
                    customSuccessAlert(
                      "Carga Exitosa",
                      "El permiso se activó correctamente",
                      "swal_edicion"
                    ).then((result) => {
                      if (result.isConfirmed) {
                        window.location.replace(GET_BASE_URL() + "/permisos");
                      }
                    });
                  })
                  .fail((err, textStatus, xhr) => {
                    let errors = JSON.parse(err.responseText);
                    errors = errors.join(". ");
                    showErrorAlert(null, errors);
                    botonActivar.disabled = false;
                    botonActivar.classList.remove("disabled");
                  });
              } else {
                botonActivar.disabled = false;
                botonActivar.classList.remove("disabled");
              }
            });
          };
          mount(div, botonActivar);
        }

        mount(grandRow, div);
        return grandRow;
      },
      noClickableRow: true,
    },
  ],
  pager: {
    totalEntitiesText: "Cantidad de Resultados",
  },
  clickableRow: {
    url: `${GET_BASE_URL()}/permisos/edit/`,
    rowId: "id_permiso",
  },
};
dynTable = new DynamicTableCellsEditable(
  document.getElementById("table"),
  tableOptions
);
dynTable.init();

function desactivarPermiso(id, nombre) {
  let form = new FormData();
  form.append("id_permiso", id);
  form.append("nombre", nombre);
  return $.ajax({
    type: "POST",
    data: form,
    url: GET_BASE_URL() + "/Permisos/disablePermission/",
    processData: false,
    contentType: false,
    beforeSend: function () {
      loadingAlert();
    },
  });
}

function activarPermiso(id, nombre) {
  let form = new FormData();
  form.append("id_permiso", id);
  form.append("nombre", nombre);
  return $.ajax({
    type: "POST",
    data: form,
    url: GET_BASE_URL() + "/Permisos/enablePermission/",
    processData: false,
    contentType: false,
    beforeSend: function () {
      loadingAlert();
    },
  });
}
