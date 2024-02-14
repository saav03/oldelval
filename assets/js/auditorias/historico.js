// # Dynamic Table Inspección de Control
const getFormDataAudControl = () => {
  const form = new FormData();
  form.append(
    "modelo_tipo",
    document.getElementById("modelo_tipo_control").value
  );
  form.append(
    "contratista",
    document.getElementById("contratista_control").value
  );
  form.append(
    "supervisor",
    document.getElementById("supervisor_control").value
  );
  form.append("proyecto", document.getElementById("proyecto_control").value);
  form.append(
    "usuario_carga",
    document.getElementById("usuario_carga_control").value
  );
  form.append(
    "fecha_desde",
    document.getElementById("fecha_desde_control").value
  );
  form.append(
    "fecha_hasta",
    document.getElementById("fecha_hasta_control").value
  );
  return form;
};

const getElementInspControl = (pageNumber, pageSize) => {
  return fetch(
    `${GET_BASE_URL()}/api/auditorias/getAuditorias/1/${pageNumber}/${pageSize}`,
    {
      method: "POST",
      body: getFormDataAudControl(),
    }
  );
};
const getElementInspControlTotales = () => {
  return fetch(`${GET_BASE_URL()}/api/auditorias/getTotalAuditorias/1`, {
    method: "POST",
    body: getFormDataAudControl(),
  });
};

const tableOptionsAudControl = {
  tableCSSClass: "table_oldelval",
  pageSize: 10,
  getDataCallback: getElementInspControl,
  totalDataCallback: getElementInspControlTotales,
  tableHeader: [
    "ID",
    "Modelo",
    "Contratista",
    "Supervisor Responsable",
    "Proyecto",
    "Usuario Carga",
    "Fecha Carga",
    permiso_eliminar_inspeccion ? "Acciones" : "",
  ],
  tableCells: [
    "id_auditoria",
    "modelo_tipo",
    "contratista",
    "supervisor",
    "proyecto",
    "usuario_carga",
    "fecha_carga_format",
    {
      key: (row) => {
        let div, btnDelete;
        div = el("div.d-flex justify-content-center");
        btnDelete = el("button.btn-desactivar ms-3", el("i.fas fa-trash"));
        btnDelete.setAttribute("data-id", row["id_auditoria"]);
        btnDelete.setAttribute("onclick", "deleteInspection(this)");

        btnPdf = el("a.btn_pdf", {
          style: "width: 35px; height: 35px; ",
          target: "_blank",
        });
        btnPdf.href =
          `${GET_BASE_URL()}/pdf/auditorias/inspeccion/` + row["id_auditoria"];
        let img = el("img", {
          alt: "Icono PDF",
          style: "width: 20px;",
        });
        img.src = `${GET_BASE_URL()}/assets/img/PDF.png`;

        // mount(btnPdf, img);
        // mount(div, btnPdf);

        if (permiso_eliminar_inspeccion) {
          mount(div, btnDelete);
        }
        return div;
      },
      noClickableRow: true,
    },
  ],
  pager: {
    totalEntitiesText: "Cantidad de Resultados",
  },
  clickableRow: {
    url: `${GET_BASE_URL()}/auditorias/view/`,
    rowId: "id_auditoria",
  },
};
dynTable = new DynamicTableCellsEditable(
  document.getElementById("tabla_auditoria_control"),
  tableOptionsAudControl
);

dynTable.addFilterField(document.getElementById("modelo_tipo_control"));
dynTable.addFilterField(document.getElementById("contratista_control"));
dynTable.addFilterField(document.getElementById("supervisor_control"));
dynTable.addFilterField(document.getElementById("proyecto_control"));
dynTable.addFilterField(document.getElementById("usuario_carga_control"));
dynTable.addFilterField(document.getElementById("fecha_desde_control"));
dynTable.addFilterField(document.getElementById("fecha_hasta_control"));
dynTable.init();

// # Dynamic Table Inspección Vehicular

