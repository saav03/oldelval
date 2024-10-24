<div class="container_primary">
    <div class="card c-perfil">
        <div class="card-header text-center header-perfil">
            <h5 style="margin: 0px;"><b>Tabla de ingresos</b></h5>
        </div>
        <div class="card-body">
            <div class="row border-perfil-top">
                <div class="col-4">
                    <p class="text-muted p-perfil fw-semibold">Primer ingreso</p>
                </div>
                <div class="col-8"><input type="input" class="form-control input-perfil" value="<?= $first_login ?>" readonly></div>
            </div>
        </div>
        <div class="card-header text-center header-perfil">
            <h5 style="margin: 0px;"><b>Historial</b></h5>
        </div>
        <div class="card-body">
            <div class="row border-perfil-top">
                <div id="ingresos"></div>
            </div>
        </div>
        <div class="card-footer text-muted text-center header-perfil">
            Ultima Modificación: <?= $fecha_modificacion ? $fecha_modificacion : "-- -- --"; ?>hs
        </div>
    </div>
</div>


<script>
    const getIngreso = (pageNumber, pageSize) => {
        return fetch(`${GET_BASE_URL()}/api/ingresos/get/${pageNumber}/${pageSize}`, {
            method: 'POST',
        });
    }
    const getIngresosTotales = () => {
        return fetch(`${GET_BASE_URL()}/api/ingresos/getTotal`, {
            method: 'POST',
        });
    }

    const tableOptions = {
        tableCSSClass: 'table table-bordered table-hover table-striped action-table',
        pageSize: 10,
        getDataCallback: getIngreso,
        totalDataCallback: getIngresosTotales,
        tableHeader: ["ID", "Usuario","Fecha/Hora"],
        tableCells: ["id", "usuario","fecha_hora"],
        pager: {
            totalEntitiesText: "Cantidad de Resultados",
        }, clickableRow:false,
    }
    dynTable = new DynamicTableCellsEditable(document.getElementById("ingresos"), tableOptions);
    dynTable.init();
</script>