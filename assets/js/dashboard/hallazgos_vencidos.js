let btn_style_alert = document.querySelectorAll(".btn_style_alert");
let h_v_aceptable,
  h_v_moderado,
  h_v_significativo,
  h_v_intolerable,
  h_v_baja,
  h_v_media,
  h_v_alta,
  h_v_muy_alta;

/**
 * Filtra por aquellos hallazgos vencidos dependiendo el tipo que se pase por parámetro
 * El 'type' hace referencia a que significancia es
 * Esta función agrega la cantidad de hallazgos que hay en el primer card del dashboard 'Mis Observaciones Abiertas Vencidas (Tarjeta M.A.S)'
 */
const filterObsOverdue = (type = "", DOMContent = false) => {
  if (DOMContent) {
    for (let i = 0; i < btn_style_alert.length; i++) {
      let hallazgos = hallazgos_vencidos.filter(
        (h_v) => h_v.significancia == i + 1
      );
      btn_style_alert[i].textContent = hallazgos.length;
    }
  } else {
    switch (type) {
      case "aceptable":
        h_v_aceptable = hallazgos_vencidos.filter(
          (h_v) => h_v.significancia == 1
        );
        _createModalHallazgos(type, h_v_aceptable);
        break;
      case "moderado":
        h_v_moderado = hallazgos_vencidos.filter(
          (h_v) => h_v.significancia == 2
        );
        _createModalHallazgos(type, h_v_moderado);
        break;
      case "significativo":
        h_v_significativo = hallazgos_vencidos.filter(
          (h_v) => h_v.significancia == 3
        );
        _createModalHallazgos(type, h_v_significativo);
        break;
      case "intolerable":
        h_v_intolerable = hallazgos_vencidos.filter(
          (h_v) => h_v.significancia == 4
        );
        _createModalHallazgos(type, h_v_intolerable);
        break;

      case "baja":
        h_v_baja = hallazgos_vencidos.filter((h_v) => h_v.significancia == 5);
        _createModalHallazgos(type, h_v_baja);
        break;
      case "media":
        h_v_media = hallazgos_vencidos.filter((h_v) => h_v.significancia == 6);
        _createModalHallazgos(type, h_v_media);
        break;
      case "alta":
        h_v_alta = hallazgos_vencidos.filter((h_v) => h_v.significancia == 7);
        _createModalHallazgos(type, h_v_alta);
        break;
      case "muy_alta":
        h_v_muy_alta = hallazgos_vencidos.filter(
          (h_v) => h_v.significancia == 8
        );
        _createModalHallazgos(type, h_v_muy_alta);
        break;
    }
  }
};

const table_tbody_obs_overdue = document.getElementById(
  "table_tbody_obs_overdue"
);
const t_overdue_significancia = document.getElementById(
  "table_overdue_significancia"
);

/**
 * Crea el contenido del tbody cuando se hace click en la cantidad de hallazgos en la primer card 'Mis Observaciones Abiertas Vencidas (Tarjeta M.A.S)'
 * Al contenido se refiere a los datos del hallazgo para que cuando se le haga click, redireccione a la Tarjeta donde pertenece ese hallazgo.
 */
const _createModalHallazgos = (type, hallazgos) => {
  table_tbody_obs_overdue.innerHTML = "";
  let tr, td;

  hallazgos.forEach((h) => {
    // Id del hallazgo
    tr = el("tr");
    td = el("td", h.id);
    mount(tr, td);
    // Id de la tarjeta
    td = el("td", h.id_tarjeta);
    mount(tr, td);
    // Hallazgo
    td = el("td", h.hallazgo);
    mount(tr, td);
    // Plan de acción
    td = el("td", h.plan_accion);
    mount(tr, td);
    // Fecha de Cierre
    td = el("td", h.fecha_cierre_f);
    mount(tr, td);
    // Responsable
    td = el("td", h.responsable);
    mount(tr, td);

    tr.addEventListener("click", (e) => {
      window.location.href = `${GET_BASE_URL()}/TarjetaObs/view_obs/${
        h.id_tarjeta
      }`;
    });
    mount(table_tbody_obs_overdue, tr);
  });

  if (type == "aceptable" || type == "baja") {
    t_overdue_significancia.textContent =
      type == "aceptable" ? "Aceptable" : "Baja";
    t_overdue_significancia.style.color = "#A9D8DF";
  } else if (type == "moderado" || type == "media") {
    t_overdue_significancia.textContent =
      type == "moderado" ? "Moderado" : "Media";
    t_overdue_significancia.style.color = "#66b572";
  } else if (type == "significativo" || type == "alta") {
    t_overdue_significancia.textContent =
      type == "significactivo" ? "Significativo" : "Alta";
    t_overdue_significancia.style.color = "#E4CA4B";
  } else if (type == "intolerable" || type == "muy_alta") {
    t_overdue_significancia.textContent =
      type == "intolerable" ? "Intolerable" : "Muy Alta";
    t_overdue_significancia.style.color = "#DD8F60";
  }

  document.getElementById("btn_open_modal_h_overdue").click();
};
