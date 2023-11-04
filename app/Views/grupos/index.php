<title>OLDELVAL - Grupos</title>
<div class="container ">
  <div class="row">
    <div class="blister-title-container">
      <h4 class="blister-title">Grupos</h4>
    </div>
    <div class="row">
      <div class="col-md-8"></div>
      <div class="col-md-4" style="text-align: end;"> <button class="btn_modify" id="generar" type="button" data-bs-toggle="collapse" data-bs-target="#collapseExample" aria-expanded="false" aria-controls="collapseExample">Generar Nuevo Grupo</button></div>
    </div>
  </div>

  <br>

  <div class="collapse mt-2" id="collapseExample">
    <div class="card card-body">
      <div class="col-md-4 col-xs-12">
        <form action="" method="POST" id="form-add">
          <div class="form-group">
            <label class="mb-1 fw-semibold" for="nombre">Nombre de Grupo:</label>
            <input type="text" class="form-control sz_inp" id="nombre" name="nombre" placeholder="Ingrese un nombre...">
            <button class="btn_modify mt-2" id="btn_add">Agregar Grupo</button>
          </div>
        </form>
      </div>
    </div>
  </div>

  <div class="card">
    <div class="card-header" style="background: white; padding: 16px; font-weight: 600; letter-spacing: 1.5px;">
      Listado total de los grupos
    </div>
    <div class="card-body">
      <div id="groups"></div>
    </div>
  </div>
</div>
<script>
  const getElement = (pageNumber, pageSize) => {
    return fetch(`${GET_BASE_URL()}/api/groups/get/${pageNumber}/${pageSize}`, {
      method: 'POST',
    });
  }
  const getElementTotales = () => {
    return fetch(`${GET_BASE_URL()}/api/groups/getTotal`, {
      method: 'POST',
    });
  }

  const tableOptions = {
    tableCSSClass: 'table_oldelval',
    pageSize: 10,
    getDataCallback: getElement,
    totalDataCallback: getElementTotales,
    tableHeader: ["ID", "Nombre", "Cargado Por", "Fecha/Hora Carga", "Activo"],
    tableCells: ["id", "nombre", "usuario", "fecha_hora", {
      key: (row) => {
        let botonDesactivar;
        let div;
        let grandRow;
        let id = row['id'];
        let nombre = row['nombre'];
        botonDesactivar = el('button.btn-desactivar', {
          'type': 'button',
          'title': 'Desactivar',
        }, el("i.fas fa-ban"));
        div = el('div.col-xs-12 col-md-12');
        grandRow = el('div.row');
        botonDesactivar.onclick = () => {
          botonDesactivar.disabled = true;
          botonDesactivar.classList.add('disabled');
          showConfirmationButton().then((result) => {
            if (result.isConfirmed) {
              desactivarGrupo(id)
                .done(function(data) {
                  successAlert("Se ha desactivado el Grupo.").then((result) => {
                    if (result.isConfirmed) {
                      window.location.replace("<?= base_url('grupos') ?>");
                    }
                  })
                })
                .fail((err, textStatus, xhr) => {
                  let errors = JSON.parse(err.responseText);
                  errors = errors.join(". ");
                  showErrorAlert(null, errors);
                  botonDesactivar.disabled = false;
                  botonDesactivar.classList.remove('disabled');
                })
            } else {
              canceledActionAlert();
              botonDesactivar.disabled = false;
              botonDesactivar.classList.remove('disabled');
            }
          });
        }
        mount(div, botonDesactivar);
        mount(grandRow, div);

        return grandRow;
      },
      noClickableRow: true
    }],
    pager: {
      totalEntitiesText: "Cantidad de Resultados",
    },
    clickableRow: {
      url: `${GET_BASE_URL()}/Grupo/view/`,
      rowId: 'id',
    },
  }
  dynTable = new DynamicTableCellsEditable(document.getElementById("groups"), tableOptions);
  dynTable.init();


  function desactivarGrupo(id) {
    return $.ajax({
      type: "POST",
      data: new FormData(),
      url: "<?= base_url('Grupo/desactivarGrupo/') ?>" + id,
      processData: false,
      contentType: false,
      beforeSend: function() {
        loadingAlert();
      },
    });
  }
</script>

<script>
  document.getElementById('generar').addEventListener('click', () => {
    document.getElementById('nombre').focus();
    document.getElementById('generar').innerHTML = document.getElementById('generar').innerHTML == "Generar Nuevo Grupo" ? "Cancelar" : "Generar Nuevo Grupo";
    if (document.getElementById('generar').classList.contains('btn_modify')) {
      document.getElementById('generar').classList.remove('btn_modify');
      document.getElementById('generar').classList.add('btn_mod_danger');
    } else {
      document.getElementById('generar').classList.remove('btn_mod_danger');
      document.getElementById('generar').classList.add('btn_modify');
    }
  });

  document.getElementById('btn_add').addEventListener('click', (e) => {
    e.preventDefault();
    showConfirmationButton().then((result) => {
      if (result.isConfirmed) {
        cargarGrupo()
          .done(function(data) {
            successAlert("Se ha registrado su solicitud.").then((result) => {
              if (result.isConfirmed) {
                window.location.replace("<?= base_url('/grupo') ?>");
              }
            })
          })
          .fail((err, textStatus, xhr) => {
            let errors = Object.values(JSON.parse(err.responseText));
            errors = errors.join('. ');
            showErrorAlert(null, errors);
            buttonSubmitUsuario.disabled = false;
            buttonSubmitUsuario.classList.remove('disabled');
          })
      } else {
        canceledActionAlert();
        buttonSubmitUsuario.disabled = false;
        buttonSubmitUsuario.classList.remove('disabled');
      }
    });
  });

  function cargarGrupo() {
    return $.ajax({
      type: "POST",
      url: "<?= base_url('addNewGroup') ?>",
      data: new FormData(document.getElementById('form-add')),
      processData: false,
      contentType: false,
      beforeSend: function() {
        loadingAlert();
      },
    });
  }
</script>