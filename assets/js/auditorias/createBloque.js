/**
 * Crea el bloque de la Auditoría, como primer instancia crea el selector para seleccionar
 * la Inspección que se va a realizar.
 * Ese selector va a escuchar por un onchange que tiene la funcionalidad de crear
 * el sector de preguntas y el hallazgo
 */
function createBloque(e) {
  fetch(`${GET_BASE_URL()}/auditorias/getAudType/${e.value}`)
    .then((response) => response.json())
    .then((data) => {
      let checklist = document.getElementById("checklist");
      checklist.innerHTML = "";

      let form = el("form#form_checklist", { method: "POST" });

      let inpAud = el("input", {
        type: "hidden",
        name: "auditoria",
        value: e.value,
      });
      mount(form, inpAud);

      let row = el("div.row", {
        style: "display: flex; justify-content: center;",
      });

      let col = el("div.col-xs-12 col-md-4");
      let formGroup = el("div.form-group mb-3");
      let label = el(
        "label.sz_inp mb-1 fw-semibold",
        "Seleccione la Inspección"
      );
      let select = el("select.form-select sz_inp", {
        name: "tipo_auditoria",
        id: "tipo_auditoria",
      });
      select.setAttribute("onchange", `getBloqueAuditorias(this, ${e.value})`);
      let opt = el("option", "-- Seleccione --");

      mount(select, opt);

      data.forEach((d) => {
        let option = el(
          "option",
          {
            value: d.id,
          },
          d.nombre
        );
        mount(select, option);
      });

      mount(col, label);
      mount(col, select);
      mount(col, formGroup);
      mount(row, col);

      mount(form, row);
      // mount(checklist, form)

      // ==============================

      let h5 = el("h5.text-center", {
        style: "font-size: 17;",
        id: "textTitle",
      });

      let encabezado = el("div.card card_custom", {
        id: "datos_generales",
      });

      let bodyChecklist = el("div.card card_custom", {
        id: "body_checklist",
      });

      let bodyObs = el("div.card card_custom", {
        id: "body_obs",
      });

      mount(checklist, h5);
      mount(form, encabezado);
      mount(form, bodyChecklist);
      mount(form, bodyObs);
      mount(checklist, form);
    });
}

/**
 * Escucha por un onChange del selector y crea el Encabezado, el cuerpo del checklist
 * y también la Observación del Hallazgo y es positiva o una Oportunidad de Mejora.
 */
function getBloqueAuditorias(e, tipo) {
  document.getElementById("datos_generales").innerHTML = "";
  document.getElementById("body_checklist").innerHTML = "";
  document.getElementById("body_obs").innerHTML = "";

  if (document.getElementById("btnSendInspection")) {
    document.getElementById("btnSendInspection").parentElement.remove();
  }

  createEncabezado(e.value, tipo);
  createBodyCheckList(e.value);
  createObsHallazgo();
}

/**
 * Crea el encabezado de los datos generales
 * Va a ser el mismo encabezado para la mayorías de las Inspecciones, pero para la Inspección Vehicular es distinta.
 */
