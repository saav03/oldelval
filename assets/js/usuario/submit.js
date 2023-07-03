function submitUsuario() {
    let form = new FormData(document.getElementById('form-add'));
    for (let i = 0; i < permisos.length; i++) {
        form.append('permisos[]', permisos[i]);
    }
    return $.ajax({
      type: "POST",
      url: GET_BASE_URL() + "/addNewUser",
      data: form,
      processData: false,
      contentType: false,
      beforeSend: function () {
        loadingAlert();
      },
    });
  }
  
  document
    .getElementById("buttonAddUsuario")
    .addEventListener("click", function (event) {
      event.preventDefault();
      permisos = arbol.getCheckedNodes();
      customConfirmationButton(
        "Carga de Usuario",
        "¿Confirma cargar este usuario?",
        "Cargar",
        "Cancelar",
        "swal_edicion"
      ).then((result) => {
        if (result.isConfirmed) {
          submitUsuario(permisos)
            .done(function (data) {
              customSuccessAlert(
                "Carga de Usuario Exitoso",
                "El usuario se registró correctamente",
                "swal_edicion"
              ).then((result) => {
                if (result.isConfirmed) {
                  window.location.replace(GET_BASE_URL() + "/all_users");
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
  