/* 
=======================
Agregar Nuevos Permisos add.php 
=======================
*/

const btnSubmit = document.getElementById("btnSubmit");
const formulario = document.getElementById("formPermiso");

btnSubmit.addEventListener("click", (e) => {
  e.preventDefault();
  let form = new FormData(formulario);
  customConfirmationButton(
    "Cargar Permiso",
    "¿Confirma la carga del permiso?",
    "Cargar",
    "Cancelar",
    "swal_edicion"
  ).then((result) => {
    if (result.isConfirmed) {
      addPermiso(form).done(function (data) {
        console.log(data);
      });
      customSuccessAlert(
        "Carga Exitosa",
        "El permiso se agregó correctamente",
        "swal_edicion"
      );
    }
  });
});

function addPermiso(form) {
  return $.ajax({
    type: "POST",
    data: form,
    url: GET_BASE_URL() + "/permisos/addNewPermission",
    processData: false,
    contentType: false,
    beforeSend: function () {
      loadingAlert();
    },
  });
}

const subpermiso = document.getElementById("subpermiso");
let previos_subpermiso_value = 0;
const orderSelectPermiso = document.getElementById("orden_permiso");
let firstOrderNumberPermiso = 1;
const genericOrderOptPermiso = el(
  "option",
  {
    value: 1,
    selected: true,
  },
  "* Primera Posición *"
);

subpermiso.onchange = () => {
  previos_subpermiso_value = subpermiso.value;
  subPermisosOf = permisosElems.filter(
    (permiso) => permiso.id_permiso_padre == previos_subpermiso_value
  );
  if (subPermisosOf.length > 0)
    firstOrderNumber = Number.parseInt(subPermisosOf[0].orden);
  else firstOrderNumber = 1;
  removeChildren(orderSelectPermiso);
  genericOrderOptPermiso.value = firstOrderNumber;
  orderSelectPermiso.appendChild(genericOrderOptPermiso);
  subPermisosOf.forEach((opt) =>
    orderSelectPermiso.appendChild(
      el(
        "option",
        {
          value: Number.parseInt(opt.orden) + 1,
        },
        opt.nombre
      )
    )
  );
};
