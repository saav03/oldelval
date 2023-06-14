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
                    <div class="p-3 pt-1 text-center">
                        <div class="btn-group btn-group-toggle" style="width: 80%;" role="group" aria-label="">
                            <input id="control" type="radio" name="seleccion_checklist" class="btn-check btn_gral_check" value="1" autocomplete="off">
                            <label class="btn blanco btnsToggle riesgos" for="control">CheckList Control</label>

                            <input id="vehicular" type="radio" name="seleccion_checklist" class="btn-check btn_gral_check" value="2" autocomplete="off">
                            <label class="btn blanco btnsToggle riesgos" for="vehicular">CheckList Vehicular</label>
                        </div>
                    </div>
                </div>
                <div class="col-xs-12 col-md-4"></div>
            </div>
        </div>
    </div>

    <section id="checklist_control" style="display: none;">
        <?= view('auditoria/control/add_control'); ?>
    </section>

    <section id="checklist_vehicular" style="display: none;">
        <?= view('auditoria/vehicular/add_vehicular'); ?>
    </section>

</div>

<script src="<?= base_url("assets/js/auditorias/submit_add_planilla.js") ?>"></script>


<script>
    const seleccion_checklist = document.getElementsByName('seleccion_checklist');
    const checklist_control = document.getElementById('checklist_control');
    const checklist_vehicular = document.getElementById('checklist_vehicular');
    for (let i = 0; i < seleccion_checklist.length; i++) {
        seleccion_checklist[i].addEventListener('change', e => {

            let input_tipo = seleccion_checklist[i].getAttribute('id');

            if (input_tipo == 'control') {
                checklist_control.style.display = 'block';
                checklist_vehicular.style.display = 'none';
            } else {
                checklist_vehicular.style.display = 'block';
                checklist_control.style.display = 'none';
            }
        })
    }
</script>

<script>
    let tipo_obs_vehicular = <?= json_encode($tipo_obs_vehicular); ?>
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