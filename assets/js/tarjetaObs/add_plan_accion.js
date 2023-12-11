// Variables
let divColDoceSeis,
  divColDoceCuatro,
  divColDoce,
  divPad,
  labelObs,
  textArea,
  select,
  input;

/**
 * Genera una observación de mejora
 * @param {*} id_contenedor
 */
function generarObservacionMejora(id_contenedor) {
  event.preventDefault();
  let div = el("div.row mt-3 p-3", { id: "aspecto_observado" });
  let fieldset = el("fieldset.fieldset_mejora fieldset_add_hallazgo", {
    style: "border-right: none;",
  });
  let legend = el(
    "legend.w-100 legend_mejora legend_add all_legends d-flex justify-content-between align-items-center", el('span.number', `Observación con Mejora #`, el("span", aux)));
  let btn_delete = el("button.btn_del_hallazgo", el("i.fa-solid fa-trash"));
  mount(legend, btn_delete);

  let br = el("br");

  // * Select 'Aspectos Observados'
  let divCol12 = el("div.col-xs-12 text-center");
  let pAspecto = el("p.fw-semibold mt-3", "Seleccione el aspecto observado");
  let divFlex = el("div.d-flex justify-content-center");
  let labelSeguridad = el(
    "label#label_riesgo_seguridad",
    {
      for: `seguridad_salud_${contador}`,
      class: "label_aspecto",
    },
    "Riesgo en Seguridad y Salud"
  );
  let inputSeguridad = el(`input#seguridad_salud_${contador}`, {
    type: "radio",
    class: "seguridad_input",
    name: `hallazgos_mejoras[${contador}][aspecto_observado]`,
    value: "1",
    checked: "true",
    hidden: "true",
  });
  let labelAmbiental = el(
    "label#label_impacto_ambiental",
    {
      for: `impacto_ambiental_${contador}`,
      class: "label_aspecto",
    },
    "Impacto Ambiental"
  );
  let inputAmbiental = el(`input#impacto_ambiental_${contador}`, {
    type: "radio",
    class: "ambiental_input",
    name: `hallazgos_mejoras[${contador}][aspecto_observado]`,
    value: "2",
    hidden: "true",
  });

  // * Section (Dentro va incluido los Efectos y la Significancia)
  let sectionEfectosSignificancia = el(`section#container_aspecto_${contador}`);
  mount(sectionEfectosSignificancia, getEfectosAndSignificancia(1, contador));

  // * Efectos
  labelSeguridad.setAttribute(
    "onclick",
    "getEfectosAndSignificancia(1," +
      contador +
      ",'container_aspecto_" +
      contador +
      "')"
  );
  labelAmbiental.setAttribute(
    "onclick",
    "getEfectosAndSignificancia(2," +
      contador +
      ",'container_aspecto_" +
      contador +
      "')"
  );

  // * Observación General
  let divObservacion = el("div.row", { id: "div-plan_accion" });

  // * Tipo de Observación (2: Observación con Mejora)
  input = el("input", {
    value: 2,
    name: `hallazgos_mejoras[${contador}][tipo]`,
    hidden: "true",
  });
  mount(divObservacion, input);
  // * ---

  // * Hallazgo Observado
  divColDoceSeis = el("div.col-xs-12 col-md-6");
  divPad = el("div.p-3 pt-1");
  labelObs = el("label.mb-2 sz_inp fw-semibold", "Hallazgo Observado");
  textArea = el("textarea.form-control sz_inp inp_custom", {
    style: "border: 1px solid #f1f1f1;",
    name: `hallazgos_mejoras[${contador}][hallazgo]`,
    cols: 30,
    rows: 5,
    placeholder: "Ingrese el hallazgo observado",
  });
  mount(divPad, labelObs);
  mount(divPad, textArea);
  mount(divColDoceSeis, divPad);
  mount(divObservacion, divColDoceSeis);
  // * ---

  // * Plan de Acción
  divColDoceSeis = el("div.col-xs-12 col-md-6");
  divPad = el("div.p-3 pt-1");
  labelObs = el("label.mb-2 sz_inp fw-semibold", "Plan de Acción");
  textArea = el("textarea.form-control sz_inp inp_custom", {
    style: "border: 1px solid #f1f1f1;",
    name: `hallazgos_mejoras[${contador}][plan_accion]`,
    cols: 30,
    rows: 5,
    placeholder: "Ingrese el plan de acción",
  });
  mount(divPad, labelObs);
  mount(divPad, textArea);
  mount(divColDoceSeis, divPad);
  mount(divObservacion, divColDoceSeis);
  // * ---

  // *  Responsable de dar tratamiento
  divColDoceCuatro = el("div.col-xs-12 col-md-4");
  divPad = el("div.p-3 pt-1");
  labelObs = el(
    "label.mb-2 sz_inp fw-semibold",
    "Responsable de dar tratamiento"
  );
  select = el("select.sz_inp", {
    name: `hallazgos_mejoras[${contador}][responsable]`,
    id: `responsable_${contador}`,
    style: "width: 100%;",
    "data-serach": "true",
    "data-silent-initial-value-set": "true",
  });
  responsables_contratista.forEach((responsable) => {
    let option = el(
      "option",
      { value: responsable.id },
      responsable.nombre + ' ' + responsable.apellido
    );
    mount(select, option);
  });
  mount(divPad, labelObs);
  mount(divPad, select);
  mount(divColDoceCuatro, divPad);
  mount(divObservacion, divColDoceCuatro);

  // * ---

  // *  Segundo Responsable
  divColDoceCuatro = el("div.col-xs-12 col-md-4");
  divPad = el("div.p-3 pt-1");
  labelObs = el(
    "label.mb-2 sz_inp fw-semibold",
    "Segundo Responsable ",
    el("small", el("em", "(Opcional)"))
  );
  select = el("select.sz_inp", {
    name: `hallazgos_mejoras[${contador}][relevo_responsable]`,
    id: `relevo_responsable_${contador}`,
    style: "width: 100%;",
    "data-serach": "true",
    "data-silent-initial-value-set": "true",
  });
  responsables_contratista.forEach((responsable) => {
    let option = el(
      "option",
      { value: responsable.id },
      responsable.nombre + ' ' + responsable.apellido
    );
    mount(select, option);
  });
  mount(divPad, labelObs);
  mount(divPad, select);
  mount(divColDoceCuatro, divPad);
  mount(divObservacion, divColDoceCuatro);
  // * ---

  // *  Fecha de Cierre
  divColDoceCuatro = el("div.col-xs-12 col-md-4");
  divPad = el("div.p-3 pt-1");
  labelObs = el("label.mb-2 sz_inp fw-semibold", "Fecha de Cierre");
  const fechaActual = new Date().toISOString().split("T")[0];
  input = el("input.form-control sz_inp inp_custom", {
    type: "date",
    name: `hallazgos_mejoras[${contador}][fecha_cierre]`,
    id: `fecha_cierre_${contador}`,
    min: fechaActual,
  });
  mount(divPad, labelObs);
  mount(divPad, input);
  mount(divColDoceCuatro, divPad);
  mount(divObservacion, divColDoceCuatro);
  // * ---

  // *  Adjuntos
  divColDoce = el("div.col-xs-12 mt-2");
  divPad = el("div.p-3 pt-1");
  labelObs = el("label.sz_inp fw-semibold", "Adjuntos");
  let divGallery = el(`div#gallery_${contador}`, {
    class: "adj_gallery",
    style: "margin-left: 10px;",
  });
  mount(divPad, labelObs);
  mount(divPad, divGallery);
  mount(divColDoce, divPad);
  mount(divObservacion, divColDoce);
  // * ---

  let contenedor = document.getElementById(id_contenedor);

  mount(divFlex, inputSeguridad);
  mount(divFlex, labelSeguridad);
  mount(divFlex, inputAmbiental);
  mount(divFlex, labelAmbiental);

  mount(divCol12, pAspecto);
  mount(divCol12, divFlex);

  mount(fieldset, legend);
  mount(fieldset, br);
  mount(fieldset, divCol12);
  mount(fieldset, sectionEfectosSignificancia);
  mount(fieldset, divObservacion);
  mount(div, fieldset);
  mount(contenedor, div);

  btn_delete.addEventListener("click", (e) => {
    e.preventDefault();
    div.remove();

    aux = 1
    let all_legends = document.querySelectorAll('.all_legends .number SPAN');
    all_legends.forEach(l => {
      l.textContent = aux;
      aux++;
    });

  });

  new addFiles(
    document.getElementById(`gallery_${contador}`),
    `adjuntos_hallazgos_${contador}`
  ).init();

  document
    .getElementById(`riesgo_seguridad_salud_${contador}`)
    .classList.add("selectMultiple");
  document
    .getElementById(`responsable_${contador}`)
    .classList.add("selectMultiple");
  document
    .getElementById(`relevo_responsable_${contador}`)
    .classList.add("selectMultiple");

  VirtualSelect.init({
    ele: `#riesgo_seguridad_salud_${contador}`,
    placeholder: "Seleccione uno o mas hallazgos en seguridad",
  });
  VirtualSelect.init({
    ele: `#responsable_${contador}`,
    placeholder: "Seleccione el Responsable",
  });
  VirtualSelect.init({
    ele: `#relevo_responsable_${contador}`,
    placeholder: "Seleccione otro Responsable (Opcional)",
  });

  document.getElementById(`relevo_responsable_${contador}`).setValue(0);
  document.getElementById(`responsable_${contador}`).setValue(0);

  document
    .getElementById(`responsable_${contador}`)
    .classList.add("responsable");
  document
    .getElementById(`relevo_responsable_${contador}`)
    .classList.add("relevo_responsable");

  contador++;
  aux++;
}

