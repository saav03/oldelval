/*
=====================================
Filtrar en el selector de 'Proyectos' 
=====================================
*/
const selector_modulos_div_t = document.getElementById("selector_modulos_div_t");
const selector_sistemas_div_t = document.getElementById("selector_sistemas_div_t");
const selector_estaciones_div_t = document.getElementById("selector_estaciones_div_t");

let selector_t, divValid_t, divInvalid_t, optionMod_t, divSetModules_t, optionDefault_t, estaciones_filtradas_t;
/**
 * Filtra entre proyectos y trae aquellos modulos pertenecientes al mismo
 */
function filtrarModulos_t(e) {
    let url = GET_BASE_URL() + "/estadisticas/getModulosFilter";
  
    let formData = new FormData();

    formData.append("id_proyecto", e.value);

    const getDataModulos_t = async () => {
        const response = await fetch(url, {
            method: "POST",
            body: formData,
        });


        const data = await response.json();
       
        crearSelectorMod_t(data);


        crearSelectorEstaciones_t(estaciones_estadisticas, e.value);
        crearSelectorSistemas_t(sistemas_estadisticas, e.value);
    };
    getDataModulos_t();
}

/**
 * Crea el selector de los módulos, siempre va a iniciar en 'No Aplica',
 * en caso de que el proyecto seleccionado es 'Sostenimiento' el módulo no va a ser requerido
 */
function crearSelectorMod_t(data) {


    divSetModules = el("div");

    selector_t = el("select.form-select sz_inp", {
        name: 'modulo_tarea_campo',
        id: 'modulo_tarea_campo',
    });

    if (data.length > 0) { // Si existen módulos, entonces..
        optionDefault = el("option", {
            value: ""
        }, "-- Seleccione --");
        mount(selector_t, optionDefault);
        data.forEach((m) => {
            optionMod = el("option", {
                value: m.id
            }, m.nombre);
            mount(selector_t, optionMod);
        });
    } else { // Caso contrario, el selector 'No Aplica' por defecto
        optionDefault = el("option", {
            value: "-1"
        }, "-- No Aplica --");
        mount(selector_t, optionDefault);
        selector_t.classList.add('simulate_dis');
        selector_t.setAttribute('readonly', true);
    }

    selector_t.setAttribute("onchange", "filtrarEstacionesMod(this)");

    mount(divSetModules, selector_t);
    setChildren(selector_modulos_div_t, divSetModules);
}

/**
 * Crea un selector de las estaciones de bombeos, dependiendo los parámetros que se le pasen
 * van a ser requeridos o no en la base de datos.
 */
function crearSelectorEstaciones_t(data, id_proyecto) {
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

    selector_t = el("select.form-select sz_inp", {
        name: "estacion_bombeo",
        id: "estacion_bombeo_t",
    });

    mount(selector_t, optionDefault);

    data.forEach((m) => {
        optionMod = el("option", {
            value: m.id
        }, m.nombre);
        mount(selector_t, optionMod);
    });

    selector_t.setAttribute("onchange", "validacionEstaciones_t()");

    mount(divSetModules, selector_t);
    setChildren(selector_estaciones_div_t, divSetModules);
}

/**
 * Crea un selector de los sistemas de oleoductos, dependiendo los parámetros que se le pasen
 * van a ser requeridos o no en la base de datos.
 */
function crearSelectorSistemas_t(data, id_proyecto) {
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

    selector_t = el("select.form-select sz_inp", {
        name: "sistema",
        id: "sistema_t",
    });

    mount(selector, optionDefault);

    data.forEach((m) => {
        optionMod = el("option", {
            value: m.id
        }, m.nombre);
        mount(selector, optionMod);
    });

    selector_t.setAttribute("onchange", "validacionSistemas_t()");

    mount(divSetModules, selector_t);
    setChildren(selector_sistemas_div_t, divSetModules);
}

/**
 * Cada vez que el selector de módulos escucha por un cambio, va a realizar un filtro
 * para buscar las estaciones que pertenece a ese módulo.
 */
function filtrarEstacionesMod_t(e) {
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
            crearSelectorEstaciones_t(data, 1);
        } else {
            crearSelectorEstaciones_t(estaciones_estadisticas);
        }
    };
    getDataEstaciones();
}

/**
 * Se creo con el fin de que si elijo una estación, entonces automaticamente
 * el selector de sistema de oleoductos volverá al option por defecto 'No Aplica' 
 */
function validacionEstaciones_t(e = '') {
    divSetModules = el("div");

    optionDefault = el("option", {
        value: "-1"
    }, "-- No Aplica --");

    selector_t = el("select.form-select sz_inp", {
        name: "sistema_t",
        id: "sistema_t",
    });

    mount(selector_t, optionDefault);

    sistemas_estadisticas.forEach((s) => {
        optionMod = el("option", {
            value: s.id
        }, s.nombre);
        mount(selector_t, optionMod);
    });

    selector_t.setAttribute("onchange", "validacionSistemas_t()");

    mount(divSetModules, selector_t);
    setChildren(selector_sistemas_div_t, divSetModules);
}

/**
 * Se creo con el fin de que si elijo un sistema de oleoducto, entonces automaticamente
 * el selector de estaciones de bombeo volverá al option por defecto 'No Aplica' 
 */
function validacionSistemas_t(e = '') {
    divSetModules = el("div");

    optionDefault = el("option", {
        value: "-1"
    }, "-- No Aplica --");

    selector_t = el("select.form-select sz_inp", {
        name: "estacion_bombeo",
        id: "estacion_bombeo_t",
    });

    mount(selector_t, optionDefault);

    let value_modulo = document.getElementById('modulo_tarea_campo').value;

    if (value_modulo == 4) {
        estaciones_filtradas.forEach((s) => {
            optionMod = el("option", {
                value: s.id
            }, s.nombre);
            mount(selector_t, optionMod);
        });
    } else {
        estaciones_estadisticas.forEach((s) => {
            optionMod = el("option", {
                value: s.id
            }, s.nombre);
            mount(selector_t, optionMod);
        });
    }

    selector_t.setAttribute("onchange", "validacionEstaciones_t()");

    mount(divSetModules, selector_t);
    setChildren(selector_estaciones_div_t, divSetModules);
}