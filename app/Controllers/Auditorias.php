<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use CodeIgniter\Controller;
use DateTime;
use App\Models;
use Config\Validation;
use PHPUnit\TextUI\XmlConfiguration\CodeCoverage\Report\Php;

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

    public function index()
    {
        if (!$this->session->get('isLogin')) {
            return redirect()->to('login');
        } else {
            $data['auditorias_control'] = $this->model_auditorias->getAllTitlesAuditoria();
            $data['auditorias_checklist'] = $this->model_auditorias->getAllTitlesAuditoria(0);
            $data['contratistas'] =  $this->model_general->getAllEstadoActivo('empresas');
            $data['proyectos'] =  $this->model_general->getAllEstadoActivo('proyectos');
            $data['modulos'] =  $this->model_general->getAllEstadoActivo('modulos');
            $data['estaciones'] =  $this->model_general->getAllEstadoActivo('estaciones_bombeo');
            $data['sistemas'] =  $this->model_general->getAllEstadoActivo('sistemas_oleoductos');
            return template('auditoria/index', $data);
        }
    }

    /**
     * Dynamic Table para las Auditorías de Control
     */
    public function getPagedControl($offset = NULL, $tamanioPagina = NULL)
    {

        $params = [
            'modelo_tipo_control' => $this->request->getPost("modelo_tipo_control"),
            'contratista' => $this->request->getPost("contratista"),
            'supervisor' => $this->request->getPost("supervisor"),
            'proyecto_aud_control' => $this->request->getPost("proyecto_aud_control"),
            'usuario_carga_control' => $this->request->getPost("usuario_carga_control"),
            'fecha_desde_control' => $this->request->getPost("fecha_desde_control"),
            'fecha_hasta_control' => $this->request->getPost("fecha_hasta_control")
        ];

        if ((is_numeric($offset) && $offset >= 0) && (is_numeric($tamanioPagina) && $tamanioPagina > 0)) {
            $response = $this->model_auditorias->getAllPagedControl($offset, $tamanioPagina, $params);
        } else {
            if (is_null($offset) && is_null($tamanioPagina)) {
                $response = $this->model_auditorias->getAllPagedControl($offset, $tamanioPagina, $params, true);
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
     * Dynamic Table para las Auditorías de CheckList Vehicular
     */
    public function getPagedVehicular($offset = NULL, $tamanioPagina = NULL)
    {

        $params = [
            'id_aud_vehicular' => $this->request->getPost("id_aud_vehicular"),
            'modelo_tipo_vehicular' => $this->request->getPost("modelo_tipo_vehicular"),
            'equipo' => $this->request->getPost("equipo"),
            'conductor' => $this->request->getPost("conductor"),
            'num_interno_vehicular' => $this->request->getPost("num_interno_vehicular"),
            'titular' => $this->request->getPost("titular"),
            'proyecto_aud_vehicular' => $this->request->getPost("proyecto_aud_vehicular"),
            'resultado_inspeccion' => $this->request->getPost("resultado_inspeccion"),
            'usuario_carga_vehicular' => $this->request->getPost("usuario_carga_vehicular"),
            'fecha_desde_vehicular' => $this->request->getPost("fecha_desde_vehicular"),
            'fecha_hasta_vehicular' => $this->request->getPost("fecha_hasta_vehicular"),
        ];

        if ((is_numeric($offset) && $offset >= 0) && (is_numeric($tamanioPagina) && $tamanioPagina > 0)) {
            $response = $this->model_auditorias->getAllPagedVehicular($offset, $tamanioPagina, $params);
        } else {
            if (is_null($offset) && is_null($tamanioPagina)) {
                $response = $this->model_auditorias->getAllPagedVehicular($offset, $tamanioPagina, $params, true);
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
     * Cambia el estado de la auditoría de ambos históricos (Aud.Control | Aud.Vehicular)
     */
    public function changeState($id_auditoria, $tipo_aud)
    {
        if ($tipo_aud) {
            $this->model_auditorias->changeState($id_auditoria, 'auditoria_control');
        } else {
            $this->model_auditorias->changeState($id_auditoria, 'auditoria_vehicular');
        }
    }

    /**
     * Vista para la Auditoría de Control
     */
    public function view_aud_control($id_aud)
    {
        if (!$this->session->get('isLogin')) {
            return redirect()->to('login');
        } else {
            $data['auditoria'] =  $this->getBloqueForViewControl($id_aud);
            $data['hallazgo'] =  $this->model_auditorias->getHallazgoAud($id_aud);

            $h = $data['hallazgo'];
            $diff = '';
            $rta_cierre_obs = false;
            
            if (!is_null($h['cierre'])) {
                $fecha1 = isset($h['fecha_cierre']) ? new DateTime($h['fecha_cierre']) : new DateTime($h['cierre']['fecha_hora_cierre']);
                $fecha2 = new DateTime($h['cierre']['fecha_hora_cierre']);
                $fecha1_tiempo = isset($h['fecha_cierre']) ? strtotime($h['fecha_cierre']) : strtotime($h['cierre']['fecha_hora_cierre']);
                $fecha2_tiempo = strtotime($h['cierre']['fecha_hora_cierre']);
            
                if ($fecha1_tiempo > $fecha2_tiempo) {
                    $rta_cierre_obs = true;
                    $diff = $fecha1->diff($fecha2);
                } else if ($fecha1_tiempo < $fecha2_tiempo) {
                    $rta_cierre_obs = false;
                    $diff = $fecha1->diff($fecha2);
                } else if ($fecha1_tiempo == $fecha2_tiempo) {
                    $rta_cierre_obs = true;
                    $diff = $fecha1->diff($fecha2);
                }
            }

            $data['hallazgo']['tiempo']['diff'] = $diff;
            $data['hallazgo']['tiempo']['rta_cierre_obs'] = $rta_cierre_obs;


            return template('auditoria/view_control', $data);
        }
    }
    /**
     * Vista para la Auditoría de CheckList Vehicular
     */
    public function view_aud_vehicular($id_aud)
    {
        if (!$this->session->get('isLogin')) {
            return redirect()->to('login');
        } else {
            $data['tipo_obs_vehicular'] = $this->model_general->getAllEstadoActivo('tipo_obs_vehicular');
            $data['auditoria'] =  $this->getBloqueForViewVehicular($id_aud);
            return template('auditoria/view_vehicular', $data);
        }
    }

    /**
     * Genera todo el bloque completo de la auditoría con las preguntas y rtas
     */
    protected function getBloqueForViewControl($id_aud)
    {
        $auditoria = $this->model_auditorias->getBloqueCompletoAudControl($id_aud);

        if ($auditoria) {
            $id_titulo_aud = $auditoria['modelo_tipo'];
            $auditoria['bloque'] = $this->model_auditorias->getSubtitulosAuditorias($id_titulo_aud);

            foreach ($auditoria['bloque'] as $key => $subtitulo) {
                /* == Carga las preguntas == */
                $auditoria['bloque'][$key]['preguntas_rtas'] = $this->model_auditorias->getIdPreguntasAuditorias($subtitulo['id'], $id_titulo_aud);

                $preguntas = $auditoria['bloque'][$key]['preguntas_rtas'];

                /* == Busca por id pregunta su correspondiente respuesta == */
                foreach ($preguntas as $llave => $p) {
                    $auditoria['bloque'][$key]['preguntas_rtas'][$llave]['respuesta'] = $this->model_auditorias->getRtasFromPreguntaAudControl($id_aud, $p['id_pregunta']);
                }
            }
        } else {
            $auditoria = [];
        }
        return $auditoria;
    }

    /**
     * Genera todo el bloque completo de la auditoría con las preguntas y rtas
     */
    protected function getBloqueForViewVehicular($id_aud)
    {
        $auditoria = $this->model_auditorias->getBloqueCompletoAudVehicular($id_aud);

        if ($auditoria) {
            $id_titulo_aud = $auditoria['modelo_tipo'];
            $auditoria['bloque'] = $this->model_auditorias->getSubtitulosAuditorias($id_titulo_aud);

            foreach ($auditoria['bloque'] as $key => $subtitulo) {
                /* == Carga las preguntas == */
                $auditoria['bloque'][$key]['preguntas_rtas'] = $this->model_auditorias->getIdPreguntasAuditorias($subtitulo['id'], $id_titulo_aud);

                $preguntas = $auditoria['bloque'][$key]['preguntas_rtas'];

                /* == Busca por id pregunta su correspondiente respuesta == */
                foreach ($preguntas as $llave => $p) {
                    $auditoria['bloque'][$key]['preguntas_rtas'][$llave]['respuesta'] = $this->model_auditorias->getRtasFromPreguntaAudVehicular($id_aud, $p['id_pregunta']);
                }
            }
        } else {
            $auditoria = [];
        }
        return $auditoria;
    }

    /**
     * Vista para poder agregar una nueva auditoría (Aud.Control | Aud.Vehicular)
     */
    public function add()
    {
        if (!$this->session->get('isLogin')) {
            return redirect()->to('login');
        } else {
            $data['tipo_obs_vehicular'] = $this->model_general->getAllEstadoActivo('tipo_obs_vehicular');

            /* Los tipos de auditorías */
            $data['auditorias_control'] = $this->model_auditorias->getAllTitlesAuditoria();
            $data['auditorias_checklist'] = $this->model_auditorias->getAllTitlesAuditoria(0);
            $data['usuarios'] = $this->model_general->getAllActivo('usuario');
            $data['contratistas'] =  $this->model_general->getAllEstadoActivo('empresas');
            $data['efectos_impactos'] =  $this->model_general->getAllEstadoActivo('efectos_impactos');
            $data['proyectos'] =  $this->model_general->getAllEstadoActivo('proyectos');
            $data['modulos'] =  $this->model_general->getAllEstadoActivo('modulos');
            $data['estaciones'] =  $this->model_general->getAllEstadoActivo('estaciones_bombeo');
            $data['sistemas'] =  $this->model_general->getAllEstadoActivo('sistemas_oleoductos');
            return template('auditoria/add', $data);
        }
    }

    /**
     * Trae el bloque de preguntas completo de esa auditoría
     * La retorna en forma de JSON
     */
    public function getBloqueAud($id_auditoria)
    {
        $data['bloque'] = $this->model_auditorias->getBloqueAud($id_auditoria);
        echo json_encode($data);
    }

    /**
     * Vista para poder crear una planilla (Nueva Auditoría)
     */
    public function addPlanilla()
    {
        if (!$this->session->get('isLogin')) {
            return redirect()->to('login');
        } else {
            return template('auditoria/addPlanilla');
        }
    }

    /**
     * Crea una nueva auditoría con sus títulos y preguntas correspondientes
     */
    public function submitCrearNewAuditoria()
    {
        $results = $this->validacion_crear_planilla();

        if ($results['exito']) {
            /* == Inserto el título de la auditoría == */
            $titulo_auditoria = [
                'nombre' => $this->request->getPost('title_auditoria'),
                'tipo_aud' => $this->request->getPost('tipo_aud'),
                'usuario_carga' => session()->get('id_usuario')
            ];
            $id_title = $this->model_general->insertG('auditorias_titulos', $titulo_auditoria);

            $contenedor = $this->request->getPost('contenedor');
            $aux = 1;
            for ($i = 0; $i < count($contenedor); $i++) {

                $data_subt = [
                    'nombre' => $contenedor[$i]['subtitle'],
                    'id_titulo' => $id_title
                ];
                $id_subtitle = $this->model_general->insertG('auditorias_subtitulos', $data_subt);

                for ($j = 0; $j < count($contenedor[$i]['preguntas']); $j++) {
                    $data_ask = [
                        'pregunta' => $contenedor[$i]['preguntas'][$j],
                        'subtitulo' => $id_subtitle,
                        'orden' => $aux,
                        'titulo' => $id_title
                    ];
                    $this->model_general->insertG('auditorias_preguntas', $data_ask);
                    $aux++;
                }
            }
        } else {
            echo json_encode($results['errores']);
        }
    }

    /**
     * Valida que los datos de la auditoría no esté vacía
     * Valida el título | los subtitulos | y las preguntas
     * La validación es media forzada, pero no sé como realizar otro tipo de implementación
     * más limpia.
     */
    protected function validacion_crear_planilla()
    {
        $result = [];

        /* == Validar el Título de la Auditoría == */
        $dato_title = [
            'nombre' => $this->request->getPost('title_auditoria')
        ];

        $result = $this->verificacion($dato_title, 'validation_title_aud');

        if ($result['exito']) {
            /* == Validar el Subtitulo de cada bloque de la Auditoría == */
            $contenedor = $this->request->getPost('contenedor');

            $data_subtitles = [];

            foreach ($contenedor as $d) {
                $data_subtitles['subtitle'] = $d['subtitle'];
                $result = $this->verificacion($data_subtitles, 'validation_subtitle_aud');

                if (!$result['exito']) break;
            }

            if ($result['exito']) {

                $data_preguntas = [];
                for ($i = 0; $i < count($contenedor); $i++) {
                    if (isset($contenedor[$i])) {
                        foreach ($contenedor[$i]['preguntas'] as $d) {
                            $data_preguntas['preguntas'] = $d;
                            $result = $this->verificacion($data_preguntas, 'validation_preguntas_aud');
                            if (!$result['exito']) break;
                        }
                    }
                }
            }
        }

        return $result;
    }

    /**
     * Carga la Auditoría (de Control o Vehicular a la BD)
     * Dentro hace el llamado al método submitRtaPreguntasAud() el cual carga las respuestas
     */
    public function submitUpload()
    {
        $aud_tipo = $this->request->getPost('aud_tipo');
        $oportunidad_mejora = $this->request->getPost('oportunidad_mejora');

        $bloque_respuestas = $this->request->getPost('bloque_respuestas');
        $comentarios_preguntas = $this->request->getPost('comentarios_preguntas');
        $modelo_tipo = $this->request->getPost('tipo_auditoria');

        if ($aud_tipo) { // Auditoría de Control
            $datos = [
                'modelo_tipo' => $modelo_tipo,
                'contratista' => $this->request->getPost('contratista'),
                'supervisor_responsable' => $this->request->getPost('supervisor_responsable'),
                'cant_personal' => $this->request->getPost('cant_personal'),
                'num_informe' => $this->request->getPost('num_informe'),
                'proyecto' => $this->request->getPost('proyecto'),
                'modulo' => $this->request->getPost('modulo'),
                'estacion' => $this->request->getPost('estacion_bombeo'),
                'sistema' => $this->request->getPost('sistema'),
                'usuario_carga' => session()->get('id_usuario'),
            ];

            $result = $this->verificacion($datos, 'validation_aud_control');

            if ($result['exito']) {

                /* == Si posee plan de acción, entonces se agrega == */
                if ($oportunidad_mejora) {

                    /* Plan de Acción */
                    $datos_plan_accion = [
                        // 'id_auditoria' => $id_aud,
                        'tipo_auditoria' => 1, // Esto es por el momento solo para la Auditoría de Control
                        'hallazgo' => $this->request->getPost('hallazgo'),
                        'plan_accion' => $this->request->getPost('plan_accion'),
                        'efecto_impacto' => $this->request->getPost('efecto_impacto'),
                        'contratista' => $this->request->getPost('contratista_plan'),
                        'significancia' => $this->request->getPost('significancia'),
                        'responsable' => $this->request->getPost('responsable_plan'),
                        'relevo_responsable' => $this->request->getPost('relevo_responsable_plan'),
                        'fecha_cierre' => $this->request->getPost('fecha_cierre'),
                        'usuario_carga' => session()->get('id_usuario'),
                    ];

                    $result_plan = $this->verificacion($datos_plan_accion, 'validation_aud_plan');

                    if ($result_plan['exito']) {

                        $id_aud = $this->model_general->insertG('auditoria_control', $datos);
                        $this->submitRtaPreguntasAud($id_aud, $aud_tipo, $modelo_tipo, $bloque_respuestas, $comentarios_preguntas);
                        
                        $datos_plan_accion['id_auditoria'] = $id_aud;
                        $this->submitUploadPlanAccion($datos_plan_accion);

                    } else {
                        echo json_encode($result_plan['errores']);
                    }
                } else {
                    $id_aud = $this->model_general->insertG('auditoria_control', $datos);
                    $this->submitRtaPreguntasAud($id_aud, $aud_tipo, $modelo_tipo, $bloque_respuestas, $comentarios_preguntas);
                }
            } else {
                echo json_encode($result['errores']);
            }
        } else { // Auditoría Vehicular
            $datos = [
                'modelo_tipo' => $modelo_tipo,
                'equipo' => $this->request->getPost('equipo'),
                'conductor' => $this->request->getPost('conductor'),
                'num_interno' => $this->request->getPost('num_interno'),
                'marca' => $this->request->getPost('marca'),
                'modelo' => $this->request->getPost('modelo'),
                'patente' => $this->request->getPost('patente'),
                'titular' => $this->request->getPost('titular'),
                'fecha' => $this->request->getPost('fecha_hoy'),
                'hora' => $this->request->getPost('hora'),
                'proyecto' => $this->request->getPost('proyecto'),
                'tarea_que_realiza' => $this->request->getPost('tarea_realiza'),
                'resultado_inspeccion' => $this->request->getPost('resultado_inspeccion'),
                'medidas_implementar' => $this->request->getPost('medidas_implementar'),
                'usuario_carga' => session()->get('id_usuario'),
            ];

            $tipo_obs = $this->request->getPost('tipo_obs');

            $result = $this->verificacion($datos, 'validation_aud_vehicular');

            if ($result['exito']) {
                $id_aud = $this->model_general->insertG('auditoria_vehicular', $datos);
                $this->submitRtaPreguntasAud($id_aud, $aud_tipo, $modelo_tipo, $bloque_respuestas, $comentarios_preguntas, $tipo_obs);
            }
        }
    }

    /**
     * Carga un plan de acción dependiendo la auditoría que se envía por parámetro
     */
    public function submitUploadPlanAccion($datos)
    {

        $efectos_separados = [];
        // $efectos = join(",", $this->request->getPost('efecto_impacto'));
        $efectos = join(",", $datos['efecto_impacto']);

        if ($efectos)
            $efectos_separados = explode(',', $efectos);

        $id_hallazgo = $this->model_general->insertG('obs_hallazgos', $datos);

        /** == Cargar Adjuntos del plan de Acción (Si Corresponde) en BD == **/
        $upload_ruta = "uploads/auditorias/hallazgos/";
        $desc_adjuntos = $this->request->getPost('adj_observacion-description');
        for ($i = 0; $i < count($_FILES["adj_observacion"]["name"]); $i++) {

            /* == Si no está vacio.. == */
            if (!empty($_FILES["adj_observacion"]["name"][$i])) {
                $target_file = $upload_ruta . basename($_FILES["adj_observacion"]["name"][$i]);
                $archivoTemporal = $_FILES["adj_observacion"]["tmp_name"][$i];

                $extension = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));
                $nombre = "adj_auditoria_" . $i . "_" . date('Y-m-d-H-i-s') . "." . $extension;
                $uploadPath = $upload_ruta . $nombre;
                $archivoCargado = false;

                if (in_array(strtolower($extension), ["pdf", "jpg", "jpeg", "png", "doc", "docx", "docm", "xls", "xlsx", "xlsb"])) {
                    if (move_uploaded_file($archivoTemporal, $uploadPath)) {
                        $archivoCargado = true;
                    }
                }

                if ($archivoCargado) {
                    $dato_bd = [
                        'id_hallazgo' => $id_hallazgo,
                        'adjunto' => $nombre,
                        'desc_adjunto' => $desc_adjuntos[$i],
                    ];
                    $this->model_general->insertG('obs_hallazgos_adj', $dato_bd);
                }
            }
        }

        /* Si hay efectos asignados, los inserto en la tabla de relación */
        if (count($efectos_separados) > 0) {
            foreach ($efectos_separados as $e) {
                $data = [
                    'id_hallazgo' => $id_hallazgo,
                    'id_efecto' => $e,
                ];
                $this->model_general->insertG('obs_rel_hallazgo_efecto', $data);
            }
        }

        $significancia = $datos['significancia'];

        /* Si hay riesgos asignados, los inserto en la tabla de relación */
        if (count($significancia) > 0) {
            foreach ($significancia as $s) {
                $data = [
                    'id_hallazgo' => $id_hallazgo,
                    'id_significancia' => $s,
                ];
                $this->model_general->insertG('obs_rel_hallazgo_significancia', $data);
            }
        }
    }

    /**
     * Carga todas las respuestas de las preguntas pertenecientes a la Auditorías
     */
    protected function submitRtaPreguntasAud($id_aud, $aud_tipo, $modelo_tipo, $bloque_respuestas, $comentarios_preguntas, $tipo_obs = [])
    {
        /* == Preguntas y Respuestas == */
        $respuestas = [];
        $comentarios = [];
        $tabla = '';

        foreach ($bloque_respuestas as $bloque => $bloque_rta) {
            foreach ($bloque_rta['respuestas'] as $pregunta => $rta) {
                $respuestas = [
                    'id_auditoria' => $id_aud,
                    'modelo_tipo' => $modelo_tipo,
                    'id_subtitulo' => $bloque,
                    'id_pregunta' => $pregunta,
                    'pregunta' => $rta['pregunta'],
                    'rta' => $rta['rta'],
                ];

                if ($aud_tipo) {
                    $this->model_general->insertG('aud_rtas_control', $respuestas);
                    $tabla = 'aud_rtas_control';
                } else {
                    $this->model_general->insertG('aud_rtas_vehicular', $respuestas);
                    $tabla = 'aud_rtas_vehicular';
                }
            }
        }

        /* == Tipo de OBS Checklist Vehicular == */
        if (count($tipo_obs) > 0) {
            foreach ($tipo_obs as $pregunta => $ob) {
                $obs = [
                    'id_auditoria' => $id_aud,
                    'id_pregunta' => $pregunta,
                    'tipo_obs' => $ob,
                ];
                $this->model_auditorias->updateTipoObsRta($obs);
            }
        }

        /* == Comentarios == */
        foreach ($comentarios_preguntas as $pregunta => $comentario) {
            $comentarios = [
                'id_auditoria' => $id_aud,
                'id_pregunta' => $pregunta,
                'comentario' => $comentario,
            ];
            $this->model_auditorias->updateComentarioRta($comentarios, $tabla);
        }
    }

    //-----------------------------------------------------------------------------------------------------------
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
}