/**
 * Crea los efectos y el riesgo de la significancia cuando
 * escucha por el selector del aspecto observado
 */
function getEfectosAndSignificancia(
  id_consecuencia,
  contador,
  id_container = ""
) {
  let divEfectoEsignificancia = el("div.row mt-3");
  let divColEfecto = el("div.col-xs-12 col-md-6");

  let divEfectoImpacto = el("div.p-3 pt-1");

  // * Significancia
  let divColSignificancia = el("div.col-xs-12 col-md-6");
  let labelRiesgoObservado = el(
    "label.mb-2 sz_inp fw-semibold",
    "Consecuencia Potencial"
  );

  let divSignificancia = el("div.text-center");
  let divGroupSignificancia = el("div.btn-group btn-group-toggle", {
    style: "width: 80%;, margin-top: 3px;",
    role: "group",
  });

  if (id_consecuencia == 1) {
    let labelEfecto = el(
      "label.mb-2 sz_inp fw-semibold",
      { for: "efecto_impacto" },
      "Seleccione el hallazgo en seguridad y salud observado"
    );
    let selectEfecto = el("select.sz_inp rounded-select selectMultiple", {
      id: `riesgo_seguridad_salud_${contador}`,
      name: `hallazgos_mejoras[${contador}][efectos][]`,
      style: "width: 100%;",
      multiple: "true",
      "data-search": "true",
      "data-silent-initial-value-set": "true",
    });

    let riesgos = efectos.filter((e) => e.consecuencia_id == 1);

    riesgos.forEach((e) => {
      let option = el("option", { value: e.id }, e.nombre);
      mount(selectEfecto, option);
    });

    let significancia_filter = significancia.filter(
      (e) => e.consecuencia_id == 1
    );

    significancia_filter.forEach((e) => {
      let inputSignificancia = el(
        `input.btn-check btn_check_significancia ${e.clase_input}`,
        {
          id: e.nombre.toLowerCase() + "_" + contador,
          type: "radio",
          value: e.id,
          name: `hallazgos_mejoras[${contador}][significancia]`,
        }
      );
      let labelSignificancia = el(
        `label.btn btnsToggle riesgos ${e.clase_label}`,
        { for: e.nombre.toLowerCase() + "_" + contador },
        e.nombre
      );
      mount(divGroupSignificancia, inputSignificancia);
      mount(divGroupSignificancia, labelSignificancia);
    });

    mount(divSignificancia, divGroupSignificancia);
    mount(divColSignificancia, labelRiesgoObservado);
    mount(divColSignificancia, divSignificancia);
    mount(divEfectoImpacto, labelEfecto);
    mount(divEfectoImpacto, selectEfecto);
    mount(divColEfecto, divEfectoImpacto);
    mount(divEfectoEsignificancia, divColEfecto);
    mount(divEfectoEsignificancia, divColSignificancia);

    if (id_container != "") {
      let section = document.getElementById(id_container);
      setChildren(section, divEfectoEsignificancia);
      document
        .getElementById(`riesgo_seguridad_salud_${contador}`)
        .classList.add("selectMultiple");

      VirtualSelect.init({
        ele: `#riesgo_seguridad_salud_${contador}`,
        placeholder: "Seleccione uno o mas hallazgos en seguridad",
      });
    } else {
      return divEfectoEsignificancia;
    }
  } else {
    let labelEfecto = el(
      "label.mb-2 sz_inp fw-semibold",
      { for: "efecto_impacto" },
      "Seleccione el hallazgo ambiental observado"
    );

    let selectEfecto = el("select.sz_inp rounded-select", {
      id: `riesgo_ambiental_${contador}`,
      name: `hallazgos_mejoras[${contador}][efectos][]`,
      style: "width: 100%;",
      multiple: "true",
      "data-search": "true",
      "data-silent-initial-value-set": "true",
    });

    let riesgos = efectos.filter((e) => e.consecuencia_id == 2);

    riesgos.forEach((e) => {
      let option = el("option", { value: e.id }, e.nombre);
      mount(selectEfecto, option);
    });

    let significancia_filter = significancia.filter(
      (e) => e.consecuencia_id == 2
    );

    significancia_filter.forEach((e) => {
      let inputSignificancia = el(
        `input.btn-check btn_check_significancia ${e.clase_input}`,
        {
          id: e.nombre + '_' + contador,
          type: "radio",
          value: e.id,
          name: `hallazgos_mejoras[${contador}][significancia]`,
        }
      );
      let labelSignificancia = el(
        `label.btn btnsToggle riesgos ${e.clase_label}`,
        { for: e.nombre + '_' + contador },
        e.nombre
      );
      mount(divGroupSignificancia, inputSignificancia);
      mount(divGroupSignificancia, labelSignificancia);
    });
    mount(divSignificancia, divGroupSignificancia);
    mount(divColSignificancia, labelRiesgoObservado);
    mount(divColSignificancia, divSignificancia);
    mount(divEfectoImpacto, labelEfecto);
    mount(divEfectoImpacto, selectEfecto);
    mount(divColEfecto, divEfectoImpacto);
    mount(divEfectoEsignificancia, divColEfecto);
    mount(divEfectoEsignificancia, divColSignificancia);

    if (id_container != "") {
      let section = document.getElementById(id_container);
      setChildren(section, divEfectoEsignificancia);
      document
        .getElementById(`riesgo_ambiental_${contador}`)
        .classList.add("selectMultiple");
      VirtualSelect.init({
        ele: `#riesgo_ambiental_${contador}`,
        placeholder: "Seleccione el hallazgo ambiental",
      });
    } else {
      return divEfectoEsignificancia;
    }
  }
}