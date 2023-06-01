/*
=======================================
Histórico para la Auditorías de Control
=======================================
*/

const getFormDataAudControl = () => {
    const form = new FormData();
    form.append("modelo_tipo_control", document.getElementById("modelo_tipo_control").value);
    form.append("contratista", document.getElementById("contratista").value);
    form.append("supervisor", document.getElementById("supervisor").value);
    form.append("proyecto_aud_control", document.getElementById("proyecto_aud_control").value);
    form.append("usuario_carga_control", document.getElementById("usuario_carga_control").value);
    form.append("fecha_desde_control", document.getElementById("fecha_desde_control").value);
    form.append("fecha_hasta_control", document.getElementById("fecha_hasta_control").value);
    return form;
}

const getElementAudControl = (pageNumber, pageSize) => {
    return fetch(
        `${GET_BASE_URL()}/api/auditorias/getControl/${pageNumber}/${pageSize}`, {
            method: "POST",
            body: getFormDataAudControl()
        }
    );
};
const getElementTotalesAudControl = () => {
    return fetch(`${GET_BASE_URL()}/api/auditorias/getTotalControl`, {
        method: "POST",
        body: getFormDataAudControl()
    });
};

const tableOptionsAudControl = {
    tableCSSClass: "table_oldelval",
    pageSize: 10,
    getDataCallback: getElementAudControl,
    totalDataCallback: getElementTotalesAudControl,
    tableHeader: ['ID', 'Modelo', 'Contratista', 'Supervisor Responsable', 'Proyecto', 'Usuario Carga', 'Fecha Carga', 'Acciones'],
    tableCells: ["id_auditoria", 'modelo_tipo', 'contratista', 'supervisor', 'proyecto', 'usuario_carga', 'fecha_carga_format', {
        key: (row) => {
            let btn;
            if (row["estado"] == 1) {
                btn = el("button.btn_desactivar", el("i.fas fa-ban"));
            } else {
                btn = el("button.btn_activar", el("i.fas fa-check"));
            }
            btn.setAttribute("data-id", row["id_auditoria"]);
            btn.setAttribute("onclick", "changeStateAuditoria(this, 1)");
            return btn;
        },
        noClickableRow: true,
    }, ],
    pager: {
        totalEntitiesText: "Cantidad de Resultados",
    },
    clickableRow: {
        url: `${GET_BASE_URL()}/auditorias/view_aud_control/`,
        rowId: 'id_auditoria',
    },
};
dynTable = new DynamicTableCellsEditable(
    document.getElementById("tabla_auditoria_control"),
    tableOptionsAudControl
);

dynTable.addFilterField(document.getElementById("modelo_tipo_control"));
dynTable.addFilterField(document.getElementById("contratista"));
dynTable.addFilterField(document.getElementById("supervisor"));
dynTable.addFilterField(document.getElementById("proyecto_aud_control"));
dynTable.addFilterField(document.getElementById("usuario_carga_control"));
dynTable.addFilterField(document.getElementById("fecha_desde_control"));
dynTable.addFilterField(document.getElementById("fecha_hasta_control"));
dynTable.init();

/*
===================================================
Histórico para la Auditorías de CheckList Vehicular
===================================================
*/

const getFormDataAudVehicular = () => {
    const form = new FormData();
    form.append("id_aud_vehicular", document.getElementById("id_aud_vehicular").value);
    form.append("modelo_tipo_vehicular", document.getElementById("modelo_tipo_vehicular").value);
    form.append("equipo", document.getElementById("equipo").value);
    form.append("conductor", document.getElementById("conductor").value);
    form.append("num_interno_vehicular", document.getElementById("num_interno_vehicular").value);
    form.append("titular", document.getElementById("titular").value);
    form.append("proyecto_aud_vehicular", document.getElementById("proyecto_aud_vehicular").value);
    form.append("resultado_inspeccion", document.getElementById("resultado_inspeccion").value);
    form.append("usuario_carga_vehicular", document.getElementById("usuario_carga_vehicular").value);
    form.append("fecha_desde_vehicular", document.getElementById("fecha_desde_vehicular").value);
    form.append("fecha_hasta_vehicular", document.getElementById("fecha_hasta_vehicular").value);
    return form;
}