function createEncabezado(id_aud, tipo) {
  let encabezado = document.getElementById("datos_generales");
  let row, col, div, label, select, opt, input, textarea;

  let cardHeader = el(
    "div.card-header card_header_custom header-perfil text-center",
    "Datos Generales"
  );
  let cardBody = el("div.card-body");
  row = el("div.row");
  col = el("div.col-md-3 col-xs-12 mt-2");
  div = el("div");
  label = el("label.mb-1 fw-semibold", "Contratista");
  select = el("select.sz_inp", {
    name: "contratista",
    id: "contratista",
    style: "width: 100%;",
    "data-search": "true",
    "data-silent-initial-value-set": "true",
  });

  contratistas.forEach((gral) => {
    opt = el(
      "option",
      {
        value: gral.id,
      },
      gral.nombre
    );
    mount(select, opt);
  });

  // Contratista
  mount(div, label);
  mount(div, select);
  mount(col, div);
  mount(row, col);
  // ---------

  if (tipo == 2) {
    col = el("div.col-md-3 col-xs-12 mt-2");
    label = el("label.mb-1 fw-semibold", "Equipo");
    input = el("input.form-control sz_inp", {
      name: "equipo",
      placeholder: "Ingrese el equipo",
    });

    // Equipo
    mount(col, label);
    mount(col, input);
    mount(row, col);
    // ---------

    col = el("div.col-md-3 col-xs-12 mt-2");
    label = el("label.mb-1 fw-semibold", "Conductor");
    input = el("input.form-control sz_inp", {
      name: "conductor",
      placeholder: "(Nombre + Apellido)",
    });

    // Conductor
    mount(col, label);
    mount(col, input);
    mount(row, col);
    // ---------

    col = el("div.col-md-3 col-xs-12 mt-2");
    div = el("div");
    label = el("label.mb-1 fw-semibold", "Supervisor");
    input = el("input.form-control sz_inp", {
      name: "supervisor_responsable",
      placeholder: "(Nombre + Apellido)",
    });
    // select = el("select.sz_inp", {
    //   name: "supervisor_responsable",
    //   id: "supervisor_responsable",
    //   style: "width: 100%;",
    //   "data-search": "true",
    //   "data-silent-initial-value-set": "true",
    // });

    // usuarios.forEach((gral) => {
    //   opt = el(
    //     "option",
    //     {
    //       value: gral.id,
    //     },
    //     gral.nombre + " " + gral.apellido
    //   );
    //   mount(select, opt);
    // });

    // Supervisor
    mount(div, label);
    mount(div, input);
    mount(col, div);
    mount(row, col);
    // ---------

    col = el("div.col-md-3 col-xs-12 mt-2");
    label = el("label.mb-1 fw-semibold", "N° Interno");
    input = el("input.form-control sz_inp", {
      name: "num_interno",
      placeholder: "Ingrese el N°",
    });

    // N° Interno
    mount(col, label);
    mount(col, input);
    mount(row, col);
    // ---------

    col = el("div.col-md-3 col-xs-12 mt-2");
    div = el("div");
    label = el("label.mb-1 fw-semibold", "Proyectos / Área");
    select = el("select.sz_inp form-select", {
      name: "proyecto",
      id: "proyecto",
    });
    opt = el("option", "-- Seleccione --");
    mount(select, opt);

    proyectos.forEach((gral) => {
      opt = el(
        "option",
        {
          value: gral.id,
        },
        gral.nombre
      );
      mount(select, opt);
    });

    // Proyectos
    mount(div, label);
    mount(div, select);
    mount(col, div);
    mount(row, col);
    // ---------

    col = el("div.col-md-2 col-xs-12 mt-2");
    label = el("label.mb-1 fw-semibold", "Marca");
    input = el("input.form-control sz_inp", {
      name: "marca",
      placeholder: "Ingrese la marca",
    });

    // Marca
    mount(col, label);
    mount(col, input);
    mount(row, col);
    // ---------

    col = el("div.col-md-2 col-xs-12 mt-2");
    label = el("label.mb-1 fw-semibold", "Modelo");
    input = el("input.form-control sz_inp", {
      name: "modelo",
      placeholder: "Ingrese el modelo",
    });

    // Modelo
    mount(col, label);
    mount(col, input);
    mount(row, col);
    // ---------

    col = el("div.col-md-2 col-xs-12 mt-2");
    label = el("label.mb-1 fw-semibold", "Patente");
    input = el("input.form-control sz_inp", {
      name: "patente",
      placeholder: "Ingrese la patente",
    });

    // Patente
    mount(col, label);
    mount(col, input);
    mount(row, col);
    // ---------

    col = el("div.col-md-3 col-xs-12 mt-2");

    // Div Col vacía
    mount(row, col);
    // ---------

    col = el("div.col-md-3 col-xs-12 mt-2");
    label = el("label.mb-1 fw-semibold", "Fecha");
    input = el("input.form-control text-center sz_inp simulate_dis", {
      name: "fecha_hoy",
      value: today,
      type: "date",
    });

    // Fecha
    mount(col, label);
    mount(col, input);
    mount(row, col);
    // ---------

    col = el("div.col-md-3 col-xs-12 mt-2");
    label = el("label.mb-1 fw-semibold", "Hora");
    input = el("input.form-control text-center sz_inp", {
      name: "hora",
      type: "time",
    });

    // Hora
    mount(col, label);
    mount(col, input);
    mount(row, col);
    // ---------

    col = el("div.col-xs-12");
    label = el("label.mb-1 fw-semibold", "Tarea que realiza");
    textarea = el("textarea.form-control sz_inp", {
      name: "tarea_realiza",
      cols: "10",
      rows: "2",
      placeholder: "Ingrese la tarea que realiza",
    });

    mount(col, label);
    mount(col, textarea);
    mount(row, col);

    col = el("div.col-md-3 col-xs-12 mt-2");

    // Div Col vacía
    mount(row, col);
    // ---------

    mount(cardBody, row);
    mount(encabezado, cardHeader);
    mount(encabezado, cardBody);

    VirtualSelect.init({
      ele: "#contratista",
      placeholder: "Seleccione la contratista",
    });
    document.getElementById("contratista").setValue(0);
    document
      .getElementById("contratista")
      .setAttribute("onchange", "changeResponsable(this)");
    // VirtualSelect.init({
    //   ele: "#supervisor_responsable",
    //   placeholder: "Seleccione el supervisor",
    // });
    // document.getElementById("supervisor_responsable").setValue(0);
  } else {
    col = el("div.col-md-3 col-xs-12 mt-2");
    div = el("div");
    label = el("label.mb-1 fw-semibold", "Supervisor");
    input = el("input.form-control sz_inp", {
      name: "supervisor_responsable",
      placeholder: "(Nombre + Apellido)",
    });
    // select = el("select.sz_inp", {
    //   name: "supervisor_responsable",
    //   id: "supervisor_responsable",
    //   style: "width: 100%;",
    //   "data-search": "true",
    //   "data-silent-initial-value-set": "true",
    // });

    // usuarios.forEach((gral) => {
    //   opt = el(
    //     "option",
    //     {
    //       value: gral.id,
    //     },
    //     gral.nombre + " " + gral.apellido
    //   );
    //   mount(select, opt);
    // });

    // Supervisor
    mount(div, label);
    mount(div, input);
    mount(col, div);
    mount(row, col);
    // ---------

    col = el("div.col-md-3 col-xs-12 mt-2");
    label = el("label.mb-1 fw-semibold", "Fecha de Carga");
    input = el("input.form-control text-center sz_inp simulate_dis", {
      name: "fecha_hoy",
      value: today,
      type: "date",
    });

    // Fecha de Carga
    mount(col, label);
    mount(col, input);
    mount(row, col);
    // ---------

    col = el("div.col-md-3 col-xs-12 mt-2");
    label = el("label.mb-1 fw-semibold", "Cantidad del Personal");
    input = el("input.form-control text-center sz_inp", {
      name: "cant_personal",
      value: today,
      type: "number",
      placeholder: "Ingrese la cantidad",
    });

    // Cantidad del Personal
    mount(col, label);
    mount(col, input);
    mount(row, col);
    // ---------

    col = el("div.col-md-3 col-xs-12 mt-2");
    div = el("div");
    label = el("label.mb-1 fw-semibold", "Proyectos / Área");
    select = el("select.sz_inp form-select", {
      name: "proyecto",
      id: "proyecto",
    });
    select.setAttribute("onchange", "filtrarModulos(this)");
    opt = el("option", "-- Seleccione --");
    mount(select, opt);

    proyectos.forEach((gral) => {
      opt = el(
        "option",
        {
          value: gral.id,
        },
        gral.nombre
      );
      mount(select, opt);
    });

    // Proyectos
    mount(div, label);
    mount(div, select);
    mount(col, div);
    mount(row, col);
    // ---------

    col = el("div.col-md-3 col-xs-12 mt-2");
    div = el("div#selector_modulos_div");
    label = el("label.mb-1 fw-semibold", "Módulos");
    select = el("select.sz_inp form-select", {
      name: "modulo",
      id: "modulo",
    });
    opt = el("option", "-- No Aplica --");

    // Módulos
    mount(select, opt);
    mount(div, select);
    mount(col, label);
    mount(col, div);
    mount(row, col);
    // ---------

    col = el("div.col-md-3 col-xs-12 mt-2");
    div = el("div#selector_estaciones_div");
    label = el("label.mb-1 fw-semibold", "Estación de Bombeo");
    select = el("select.sz_inp form-select", {
      name: "estacion",
      id: "estacion_bombeo",
    });
    select.setAttribute("onchange", "validacionEstaciones(this)");
    opt = el("option", "-- No Aplica --");
    mount(select, opt);

    estaciones.forEach((gral) => {
      opt = el(
        "option",
        {
          value: gral.id,
        },
        gral.nombre
      );
      mount(select, opt);
    });

    // Estación de Bombeo
    mount(div, select);
    mount(col, label);
    mount(col, div);
    mount(row, col);
    // ---------

    col = el("div.col-md-3 col-xs-12 mt-2");
    div = el("div#selector_sistemas_div");
    label = el("label.mb-1 fw-semibold", "Sistema de Oleoductos");
    select = el("select.sz_inp form-select", {
      name: "sistema",
      id: "sistema",
    });
    select.setAttribute("onchange", "validacionSistemas(this)");
    opt = el("option", "-- No Aplica --");
    mount(select, opt);

    sistemas.forEach((gral) => {
      opt = el(
        "option",
        {
          value: gral.id,
        },
        gral.nombre
      );
      mount(select, opt);
    });

    // Sistema de Oleoductos
    mount(div, select);
    mount(col, label);
    mount(col, div);
    mount(row, col);
    // ---------

    mount(cardBody, row);
    mount(encabezado, cardHeader);
    mount(encabezado, cardBody);

    VirtualSelect.init({
      ele: "#contratista",
      placeholder: "Seleccione la contratista",
    });
    // VirtualSelect.init({
    //   ele: "#supervisor_responsable",
    //   placeholder: "Seleccione el supervisor",
    // });
    document.getElementById("contratista").setValue(0);
    document
      .getElementById("contratista")
      .setAttribute("onchange", "changeResponsable(this)");
    // document.getElementById("supervisor_responsable").setValue(0);
  }

  return encabezado;
}

