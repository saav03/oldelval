/* == Declaración de Constantes ==  */
const btns_add_plan_accion = document.getElementById("btns_add_plan_accion");
const btns_obs_positive = document.getElementById("btns_obs_positive");

const section_plan_accion = document.getElementById("section_plan_accion");
const section_obs_positiva = document.getElementById("section_obs_positiva");

const situacion = document.getElementsByName("situacion");
const terminos_observacion = document.getElementById("terminos_observacion");

// Definición de Constantes - Selector Efecto Impacto
const selector_efectos_impacto = document.querySelector('#efecto_impacto');
// Definición de Constantes - Significancia
const all_btn_significancia = document.querySelectorAll('.btn_check_significancia');

// Definición de Constantes - ¿Cómo evaluaría la observación actual en términos positivos / oportunidades de mejora?
const tipo_observacion = document.getElementsByName("tipo_observacion");
const btn_tipo_obs = document.querySelectorAll('.btn_tipo_obs');
const alerta_obs_estado = document.getElementById('alerta_obs_estado');


// == Definición de Constantes - ¿Desea destacar un reconocimiento positivo?
const container_generar_reconocimiento = document.getElementById("generar_reconocimiento");
const si_ejecutar_reconocimiento = document.getElementById("si_ejecutar_reconocimiento");
const no_ejecutar_reconocimiento = document.getElementById("no_ejecutar_reconocimiento");
const destacar_reconocimiento = document.getElementById("destacar_reconocimiento");

// == Definición de Constantes - Observación actual con estado cerrado
const container_obs_cerrada = document.getElementById("obs_cerrada_sin_plan");

// == Definición de Constantes - Inputs
const input_tipo_positivo = document.getElementById("tipo_positivo");
const label_tipo_positivo = document.getElementById("label_tipo_positivo");
const input_oportunidad_mejora = document.getElementById("oportunidad_mejora");
const label_oportunidad_mejora = document.getElementById("label_oportunidad_mejora");


// Esto hace de que se habilite o no para agregar un reconocimiento positivo o una oportunidad de mejora
for (let i = 0; i < situacion.length; i++) {
  situacion[i].addEventListener('change', e => {
    alerta_obs_estado.style.display = 'none';
    for (let j = 0; j < btn_tipo_obs.length; j++) {
      btn_tipo_obs[j].removeAttribute('disabled');
      tipo_observacion[j].removeAttribute('disabled');
    }

    // # Si es de estado cerrada, entonces no hay plan de acción, pero si puede generar un reconocimiento positivo
    if (situacion[i].value == 0) {
      label_tipo_positivo.removeAttribute('disabled');
      input_tipo_positivo.removeAttribute('disabled');
      section_plan_accion.style.display = 'none';

      if (input_oportunidad_mejora.checked) {
        section_plan_accion.style.display = "none";

        container_obs_cerrada.style.display = 'block';

      } else if (input_tipo_positivo.checked) {
        container_obs_cerrada.style.display = 'none';
        container_generar_reconocimiento.style.display = 'block';
      }

    } else { // # Si está abierta, entonces..
      // # Si es una observación abierta, entonces no van haber hallazgos positivos, tiene que estar cerrada para eso
      label_tipo_positivo.setAttribute('disabled', '');
      input_tipo_positivo.setAttribute('disabled', '');
      section_obs_positiva.style.display = 'none';

      if (input_oportunidad_mejora.checked) {
        section_plan_accion.style.display = "block";
        container_obs_cerrada.style.display = 'none';

      } else if (input_tipo_positivo.checked) {

        container_obs_cerrada.style.display = 'none';
        container_generar_reconocimiento.style.display = 'none';
      }

    }

  });
}

