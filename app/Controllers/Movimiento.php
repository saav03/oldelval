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
        $this->model_general = model('Model_general');
    }

    public function index()
    {
        if (!session()->get('isLogin')) {
            return redirect()->to('login');
        } else {
            if (acceso(1)) { //1 administracion_blister
                return view('partials/header') . view('movimientos/index') . view('partials/footer');
            } else { //agregar un mensaje en set_flashdata e indicar que no tiene los permisos para tal accion
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

    public function recentActivity()
    {
        $actividad_reciente = $this->model_movimiento->getRecentActivity();
        $actividades = [];

        foreach ($actividad_reciente as $key => $act) {
            switch ($act['id_modulo']) {
                case '1': // Usuario
                    $user = $this->model_general->get('usuario', $act['id_afectado']);
                    switch ($act['id_accion']) {
                        case '1': // Alta/Nuevo
                            $txt = 'Alta de Usuario #' . $act['id_afectado'] . ' ' . $user['nombre'] . ' ' . $user['apellido'] . ' por' . ' ' . $act['nombre_usuario'];
                            break;
                        case '2': // Baja/Desactivar
                            $txt = 'Baja de Usuario #' . $act['id_afectado'] . ' ' . $user['nombre'] . ' ' . $user['apellido'] . ' por' . ' ' . $act['nombre_usuario'];
                            break;
                        case '3': // Modificacion/Editar
                            $txt = 'Edición de Usuario #' . $act['id_afectado'] . ' ' . $user['nombre'] . ' ' . $user['apellido'] . ' por' . ' ' . $act['nombre_usuario'];
                            break;
                        case '4': // Baja/Eliminar
                            $txt = 'Eliminación de Usuario #' . $act['id_afectado'] . ' ' . $user['nombre'] . ' ' . $user['apellido'] . ' por' . ' ' . $act['nombre_usuario'];
                            break;
                        case '5': // Alta/Activar
                            $txt = 'Habilitación de Usuario #' . $act['id_afectado'] . ' ' . $user['nombre'] . ' ' . $user['apellido'] . ' por' . ' ' . $act['nombre_usuario'];
                            break;
                        case '6': // Envío E-Mail
                            $txt = 'Envío de credencial para #' . $act['id_afectado'] . ' ' . $user['nombre'] . ' ' . $user['apellido'] . ' por' . ' ' . $act['nombre_usuario'];
                            break;
                    }
                    $actividades[] = [
                        'horario' => '2 hs',
                        'texto' => $txt,
                    ];
                    break;
                case '2': // Perfil

                    break;
                case '3': // Contraseña
                    break;
                case '4': // Grupos
                    break;
                case '5': // Permisos
                    /* $permiso = $this->model_general->get('gg_permisos', $act['id_afectado']);
                    switch ($act['id_accion']) {
                        case '1': // Alta/Nuevo
                            $txt = 'Nuevo permiso creado #' . $act['id_afectado'] . ' ' . $permiso['nombre'] . ' ' . ' por' . ' ' . $act['nombre_usuario'];
                            break;
                        case '2': // Baja/Desactivar
                            $txt = 'Permiso desactivado #' . $act['id_afectado'] . ' ' . $permiso['nombre'] . ' ' . ' por' . ' ' . $act['nombre_usuario'];
                            break;
                        case '3': // Modificacion/Editar
                            $txt = 'Permiso editado #' . $act['id_afectado'] . ' ' . $permiso['nombre'] . ' ' . ' por' . ' ' . $act['nombre_usuario'];
                            break;
                        case '4': // Baja/Eliminar
                            $txt = 'Permiso eliminado #' . $act['id_afectado'] . ' ' . $permiso['nombre'] . ' ' . ' por' . ' ' . $act['nombre_usuario'];
                            break;
                        case '5': // Alta/Activar
                            $txt = 'Permiso habilitado #' . $act['id_afectado'] . ' ' . $permiso['nombre'] . ' ' . ' por' . ' ' . $act['nombre_usuario'];
                            break;
                    }
                    $actividades[] = [
                        'horario' => '2 hs',
                        'texto' => $txt,
                    ]; */
                    break;
                case '6': // Tarjeta
                    break;
                case '7': // Estadística
                    break;
                case '8': // Credencial Usuario
                    break;
            }
        }

        echo '<pre>';
        var_dump($actividad_reciente);
        var_dump($actividades);
        echo '</pre>';
        exit;
        return $actividad_reciente;
    }
}
