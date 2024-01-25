let arrayImgs = [];

function submitForm(arrayImgs) {
  let form = new FormData(document.getElementById("form_submit"));

  arrayImgs.forEach((img) => {
    form.append("files[]", img);
  });

  return $.ajax({
    type: "POST",
    url: GET_BASE_URL() + "/TarjetaObs/submit",
    data: form,
    processData: false,
    contentType: false,
    beforeSend: function () {
      loadingAlert();
    },
  });
}

document
  .getElementById("btnSubmit")
  .addEventListener("click", function (event) {
    event.preventDefault();
    const buttonSubmitUsuario = document.getElementById("buttonAddUsuario");
    customConfirmationButton(
      "Carga de Tarjeta de Observación",
      "¿Confirma la carga de la misma?",
      "Cargar",
      "Cancelar",
      "swal_edicion"
    ).then((result) => {
      if (result.isConfirmed) {
        submitForm(arrayImgs)
          .done(function (data) {
            customSuccessAlert(
              "Registro Exitoso",
              "La observación se registró correctamente",
              "swal_edicion"
            ).then((result) => {
              if (result.isConfirmed) {
                window.location.replace(GET_BASE_URL() + "/TarjetaObs");
              }
            });
          })
          .fail((err, textStatus, xhr) => {
            let errors = Object.values(JSON.parse(err.responseText));
            errors = errors.join(". ");
            customShowErrorAlert(null, errors, "swal_edicion");
          });
      }
    });
  });
