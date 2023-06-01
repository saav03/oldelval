/* == Generar Bloque de Preguntas == */
const add_bloque_preguntas = document.getElementById("add_bloque_preguntas");
const bloque_preguntas = document.getElementById("bloque_preguntas");

let div,
  col,
  div_dlt_bloque,
  btn_dlt_bloque,
  div_title,
  label,
  inp_title,
  preguntas_collapse,
  pregunta_title,
  parrafo,
  drop_down,
  div_preguntas,
  single_container_ask,
  span_pregunta,
  inp_pregunta,
  span_btn_dlt,
  btn_dlt_pregunta,
  div_add_pregunta,
  btn_pregunta,
  div_gral,
  btn_add_locacion,
  div_bloque_locaciones,
  input_bloque_loc,
  hr;

let cantidad = -1;
let contador = 0;

add_bloque_preguntas.addEventListener("click", (e) => {
  e.preventDefault();
  cantidad++;
  generarBloque(cantidad);
});

/**
 * Genera un nuevo bloque de preguntas cuando se llamada con el click en btn 'add_bloque_preguntas'
 */
function generarBloque(cantidad) {
  let i = cantidad;
  div = el("div.row container_pregunta");

  div_dlt_bloque = el("div.div-dlt_bloque");
  btn_dlt_bloque = el("button#btn_dlt_bloque", el("i.fa fa-times-circle"));

  btn_dlt_bloque.setAttribute("onclick", "removeBloque(this); return false;");

  mount(div_dlt_bloque, btn_dlt_bloque);
  mount(div, div_dlt_bloque);

  col = el("div.col-xs-12");
  div_title = el("div.col-xs-12");
  label = el("label", "Ingrese el título para este bloque");
  inp_title = el("input.form-control", {
    type: "text",
    name: "contenedor[" + cantidad + "][subtitle]", //bandera
    placeholder: "Ingrese aquí",
  });

  mount(div_title, label);
  mount(div_title, inp_title);
  mount(col, div_title);
  mount(div, col);
  mount(bloque_preguntas, div);

  col = el("div.col-xs-12");
  preguntas_collapse = el("div.col-xs-12 preguntas_collapse");
  pregunta_title = el("div.pregunta_title", {
    "data-toggle": "collapse",
    "data-target": "#preguntas_" + i,
  });
  parrafo = el("p", "PREGUNTAS");
  drop_down = el("i.fa fa-caret-down", { "aria-hidden": "true" });

  mount(pregunta_title, parrafo);
  mount(pregunta_title, drop_down);
  mount(preguntas_collapse, pregunta_title);

  div_gral = el("div");
  div_preguntas = el("div#preguntas_" + i, { class: "collapse" });
  div_add_pregunta = el("div.div-add_pregunta");
  btn_add_pregunta = el(
    "button.btn-add_pregunta",
    el("i.fa fa-plus", { "aria-hidden": "true" }),
    "Agregar pregunta"
  );

  btn_add_pregunta.setAttribute("data-pregunta", "preguntas_" + i);
  btn_add_pregunta.setAttribute("data-id", cantidad);
  btn_add_pregunta.setAttribute(
    "onclick",
    "generarPregunta(this); return false;"
  );
  mount(div_add_pregunta, btn_add_pregunta);
  mount(div_preguntas, div_gral);
  mount(div_preguntas, div_add_pregunta);

  mount(preguntas_collapse, div_preguntas);

  mount(col, preguntas_collapse);
  mount(div, col);
}

function generarPregunta(e) {
  contador++;
  let pregunta = e.getAttribute("data-pregunta");
  let ind_pregunta = e.getAttribute("data-id");
  let div_preguntas = document.getElementById(`${pregunta}`).firstElementChild;

  single_container_ask = el("div.col-xs-12 single-container_ask");
  span_pregunta = el("span.num_pregunta", "#" + contador);
  inp_pregunta = el("input.form-control", {
    type: "text",
    placeholder: "Ingrese la pregunta",
    name: "contenedor[" + ind_pregunta + "][preguntas][]", //bandera
  });
  span_btn_dlt = el("span");
  btn_dlt_pregunta = el(
    "button.btn-dlt_pregunta",
    el("i.fa fa-trash", { "aria-hidden": "true" })
  );

  btn_dlt_pregunta.setAttribute("onclick", "removePregunta(this)");

  mount(span_btn_dlt, btn_dlt_pregunta);
  mount(single_container_ask, span_pregunta);
  mount(single_container_ask, inp_pregunta);
  mount(single_container_ask, span_btn_dlt);
  mount(div_preguntas, single_container_ask);

  let num_pregunta = document.querySelectorAll(".num_pregunta");

  for (let i = 0; i < num_pregunta.length; i++) {
    num_pregunta[i].textContent = "#" + (i + 1);
  }
}

function removePregunta(e) {
  e.parentElement.parentElement.remove();
  contador--;
  let num_pregunta = document.querySelectorAll(".num_pregunta");

  for (let i = 0; i < num_pregunta.length; i++) {
    num_pregunta[i].textContent = "#" + (i + 1);
  }
}

function removeBloque(e) {
  e.parentElement.parentElement.remove();
}

/* == Bloque de Locaciones == */
/* const bloque_locaciones = document.querySelectorAll(".bloque_locaciones");
const btn_bloque_locaciones = document.getElementById("btn_bloque_locaciones");
function addBloqueLocacion(e) {
  let element_bloque = e.parentElement.previousElementSibling;
  if (element_bloque.style.display == "none") {
    element_bloque.style.display = "block";
    e.textContent = "Remover Bloque de Locaciones";
    e.classList.remove("btn_add_locaciones");
    e.classList.add("btn_remove_locaciones");
  } else {
    e.classList.remove("btn_remove_locaciones");
    e.classList.add("btn_add_locaciones");
    e.textContent = "Bloque de Locaciones";
    element_bloque.style.display = "none";
  }
} */

/* window.addEventListener("DOMContentLoaded", () => {
  document.getElementById("add_bloque_preguntas").click();
  document.getElementById("add_bloque_preguntas").click();
});
 */
