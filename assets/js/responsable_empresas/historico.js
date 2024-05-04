const getElement = (pageNumber, pageSize) => {
  return fetch(
    `${GET_BASE_URL()}/api/responsable_empresas/get/${pageNumber}/${pageSize}`,
    {
      method: "POST",
    }
  );
};
const getElementTotales = () => {
  return fetch(`${GET_BASE_URL()}/api/responsable_empresas/getTotal`, {
    method: "POST",
  });
};

const tableOptions = {
  tableCSSClass: "table_oldelval text-center",
  pageSize: 10,
  getDataCallback: getElement,
  totalDataCallback: getElementTotales,
  tableHeader: [
    "#ID",
    "Responsable",
    "Empresa",
    "Tarjeta M.A.S",
    "Inspecciones",
    "Fecha carga",
    "Usuario creador",
    "Estado",
    "Acciones",
  ],
  tableCells: [
    "id",
    "usuario_responsable",
    "empresa",
    {
      key: (row) => {
        if (row["tarjeta_mas"]) {
          return "Si";
        }
        return "No";
      },
    },
    {
      key: (row) => {
        if (row["inspecciones"]) {
          return "Si";
        }
        return "No";
      },
    },
    "fecha_carga_f",
    "usuario_carga",
    {
      key: (row) => {
        let div;

        if (row["activo"] == 1) {
          div = el("div.td_estado_activo", "Activo");
        } else {
          div = el("div.td_estado_inactivo", "Inactivo");
        }
        return div;
      },
    },
    {
      key: (row) => {
        let div, btnDelete;
        div = el("div.d-flex justify-content-center");
        btnDelete = el("button.btn-desactivar ms-3", el("i.fas fa-trash"));
        btnDelete.setAttribute("onclick", `removeResponsable(${row["id"]})`);
        mount(div, btnDelete);
        return div;
      },
    },
  ],
  pager: {
    totalEntitiesText: "Cantidad de Resultados",
  },
  clickableRow: false,
};
dynTable = new DynamicTableCellsEditable(
  document.getElementById("tabla_responsable_empresas"),
  tableOptions
);
dynTable.init();

/**
 * Elimina la relación del ID responsable con la empresa (NO DESACTIVA, LO ELIMINA)
 */
const removeResponsable = (id_rel) => {
  let form = new FormData();

  form.append("id", id_rel);

  customConfirmationButton(
    "Eliminar Responsable",
    "¿Confirma eliminar el usuario seleccionado?",
    "Confirmar",
    "Cancelar",
    "swal_edicion"
  ).then((result) => {
    if (result.isConfirmed) {
      fetch(`${GET_BASE_URL()}/responsable/delete`, {
        method: "POST",
        body: form,
      })
        .then((response) => {
          return response.json();
        })
        .then((data) => {
          if (data == null) {
            customSuccessAlert(
              "Responsable Eliminado",
              "El Responsable ha sido eliminado correctamente",
              "swal_edicion"
          ).then((result) => {
              if (result.isConfirmed) {
                  window.location.reload();
              }
          });
          }
        });
    }
  });
};
