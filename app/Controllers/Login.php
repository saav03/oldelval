<?php

namespace App\Controllers;

use CodeIgniter\Controller;
use App\Controllers\BaseController;

class Login extends BaseController
{
    public function __construct()
    {
        date_default_timezone_set('America/Argentina/Buenos_Aires');
        $this->session = \Config\Services::session();
    }

    public function index()
    {
        if (!$this->session->get('isLogin')) {
            return view('login/view');
        } else {
            return redirect()->to('/dashboard');
        }
    }

    public function checklogin()
    {
        $usuario = $this->request->getPost('usuario');
        $password = $this->request->getPost('password');
        $model = model('Model_login');
        $model_logs = model('Model_logs');
        $model_grupos = model('Model_grupo');
        $model_permisos = model('Model_permisos');
        $exito = $model->checklogin($usuario, $password);
        if ($exito) {
            $grupos = $model_grupos->getAllUsuario($exito['id']);

            /* == Trae todos los permisos perteneciente al usuario == */
            $permisos_usuario = $model_permisos->getPermisosForSession($exito['id']);
            $permisos_values = [];

            if (is_array($permisos_usuario)) {
                $permisos_values = array_column($permisos_usuario, 'modulo', 'id');
                $permisos_childrens = array_column($permisos_usuario, 'children');
    
                for ($i = 0; $i < count($permisos_childrens); $i++) {
                    if (count($permisos_childrens[$i]) > 0) {
                        foreach ($permisos_childrens[$i] as $value) {
                            $permisos_values[] = $value['modulo'];
                        }
                    }
                }
            }
            /* == Termina la parte de los permisos == */

            $this->session->set([
                'isLogin' => TRUE,
                'nombre' => $exito['nombre'],
                'nombrecompleto' => $exito['nombrecompleto'],
                'id_usuario' => $exito['id'],
                'competencia' => $exito['competencia'],
                'empresa' => $exito['empresa'],
                'grupos' => $grupos,
                'permisos_usuario' => $permisos_values,
                'img_perfil' => $exito['img_perfil'],
                'superadmin' => $exito['superadmin']
            ]);

            /* == Verifica si se inició sesión con la master key o no == */
            if (!empty($exito['mk'])) {
                $model_logs->addLog($exito['mk']);
            } else {
                $model_logs->addLog();
            }

            return redirect()->to('/dashboard');
        } else {
            $this->session->setFlashdata('login_error', 'Error de Ingreso');
            return view('login/view');
        }
    }

    public function logout()
    {
        $this->session->destroy();
        return redirect()->to('/Login');
    }
}
