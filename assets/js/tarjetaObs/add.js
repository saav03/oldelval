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
    situacion.classList.add('simulate_dis');
    situacion.style.pointerEvents = 'none';
  } else {
    btns_obs_positive.style.display = "none";
    btn_no_positive.classList.remove("btn_checked");
    btn_yes_positive.classList.remove("btn_checked");
    section_obs_positiva.style.display = "none";
    btns_add_plan_accion.style.display = "block";
    situacion.classList.remove('simulate_dis');
    situacion.style.pointerEvents = 'all';
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
  if (section_plan_accion.style.display == 'block') {
    customConfirmationButton(
      "Nueva Selección",
      "Si elige que no la observación actual se eliminará. ¿Está seguro de ésta acción?",
      "Cambiar",
      "Cancelar",
      "swal_edicion"
    ).then((result) => {
      btn_no.classList.add("btn_checked");
      section_plan_accion.style.display = "none";
      btn_yes.classList.remove("btn_checked");
      posee_obs.value = 0;
    });
  } else {
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
  e.preventDefault();
  if (section_obs_positiva.style.display == 'block') {
    customConfirmationButton(
      "Nueva Selección",
      "Si elige que no la observación actual se eliminará. ¿Está seguro de ésta acción?",
      "Cambiar",
      "Cancelar",
      "swal_edicion"
    ).then((result) => {
      btn_no_positive.classList.add("btn_checked");
      section_obs_positiva.style.display = "none";
      btn_yes_positive.classList.remove("btn_checked");
      posee_obs.value = 0;
    });
  } else {
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
const selector_sistemas_div = document.getElementById("selector_sistemas_div");

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
    crearSelectorMod(data);

    crearSelectorEstaciones(estaciones, e.value);
    crearSelectorSistemas(sistemas, e.value);
  };
  getDataModulos();
}

/**
 * Crea el selector de los módulos, siempre va a iniciar en 'No Aplica',
 * en caso de que el proyecto seleccionado es 'Sostenimiento' el módulo no va a ser requerido
 */
function crearSelectorMod(data) {
  divSetModules = el("div");

  selector = el("select.form-select sz_inp", {
    name: "modulo",
    id: "modulo",
  });

  if (data.length > 0) { // Si existen módulos, entonces..
    optionDefault = el("option", {
      value: ""
    }, "-- Seleccione --");
    mount(selector, optionDefault);
    data.forEach((m) => {
      optionMod = el("option", {
        value: m.id
      }, m.nombre);
      mount(selector, optionMod);
    });
  } else { // Caso contrario, el selector 'No Aplica' por defecto
    optionDefault = el("option", {
      value: "-1"
    }, "-- No Aplica --");
    mount(selector, optionDefault);
    selector.classList.add('simulate_dis');
    selector.setAttribute('readonly', true);
  }

  selector.setAttribute("onchange", "filtrarEstacionesMod(this)");

  mount(divSetModules, selector);
  setChildren(selector_modulos_div, divSetModules);
}

/**
 * Crea un selector de las estaciones de bombeos, dependiendo los parámetros que se le pasen
 * van a ser requeridos o no en la base de datos.
 */
function crearSelectorEstaciones(data, id_proyecto) {
  divSetModules = el("div");

  if (id_proyecto == 1) {
    optionDefault = el("option", {
      value: ""
    }, "-- No Aplica --");
  } else {
    optionDefault = el("option", {
      value: "-1"
    }, "-- No Aplica --");
  }

  selector = el("select.form-select sz_inp", {
    name: "estacion_bombeo",
    id: "estacion_bombeo",
  });

  mount(selector, optionDefault);

  data.forEach((m) => {
    optionMod = el("option", {
      value: m.id
    }, m.nombre);
    mount(selector, optionMod);
  });

  selector.setAttribute("onchange", "validacionEstaciones()");

  mount(divSetModules, selector);
  setChildren(selector_estaciones_div, divSetModules);
}

/**
 * Crea un selector de los sistemas de oleoductos, dependiendo los parámetros que se le pasen
 * van a ser requeridos o no en la base de datos.
 */
function crearSelectorSistemas(data, id_proyecto) {
  divSetModules = el("div");

  if (id_proyecto == 1) {
    optionDefault = el("option", {
      value: ""
    }, "-- No Aplica --");
  } else {
    optionDefault = el("option", {
      value: "-1"
    }, "-- No Aplica --");
  }

  selector = el("select.form-select sz_inp", {
    name: "sistema_oleoducto",
    id: "sistema_oleoducto",
  });

  mount(selector, optionDefault);

  data.forEach((m) => {
    optionMod = el("option", {
      value: m.id
    }, m.nombre);
    mount(selector, optionMod);
  });

  selector.setAttribute("onchange", "validacionSistemas()");

  mount(divSetModules, selector);
  setChildren(selector_sistemas_div, divSetModules);
}

/**
 * Cada vez que el selector de módulos escucha por un cambio, va a realizar un filtro
 * para buscar las estaciones que pertenece a ese módulo.
 */
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
      estaciones_filtradas = data;
      crearSelectorEstaciones(data, 1);
    } else {
      crearSelectorEstaciones(estaciones);
    }
  };
  getDataEstaciones();
}

