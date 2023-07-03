<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use CodeIgniter\Controller;
use Config\Validation;

class Usuario extends BaseController
{

    public function __construct()
    {
        $this->session = \Config\Services::session();
        $this->validation = \Config\Services::validation();
        $this->model_general = model('Model_general');
        $this->model_usuario = model('Model_usuario');
        $this->model_grupo = model('Model_grupo');
        $this->model_menu = model('Model_menu');
        $this->model_permisos = model('Model_permisos');
        $this->model_logs = model('Model_logs');
        if (!$this->session->get('isLogin')) {
            return redirect()->to('login');
        }
        date_default_timezone_set('America/Argentina/Buenos_Aires');
    }

    public function index()
    {
        if (!$this->session->get('isLogin')) {
            return redirect()->to('login');
        } else {
            // if (acceso(1,'index_usuario')) {
            return template('usuario/index');
            // } else {
            // return redirect()->to('/');
            // }
        }
    }

    public function view($id)
    {
        if (acceso(1)) { // 1 admin
            $data['datos_basicos'] = $this->model_usuario->getDatos($id);
            /* Los permisos tienen que pertenecer al usuario */
            $data['permisos'] = $this->model_permisos->getAllPermisos();
            $data['permisos_usuario'] = $this->model_permisos->getAllPermisosUser($id, 'LEFT', true);
            $data['grupos'] = $this->model_grupo->getAll();
            $data['grupos_usuario'] = $this->model_grupo->getGruposUsuario($id);
            $data['last_mod'] = $this->model_general->get_mov($id);

            return template('usuario/view', $data);
        } else {
            return redirect()->to('/');
        }
    }

    public function getPagedUsuario($id_user, $offset = NULL, $tamanioPagina = NULL)
    {
        if ((is_numeric($offset) && $offset >= 0) && (is_numeric($tamanioPagina) && $tamanioPagina > 0)) {
            $response = $this->model_logs->getAllPagedUsuario($offset, $tamanioPagina, $id_user);
        } else {
            if (is_null($offset) && is_null($tamanioPagina)) {
                $response = $this->model_logs->getAllPagedUsuario($offset, $tamanioPagina, $id_user, true);
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

    public function getPaged($offset = NULL, $tamanioPagina = NULL)
    {
        if ((is_numeric($offset) && $offset >= 0) && (is_numeric($tamanioPagina) && $tamanioPagina > 0)) {
            $response = $this->model_usuario->getAllPaged($offset, $tamanioPagina);
        } else {
            if (is_null($offset) && is_null($tamanioPagina)) {
                $response = $this->model_usuario->getAllPaged($offset, $tamanioPagina, true);
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

    public function addView()
    {
        if (!$this->session->get('isLogin')) {
            return redirect()->to('login');
        } else {

            if (acceso('add_usuario')) {
                $data['grupos'] = $this->model_grupo->getAll();
                $data['empresas'] = $this->model_general->getAll('empresas');
                $data['permisos'] = $this->model_permisos->getAllPermisos();
                // $data['permisos_no_group'] = $this->model_menu->getAllPermisos();
                return view('partials/header') . view('usuario/add', $data) . view('partials/footer');
            } else {
                return redirect()->to('/');
            }
        }
    }



    public function getAllPermisosUser()
    {
        $id_usuario = $_POST['id_usuario'];
        $permisos_user = $this->model_permisos->getAllPermisosUser($id_usuario);
        echo json_encode($permisos_user);
    }
    public function getAllPermisosGroup()
    {
        $grupos = $_POST['grupos'];
        $permisos = $this->model_permisos->getAllPermisosGroup($grupos, 'LEFT', true);
        echo json_encode($permisos);
    }

    /**
     * Trae los permisos pertenecientes al usuario y también del grupo que está asignado
     * Se utiliza en 
     */
    public function getAllPermisosGroupAndUsers()
    {
        $grupos = $_POST['grupos'];
        $id_usuario = $_POST['id_usuario'];
        $permisos = $this->model_permisos->getAllPermisosGroupAndUsers($grupos, $id_usuario);
        echo json_encode($permisos);
    }

    public function add()
    {
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

        $permisos = $this->request->getPost('permisos');
        $grupos = $this->request->getPost('grupos');

        $datos  = [
            'correo'    => $this->request->getPost('correo'),
            'clave'     => $this->request->getPost('clave'),
            'dni'     => $this->request->getPost('dni'),
            'nombre'    => $this->request->getPost('nombre'),
            'apellido'    => $this->request->getPost('apellido'),
            'fecha_nacimiento'    => $this->request->getPost('fec_nac'),
            'empresa'    => $this->request->getPost('empresa'),
            'localidad'    => $this->request->getPost('localidad'),
            'imagen_perfil'    => $foto,
            'telefono'    => $this->request->getPost('telefono') ? $this->request->getPost('telefono') : 0,
            'id_usuario_creador' => session()->get('id_usuario')
        ];
        $datos_perfil = [
            'fecha_creacion' => date('Y-m-d H:i:s'),
            'fecha_first_login' => '',
            'fecha_modificacion_perfil' => '',
            'panel_emergente' => 1, //1 activo, predeterminado
            'estilo' => 0   //claro-oscuro, 0 claro predeterminado
        ];

        if ($this->validation->run($datos, 'validation_add') === FALSE) {
            $this->response->setStatusCode(400);
            $results = $this->validation->getErrors();
        } else {
            $datos['clave'] = password_hash($datos['clave'], PASSWORD_DEFAULT);
            $results = $this->model_usuario->add($datos);
            if ($results['status']) {
                $last_id = $results['last_id'];
                $datos_perfil['id_usuario'] = $last_id;
                $this->model_usuario->addDatosPerfil($datos_perfil);
                $results = $this->model_grupo->vincularUsuario($grupos, $last_id);
                newMov(1, 1, $last_id); //Movimiento

                // == Agregar Permisos al usuario ==
                /* Si tiene permisos se agregan acá */
                if (!empty($permisos)) {
                    $this->model_permisos->vincularPermisoUsuario($permisos, $last_id);
                }
            }
        }
        echo json_encode($results);
    }

    /**
     * Envía las credenciales de usuario una vez que está creado
     */
    public function sendCredentials()
    {
        $helper = new Helper();

        # Envío de credenciales 
        $id_usuario = $this->request->getPost('id_usuario'); 
        $emails[] = $this->request->getPost('correo');
        $datos = [
            'correo' => $this->request->getPost('correo'),
            'mensaje' => $this->request->getPost('mensaje'),
            'clave' => $this->request->getPost('clave'),
        ];
        $helper->sendMail($datos, 'Credenciales de Ingreso', '', 'emails/credentials/credentials', $emails);

        newMov(8, 6, $id_usuario, 'Envío de Credenciales'); //Movimiento
    }

    public function editarPermisosUsuario()
    {

        $id_usuario = $this->request->getPost('id_usuario');

        /* Grupos */
        $grupos = $this->request->getPost('grupos');

        $grupos_new = $this->gestionarGrupoUsuario($grupos, 1, $id_usuario);
        $grupos_edit = $this->gestionarGrupoUsuario($grupos, 0, $id_usuario);

        /* == Agrego los grupos == */
        if (!empty($grupos_new)) {
            foreach ($grupos_new as $g) {
                $datos = [
                    'id_usuario' => $id_usuario,
                    'id_grupo' => $g,
                    'estado' => '1',
                ];
                $this->model_general->insertG('usuario_rel_usuario_grupo', $datos);
            }
        }

        if (!empty($grupos_edit)) {
            foreach ($grupos_edit as $g) {
                $this->model_usuario->changeState($g, $id_usuario);
            }
        }

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
                    // $this->model_permisos->changeStatePermisoUser($id_usuario, $p, 0);
                    $this->model_permisos->deleteOnePermissionUser($id_usuario, $p);
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
                        $this->model_general->insertG('gg_rel_usuario_permiso', $datos);
                    }
                }
            }
        } else {
            foreach ($permisos_checkeds as $p) {
                $datos = [
                    'id_usuario' => $id_usuario,
                    'id_permiso' => $p,
                    'estado' => '1',
                ];
                $this->model_general->insertG('gg_rel_usuario_permiso', $datos);
            }
        }
    }

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
            'superadmin' => $this->request->getPost('superadmin') ? 1 : 0,
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
                newMov(1, 3, $id_usuario, 'Datos del Usuario'); //Movimiento
            }
        }

