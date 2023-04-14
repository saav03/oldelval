<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use CodeIgniter\Controller;
use App\Models;
use Config\Validation;

class Auditorias extends BaseController
{
    public function __construct()
    {
        $this->session = \Config\Services::session();
        $this->validation = \Config\Services::validation();
        $this->model_logs = model('Model_logs');
        $this->model_usuario = model('Model_usuario');
        $this->model_auditorias = model('Model_auditorias');
        $this->model_general = model('Model_general');
    }

    public function add()
    {
        if (!$this->session->get('isLogin')) {
            return redirect()->to('login');
        } else {
            return template('auditoria/add');
        }
    }

    public function addPlanilla()
    {
        if (!$this->session->get('isLogin')) {
            return redirect()->to('login');
        } else {
            return template('auditoria/addPlanilla');
        }
    }
}
