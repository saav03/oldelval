<div class="container">
    <div class="blister-title-container">
        <h4 class="blister-title">Tabla de movimientos</h4>
    </div>
    <div class="card">
        <div class="card-body">
            <div id="table"></div>
        </div>
    </div>
</div>
<script>
    const getElemento = (pageNumber, pageSize) => {
        return fetch(`${GET_BASE_URL()}/api/movimientos/get/${pageNumber}/${pageSize}`, {
            method: 'POST',
        });
    }
    const getCantidad = () => {
        return fetch(`${GET_BASE_URL()}/api/movimientos/getTotal`, {
            method: 'POST',
        });
    }

    const tableOptions = {
        tableCSSClass: 'table_oldelval',
        pageSize: 20,
        getDataCallback: getElemento,
        totalDataCallback: getCantidad,
        tableHeader: ["ID", "Usuario", "Modulo", "Accion", "ID Afectado", "Comentario", "Fecha Accion"],
        tableCells: ["id", "usuario", "modulo", "accion", "id_afectado", {
            key: (row) => {
                if (row['comentario']) {
                    return row['comentario'];
                } else {
                    return "N/A";
                }
            }
        }, "fecha_accion"],
        pager: {
            totalEntitiesText: "Cantidad de Resultados",
        },
        clickableRow: false,
    }
    dynTable = new DynamicTableCellsEditable(document.getElementById("table"), tableOptions);
    dynTable.init();
</script>