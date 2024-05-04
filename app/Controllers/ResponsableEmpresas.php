<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use CodeIgniter\Controller;
use App\Models;
use SebastianBergmann\Template\Template;
use Config\Validation;
use DateTime;

class ResponsableEmpresas extends BaseController
{

    public function __construct()
    {
        $this->session = \Config\Services::session();
        $this->validation = \Config\Services::validation();
        $this->model_logs = model('Model_logs');
        $this->model_responsable_empresas = model('Model_responsable_empresas');
        $this->model_general = model('Model_general');
    }

    /**
     * 
     */
    public function index()
    {
        $data['empresas'] = $this->model_general->getAllEstadoActivo('empresas');
        $data['usuarios'] = $this->model_general->getAllActivo('usuario');
        return template('responsable_empresas/index', $data);
    }

    public function getPaged($offset = NULL, $tamanioPagina = NULL)
    {
        if ((is_numeric($offset) && $offset >= 0) && (is_numeric($tamanioPagina) && $tamanioPagina > 0)) {
            $response = $this->model_responsable_empresas->getAllPaged($offset, $tamanioPagina, false);
        } else {
            if ((is_null($offset) || $offset == 0) && (is_null($tamanioPagina) || $tamanioPagina == 0)) {
                $response = $this->model_responsable_empresas->getAllPaged($offset, $tamanioPagina, true);
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

    /**
     * Genera la carga de la relación entre el usuario y la empresa para convertirse en responsable
     * Puede ser responsable de la Tarjeta M.A.S y/o también de las Inspecciones
     */
    public function store()
    {
        /*
        Primero validamos que no se esté agregando el mismo responsable que ya está cargado en el sistema
        Es decir, evitar datos repetidos
        (NO ESTÁ HECHO AÚN)
        */

        # Validamos que se hayan agregado uno o más usuarios
        $usuarios = $this->request->getPost('usuario_responsable');
        $id_usuarios = join(",", $usuarios);
        $id_usuarios_separados = explode(',', $id_usuarios);

        if (in_array('', $id_usuarios_separados)) {
            $this->response->setStatusCode(400);
            $response = [
                'error' => 400,
                'message' =>  "¡Debe seleccionar uno o más usuarios!"
            ];
            return $this->response->setJSON($response);
        }

        # Validamos que se haya seleccionado la empresa
        $id_empresa = $this->request->getPost('empresa');
        if (!$id_empresa) {
            $this->response->setStatusCode(400);
            $response = [
                'error' => 400,
                'message' =>  "¡Debe seleccionar una empresa!"
            ];
            return $this->response->setJSON($response);
        }

        $tarjeta_mas = null;
        $inspecciones = null;

        # En caso de que no haya elegido si es responsable para la Tarjeta M.A.S o de Inspecciones
        if (!$this->request->getPost('responsable_tarjeta_mas') && !$this->request->getPost('responsable_inspecciones')) {
            $this->response->setStatusCode(400);
            $response = [
                'error' => 400,
                'message' => "Debe seleccionar si es responsable de las Tarjeta M.A.S y/o Inspecciones"
            ];
            return $this->response->setJSON($response);
        }

        if ($this->request->getPost('responsable_tarjeta_mas'))
            $tarjeta_mas = 1;

        if ($this->request->getPost('responsable_inspecciones'))
            $inspecciones = 1;

        # Realizamos una iteración de cada usuario e insertamos la relación en la tabla 'responsable_empresas'
        foreach ($id_usuarios_separados as $u) {
            $datos_insert = [
                'id_usuario' => $u,
                'id_empresa' => $this->request->getPost('empresa'),
                'tarjeta_mas' => $tarjeta_mas,
                'inspecciones' => $inspecciones,
                'usuario_carga' => session()->get('id_usuario'),
                'fecha_carga' => date('Y-m-d')
            ];
            $this->model_responsable_empresas->insertData($datos_insert);
        }
        $response = true;
        echo json_encode($response);
    }

    /**
     * Elimina la relación que existe entre el usuario y la empresa en la tabla 'responsable_empresas'
     */
    public function delete()
    {
        $id_rel = $this->request->getPost('id');
        $id_delete = $this->model_responsable_empresas->delete($id_rel);
        echo json_encode($id_delete);
    }
}
