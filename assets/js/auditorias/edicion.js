// Itera por cada botón para agregar una pregunta a cada bloque adecuado
const btn_add_question = document.querySelectorAll('.btn_add_question');
const section_questions = document.querySelectorAll('.section_questions');

for (let i = 0; i < btn_add_question.length; i++) {
    btn_add_question[i].addEventListener('click', () => {
        event.preventDefault();
        const id = btn_add_question[i].getAttribute('data-id')
        const section = document.getElementById(`section_questions_${id}`);

        let article = el('article.d-flex mb-2');
        let textarea_question = el('textarea.form-control sz_inp inp_pregunta', {
            id: 'text-area_question',
            name: `bloque[${id}][preguntas_editadas][]`,
            placeholder: 'Ingrese la nueva pregunta',
            rows: '1'
        });
        let button = el('button.ms-3 btn_del_question', el('i.fa-solid fa-trash'));

        button.addEventListener('click', () => {
            article.remove();
        })

        mount(article, textarea_question);
        mount(article, button);
        mount(section, article);
    });
}

// Constantes
const btn_action = document.querySelector('.btn_action');
const add_question = document.querySelectorAll('.add_question');
const inp_pregunta = document.querySelectorAll('.inp_pregunta');
const visible_btn_submit = document.getElementById('visible_btn_submit');

const content_modal = document.querySelector('.content_modal');
const btn_modal = document.querySelector('.btn_modal');
const subt_question = document.querySelectorAll('.subt_question');
const title_auditoria = document.querySelector('.title_auditoria');

const div_new_bloque = document.querySelector('.div-new_bloque');
const btn_del_question = document.querySelectorAll('.btn_del_question');

// Habilitar el modo de edición para poder editar y/o eliminar las preguntas
btn_action.addEventListener('click', e => {
    event.preventDefault();
    if (btn_action.classList.contains('btn_edition')) {

        // Mostrar Cartel para dar una advertencia de las Revisiones
        btn_action.classList.add('btn_cancel');
        btn_action.classList.remove('btn_edition');
        btn_action.innerHTML = `<i class="fa-solid fa-xmark"></i> Cancelar Edición`;

        title_auditoria.removeAttribute('disabled');
        visible_btn_submit.style.display = 'block'

        // Habilitando el botón '+' para agregar nuevas preguntas
        for (let i = 0; i < add_question.length; i++) {
            add_question[i].style.display = 'flex';
        }

        for (let i = 0; i < subt_question.length; i++) {
            subt_question[i].removeAttribute('disabled');
        }

        // Habilita el poder editar la pregunta y modificarla
        for (let i = 0; i < inp_pregunta.length; i++) {
            inp_pregunta[i].removeAttribute('disabled');
        }

        // Habilita los botones para eliminar una pregunta
        for (let i = 0; i < btn_del_question.length; i++) {
            btn_del_question[i].style.display = 'block';
        }

        // Habilita poder agregar nuevos bloques
        div_new_bloque.style.display = 'block';

    } else {
        content_modal.innerHTML = `<p><b>¿Está seguro de deshabilitar la funcionalidad de edición?</b> Cualquier modificación realizada no se guardará ni registrará.</p>`;
        btn_modal.textContent = 'Cancelar Edición';
        btn_modal.addEventListener('click', () => {
            location.reload();
        })
    }
})

/**
 * Elimina una pregunta del CheckList
 */
function deleteQuestion(e) {
    event.preventDefault();
    e.parentElement.remove();
}

// Constantes
const section_nuevos_bloques = document.getElementById('section_nuevos_bloques');

/**
 * Agrega un nuevo bloque de preguntas para la auditoría
 */
function addNewQuestion() {
    event.preventDefault();

    let div_block_new = el('div.row mt-2 p-3', {
        id: 'block-news_questions'
    });
    let div = el('div');

    let input = el('input.form-control subt_question', {
        placeholder: 'Ingrese el título del bloque',
        name: `bloque[${section_aux}][subtitulo]`
    });
    let section_questions = el('div.section_questions', {
        id: `section_new_questions_${section_aux}`
    });
    let div_flex = el('div.d-flex justify-content-center mt-3');
    let btn = el('button.btn_add_new_question', el('i.fa-solid fa-circle-plus'));

    let div_line = el('div.ps-3 pe-3', el('hr'));

    mount(div_flex, btn);
    mount(div, input);
    mount(div, section_questions);
    mount(div, div_flex);
    mount(div_block_new, div);

    mount(div_block_new, div_line);
    mount(section_nuevos_bloques, div_block_new);

    /**
     * Agrega un nuevo input para agregar una pregunta
     */
    btn.addEventListener('click', e => {
        event.preventDefault();

        let article = el('article.d-flex mb-2');

        let textarea_question = el('textarea.form-control sz_inp inp_pregunta', {
            id: 'text-area_question',
            name: `bloque[${section_aux}][preguntas_editadas][]`,
            placeholder: 'Ingrese una pregunta',
            rows: '1'
        });
        let btn_delete = el('button.ms-3 btn_del_question', el('i.fa-solid fa-trash'));

        /**
         * Elimina el artículo que almacena la pregunta
         */
        btn_delete.addEventListener('click', e => {
            event.preventDefault();
            article.remove();
        })

        mount(article, textarea_question);
        mount(article, btn_delete);
        mount(section_questions, article);
    })

}


/*
==
SUBMIT EDICIÓN
==
*/ 

// Constantes
const btn_submit = document.getElementById('btn_submit');

// Submit del Formulario
document
    .getElementById("form_submit")
    .addEventListener("submit", function(event) {
        event.preventDefault();
        customConfirmationButton(
            "Edición de Auditoría",
            "¿Confirma la edición de la misma?",
            "Cargar Edición",
            "Cancelar",
            "swal_edicion"
        ).then((result) => {
            if (result.isConfirmed) {
                btn_submit.setAttribute('disabled', true);
                btn_submit.style.cursor = 'wait';
                fetch(`${GET_BASE_URL()}/auditoria/submitEdicionPlanilla`, {
                        method: 'POST',
                        body: new FormData(document.getElementById('form_submit'))
                    })
                    .then(response => response.json())
                    .then(data => {
                        console.log(data);

                        if (!data.exito) {
                            let errors = Object.values(data.errores);
                            errors = errors.join(". ");
                            btn_submit.removeAttribute('disabled');
                            btn_submit.style.cursor = 'default';
                            customShowErrorAlert(null, errors, 'swal_edicion');
                            return;
                        }

                        customSuccessAlert(
                            "Inspección Actualizada",
                            "La Inspección se ha actualizado correctamente",
                            "swal_edicion"
                        ).then((result) => {
                            if (result.isConfirmed) {
                                window.location.replace(`${GET_BASE_URL()}/auditorias/planillas`);
                            }
                        });

                    })
                    .catch(error => {
                        console.error('Error en la solicitud:', error);
                    });
            }
        });
    });