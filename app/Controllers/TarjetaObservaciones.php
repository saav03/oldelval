<?php

namespace App\Controllers;

use Config\Validation;
use App\Controllers\Helper;
use App\Models\Model_tarjeta;
use PhpParser\Node\Stmt\TryCatch;

class TarjetaObservaciones extends BaseController
{
    public function __construct()
    {
        date_default_timezone_set('America/Argentina/Buenos_Aires');
        $this->session = \Config\Services::session();
        $this->validation = \Config\Services::validation();
        helper('adjunto');
        $this->model_logs = model('Model_logs');
        $this->model_general = model('Model_general');
        $this->model_tarjeta = model('Model_tarjeta');
        $this->model_mail_tarjeta = model('Model_mail_tarjeta');
        $this->model_proyectos = model('Model_proyectos');
        $this->model_estacion = model('Model_estacion');
    }

    public function index()
    {
        return template('tarjetas_obs/index');
    }

    public function view($id)
    {
        $data['tarjeta'] =  $this->model_tarjeta->getDataTarjeta($id);
        $data['indicadores'] =  $this->model_tarjeta->getDataIndicadoresTarjeta($id);
        return template('tarjetas_obs/view_obs', $data);
    }

    protected function getRiesgos($id_riesgo)
    {
        $riesgo = [];
        switch ($id_riesgo) {
            case '1':
                $riesgo['clase'] = 'riesgo_muy_bajo';
                $riesgo['valor'] = 'Muy Bajo';
                break;
            case '2':
                $riesgo['clase'] = 'riesgo_bajo';
                $riesgo['valor'] = 'Bajo';
                break;
            case '3':
                $riesgo['clase'] = 'riesgo_medio';
                $riesgo['valor'] = 'Medio';
                break;
            case '4':
                $riesgo['clase'] = 'riesgo_alto';
                $riesgo['valor'] = 'Alto';
                break;
        }
        return $riesgo;
    }

