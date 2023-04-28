<div class="container">
    <h4>Listado de ingresos</h4>
    <hr>
    <div id="ingresos"></div>

</div>


<script>
    const getIngreso = (pageNumber, pageSize) => {
        return fetch(`${GET_BASE_URL()}/api/access/get/${pageNumber}/${pageSize}`, {
            method: 'POST',
        });
    }
    const getIngresosTotales = () => {
        return fetch(`${GET_BASE_URL()}/api/access/getTotal`, {
            method: 'POST',
        });
    }

    const tableOptions = {
        tableCSSClass: 'table table-bordered table-hover table-striped action-table',
        pageSize: 20,
        getDataCallback: getIngreso,
        totalDataCallback: getIngresosTotales,
        tableHeader: ["ID", "Usuario", "Llave Maestra", "Fecha/Hora"],
        tableCells: ["id", "usuario", "mk", "fecha_hora"],
        pager: {
            totalEntitiesText: "Cantidad de Resultados",
        },
        clickableRow: false,
    }
    dynTable = new DynamicTableCellsEditable(document.getElementById("ingresos"), tableOptions);
    dynTable.init();
</script>