/**
 * Crea el cuerpo del checklist gracias al _generar_bloque_preguntas()
 * El mismo genera todas las preguntas con sus respectivos comentarios.
 */
function createBodyCheckList(id_aud) {
  fetch(GET_BASE_URL() + "/api/auditorias/getBloqueAud/" + id_aud)
    .then((response) => response.json())
    .then((data) => {
      let bodyChecklist = document.getElementById("body_checklist");
      let cardHeader = el(
        "div.card-header card_header_custom header-perfil text-center",
        "Sector de Preguntas"
      );
      let cardBody = el("div.card-body", {
        id: "bloque_container",
      });

      let bloque = _generar_bloque_preguntas(data);

      mount(bodyChecklist, cardHeader);
      mount(cardBody, bloque);

      let div, row, col, fieldset, rowtwo, content, dpx, pyx, textarea;

      if (data.bloque[0].tipo_aud == 2) {
        console.log("Entro aca?");
        div = el("div", { id: "ResultadoInspeccionVehicular" });
        row = el("div.row", { id: "datos_extras" });
        col = el("div.col-xs-12 col-md-6");

        fieldset = el("fieldset", el("legend", "Resultado de la Inspección"));

        rowtwo = el("div.row", { style: "padding: 35.5px 50px;" });
        content = el("div.content");
        dpx = el("div.dpx");
        pyx = el("div.py d-flex");

        label = el(
          "label.label_satisfactoria",
          { style: "margin-right: 20px;" },
          el("input.option-input radio", {
            type: "radio",
            name: "resultado_inspeccion",
            value: "1",
          }),
          "Satisfactoria"
        );

        mount(pyx, label);

        label = el(
          "label.label_satisfactoria",
          { style: "margin-right: 20px;" },
          el("input.option-input radio", {
            type: "radio",
            name: "resultado_inspeccion",
            value: "0",
          }),
          "No Satisfactoria"
        );

        mount(pyx, label);
        mount(dpx, pyx);
        mount(content, dpx);
        mount(rowtwo, content);
        mount(fieldset, rowtwo);

        mount(col, fieldset);
        mount(row, col);

        col = el("div.col-xs-12 col-md-6");
        fieldset = el(
          "fieldset",
          { style: "padding: 13px;" },
          el("legend", "Observaciones y Medidas a Implementar")
        );
        rowtwo = el("div.row", { style: "padding: 10px 50px;" });
        textarea = el("textarea.form-control sz_inp obs_medidas", {
          name: "medidas_implementar",
          cols: "30",
          rows: "5",
          style: "border: 1px solid #f1f1f1;",
          placeholder:
            "Ingrese las observaciones (Opcional) - Máximo 300 caracteres",
        });

        mount(fieldset, textarea);
        mount(rowtwo, fieldset);
        mount(col, rowtwo);

        mount(row, col);
        mount(div, row);
        mount(cardBody, div);
      }

      mount(bodyChecklist, cardBody);
    });
}

