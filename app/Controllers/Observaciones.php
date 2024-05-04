<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use CodeIgniter\Controller;
use App\Models;
use SebastianBergmann\Template\Template;
use Config\Validation;
use DateTime;

class Observaciones extends BaseController
{

    public function __construct()
    {
        $this->session = \Config\Services::session();
        $this->validation = \Config\Services::validation();
        $this->model_logs = model('Model_logs');
    }

    public function index() {
        return template('observaciones/pendientes/index');
    }

}

?>