const getFormDataAudVehicular = () => {
  const form = new FormData();
  form.append("id", document.getElementById("id_aud_vehicular").value);

  form.append(
    "contratista",
    document.getElementById("contratista_vehicular").value
  );
  form.append("equipo", document.getElementById("equipo").value);
  form.append("conductor", document.getElementById("conductor").value);
  form.append(
    "supervisor",
    document.getElementById("supervisor_vehicular").value
  );
  form.append(
    "modelo_tipo",
    document.getElementById("modelo_tipo_vehicular").value
  );
  form.append(
    "resultado",
    document.getElementById("resultado_inspeccion").value
  );
  form.append(
    "proyecto",
    document.getElementById("proyecto_aud_vehicular").value
  );
  form.append(
    "usuario_carga",
    document.getElementById("usuario_carga_vehicular").value
  );
  form.append(
    "fecha_desde",
    document.getElementById("fecha_desde_vehicular").value
  );
  form.append(
    "fecha_hasta",
    document.getElementById("fecha_hasta_vehicular").value
  );
  return form;
};

const getElementInspVehicular = (pageNumber, pageSize) => {
  return fetch(
    `${GET_BASE_URL()}/api/auditorias/getAuditorias/2/${pageNumber}/${pageSize}`,
    {
      method: "POST",
      body: getFormDataAudVehicular(),
    }
  );
};
const getElementInspVehicularTotales = () => {
  return fetch(`${GET_BASE_URL()}/api/auditorias/getTotalAuditorias/2`, {
    method: "POST",
    body: getFormDataAudVehicular(),
  });
};

const tableOptionsInspVehicular = {
  tableCSSClass: "table_oldelval",
  pageSize: 10,
  getDataCallback: getElementInspVehicular,
  totalDataCallback: getElementInspVehicularTotales,
  tableHeader: [
    "ID",
    "Modelo",
    "Contratista",
    "Supervisor Responsable",
    "Conductor",
    "Equipo",
    "Resultado",
    "Proyecto",
    "Usuario Carga",
    "Fecha Carga",
    permiso_eliminar_inspeccion ? "Acciones" : "",
  ],
  tableCells: [
    "id_auditoria",
    "modelo_tipo",
    "contratista",
    "supervisor",
    "conductor",
    "equipo",
    {
      key: (row) => {
        let span;
        if (row["resultado_inspeccion"] == 1) {
          span = "Satisfactoria";
        } else {
          span = "No Satisfactoria";
        }
        return span;
      },
    },
    "proyecto",
    "usuario_carga",
    "fecha_carga_format",
    {
      key: (row) => {
        let div, btnDelete;
        div = el("div.d-flex justify-content-center");
        btnDelete = el("button.btn-desactivar ms-3", el("i.fas fa-trash"));
        btnDelete.setAttribute("data-id", row["id_auditoria"]);
        btnDelete.setAttribute("onclick", "deleteInspection(this)");

        btnPdf = el("a.btn_pdf", {
          style: "width: 35px; height: 35px; ",
          target: "_blank",
        });
        btnPdf.href =
          `${GET_BASE_URL()}/pdf/auditorias/inspeccion/` + row["id_auditoria"];
        let img = el("img", {
          alt: "Icono PDF",
          style: "width: 20px;",
        });
        img.src = `${GET_BASE_URL()}/assets/img/PDF.png`;

        // mount(btnPdf, img);
        // mount(div, btnPdf);

        if (permiso_eliminar_inspeccion) {
          mount(div, btnDelete);
        }
        return div;
      },
      noClickableRow: true,
    },
  ],
  pager: {
    totalEntitiesText: "Cantidad de Resultados",
  },
  clickableRow: {
    url: `${GET_BASE_URL()}/auditorias/view/`,
    rowId: "id_auditoria",
  },
};
dynTableInspVehicular = new DynamicTableCellsEditable(
  document.getElementById("tabla_auditoria_vehicular"),
  tableOptionsInspVehicular
);

dynTableInspVehicular.addFilterField(
  document.getElementById("modelo_tipo_vehicular")
);
dynTableInspVehicular.addFilterField(
  document.getElementById("contratista_vehicular")
);
dynTableInspVehicular.addFilterField(
  document.getElementById("supervisor_vehicular")
);
dynTableInspVehicular.addFilterField(
  document.getElementById("proyecto_aud_vehicular")
);
dynTableInspVehicular.addFilterField(document.getElementById("equipo"));
dynTableInspVehicular.addFilterField(document.getElementById("conductor"));
dynTableInspVehicular.addFilterField(
  document.getElementById("resultado_inspeccion")
);
dynTableInspVehicular.addFilterField(
  document.getElementById("usuario_carga_vehicular")
);
dynTableInspVehicular.addFilterField(
  document.getElementById("fecha_desde_vehicular")
);
dynTableInspVehicular.addFilterField(
  document.getElementById("fecha_hasta_vehicular")
);
dynTableInspVehicular.init();