/**
 * Crea la Observación del Hallazgo (Misma lógica y script que la Tarjeta M.A.S)
 */
function createObsHallazgo() {
  let bodyObs = document.getElementById("body_obs");
  let cardHeader = el(
    "div.card-header card_header_custom header-perfil text-center",
    "Observación"
  );
  let cardBody = el("div.card-body", {
    id: "obs_hallazgo",
  });

  let section = el("section#contenedor_observaciones");
  let row = el("div.row mt-4", {
    id: "terminos_observacion",
  });
  let divTextCenter = el("div.col-xs-12 col-md-12 text-center");
  let p = el(
    "p.border_obs fw-semibold",
    "En este apartado puede abordar múltiples observaciones, ya sean sugerencias de optimización o reconocimientos positivos."
  );

  let flex = el("div.d-flex justify-content-center");
  let btnPositive = el(
    "button.btn_modify btn_tipo_obs",
    {
      id: "label_tipo_positivo",
      for: "tipo_positivo",
      style:
        "margin-right: 2px; width: 100%; max-width: 250px; background-color: #509d50;",
    },
    "Observación Positiva"
  );
  btnPositive.setAttribute(
    "onclick",
    'generarObservacionPositiva("contenedor_observaciones")'
  );

  let btnMejora = el(
    "button.btn_modify btn_tipo_obs",
    {
      id: "label_oportunidad_mejora",
      for: "oportunidad_mejora",
    },
    "Observación con Mejora"
  );
  btnMejora.setAttribute(
    "onclick",
    'generarObservacionMejora("contenedor_observaciones")'
  );

  mount(flex, btnPositive);
  mount(flex, btnMejora);
  mount(divTextCenter, p);
  mount(divTextCenter, flex);
  mount(row, divTextCenter);

  mount(cardBody, section);
  mount(cardBody, row);
  mount(bodyObs, cardHeader);
  mount(bodyObs, cardBody);

  let inpSubmit = el(
    "div.d-flex justify-content-end mt-3 mb-3",
    el("button.btn_modify", { id: "btnSendInspection" }, "Enviar Inspección")
  );
  inpSubmit.setAttribute("onclick", "submitInspection(this)");
  let br = el("br");
  mount(document.getElementById("checklist"), inpSubmit);
  //   mount(document.getElementById("form_checklist"), br);
  // let iconPositive = el('i.fa-solid fa-plus');
}

