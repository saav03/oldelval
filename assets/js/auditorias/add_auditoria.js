/* == Definición de Constantes == */
const bloque_rtas_container_a = document.getElementById('bloque_respuestas_container_a'); /* (Contenedor de preguntas) */
const text_aud_title_a = document.getElementById('text-aud_title_a'); /* (Contenedor de preguntas) */

/**
 * Escucha por un cambio en el selector del tipo de auditorías y en base a eso
 * genera el bloque de preguntas.
 */
function getBloqueAuditorias_a(e) {
    datosgenerales_auditoria = document.getElementById('datosgenerales_a');
    bloque_obs_a = document.getElementById('obs_a');
    boton_tarea_a = document.getElementById('boton_a');

    let id_auditoria = e.value;

    if (id_auditoria != '') {
        fetch(GET_BASE_URL() + "/api/auditorias/getBloqueAud/" + id_auditoria)
            .then((response) => response.json())
            .then((data) => {
                console.log(data);
                _generar_bloque_preguntas_a(data);
                datosgenerales_auditoria.style.display = "block";
                bloque_obs_a.style.display = "block";
                boton_tarea_a.style.display = "block";

            });
    } else {
        text_aud_title_a.textContent = '';
        bloque_rtas_container_a.innerHTML = '';
        datosgenerales_auditoria.style.display = "none";
        bloque_obs_a.style.display = "none"
        boton_tarea_a.style.display = "none"
    }






}


function _generar_bloque_preguntas_a(datos) {

    text_aud_title_a.textContent = datos['bloque'][0].nombre;

    bloque_rtas_container_a.innerHTML = '';
    for (let i = 0; i < datos['bloque']['bloque_preguntas'].length; i++) {

        // -- Subtitulo Auditoría
        let divRowContainer = el('div.row container mb-4');
        let divSubtitleAud = el('div.div-subtitle_aud_a');
        let h6 = el('h6.subtitle_aud_a', datos['bloque']['bloque_preguntas'][i].nombre);
        mount(divSubtitleAud, h6);
        mount(divRowContainer, divSubtitleAud);
        // ----------

        let divRow = el('div.row');
        let bloque_preguntas = datos['bloque']['bloque_preguntas'][i]['preguntas'];

        for (let j = 0; j < bloque_preguntas.length; j++) {
            // -- Preguntas
            let divColFlex = el('div.d-flex justify-content-around align-items-center pb-2 pt-2 flex-container_questions');

            let divColPregunta = el('div.col-xs-12 col-md-7');
            let divGral = el('div');

            let pregunta = el('p.m-0', el('small', el('b', '( ' + (j + 1) + ' ) ')), bloque_preguntas[j].pregunta);
            mount(divGral, pregunta);
            // ----------

            // -- Botones Toggle
            let btnsToggle = _generar_btns_toggle_a(datos['bloque']['bloque_preguntas'][i].id, bloque_preguntas[j].id, bloque_preguntas[j].pregunta);
            mount(divColPregunta, divGral);
            mount(divColFlex, divColPregunta);
            mount(divColFlex, btnsToggle);
            // ----------

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
                name: "comentarios_preguntas_a[" + bloque_preguntas[j].id + "]",
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
        mount(bloque_rtas_container_a, divRowContainer);
    }

}

/**
 * Genera los botones toggle 'Bien | Mal | No Aplica'
 */
function _generar_btns_toggle_a(aux, id_pregunta, pregunta) {
    let input, input_pregunta, label;
    let div = el('div.col-xs-12 col-md-3 text-center');
    let divToggle = el('div.btn-group btn-group-toggle', {
        role: 'group',
        'aria-label': 'Basic radio toggle button group'
    });
    input = el('input.btn-check verde_check', {
        type: 'radio',
        name: "bloque_respuestas_a[" + aux + "][respuestas][" + id_pregunta + "][rta]",
        value: '1',
        id: 'btn_bien_' + id_pregunta,
        autocomplete: 'off'
    });
    input_pregunta = el('input', {
        type: 'hidden',
        name: "bloque_respuestas_a[" + aux + "][respuestas][" + id_pregunta + "][pregunta]",
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
        name: "bloque_respuestas_a[" + aux + "][respuestas][" + id_pregunta + "][rta]",
        value: '0',
        id: 'btn_mal_' + id_pregunta,
        autocomplete: 'off'
    })
    input_pregunta = el('input', {
        type: 'hidden',
        name: "bloque_respuestas_a[" + aux + "][respuestas][" + id_pregunta + "][pregunta]",
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
        name: "bloque_respuestas_a[" + aux + "][respuestas][" + id_pregunta + "][rta]",
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
        name: "bloque_respuestas_a[" + aux + "][respuestas][" + id_pregunta + "][pregunta]",
        value: pregunta,
    });

    mount(divToggle, input);
    mount(divToggle, label);
    mount(divToggle, input_pregunta);

    mount(div, divToggle);

    return div;
}