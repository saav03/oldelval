<?php 

function setNotificacion($datos) {
    $model_notificacion = model('Model_notificacion');
    $model_notificacion->add($datos);
}

?>