function _generar_bloque_preguntas(datos) {
  const text_aud_title = document.getElementById("textTitle");

  let bloque_question_container = el("div#bloque_question_container");

  text_aud_title.textContent = datos["bloque"][0].nombre;
  bloque_question_container.innerHTML = "";
  for (let i = 0; i < datos["bloque"]["bloque_preguntas"].length; i++) {
    // -- Subtitulo Auditoría
    let divRowContainer = el("div.row container mb-4");
    let divSubtitleAud = el("div.div-subtitle_aud");
    let h6 = el(
      "h6.subtitle_aud",
      datos["bloque"]["bloque_preguntas"][i].nombre
    );
    mount(divSubtitleAud, h6);
    mount(divRowContainer, divSubtitleAud);
    // ----------

    let divRow = el("div.row");
    let bloque_preguntas = datos["bloque"]["bloque_preguntas"][i]["preguntas"];

    for (let j = 0; j < bloque_preguntas.length; j++) {
      // -- Preguntas
      let divColFlex = el(
        "div.d-flex justify-content-around align-items-center pb-2 pt-2 flex-container_questions"
      );

      let divColPregunta = el("div.col-xs-12 col-md-7");
      let divGral = el("div");

      let pregunta = el(
        "p.m-0",
        el("small", el("b", "( " + (j + 1) + " ) ")),
        bloque_preguntas[j].pregunta
      );
      mount(divGral, pregunta);
      // ----------

      // -- Botones Toggle
      let btnsToggle = _generar_btns_toggle(
        datos["bloque"]["bloque_preguntas"][i].id,
        bloque_preguntas[j].id,
        bloque_preguntas[j].pregunta
      );
      mount(divColPregunta, divGral);
      mount(divColFlex, divColPregunta);
      mount(divColFlex, btnsToggle);
      // ----------

      // -- Comentario BTN
      let divCommentCol = el("div.col-xs-12 col-md-2");
      let divComment = el("div.d-flex justify-content-end");
      let pComment = el(
        "p.m-0 p-1 btn-comment",
        {
          "data-bs-toggle": "collapse",
          "data-bs-target": "#comment_mejora_" + bloque_preguntas[j].id,
        },
        el("i.fa-solid fa-comment"),
        " Comentario"
      );

      mount(divComment, pComment);
      mount(divCommentCol, divComment);
      mount(divColFlex, divCommentCol);
      // ----------

      // -- Comentario Collapse (Textarea)
      let divCommentTextArea = el("div.row collapse", {
        id: "comment_mejora_" + bloque_preguntas[j].id,
      });
      let divFloating = el("div.form-floating mt-2 mb-2");
      let txtArea = el("textarea.form-control sz_inp comment_accion", {
        placeholder: "Leave a comment here",
        id: "comentario_accion_tomar_" + bloque_preguntas[j].id,
        name: "comentarios_preguntas[" + bloque_preguntas[j].id + "]",
      });
      let labelTxtArea = el(
        "label.mb-1 fw-semibold sz_inp",
        {
          for: "comentario_accion_tomar_" + bloque_preguntas[j].id,
          style: "padding-left: 55px; width: 80%; margin: 0 auto 0 100px;",
        },
        "Comentario / Acciones a tomar:"
      );

      mount(divRow, divColFlex);
      mount(divFloating, txtArea);
      mount(divFloating, labelTxtArea);
      mount(divCommentTextArea, divFloating);
      mount(divRow, divCommentTextArea);
      // ----------
    }

    mount(divRowContainer, divRow);
    /* Último mount */
    mount(bloque_question_container, divRowContainer);
  }
  return bloque_question_container;
}

