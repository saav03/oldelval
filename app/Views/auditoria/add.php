<link rel="stylesheet" href="<?= base_url('assets/css/auditorias/add.css') ?>">
<link rel="stylesheet" href="<?= base_url('assets/css/auditorias/aud_gral.css') ?>">

<div class="container">
    <div class="row">
        <div class="blister-title-container">
            <h4 class="blister-title">Nueva Auditoría</h4>
        </div>
    </div>

    <div class="card" style="border: 1px solid #f6f6f6;box-shadow: 0px 0 30px rgb(179 179 179 / 53%);">
        <div class="card-header subtitle" style="font-weight: 600; letter-spacing: 1.5px; text-align: center;">
            Seleccione que tipo de Auditoría va a generar
        </div>

        <div class="card-body">
            <div class="row">
                <div class="col-xs-12 col-md-4"></div>
                <div class="col-xs-12 col-md-4 text-center">
                    <label class="sz_inp fw-semibold">Seleccione el Tipo</label>
                    <select name="" id="" class="form-select sz_inp text-center" onchange="seleccionarTipoAuditoria(this)">
                        <option value="">-- Seleccione --</option>
                        <option value="0">Checklist Control</option>
                        <option value="1">Checklist Vehicular</option>
                    </select>
                </div>
                <div class="col-xs-12 col-md-4"></div>
            </div>
        </div>
    </div>

    <section id="checklist_control" style="display: block;">
        <?= view('auditoria/control/add_control'); ?>
    </section>

    <section id="checklist_vehicular" style="display: none;">
        <?= view('auditoria/vehicular/add_vehicular'); ?>
    </section>

</div>

<script src="<?= base_url("assets/js/auditorias/submit_add_planilla.js") ?>"></script>

<script>
    let tipo_obs_vehicular = <?= json_encode($tipo_obs_vehicular); ?>
</script>

<script>
    /* == Definición de Constantes == */
    const checklist_control = document.getElementById('checklist_control');
    const checklist_vehicular = document.getElementById('checklist_vehicular');

    function seleccionarTipoAuditoria(e) {
        let id_tipo_aud = e.value;
        if (id_tipo_aud == 0) {
            checklist_control.style.display = 'block';
            checklist_vehicular.style.display = 'none';
        } else {
            checklist_vehicular.style.display = 'block';
            checklist_control.style.display = 'none';
        }
    }
</script>

<script>
    const btn_switches = document.querySelectorAll('.btn_switches');

    for (let i = 0; i < btn_switches.length; i++) {

        btn_switches[i].addEventListener('click', () => {
            for (let j = 0; j < btn_switches.length; j++) {
                btn_switches[j].classList.remove('active');
            }
            btn_switches[i].classList.add('active')
        });
    }
</script>