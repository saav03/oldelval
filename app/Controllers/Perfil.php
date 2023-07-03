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
        $this->validation = \Config\Services::validation();
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

    /**
     * Este método se aplica cuando el usuario está editando sus propios datos
     * (Es más limitado que el edit en el controlador de Usuario porque hay datos que el usuario mismo no puede cambiar)
     */
    public function edit()
    {
        $id_usuario = $this->request->getPost('id_usuario');

        // == Foto Perfil ==
        if (empty($_FILES["profileImage"]["name"])) {
        }
        $nombreFoto = time() . '-' . $_FILES["profileImage"]["name"];
        $target_dir = "uploads/fotosPerfil/";
        $target_file = $target_dir . basename($nombreFoto);
        $foto = "";
        if (move_uploaded_file($_FILES["profileImage"]["tmp_name"], $target_file)) {
            $foto = $nombreFoto;
        }

        /* Datos del Usuario */
        $datos = [
            'correo' => $this->request->getPost('correo'),
            'nombre' => $this->request->getPost('nombre'),
            'apellido' => $this->request->getPost('apellido'),
            'telefono' => $this->request->getPost('telefono'),
            'dni' => $this->request->getPost('dni'),
            'fecha_nacimiento' => $this->request->getPost('fecha_nacimiento'),
            'competencia' => $this->request->getPost('competencia'),
            'usuario' => $this->request->getPost('usuario'),
            'localidad' => $this->request->getPost('localidad'),
        ];

        if ($foto != '') {
            $datos['imagen_perfil'] = $foto;
        }

        if ($this->validation->run($datos, 'validation_edit_user') === FALSE) {
            $this->response->setStatusCode(400);
            $results = $this->validation->getErrors();
        } else {
            $results = $this->model_usuario->edit($datos, $id_usuario);
            if ($results['status']) {
                $last_id = $results['last_id'];
                newMov(1, 3, $id_usuario, 'Editó su perfil'); //Movimiento
            }
        }

        if ($this->request->getPost('password') != '') {
            $clave = $this->request->getPost('password');
            $datos['clave'] = password_hash($clave, PASSWORD_DEFAULT);
            $this->model_usuario->changePassword($datos['clave'], $id_usuario);
        }
    }
}
