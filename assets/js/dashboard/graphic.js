/**
 * Genera el gráfico para "Reporte Observaciones | Tarjeta M.A.S"
 */
function obs_graphic_significancia(type) {
  if (type == 1) {
    // NO FUNCIONA ESTO
    document.querySelector("#reportsChart").innerHTML = "";
    document.getElementById("riesgo_seguridad_salud").checked = true;
    document.getElementById("riesgo_impacto_ambiental").checked = false;
    _graphicReportSignificancia(
      aceptable,
      moderado,
      significativo,
      intolerable,
      month,
      document.querySelector("#reportsChart")
    );
  } else {
    // NO FUNCIONA ESTO
    document.getElementById("riesgo_impacto_ambiental").checked = true;
    document.getElementById("riesgo_seguridad_salud").checked = false;
    document.querySelector("#reportsChart").innerHTML = "";
    _graphicReportSignificancia(
      baja,
      media,
      alta,
      muy_alta,
      month,
      document.querySelector("#reportsChart")
    );
  }
}

/**
 * Este método filtra por día, mes y año
 * Cuando filtra por día, [Ejemplo] => si es 14, obtiene el día anterior 13, y el día después 15 para poder realizar bien el gráfico
 */
function filterTodayObsGraphic(type) {
  let inps_checked = document.getElementsByName("riesgo_selected");
  let valor;
  inps_checked.forEach((e) => {
    if (e.checked) {
      valor = e.value;
    }
  });

  switch (type) {
    case "today":
      let fecha = new Date();
      let dia = fecha.getDate();

      let dia_anterior = dia - 2;
      let dia_actual = dia - 1;
      let dia_despues = dia;

      primer_significancia = [];
      primer_significancia.push(0); // Día anterior
      primer_significancia.push(parseInt(aceptable[dia_actual])); // Día actual
      primer_significancia.push(0); // Día después

      segunda_significancia = [];
      segunda_significancia.push(0); // Día anterior
      segunda_significancia.push(parseInt(moderado[dia_actual])); // Día actual
      segunda_significancia.push(0); // Día después

      tercer_significancia = [];
      tercer_significancia.push(0); // Día anterior
      tercer_significancia.push(parseInt(significativo[dia_actual])); // Día actual
      tercer_significancia.push(0); // Día después

      cuarta_significancia = [];
      cuarta_significancia.push(0); // Día anterior
      cuarta_significancia.push(parseInt(intolerable[dia_actual])); // Día actual
      cuarta_significancia.push(0); // Día después

      mes = [];
      mes.push(month[dia_anterior]); // Día anterior
      mes.push(month[dia_actual]); // Día actual
      mes.push(month[dia_despues]); // Día después

      // Realiza el gráfico de los hallazgos en base a su significancia y en el día actual
      _graphicReportSignificancia(
        primer_significancia,
        segunda_significancia,
        tercer_significancia,
        cuarta_significancia,
        mes,
        document.querySelector("#reportsChart")
      );

      break;
    case "month": // Filtra por mes, así que solamente llamamos al método obs_graphic_significancia($param) que se encarga de hacer el gráfico por mes
      obs_graphic_significancia(valor);
      break;
    case "year":
      console.log(valor);
      if (valor == 1) {
        _graphicReportSignificancia(
          aceptable_y,
          moderado_y,
          significativo_y,
          intolerable_y,
          all_months,
          document.querySelector("#reportsChart")
        );
      } else {
        _graphicReportSignificancia(
          baja_y,
          media_y,
          alta_y,
          muy_alta_y,
          all_months,
          document.querySelector("#reportsChart")
        );
      }
      break;
  }
}

/**
 * Esta función genera el gráfico para las observaciones que hay realizadas
 * Como son dos tipos de consecuencias, puede generar el gráfico en:
 * (Obs. con Riesgo en Seguridad y Salud) => aceptables, moderadas, significativas, o intolerables
 * Como así también (Obs. con Impacto Ambiental) => baja, media, alta, muy alta
 * TODO | [VERIFICAR] Si los hallazgos que aparecen están pendientes o solamente filtra por todos
 */
function _graphicReportSignificancia(
  primer_significancia,
  segunda_significancia,
  tercer_significancia,
  cuarta_significancia,
  mes,
  id_container
) {
  id_container.innerHTML = "";

  // Busca por cual botón está seleccionado, es decir, el botón "Observaciones con Riesgo en Seguridad y Salud" ó "Observaciones con Impacto Ambiental"
  let inps_checked = document.getElementsByName("riesgo_selected");
  let valor;
  inps_checked.forEach((e) => {
    if (e.checked) {
      valor = e.value;
    }
  });
  console.log("Este es el valor", valor);

  // Genera la inicialización del gráfico
  new ApexCharts(id_container, {
    series: [
      {
        name: valor == 1 ? "Aceptable" : "Baja",
        data: primer_significancia,
      },
      {
        name: valor == 1 ? "Moderado" : "Media",
        data: segunda_significancia,
      },
      {
        name: valor == 1 ? "Significativo" : "Alta",
        data: tercer_significancia,
      },
      {
        name: valor == 1 ? "Intolerable" : "Muy Alta",
        data: cuarta_significancia,
      },
    ],
    chart: {
      height: 350,
      type: "area",
      toolbar: {
        show: false,
      },
    },
    markers: {
      size: 4,
    },
    colors: ["#A9D8DF", "#66b572", "#E4CA4B", "#F1B7B3"],
    fill: {
      type: "gradient",
      gradient: {
        shadeIntensity: 1,
        opacityFrom: 0.3,
        opacityTo: 0.4,
        stops: [0, 90, 100],
      },
    },
    dataLabels: {
      enabled: false,
    },
    stroke: {
      width: 2,
    },
    xaxis: {
      type: "date",
      categories: mes,
    },
    tooltip: {
      x: {
        format: "dd/MM/yy HH:mm",
      },
    },
  }).render();
}
