<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use CodeIgniter\Controller;
use Config\Validation;

class Movimiento extends BaseController
{

    public function __construct()
    {
        $this->session = \Config\Services::session();
        $this->validation = \Config\Services::validation();
        $this->model_movimiento = model('Model_movimiento');

    }

    public function index()
    {
        if (!session()->get('isLogin')) {
            return redirect()->to('login');
        } else {
            if(acceso(1)){ //1 administracion_blister
                return view('partials/header') . view('movimientos/index') . view('partials/footer');
            }else{//agregar un mensaje en set_flashdata e indicar que no tiene los permisos para tal accion
                return view('partials/header') . view('dashboard/index') . view('partials/footer');
            }
        }
    }

    public function getPaged($offset = NULL, $tamanioPagina = NULL)
    {
        if ((is_numeric($offset) && $offset >= 0) && (is_numeric($tamanioPagina) && $tamanioPagina > 0)) {
            $response = $this->model_movimiento->getAllPaged($offset, $tamanioPagina);
        } else {
            if (is_null($offset) && is_null($tamanioPagina)) {
                $response = $this->model_movimiento->getAllPaged($offset, $tamanioPagina, true);
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