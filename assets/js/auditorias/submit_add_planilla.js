function submitUpload(form) {
    return $.ajax({
        type: "POST",
        url: GET_BASE_URL() + "/Auditorias/submitUpload",
        data: form,
        processData: false,
        contentType: false,
        beforeSend: function () {
            loadingAlert();
        },
    });
}

const btnUploadAud = document.querySelectorAll('.btnUploadAud');

for (let i = 0; i < btnUploadAud.length; i++) {
    btnUploadAud[i].addEventListener("click", function (event) {
        event.preventDefault();
        let id_form = btnUploadAud[i].getAttribute('data-id')
        let form = new FormData(document.getElementById(`${id_form}`));
        customConfirmationButton(
            "Crear Auditoría",
            "¿Confirma la carga de la misma?",
            "Cargar",
            "Cancelar",
            "swal_edicion"
        ).then((result) => {
            if (result.isConfirmed) {
                submitUpload(form)
                    .done(function (data) {
                        customSuccessAlert(
                            "Carga Exitosa",
                            "La auditoría se cargó correctamente",
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
}