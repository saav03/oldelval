function submitCredenciales() {
    let form = new FormData(document.getElementById('form_credenciales'));
    return $.ajax({
        type: "POST",
        url: GET_BASE_URL() + "/sendCredentials",
        data: form,
        processData: false,
        contentType: false,
        beforeSend: function () {
            loadingAlert();
        },
    });
}

document
    .getElementById("btn_submit_credenciales")
    .addEventListener("click", function (event) {
        event.preventDefault();
        customConfirmationButton(
            "Envío de Credenciales",
            "¿Confirma enviar las credenciales al usuario?",
            "Enviar",
            "Cancelar",
            "swal_edicion"
        ).then((result) => {
            if (result.isConfirmed) {
                submitCredenciales()
                    .done(function (data) {
                        customSuccessAlert(
                            "Envío Exitoso",
                            "¡Las credenciales fueron enviadas correctamente!",
                            "swal_edicion"
                        ).then((result) => {
                            if (result.isConfirmed) {
                                window.location.reload();
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