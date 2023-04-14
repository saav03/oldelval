function submitForm() {
  let form = new FormData(document.getElementById("form_submit"));
  return $.ajax({
    type: "POST",
    url: GET_BASE_URL() + "/Estadistica/submit",
    data: form,
    processData: false,
    contentType: false,
    beforeSend: function () {
      loadingAlert();
    },
  });
}

document
  .getElementById("btnSubmitEstadistica")
  .addEventListener("click", function (event) {
    event.preventDefault();
    customConfirmationButton(
      "Carga de Estadística",
      "¿Confirma la carga de la misma?",
      "Cargar",
      "Cancelar",
      "swal_edicion"
    ).then((result) => {
      if (result.isConfirmed) {
        submitForm()
          .done(function (data) {
            customSuccessAlert(
              "Registro Exitoso",
              "La estadística se registró correctamente",
              "swal_edicion"
            ).then((result) => {
              if (result.isConfirmed) {
                window.location.replace(GET_BASE_URL() + "/estadisticas");
              }
            });
          })
          .fail((err, textStatus, xhr) => {
            let errors = Object.values(JSON.parse(err.responseText));
            errors = errors.join(". ");
            customShowErrorAlert(null, errors, 'swal_edicion');
          });
      }
    });
  });
