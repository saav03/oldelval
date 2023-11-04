// function generarObsPositiva(section, container) {
//   /* == Variables creadas por defecto == */
//   let contenedor, div, divCaja, divContainer, option, inputFecha; // (Las que contienen todo)

//   let divGenerico;
//   let textAreaGenerico;
//   let selectGenerico;
//   let inputGenerico;
//   let labelGenerico;

//   // ======================================================================================================

//   let sectionPadre = document.getElementById(section);
//   contenedor = el("div#" + container);
//   div = el("div.row");
//   divCaja = el("div.col-xs-12", { id: "caja_plan_accion_positiva" });
//   divContainer = el("div.row p-3", { id: "contenedor_plan_positivo" });
//   option = el("option", { value: "" }, "-- Seleccione --");

//   /* == DESCRIPCION DEL HALLAZGO OBSERVADO == */
//   divGenerico = el("div.col-xs-12");
//   labelGenerico = el(
//     "label.mb-1 fw-semibold sz_inp",
//     {},
//     "Descripción de lo Observado ",
//     el("small", "(*)")
//   );
//   textAreaGenerico = el("textarea.form-control sz_inp", {
//     name: "desc_positivo",
//     id: "desc_positivo",
//     cols: "30",
//     rows: "5",
//     placeholder: "Ingrese el hallazgo que observó",
//     required: 'true'
//   });

//   mount(divGenerico, labelGenerico);
//   mount(divGenerico, textAreaGenerico);

//   mount(divContainer, divGenerico);

//   /* == SELECT CLASIFICACION DEL HALLAZGO == */
//   divGenerico = el("div.col-xs-12 col-md-6 mt-3");
//   labelGenerico = el(
//     "label.mb-1 fw-semibold sz_inp",
//     {},
//     "Clasificación de Hallazgo ",
//     el("small", "(*)")
//   );
//   selectGenerico = el("select.form-control sz_inp", {
//     name: "clasificacion",
//     id: "clasificacion",
//     required: 'true'
//   });

//   mount(selectGenerico, option);

//   clasificaciones.forEach(c => {
//     let optionC = el('option', {value: c.id}, c.nombre);
//     mount(selectGenerico, optionC); 
//   });

//   mount(divGenerico, labelGenerico);
//   mount(divGenerico, selectGenerico);

//   mount(divContainer, divGenerico);

//   /* == SELECT CONTRATISTA == */
//   divGenerico = el("div.col-xs-12 col-md-6 mt-3");
//   labelGenerico = el(
//     "label.mb-1 fw-semibold sz_inp",
//     {},
//     "Contratista ",
//     el("small", "(*)")
//   );
//   selectGenerico = el("select.form-control sz_inp", {
//     name: "contratista",
//     id: "contratista",
//     required: 'true'
//   });

//   option = el("option", { value: "" }, "-- Seleccione --");
//   mount(selectGenerico, option);

//   contratista.forEach(c => {
//     let optionC = el('option', {value: c.id}, c.nombre);
//     mount(selectGenerico, optionC); 
//   });

//   mount(divGenerico, labelGenerico);
//   mount(divGenerico, selectGenerico);

//   mount(divContainer, divGenerico);

//   /* == ADJUNTOS == */
//   divGenerico = el("div.col-xs-12 mt-3");
//   labelGenerico = el(
//     "label.mb-1 fw-semibold sz_inp",
//     {},
//     "Adjuntos"
//   );
//   let contenedorAdj = el('div#contenedorAdj');
  
//   mount(divGenerico, labelGenerico);

//   mount(divGenerico, contenedorAdj);
//   mount(divContainer, divGenerico);

//   divGenerico = el("div.col-xs-12");
//   let divGallery = el('div#gallery_positive');

//   mount(divGenerico, divGallery);
//   mount(divContainer, divGenerico);

//   let galeria = 'gallery_positive';
//   new fileDropAdderPositive(contenedorAdj, 'container_positive', galeria).init();

//   mount(divCaja, divContainer);
//   mount(div, divCaja);
//   mount(contenedor, divCaja);
//   setChildren(sectionPadre, contenedor);
// }
