<?php
$model = model('App\Models\oldelval\Model_menu');
$this->session = session();

$grupos = $this->session->get('grupos');
$id_grupos = array_column($grupos, 'id');

$id_usuario = $this->session->get('id_usuario');
$superadmin = $this->session->get('superadmin');
// $id_grupo = $this->session->get('id_grupo');
$menu = $model->getPermisosForMenu($id_usuario, $superadmin);
$menu = $model->drawMenu($menu);

?>

<ul class="sidebar-nav" id="sidebar-nav">
    <?= $menu ?>
</ul>