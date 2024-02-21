<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use Config\Validation;

class Estadisticas extends BaseController
{

    public function __construct()
    {
        $this->db = \Config\Database::connect();
        $this->session = \Config\Services::session();
        $this->validation = \Config\Services::validation();
        $this->model_logs = model('Model_logs');
        $this->model_general = model('Model_general');
        $this->model_estadisticas = model('Model_estadisticas');
        $this->model_usuario = model('Model_usuario');
        $this->model_empresas = model('Model_empresas');
        $this->model_proyectos = model('Model_proyectos');
        $this->model_estacion = model('Model_estacion');
    }

    public function index()
    {
        if (!$this->session->get('isLogin')) {
            return redirect()->to('login');
        } else {
            // $data['datos_basicos'] = $this->model_usuario->getDatos(session()->get('id_usuario'));
            return template('estadisticas/index'/*, $data*/);
        }
    }

    public function view($id_estadistica, $id_tipo)
    {
        if (!$this->session->get('isLogin')) {
            return redirect()->to('login');
        } else {

            $data['estadistica'] = $this->model_estadisticas->getEstadistica($id_estadistica, $id_tipo);
            $anio = $data['estadistica'][0]['anio_id'];
            $periodos = $this->model_estadisticas->getPeriodos($anio);
            $meses = $this->meses();

            foreach ($periodos as $key => $p) {

                if ($data['estadistica'][0]['periodo'] == $p['mes'] && $data['estadistica'][0]['anio'] == $p['anio']) {
                    $num_mes = $p['mes'] - 1;
                    $data['estadistica'][0]['periodo'] = $meses[$num_mes] . '/' . $p['anio'];
                }
            }

            $data['contratistas'] =  $this->model_general->getAllEstadoActivo(' empresas');
            $data['proyectos'] =  $this->model_general->getAllEstadoActivo('proyectos');
            $data['modulos'] =  $this->model_general->getAllEstadoActivo('modulos');
            $data['estaciones'] =  $this->model_general->getAllEstadoActivo('estaciones_bombeo');
            $data['sistemas'] =  $this->model_general->getAllEstadoActivo('sistemas_oleoductos');

            return template('estadisticas/view', $data);
        }
    }

    public function add_planilla_tipo()
    {
        return template('estadisticas/add_tipo_planilla');
    }

    public function addFormulario()
    {
        if (!$this->session->get('isLogin')) {
            return redirect()->to('login');
        } else {
            $data = $this->getEncabezado(1);
            return template('estadisticas/add', $data);
        }
    }

    public function addCapacitaciones()
    {
        if (!$this->session->get('isLogin')) {
            return redirect()->to('login');
        } else {
            $data = $this->getEncabezado(2);
            return template('estadisticas/capacitaciones/add', $data);
        }
    }

    public function addGestionVehicular()
    {
        if (!$this->session->get('isLogin')) {
            return redirect()->to('login');
        } else {
            $data = $this->getEncabezado(3);
            return template('estadisticas/gestion_vehicular/add', $data);
        }
    }

    protected function getEncabezado($id_tipo)
    {
        /*
        Voy a llevar solo los periodos a partir de la fecha actual hacia atrás
        */
        $data['anio_periodos'] =  $this->model_general->getAll('anio_periodos');
        $data['proyectos'] =  $this->model_general->getAllEstadoActivo('proyectos');
        $data['estaciones'] =  $this->model_general->getAllEstadoActivo('estaciones_bombeo');
        $data['sistemas'] =  $this->model_general->getAllEstadoActivo('sistemas_oleoductos');
        $data['contratista'] =  $this->model_empresas->getEmpresas($this->session->get('empresa'));
        $data['contratistas'] =  $this->model_empresas->getEmpresas(0);
        $data['planilla'] = $this->model_estadisticas->getDataPlanillaIncidente($id_tipo);
        return $data;
    }

