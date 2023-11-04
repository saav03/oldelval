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
            let btn, div, btnPdf;
            div = el('div.d-flex justify-content-around');
            // BTN para desactivar
            if (row["estado"] == 1) {
                btn = el("button.btn-desactivar", el("i.fas fa-ban"));
            } else {
                btn = el("button.btn-activar", el("i.fas fa-check"));
            }
            btn.setAttribute("data-id", row["id_auditoria"]);
            btn.setAttribute("onclick", "changeStateAuditoria(this, 1)");

            // BTN pdf 
            btnPdf = el('a.btn_pdf', {
                style: 'width: 35px; height: 35px;',
                target: '_blank',
            });
            btnPdf.href = `${GET_BASE_URL()}/pdf/auditoria_control/` + row["id_auditoria"]
            let img = el('img', {
                alt: 'Icono PDF',
                style: 'width: 20px;'
            });
            img.src = `${GET_BASE_URL()}/assets/img/PDF.png`;
            mount(btnPdf, img)
            mount(div, btnPdf);

            mount(div, btn);
            return div;
        },
        noClickableRow: true,
    },],
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
            let btn, div, btnPdf;
            div = el('div.d-flex justify-content-around');
            if (row["estado"] == 1) {
                btn = el("button.btn-desactivar", el("i.fas fa-ban"));
            } else {
                btn = el("button.btn-activar", el("i.fas fa-check"));
            }
            btn.setAttribute("data-id", row["id_auditoria"]);
            btn.setAttribute("onclick", "changeStateAuditoria(this, 0)");

            // BTN pdf 
            btnPdf = el('a.btn_pdf', {
                style: 'width: 35px; height: 35px;',
                target: '_blank',
            });
            btnPdf.href = `${GET_BASE_URL()}/pdf/auditoria_vehicular/` + row["id_auditoria"]
            let img = el('img', {
                alt: 'Icono PDF',
                style: 'width: 20px;'
            });
            img.src = `${GET_BASE_URL()}/assets/img/PDF.png`;
            mount(btnPdf, img)
            mount(div, btnPdf);

            mount(div, btn);

            return div;
        },
        noClickableRow: true,
    },],
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



/*
=======================================
Histórico para la Auditorías de Tarea De Campo
=======================================
*/

const getFormDataAudControl_tarea_de_campo = () => {
    const form = new FormData();
    form.append("modelo_tipo_tarea_de_campo", document.getElementById("modelo_tipo_tarea_de_campo").value);
    form.append("contratista_tarea_de_campo", document.getElementById("contratista_tarea_de_campo").value);
    form.append("supervisor_tarea_de_campo", document.getElementById("supervisor_tarea_de_campo").value);
    form.append("proyecto_aud_tarea_de_campo", document.getElementById("proyecto_aud_tarea_de_campo").value);
    form.append("usuario_carga_tarea_de_campo", document.getElementById("usuario_carga_tarea_de_campo").value);
    form.append("fecha_desde_tarea_de_campo", document.getElementById("fecha_desde_tarea_de_campo").value);
    form.append("fecha_hasta_tarea_de_campo", document.getElementById("fecha_hasta_tarea_de_campo").value);
    return form;
}

const getElementAudControl_tarea_de_campo = (pageNumber, pageSize) => {
    return fetch(
        `${GET_BASE_URL()}/api/auditorias/get_tarea_campo/${pageNumber}/${pageSize}`, {
        method: "POST",
        body: getFormDataAudControl_tarea_de_campo()
    }
    );
};
const getElementTotalesAudControl_tarea_de_campo = () => {
    return fetch(`${GET_BASE_URL()}/api/auditorias/getTotal_tarea_campo`, {
        method: "POST",
        body: getFormDataAudControl_tarea_de_campo()
    });
};

const tableOptionsAud_tarea_de_campo = {
    tableCSSClass: "table_oldelval",
    pageSize: 10,
    getDataCallback: getElementAudControl_tarea_de_campo,
    totalDataCallback: getElementTotalesAudControl_tarea_de_campo,
    tableHeader: ['ID', 'Modelo', 'Contratista', 'Supervisor Responsable', 'Proyecto', 'Usuario Carga', 'Fecha Carga', 'Acciones'],
    tableCells: ["id_auditoria", 'modelo_tipo', 'contratista', 'supervisor', 'proyecto', 'usuario_carga', 'fecha_carga_format', {
        key: (row) => {
            let btn, div, btnPdf;
            div = el('div.d-flex justify-content-around');
            // BTN para desactivar
            if (row["estado"] == 1) {
                btn = el("button.btn-desactivar", el("i.fas fa-ban"));
            } else {
                btn = el("button.btn-activar", el("i.fas fa-check"));
            }
            btn.setAttribute("data-id", row["id_auditoria"]);
            btn.setAttribute("onclick", "changeStateAuditoria(this, 1)");

            // BTN pdf 
            btnPdf = el('a.btn_pdf', {
                style: 'width: 35px; height: 35px;',
                target: '_blank',
            });
            btnPdf.href = `${GET_BASE_URL()}/pdf/auditoria_tarea_de_campo/` + row["id_auditoria"]
            let img = el('img', {
                alt: 'Icono PDF',
                style: 'width: 20px;'
            });
            img.src = `${GET_BASE_URL()}/assets/img/PDF.png`;
            mount(btnPdf, img)
            mount(div, btnPdf);

            mount(div, btn);
            return div;
        },
        noClickableRow: true,
    },],
    pager: {
        totalEntitiesText: "Cantidad de Resultados",
    },
    clickableRow: {
        url: `${GET_BASE_URL()}/auditorias/view_aud_tarea_campo/`,
        rowId: 'id_auditoria',
    },
};
dynTableTarea = new DynamicTableCellsEditable(
    document.getElementById("tabla_auditoria_tarea_de_campo"),
    tableOptionsAud_tarea_de_campo
);

