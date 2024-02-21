<div class="col-12">
  <div class="card">
    <div class="filter">
      <a class="icon" href="#" data-bs-toggle="dropdown"><i class="bi bi-three-dots"></i></a>
      <ul class="dropdown-menu dropdown-menu-end dropdown-menu-arrow">
        <li class="dropdown-header text-start">
          <h6>Filtros</h6>
        </li>

        <li><button class="dropdown-item" onclick="filterTodayObsGraphic('today')">Hoy</button></li>
        <li><button class="dropdown-item" onclick="filterTodayObsGraphic('month')">Este mes</button></li>
        <li><button class="dropdown-item" onclick="filterTodayObsGraphic('year')">Este año</button></li>
      </ul>
    </div>

    <div class="card-body">
      <h5 class="card-title">Reporte Observaciones | Tarjeta M.A.S<span> / Este Mes</span></h5>

      <style>
        .label_aspecto {
          margin-right: 2px;
          width: 100%;
          max-width: 450px;
          border: 1px solid #cbcbcb;
          padding: 4px;
          font-weight: 500;
          letter-spacing: 0.5px;
          border-radius: 6px;
          cursor: pointer;
          text-align: center;
        }

        .seguridad_input:checked+.label_aspecto {
          box-shadow: inset 0 0 10px rgb(0 0 0 / 9%);
        }

        .ambiental_input:checked+.label_aspecto {
          box-shadow: inset 0 0 10px rgb(0 0 0 / 9%);
        }
      </style>

      <div class="d-flex justify-content-center">
        <input id="riesgo_seguridad_salud" type="radio" class="seguridad_input" name="riesgo_selected" value="1" checked hidden>
        <label id="label_riesgo_seguridad" for="riesgo_seguridad_salud" class="label_aspecto" onclick="obs_graphic_significancia(1)">Observaciones con Riesgo en Seguridad y Salud</label>
        <input id="riesgo_impacto_ambiental" type="radio" class="ambiental_input" name="riesgo_selected" value="2" hidden>
        <label id="label_impacto_ambiental" for="riesgo_impacto_ambiental" class="label_aspecto" onclick="obs_graphic_significancia(2)">Observaciones con Impacto Ambiental</label>
      </div>

      <!-- Line Chart -->
      <div id="reportsChart"></div>

      <script>
        let month = <?= json_encode($month); ?>;
        let all_months = <?= json_encode($all_months); ?>;
        
        let aceptable_y = <?= json_encode($obs_graphic_year['aceptable']); ?>;
        let moderado_y = <?= json_encode($obs_graphic_year['moderado']); ?>;
        let significativo_y = <?= json_encode($obs_graphic_year['significativo']); ?>;
        let intolerable_y = <?= json_encode($obs_graphic_year['intolerable']); ?>;
        
        let aceptable = <?= json_encode($obs_graphic_months['aceptable']); ?>;
        let moderado = <?= json_encode($obs_graphic_months['moderado']); ?>;
        let significativo = <?= json_encode($obs_graphic_months['significativo']); ?>;
        let intolerable = <?= json_encode($obs_graphic_months['intolerable']); ?>;

        let baja = <?= json_encode($obs_graphic_months['baja']); ?>;
        let media = <?= json_encode($obs_graphic_months['media']); ?>;
        let alta = <?= json_encode($obs_graphic_months['alta']); ?>;
        let muy_alta = <?= json_encode($obs_graphic_months['muy_alta']); ?>;

        let baja_y = <?= json_encode($obs_graphic_year['baja']); ?>;
        let media_y = <?= json_encode($obs_graphic_year['media']); ?>;
        let alta_y = <?= json_encode($obs_graphic_year['alta']); ?>;
        let muy_alta_y = <?= json_encode($obs_graphic_year['muy_alta']); ?>;

        document.addEventListener("DOMContentLoaded", () => {
          new ApexCharts(document.querySelector("#reportsChart"), {
            series: [{
                name: 'Aceptable',
                data: aceptable,
              }, {
                name: 'Moderado',
                data: moderado
              }, {
                name: 'Significativo',
                data: significativo
              },
              {
                name: 'Intolerable',
                data: intolerable
              }
            ],
            chart: {
              height: 350,
              type: 'area',
              toolbar: {
                show: false
              },
            },
            markers: {
              size: 4
            },
            colors: ["#A9D8DF", "#66b572", "#E4CA4B", "#DD8F60"],
            fill: {
              type: "gradient",
              gradient: {
                shadeIntensity: 1,
                opacityFrom: 0.3,
                opacityTo: 0.4,
                stops: [0, 90, 100]
              }
            },
            dataLabels: {
              enabled: false
            },
            stroke: {
              width: 2
            },
            xaxis: {
              type: 'date',
              categories: month
            },
            tooltip: {
              x: {
                format: 'dd/MM/yy HH:mm'
              },
            }
          }).render();
        });
      </script>
      <!-- End Line Chart -->

    </div>

  </div>
</div>