    public function getYearsPeriod($year_id)
    {
        $periodos = $this->model_estadisticas->getPeriodos($year_id);
        $meses = $this->meses();
        $periodos_disponibles = [];

        /* == Fecha actual == */
        $today = date("Y-m-d");
        // $today = new DateTime("2023-05-05 00:00:00");
        // $formateandoAndo = $today->format("Y-m-d");

        $today_int = strtotime($today);
        $today_month = date('m', $today_int);

        foreach ($periodos as $key => $p) {
            if ($p['fecha_ini'] <= $today) {

                $periodos_disponibles[] = $p;
                $fecha_ini_int = strtotime($p['fecha_ini']);
                $mes_periodo = date('m', $fecha_ini_int);

                $num_mes = $p['mes'] - 1;
                $periodos_disponibles[$key]['nombre_mes'] = $meses[$num_mes];

                if ($p['fecha_fin'] < $today) {
                    $periodos_disponibles[$key]['atrasado'] = 'Atrasado';
                }
                if ($mes_periodo != $today_month) {
                }
            }
        }
        $periodos_format = $periodos_disponibles;
        echo json_encode($periodos_format);
    }

    /**
     * Trae los modulos filtrados pertenecientes a un proyecto
     */
    public function getModulosFilter()
    {
        $id_proyecto = $this->request->getPost('id_proyecto');
        $modulos =  $this->model_proyectos->getModulosFilter($id_proyecto);
        echo json_encode($modulos);
    }

    /**
     * Trae las estaciones filtradas pertenecientes a un módulo
     * (No funciona)
     * Por algún motivo me aparece un error, el siguiente:
     * 'You must set the database table to be used with your query.'
     * Quien lo lea a futuro y lo pueda arreglar genial. Por el momento se utiliza el controlador
     * de TarjetaObservaciones el cual si funciona :s
     */
    public function getEstacionesFilter()
    {
        $id_modulo = $this->request->getPost('id_modulo');
        $estaciones = $this->model_estacion->getEstacionesFilter($id_modulo);
        echo json_encode($estaciones);
    }

    public function submitEstadistica()
    {

        $id_tipo = $this->request->getPost('tipo');

        $datos = [
            'tipo' => $id_tipo,
            'contratista' => $this->request->getPost('contratista'),
            'anio' => $this->request->getPost('anio_periodo'),
            'periodo' => $this->request->getPost('periodo'),
            'proyecto' => $this->request->getPost('proyecto'),
            'modulo' => $this->request->getPost('modulo') == '-1' ? -1 : $this->request->getPost('modulo'),
            'estacion' => $this->request->getPost('estacion_bombeo') == '-1' ? -1 : $this->request->getPost('estacion_bombeo'),
            // 'sistema' => $this->request->getPost('sistema') == '-1' ? -1 : $this->request->getPost('sistema'),
        ];

        $datos_estadisticas = $this->verificacion($datos, 'validation_estadistica');

        if ($datos_estadisticas['exito']) {
            $anio = $this->model_general->get('anio_periodos', $this->request->getPost('anio_periodo'));
            $datos['anio'] = $anio['anio'];
            
            $periodo = $_POST['periodo'];

            /** == Periodo == **/
            if (str_contains($periodo, 'pas_')) {
                $datos['atrasado'] = 1;
                $periodo = explode('pas_', $periodo);
            } else {
                $datos['atrasado'] = 0;
                $periodo = explode('pre_', $periodo);
            }
            // $datos['anio'] = intval(date('Y'));
            $datos['periodo'] = $periodo[1];
            // $datos['fecha_hora_carga'] = date('Y-m-d H:i:s');
            $datos['usuario_carga'] = session()->get('id_usuario');

            $data = [];

            /** == Cargar Encabezado en BD == **/
            $results = $this->model_estadisticas->addPlanillaFormulario($datos);

            /** == Cargar Adjuntos de la Est.Capacitacion en BD == **/
            if ($id_tipo == 2) {
                $upload_ruta = "uploads/estadisticas/";
                $desc_adjuntos = $this->request->getPost('adj_desc');
                for ($i = 0; $i < count($_FILES["adj_capacitaciones"]["name"]); $i++) {

                    /* == Si no está vacio.. == */
                    if (!empty($_FILES["adj_capacitaciones"]["name"][$i])) {
                        $target_file = $upload_ruta . basename($_FILES["adj_capacitaciones"]["name"][$i]);
                        $archivoTemporal = $_FILES["adj_capacitaciones"]["tmp_name"][$i];

                        $extension = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));
                        $nombre = "adj_capacitacion-" . date('Y-m-d-H-i-s') . "." . $extension;
                        $uploadPath = $upload_ruta . $nombre;

                        if (in_array(strtolower($extension), ["pdf", "doc", "docx", "docm", "xls", "xlsx", "xlsb"])) {
                            if (move_uploaded_file($archivoTemporal, $uploadPath)) {
                                $archivoCargado = true;
                            }
                        }
                        $dato_bd = [
                            'id_estadistica' => $results['last_id'],
                            'adjunto' => $nombre,
                            'descripcion' => $desc_adjuntos[$i],
                        ];
                        $this->model_general->insertG('adj_estadisticas', $dato_bd);
                    }
                }
            }