for (let i = 0; i < tipo_observacion.length; i++) {
  tipo_observacion[i].addEventListener('change', e => {
    if (tipo_observacion[i].value == 1) { // Reconocimiento Positivo

      // # Removemos el btn checkeado
      for (let j = 0; j < btn_tipo_obs.length; j++) {
        btn_tipo_obs[j].classList.remove('btn_checked');
      }
      btn_tipo_obs[i].classList.add('btn_checked');

      // # Desactivamos los efectos / impactos en caso de ser un reconocimiento positivo
      document.querySelector('#efecto_impacto').setValue(0);
      document.getElementById('efecto_impacto').setAttribute('disabled', '');

      // # También habilitamos solamente la significancia 'Aceptable'.
      for (let j = 0; j < all_btn_significancia.length; j++) {
        if (all_btn_significancia[j].classList.contains('blanco_check')) {
          all_btn_significancia[j].removeAttribute('disabled');
          all_btn_significancia[j].setAttribute('checked', '');
        } else {
          all_btn_significancia[j].checked = false;
          all_btn_significancia[j].setAttribute('disabled', '');
        }
      }

      container_generar_reconocimiento.style.display = 'none';
      container_obs_cerrada.style.display = 'none';

      // # Si la tarjeta está cerrada, entonces puede tener un reconocimiento positivo
      if (document.getElementById('cerrada').checked) {
        container_generar_reconocimiento.style.display = 'block';
        container_obs_cerrada.style.display = 'none';
      }

    } else if (tipo_observacion[i].value == 2) { // Oportunidad de Mejora

      // # Removemos el btn checkeado
      for (let j = 0; j < btn_tipo_obs.length; j++) {
        btn_tipo_obs[j].classList.remove('btn_checked');
      }
      btn_tipo_obs[i].classList.add('btn_checked');

      // # Activamos los efectos / impactos en caso de ser una oportunidad de mejora
      document.getElementById('efecto_impacto').removeAttribute('disabled');

      // # También habilitamos todas las significancias.
      for (let j = 0; j < all_btn_significancia.length; j++) {
        all_btn_significancia[j].removeAttribute('disabled');
        all_btn_significancia[j].checked = false;
      }

      // # Si la tarjeta no está cerrada, entonces..
      if (!document.getElementById('cerrada').checked) {
        section_plan_accion.style.display = 'block';
        section_obs_positiva.style.display = 'none';
        container_generar_reconocimiento.style.display = 'none';
        container_obs_cerrada.style.display = 'none';
      } else { // # Si la tarjeta está abierta, entonces hay plan de acción
        section_plan_accion.style.display = 'none';
        section_obs_positiva.style.display = 'none';
        container_obs_cerrada.style.display = 'block';
        container_generar_reconocimiento.style.display = 'none';
      }

    }
  });
}

si_ejecutar_reconocimiento.addEventListener("click", (e) => {
  e.preventDefault();
  section_obs_positiva.style.display = "block";
  si_ejecutar_reconocimiento.classList.add("btn_checked");
  no_ejecutar_reconocimiento.classList.remove("btn_checked");
  destacar_reconocimiento.value = 1;
});
no_ejecutar_reconocimiento.addEventListener("click", (e) => {
  e.preventDefault();
  section_obs_positiva.style.display = "none";
  no_ejecutar_reconocimiento.classList.add("btn_checked");
  si_ejecutar_reconocimiento.classList.remove("btn_checked");
  destacar_reconocimiento.value = 0;
});


/**************************************************************************************************/
/**************************************************************************************************/
/**************************************************************************************************/

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

/** Efecto e Impacto **/
VirtualSelect.init({
  ele: '#efecto_impacto',
  placeholder: 'Seleccione uno o mas efectos',
});
document.getElementById('efecto_impacto').setAttribute('disabled', '');
VirtualSelect.init({
  ele: '#contratista',
  placeholder: 'Seleccione una contratista',
});
VirtualSelect.init({
  ele: '#contratista_plan',
  placeholder: 'Seleccione una contratista',
});
VirtualSelect.init({
  ele: '#contratista_reconocimiento',
  placeholder: 'Seleccione una contratista',
});
VirtualSelect.init({
  ele: '#responsable',
  placeholder: 'Seleccione un responsable',
});
VirtualSelect.init({
  ele: '#relevo_responsable',
  placeholder: 'Seleccione un relevo de responsable',
});

document.getElementById('contratista').setValue(0);
document.getElementById('contratista_plan').setValue(0);
document.getElementById('contratista_reconocimiento').setValue(0);
document.getElementById('responsable').setValue(0);
document.getElementById('relevo_responsable').setValue(0);