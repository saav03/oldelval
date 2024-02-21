<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use CodeIgniter\Controller;
use Config\Validation;

class Sistema extends BaseController
{
    public function __construct()
    {
        date_default_timezone_set('America/Argentina/Buenos_Aires');
        $this->session = \Config\Services::session();
        $this->validation = \Config\Services::validation();
        $this->model_general = model('Model_general');
        if (!$this->session->get('isLogin')) {
            return redirect()->to('login');
        }
    }

    public function view()
    {
        $data['maintenance'] = $this->model_general->getMaintenance();
        if (acceso(1)) {
            return template('sistema/view', $data);
        } else {
            return redirect()->to('dashboard');
        }
    }

    /**
     * Setea un nuevo horario y fecha para el mantenimiento, y habilita el cartel claro
     */
    public function store()
    {
        $data = [
            'status' => 1,
            'hora' => $this->request->getPost('hora'),
            'fecha' => $this->request->getPost('fecha'),
        ];
        $this->model_general->updateG('maintenance', 1, $data);
        echo json_encode(true);
    }

    public function removeMaintenance()
    {
        $data = [
            'status' => null,
            'hora' => null,
            'fecha' => null,
        ];
        $this->model_general->updateG('maintenance', 1, $data);
        echo json_encode($data);
    }
}