            newMov(7, 1, $results['last_id']); //Movimiento

            if ($results['status']) {
                /** == Indicadores Generales (Sin títulos y Sin subtitulos) == **/
                $indicadores_grales = $this->request->getPost('indicador_gral');

                if (!is_null($indicadores_grales)) {
                    foreach ($indicadores_grales as $ind_gral) {
                        $data = [
                            /** ACA VA EL ULTIMO INSERT ID DE LA ESTADISTICA **/
                            'id_estadistica' => $results['last_id'],
                            'id_indicador' => $ind_gral['id_indicador'],
                            'valor' => $ind_gral['valor'],
                            'nota' => $ind_gral['nota'],
                            'id_tipo' => $id_tipo,
                            // 'fecha_hora_carga' => date('Y-m-d H:i:s'),
                            'usuario_carga' => session()->get('id_usuario')
                        ];
                        $this->model_general->insertG('estadisticas_rel_planilla_indicadores', $data);
                    }
                }

                /** == Indicadores (Sin títulos y Con subtitulos) == **/
                $indicadores_subts = $this->request->getPost('indicador_subt');

                if (!is_null($indicadores_subts)) {
                    foreach ($indicadores_subts as $ind_subt) {
                        $data = [
                            /** ACA VA EL ULTIMO INSERT ID DE LA ESTADISTICA **/
                            'id_estadistica' => $results['last_id'],
                            'id_indicador' => $ind_subt['id_indicador'],
                            'valor' => $ind_subt['valor'],
                            'nota' => $ind_subt['nota'],
                            'id_tipo' => $id_tipo,
                            'id_subtitulo' => $ind_subt['id_subtitulo'],
                            'id_titulo' => $ind_subt['id_titulo'],
                            // 'fecha_hora_carga' => date('Y-m-d H:i:s'),
                            'usuario_carga' => session()->get('id_usuario')
                        ];
                        $this->model_general->insertG('estadisticas_rel_planilla_indicadores', $data);
                    }
                }

                /** == Indicadores (Con títulos y Sin subtitulos) == **/
                $indicadores_title = $this->request->getPost('indicador_title');

                if (!is_null($indicadores_title)) {
                    foreach ($indicadores_title as $ind_title) {
                        $data = [
                            /** ACA VA EL ULTIMO INSERT ID DE LA ESTADISTICA **/
                            'id_estadistica' => $results['last_id'],
                            'id_indicador' => $ind_title['id_indicador'],
                            'valor' => $ind_title['valor'],
                            'nota' => $ind_title['nota'],
                            'id_tipo' => $id_tipo,
                            'id_titulo' => $ind_title['id_titulo'],
                            // 'fecha_hora_carga' => date('Y-m-d H:i:s'),
                            'usuario_carga' => session()->get('id_usuario')
                        ];
                        $this->model_general->insertG('estadisticas_rel_planilla_indicadores', $data);
                    }
                }

                /** == Indices == **/
                $indices = $this->request->getPost('indice');
                if (!is_null($indices)) {
                    foreach ($indices as $indice) {
                        $data = [
                            'id_estadistica' => $results['last_id'],
                            'id_indicador' => $indice['id_indicador'],
                            'valor' => $indice['valor'],
                            'nota' => $indice['nota'],
                            'id_tipo' => $id_tipo,
                            'es_kpi' => 1,
                            // 'fecha_hora_carga' => date('Y-m-d H:i:s'),
                            'usuario_carga' => session()->get('id_usuario')
                        ];
                        $this->model_general->insertG('estadisticas_rel_planilla_indicadores', $data);
                    }
                }
            } else {
                echo json_encode($results['message']);
            }
        } else {
            $errores = $datos_estadisticas['errores'];
            echo json_encode($errores);
        }
    }

    /**
     * Agrega el Indice IF AAP
     */
    public function submitIndiceIFAAP()
    {
        $data = [
            'id_estadistica' => $this->request->getPost('id_estadistica'),
            'id_indicador' => 14,
            'valor' => $this->request->getPost('valor'),
            'nota' => $this->request->getPost('motivo'),
            'id_tipo' => 1,
            'es_kpi' => 1,
            'usuario_carga' => session()->get('id_usuario')
        ];
        $this->model_general->insertG('estadisticas_rel_planilla_indicadores', $data);
    }

    public function changeState($id_estadistica)
    {
        $this->model_estadisticas->changeState($id_estadistica);
    }

    public function getPaged($offset = NULL, $tamanioPagina = NULL)
    {
        if ((is_numeric($offset) && $offset >= 0) && (is_numeric($tamanioPagina) && $tamanioPagina > 0)) {
            $response = $this->model_estadisticas->getAllPaged($offset, $tamanioPagina);
        } else {
            if (is_null($offset) && is_null($tamanioPagina)) {
                $response = $this->model_estadisticas->getAllPaged($offset, $tamanioPagina, true);
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

    //-----------------------------------------------------------------------------------------------------------
    protected function verificacion($datos, $nombre_validacion)
    {
        $verificacion = [];
        if ($this->validation->run($datos, $nombre_validacion) === FALSE) {
            $this->response->setStatusCode(400);
            $verificacion['exito'] = false;
            $verificacion['errores'] = $this->validation->getErrors();
        } else {
            $verificacion['exito'] = true;
        }
        return $verificacion;
    }

    public function cargarTipoPlanilla()
    {
        $tipo = $this->request->getPost('tipo_plan');
        $indicadores = $this->request->getPost('indicadores');
        $datos  = [
            'tipo_plan' => $this->request->getPost('tipo_plan')
        ];
        echo $tipo;
        if (acceso(1)) {

            $result = $this->validar($datos);
            extract($result);
            if ($exito) {
                $this->model_estadisticas->addPlanillaFormulario(['nombre' => $datos['tipo_plan'], 'usuario_creacion' => session()->get('id_usuario')]);
                $id = $this->model_general->getLastId('estadisticas_tipo');
                foreach ($indicadores as $i) {
                    $this->model_estadisticas->addIndicadoresPlanilla(['indicador' => $i, "id_tipo" => $id, 'usuario_creacion' => session()->get('id_usuario')]);
                }
                newMov(6, 1, $id['id']); //Movimiento
                echo $id['id'];
            } else {
                http_response_code(400);
                echo json_encode($errores);
            }
        } else {
            http_response_code(403);
            $response = ['No se poseen los permisos para acceder a esta accion.'];
            echo json_encode($response);
        }
    }
    //-------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
    protected function validar($data)
    {
        $resultado = true;
        $errores = [];
        if ($this->validation->run($data, 'validation_form') === FALSE) {
            $this->response->setStatusCode(400);
            $resultado = $this->validation->getErrors();
        }
        $result = array(
            'exito' => $resultado,
            'errores' => $errores
        );
        return $result;
    }

    protected function meses()
    {
        $meses = ["Enero", "Febrero", "Marzo", "Abril", "Mayo", "Junio", "Julio", "Agosto", "Septiembre", "Octubre", "Noviembre", "Diciembre"];
        return $meses;
    }
}
