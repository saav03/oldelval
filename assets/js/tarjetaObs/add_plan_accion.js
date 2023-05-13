function generarPlanAccion(section, container) {
  /* == Variables creadas por defecto == */
  let contenedor, div, divCaja, divContainer, option, inputFecha; // (Las que contienen todo)

  let divGenerico;
  let textAreaGenerico;
  let selectGenerico;
  let inputGenerico;
  let labelGenerico;
  // ======================================================================================================

  let sectionPadre = document.getElementById(section);
  contenedor = el("div#" + container);
  div = el("div.row");
  divCaja = el("div.col-xs-12", {
    id: "caja_plan_accion"
  });
  divContainer = el("div.row p-3", {
    id: "contenedor_plan"
  });
  option = el("option", {
    value: ""
  }, "-- Seleccione --");

  /* == TEXTAREA HALLAZGO == */
  divGenerico = el("div.col-xs-12.col-md-6");
  labelGenerico = el(
    "label.mb-1 fw-semibold sz_inp", {},
    "Hallazgo ",
    el("small", "(*)")
  );
  textAreaGenerico = el("textarea.form-control sz_inp", {
    name: "hallazgo",
    id: "hallazgo",
    cols: "30",
    rows: "5",
    placeholder: "Ingrese el hallazgo que observó",
    required: 'true'
  });

  mount(divGenerico, labelGenerico);
  mount(divGenerico, textAreaGenerico);

  mount(divContainer, divGenerico);

  /* == TEXTAREA PLAN DE ACCION == */
  divGenerico = el("div.col-xs-12.col-md-6");
  labelGenerico = el(
    "label.mb-1 fw-semibold sz_inp", {},
    "Plan de Acción ",
    el("small", "(*)")
  );
  textAreaGenerico = el("textarea.form-control sz_inp", {
    name: "accion_recomendacion",
    id: "accion_recomendacion",
    cols: "30",
    rows: "5",
    placeholder: "Ingrese el plan de acción a recomendar",
    required: 'true'

  });

  mount(divGenerico, labelGenerico);
  mount(divGenerico, textAreaGenerico);

  mount(divContainer, divGenerico);

  /* == SELECT CLASIFICACION DEL HALLAZGO == */
  divGenerico = el("div.col-xs-12 col-md-4 mt-3");
  labelGenerico = el(
    "label.mb-1 fw-semibold sz_inp", {},
    "Clasificación de Hallazgo ",
    el("small", "(*)")
  );
  selectGenerico = el("select.form-select sz_inp", {
    name: "clasificacion",
    id: "clasificacion",
    required: 'true'
  });

  mount(selectGenerico, option);

  clasificaciones.forEach(c => {
    let optionC = el('option', {
      value: c.id
    }, c.nombre);
    mount(selectGenerico, optionC);
  });

  mount(divGenerico, labelGenerico);
  mount(divGenerico, selectGenerico);

  mount(divContainer, divGenerico);

  /* == SELECT TIPO DEL HALLAZGO == */
  divGenerico = el("div.col-xs-12 col-md-4 mt-3");
  labelGenerico = el(
    "label.mb-1 fw-semibold sz_inp", {},
    "Tipo de hallazgo ",
    el("small", "(*)")
  );
  selectGenerico = el("select.form-select sz_inp", {
    name: "tipo",
    id: "tipo",
    required: 'true'
  });

  option = el("option", {
    value: ""
  }, "-- Seleccione --");
  mount(selectGenerico, option);

  tipo_hallazgo.forEach(t => {
    let optionC = el('option', {
      value: t.id
    }, t.nombre);
    mount(selectGenerico, optionC);
  });

  mount(divGenerico, labelGenerico);
  mount(divGenerico, selectGenerico);

  mount(divContainer, divGenerico);

  /* == SIGNIFICANCIA RIESGO == */
  /* == LO COMENTO POR AHORA | VICTOR ME DIJO QUE AGREGUE MUY BAJO, BAJO, MEDIO, ALTO == */
  // divGenerico = el("div.col-xs-12 col-md-4 mt-3");
  // let divRow = el("div.row");
  // let divCol6 = el("div.col-xs-12 col-md-6");

  // labelGenerico = el(
  //   "label.mb-1 fw-semibold sz_inp", {},
  //   "Significancia ",
  //   el("small", "(*)")
  // );
  // inputGenerico = el("input.form-control sz_inp", {
  //   name: "riesgo",
  //   id: "riesgo",
  //   readonly: "true",
  //   placeholder: "Calcule el riesgo",
  //   // required: 'true'
  // });

  // let inputFilaRiesgo = el("input.form-control sz_inp", {
  //   type: 'hidden',
  //   name: "riesgo_fila",
  //   id: "riesgo_fila",
  // });

  // mount(divCol6, labelGenerico);
  // mount(divCol6, inputGenerico);
  // mount(divCol6, inputFilaRiesgo);

  // mount(divRow, divCol6);
  // let btnRiesgo = el("button.btn_riesgo", {
  //   'data-bs-target': '#matrizRiesgoModal',
  //   'data-bs-toggle': "modal"
  // }, el('i.fas fa-exclamation-triangle'), " Calcular Riesgo");
  // btnRiesgo.setAttribute('onclick', 'prevenirDefault(event)');
  // divCol6 = el("div.col-xs-12 col-md-6");
  // mount(divCol6, btnRiesgo);
  // mount(divRow, divCol6);

  // mount(divGenerico, divRow);
  // mount(divContainer, divGenerico);

  divGenerico = el("div.col-xs-12 col-md-4 mt-3");
  labelGenerico = el(
    "label.mb-1 fw-semibold sz_inp", {},
    "Significancia ",
    el("small", "(*)")
  );
  selectGenerico = el("select.form-select sz_inp", {
    name: "riesgo",
    id: "riesgo",
    required: 'true'
  });

  option = el("option", {
    value: ""
  }, "-- Seleccione --");
  mount(selectGenerico, option);

  let riesgos = [
    {
      id: '1',
      valor: 'Muy Bajo',
    },
    {
      id: '2',
      valor: 'Bajo',
    },
    {
      id: '3',
      valor: 'Medio',
    },
    {
      id: '4',
      valor: 'Alto',
    }
  ];
  riesgos.forEach(r => {
    let optionC = el('option', {
      value: r.id
    }, r.valor);
    mount(selectGenerico, optionC);
  });

  mount(divGenerico, labelGenerico);
  mount(divGenerico, selectGenerico);

  mount(divContainer, divGenerico);

  /* == SELECT CONTRATISTA == */
  divGenerico = el("div.col-xs-12 col-md-3 mt-3");
  labelGenerico = el(
    "label.mb-1 fw-semibold sz_inp", {},
    "Contratista ",
    el("small", "(*)")
  );
  selectGenerico = el("select.form-select sz_inp", {
    name: "contratista",
    id: "contratista",
    required: 'true'
  });

  option = el("option", {
    value: ""
  }, "-- Seleccione --");
  mount(selectGenerico, option);

  contratista.forEach(c => {
    let optionC = el('option', {
      value: c.id
    }, c.nombre);
    mount(selectGenerico, optionC);
  });

  mount(divGenerico, labelGenerico);
  mount(divGenerico, selectGenerico);

  mount(divContainer, divGenerico);

  /* == SELECT RESPONSABLE == */
  divGenerico = el("div.col-xs-12 col-md-3 mt-3");
  labelGenerico = el(
    "label.mb-1 fw-semibold sz_inp", {},
    "Responsable de dar tratamiento ",
    el("small", "(*)")
  );
  selectGenerico = el("select.form-select sz_inp", {
    name: "responsable",
    id: "responsable",
    required: 'true'
  });

  option = el("option", {
    value: ""
  }, "-- Seleccione --");
  mount(selectGenerico, option);

  responsable.forEach(r => {
    let optionC = el('option', {
      value: r.id
    }, r.nombre + ' ' + r.apellido);
    mount(selectGenerico, optionC);
  });

  mount(divGenerico, labelGenerico);
  mount(divGenerico, selectGenerico);

  mount(divContainer, divGenerico);

  /* == SELECT SEGUNDO RESPONSABLE == */
  divGenerico = el("div.col-xs-12 col-md-3 mt-3");
  labelGenerico = el(
    "label.mb-1 fw-semibold sz_inp", {},
    "Relevo de Responsable ",
    el("small", "(Si corresponde)")
  );
  selectGenerico = el("select.form-select sz_inp", {
    name: "otro_responsable",
    id: "otro_responsable",
    required: 'true'
  });

  option = el("option", {
    value: ""
  }, "-- Seleccione --");
  mount(selectGenerico, option);

  responsable.forEach(r => {
    let optionC = el('option', {
      value: r.id
    }, r.nombre + ' ' + r.apellido);
    mount(selectGenerico, optionC);
  });

  mount(divGenerico, labelGenerico);
  mount(divGenerico, selectGenerico);

  mount(divContainer, divGenerico);

  /* == FECHA CIERRE == */
  divGenerico = el("div.col-xs-12 col-md-2 mt-3");
  labelGenerico = el(
    "label.mb-1 fw-semibold sz_inp", {},
    "Fecha de Cierre ",
    el("small", "(*)")
  );
  inputFecha = el("input.form-control sz_inp", {
    type: "date",
    name: "fecha_cierre",
    id: "fecha_cierre",
    required: 'true'
  });
  mount(divGenerico, labelGenerico);
  mount(divGenerico, inputFecha);
  mount(divContainer, divGenerico);

  /* == ADJUNTOS == */
  divGenerico = el("div.col-xs-12 mt-3");
  labelGenerico = el(
    "label.mb-1 fw-semibold sz_inp", {},
    "Adjuntos"
  );
  let parrafo = el('p', 'Formatos permitidos: PNG JPG JPEG PDF - Maximo 3 archivos por carga');
  let contenedorAdj = el('div#contenedorAdj');

  mount(divGenerico, labelGenerico);

  mount(divGenerico, contenedorAdj);
  mount(divContainer, divGenerico);

  divGenerico = el("div.col-xs-12");
  let divGallery = el('div#gallery');

  mount(divGenerico, divGallery);
  mount(divContainer, divGenerico);

  let cantidad = 0;
  new fileDropAdder(contenedorAdj, 'prueba', 'gallery').init();

  /*   let icono = el('i.fas fa-upload');
    let h5 = el('h5', 'Subir Archivo');
    let pArrastre = el('p', {style: 'font-size: 14px;'}, 'Arrastre y suelte los archivos aquí');
    inputGenerico = el('input#fileElem', {type: 'file', multiple: 'true'});
    inputGenerico.setAttribute('onchange', 'handleFiles(this.files)'); */

  /*   mount(divDropArea, icono);
    mount(divDropArea, h5);
    mount(divDropArea, pArrastre);
    mount(divDropArea, inputGenerico); */

  // mount(divGenerico, labelGenerico);
  // mount(divGenerico, parrafo);

  mount(divCaja, divContainer);
  mount(div, divCaja);
  mount(contenedor, divCaja);
  setChildren(sectionPadre, contenedor);
}