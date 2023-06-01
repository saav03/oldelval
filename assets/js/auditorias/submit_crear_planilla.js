function submitCrear() {
    let form = new FormData(document.getElementById("form_create_aud"));
    return $.ajax({
        type: "POST",
        url: GET_BASE_URL() + "/Auditorias/submit",
        data: form,
        processData: false,
        contentType: false,
        beforeSend: function () {
            loadingAlert();
        },
    });
}

document
    .getElementById("btnSubmitCrearAuditoria")
    .addEventListener("click", function (event) {
        event.preventDefault();
        customConfirmationButton(
            "Crear Auditoría",
            "¿Confirma la carga de la misma?",
            "Cargar",
            "Cancelar",
            "swal_edicion"
        ).then((result) => {
            if (result.isConfirmed) {
                submitCrear()
                    .done(function (data) {
                        customSuccessAlert(
                            "Registro Exitoso",
                            "La auditoría se registró correctamente",
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