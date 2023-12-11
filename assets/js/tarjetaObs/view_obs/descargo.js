/*
========================
Agregar descargo
========================
*/

function addDescargoAjax(form) {
  return $.ajax({
    type: "POST",
    url: GET_BASE_URL() + "/TarjetaObs/submitDescargo",
    data: form,
    processData: false,
    contentType: false,
    beforeSend: function () {
      loadingAlert();
    },
  });
}

const btn_add_descargo = document.getElementById("btn_add_descargo");
btn_add_descargo.addEventListener("click", (e) => {
  e.preventDefault();

  let form = new FormData(document.getElementById("form_descargo"));

  // arrayImgs.forEach((img) => {
  //   form.append("files[]", img);
  // });

  customConfirmationButton(
    "Respuesta del Hallazgo",
    "¿Confirma la carga de la misma?",
    "Cargar",
    "Cancelar",
    "swal_edicion"
  ).then((result) => {
    if (result.isConfirmed) {
      addDescargoAjax(form)
        .done(function (data) {
          customSuccessAlert(
            "Registro Exitoso",
            "El descargo se registró correctamente",
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
          customShowErrorAlert(null, errors, "swal_edicion");
        });
    }
  });
});


/*
==================
Cerrar Observación
==================
*/
function cerrarObservacion(form) {
  return $.ajax({
    type: "POST",
    url: GET_BASE_URL() + "/TarjetaObs/submitCerrarObs",
    data: form,
    processData: false,
    contentType: false,
    beforeSend: function () {
      loadingAlert();
    },
  });
}

const btn_cerrar_obs = document.getElementById("btn_cerrar_obs");
btn_cerrar_obs.addEventListener("click", (e) => {
  e.preventDefault();

  let form = new FormData(document.getElementById("form_cierre_obs"));

  customConfirmationButton(
    "Cierre de la Observación",
    "¿Confirma la finalización de la misma?",
    "Cerrar Observación",
    "Cancelar",
    "swal_edicion"
  ).then((result) => {
    if (result.isConfirmed) {
      cerrarObservacion(form)
        .done(function (data) {
          customSuccessAlert(
            "Observación Concluida/Finalizada",
            "La observación se cerró correctamente",
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
          customShowErrorAlert(null, errors, "swal_edicion");
        });
    }
  });
});

/* == Botones de los descargos == */
const aceptar_descargo = document.querySelectorAll(".aceptar_descargo");
const rechazar_descargo = document.querySelectorAll(".rechazar_descargo");

/* == Botones de los motivos de descargos == */
const aceptar_motivo_descargo = document.querySelectorAll(
  ".aceptar_motivo_descargo"
);
const cancelar_motivo_descargo = document.querySelectorAll(
  ".cancelar_motivo_descargo"
);

/* == Contenedor de botones en lo descargos == */
const btns_descargos = document.querySelectorAll(".btns_descargos");
/* == Contenedor de motivo del descargo == */
const container_motivo = document.querySelectorAll(".container_motivo");

/* == Otros == */
const label_motivo = document.querySelectorAll(".label_motivo");
const tipo_rta_descargo = document.querySelectorAll(".tipo_rta_descargo");
const inp_id_descargo = document.querySelectorAll(".inp_id_descargo");

for (let i = 0; i < aceptar_descargo.length; i++) {
  aceptar_descargo[i].addEventListener("click", e => {
    e.preventDefault();
    tipo_rta_descargo[i].value = 1;
    inp_id_descargo[i].value = aceptar_descargo[i].getAttribute('data-id');
    container_motivo[i].style.display = "block";
    btns_descargos[i].style.display = "none";
    label_motivo[i].textContent =
      "Motivo por el cual se acepta la respuesta/descargo";
  });
}

for (let i = 0; i < rechazar_descargo.length; i++) {
  rechazar_descargo[i].addEventListener("click", e => {
    e.preventDefault();
    tipo_rta_descargo[i].value = 2;
    inp_id_descargo[i].value = rechazar_descargo[i].getAttribute('data-id');
    container_motivo[i].style.display = "block";
    btns_descargos[i].style.display = "none";
    label_motivo[i].textContent =
      "Motivo por el cual se rechaza la respuesta/descargo";

  });
}

/*
=============================
Agregar respuesta al descargo
=============================
*/

function addRtaDescargo(form) {
  return $.ajax({
    type: "POST",
    url: GET_BASE_URL() + "/TarjetaObs/submitRtaDescargo",
    data: form,
    processData: false,
    contentType: false,
    beforeSend: function () {
      loadingAlert();
    },
  });
}

for (let i = 0; i < aceptar_motivo_descargo.length; i++) {
  aceptar_motivo_descargo[i].addEventListener("click", (e) => {
    e.preventDefault();
    let id_descargo = aceptar_descargo[i].getAttribute('data-id');
    let form = new FormData(document.getElementById("form_motivo_descargo_"+id_descargo));

    customConfirmationButton(
      "Respuesta del Descargo",
      "¿Confirma la carga de la misma?",
      "Cargar",
      "Cancelar",
      "swal_edicion"
    ).then((result) => {
      if (result.isConfirmed) {
        addRtaDescargo(form)
          .done(function (data) {
            customSuccessAlert(
              "Registro Exitoso",
              "La respuesta se registró correctamente",
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
            customShowErrorAlert(null, errors, "swal_edicion");
          });
      }
    });
  });

}

for (let i = 0; i < cancelar_motivo_descargo.length; i++) {
  cancelar_motivo_descargo[i].addEventListener("click", (e) => {
    e.preventDefault();
    btns_descargos[i].style.display = "block";
    container_motivo[i].style.display = "none";
  });
}