/**
 * Se creo con el fin de que si elijo una estación, entonces automaticamente
 * el selector de sistema de oleoductos volverá al option por defecto 'No Aplica' 
 */
function validacionEstaciones(e = '') {
  divSetModules = el("div");

  optionDefault = el("option", {
    value: "-1"
  }, "-- No Aplica --");

  selector = el("select.form-select sz_inp", {
    name: "sistema_oleoducto",
    id: "sistema_oleoducto",
  });

  mount(selector, optionDefault);

  sistemas.forEach((s) => {
    optionMod = el("option", {
      value: s.id
    }, s.nombre);
    mount(selector, optionMod);
  });

  selector.setAttribute("onchange", "validacionSistemas()");

  mount(divSetModules, selector);
  setChildren(selector_sistemas_div, divSetModules);
}

/**
 * Se creo con el fin de que si elijo un sistema de oleoducto, entonces automaticamente
 * el selector de estaciones de bombeo volverá al option por defecto 'No Aplica' 
 */
function validacionSistemas(e = '') {
  divSetModules = el("div");

  optionDefault = el("option", {
    value: "-1"
  }, "-- No Aplica --");

  selector = el("select.form-select sz_inp", {
    name: "estacion_bombeo",
    id: "estacion_bombeo",
  });

  mount(selector, optionDefault);

  let value_modulo = document.getElementById('modulo').value;

  if (value_modulo == 4) {
    estaciones_filtradas.forEach((s) => {
      optionMod = el("option", {
        value: s.id
      }, s.nombre);
      mount(selector, optionMod);
    });
  } else {
    estaciones.forEach((s) => {
      optionMod = el("option", {
        value: s.id
      }, s.nombre);
      mount(selector, optionMod);
    });
  }

  selector.setAttribute("onchange", "validacionEstaciones()");

  mount(divSetModules, selector);
  setChildren(selector_estaciones_div, divSetModules);
}

function prevenirDefault(event) {
  event.preventDefault();
}

/* ================================================================================================================================= */

/*
===========================
Sección Nuevos Observadores
===========================
*/

const btn_add_obs = document.getElementById('btn_add_obs');
const seccion_observadores = document.getElementById('seccion_observadores');
const container_observadores = document.getElementById('container_observadores');
const obs_new_title = document.querySelector('.obs-new_title');
btn_add_obs.addEventListener('click', e => {
  e.preventDefault();
  crearSeccionNewObservador();
})

function crearSeccionNewObservador() {
  if (!container_observadores.hasChildNodes()) {
    obs_new_title.style.display = 'block';
  }
  let divObservadores = el('div.row d-flex justify-content-end observadores_inps mt-2');
  let div = el('div.col-xs-12 col-md-4 d-flex');
  let btnTrash = el('button.obs-btn_trash', el('i.fa-solid fa-trash'));
  btnTrash.addEventListener('click', b => {
    let observadores_inps = document.querySelectorAll('.observadores_inps');
    b.preventDefault();
    let div_padre = btnTrash.parentElement.parentElement
    div_padre.remove();
    console.log(observadores_inps.length);
    if (observadores_inps.length == 1) {
      obs_new_title.style.display = 'none';
    }
  });
  let inputNameObs = el('input.form-control sz_inp', {
    type: 'text',
    placeholder: 'Ingrese el nombre',
    name: 'observadores[]'
  });

  mount(div, btnTrash);
  mount(div, inputNameObs);
  mount(divObservadores, div);
  mount(container_observadores, divObservadores);
}