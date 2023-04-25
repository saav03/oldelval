// -----------------------------------------------------------------CAMBIO DINAMICO DE COLOR Y RADIO (MATRIZ DE RIESGO)-----------------------------------------------------------

const celdas = document.querySelectorAll(".celdas_colores");
const input_celdas = document.querySelectorAll(".inputCeldas");
const checks = document.querySelectorAll(".checkAnimado");
const checkAnimado = document.getElementById("checkis");

// agregado del check animado
const addMore = () => {
  const checkAni = document.getElementById("checkis");
  setChildren(checks[i], checkAni);
};

let checkAni;
for (let i = 0; i < input_celdas.length; i++) {
  switch (riesgo_fila) {
    case "1":
      if (riesgo_fila == input_celdas[i].getAttribute("data-num")) {
        if (riesgo == input_celdas[i].value) {
          switch (riesgo) {
            case "1":
              celdas[i].classList.add("color_bajo");
              break;
            case "2":
              celdas[i].classList.add("color_medio");
              break;
            case "3":
              celdas[i].classList.add("color_alto");
              break;
            case "4":
              celdas[i].classList.add("color_extremo");
              break;
          }
          checkAni = document.getElementById("checkis");
          setChildren(checks[i], checkAni);
          checks[i];
        }
      }

      document.getElementById("riesgo").value = "Menor";
      break;
    case "2":
      if (riesgo_fila == input_celdas[i].getAttribute("data-num")) {
        if (riesgo == input_celdas[i].value) {
          switch (riesgo) {
            case "1":
              celdas[i].classList.add("color_bajo");
              break;
            case "2":
              celdas[i].classList.add("color_medio");
              break;
            case "3":
              celdas[i].classList.add("color_alto");
              break;
            case "4":
              celdas[i].classList.add("color_extremo");
              break;
          }
          checkAni = document.getElementById("checkis");
          setChildren(checks[i], checkAni);
          checks[i];
        }
      }
      document.getElementById("riesgo").value = "Serio";
      break;
    case "3":
      if (riesgo_fila == input_celdas[i].getAttribute("data-num")) {
        if (riesgo == input_celdas[i].value) {
          console.log(riesgo);
          switch (riesgo) {
            case "1":
              celdas[i].classList.add("color_bajo");
              break;
            case "2":
              celdas[i].classList.add("color_medio");
              break;
            case "3":
              celdas[i].classList.add("color_alto");
              break;
            case "4":
              celdas[i].classList.add("color_extremo");
              break;
          }
          checkAni = document.getElementById("checkis");
          setChildren(checks[i], checkAni);
          checks[i];
        }
      }
      document.getElementById("riesgo").value = "Grave";
      break;
    case "4":
      if (riesgo_fila == input_celdas[i].getAttribute("data-num")) {
        if (riesgo == input_celdas[i].value) {
          console.log(riesgo);
          switch (riesgo) {
            case "1":
              celdas[i].classList.add("color_bajo");
              break;
            case "2":
              celdas[i].classList.add("color_medio");
              break;
            case "3":
              celdas[i].classList.add("color_alto");
              break;
            case "4":
              celdas[i].classList.add("color_extremo");
              break;
          }
          checkAni = document.getElementById("checkis");
          setChildren(checks[i], checkAni);
          checks[i];
        }
      }
      document.getElementById("riesgo").value = "Catastrófico";
      break;
  }
}