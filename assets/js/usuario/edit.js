/*
====================
Permisos del Usuario
====================
*/

let arbol;

$(document).ready(function () {
  arbol = $("#tree").tree({
    textField: "nombre",
    dragAndDrop: true,
    cascadeCheck: false,
    checkedField: "pertenece",
    primaryKey: "id",
    uiLibrary: "bootstrap5",
    dataSource: permisos_usuario,
    checkboxes: true,
  });
});

let allPermissions;

const select_grupos = document.getElementById("grupos");
let grupos_arr = [];
let datosPermiso = [];

//  **Se setean los grupos en el value del selector del grupo**
grupos_usuario.forEach((g) => {
  grupos_arr.push(g.id_grupo);
});
select_grupos.setValue(grupos_arr);

select_grupos.addEventListener('change', function () {
  let id_grupos = this.value;
  form = new FormData();

  const getPermisos = async () => {

    if (id_grupos.length > 0) {
      for (let i = 0; i < id_grupos.length; i++) {
        form.append('grupos[]', id_grupos[i])
      }
    } else {
      form.append('grupos[]', 0)
    }
    form.append('id_usuario', id_usuario);
    
    const response = await fetch(
      `${GET_BASE_URL()}/oldelval/getPermissionsGrousUsers`, {
        method: 'POST',
        body: form
      }
    );

    const data = await response.json();
    datosPermiso = data;
    return data;
  };

  (async () => {
    await getPermisos();

    for (let i = 0; i < permisos_usuario.length; i++) {
      permisos_usuario[i].pertenece = 0;
    }

    for (let i = 0; i < datosPermiso.length; i++) {
      if (datosPermiso[i].pertenece_grupo == 1 || datosPermiso[i].pertenece_usuario == 1) {
        permisos_usuario[i].pertenece = 1;
      }
    }

    arbol.destroy();

    // Reinicio el árbol y le seteo los permisos ya checkeados que pertenecen a esos grupos

    arbol = $("#tree").tree({
      textField: "nombre",
      dragAndDrop: true,
      cascadeCheck: true,
      checkedField: "pertenece",
      primaryKey: "id",
      uiLibrary: "bootstrap5",
      dataSource: permisos_usuario,
      checkboxes: true,
    });
  })();
});


// == Botones de Edición por parte de los Permisos ==

const btnEditNormal = document.querySelector(".btn-edit_normal");
const container_permisos = document.querySelector("#container_permisos");
const sector_botones = document.querySelector("#sector_botones");
const div_permisos = document.querySelector("#div_permisos");

const btnCancelar = document.querySelector(".btn-edit_cancelar");
const btnEnviar = document.querySelector(".btn-edit_submit");

btnEditNormal.addEventListener("click", (e) => {
  e.preventDefault();
  div_permisos.style.opacity = "1";
  container_permisos.style.pointerEvents = "auto";
  sector_botones.style.display = "flex";
  sector_botones.classList.add("sector_botones");
  btnEditNormal.style.display = "none";
});

btnCancelar.addEventListener("click", (e) => {
  e.preventDefault();

  div_permisos.style.opacity = ".5";
  container_permisos.style.pointerEvents = "none";
  btnEditNormal.style.display = "flex";
  sector_botones.style.display = "none";
  sector_botones.classList.remove("sector_botones");
});

btnEnviar.addEventListener("click", (e) => {
  e.preventDefault();
  let form = new FormData(document.getElementById("form_permisos"));
  let checkedIds = arbol.getCheckedNodes();
  for (let i = 0; i < checkedIds.length; i++) {
    form.append("checkedIds[]", checkedIds[i]);
  }

  customConfirmationButton(
    "Edición de Permisos",
    "¿Confirma la modificación de permisos?",
    "Editar",
    "Cancelar",
    "swal_edicion"
  ).then((result) => {
    if (result.isConfirmed) {
      editarPermisos(form).done(function (data) {});
      customSuccessAlert(
        "Modificación Exitosa",
        "Los cambios de los permisos se modificaron correctamente",
        "swal_edicion"
      ).then((result) => {
        if (result.isConfirmed) {
          window.location.reload();
        }
      });
    }
  });
});

function editarPermisos(form) {
  return $.ajax({
    type: "POST",
    url: GET_BASE_URL() + "/usuario/editarPermisosUsuario",
    data: form,
    processData: false,
    contentType: false,
    beforeSend: function () {
      loadingAlert();
    },
  });
}