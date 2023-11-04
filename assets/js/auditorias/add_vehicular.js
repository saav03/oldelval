/* == Definición de Constantes == */
const bloque_rtas_container_vehicular = document.getElementById('bloque_respuestas_container_vehicular'); /* (Contenedor de preguntas) */
const text_aud_title_vehicular = document.getElementById('text-aud_title_vehicular'); /* (Contenedor de preguntas) */
const datos_extras = document.querySelector('#datos_extras');

const option_input = document.querySelectorAll('.option-input');
const obs_medidas = document.querySelector('.obs_medidas');


/**
 * Escucha por un cambio en el selector del tipo de auditorías y en base a eso
 * genera el bloque de preguntas.
 */
function getBloqueAuditoriasVehicular(e) {
    datos_generales_vehicular = document.getElementById('datos_generalesVehicular');
    bloque_resultado = document.getElementById('ResultadoInspeccionVehicular');
    bloque_obs_vehicular_prueba = document.getElementById('obs_vehicular_prueba');
    boton_vehicular = document.getElementById('boton_vehicular');



    let id_auditoria = e.value;
    obs_medidas.value = '';

    for (let i = 0; i < option_input.length; i++) {
        option_input[i].checked = false;
    }

    if (id_auditoria != '') {
        datos_extras.style.display = 'flex';
        fetch(GET_BASE_URL() + "/api/auditorias/getBloqueAud/" + id_auditoria)
            .then((response) => response.json())
            .then((data) => {
                _generar_bloque_preguntas_vehicular(data);

                datos_generales_vehicular.style.display = "block";
                bloque_resultado.style.display = "block";
                bloque_obs_vehicular_prueba.style.display = "block";
                boton_vehicular.style.display = "block";




            });
    } else {
        text_aud_title_vehicular.textContent = '';
        bloque_rtas_container_vehicular.innerHTML = '';
        datos_extras.style.display = 'none';

        datos_generales_vehicular.style.display = "none";
        bloque_resultado.style.display = "none";
        bloque_obs_vehicular_prueba.style.display = "none";
        boton_vehicular.style.display = "none";
    }

}

/**
 * Genera las preguntas para la auditoría checklist vehicular
 */
function _generar_bloque_preguntas_vehicular(datos) {

    text_aud_title_vehicular.textContent = datos['bloque'][0].nombre;

    bloque_rtas_container_vehicular.innerHTML = '';
    for (let i = 0; i < datos['bloque']['bloque_preguntas'].length; i++) {

        // -- Subtitulo Auditoría
        let divRowContainer = el('div.row container mb-4');
        let divSubtitleAud = el('div.div-subtitle_aud_check');
        let h6 = el('h6.subtitle_aud_check', datos['bloque']['bloque_preguntas'][i].nombre);
        mount(divSubtitleAud, h6);
        mount(divRowContainer, divSubtitleAud);
        // ----------

        let divRow = el('div.row');
        let bloque_preguntas = datos['bloque']['bloque_preguntas'][i]['preguntas'];

        for (let j = 0; j < bloque_preguntas.length; j++) {
            // -- Preguntas
            let divColFlex = el('div.d-flex justify-content-around align-items-center pb-2 pt-2 flex-container_questions');

            let divColPregunta = el('div.col-xs-12 col-md-5');
            let divGral = el('div');

            let pregunta = el('p.m-0', el('small', el('b', '( ' + (j + 1) + ' ) ')), bloque_preguntas[j].pregunta);
            mount(divGral, pregunta);
            // ----------

            // -- Botones Toggle
            let btnsToggle = _generar_btns_toggle_vehicular(datos['bloque']['bloque_preguntas'][i].id, bloque_preguntas[j].id, bloque_preguntas[j].pregunta);
            mount(divColPregunta, divGral);
            mount(divColFlex, divColPregunta);
            mount(divColFlex, btnsToggle);
            // ----------

            // -- Selector OBS
            let divSelectObs = el('div.col-xs-12.col-md-2');
            let selectObs = el('select.form-select sz_inp text-center', {
                name: "tipo_obs[" + bloque_preguntas[j].id + "]"
            });
            let optionGral = el('option', {
                value: ''
            }, '-- OBS --');
            mount(selectObs, optionGral);

            for (let t = 0; t < tipo_obs_vehicular.length; t++) {
                let option = el('option', {
                    value: tipo_obs_vehicular[t]['id']
                }, tipo_obs_vehicular[t]['tipo']);
                mount(selectObs, option);
            }

            mount(divSelectObs, selectObs);
            // ----------

            mount(divColFlex, divSelectObs);

            // -- Comentario BTN
            let divCommentCol = el('div.col-xs-12 col-md-2');
            let divComment = el('div.d-flex justify-content-end');
            let pComment = el('p.m-0 p-1 btn-comment', {
                'data-bs-toggle': 'collapse',
                'data-bs-target': '#comment_mejora_' + bloque_preguntas[j].id,
            }, el('i.fa-solid fa-comment'), ' Comentario');

            mount(divComment, pComment);
            mount(divCommentCol, divComment)
            mount(divColFlex, divCommentCol);
            // ----------

            // -- Comentario Collapse (Textarea)
            let divCommentTextArea = el('div.row collapse', {
                id: 'comment_mejora_' + bloque_preguntas[j].id
            });
            let divFloating = el('div.form-floating mt-2 mb-2');
            let txtArea = el('textarea.form-control sz_inp comment_accion', {
                placeholder: 'Leave a comment here',
                id: 'comentario_accion_tomar_' + bloque_preguntas[j].id,
                name: "comentarios_preguntas[" + bloque_preguntas[j].id + "]",
            });
            let labelTxtArea = el('label.mb-1 fw-semibold sz_inp', {
                for: 'comentario_accion_tomar_' + bloque_preguntas[j].id,
                style: 'padding-left: 55px; width: 80%; margin: 0 auto 0 100px;'
            }, 'Comentario / Acciones a tomar:');

            mount(divRow, divColFlex);
            mount(divFloating, txtArea);
            mount(divFloating, labelTxtArea);
            mount(divCommentTextArea, divFloating);
            mount(divRow, divCommentTextArea);
            // ----------
        }

        mount(divRowContainer, divRow);
        /* Último mount */
        mount(bloque_rtas_container_vehicular, divRowContainer);
    }

}

