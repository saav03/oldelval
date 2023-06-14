function submitUpload(form, url) {
    return $.ajax({
        type: "POST",
        url: GET_BASE_URL() + url,
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
        let url;
        let id_form = btnUploadAud[i].getAttribute('data-id')
        if (id_form == 'form_aud_control') {
            url = '/audcontrol/submitPlanilla';
        } else {
            url = '/audvehicular/submitPlanilla';
        }
        let form = new FormData(document.getElementById(`${id_form}`));
        customConfirmationButton(
            "Crear Auditoría",
            "¿Confirma la carga de la misma?",
            "Cargar",
            "Cancelar",
            "swal_edicion"
        ).then((result) => {
            if (result.isConfirmed) {
                submitUpload(form, url)
                    .done(function (data) {
                        customSuccessAlert(
                            "Carga Exitosa",
                            "La auditoría se cargó correctamente",
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
                        customShowErrorAlert(null, errors, 'swal_edicion');
                    });
            }
        });
    });
}