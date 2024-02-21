// Registrar Mantenimiento
const btnLoadMaintenance = document.getElementById("btnLoadMaintenance");

if (btnLoadMaintenance) {
  btnLoadMaintenance.addEventListener("click", (e) => {
    e.preventDefault();
    let form = new FormData();
    form.append("hora", document.getElementById("hora").value);
    form.append("fecha", document.getElementById("fecha").value);

    if (document.getElementById("hora").value == '' || document.getElementById("fecha").value == '') {
        customShowErrorAlert(null, 'No se permiten campos vacíos', 'swal_edicion');
        return;
    }

    fetch(`${GET_BASE_URL()}/sistema/store`, {
      method: "POST",
      body: form,
    })
      .then((response) => {
        if (!response.ok) {
          throw new Error("Error en la solicitud");
        }

        return response.json();
      })
      .then((data) => {
        if (data) {
            customSuccessAlert(
                "Mantenimiento Agregado",
                "Se agregó el cartel de mantenimiento en el Dashboard",
                "swal_edicion"
              ).then((result) => {
                if (result.isConfirmed) {
                  window.location.replace(`${GET_BASE_URL()}/dashboard`);
                }
              });
        }
      })
      .catch((error) => {
        console.error("Error:", error);
      });
  });
}

// Remover cartel de Mantenimiento
const btnRemoveMaintenance = document.getElementById("btnRemoveMaintenance");
if (btnRemoveMaintenance) {
  btnRemoveMaintenance.addEventListener("click", (e) => {
    e.preventDefault();
    fetch(`${GET_BASE_URL()}/sistema/removeMaintenance`, {
      method: "POST",
    })
      .then((response) => {
        if (!response.ok) {
          throw new Error("Error en la solicitud");
        }

        return response.json();
      })
      .then((data) => {
        if (data) {
            customSuccessAlert(
                "Mantenimiento Retirado",
                "Se eliminó el cartel de mantenimiento",
                "swal_edicion"
              ).then((result) => {
                if (result.isConfirmed) {
                  window.location.replace(`${GET_BASE_URL()}/dashboard`);
                }
              });
        }
        console.log(data);
      })
      .catch((error) => {
        console.error("Error:", error);
      });
  });
}
