<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use CodeIgniter\Controller;
use App\Models;

class Grupo extends BaseController
{
    public function __construct()
    {
        $this->session = \Config\Services::session();
        $this->validation = \Config\Services::validation();
        $this->model_grupo = model('Model_grupo');
        $this->model_permisos = model('Model_permisos');
        $this->model_menu = model('Model_menu');
    }


    public function index()
    {
        if (is_null((session()->get('isLogin')))) {
            return redirect()->to('login');
        } else {
            if (acceso(1)) { //1 administracion_blister
                return view('partials/header') . view('grupos/index') . view('partials/footer');
            } else { //agregar un mensaje en set_flashdata e indicar que no tiene los permisos para tal accion
                return view('partials/header') . view('dashboard/index') . view('partials/footer');
            }
        }
    }
    public function getPaged($offset = NULL, $tamanioPagina = NULL)
    {
        if ((is_numeric($offset) && $offset >= 0) && (is_numeric($tamanioPagina) && $tamanioPagina > 0)) {
            $response = $this->model_grupo->getAllPaged($offset, $tamanioPagina);
        } else {
            if (is_null($offset) && is_null($tamanioPagina)) {
                $response = $this->model_grupo->getAllPaged($offset, $tamanioPagina, true);
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

    public function add()
    {
        $grupo = [
            'nombre' => $this->request->getPost('nombre'),
            'id_usuario'    => session()->get('id_usuario')
        ];
        $this->validation->setRules(
            [
                'nombre' => [
                    'label' => "Nombre",
                    'rules' => 'required|is_unique[gg_grupos.nombre]',
                    'errors' => [
                        'required' => 'Ingrese un nombre.',
                        'is_unique' => 'Ya existe un grupo con ese nombre'
                    ]
                ]
            ]
        ); //'nombre', 'Grupo', 'required|is_unique[gg_grupos.nombre]'
        if ($this->validation->run($grupo) === FALSE) {
            $this->response->setStatusCode(400);
            $results = $this->validation->getErrors();
        } else {

            $results = $this->model_grupo->add($grupo);
            if ($results['status']) {
                $last_id = $results['last_id'];
                newMov(4, 1, $last_id); //Movimiento
            }
        }
        echo json_encode($results);
    }
    
    public function view($id)
    {
        if (acceso(1)) { //1 administracion_blister
            $data['grupo'] = $this->model_grupo->get($id);
            // $data['permisos'] = $this->model_menu->getAll(false);
            $id_grupo = [];
            $id_grupo[] = $id;
            $data['permisos'] = $this->model_permisos->getAllPermisosGroup($id_grupo, 'LEFT', true);
            return template('grupos/view', $data);
        }else{
            session()->setFlashdata('login_error','No tiene los permisos necesarios para ingresar en esa URL');
            return redirect()->to('login');
        }
    }
}
