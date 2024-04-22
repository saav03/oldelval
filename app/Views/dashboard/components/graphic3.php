<?php if (vista_access('index_auditoria')) : ?>

<div class="card">
  <div class="card-body pb-0">
    <h5 class="card-title">Total Inspecciones <span>| Este mes</span></h5>

    <div id="trafficChart" style="min-height: 500px;" class="echart"></div>
    <script>
      let control = <?= json_encode($inspeccion_graphic_cake['control']); ?>;
      let vehicular = <?= json_encode($inspeccion_graphic_cake['vehicular']); ?>;
      let obra = <?= json_encode($inspeccion_graphic_cake['obra']); ?>;
      let auditoria = <?= json_encode($inspeccion_graphic_cake['auditoria']); ?>;

      document.addEventListener("DOMContentLoaded", () => {
        echarts.init(document.querySelector("#trafficChart")).setOption({
          tooltip: {
            trigger: 'item'
          },
          legend: {
            top: '5%',
            left: 'center'
          },
          series: [{
            name: 'Inspecciones',
            type: 'pie',
            radius: ['40%', '70%'],
            itemStyle: {
              borderRadius: 12,
              borderColor: '#fff',
              borderWidth: 3
            },
            avoidLabelOverlap: false,
            label: {
              show: false,
              position: 'center'
            },
            emphasis: {
              label: {
                show: true,
                fontSize: '18',
                fontWeight: 'bold'
              }
            },
            labelLine: {
              show: false
            },
            data: [{
                value: control,
                name: 'Inspección de Control'
              },
              {
                value: vehicular,
                name: 'Inspección Vehicular'
              },
              {
                value: obra,
                name: 'Inspección de Obra'
              },
              {
                value: auditoria,
                name: 'Inspección de Auditoría'
              },
            ]
          }]
        });
      });
    </script>

  </div>
</div>
<?php endif; ?>
