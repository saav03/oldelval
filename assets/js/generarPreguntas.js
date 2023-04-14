function generarPreguntas(preguntas, contenedor) {
    preguntas.forEach(pregunta => {
      dibujarPregunta(pregunta, contenedor);
      contador_preguntas++;
    });
  }