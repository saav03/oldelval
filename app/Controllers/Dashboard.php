<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use CodeIgniter\Controller;


//defined('BASEPATH') or exit('No direct script access allowed');

class Dashboard extends BaseController
{

    public function __construct()
    {
        //parent::__construct();
        $this->session = \Config\Services::session();
    }

    public function index()
    {
        if (!$this->session->get('isLogin')) {
            return redirect()->to('login');
        } else {
            // $movimiento = new Movimiento();
            // $data['actividad_reciente'] = $movimiento->recentActivity(); 
            return template('dashboard/view'/*, $data*/);
            // return view('partials/header') . view('dashboard/view') . view('partials/footer');
        }
    }
}
