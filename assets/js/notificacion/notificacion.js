/**
 * Consulta por las notificaciones que tiene el usuario quien está logueado
 */
function notification() {
  fetch(`${GET_BASE_URL()}/notificaciones`)
    .then((response) => {
      // Si la consulta sale mal, entonces retorna un error
      if (!response.ok) {
        throw new Error("Error en la solicitud");
      }
      return response.json();
    })
    .then((data) => {
      let contenedor_notificaciones = document.querySelector(
        ".contenedor_notificaciones"
      );
      contenedor_notificaciones.innerHTML = "";

      let alert;
      let aux = 0;
      let i = 1;
      let leido = "";

      // Genera la cantidad de notificaciones nuevas que hay
      data.forEach((e) => {
        if (e.leido == 0) {
          aux++;
        }
      });

      // Si no hay notificaciones, entonces, no muestra nada más que la campanita
      if (aux == 0) {
        document.querySelector(".cant_notifications").innerHTML = "";
        document.querySelector(
          "#notificaciones_nuevas"
        ).textContent = `No hay nuevas notificaciones`;
      } else {
        // Caso contrario, muestra la cantidad de notificaciones nuevas en la campanita
        document.querySelector(".cant_notifications").textContent = aux;
        document.querySelector(
          "#notificaciones_nuevas"
        ).textContent = `Hay ${aux} notificaciones nuevas`;
      }

      // Genera las notificaciones
      data.forEach((e) => {
        if (i <= 4) {
          if (e.leido == 0) {
            leido = "new";
          } else {
            leido = false;
          }

          // En este switch, muestro el tipo de notificación va a ser si es de alerta, informativa, etc.
          switch (e.tipo) {
            case "1":
              alert = "text-warning";
              contenedor_notificaciones.innerHTML += `
                <button onclick="btnRedirectView({id: '${e.id}', url: '${e.url}${e.id_opcionales}'})" style="background: none; border: none; padding: 0; text-align: left;">
                    <li class="notification-item ${leido}">
                        <i class="bi bi-exclamation-circle ${alert}"></i>
                        <div>
                        <h4>${e.titulo}</h4>
                        <p>${e.descripcion}</p>
                        <p>${e.tiempo}</p>
                        </div>
                    </li>
                </button>
    
                <li>
                    <hr class="dropdown-divider">
                </li>
                `;
              break;
            case "2":
              alert = "text-success";
              contenedor_notificaciones.innerHTML += `
                <button onclick="btnRedirectView({id: '${e.id}', url: '${e.url}${e.id_opcionales}'})" style="background: none; border: none; padding: 0; text-align: left;">
                    <li class="notification-item ${leido}">
                        <i class="bi bi-check-circle ${alert}"></i>
                        <div>
                        <h4>${e.titulo}</h4>
                        <p>${e.descripcion}</p>
                        <p>${e.tiempo}</p>
                        </div>
                    </li>
                </button>
    
                    <li>
                        <hr class="dropdown-divider">
                    </li>
                `;
              break;
            case "3":
              alert = "text-danger";
              contenedor_notificaciones.innerHTML += `
              <button onclick="btnRedirectView({id: '${e.id}', url: '${e.url}${e.id_opcionales}'})" style="background: none; border: none; padding: 0; text-align: left;">
                    <li class="notification-item ${leido}">
                        <i class="bi bi-x-circle ${alert}"></i>
                        <div>
                        <h4>${e.titulo}</h4>
                        <p>${e.descripcion}</p>
                        <p>${e.tiempo}</p>
                        </div>
                    </li>
              </button>
    
                    <li>
                        <hr class="dropdown-divider">
                    </li>
                `;
              break;
          }
          i++;
        } else {
          return;
        }
      });
    });
}
notification();

setInterval(() => {
  notification();
}, 30000);

function btnRedirectView(notificacion) {
  event.preventDefault();
  let form = new FormData();
  form.append("id_notificacion", notificacion.id);

  fetch(`${GET_BASE_URL()}/notificacion_leida`, { method: "POST", body: form })
    .then((response) => response.json())
    .then((data) => {
      if (data) {
        window.location.replace(`${GET_BASE_URL()}/${notificacion.url}`);
      }
    });
}
