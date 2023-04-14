// -----------------------------------------------------------------CAMBIO DINAMICO DE COLOR Y RADIO (MATRIZ DE RIESGO)-----------------------------------------------------------

const celdas = document.querySelectorAll(".celdas_colores");
const input_celdas = document.querySelectorAll(".inputCeldas");
const checks = document.querySelectorAll(".checkAnimado");
const checkAnimado = document.getElementById("checkis");

for (let j = 0; j < input_celdas.length; j++) {
  const element = input_celdas[j];

  element.checked = false;

  celdas[j].addEventListener("click", () => {
    for (let f = 0; f < celdas.length; f++) {
      if (celdas[f].classList.contains("color_bajo")) {
        celdas[f].classList.remove("color_bajo");
        celdas[f].classList.add("color_apagado_bajo");
      } else if (celdas[f].classList.contains("color_medio")) {
        celdas[f].classList.remove("color_medio");
        celdas[f].classList.add("color_apagado_medio");
      } else if (celdas[f].classList.contains("color_alto")) {
        celdas[f].classList.remove("color_alto");
        celdas[f].classList.add("color_apagado_alto");
      } else if (celdas[f].classList.contains("color_extremo")) {
        celdas[f].classList.remove("color_extremo");
        celdas[f].classList.add("color_apagado_extremo");
      }
    }
  });
}
for (let i = 0; i < celdas.length; i++) {
  const element = celdas[i];

  element.addEventListener("click", () => {
    let fila = input_celdas[i].getAttribute('data-num');

    document.getElementById('riesgo_fila').value = fila;

    input_celdas[i].checked = true;

    if (celdas[i].classList.contains("color_apagado_bajo")) {
      celdas[i].classList.remove("color_apagado_bajo");
      celdas[i].classList.add("color_bajo");
      addMore();
    } else if (celdas[i].classList.contains("color_apagado_medio")) {
      celdas[i].classList.remove("color_apagado_medio");
      celdas[i].classList.add("color_medio");
      addMore();
    } else if (celdas[i].classList.contains("color_apagado_alto")) {
      celdas[i].classList.remove("color_apagado_alto");
      celdas[i].classList.add("color_alto");
      addMore();
    } else if (celdas[i].classList.contains("color_apagado_extremo")) {
      celdas[i].classList.remove("color_apagado_extremo");
      celdas[i].classList.add("color_extremo");
      addMore();
    }
  });

  // agregado del check animado
  const addMore = () => {
    const checkAni = document.getElementById("checkis");
    setChildren(checks[i], checkAni);
  };
}
