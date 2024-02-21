function filterPendientes(filter) {
  let cantidad_pendiente, porcentaje_pendiente;
  switch (filter) {
    case "hoy":
      cantidad_pendiente = cantidad_pendiente_hoy;
      porcentaje_pendiente = porcentaje_pendiente_hoy;
      document.getElementById("span_tarjeta").innerHTML =
        "| Tarjeta M.A.S <small>(Hoy)</small>";
      break;
    case "mes":
      cantidad_pendiente = cantidad_pendiente_mes;
      porcentaje_pendiente = porcentaje_pendiente_mes;
      document.getElementById("span_tarjeta").innerHTML =
        "| Tarjeta M.A.S <small>(Mensual)</small>";
      break;
    case "year":
      cantidad_pendiente = cantidad_pendiente_year;
      porcentaje_pendiente = porcentaje_pendiente_year;
      document.getElementById("span_tarjeta").innerHTML =
        "| Tarjeta M.A.S <small>(Año)</small>";
      break;
  }
  document.getElementById("cantidad_pendiente").innerHTML = cantidad_pendiente;
  document.getElementById("porcentaje_pendiente").innerHTML =
    porcentaje_pendiente + "%";
}

/**
 *
 */
function filterInspecciones(filter) {
  let cantidad_inspecciones;
  switch (filter) {
    case "hoy":
      cantidad_inspecciones = cantidad_inspecciones_hoy;
      document.getElementById("span_inspecciones").innerHTML =
        "| Inspecciones <small>(Hoy)</small>";
      break;
    case "mes":
      cantidad_inspecciones = cantidad_inspecciones_mes;
      document.getElementById("span_inspecciones").innerHTML =
        "| Inspecciones <small>(Mensual)</small>";
      break;
    case "year":
      cantidad_inspecciones = cantidad_inspecciones_year;
      document.getElementById("span_inspecciones").innerHTML =
        "| Inspecciones <small>(Año)</small>";
      break;
  }
  document.getElementById("cantidad_inspecciones").innerHTML =
  cantidad_inspecciones;
}

/**
 *
 */
function filterEstadisticas(filter) {
  let cantidad_estadistica;
  switch (filter) {
    case "hoy":
      cantidad_estadistica = cantidad_estadistica_hoy;
      document.getElementById("span_estadistica").innerHTML =
        "| Estadística <small>(Hoy)</small>";
      break;
    case "mes":
      cantidad_estadistica = cantidad_estadistica_mes;
      document.getElementById("span_estadistica").innerHTML =
        "| Estadística <small>(Mensual)</small>";
      break;
    case "year":
      cantidad_estadistica = cantidad_estadistica_year;
      document.getElementById("span_estadistica").innerHTML =
        "| Estadística <small>(Año)</small>";
      break;
  }
  document.getElementById("cantidad_estadistica").innerHTML =
    cantidad_estadistica;
}