/**
 * Genera los botones toggle 'Bien | Mal | No Aplica'
 */
function _generar_btns_toggle(aux, id_pregunta, pregunta) {
  let input, input_pregunta, label;
  let div = el("div.col-xs-12 col-md-3 text-center");
  let divToggle = el("div.btn-group btn-group-toggle", {
    role: "group",
    "aria-label": "Basic radio toggle button group",
  });
  input = el("input.btn-check verde_check", {
    type: "radio",
    name:
      "bloque_respuestas[" + aux + "][respuestas][" + id_pregunta + "][rta]",
    value: "1",
    id: "btn_bien_" + id_pregunta,
    autocomplete: "off",
  });
  input_pregunta = el("input", {
    type: "hidden",
    name:
      "bloque_respuestas[" +
      aux +
      "][respuestas][" +
      id_pregunta +
      "][pregunta]",
    value: pregunta,
  });
  label = el(
    "label.btn verde btnsToggle",
    {
      for: "btn_bien_" + id_pregunta,
    },
    "Bien"
  );

  mount(divToggle, input);
  mount(divToggle, label);
  mount(divToggle, input_pregunta);

  input = el("input.btn-check rojo_check", {
    type: "radio",
    name:
      "bloque_respuestas[" + aux + "][respuestas][" + id_pregunta + "][rta]",
    value: "0",
    id: "btn_mal_" + id_pregunta,
    autocomplete: "off",
  });
  input_pregunta = el("input", {
    type: "hidden",
    name:
      "bloque_respuestas[" +
      aux +
      "][respuestas][" +
      id_pregunta +
      "][pregunta]",
    value: pregunta,
  });
  label = el(
    "label.btn rojo btnsToggle",
    {
      for: "btn_mal_" + id_pregunta,
    },
    "Mal"
  );

  mount(divToggle, input);
  mount(divToggle, label);
  mount(divToggle, input_pregunta);

  input = el("input.btn-check amarillo_checked", {
    type: "radio",
    name:
      "bloque_respuestas[" + aux + "][respuestas][" + id_pregunta + "][rta]",
    value: "-1",
    id: "btn_na_" + id_pregunta,
    autocomplete: "off",
  });
  input.setAttribute("checked", true);
  label = el(
    "label.btn amarillo btnsToggle",
    {
      for: "btn_na_" + id_pregunta,
    },
    "N/A"
  );
  input_pregunta = el("input", {
    type: "hidden",
    name:
      "bloque_respuestas[" +
      aux +
      "][respuestas][" +
      id_pregunta +
      "][pregunta]",
    value: pregunta,
  });

  mount(divToggle, input);
  mount(divToggle, label);
  mount(divToggle, input_pregunta);

  mount(div, divToggle);

  return div;
}