// # Dynamic Table Inspección de Obra

const getFormDataInspdeObra = () => {
  const form = new FormData();
  form.append("modelo_tipo", document.getElementById("modelo_tipo_obra").value);
  form.append("contratista", document.getElementById("contratista_obra").value);
  form.append("supervisor", document.getElementById("supervisor_obra").value);
  form.append("proyecto", document.getElementById("proyecto_obra").value);
  form.append(
    "usuario_carga",
    document.getElementById("usuario_carga_obra").value
  );
  form.append("fecha_desde", document.getElementById("fecha_desde_obra").value);
  form.append("fecha_hasta", document.getElementById("fecha_hasta_obra").value);
  return form;
};

const getElementInspObra = (pageNumber, pageSize) => {
  return fetch(
    `${GET_BASE_URL()}/api/auditorias/getAuditorias/3/${pageNumber}/${pageSize}`,
    {
      method: "POST",
      body: getFormDataInspdeObra(),
    }
  );
};
const getElementInspObraTotales = () => {
  return fetch(`${GET_BASE_URL()}/api/auditorias/getTotalAuditorias/3`, {
    method: "POST",
    body: getFormDataInspdeObra(),
  });
};

const tableOptionsInspObra = {
  tableCSSClass: "table_oldelval",
  pageSize: 10,
  getDataCallback: getElementInspObra,
  totalDataCallback: getElementInspObraTotales,
  tableHeader: [
    "ID",
    "Modelo",
    "Contratista",
    "Supervisor Responsable",
    "Proyecto",
    "Usuario Carga",
    "Fecha Carga",
    permiso_eliminar_inspeccion ? "Acciones" : "",
  ],
  tableCells: [
    "id_auditoria",
    "modelo_tipo",
    "contratista",
    "supervisor",
    "proyecto",
    "usuario_carga",
    "fecha_carga_format",
    {
      key: (row) => {
        let div, btnDelete;
        div = el("div.d-flex justify-content-center");
        btnDelete = el("button.btn-desactivar ms-3", el("i.fas fa-trash"));
        btnDelete.setAttribute("data-id", row["id_auditoria"]);
        btnDelete.setAttribute("onclick", "deleteInspection(this)");

        btnPdf = el("a.btn_pdf", {
          style: "width: 35px; height: 35px; ",
          target: "_blank",
        });
        btnPdf.href =
          `${GET_BASE_URL()}/pdf/auditorias/inspeccion/` + row["id_auditoria"];
        let img = el("img", {
          alt: "Icono PDF",
          style: "width: 20px;",
        });
        img.src = `${GET_BASE_URL()}/assets/img/PDF.png`;

        // mount(btnPdf, img);
        // mount(div, btnPdf);

        if (permiso_eliminar_inspeccion) {
          mount(div, btnDelete);
        }
        return div;
      },
      noClickableRow: true,
    },
  ],
  pager: {
    totalEntitiesText: "Cantidad de Resultados",
  },
  clickableRow: {
    url: `${GET_BASE_URL()}/auditorias/view/`,
    rowId: "id_auditoria",
  },
};
dynTableInspObra = new DynamicTableCellsEditable(
  document.getElementById("table_inspeccion_obra"),
  tableOptionsInspObra
);

dynTableInspObra.addFilterField(document.getElementById("modelo_tipo_obra"));
dynTableInspObra.addFilterField(document.getElementById("contratista_obra"));
dynTableInspObra.addFilterField(document.getElementById("supervisor_obra"));
dynTableInspObra.addFilterField(document.getElementById("proyecto_obra"));
dynTableInspObra.addFilterField(document.getElementById("usuario_carga_obra"));
dynTableInspObra.addFilterField(document.getElementById("fecha_desde_obra"));
dynTableInspObra.addFilterField(document.getElementById("fecha_hasta_obra"));
dynTableInspObra.init();

