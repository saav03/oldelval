<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use CodeIgniter\Controller;
use App\Models;

class Menu extends BaseController
{
    public function __construct()
    {
        $this->session = \Config\Services::session();
        $this->validation = \Config\Services::validation();
        $this->view = \Config\Services::renderer();
        $this->model_grupo = model('Model_grupo');
        $this->model_menu = model('Model_menu');
        $this->model_permisos = model('Model_permisos');
        $this->model_general = model('Model_general');
        date_default_timezone_set('America/Argentina/Buenos_Aires');
    }
    public function index()
    {
        if (!session()->get('isLogin')) {
            return redirect()->to('login');
        } else {
            if (acceso(1)) { //1 administracion_blister
                return view('partials/header') . view('menu/index') . view('partials/footer');
            } else { //agregar un mensaje en set_flashdata e indicar que no tiene los permisos para tal accion
                session()->setFlashdata('no_access', 'Acceso a URL o Módulo denegado');
                return view('partials/header') . view('dashboard/index') . view('partials/footer');
            }
        }
    }

    public function viewAdd()
    {
        if (acceso(1)) {
            $data['allMenues'] = $this->model_menu->getAll();
            $data['possible_menu_parents'] = $this->model_menu->getPosibleParents();
            $data['permisos'] = $this->model_permisos->getAll();
            $data['permisos_parents'] = $this->model_permisos->getPosibleParents();
            $data['inputs'] = $this->view->setData($data)->render('menu/inputs');
            return template('menu/add', $data);
        } else {
            session()->setFlashdata('login_error', 'No tiene los permisos necesarios para ingresar en esa URL');
            return redirect()->to('login');
        }
    }

    public function add()
    {
        /*  ENDPOINT */
        $fecha_hora = date('Y-m-d H:i:s');
        if (acceso(1)) {
            /*POSTED DATA*/
            $data_to_submit = array(
                'nombre' => $this->request->getPost("name"),
                'is_heading' => $this->request->getPost("separator") == 1 ? 1 : 0,
                'id_menu_padre' => $this->request->getPost("submenu"),
                'orden' => $this->request->getPost("order"),
                'ruta' => $this->request->getPost("path"),
                'icono' => $this->request->getPost("icon")
            );
            $result = $this->validar($data_to_submit);
            extract($result);
            if ($exito) {
                $data_to_submit['usuario'] = session()->get('id_usuario');
                $data_to_submit['fecha_hora'] = $fecha_hora;
                $id = $this->model_menu->insertAndReorder($data_to_submit);

                /* == Permisos == */
                if ($this->request->getPost("permiso") == -1) { // Se agrega nuevo permiso
                    $data_permiso = [
                        'nombre' => $this->request->getPost("nombre_permiso"),
                        'id_permiso_padre' => $this->request->getPost("subpermiso"),
                        'orden' => $this->request->getPost("orden_permiso"),
                        'tipo_modulo' => $this->request->getPost("tipo_modulo"),
                    ];
                    $id_permiso = $this->model_permisos->insertAndReorder($data_permiso);
                } else {
                    $id_permiso = $this->request->getPost("permiso");
                }

                /* Agrego la nueva relación entre menú y permiso */
                $this->model_permisos->vincularPermisoMenu($id_permiso, $id);

                echo $id;
            } else {
                http_response_code(400);
                echo json_encode($errores);
            }
        } else {
            http_response_code(403);
            $response = ['No se poseen los permisos para acceder a esta accion.'];
            echo json_encode($response);
        }
    }

