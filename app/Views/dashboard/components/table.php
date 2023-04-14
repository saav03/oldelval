<style>
    td:hover{
        cursor:pointer;
    }
</style>
<div class="col-12">
    <div class="card recent-sales overflow-auto">

        <div class="filter">
            <a class="icon" href="#" data-bs-toggle="dropdown"><i class="bi bi-three-dots"></i></a>
            <ul class="dropdown-menu dropdown-menu-end dropdown-menu-arrow">
                <li class="dropdown-header text-start">
                    <h6>Filter</h6>
                </li>

                <li><a class="dropdown-item" href="#">Today</a></li>
                <li><a class="dropdown-item" href="#">This Month</a></li>
                <li><a class="dropdown-item" href="#">This Year</a></li>
            </ul>
        </div>

        <div class="card-body">
            <h5 class="card-title">Tus Observaciones <span>| pendientes de cierre</span></h5>

            <table class="table table-borderless datatable">
                <thead>
                    <tr>
                        <th scope="col">ID#</th>
                        <th scope="col">Responsable</th>
                        <th scope="col">Tipo Auditoria</th>
                        <th scope="col">Respuesta</th>
                        <th scope="col">Estado</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <th scope="row"><a href="#">#2457</a></th>
                        <td>Brian Zapata</td>
                        <td>Medio Ambiente</td>
                        <td>Resultado OK</td>
                        <td><span class="badge bg-success">Aprobado</span></td>
                    </tr>
                    <tr>
                        <th scope="row"><a href="#">#2147</a></th>
                        <td>Lucas Kesser</td>
                        <td>Transporte pesado</td>
                        <td>Esperando checklist vehicular</td>
                        <td><span class="badge bg-warning">Pendiente</span></td>
                    </tr>
                    <tr>
                        <th scope="row"><a href="#">#2049</a></th>
                        <td>Andrea Lagos</td>
                        <td>LOD1</td>
                        <td>Cumple</td>
                        <td><span class="badge bg-success">Aprobado</span></td>
                    </tr>
                    <tr>
                        <th scope="row"><a href="#">#2644</a></th>
                        <td>Agustin Gadriel</td>
                        <td>Levantamiento de cargas</td>
                        <td>No cumple con las reglas establecidas</td>
                        <td><span class="badge bg-danger">Rechazado</span></td>
                    </tr>
                    <tr>
                        <th scope="row"><a href="#">#2644</a></th>
                        <td>Andrea Lagos</td>
                        <td>LOD1</td>
                        <td>Cumple</td>
                        <td><span class="badge bg-success">Aprobado</span></td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</div>