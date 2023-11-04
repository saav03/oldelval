const getElement = (pageNumber, pageSize) => {
  return fetch(
    `${GET_BASE_URL()}/api/estadisticas/get/${pageNumber}/${pageSize}`,
    {
      method: "POST",
    }
  );
};
const getElementTotales = () => {
  return fetch(`${GET_BASE_URL()}/api/estadisticas/getTotal`, {
    method: "POST",
  });
};

const tableOptions = {
  tableCSSClass: "table_oldelval",
  pageSize: 10,
  getDataCallback: getElement,
  totalDataCallback: getElementTotales,
  tableHeader: [
    "#ID",
    "Contratista",
    "Proyecto",
    'Periodo',
    "Planilla",
    "Fecha Carga",
    "Usuario Carga",
    permiso_edicion ? 'Acciones' : '',
  ],
  tableCells: [
    "id_estadistica",
    "contratista",
    "proyecto",
    {
      key: (row) => {
        let mes = obtenerNombreMes(row['periodo']);
        return mes + '/' + row['anio']
      }
    },
    {
      key: (row) => {
        let div;
        switch (row["tipo"]) {
          case "1":
            div = el("div", "Incidente");
            break;
          case "2":
            div = el("div", "Capacitaciones");
            break;
          case "3":
            div = el("div", "Gestión Vehicular");
            break;
        }
        return div;
      },
    },
    "fecha_hora_carga",
    {
      key: (row) => {
        return row["nombre_u_carga"] + " " + row["apellido_u_carga"];
      },
    },
    permiso_edicion ? 
    {
      key: (row) => {
        let btn;
        if (row["estado"] == 1) {
          btn = el("button.btn-desactivar", el("i.fas fa-ban"));
        } else {
          btn = el("button.btn-activar", el("i.fas fa-check"));
        }
        btn.setAttribute("data-id", row["id_estadistica"]);
        btn.setAttribute("onclick", "changeStateEstadistica(this)");
        return btn;
      },
      noClickableRow: true,
    } : '',
  ],
  pager: {
    totalEntitiesText: "Cantidad de Resultados",
  },
  clickableRow: {
    url: `${GET_BASE_URL()}/estadisticas/view/`,
    rowId: "id_estadistica",
    secondRowId: "tipo",
  },
};
dynTable = new DynamicTableCellsEditable(
  document.getElementById("tabla_estadisticas"),
  tableOptions
);
dynTable.init();

function changeStateEstadistica(e) {
  let id_estadistica = e.getAttribute("data-id");
  customConfirmationButton(
    "Actualizar Estado Estadística",
    "¿Confirma actualizar el estado?",
    "Actualizar",
    "Cancelar",
    "swal_edicion"
  ).then((result) => {
    if (result.isConfirmed) {
      fetch(`${GET_BASE_URL()}/estadisticas/changeState/${id_estadistica}`, {
        method: "POST",
      })
      .then(response => response.json())
      window.location.replace(GET_BASE_URL() + "/estadisticas");
    }
  });
}

function obtenerNombreMes (numero) {
  let miFecha = new Date();
  if (0 < numero && numero <= 12) {
    miFecha.setMonth(numero - 1);
    return new Intl.DateTimeFormat('es-ES', { month: 'long'}).format(miFecha);
  } else {
    return null;
  }
}
