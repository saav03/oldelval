<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use CodeIgniter\Controller;
use DateTime;
use App\Models;

class Notificacion extends BaseController
{
    public function __construct()
    {
        date_default_timezone_set('America/Argentina/Buenos_Aires');
        $this->session = \Config\Services::session();
        $this->validation = \Config\Services::validation();
        $this->model_usuario = model('Model_usuario');
        $this->model_general = model('Model_general');
        $this->model_notificacion = model('Model_notificacion');
    }

    public function index() {
        $data['mis_notificaciones'] = $this->model_notificacion->get();
        return template('notificaciones/index', $data);
    }

    /**
     * 
     */
    public function get()
    {
        $data = $this->model_notificacion->get();

        // $aux = 0;
        // for ($i=0; $i < $data.count(); $i++) { 
        //     if ($data[$i]['leido'] == 0) {
        //         $aux++;
        //     }
        // }
        // $data['total_no_leidos'] = $aux;


        foreach ($data as $key => $d) {
            $fecha = new DateTime($d['fecha_notificacion']);
            $fecha_actual = new DateTime();

            $diferencia = $fecha_actual->diff($fecha);

            if ($diferencia->y > 0) {
                $data[$key]['tiempo'] = $diferencia->y . " años";
            } elseif ($diferencia->m > 0) {
                $data[$key]['tiempo'] = $diferencia->m != 1 ? $diferencia->m . ' meses' : $diferencia->m . ' mes';
            } elseif ($diferencia->d >= 7) {
                $semanas = floor($diferencia->d / 7);
                $data[$key]['tiempo'] = $semanas != 1 ? $semanas . ' sem' : $semanas . ' sem';
            } elseif ($diferencia->d > 0) {
                $data[$key]['tiempo'] = $diferencia->d . " días";
            } elseif ($diferencia->h > 0) {
                $data[$key]['tiempo'] = $diferencia->h . " hs";
            } elseif ($diferencia->i > 0) {
                $data[$key]['tiempo'] = $diferencia->i . " min";
            } else {
                $data[$key]['tiempo'] = $diferencia->s . " seg";
            }
        }

        echo json_encode($data);
    }

    public function notificacion_leida() {
        $data = ['leido' => 1];
        $id_notificacion = $this->request->getPost('id_notificacion');
        $this->model_notificacion->set_notificacion_leida($id_notificacion, $data);
        echo json_encode($id_notificacion);
    }

    public function setear_notificaciones_leidas() {
        $data = ['leido' => 1];
        $this->model_notificacion->setear_notificaciones_leidas($data);
    }

}
