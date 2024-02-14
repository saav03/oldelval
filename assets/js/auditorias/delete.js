/**
 * Eliminar una Inspección junto con todos sus hallazgos, descargos, adjuntos, riesgos correspondientes
 */
function deleteInspection(e) {
  event.preventDefault();
  let id_inspeccion = e.getAttribute("data-id");
  let form = new FormData();
  form.append('id_inspeccion', id_inspeccion);
  customConfirmationButton(
    "Eliminar Inspección",
    "¿Está seguro de eliminar esta inspección? (No se podrá recuperar)",
    "Eliminar",
    "Cancelar",
    "swal_edicion"
  ).then((result) => {
    if (result.isConfirmed) {
      fetch(`${GET_BASE_URL()}/auditorias/delete`, {
        method: "POST",
        body: form
      }).then((response) => response.json());
      window.location.reload();
    }
  });
}