    public function view_add_obs()
    {
        $data['proyectos'] =  $this->model_general->getAllEstadoActivo('proyectos');
        $data['estaciones'] =  $this->model_general->getAllEstadoActivo('estaciones_bombeo');
        $data['sistemas'] =  $this->model_general->getAllEstadoActivo('sistemas_oleoductos');
        $data['indicadores'] =  $this->model_general->getAllEstadoActivo('tarjeta_indicadores');
        $data['clasificaciones'] =  $this->model_general->getAllEstadoActivo(' tarjeta_clasificaciones');
        $data['efectos'] =  $this->model_general->getAllEstadoActivo('efectos_impactos');
        $data['significancia'] =  $this->model_general->getAllEstadoActivo('significancia');
        $data['tipo_hallazgo'] =  $this->model_general->getAllEstadoActivo(' tarjeta_tipo_hallazgo');
        $data['contratistas'] =  $this->model_general->getAllEstadoActivo(' empresas');
        $data['responsables'] =  $this->model_general->getAllActivo(' usuario');
        // $data['modulos'] =  $this->model_general->getAllEstadoActivo('modulos');
        return template('tarjetas_obs/add_obs', $data);
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
     */
    public function getEstacionesFilter()
    {
        $id_modulo = $this->request->getPost('id_modulo');
        $estaciones =  $this->model_estacion->getEstacionesFilter($id_modulo);
        echo json_encode($estaciones);
    }

    /**
     * Carga una nueva tarjeta con todos sus hallazgos (Tanto positivos como posibilidad de mejora)
     * Y realiza el envío de los correos
     * (Envía correo al creador de la tarjeta y para los responsables del hallazgo)
     */
    public function submitTarjeta()
    {
        $model = new Model_tarjeta();
        $helper = new Helper();
        $obs_oportunidad_mejora = !is_null($this->request->getPost('hallazgos_mejoras')) && count($this->request->getPost('hallazgos_mejoras')) > 0 ? true : false;

        $datos_tarjeta  = [
            'contratista'    => $this->request->getPost('contratista'),
            'fecha_deteccion'    => $this->request->getPost('fecha_deteccion'),
            'tipo_observacion'     => $obs_oportunidad_mejora ? 2 : 1,
            'observador'     => $this->request->getPost('observador'),
            'descripcion'    => $this->request->getPost('descripcion'),
            'proyecto'    => $this->request->getPost('proyecto'),
            'modulo'    => $this->request->getPost('modulo'),
            'estacion_bombeo'    => empty($this->request->getPost('estacion_bombeo')) ? null : $this->request->getPost('estacion_bombeo'),
            'sistema_oleoducto'    => empty($this->request->getPost('sistema_oleoducto')) ? null : $this->request->getPost('sistema_oleoducto'),
            'usuario_carga' => session()->get('id_usuario')
        ];

        // Inicia la transacción
        $model->db->transStart();
        try {
            $results = $this->verificacion($datos_tarjeta, 'validation_tarjeta');

            if (!$results['exito']) {
                $model->db->transRollback();
                return json_encode($results['errores']);
            }

            // * Insertando tarjeta de observación

            $obs_tarjeta = $this->model_tarjeta->addSubmit($datos_tarjeta);
            $id_tarjeta = $obs_tarjeta['last_id'];

            // * Inserta nuevos observadores que se relacionen con la tarjeta
            if (isset($id_tarjeta)) {
                $observadores = $this->request->getPost('observadores');
                if ($observadores != NULL) {
                    foreach ($observadores as $observador) {
                        $data_observadores = [
                            'id_tarjeta' => $id_tarjeta,
                            'observador' => $observador
                        ];
                        $this->model_tarjeta->addObservadorTarjeta($data_observadores);
                    }
                }
            }

            // * Insertando checklist (o también conocido como Indicadores)
            if (isset($id_tarjeta)) {
                $guia_deteccion = $this->request->getPost('guia_deteccion');
                foreach ($guia_deteccion as $key => $guia) {
                    $data = [
                        'id_tarjeta' => $id_tarjeta,
                        'id_indicador' => $key,
                        'comentario' => $guia['comentario'],
                        'rta' => $guia['rta'],
                    ];
                    $this->model_general->insertG('tarjeta_rel_indicadores', $data);
                }
            }

            // * Insertando los hallazgos de mejoras
            if (isset($id_tarjeta)) {
                // * Hallazgo de mejoras
                $hallazgos_mejoras = $this->request->getPost('hallazgos_mejoras');
                if (!is_null($hallazgos_mejoras) && count($hallazgos_mejoras) > 0) {

                    foreach ($hallazgos_mejoras as $key => $h) {
                        $data = [
                            'id_tarjeta' => $id_tarjeta,
                            'hallazgo' => $h['hallazgo'],
                            'plan_accion' => $h['plan_accion'],
                            'aspecto' => $h['aspecto_observado'],
                            'significancia' => isset($h['significancia']) ? $h['significancia'] : '',
                            'tipo' => $h['tipo'],
                            'responsable' => $h['responsable'],
                            'relevo_responsable' => $h['relevo_responsable'],
                            'fecha_cierre' => $h['fecha_cierre'],
                            'usuario_carga' => session()->get('id_usuario')
                        ];
                        $result_hallazgo = $this->verificacion($data, 'validation_hallazgo');

                        if (!$result_hallazgo['exito']) {
                            $model->db->transRollback();
                            return json_encode($result_hallazgo['errores']);
                        }

                        $hallazgo_creado = $this->model_tarjeta->addSubmitHallazgo($data);

                        $id_hallazgo = $hallazgo_creado['last_id'];

                        // * Insertar los efectos de ese hallazgo creado
                        if ($h['efectos']) {
                            $efectos_separados = [];
                            $efectos = join(",", $h['efectos']);
                            if ($efectos)
                                $efectos_separados = explode(',', $efectos);
                            if (count($efectos_separados) > 0) {
                                foreach ($efectos_separados as $e) {
                                    $data = [
                                        'efecto_id' => $e,
                                        'hallazgo_id' => $id_hallazgo,
                                        'tarjeta_id' => $id_tarjeta,
                                    ];
                                    $this->model_general->insertG('tarjeta_rel_efecto', $data);
                                }
                            }
                        }
                        // * ---

                        // * Inserta los adjuntos de cada hallazgo creado
                        if ($this->request->getPost('adjuntos_hallazgos_' . $key . '-description')) {
                            $bd_info = array(
                                'table' => 'tarjeta_hallazgos_adjuntos',
                                'file' => 'adjunto',
                                'description' => 'desc_adjunto',
                                'optional_ids' => ['id_hallazgo' => $id_hallazgo['id']]
                            );
                            $helper->cargarArchivos('adjuntos_hallazgos_' . $key, 'uploads/tarjetaObs/', 'obs_tarjeta', $bd_info, $this->request->getPost('adjuntos_hallazgos_' . $key . '-description'));
                        }
                        // * ---

                        // * Envío de E-Mails
                        $datos_hallazgo = [];
                        $datos_hallazgo['datos'] = $this->model_mail_tarjeta->getInfoTarjetaCreada($id_tarjeta, $id_hallazgo);

                        // * Para el responsable
                        if ($h['responsable'] != '')
                            $helper->sendMailTarjeta($datos_hallazgo, 2);

                        // * Para el segundo responsable
                        if ($h['relevo_responsable'] != '') {
                            $datos_hallazgo['datos'] = $this->model_mail_tarjeta->getInfoTarjetaCreada($id_tarjeta, $id_hallazgo, true);
                            $helper->sendMailTarjeta($datos_hallazgo, 5);
                        }
                    }
                }

                // * Hallazgos positivos
                $hallazgos_positivos = $this->request->getPost('hallazgos_positivos');
                if (!is_null($hallazgos_positivos) && count($hallazgos_positivos) > 0) {

                    foreach ($hallazgos_positivos as $key => $h) {
                        $data = [
                            'id_tarjeta' => $id_tarjeta,
                            'hallazgo' => $h['hallazgo'],
                            'aspecto' => $h['aspecto_observado'],
                            'significancia' => isset($h['significancia']) ? $h['significancia'] : '',
                            'tipo' => $h['tipo'],
                            'responsable' => $h['responsable'],
                            'resuelto' => 1,
                            'usuario_carga' => session()->get('id_usuario')
                        ];

                        $result_hallazgo = $this->verificacion($data, 'validation_obs_positiva');

                        if (!$result_hallazgo['exito']) {
                            $model->db->transRollback();
                            return json_encode($result_hallazgo['errores']);
                        }

                        $hallazgo_creado = $this->model_tarjeta->addSubmitHallazgo($data);
                        $id_hallazgo = $hallazgo_creado['last_id'];

                        // * Insertar los efectos de ese hallazgo creado
                        if ($h['efectos']) {
                            $efectos_separados = [];
                            $efectos = join(",", $h['efectos']);
                            if ($efectos)
                                $efectos_separados = explode(',', $efectos);

                            if (count($efectos_separados) > 0) {
                                foreach ($efectos_separados as $e) {
                                    $data = [
                                        'efecto_id' => $e,
                                        'hallazgo_id' => $id_hallazgo,
                                        'tarjeta_id' => $id_tarjeta,
                                    ];
                                    $this->model_general->insertG('tarjeta_rel_efecto', $data);
                                }
                            }
                        }
                        // * ---

                        // * Inserta los adjuntos de cada hallazgo creado
                        if ($this->request->getPost('adjuntos_hallazgos_positivos_' . $key . '-description')) {
                            $bd_info = array(
                                'table' => 'tarjeta_hallazgos_adjuntos',
                                'file' => 'adjunto',
                                'description' => 'desc_adjunto',
                                'optional_ids' => ['id_hallazgo' => $id_hallazgo['id']]
                            );
                            $helper->cargarArchivos('adjuntos_hallazgos_positivos_' . $key, 'uploads/tarjetaObs/', 'obs_tarjeta', $bd_info, $this->request->getPost('adjuntos_hallazgos_positivos_' . $key . '-description'));
                        }
                        // * ---

                        $data_reconocimiento = [];
                        $data_reconocimiento['datos'] = $this->model_mail_tarjeta->getDataTarjetaReconocimiento($id_tarjeta, $id_hallazgo);
                        // * Envío de correo para el responsable
                        if ($h['responsable'] != '')
                            $helper->sendMailTarjeta($data_reconocimiento, 6);
                    }
                }

                $datos_hallazgo = [];
                // * Para quien carga la tarjeta
                $datos_hallazgo['datos'] = $this->model_mail_tarjeta->getInfoTarjetaCreada($id_tarjeta, $id_hallazgo);
                $helper->sendMailTarjeta($datos_hallazgo, 1);
            }

            // * Si todos los hallazgos son positivos, entonces la tarjeta se da como cerrada desde un principio
            if (!$obs_oportunidad_mejora) {

                /* Cierra la observación */
                $datos_actualizar_tarjeta = [
                    'situacion' => 0
                ];
                $this->model_general->updateG('tarjeta_observaciones', $id_tarjeta, $datos_actualizar_tarjeta);

                $data_cierre = [
                    'motivo'    => 'Tarjeta M.A.S con Observaciones Positivas',
                    'cierre_manual'    => 0,
                    'id_tarjeta_obs' => $id_tarjeta,
                    'id_usuario_cierre' => session()->get('id_usuario'),
                ];
                $this->model_tarjeta->addMotivoCierre($data_cierre);
            }

            newMov(6, 1, $id_tarjeta, 'Tarjeta M.A.S'); //Movimiento (Registra el ID de la Tarjeta M.A.S creada)

            // Finaliza la transacción
            $model->db->transComplete();
            echo json_encode($results);
        } catch (\Throwable $error) {
            $model->db->transRollback();
            return 'Ha ocurrido un error en el registro de la tarjeta: ' . $error->getMessage();
        }
    }

    /**
     * Generar un nuevo descargo
     */
    public function submitDescargo()
    {
        $helper = new Helper();
        $id_hallazgo = $this->request->getPost('id_hallazgo');

        $datos_descargo  = [
            'id_hallazgo'     => $id_hallazgo,
            'motivo'    => $this->request->getPost('new_descargo'),
            'id_usuario' => session()->get('id_usuario'),
            'fecha_hora_motivo' => date('Y-m-d H:i:s'),
        ];

        $results_descargo = $this->model_tarjeta->addDescargo($datos_descargo);

        $datos['datos'] = $this->model_mail_tarjeta->getInfoNewDescargo($id_hallazgo, $results_descargo['last_id']);
        $helper->sendMailTarjeta($datos, 3);

        # Se cargan adjuntos si es que realmente existen
        if ($this->request->getPost('adj_descargo-description[]')) {
            $bd_info = array(
                'table' => 'tarjeta_descargos_adj',
                'file' => 'adjunto',
                'description' => 'desc_adjunto',
                'optional_ids' => ['id_descargo' => $results_descargo['last_id']['id']]
            );
            $helper->cargarArchivos('adj_descargo', 'uploads/tarjetaObs/descargos/', 'obs_descargo', $bd_info, $this->request->getPost('adj_descargo-description'));
        }

        newMov(10, 1, $id_hallazgo, 'Descargo Tarjeta M.A.S'); //Movimiento (Registra el ID del Descargo creado)

        return $results_descargo;
    }

    /**
     * Dar una respuesta a un descargo
     */
    public function submitRtaDescargo()
    {
        $helper = new Helper();
        $id_descargo = $this->request->getPost('inp_id_descargo');

        /* == Si es 1 se aceptó el descargo | Si es 2 se rechazó == */
        $estado = ($this->request->getPost('tipo_rta_descargo') == 1) ? 1 : 2;

        $datos_rta_descargo  = [
            'estado'    => $estado,
            'respuesta'    => $this->request->getPost('rta_descargo'),
            'fecha_hora_respuesta' => date('Y-m-d H:i:s'),
            'id_usuario_rta' => session()->get('id_usuario'),
        ];

        $results_descargo = $this->model_tarjeta->editDescargo($datos_rta_descargo, $id_descargo);
        $datos['datos'] = $this->model_mail_tarjeta->getRespuestaDescargo($id_descargo);

        // * Actualiza el hallazgo y lo setea como resuelto
        $id_hallazgo = $datos['datos']['id_hallazgo'];
        $id_tarjeta = $datos['datos']['id_obs'];
        if ($estado == 1) {
            $data = [
                'resuelto' => 1
            ];
        } else {
            $data = [
                'resuelto' => null
            ];
        }

        $this->model_general->updateG('tarjeta_hallazgos', $id_hallazgo, $data);
        $helper->sendMailTarjeta($datos, 4);

        newMov(11, 1, $id_hallazgo, 'Rta Descargo Tarjeta M.A.S'); //Movimiento (Registra el ID de la Respuesta del Descargo creado)
    }

    /**
     * Cerrar la Tarjeta de Observación | También si hay descargos sin rtas los va a colocar en BD como sin rta.
     */
    public function submitCerrarObs()
    {

        $id_tarjeta = $this->request->getPost('id_tarjeta_close');
        $id_hallazgo = $this->request->getPost('id_hallazgo');
        $cierre_forzado = $this->request->getPost('cierre_forzado');
        $descargos = $this->model_tarjeta->getDescargoHallazgoTarjeta($id_hallazgo);

        /* Cierra la observación */
        $datos_actualizar_tarjeta = [
            'situacion' => 0
        ];
        $this->model_general->updateG('tarjeta_observaciones', $id_tarjeta, $datos_actualizar_tarjeta);

        // ! NO VA PORQUE LO VOY HACER QUE ESTÉN TODOS LOS DESCARGOS ACEPTADOS CORRECTAMENTE PARA CERRAR LA TARJETA M.A.S
        /* Cierra los descargos sin rta */
        // if ($cierre_forzado == 1) {
        //     foreach ($descargos as $d) {
        //         if ($d['estado'] == 0 && is_null($d['respuesta'])) {
        //             $datos_actualizar_hallazgo = [
        //                 'estado' => '-1',
        //             ];
        //             $this->model_tarjeta->closeDescargoForced($datos_actualizar_hallazgo, $d['id']);
        //         }
        //     }
        // }

        $datos_motivo_cierre  = [
            'motivo'    => $this->request->getPost('motivo_cierre_obs'),
            'cierre_manual'    => 0,
            'id_tarjeta_obs' => $id_tarjeta,
            'id_usuario_cierre' => session()->get('id_usuario'),
        ];

        $results_cierre = $this->model_tarjeta->addMotivoCierre($datos_motivo_cierre);

        newMov(6, 3, $id_tarjeta, 'Cierre de Tarjeta M.A.S'); //Movimiento (Registra el ID de la Tarjeta M.A.S creada)
    }

    protected function verificacion($datos, $nombre_validacion)
    {
        $verificacion = [];
        $this->validation->reset();
        if ($this->validation->run($datos, $nombre_validacion) === FALSE) {
            $this->response->setStatusCode(400);
            $verificacion['exito'] = false;
            $verificacion['errores'] = $this->validation->getErrors();
        } else {
            $verificacion['exito'] = true;
        }
        return $verificacion;
    }

    public function getPaged($offset = NULL, $tamanioPagina = NULL)
    {

        if ((is_numeric($offset) && $offset >= 0) && (is_numeric($tamanioPagina) && $tamanioPagina > 0)) {
            $response = $this->model_tarjeta->getAllPaged($offset, $tamanioPagina);
        } else {
            if (is_null($offset) && is_null($tamanioPagina)) {
                $response = $this->model_tarjeta->getAllPaged($offset, $tamanioPagina, true);
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

    public function testing($id, $email)
    {
    }
}
