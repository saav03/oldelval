function dibujarPregunta(pregunta, contenedor) {
    let row = el("div.row");
    let seccion_pregunta = el("div.col-md-10 col-xs-12",{"style":"text-align: left!important;"});
    let pregunta_texto = el("div.form-group", el("p", contador_preguntas + ". " + pregunta.pregunta));
    mount(seccion_pregunta, pregunta_texto);
    mount(row, seccion_pregunta);
    let seccion_respuestas = el("div.col-md-2 col-xs-12");
   let grupo = el("input.form-control sz_inp",{"type":"number"});
    mount(seccion_respuestas, el("div.form-group", grupo));
    mount(row, seccion_respuestas);
    mount(contenedor, row);
    ////
    mount(contenedor, el("hr"));
  }