    public function edit()
    {
        $fecha_hora = date('Y-m-d H:i:s');
        if (acceso(1)) {
            $data_to_edit = array(
                'nombre' => $this->request->getPost("name"),
                'is_heading' => $this->request->getPost("separator") == 1 ? 1 : 0,
                'id_menu_padre' => $this->request->getPost("submenu"),
                'orden' => $this->request->getPost("order"),
                'ruta' => $this->request->getPost("path"),
                'icono' => $this->request->getPost("icon")
            );
            $result = $this->validar($data_to_edit);
            extract($result);
            if ($exito) {
                $id = $this->request->getPost("id");
                $data_to_edit['usuario'] = session()->get('id_usuario');
                $data_to_edit['fecha_hora'] = $fecha_hora;

                $this->model_menu->updateAndReorder($data_to_edit, $id);

                /* == Permisos == */
                /* Esto se comentó por que la vdd no sé si estaría bien agregar un nuevo permiso desde la edición */
                /* if ($this->request->getPost("permiso") == -1) { // Se agrega nuevo permiso
                    $data_permiso = [
                        'nombre' => $this->request->getPost("nombre_permiso"),
                        'id_permiso_padre' => $this->request->getPost("subpermiso"),
                        'orden' => $this->request->getPost("orden_permiso"),
                        'tipo_modulo' => $this->request->getPost("tipo_modulo"),
                    ];
                    $id_permiso = $this->model_permisos->insertAndReorder($data_permiso);
                } else {
                    $id_permiso = $this->request->getPost("permiso");
                } */

                $id_permiso = $this->request->getPost("permiso");

                /* $data = [
                    'id_permiso' => $id_permiso,
                    'id_menu' => $id,
                ]; */

                /* Edit la relación entre menú y permiso */
                $this->model_permisos->editVinculoPermisoMenu($id_permiso, $id);

                echo $id;
            } else {
                http_response_code(400);
                echo json_encode($errores);
            }
        }
    }

    protected function validar($data_to_submit)
    {
        /*  THIS FUNCTION DOES ALL THE VALIDATIONS AND RETURNS THOSE RESULTS TO CALLER SO IT CAN SUBMIT/EDIT */
        $resultado = true;
        $errores = [];
        if ($this->validation->run($data_to_submit, 'validation_menu') === FALSE) {
            $this->response->setStatusCode(400);
            $resultado = $this->validation->getErrors();
        } else {
            /* EXTRA SECURITY, COMPATIBILITY */
            $tiene_icono = !is_null($data_to_submit['icono']) && $data_to_submit['icono'] != '';
            if ($data_to_submit['id_menu_padre'] > 0 && $tiene_icono) {
                $errores = ['Un Submenu no puede tener icono'];
                $resultado = false;
            } else if ($data_to_submit['is_heading'] == 1 && $tiene_icono) {
                $errores = ['Un Separador no puede tener icono'];
                $resultado = false;
            }
        }
        $result = array(
            'exito' => $resultado,
            'errores' => $errores
        );
        return $result;
    }
    public function getPaged($offset = NULL, $tamanioPagina = NULL)
    {

        if (is_null($offset) && is_null($tamanioPagina)) {
            //Busca el numero total
            $response = $this->model_menu->getAllPaged($offset, $tamanioPagina);
            echo (int)$response[0]['cantidad'];
        } else {
            //Busca los registros
            if ((is_numeric($offset) && $offset >= 0) && (is_numeric($tamanioPagina) && $tamanioPagina > 0)) {
                $response = $this->model_menu->getAllPaged($offset, $tamanioPagina);
            } else {
                http_response_code(400);
                $response = ["Parametros no validos"];
            }
            echo json_encode($response);
        }
    }

    public function view($id)
    {

        if (acceso(1)) { //1 administracion_blister
            $data['allMenues'] = $this->model_menu->getAll();
            $data['possible_menu_parents'] = $this->model_menu->getPosibleParents();
            $menu = $this->model_menu->get($id);
            $permiso = $this->model_permisos->getRelMenuPermiso($id);
            $padre = [];
            if ($menu['id_menu_padre'] != 0) {
                //Tiene submenu
                $padre = $this->model_menu->get($menu['id_menu_padre']);
            }
            $data['menu'] = $menu;
            $data['permiso'] = $permiso;
            $data['padre'] = $padre;
            $data['permisos'] = $this->model_permisos->getAll();
            $data['permisos_parents'] = $this->model_permisos->getPosibleParents();
            $data['inputs'] = $this->view->setData($data)->render('menu/inputs');
            return template('menu/view', $data);
        } else { //agregar un mensaje en set_flashdata e indicar que no tiene los permisos para tal accion
            session()->setFlashdata('no_access', 'Acceso a URL o Módulo denegado');
            return template('dashboard/index');
        }
    }
    public function activation()
    {
        $id = $this->request->getPost("id");
        $activar = $this->request->getPost("active");
        $datos = array(
            'activo' => $activar
        );
        if ($activar == 0) {
            //Se desactivo
            $this->model_menu->deactivateAndReorder($datos, $id);
        } else {
            $this->model_general->updateG('gg_menu', $id, $datos);
        }
        echo $id;
    }
}
