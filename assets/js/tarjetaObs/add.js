/*
=========================================================================================================================================
*/

const label_oportunidad_mejora = document.getElementById('label_oportunidad_mejora');

/*
=========================================================================================================================================
*/

/*
===============================
Seleccione el Aspecto Observado
===============================
*/
const label_riesgo_seguridad = document.getElementById(
  "label_riesgo_seguridad"
);
const label_impacto_ambiental = document.getElementById(
  "label_impacto_ambiental"
);

/*
=========================================================================================================================================
*/

/*
=============================================================
Selector de las Estaciones de Bombeo y Sistemas de Oleoductos
=============================================================
*/

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

  if (data.length > 0) {
    // Si existen módulos, entonces..
    optionDefault = el(
      "option",
      {
        value: "",
      },
      "-- Seleccione --"
    );
    mount(selector, optionDefault);
    data.forEach((m) => {
      optionMod = el(
        "option",
        {
          value: m.id,
        },
        m.nombre
      );
      mount(selector, optionMod);
    });
  } else {
    // Caso contrario, el selector 'No Aplica' por defecto
    optionDefault = el(
      "option",
      {
        value: "-1",
      },
      "-- No Aplica --"
    );
    mount(selector, optionDefault);
    selector.classList.add("simulate_dis");
    selector.setAttribute("readonly", true);
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
    optionDefault = el(
      "option",
      {
        value: "",
      },
      "-- No Aplica --"
    );
  } else {
    optionDefault = el(
      "option",
      {
        value: "-1",
      },
      "-- No Aplica --"
    );
  }

  selector = el("select.form-select sz_inp", {
    name: "estacion_bombeo",
    id: "estacion_bombeo",
  });

  mount(selector, optionDefault);

  data.forEach((m) => {
    optionMod = el(
      "option",
      {
        value: m.id,
      },
      m.nombre
    );
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
    optionDefault = el(
      "option",
      {
        value: "",
      },
      "-- No Aplica --"
    );
  } else {
    optionDefault = el(
      "option",
      {
        value: "-1",
      },
      "-- No Aplica --"
    );
  }

  selector = el("select.form-select sz_inp", {
    name: "sistema_oleoducto",
    id: "sistema_oleoducto",
  });

  mount(selector, optionDefault);

  data.forEach((m) => {
    optionMod = el(
      "option",
      {
        value: m.id,
      },
      m.nombre
    );
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
function validacionEstaciones(e = "") {
  divSetModules = el("div");

  optionDefault = el(
    "option",
    {
      value: "-1",
    },
    "-- No Aplica --"
  );

  selector = el("select.form-select sz_inp", {
    name: "sistema_oleoducto",
    id: "sistema_oleoducto",
  });

  mount(selector, optionDefault);

  sistemas.forEach((s) => {
    optionMod = el(
      "option",
      {
        value: s.id,
      },
      s.nombre
    );
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
function validacionSistemas(e = "") {
  divSetModules = el("div");

  optionDefault = el(
    "option",
    {
      value: "-1",
    },
    "-- No Aplica --"
  );

  selector = el("select.form-select sz_inp", {
    name: "estacion_bombeo",
    id: "estacion_bombeo",
  });

  mount(selector, optionDefault);

  let value_modulo = document.getElementById("modulo").value;

  if (value_modulo == 4) {
    estaciones_filtradas.forEach((s) => {
      optionMod = el(
        "option",
        {
          value: s.id,
        },
        s.nombre
      );
      mount(selector, optionMod);
    });
  } else {
    estaciones.forEach((s) => {
      optionMod = el(
        "option",
        {
          value: s.id,
        },
        s.nombre
      );
      mount(selector, optionMod);
    });
  }

  selector.setAttribute("onchange", "validacionEstaciones()");

  mount(divSetModules, selector);
  setChildren(selector_estaciones_div, divSetModules);
}

/*
=========================================================================================================================================
*/

/*
===========================
Sección Nuevos Observadores
===========================
*/

const btn_add_obs = document.getElementById("btn_add_obs");
const seccion_observadores = document.getElementById("seccion_observadores");
const container_observadores = document.getElementById(
  "container_observadores"
);
const obs_new_title = document.querySelector(".obs-new_title");
btn_add_obs.addEventListener("click", (e) => {
  e.preventDefault();
  crearSeccionNewObservador();
});

function crearSeccionNewObservador() {
  if (!container_observadores.hasChildNodes()) {
    obs_new_title.style.display = "block";
  }
  let divObservadores = el(
    "div.row d-flex justify-content-end observadores_inps mt-2"
  );
  let div = el("div.col-xs-12 col-md-4 d-flex");
  let btnTrash = el("button.obs-btn_trash", el("i.fa-solid fa-trash"));
  btnTrash.addEventListener("click", (b) => {
    let observadores_inps = document.querySelectorAll(".observadores_inps");
    b.preventDefault();
    let div_padre = btnTrash.parentElement.parentElement;
    div_padre.remove();
    if (observadores_inps.length == 1) {
      obs_new_title.style.display = "none";
    }
  });
  let inputNameObs = el("input.form-control sz_inp", {
    type: "text",
    placeholder: "Ingrese el nombre",
    name: "observadores[]",
  });

  mount(div, btnTrash);
  mount(div, inputNameObs);
  mount(divObservadores, div);
  mount(container_observadores, divObservadores);
}

/*
=========================================================================================================================================
*/
VirtualSelect.init({
  ele: "#contratista",
  placeholder: "Seleccione una contratista",
});
document.getElementById("contratista").setValue(0);
