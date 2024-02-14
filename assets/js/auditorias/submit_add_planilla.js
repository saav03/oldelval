function submitUpload(form) {
  return $.ajax({
    type: "POST",
    url: GET_BASE_URL() + "/auditoria/create",
    data: form,
    processData: false,
    contentType: false,
    beforeSend: function () {
      loadingAlert();
    },
  });
}

function submitInspection() {
    let form = new FormData(document.getElementById("form_checklist"));
    customConfirmationButton(
      "Enviar Inspección",
      "¿Confirma enviar la Inspección?",
      "Enviar",
      "Cancelar",
      "swal_edicion"
    ).then((result) => {
      if (result.isConfirmed) {
        submitUpload(form)
          .done(function (data) {
            console.log(data);
            customSuccessAlert(
                "Envío Exitoso",
                "La Inspección se envió correctamente",
                "swal_edicion"
            ).then((result) => {
                if (result.isConfirmed) {
                    window.location.replace(GET_BASE_URL() + "/auditorias");
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
}