// # Dynamic Table Inspección de Auditoría

const getFormDataInspdeAuditoria = () => {
  const form = new FormData();
  form.append(
    "modelo_tipo",
    document.getElementById("modelo_tipo_auditoria").value
  );
  form.append(
    "contratista",
    document.getElementById("contratista_auditoria").value
  );
  form.append(
    "supervisor",
    document.getElementById("supervisor_auditoria").value
  );
  form.append("proyecto", document.getElementById("proyecto_auditoria").value);
  form.append(
    "usuario_carga",
    document.getElementById("usuario_carga_auditoria").value
  );
  form.append(
    "fecha_desde",
    document.getElementById("fecha_desde_auditoria").value
  );
  form.append(
    "fecha_hasta",
    document.getElementById("fecha_hasta_auditoria").value
  );
  return form;
};

const getElementInspAuditoria = (pageNumber, pageSize) => {
  return fetch(
    `${GET_BASE_URL()}/api/auditorias/getAuditorias/4/${pageNumber}/${pageSize}`,
    {
      method: "POST",
      body: getFormDataInspdeAuditoria(),
    }
  );
};
const getElementInspAuditoriaTotales = () => {
  return fetch(`${GET_BASE_URL()}/api/auditorias/getTotalAuditorias/4`, {
    method: "POST",
    body: getFormDataInspdeAuditoria(),
  });
};

const tableOptionsInspAuditoria = {
  tableCSSClass: "table_oldelval",
  pageSize: 10,
  getDataCallback: getElementInspAuditoria,
  totalDataCallback: getElementInspAuditoriaTotales,
  tableHeader: [
    "ID",
    "Modelo",
    "Contratista",
    "Supervisor Responsable",
    "Proyecto",
    "Usuario Carga",
    "Fecha Carga",
    permiso_eliminar_inspeccion ? "Acciones" : "",
  ],
  tableCells: [
    "id_auditoria",
    "modelo_tipo",
    "contratista",
    "supervisor",
    "proyecto",
    "usuario_carga",
    "fecha_carga_format",
    {
      key: (row) => {
        let div, btnDelete;
        div = el("div.d-flex justify-content-center");
        btnDelete = el("button.btn-desactivar ms-3", el("i.fas fa-trash"));
        btnDelete.setAttribute("data-id", row["id_auditoria"]);
        btnDelete.setAttribute("onclick", "deleteInspection(this)");

        btnPdf = el("a.btn_pdf", {
          style: "width: 35px; height: 35px; ",
          target: "_blank",
        });
        btnPdf.href =
          `${GET_BASE_URL()}/pdf/auditorias/inspeccion/` + row["id_auditoria"];
        let img = el("img", {
          alt: "Icono PDF",
          style: "width: 20px;",
        });
        img.src = `${GET_BASE_URL()}/assets/img/PDF.png`;

        // mount(btnPdf, img);
        // mount(div, btnPdf);

        if (permiso_eliminar_inspeccion) {
          mount(div, btnDelete);
        }
        return div;
      },
      noClickableRow: true,
    },
  ],
  pager: {
    totalEntitiesText: "Cantidad de Resultados",
  },
  clickableRow: {
    url: `${GET_BASE_URL()}/auditorias/view/`,
    rowId: "id_auditoria",
  },
};
dynTableInspAuditoria = new DynamicTableCellsEditable(
  document.getElementById("table_inspeccion_auditoria"),
  tableOptionsInspAuditoria
);

dynTableInspAuditoria.addFilterField(
  document.getElementById("modelo_tipo_auditoria")
);
dynTableInspAuditoria.addFilterField(
  document.getElementById("contratista_auditoria")
);
dynTableInspAuditoria.addFilterField(
  document.getElementById("supervisor_auditoria")
);
dynTableInspAuditoria.addFilterField(
  document.getElementById("proyecto_auditoria")
);
dynTableInspAuditoria.addFilterField(
  document.getElementById("usuario_carga_auditoria")
);
dynTableInspAuditoria.addFilterField(
  document.getElementById("fecha_desde_auditoria")
);
dynTableInspAuditoria.addFilterField(
  document.getElementById("fecha_hasta_auditoria")
);
dynTableInspAuditoria.init();
