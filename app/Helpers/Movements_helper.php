<?php


/**
 * 
 */
function newMov($id_modulo,$accion,$id_afectado = null,$id_input = null) //$id_input es para la edicion, identifica que input fue modificado
{
    $model_movimiento = model('Model_movimiento');
    $datos = [
        'id_usuario' => session()->get('id_usuario'),
        'id_modulo' => $id_modulo,
        'id_accion' => $accion,
        'id_afectado' => $id_afectado,
        'comentario' => $id_input
    ];
    $model_movimiento->add($datos);
}