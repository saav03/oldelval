const getElementInspecciones = (pageNumber, pageSize) => {
    return fetch(
      `${GET_BASE_URL()}/api/auditorias/getPendientes/${pageNumber}/${pageSize}`, {
        method: "POST",
      }
    );
  };
  const getElementTotalesInspecciones = () => {
    return fetch(`${GET_BASE_URL()}/api/auditorias/getTotalPendientes`, {
      method: "POST",
    });
  };

  const tableOptionsInspecciones = {
    tableCSSClass: "table_oldelval text-center",
    pageSize: 10,
    getDataCallback: getElementInspecciones,
    totalDataCallback: getElementTotalesInspecciones,
    tableHeader: [
      "#ID",
      "Inspección",
      "Modelo",
      "Contratista",
      "Supervisor",
      "Proyecto",
      "Usuario Carga",
      "Fecha Carga",
    ],
    tableCells: [
        'id_auditoria',
        'auditoria',
        'modelo_tipo',
        'contratista',
        'supervisor',
        'proyecto',
        'usuario_carga',
        'fecha_carga_format',
    ],
    pager: {
      totalEntitiesText: "Cantidad de Resultados",
    },
    clickableRow: {
      url: `${GET_BASE_URL()}/auditorias/view/`,
      rowId: "id_auditoria",
    },
  };
  dynTableInspecciones = new DynamicTableCellsEditable(
    document.getElementById("tabla_inspecciones"),
    tableOptionsInspecciones
  );
  dynTableInspecciones.init();