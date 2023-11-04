<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use CodeIgniter\Controller;

class Permisos extends BaseController
{
    public function __construct()
    {
        $this->session = \Config\Services::session();
        $this->validation = \Config\Services::validation();
        $this->model_grupo = model('Model_grupo');
        $this->model_general = model('Model_general');
        $this->model_permisos = model('Model_permisos');
        $this->model_menu = model('Model_menu');
        date_default_timezone_set('America/Argentina/Buenos_Aires');
    }

    public function index()
    {
        if (!session()->get('isLogin')) {
            return redirect()->to('login');
        } else {
            if (acceso(1)) { //1 administracion_blister
                $data['permisos'] = $this->model_permisos->getAll();
                $data['permisos_parents'] = $this->model_permisos->getPosibleParents();
                return view('partials/header') . view('permisos/index', $data) . view('partials/footer');
            } else { //agregar un mensaje en set_flashdata e indicar que no tiene los permisos para tal accion
                session()->setFlashdata('no_access', 'Acceso a URL o Módulo denegado');
                return view('partials/header') . view('dashboard/index') . view('partials/footer');
            }
        }
    }

    public function getDataPermiso($id_permiso)
    {
        $data = $this->model_permisos->getDataPermiso($id_permiso);
        echo json_encode($data);
    }

    public function add()
    {
        if (!session()->get('isLogin')) {
            return redirect()->to('login');
        } else {
            $data['permisos'] = $this->model_permisos->getAll();
            $data['permisos_parents'] = $this->model_permisos->getPosibleParents();
            return template('permisos/add', $data);
        }
    }

    public function edit($id_permiso)
    {
        if (!session()->get('isLogin')) {
            return redirect()->to('login');
        } else {
            $data['allPermisos'] = $this->model_permisos->getAll();
            $permiso = $this->model_permisos->getDataPermiso($id_permiso);
            $padre = [];
            if ($permiso['id_permiso_padre'] != 0) {
                //Tiene submenu
                $padre = $this->model_permisos->getDataPermiso($permiso['id_permiso_padre']);
            }
            $data['permiso'] = $permiso;
            $data['padre'] = $padre;
            $data['permisos'] = $this->model_permisos->getAll();
            $data['permisos_parents'] = $this->model_permisos->getPosibleParents();
            return template('permisos/edit', $data);
        }
    }

    public function editPermission()
    {

        $fecha_hora = date('Y-m-d H:i:s');
        // if (acceso(1)) {
        $data_to_edit = array(
            'nombre' => $this->request->getPost("nombre"),
            'id_permiso_padre' => $this->request->getPost("subpermiso"),
            'orden' => $this->request->getPost("orden_permiso"),
            'tipo_modulo' => $this->request->getPost("tipo_modulo"),
        );

        $result = $this->verificacion($data_to_edit, 'validation_permission');

        extract($result);

        if ($exito) {
            $id = $this->request->getPost("id");
            $data_to_edit['usuario'] = session()->get('id_usuario');
            $data_to_edit['fecha_carga'] = $fecha_hora;

            $this->model_permisos->updateAndReorder($data_to_edit, $id);

            echo $id;
        } else {
            http_response_code(400);
            echo json_encode($errores);
        }
        // }
    }

    public function submit()
    {
        $resultado = "";
        $data_to_submit = array(
            'nombre' => $this->request->getPost("nombre"),
            'modulo' => $this->request->getPost("modulo"),
            'id_permiso_padre' => $this->request->getPost("subpermiso"),
            'orden' => $this->request->getPost("orden_permiso"),
            'tipo_modulo' => $this->request->getPost("tipo_modulo"),
        );

        if ($this->validation->run($data_to_submit, 'validation_permiso') === FALSE) {
            $this->response->setStatusCode(400);
            $resultado = $this->validation->getErrors();
        } else {
            $data_to_submit['usuario'] = session()->get('id_usuario');
            $data_to_submit['fecha_carga'] = date('Y-m-d H:i:s');
            $id = $this->model_permisos->insertAndReorder($data_to_submit);
            $resultado = $id;
            newMov(5, 1, null, 'Permiso: ' . $this->request->getPost('nombre')); //Movimiento
        }
        echo json_encode($resultado);
    }

    /* public function edit()
    {

        $id_permiso = $this->request->getPost("id_permiso");

        $data_to_edit = array(
            'nombre' => $this->request->getPost("nombre"),
            'id_permiso_padre' => $this->request->getPost("subpermiso"),
            'orden' => $this->request->getPost("orden_permiso"),
            'tipo_modulo' => $this->request->getPost("tipo_modulo"),
        );

        if ($this->validation->run($data_to_edit, 'validation_permiso') === FALSE) {
            $this->response->setStatusCode(400);
            $resultado = $this->validation->getErrors();
        } else {
            $data_to_edit['usuario'] = session()->get('id_usuario');
            $data_to_edit['fecha_carga'] = date('Y-m-d H:i:s');
            $id = $this->model_permisos->updateAndReorder($data_to_edit, $id_permiso);
            $resultado = $id;
            newMov(5, 3, null, 'Permiso: ' . $this->request->getPost('nombre')); //Movimiento
        }

        echo json_encode($resultado);

    } */