        if ($this->request->getPost('password') != '') {
            $clave = $this->request->getPost('password');
            $datos['clave'] = password_hash($clave, PASSWORD_DEFAULT);
            $this->model_usuario->changePassword($datos['clave'], $id_usuario);
        }
    }

    protected function gestionarGrupoUsuario($grupos, $tipo, $id_usuario)
    {
        $respuesta = '';
        $id_grupos = join(",", $grupos);
        $grupos_separados = explode(',', $id_grupos);

        foreach ($grupos_separados as $value) {
            $grupos_post[] = $value;
        }

        $grupos_old = $this->model_grupo->getGruposUsuario($id_usuario);
        $id_grupos_old = [];

        for ($i = 0; $i < count($grupos_old); $i++) {
            $id_grupos_old[] = $grupos_old[$i]['id_grupo'];
        }

        if ($tipo == 1) { // Se agregan nuevos grupos
            $respuesta = array_diff($grupos_post, $id_grupos_old);
        } else { // Se edita el estado de los grupos ya existentes
            $respuesta = array_diff($id_grupos_old, $grupos_post);
        }

        return $respuesta;
    }

    public function changeStateUser($id_usuario)
    {
        $this->model_usuario->changeStateUser($id_usuario);
        newMov(1, 3, $id_usuario, 'Actualizar Estado'); // Movimiento Activar o Desactivar

    }


    public function ingresos()
    {
        if (!session()->get('isLogin')) {
            return redirect()->to('login');
        } else {
            if (acceso(1)) { //1 administracion_blister
                return view('partials/header') . view('logs/index') . view('partials/footer');
            } else { //agregar un mensaje en set_flashdata e indicar que no tiene los permisos para tal accion
                return redirect()->to('/');
            }
        }
    }

    public function getPagedAccess($offset = NULL, $tamanioPagina = NULL)
    {
        if ((is_numeric($offset) && $offset >= 0) && (is_numeric($tamanioPagina) && $tamanioPagina > 0)) {
            $response = $this->model_logs->getAllPagedAccess($offset, $tamanioPagina);
        } else {
            if (is_null($offset) && is_null($tamanioPagina)) {
                $response = $this->model_logs->getAllPagedAccess($offset, $tamanioPagina, true);
                $response = (int)$response[0]['cantidad'];
            } else {
                $this->response->setStatusCode(400);
                $response = [
                    'error' => 400,
                    'message' => "Parametros no validos"
                ];
            }
        }
        echo json_encode($response);
    }
}