/**
 * Cambia el responsable cuando escucha un cambio del selector de la contratista
 * Obtiene el ID de la contratista y filtra por los usuarios a ver que usuario
 * pertenece a la contratista y los coloca como responsables.
 */
function changeResponsable(contratista) {
  let id_contratista = contratista.value;
  responsables_contratista = responsable.filter(
    (e) => e.empresa == id_contratista
  );

  let selectorResponsables = document.querySelectorAll(".responsable");
  let selectorResponsablesRelevo = document.querySelectorAll(
    ".relevo_responsable"
  );

  // * Selector de responsables (Se resetea cuando escucha un cambio en el selector de contratista)
  selectorResponsables.forEach((e) => {
    let options = [];
    responsables_contratista.forEach((r) => {
      let objeto = {
        label: r.nombre + " " + r.apellido,
        value: r.id,
      };
      options.push(objeto);
    });
    document.querySelector("#" + e.getAttribute("id")).setOptions(options);
  });

  // * Selector de relevo de responsables (Se resetea cuando escucha un cambio en el selector de contratista)
  selectorResponsablesRelevo.forEach((e) => {
    let options = [];
    responsables_contratista.forEach((r) => {
      let objeto = {
        label: r.nombre + " " + r.apellido,
        value: r.id,
      };
      options.push(objeto);
    });
    document.querySelector("#" + e.getAttribute("id")).setOptions(options);
  });
}