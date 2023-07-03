<?php

namespace App\Controllers;

use Config\Validation;
use App\Controllers\Helper;


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

        $data['tabla'] = $this->model_tarjeta->getMatrizRiesgo();
        $data['tarjeta'] =  $this->model_tarjeta->getDataTarjeta($id);
        $data['indicadores'] =  $this->model_tarjeta->getDataIndicadoresTarjeta($id);

        if (isset($data['tarjeta']['hallazgo'])) {
            $data['riesgos'] = $this->getRiesgos($data['tarjeta']['hallazgo']['matriz_riesgo']);
        }

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
        $data['tabla'] = $this->model_tarjeta->getMatrizRiesgo();
        $data['proyectos'] =  $this->model_general->getAllEstadoActivo('proyectos');
        $data['estaciones'] =  $this->model_general->getAllEstadoActivo('estaciones_bombeo');
        $data['sistemas'] =  $this->model_general->getAllEstadoActivo('sistemas_oleoductos');
        $data['indicadores'] =  $this->model_general->getAllEstadoActivo('tarjeta_indicadores');
        $data['clasificaciones'] =  $this->model_general->getAllEstadoActivo(' tarjeta_clasificaciones');
        $data['efectos'] =  $this->model_general->getAllEstadoActivo('efectos_impactos');
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

    public function submitTarjeta()
    {

        $helper = new Helper();
        $situacion = $this->request->getPost('situacion');
        $tipo_observacion = $this->request->getPost('tipo_observacion');
        $datos_tarjeta  = [
            'contratista'    => $this->request->getPost('contratista'),
            'fecha_deteccion'    => $this->request->getPost('fecha_deteccion'),
            'tipo_observacion'     => $tipo_observacion,
            'observador'     => $this->request->getPost('observador'),
            'descripcion'    => $this->request->getPost('descripcion'),
            'proyecto'    => $this->request->getPost('proyecto'),
            'modulo'    => $this->request->getPost('modulo'),
            'estacion_bombeo'    => empty($this->request->getPost('estacion_bombeo')) ? null : $this->request->getPost('estacion_bombeo'),
            'sistema_oleoducto'    => empty($this->request->getPost('sistema_oleoducto')) ? null : $this->request->getPost('sistema_oleoducto'),
            'usuario_carga' => session()->get('id_usuario')
        ];

        $verificacion_tarjeta = $this->verificacion($datos_tarjeta, 'validation_tarjeta');

        if ($verificacion_tarjeta['exito']) {

            switch ($tipo_observacion) {
                case '1': // Reconocimiento Positivo

                    # Tarjeta de Observación Cerrada y con Reconocimiento
                    $datos_reconocimiento = $this->verify_reconocimiento_positivo();

                    if ($datos_reconocimiento['exito']) {

                        # Se cargan los Riesgos Observados
                        $significancia = $this->request->getPost('significancia');

                        $datos_significancia = $this->verify_significancia($significancia);
                        if (!empty($datos_significancia) && !isset($datos_significancia['errores'])) {

                            $datos_tarjeta['situacion'] = 0;
                            $results = $this->model_tarjeta->addSubmit($datos_tarjeta);
                            $id_tarjeta = $results['last_id'];

                            if (isset($datos_reconocimiento['reconocimiento'])) // # Significa que seleccionó que desea destacar un reconocimiento positivo
                                $this->submitReconocimientoPositivo($datos_reconocimiento['reconocimiento'], $id_tarjeta);

                            $id_hallazgo = '';

                            foreach ($datos_significancia as $r) {
                                $riesgos = [
                                    'id_tarjeta' => $id_tarjeta,
                                    'id_significancia' => $r,
                                ];
                                $this->model_general->insertG('tarjeta_rel_significancia', $riesgos);
                            }

                            $id_responsable = $datos_reconocimiento['reconocimiento']['responsable'];

                            # Insertar datos de la tarjeta cerrada
                            $datos_motivo_cierre  = [
                                'motivo'    => 'Tarjeta Cerrada',
                                'id_tarjeta_obs' => $id_tarjeta,
                                'id_usuario_cierre' => session()->get('id_usuario'),
                            ];

                            $this->model_tarjeta->addMotivoCierre($datos_motivo_cierre);

                            # Envío de correo
                            if (isset($id_tarjeta)) {
                                // $datos_hallazgo['datos'] = $this->model_mail_tarjeta->getInfoTarjetaCreada($id_tarjeta, $id_hallazgo);
                                $data_reconocimiento['datos'] = $this->model_mail_tarjeta->getDataTarjetaReconocimiento($id_tarjeta);
                                # Envío de correo para el responsable
                                if ($id_responsable != '')
                                    $helper->sendMailTarjeta($data_reconocimiento, 6);
                            }
                        } else {
                            echo json_encode($datos_significancia['errores']);
                        }
                    }
                    break;
                case '2': // Oportunidad de Mejora
                    # Tarjeta de Observación Abierta y con Oportunidad de Mejora
                    $datos_hallazgos = $this->verify_plan_accion();

                    if ($datos_hallazgos['exito']) {

                        # Se cargan los Riesgos Observados
                        $significancia = $this->request->getPost('significancia');

                        $datos_significancia = $this->verify_significancia($significancia);

                        if (!empty($datos_significancia) && !isset($datos_significancia['errores'])) {
                            $datos_tarjeta['situacion'] = 1;
                            $results = $this->model_tarjeta->addSubmit($datos_tarjeta);
                            $id_tarjeta = $results['last_id'];

                            foreach ($datos_significancia as $r) {
                                $riesgos = [
                                    'id_tarjeta' => $id_tarjeta,
                                    'id_significancia' => $r,
                                ];
                                $this->model_general->insertG('tarjeta_rel_significancia', $riesgos);
                            }

                            $id_hallazgo = $this->submitPlanAccion($datos_hallazgos['plan'], $id_tarjeta);
                            $id_responsable = $datos_hallazgos['plan']['responsable'];
                            $id_relevo_responsable = $datos_hallazgos['plan']['relevo_responsable'];

                            # Envío de correo para el que carga la tarjeta
                            if (isset($id_tarjeta)) {
                                $datos_hallazgo['datos'] = $this->model_mail_tarjeta->getInfoTarjetaCreada($id_tarjeta, $id_hallazgo);

                                # Envío de correo para el responsable
                                if ($id_responsable != '')
                                    $helper->sendMailTarjeta($datos_hallazgo, 2);

                                # Envío correo para el relevo de responsable (Si es que existe)
                                if ($id_relevo_responsable != '') {
                                    $datos_hallazgo['datos'] = $this->model_mail_tarjeta->getInfoTarjetaCreada($id_tarjeta, $id_hallazgo, true);
                                    $helper->sendMailTarjeta($datos_hallazgo, 5);
                                }
                            }
                        } else {
                            echo json_encode($datos_significancia['errores']);
                        }
                    } else {
                        echo json_encode($datos_hallazgos['errores']);
                    }
                    break;
            }


            # Se cargan los Efectos/Impactos (Si es que hay)
            if (isset($id_tarjeta)) {
                $efectos_impactos = $this->request->getPost('efecto_impacto');

                if ($efectos_impactos)
                    $this->submitEfectoRelTarjeta($efectos_impactos, $id_tarjeta);
            }

            # Inserto los Indicadores (Si es que existen)
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

            # Inserta nuevos observadores que se relacionen con la tarjeta
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

            # Envío de correo para el que carga la tarjeta
            if (isset($id_tarjeta)) {
                // if ($id_hallazgo == '') {
                $datos_hallazgo['datos'] = $this->model_mail_tarjeta->getInfoTarjetaCreada($id_tarjeta, $id_hallazgo);
                $helper->sendMailTarjeta($datos_hallazgo, 1);
                // }
            }

            if (isset($id_tarjeta)) {
                newMov(6, 1, $id_tarjeta); //Movimiento
            }
        } else {
            echo json_encode($verificacion_tarjeta['errores']);
        }
    }

    /**
     * Verifico que los datos ingresados estén completos y no vacíos
     */
    protected function verify_plan_accion()
    {

        $datos_hallazgo = [
            'hallazgo'    => $this->request->getPost('hallazgo'),
            'plan_accion'    => $this->request->getPost('plan_accion'),
            'contratista'    => $this->request->getPost('contratista'),
            'responsable'    => $this->request->getPost('responsable'),
            'relevo_responsable'    => $this->request->getPost('relevo_responsable'),
            'fecha_cierre'    => $this->request->getPost('fecha_cierre'),
            'usuario_carga' => session()->get('id_usuario')
        ];

        $result_ejecutar_plan = $this->verificacion($datos_hallazgo, 'validation_hallazgo');

        if ($result_ejecutar_plan['exito']) {
            $result_ejecutar_plan['plan'] = $datos_hallazgo;
        }

        return $result_ejecutar_plan;
    }
    /**
     * Genera el submit del plan de acción con sus adjuntos correspondientes
     */
    protected function submitPlanAccion($data, $id_tarjeta)
    {
        $helper = new Helper();
        $data['id_tarjeta'] = $id_tarjeta;
        $results_hallazgo = $this->model_tarjeta->addSubmitHallazgo($data);

        if ($results_hallazgo['status']) {
            $id_hallazgo = $results_hallazgo['last_id'];
        }

        # Se cargan adjuntos si es que realmente existen
        if ($this->request->getPost('adj_observacion-description[]')) {
            $bd_info = array(
                'table' => 'tarjeta_hallazgos_adjuntos',
                'file' => 'adjunto',
                'description' => 'desc_adjunto',
                'optional_ids' => ['id_hallazgo' => $id_hallazgo['id']]
            );
            $helper->cargarArchivos('adj_observacion', 'uploads/tarjetaObs/', 'obs_tarjeta', $bd_info, $this->request->getPost('adj_observacion-description'));
        }



        return $id_hallazgo;
    }

    /**
     * Verifico que los datos ingresados estén completos y no vacíos
     */
    protected function verify_reconocimiento_positivo()
    {
        $result = [];

        $datos_reconocimiento = [
            'hallazgo'    => $this->request->getPost('observado'),
            'responsable'    => $this->request->getPost('responsable_reconocimiento'),
            'contratista'    => $this->request->getPost('contratista'),
            'usuario_carga' => session()->get('id_usuario')
        ];

        $result = $this->verificacion($datos_reconocimiento, 'validation_obs_positiva');

        if ($result['exito']) {
            $result['reconocimiento'] = $datos_reconocimiento;
        } else {
            echo json_encode($result['errores']);
        }

        return $result;
    }
    /**
     * Genera el submit del reconocimiento con sus adjuntos correspondientes
     */
    protected function submitReconocimientoPositivo($data, $id_tarjeta)
    {
        $helper = new Helper();
        $data['id_tarjeta'] = $id_tarjeta;
        $results_hallazgo = $this->model_tarjeta->addSubmitHallazgo($data);

        if ($results_hallazgo['status']) {
            $id_hallazgo = $results_hallazgo['last_id'];
        }

        # Se cargan adjuntos si es que realmente existen
        if ($this->request->getPost('adj_observacion_positive-description[]')) {
            $bd_info = array(
                'table' => 'tarjeta_hallazgos_adjuntos',
                'file' => 'adjunto',
                'description' => 'desc_adjunto',
                'optional_ids' => ['id_hallazgo' => $id_hallazgo['id']]
            );
            $helper->cargarArchivos('adj_observacion_positive', 'uploads/tarjetaObs/', 'obs_tarjeta', $bd_info, $this->request->getPost('adj_observacion_positive-description'));
        }
    }

    /**
     * Crea el submit de los efectos/impactos relacionados al ID de la tarjeta
     */
    protected function submitEfectoRelTarjeta($efectos_impactos, $id_tarjeta)
    {
        $efectos_separados = [];
        $efectos = join(",", $efectos_impactos);

        if ($efectos)
            $efectos_separados = explode(',', $efectos);

        /* Si hay efectos asignados, los inserto en la tabla de relación */
        if (count($efectos_separados) > 0) {
            foreach ($efectos_separados as $e) {
                $data = [
                    'id_tarjeta' => $id_tarjeta,
                    'id_efecto' => $e,
                ];
                $this->model_general->insertG('tarjeta_rel_efecto', $data);
            }
        }
    }

    /**
     * Verifica que al menos un riesgo ha sido seleccionado, caso contrario, retorna los errores
     */
    protected function verify_significancia($significancia)
    {
        $data_significancia = [];
        $datos = [];
        if ($significancia != null && count($significancia) > 0) {
            for ($i = 0; $i < count($significancia); $i++) {
                $data_significancia['significancia'] = $significancia[$i];
                $result = $this->verificacion($data_significancia, 'validation_significancia_obs');
                if ($result['exito']) {
                    $datos[] = $significancia[$i];
                } else {
                    $datos = [];
                    $datos['errores'] = $result['errores'];
                    break;
                    // echo json_encode($result['errores']);
                }
            }
        } else {
            $data_significancia['significancia'] = '';
            $result = $this->verificacion($data_significancia, 'validation_significancia_obs');
            $datos['errores'] = $result['errores'];
        }

        return $datos;
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
        $helper->sendMailTarjeta($datos, 4);
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

        /* Cierra los descargos sin rta */
        if ($cierre_forzado == 1) {
            foreach ($descargos as $d) {
                if ($d['estado'] == 0 && is_null($d['respuesta'])) {
                    $datos_actualizar_hallazgo = [
                        'estado' => '-1',
                    ];
                    $this->model_tarjeta->closeDescargoForced($datos_actualizar_hallazgo, $d['id']);
                }
            }
        }

        $datos_motivo_cierre  = [
            'motivo'    => $this->request->getPost('motivo_cierre_obs'),
            'cierre_manual'    => $cierre_forzado,
            'id_tarjeta_obs' => $id_tarjeta,
            'id_usuario_cierre' => session()->get('id_usuario'),
        ];

        $results_cierre = $this->model_tarjeta->addMotivoCierre($datos_motivo_cierre);
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

    public function testing()
    {
        $helper = new Helper();
        // $this->sendMail();
        // echo 'Se envió todo correcto';
        $data_reconocimiento['datos'] = $this->model_mail_tarjeta->getDataTarjetaReconocimiento(1);
        echo view('emails/tarjetaObs/reconocimiento', $data_reconocimiento);
        /* echo '<pre>';
        var_dump($data_reconocimiento);
        echo '</pre>';
        exit; */
        // $datos['datos'] = $this->model_mail_tarjeta->getInfoTarjetaCreada(1, 1, true);

        // $datos['url'] = base_url('/TarjetaObs/view_obs/') . '/' . 1;

        // $id = $datos['datos'][0]['id_obs'];
        // $datos['url'] = base_url('/TarjetaObs/view_obs/') . '/' . 2;
        // $correos[] = $datos['datos'][0]['correo_carga'];
        // $helper->sendMailTarjeta($datos, 1);
        // view('emails/tarjetaObs/otroResponsable', $datos);
    }
}
