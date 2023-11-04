/* 
=======================
Editar Permisos 
=======================
*/

const btnSubmit = document.getElementById("btnSubmit");
const formulario = document.getElementById("formPermiso");

btnSubmit.addEventListener("click", (e) => {
  e.preventDefault();
  let form = new FormData(formulario);
  customConfirmationButton(
    "Editar Permiso",
    "¿Confirma la edición del permiso?",
    "Editar",
    "Cancelar",
    "swal_edicion"
  ).then((result) => {
    if (result.isConfirmed) {
      editPermiso(form).done(function (data) {
        console.log(data);
      });
      customSuccessAlert(
        "Edición Exitosa",
        "El permiso se editó correctamente",
        "swal_edicion"
      );
    }
  });
});

function editPermiso(form) {
  return $.ajax({
    type: "POST",
    data: form,
    url: GET_BASE_URL() + "/permisos/editPermission",
    processData: false,
    contentType: false,
    beforeSend: function () {
      loadingAlert();
    },
  });
}

