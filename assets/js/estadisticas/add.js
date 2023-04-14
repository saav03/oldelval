/*
=====================================
Filtrar en el selector de 'Proyectos' 
=====================================
*/
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
  selector = el("select.form-select sz_inp", {
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
  selector = el("select.form-select sz_inp", {
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
