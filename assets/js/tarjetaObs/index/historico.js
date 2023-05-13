const getElement = (pageNumber, pageSize) => {
  return fetch(
    `${GET_BASE_URL()}/api/TarjetaObs/get/${pageNumber}/${pageSize}`, {
      method: "POST",
    }
  );
};
const getElementTotales = () => {
  return fetch(`${GET_BASE_URL()}/api/TarjetaObs/getTotal`, {
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
    "Proyecto",
    "Módulo",
    "Estación",
    "Sistema",
    "Fecha de Detección",
    "Observación",
    "Situación",
    // "Estado",
    // "Acciones",
  ],
  tableCells: [
    "id_tarjeta",
    "proyecto",
    {
      key: (row) => {
        let modulo;
        if (row['modulo']) {
          modulo = row['modulo'];
        } else {
          modulo = 'No Aplica'
        }
        return modulo;
      }
    },
    {
      key: (row) => {
        if (row['estacion']) {return row['estacion']} else {return 'No Aplica'}
      },
    },
    {
      key: (row) => {
        if (row['sistema']) {return row['sistema']} else {return 'No Aplica'}
      },
    },
    // "estacion",
    // "sistema",
    "fecha_deteccion",
    {
      key: (row) => {
        let div, clase;
        if (row["observacion"] == 1) {
          div = el("div", "Positiva");
          clase = 'obs_negativa';
        } else {
          div = el("div", "Mejora");
          clase = 'obs_positiva';
        }
        return div;
      },
      class: (row) => {
        if (row["observacion"] == 1) {
          return 'obs_positiva';
        } else {
          return 'obs_negativa';
        }
      },
    },
    {
      key: (row) => {
        let div;

        if (row["situacion"] == 1) {
          div = el("div.td_estado_activo", "Abierta");
        } else {
          div = el("div.td_estado_inactivo", "Cerrada");
        }
        return div;
      },
    },
    /*
    {
      key: (row) => {
        let button, p;
        button = el("button.btn_edit", el("i.fas fa-file"));
        p = el("p", "PDF");
        mount(button, p);
        return button;
      },
    },*/
  ],
  pager: {
    totalEntitiesText: "Cantidad de Resultados",
  },
  clickableRow: {
    url: `${GET_BASE_URL()}/TarjetaObs/view_obs/`,
    rowId: "id_tarjeta",
  },
};
dynTable = new DynamicTableCellsEditable(
  document.getElementById("tabla_tarjetas"),
  tableOptions
);
dynTable.init();