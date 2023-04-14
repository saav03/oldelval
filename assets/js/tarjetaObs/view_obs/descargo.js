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

  arrayImgs.forEach((img) => {
    form.append("files[]", img);
  });

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
              // window.location.reload();
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

const btn_aceptar_motivo_descargo = document.getElementById("aceptar_motivo_descargo");
btn_aceptar_motivo_descargo.addEventListener("click", (e) => {
  e.preventDefault();

  let form = new FormData(document.getElementById("form_motivo_descargo"));

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
              // window.location.reload();
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
              // window.location.reload();
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
const aceptar_descargo = document.getElementById("aceptar_descargo");
const rechazar_descargo = document.getElementById("rechazar_descargo");

/* == Botones de los motivos de descargos == */
const aceptar_motivo_descargo = document.getElementById(
  "aceptar_motivo_descargo"
);
const cancelar_motivo_descargo = document.getElementById(
  "cancelar_motivo_descargo"
);

/* == Contenedor de botones en lo descargos == */
const btns_descargos = document.getElementById("btns_descargos");
/* == Contenedor de motivo del descargo == */
const container_motivo = document.getElementById("container_motivo");

/* == Otros == */
const label_motivo = document.getElementById("label_motivo");
const tipo_rta_descargo = document.getElementById("tipo_rta_descargo");
const inp_id_descargo = document.getElementById("inp_id_descargo");

aceptar_descargo.addEventListener("click", (e) => {
  e.preventDefault();
  tipo_rta_descargo.value = 1; 
  inp_id_descargo.value = aceptar_descargo.getAttribute('data-id');
  container_motivo.style.display = "block";
  btns_descargos.style.display = "none";
  label_motivo.textContent =
    "Motivo por el cual se acepta la respuesta/descargo";
});

rechazar_descargo.addEventListener("click", (e) => {
  e.preventDefault();
  tipo_rta_descargo.value = 2;
  inp_id_descargo.value = rechazar_descargo.getAttribute('data-id');
  container_motivo.style.display = "block";
  btns_descargos.style.display = "none";
  label_motivo.textContent =
    "Motivo por el cual se rechaza la respuesta/descargo";
});

aceptar_motivo_descargo.addEventListener("click", (e) => {
  e.preventDefault();
  /* == Acá se ejecutaría el AJAX == */
  /*
        Tengo que hacer la diferencia para saber si se está aceptando o rechazando el descargo
        */
});
cancelar_motivo_descargo.addEventListener("click", (e) => {
  e.preventDefault();
  btns_descargos.style.display = "block";
  container_motivo.style.display = "none";
});
