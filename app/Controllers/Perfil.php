<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use CodeIgniter\Controller;
use App\Models;
use SebastianBergmann\Template\Template;

class Perfil extends BaseController
{

    public function __construct()
    {
        $this->session = \Config\Services::session();
        $this->model_logs = model('Model_logs');
        $this->model_usuario = model('Model_usuario');
        $this->model_grupo = model('Model_grupo');
        $this->model_permisos = model('Model_permisos');
        $this->model_general = model('Model_general');
    }

    public function index()
    {
        if (!$this->session->get('isLogin')) {
            return redirect()->to('login');
        } else {
            $data['datos_basicos'] = $this->model_usuario->getDatos(session()->get('id_usuario'));
            return template('perfil/view', $data);
        }
    }

    public function view($id)
    {
        if ($id == session()->get('id_usuario')) { // Si el ID usuario es igual al usuario logueado, entonces..
            $data['datos_basicos'] = $this->model_usuario->getDatos($id);
            /* Los permisos tienen que pertenecer al usuario */
            $data['permisos'] = $this->model_permisos->getAllPermisos();
            $data['permisos_usuario'] = $this->model_permisos->getAllPermisosUser($id, 'LEFT', true);
            $data['grupos'] = $this->model_grupo->getAll();
            $data['grupos_usuario'] = $this->model_grupo->getGruposUsuario($id);
            return template('perfil/view', $data);
        } else {
            return redirect()->to('/');
        }
    }

    public function getPagedPerfil($offset = NULL, $tamanioPagina = NULL)
    {
        if ((is_numeric($offset) && $offset >= 0) && (is_numeric($tamanioPagina) && $tamanioPagina > 0)) {
            $response = $this->model_logs->getAllPagedPerfil($offset, $tamanioPagina);
        } else {
            if (is_null($offset) && is_null($tamanioPagina)) {
                $response = $this->model_logs->getAllPagedPerfil($offset, $tamanioPagina, true);
                $response = (int)$response[0]['cantidad'];
            } else {
                http_response_code(400);
                $response = [
                    'error' => 400,
                    'message' => "Parametros no validos"
                ];
            }
        }
        echo json_encode($response);
    }

    public function editarPermisosUsuario()
    {

        echo '<pre>';
        var_dump($_POST);
        echo '</pre>';
        exit;

        $id_usuario = $this->request->getPost('id_usuario');

        $grupos = $this->request->getPost('grupos');

        $permisos_checkeds = $this->request->getPost('checkedIds');
        $permisos_usuario = $this->model_permisos->getAllPermisosUser($id_usuario, 'INNER');

        if (is_array($permisos_usuario)) {
            $permisos_ids = array_column($permisos_usuario, 'id');
            $permisos_childrens = array_column($permisos_usuario, 'children');

            for ($i = 0; $i < count($permisos_childrens); $i++) {

                if (count($permisos_childrens[$i]) > 0) {
                    foreach ($permisos_childrens[$i] as $value) {
                        $permisos_ids[] = $value['id'];
                    }
                }
            }

            $permisos_unchecked = array_diff($permisos_ids, $permisos_checkeds);

            // === Desactivar Permisos ===
            if (count($permisos_unchecked) > 0) {
                foreach ($permisos_unchecked as $p) {
                    /* ==  Acá se van a modificar el estado de los permisos a 0 del usuario ==  */
                    $this->model_permisos->changeStatePermisoUser($id_usuario, $p, 0);
                }
            }

            // == Agregar o Actualizar Permisos ==
            if (count($permisos_checkeds) > 0) {
                foreach ($permisos_checkeds as $p) {
                    if (in_array($p, $permisos_ids)) {
                        /* ==  Acá se van a modificar el estado de los permisos a 1 del usuario ==  */
                        $this->model_permisos->changeStatePermisoUser($id_usuario, $p, 1);
                    } else {
                        /* == En caso de ser nuevos permisos para el usuario, se insertan == */
                        $datos = [
                            'id_usuario' => $id_usuario,
                            'id_permiso' => $p,
                            'estado' => '1',
                        ];

                        echo '<pre>';
                        var_dump($datos);
                        echo '</pre>';
                        $this->model_general->insertG('gg_rel_usuario_permiso', $datos);
                    }
                }
            }
        } else {
            $permisos_checked = $this->request->getPost('permisos_checked');
            foreach ($permisos_checked as $p) {
                $datos = [
                    'id_usuario' => $id_usuario,
                    'id_permiso' => $p,
                    'estado' => '1',
                ];
                $this->model_general->insertG('gg_rel_usuario_permiso', $datos);
            }
        }
    }
}