    public function getPaged($offset = NULL, $tamanioPagina = NULL)
    {
        if (is_null($offset) && is_null($tamanioPagina)) {
            $response = $this->model_permisos->getAllPaged($offset, $tamanioPagina);
            echo (int)$response[0]['cantidad'];
        } else {
            if ((is_numeric($offset) && $offset >= 0) && (is_numeric($tamanioPagina) && $tamanioPagina > 0)) {
                $response = $this->model_permisos->getAllPaged($offset, $tamanioPagina, true);
            } else {
                http_response_code(400);
                $response = [
                    'error' => 400,
                    'message' => "Parametros no validos"
                ];
            }
            echo json_encode($response);
        }
    }

    /**
     * NO FUNCIONA AUN | HAY QUE HACER UN PAR DE MODIFICACIONES
     */
    public function addGroupPermission()
    {

        $id_grupo = [];
        $id_grupo[] = $this->request->getPost('id_grupo');
        $permisos_grupo = $this->model_permisos->getAllPermisosGroup($id_grupo, 'INNER', true);

        if (is_array($permisos_grupo)) {
            $permisos_ids = array_column($permisos_grupo, 'id');
            $permisos_childrens = array_column($permisos_grupo, 'children');

            for ($i = 0; $i < count($permisos_childrens); $i++) {

                if (count($permisos_childrens[$i]) > 0) {
                    foreach ($permisos_childrens[$i] as $value) {
                        $permisos_ids[] = $value['id'];
                    }
                }
            }

            $permisos_checked = $this->request->getPost('permisos_checked');

            $permisos_unchecked = array_diff($permisos_ids, $permisos_checked);

            // === Desactivar Permisos ===
            if (count($permisos_unchecked) > 0) {
                foreach ($permisos_unchecked as $p) {
                    // $this->model_permisos->changeState($id_grupo[0], $p, 0);
                }

                // $this->model_permisos->deletePermissionUser($permisos_unchecked, $id_grupo[0]);
            }

            // == Agregar o Actualizar Permisos ==
            if (count($permisos_checked) > 0) {
                foreach ($permisos_checked as $p) {
                    if (in_array($p, $permisos_ids)) {
                        $this->model_permisos->changeState($id_grupo[0], $p, 1);
                    } else {
                        $datos = [
                            'id_grupo' => $id_grupo[0],
                            'id_permiso' => $p,
                            'estado' => '1',
                        ];
                        // $this->model_general->insertG('gg_rel_permiso_grupo', $datos);
                    }
                }

                /*
                ==
                NO FUNCIONA ESTO ACA TE QUEDASTE, TENES QUE INSERTAR PERMISOS A CADA
                USUARIO PERO NO SE TIENEN QUE DUPLICAR LOS MISMOS.
                LO DEJASTE ACA PORQUE HAY Q SUBIR OLDELVAL
                ==
                */
                $this->addGroupPermissionToUser($id_grupo[0], $permisos_checked);
                exit;
            }
        } else {
            $permisos_checked = $this->request->getPost('permisos_checked');
            foreach ($permisos_checked as $p) {
                $datos = [
                    'id_grupo' => $id_grupo,
                    'id_permiso' => $p,
                    'estado' => '1',
                ];
                $this->model_general->insertG('gg_rel_permiso_grupo', $datos);
            }
        }
    }

    public function disable()
    {
        $id = $this->request->getPost('id_permiso');
        $result = $this->model_permisos->disablePermit($id);

        if ($result['status']) {
            newMov(5, 4, null, 'Permiso: ' . $this->request->getPost('nombre')); //Movimiento
        }
        return json_encode($result);
    }

    public function enable()
    {
        $id = $this->request->getPost('id_permiso');
        $result = $this->model_permisos->enablePermit($id);

        if ($result['status']) {
            newMov(5, 4, null, 'Permiso: ' . $this->request->getPost('nombre')); //Movimiento
        }
        return json_encode($result);
    }

    /**
     * Agrega permisos nuevos a cada usuario que corresponden al grupo asignado en parámetros
     */
    protected function addGroupPermissionToUser($id_grupo, $permisos)
    {
        $new_permissions = [];
        $allUsers = $this->model_grupo->getAllUsersFromGroup($id_grupo);

        foreach ($allUsers as $u) {
            foreach ($permisos as $p) {
                $new_permissions[] = [
                    'id_usuario' => $u['id_usuario'],
                    'id_permiso' => $p,
                ];
            }
        }

        echo '<pre>';
        var_dump($new_permissions);
        echo '</pre>';
    }

    protected function verificacion($datos, $nombre_validacion)
    {
        $verificacion = [];
        if ($this->validation->run($datos, $nombre_validacion) === FALSE) {
            $this->response->setStatusCode(400);
            $verificacion['exito'] = false;
            $verificacion['errores'] = $this->validation->getErrors();
        } else {
            $verificacion['exito'] = true;
        }
        return $verificacion;
    }
}