/**
 * Genera los botones toggle 'Bien | Mal | No Aplica'
 */
function _generar_btns_toggle_vehicular(aux, id_pregunta, pregunta) {
    let input, label;
    let div = el('div.col-xs-12 col-md-3 text-center');
    let divToggle = el('div.btn-group btn-group-toggle', {
        role: 'group',
        'aria-label': 'Basic radio toggle button group'
    });
    input = el('input.btn-check verde_check', {
        type: 'radio',
        name: "bloque_respuestas[" + aux + "][respuestas][" + id_pregunta + "][rta]",
        value: '1',
        id: 'btn_bien_' + id_pregunta,
        autocomplete: 'off'
    })
    input_pregunta = el('input', {
        type: 'hidden',
        name: "bloque_respuestas[" + aux + "][respuestas][" + id_pregunta + "][pregunta]",
        value: pregunta,
    });
    label = el('label.btn verde btnsToggle', {
        for: 'btn_bien_' + id_pregunta
    }, 'Bien');

    mount(divToggle, input);
    mount(divToggle, label);
    mount(divToggle, input_pregunta);

    input = el('input.btn-check rojo_check', {
        type: 'radio',
        name: "bloque_respuestas[" + aux + "][respuestas][" + id_pregunta + "][rta]",
        value: '0',
        id: 'btn_mal_' + id_pregunta,
        autocomplete: 'off'
    })
    input_pregunta = el('input', {
        type: 'hidden',
        name: "bloque_respuestas[" + aux + "][respuestas][" + id_pregunta + "][pregunta]",
        value: pregunta,
    });
    label = el('label.btn rojo btnsToggle', {
        for: 'btn_mal_' + id_pregunta
    }, 'Mal');

    mount(divToggle, input);
    mount(divToggle, label);
    mount(divToggle, input_pregunta);

    input = el('input.btn-check amarillo_checked', {
        type: 'radio',
        name: "bloque_respuestas[" + aux + "][respuestas][" + id_pregunta + "][rta]",
        value: '-1',
        id: 'btn_na_' + id_pregunta,
        autocomplete: 'off'
    })
    input.setAttribute('checked', true);
    label = el('label.btn amarillo btnsToggle', {
        for: 'btn_na_' + id_pregunta
    }, 'N/A');
    input_pregunta = el('input', {
        type: 'hidden',
        name: "bloque_respuestas[" + aux + "][respuestas][" + id_pregunta + "][pregunta]",
        value: pregunta,
    });

    mount(divToggle, input);
    mount(divToggle, label);
    mount(divToggle, input_pregunta);

    mount(div, divToggle);

    return div;
}