const getElementAudVehicular = (pageNumber, pageSize) => {
    return fetch(
        `${GET_BASE_URL()}/api/auditorias/getVehicular/${pageNumber}/${pageSize}`, {
            method: "POST",
            body: getFormDataAudVehicular()
        }
    );
};
const getElementTotalesAudVehicular = () => {
    return fetch(`${GET_BASE_URL()}/api/auditorias/getTotalVehicular`, {
        method: "POST",
        body: getFormDataAudVehicular()
    });
};

const tableOptionsAudVehicular = {
    tableCSSClass: "table_oldelval",
    pageSize: 10,
    getDataCallback: getElementAudVehicular,
    totalDataCallback: getElementTotalesAudVehicular,
    tableHeader: ['ID', 'Modelo', 'Equipo', 'Conductor', 'N° Interno', 'Titular', 'Proyecto', 'Resultado', 'Usuario Carga', 'Fecha Carga', 'Acciones'],
    tableCells: ['id_auditoria', 'modelo_tipo', 'equipo', 'conductor', 'num_interno', 'titular', 'proyecto', 'resultado_inspeccion', 'usuario_carga', 'fecha_carga_format', {
        key: (row) => {
            let btn;
            if (row["estado"] == 1) {
                btn = el("button.btn_desactivar", el("i.fas fa-ban"));
            } else {
                btn = el("button.btn_activar", el("i.fas fa-check"));
            }
            btn.setAttribute("data-id", row["id_auditoria"]);
            btn.setAttribute("onclick", "changeStateAuditoria(this, 0)");
            return btn;
        },
        noClickableRow: true,
    }, ],
    pager: {
        totalEntitiesText: "Cantidad de Resultados",
    },
    clickableRow: {
        url: `${GET_BASE_URL()}/auditorias/view_aud_vehicular/`,
        rowId: 'id_auditoria',
    },

};
dynTableAudVehicular = new DynamicTableCellsEditable(
    document.getElementById("tabla_auditoria_vehicular"),
    tableOptionsAudVehicular
);
dynTableAudVehicular.addFilterField(document.getElementById("id_aud_vehicular"));
dynTableAudVehicular.addFilterField(document.getElementById("modelo_tipo_vehicular"));
dynTableAudVehicular.addFilterField(document.getElementById("equipo"));
dynTableAudVehicular.addFilterField(document.getElementById("conductor"));
dynTableAudVehicular.addFilterField(document.getElementById("num_interno_vehicular"));
dynTableAudVehicular.addFilterField(document.getElementById("titular"));
dynTableAudVehicular.addFilterField(document.getElementById("proyecto_aud_vehicular"));
dynTableAudVehicular.addFilterField(document.getElementById("resultado_inspeccion"));
dynTableAudVehicular.addFilterField(document.getElementById("usuario_carga_vehicular"));
dynTableAudVehicular.addFilterField(document.getElementById("fecha_desde_vehicular"));
dynTableAudVehicular.addFilterField(document.getElementById("fecha_hasta_vehicular"));
dynTableAudVehicular.init();

function changeStateAuditoria(e, tipo_aud) {
    let id_aud = e.getAttribute("data-id");
    customConfirmationButton(
        "Actualizar Estado Auditoría",
        "¿Confirma actualizar el estado?",
        "Actualizar",
        "Cancelar",
        "swal_edicion"
    ).then((result) => {
        if (result.isConfirmed) {
            fetch(`${GET_BASE_URL()}/auditorias/changeState/${id_aud}/${tipo_aud}`, {
                    method: "POST",
                })
                .then(response => response.json())
            window.location.replace(GET_BASE_URL() + "/auditorias");
        }
    });
}