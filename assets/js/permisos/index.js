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
        div = el("div.d-flex justify-content-around");
        grandRow = el("div");
        let botonEditar = el(
          "button.btn-editar",
          {
            type: "button",
            title: "Ver/Editar",
            "data-id": row["id_permiso"],
          },
          el("i.fas fa-eye")
        );
        botonEditar.setAttribute("onclick", "openModalEdicion(this)");
        mount(div, botonEditar);

        if (row["estado"] == 1) {
          let botonDesactivar;

          botonDesactivar = el(
            "button.btn-desactivar",
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
            "button.btn-activar",
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
    url: `${GET_BASE_URL()}/Permisos/view/`,
    rowId: "id",
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

/** Ver o Editar el permiso **/

let modal_permiso = new bootstrap.Modal("#modal_permiso");
let datosPermisos;

const subpermiso = document.getElementById("subpermiso");
let previos_subpermiso_value = 0;
const orderSelectPermiso = document.getElementById("orden_permiso");
let firstOrderNumberPermiso = 1;
const genericOrderOptPermiso = el(
  "option",
  {
    value: 1,
    selected: true,
  },
  "* Primera Posición *"
);

function openModalEdicion(e) {
  let id_permiso = e.getAttribute("data-id");
  modal_permiso.show();

  fetch(GET_BASE_URL() + "/permisos/getData/" + id_permiso)
    .then((response) => response.json())
    .then((data) => {
      console.log(data);
      document.getElementById("nombre").value = data.nombre;
      document.getElementById("subpermiso").value = data.id_permiso_padre;
      document.getElementById("tipo_modulo").value = data.tipo_modulo;
      document.getElementById("id_permiso").value = data.id;

      const initialOrderOptions = permisosElems.filter(
        (permiso) => permiso.id_permiso_padre == data.id_permiso_padre
      );

      if (initialOrderOptions.length > 0)
        firstOrderNumber = Number.parseInt(initialOrderOptions[0].orden);
      else firstOrderNumber = 1;

      removeChildren(orderSelectPermiso);
      genericOrderOptPermiso.value = firstOrderNumber;
      orderSelectPermiso.appendChild(genericOrderOptPermiso);
      initialOrderOptions.forEach((opt) => {
        if (opt.id != data.id) {
          let optionDom = el(
            "option",
            {
              value: Number.parseInt(opt.orden) + 1,
            },
            opt.nombre
          );
          if (opt.orden == data.orden - 1) {
            optionDom.selected = true;
          }
          orderSelectPermiso.appendChild(optionDom);
        }
      });
    });
}

const btnEdicionPermiso = document.getElementById("btnEditarPermiso");
const formulario = document.getElementById("formPermiso");

btnEdicionPermiso.addEventListener("click", (e) => {
  e.preventDefault();
  let form = new FormData(formulario);
  customConfirmationButton(
    "Edición Permiso",
    "¿Confirma la edición del permiso?",
    "Editar",
    "Cancelar",
    "swal_edicion"
  ).then((result) => {
    if (result.isConfirmed) {
      addPermiso(form).done(function (data) {
        console.log(data);
      });
      customSuccessAlert(
        "Edición Exitosa",
        "El permiso se editó correctamente",
        "swal_edicion"
      );
    }
  });
});

function addPermiso(form) {
  return $.ajax({
    type: "POST",
    data: form,
    url: GET_BASE_URL() + "/permisos/editPermission",
    processData: false,
    contentType: false,
    beforeSend: function () {
      loadingAlert();
    },
  });
}
