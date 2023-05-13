<style>
    fieldset.inputs-menu input:disabled,
    fieldset.inputs-menu select:disabled,
    fieldset.inputs-menu textarea:disabled {
        background-color: white;
        cursor: no-drop;
    }

    .input-group-text {
        border-radius: 5px 0px 0px 5px;
    }

    #icon_preview {
        height: 38px;
        border-radius: 0px 5px 5px 0px;

    }
</style>
<div class="container">
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Menu</h1>
    </div>
    <div class="row">
        <div class="col-xl-12 col-lg-12">
            <div class="card shadow mb-4">
                <div class="card-header py-3 d-flex justify-content-between align-items-center">
                    <h5 class="m-0 font-weight-bold text-primary">Ver - Datos del Item # <?= $menu['id'] . " : " . ($padre ? $padre['nombre'] . " -> : " : "") . $menu['nombre'] ?></h6>
                        <div>
                            <?php if ($menu['activo'] == 1) { ?>
                                <button type="button" name="buttonEdit" id="buttonEdit" class="btn btn-outline-primary">Editar<i class="fas fa-pencil-alt"></i></button>
                                <button type="button" name="buttonActivation" id="buttonActivation" class="btn btn-outline-danger">Desactivar<i class="fas fa-ban"></i></button>
                            <?php } else { ?>
                                <button type="button" name="buttonActivation" id="buttonActivation" class="btn btn-outline-success">Actrivara<i class="fas fa-check"></i></button>
                            <?php } ?>
                        </div>
                </div>
                <div class="card-body">
                    <form method="post" id="editMenuForm" action="<?= base_url('api/menu/edit') ?>">
                        <fieldset id="inputs-menu" class="inputs-menu p-3" disabled>
                            <?= $inputs ?>
                        </fieldset>
                        <div class="row justify-content-end">
                            <input type="submit" name="buttonSave" id="buttonSave" class="btn btn-primary mr-3" style="visibility: hidden;" disabled value="Guardar Cambios">
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
<script src="<?= base_url('/assets/js/domManipulation.js') ?>"></script>
<script src="<?= base_url('/assets/js/editForms.js') ?>"></script>

<script>
    /*  MAKE FORM EDITABLE */

    /* INITIAL BEHAVIOUR */
    let me = <?= json_encode($menu) ?>;
    let my_parent = me.id_menu_padre;
    previous_submenu_value = my_parent;
    if (separator.checked) {
        iconManipulation();
        toggleReadOnly(path);
    }
    if (previous_submenu_value > 0) {
        if (!separator.checked) {
            iconManipulation();
        }
    }

    const initialIcon = [...document.getElementById('icon_preview').childNodes];

    function setInitialIcon() {
        removeChildren(document.getElementById('icon_preview'));
        initialIcon.forEach(node => document.getElementById('icon_preview').appendChild(node));
    }
    const initialOrderOptions = menuElems.filter(menu => menu.id_menu_padre == my_parent);
    console.log(initialOrderOptions);
    if (initialOrderOptions.length > 0)
        firstOrderNumber = Number.parseInt(initialOrderOptions[0].orden);
    else
        firstOrderNumber = 1;
    removeChildren(orderSelect);
    genericOrderOpt.value = firstOrderNumber;
    orderSelect.appendChild(genericOrderOpt);
    initialOrderOptions.forEach(opt => {
        if (opt.id != me.id) {
            let optionDom = el('option', {
                value: (Number.parseInt(opt.orden) + 1)
            }, opt.nombre);
            if (opt.orden == (me.orden - 1)) {
                optionDom.selected = true;
            }
            orderSelect.appendChild(optionDom);
        }
    });

    /* EDIT COMPONENTS */
    const buttonEdit = document.getElementById('buttonEdit');
    const buttonSave = document.getElementById('buttonSave');
    const buttonActivation = document.getElementById('buttonActivation');
    if (buttonEdit) {
        const fieldset = document.getElementById('inputs-menu');
        triggerEdition(buttonEdit, fieldset, buttonSave, buttonActivation);
        buttonEdit.onclick = () => {
            if (buttonSave.style.visibility == 'hidden') {
                //volver a los valores iniciales
                setInitialIcon();
                if (initialOrderOptions.length > 0)
                    firstOrderNumber = Number.parseInt(initialOrderOptions[0].orden);
                else
                    firstOrderNumber = 1;
                removeChildren(orderSelect);
                genericOrderOpt.value = firstOrderNumber;
                orderSelect.appendChild(genericOrderOpt);
                initialOrderOptions.forEach(opt => {
                    if (opt.id != me.id) {
                        let optionDom = el('option', {
                            value: (Number.parseInt(opt.orden) + 1)
                        }, opt.nombre);
                        if (opt.orden == (me.orden - 1)) {
                            optionDom.selected = true;
                        }
                        orderSelect.appendChild(optionDom);
                    }
                });
            }
        }
    }
    /*  SAVE CHANGES */
    const formulario = document.getElementById('editMenuForm');
    formulario.appendChild(el('input', {
        type: 'hidden',
        name: 'id',
        value: <?= $menu['id'] ?>
    }));
    const confirmButtonData = {
        text: "Volver a Menúes",
        url: GET_BASE_URL() + "menu",
        appendResult: false
    };
    const cancelButtonData = {
        text: "Ver Cambios",
        url: GET_BASE_URL() + "menu",
        appendResult: true
    };
    addSubmitHandler(formulario, "buttonSave", confirmButtonData, cancelButtonData);

    /* (DE)ACTIVATION */
    const entityData = {
        'id': me.id,
        'isActive': me.activo == 1
    }
    addActivateHandler(buttonActivation, entityData, GET_BASE_URL() + '/menu/activation', confirmButtonData, cancelButtonData);
</script>