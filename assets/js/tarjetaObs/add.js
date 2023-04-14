/*
 Dependiendo lo que seleccione en el selector 'Tipo de Observación' 
 se va a mostrar los botones para agregar ya sea obs positiva o un plan de acción   
*/

/* == Declaración de Constantes ==  */

const btns_add_plan_accion = document.getElementById("btns_add_plan_accion");
const btns_obs_positive = document.getElementById("btns_obs_positive");

const section_plan_accion = document.getElementById("section_plan_accion");
const section_obs_positiva = document.getElementById("section_obs_positiva");

/** Botón para agregar plan de acción o no **/

const btn_yes = document.getElementById("btn_yes");
const btn_no = document.getElementById("btn_no");

/** Botón para agregar hallazgos positivos **/

const btn_yes_positive = document.getElementById("btn_yes_positive");
const btn_no_positive = document.getElementById("btn_no_positive");

const posee_obs = document.getElementById("posee_obs");
const situacion = document.getElementById("situacion");

/**
 * Escucha por el cambio del selector (Si es positiva la observación o no)
 */
function listenTipoObs(e) {
  if (e.value == 1) {
    btns_obs_positive.style.display = "block";
    btns_add_plan_accion.style.display = "none";
    btn_no.classList.remove("btn_checked");
    btn_yes.classList.remove("btn_checked");
    section_plan_accion.style.display = "none";
    // situacion.setAttribute('disabled', true);
    situacion.value = 0;
  } else {
    btns_obs_positive.style.display = "none";
    btn_no_positive.classList.remove("btn_checked");
    btn_yes_positive.classList.remove("btn_checked");
    section_obs_positiva.style.display = "none";
    btns_add_plan_accion.style.display = "block";
    // situacion.removeAttribute('disabled');
  }
  posee_obs.value = 0;
}

btn_yes.addEventListener("click", (e) => {
  e.preventDefault();
  btn_yes.classList.add("btn_checked");
  section_plan_accion.style.display = "block";
  btn_no.classList.remove("btn_checked");
  posee_obs.value = 1;
  generarPlanAccion("section_plan_accion", "contenedor");
});

btn_no.addEventListener("click", (e) => {
  e.preventDefault();
  if (
    confirm(
      "Si elige que no la observación actual se eliminará. ¿Está seguro de ésta acción?"
    )
  ) {
    btn_no.classList.add("btn_checked");
    section_plan_accion.style.display = "none";
    btn_yes.classList.remove("btn_checked");
    posee_obs.value = 0;
  }
});

btn_yes_positive.addEventListener("click", (e) => {
  e.preventDefault();
  btn_yes_positive.classList.add("btn_checked");
  section_obs_positiva.style.display = "block";
  btn_no_positive.classList.remove("btn_checked");
  posee_obs.value = 2;
  generarObsPositiva("section_obs_positiva", "contenedor");
});

btn_no_positive.addEventListener("click", (e) => {
  if (
    confirm(
      "Si elige que no la observación actual se eliminará. ¿Está seguro de ésta acción?"
    )
  ) {
    e.preventDefault();
    btn_no_positive.classList.add("btn_checked");
    section_obs_positiva.style.display = "none";
    btn_yes_positive.classList.remove("btn_checked");
    posee_obs.value = 0;
  }
});

/***************************************************************/

const selector_modulos_div = document.getElementById("selector_modulos_div");
const selector_estaciones_div = document.getElementById(
  "selector_estaciones_div"
);
let selector, divValid, divInvalid, optionMod, divSetModules, optionDefault;

/**
 * Filtra entre proyectos y trae aquellos modulos pertenecientes al mismo
 */
function filtrarModulos(e) {
  let url = GET_BASE_URL() + "/TarjetaObs/getModulosFilter";
  let formData = new FormData();
  formData.append("id_proyecto", e.value);

  const getDataModulos = async () => {
    const response = await fetch(url, {
      method: "POST",
      body: formData,
    });

    const data = await response.json();

    if (data.length == 0) {
      crearSelectorEstaciones(estaciones);
    }
    crearSelectorMod(data);
  };
  getDataModulos();
}

function crearSelectorMod(data) {
  divSetModules = el("div");
  optionDefault = el("option", { value: "" }, "-- Seleccione --");
  selector = el("select.form-control sz_inp", {
    name: "modulo",
    id: "modulo",
  });
  selector.setAttribute("onchange", "filtrarEstacionesMod(this)");
  divValid = el("div.valid-feedback");
  divInvalid = el("div.invalid-feedback", "El módulo es requerido");

  mount(selector, optionDefault);

  data.forEach((m) => {
    optionMod = el("option", { value: m.id }, m.nombre);
    mount(selector, optionMod);
  });

  mount(divSetModules, selector);
  mount(divSetModules, divValid);
  mount(divSetModules, divInvalid);
  setChildren(selector_modulos_div, divSetModules);
}

function crearSelectorEstaciones(data) {
  divSetModules = el("div");
  optionDefault = el("option", { value: "" }, "-- Seleccione --");
  selector = el("select.form-control sz_inp", {
    name: "estacion_bombeo",
    id: "estacion_bombeo",
  });
  divValid = el("div.valid-feedback");
  divInvalid = el("div.invalid-feedback", "La estación de bombeo es requerida");

  mount(selector, optionDefault);

  data.forEach((m) => {
    optionMod = el("option", { value: m.id }, m.nombre);
    mount(selector, optionMod);
  });

  mount(divSetModules, selector);
  mount(divSetModules, divValid);
  mount(divSetModules, divInvalid);
  setChildren(selector_estaciones_div, divSetModules);
}

function filtrarEstacionesMod(e) {
  let url = GET_BASE_URL() + "/TarjetaObs/getEstacionesFilter";
  let formData = new FormData();
  formData.append("id_modulo", e.value);

  const getDataEstaciones = async () => {
    const response = await fetch(url, {
      method: "POST",
      body: formData,
    });

    const data = await response.json();

    if (data.length > 0) {
      crearSelectorEstaciones(data);
    } else {
      crearSelectorEstaciones(estaciones);
    }
  };
  getDataEstaciones();
}

function prevenirDefault(event) {
  event.preventDefault();
}