dynTableTarea.addFilterField(document.getElementById("modelo_tipo_tarea_de_campo"));
dynTableTarea.addFilterField(document.getElementById("contratista_tarea_de_campo"));
dynTableTarea.addFilterField(document.getElementById("supervisor_tarea_de_campo"));
dynTableTarea.addFilterField(document.getElementById("proyecto_aud_tarea_de_campo"));
dynTableTarea.addFilterField(document.getElementById("usuario_carga_tarea_de_campo"));
dynTableTarea.addFilterField(document.getElementById("fecha_desde_tarea_de_campo"));
dynTableTarea.addFilterField(document.getElementById("fecha_hasta_tarea_de_campo"));
dynTableTarea.init();




/*
=======================================
Histórico para la Auditoria Auditoria
=======================================
*/

const getFormDataAud_auditoria = () => {
    const form = new FormData();
    form.append("modelo_tipo_auditoria_a", document.getElementById("modelo_tipo_auditoria_a").value);
    form.append("contratista_auditoria_a", document.getElementById("contratista_auditoria_a").value);
    form.append("supervisor_auditoria_a", document.getElementById("supervisor_auditoria_a").value);
    form.append("proyecto_aud_auditoria_a", document.getElementById("proyecto_aud_auditoria_a").value);
    form.append("usuario_carga_auditoria_a", document.getElementById("usuario_carga_auditoria_a").value);
    form.append("fecha_desde_auditoria_a", document.getElementById("fecha_desde_tarea_de_campo").value);
    form.append("fecha_hasta_auditoria_a", document.getElementById("fecha_hasta_auditoria_a").value);
    return form;
}

const getElementAud_auditoria = (pageNumber, pageSize) => {
    return fetch(
        `${GET_BASE_URL()}/api/auditorias/get_auditoria/${pageNumber}/${pageSize}`, {
        method: "POST",
        body: getFormDataAud_auditoria()
    }
    );
};
const getElementTotalesAud_auditoria = () => {
    return fetch(`${GET_BASE_URL()}/api/auditorias/getTotal_auditoria`, {
        method: "POST",
        body: getFormDataAud_auditoria()
    });
};

const tableOptionsAud_auditoria = {
    tableCSSClass: "table_oldelval",
    pageSize: 10,
    getDataCallback: getElementAud_auditoria,
    totalDataCallback: getElementTotalesAud_auditoria,
    tableHeader: ['ID', 'Modelo', 'Contratista', 'Supervisor Responsable', 'Proyecto', 'Usuario Carga', 'Fecha Carga', 'Acciones'],
    tableCells: ["id_auditoria", 'modelo_tipo', 'contratista', 'supervisor', 'proyecto', 'usuario_carga', 'fecha_carga_format', {
        key: (row) => {
            let btn, div, btnPdf;
            div = el('div.d-flex justify-content-around');
            // BTN para desactivar
            if (row["estado"] == 1) {
                btn = el("button.btn-desactivar", el("i.fas fa-ban"));
            } else {
                btn = el("button.btn-activar", el("i.fas fa-check"));
            }
            btn.setAttribute("data-id", row["id_auditoria"]);
            btn.setAttribute("onclick", "changeStateAuditoria(this, 1)");

            // BTN pdf 
            btnPdf = el('a.btn_pdf', {
                style: 'width: 35px; height: 35px;',
                target: '_blank',
            });
            btnPdf.href = `${GET_BASE_URL()}/pdf/auditoria_auditoria/` + row["id_auditoria"]
            let img = el('img', {
                alt: 'Icono PDF',
                style: 'width: 20px;'
            });
            img.src = `${GET_BASE_URL()}/assets/img/PDF.png`;
            mount(btnPdf, img)
            mount(div, btnPdf);

            mount(div, btn);
            return div;
        },
        noClickableRow: true,
    },],
    pager: {
        totalEntitiesText: "Cantidad de Resultados",
    },
    clickableRow: {
        url: `${GET_BASE_URL()}/auditorias/view_aud_auditoria/`,
        rowId: 'id_auditoria',
    },
};
dynTable = new DynamicTableCellsEditable(
    document.getElementById("tabla_auditoria_auditoria"),
    tableOptionsAud_auditoria
);

dynTable.addFilterField(document.getElementById("modelo_tipo_auditoria_a"));
dynTable.addFilterField(document.getElementById("contratista_auditoria_a"));
dynTable.addFilterField(document.getElementById("supervisor_auditoria_a"));
dynTable.addFilterField(document.getElementById("proyecto_aud_auditoria_a"));
dynTable.addFilterField(document.getElementById("usuario_carga_auditoria_a"));
dynTable.addFilterField(document.getElementById("fecha_desde_auditoria_a"));
dynTable.addFilterField(document.getElementById("fecha_hasta_auditoria_a"));
dynTable.init();






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