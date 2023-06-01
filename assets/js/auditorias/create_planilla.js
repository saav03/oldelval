/*
============================
Bloque de Preguntas
============================
*/

const add_bloque_preguntas = document.getElementById('add_bloque_preguntas'); /* Boton de add bloque de preguntas */
const container_bloque_preguntas = document.getElementById('container_bloque_preguntas'); /* Contenedor del Bloque de Preguntas */

let cantidad = -1;
let contador = 0;

add_bloque_preguntas.addEventListener('click', e => {
    e.preventDefault();
    cantidad++;
    generarBloquePreguntas(cantidad);
})

let divCol;
let br = el('br');
let hr = el('hr', {
    style: 'border-top: 4px solid #e5c7c7;'
});

function generarBloquePreguntas(cantidad) {
    let i = cantidad;

    let divContenedor = el('div.row', {
        style: 'width: 85%; margin: 0 auto;'
    });
    mount(container_bloque_preguntas, divContenedor);

    /* Botón de Eliminar Bloque (x) */
    let divBtnDelBLoque = el('div.row justify-content-end');
    divCol = el('div.col-xs-12 col-md-1');
    let btnDelBloque = el('button.btn-del_question', {
        title: 'Eliminar Bloque de Preguntas'
    }, el('i.fa-solid fa-xmark'));

    btnDelBloque.setAttribute("onclick", "removeBloque(this.parentElement); return false;");

    mount(divCol, btnDelBloque);
    mount(divBtnDelBLoque, divCol);
    mount(divContenedor, divBtnDelBLoque);
    // --------

    let divTitleContainer = el('div.title_container');

    /* Titulo Bloque de Preguntas (x) */
    divCol = el('div.col-xs-12 mt-3');
    let divFormGroup = el('div.form-group');
    let label = el('label.sz_inp mb-1 fw-semibold', 'Ingrese el título para este bloque');
    let input = el('input.form-control.sz_inp', {
        placeholder: 'Ingrese el título',
        name: "contenedor[" + cantidad + "][subtitle]"
    });

    mount(divFormGroup, label);
    mount(divFormGroup, input);
    mount(divCol, divFormGroup);
    // --------

    mount(divTitleContainer, divCol);
    // mount(divTitleContainer, br);

    /* Div All Preguntas */
    let divAllPreguntas = el('div#all_preguntas_' + i);
    mount(divTitleContainer, divAllPreguntas);
    // --------

    /* Div Generar Pregunta */
    let div_generar_pregunta = el('div.text-center mt-2');
    let btnAddQuestion = el('button#btnAddQuestion', el('i.fa-solid fa-plus'), 'Agregar Pregunta');
    mount(div_generar_pregunta, btnAddQuestion);

    btnAddQuestion.setAttribute("data-pregunta", "all_preguntas_" + i);
    btnAddQuestion.setAttribute("data-id", cantidad);
    btnAddQuestion.setAttribute(
        "onclick",
        "generar_pregunta(this); return false;"
    );

    mount(divTitleContainer, div_generar_pregunta);
    // --------


    mount(divContenedor, divTitleContainer);
    /* Aun no */
    mount(container_bloque_preguntas, divContenedor);
    // mount(divContenedor, hr);
}

/**
 * 
 */
function generar_pregunta(e) {
    contador++;
    let pregunta = e.getAttribute("data-pregunta");
    let ind_pregunta = e.getAttribute("data-id");
    let div_preguntas = document.getElementById(`${pregunta}`);
    console.log(pregunta);
    let divCol;
    let divRow = el('div.row', {style:'margin: 5px;'});

    divCol = el('div.col-xs-12.col-md-1');
    mount(divRow, divCol);
    divCol = el('div.col-xs-12.col-md-10');

    let divFlex = el('div.d-flex align-items-center');
    let label = el('label.label_question', contador);
    let input = el('input.form-control sz_inp', {
        type: 'text',
        placeholder: 'Ingrese la pregunta',
        name: "contenedor[" + ind_pregunta + "][preguntas][]",
    });
    let label_del_question = el('label.del_question', el('i.fa-solid fa-trash'));

    label_del_question.setAttribute("onclick", "removePregunta(this)");

    mount(divFlex, label);
    mount(divFlex, input);
    mount(divFlex, label_del_question);
    mount(divCol, divFlex);
    mount(divRow, divCol);
    divCol = el('div.col-xs-12.col-md-1');
    mount(divRow, divCol);
    mount(div_preguntas, divRow);

    let num_pregunta = document.querySelectorAll(".label_question");

    for (let i = 0; i < num_pregunta.length; i++) {
        num_pregunta[i].textContent = (i + 1);
    }
}

/**
 * Elimina un bloque de preguntas
 */
function removeBloque(e) {
    e.parentElement.parentElement.remove();
}

/**
 * Elimina la pregunta
 */
function removePregunta(e) {
    e.parentElement.parentElement.parentElement.remove();
    contador--;
    let num_pregunta = document.querySelectorAll(".label_question");

    for (let i = 0; i < num_pregunta.length; i++) {
        num_pregunta[i].textContent = (i + 1);
    }
}