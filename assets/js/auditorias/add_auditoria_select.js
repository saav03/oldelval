/*
=====================================
Filtrar en el selector de 'Proyectos' 
=====================================
*/
const selector_modulos_div_a = document.getElementById("selector_modulos_div_a");
const selector_sistemas_div_a = document.getElementById("selector_sistemas_div_a");
const selector_estaciones_div_a = document.getElementById("selector_estaciones_div_a");

let selector_a, divValid_a, divInvalid_a, optionMod_a, divSetModules_a, optionDefault_a, estaciones_filtradas_a;
/**
 * Filtra entre proyectos y trae aquellos modulos pertenecientes al mismo
 */
function filtrarModulos_a(e) {
    let url = GET_BASE_URL() + "/estadisticas/getModulosFilter";

    let formData = new FormData();

    formData.append("id_proyecto", e.value);

    const getDataModulos_a = async () => {
        const response = await fetch(url, {
            method: "POST",
            body: formData,
        });


        const data = await response.json();

        crearSelectorMod_a(data);


        crearSelectorEstaciones_a(estaciones_estadisticas, e.value);
        crearSelectorSistemas(sistemas_estadisticas, e.value);
    };
    getDataModulos_a();
}

/**
 * Crea el selector de los módulos, siempre va a iniciar en 'No Aplica',
 * en caso de que el proyecto seleccionado es 'Sostenimiento' el módulo no va a ser requerido
 */
function crearSelectorMod_a(data) {


    divSetModules = el("div");

    selector_a = el("select.form-select sz_inp", {
        name: 'modulo_a',
        id: 'modulo_a',
    });

    if (data.length > 0) { // Si existen módulos, entonces..
        optionDefault = el("option", {
            value: ""
        }, "-- Seleccione --");
        mount(selector_a, optionDefault);
        data.forEach((m) => {
            optionMod = el("option", {
                value: m.id
            }, m.nombre);
            mount(selector_a, optionMod);
        });
    } else { // Caso contrario, el selector 'No Aplica' por defecto
        optionDefault = el("option", {
            value: "-1"
        }, "-- No Aplica --");
        mount(selector_a, optionDefault);
        selector_a.classList.add('simulate_dis');
        selector_a.setAttribute('readonly', true);
    }

    selector_a.setAttribute("onchange", "filtrarEstacionesMod_a(this)");

    mount(divSetModules, selector_a);
    setChildren(selector_modulos_div_a, divSetModules);
}

/**
 * Crea un selector de las estaciones de bombeos, dependiendo los parámetros que se le pasen
 * van a ser requeridos o no en la base de datos.
 */
function crearSelectorEstaciones_a(data, id_proyecto) {
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

    selector_a = el("select.form-select sz_inp", {
        name: "estacion_bombeo_a",
        id: "estacion_bombeo_a",
    });

    mount(selector_a, optionDefault);

    data.forEach((m) => {
        optionMod = el("option", {
            value: m.id
        }, m.nombre);
        mount(selector_a, optionMod);
    });

    selector_a.setAttribute("onchange", "validacionEstaciones_a()");

    mount(divSetModules, selector_a);
    setChildren(selector_estaciones_div_a, divSetModules);
}

/**
 * Crea un selector de los sistemas de oleoductos, dependiendo los parámetros que se le pasen
 * van a ser requeridos o no en la base de datos.
 */
function crearSelectorSistemas_a(data, id_proyecto) {
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

    selector_a = el("select.form-select sz_inp", {
        name: "sistema_a",
        id: "sistema_a",
    });

    mount(selector, optionDefault);

    data.forEach((m) => {
        optionMod = el("option", {
            value: m.id
        }, m.nombre);
        mount(selector, optionMod);
    });

    selector_a.setAttribute("onchange", "validacionSistemas_a()");

    mount(divSetModules, selector_a);
    setChildren(selector_sistemas_div_a, divSetModules);
}

/**
 * Cada vez que el selector de módulos escucha por un cambio, va a realizar un filtro
 * para buscar las estaciones que pertenece a ese módulo.
 */
function filtrarEstacionesMod_a(e) {
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
            crearSelectorEstaciones_a(data, 1);
        } else {
            crearSelectorEstaciones_a(estaciones_estadisticas);
        }
    };
    getDataEstaciones();
}

/**
 * Se creo con el fin de que si elijo una estación, entonces automaticamente
 * el selector de sistema de oleoductos volverá al option por defecto 'No Aplica' 
 */
function validacionEstaciones_a(e = '') {
    divSetModules = el("div");

    optionDefault = el("option", {
        value: "-1"
    }, "-- No Aplica --");

    selector_a = el("select.form-select sz_inp", {
        name: "sistema_a",
        id: "sistema_a",
    });

    mount(selector_a, optionDefault);

    sistemas_estadisticas.forEach((s) => {
        optionMod = el("option", {
            value: s.id
        }, s.nombre);
        mount(selector_a, optionMod);
    });

    selector_a.setAttribute("onchange", "validacionSistemas_a()");

    mount(divSetModules, selector_a);
    setChildren(selector_sistemas_div_a, divSetModules);
}

/**
 * Se creo con el fin de que si elijo un sistema de oleoducto, entonces automaticamente
 * el selector de estaciones de bombeo volverá al option por defecto 'No Aplica' 
 */
function validacionSistemas_a(e = '') {
    divSetModules = el("div");

    optionDefault = el("option", {
        value: "-1"
    }, "-- No Aplica --");

    selector_a = el("select.form-select sz_inp", {
        name: "estacion_bombeo_a",
        id: "estacion_bombeo_a",
    });

    mount(selector_a, optionDefault);

    let value_modulo = document.getElementById('modulo_a').value;

    if (value_modulo == 4) {
        estaciones_filtradas.forEach((s) => {
            optionMod = el("option", {
                value: s.id
            }, s.nombre);
            mount(selector_a, optionMod);
        });
    } else {
        estaciones_estadisticas.forEach((s) => {
            optionMod = el("option", {
                value: s.id
            }, s.nombre);
            mount(selector_a, optionMod);
        });
    }

    selector_a.setAttribute("onchange", "validacionEstaciones_a()");

    mount(divSetModules, selector_a);
    setChildren(selector_estaciones_div_a, divSetModules);
}