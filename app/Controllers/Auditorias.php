<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use CodeIgniter\Controller;
use DateTime;
use App\Models;
use App\Models\Model_general;
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
        $this->model_mail_auditoria = model('Model_mail_auditoria');
        $this->model_aud_control = model('Model_aud_control');
        $this->model_aud_vehicular = model('Model_aud_vehicular');
    }

    public function index()
    {
        if (!$this->session->get('isLogin')) {
            return redirect()->to('login');
        } else {
            $data['auditorias_control'] = $this->model_auditorias->getAllTitlesAuditoria();
            $data['auditorias_checklist'] = $this->model_auditorias->getAllTitlesAuditoria(0);
            $data['auditorias_tarea_de_campo'] = $this->model_auditorias->getAllTitlesAuditoria(3);
            $data['auditorias_auditoria'] = $this->model_auditorias->getAllTitlesAuditoria(4);
            $data['contratistas'] = $this->model_general->getAllEstadoActivo('empresas');
            $data['proyectos'] = $this->model_general->getAllEstadoActivo('proyectos');
            $data['modulos'] = $this->model_general->getAllEstadoActivo('modulos');
            $data['estaciones'] = $this->model_general->getAllEstadoActivo('estaciones_bombeo');
            $data['sistemas'] = $this->model_general->getAllEstadoActivo('sistemas_oleoductos');
            return template('auditoria/index', $data);
        }
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
     * Generar un nuevo descargo
     */
    public function submitDescargo()
    {
        $helper = new Helper();

        $id_hallazgo = $this->request->getPost('id_hallazgo');
        $tipo_obs = $this->request->getPost('tipo_obs');

        $datos_descargo = [
            'id_hallazgo' => $id_hallazgo,
            'motivo' => $this->request->getPost('new_descargo'),
            'id_usuario' => session()->get('id_usuario'),
        ];

        $results = $this->model_auditorias->addDescargo($datos_descargo);
        $id_descargo = $results['last_id']['id'];

        # Envío de correo

        if ($tipo_obs == 1) { # En caso de que sea una Auditoría de Control
            $datos_emails = $this->model_mail_auditoria->getInfoNewDescargoAudControl($id_hallazgo, $id_descargo);
            $url = base_url('/auditorias/view_aud_control/') . '/' . $datos_emails['id_hallazgo'];

            $emails[] = $datos_emails['correo_carga'];

            $helper->sendMail($datos_emails, 'Nuevo Descargo | Auditoría Control #', $url, 'emails/auditorias/control/descargo', $emails, 'id_auditoria');
        } else {
            if ($tipo_obs == 3) {
                $datos_emails = $this->model_mail_auditoria->getInfoNewDescargoAudTarea_de_campo($id_hallazgo, $id_descargo);
                $url = base_url('/auditorias/view_aud_tarea_campo/') . '/' . $datos_emails['id_hallazgo'];

                $emails[] = $datos_emails['correo_carga'];
                $helper->sendMail($datos_emails, 'Nuevo Descargo | CheckList Tarea de Campo #', $url, 'emails/auditorias/tarea_de_campo/descargo', $emails, 'id_auditoria');
            } else {
                if ($tipo_obs == 4) {
                    $datos_emails = $this->model_mail_auditoria->getInfoNewDescargoAudAuditoria($id_hallazgo, $id_descargo);
                    $url = base_url('/auditorias/view_aud_auditoria/') . '/' . $datos_emails['id_auditoria'];
                    $emails[] = $datos_emails['correo_carga'];
                    $helper->sendMail($datos_emails, 'Nuevo Descargo | CheckList Auditoria #', $url, 'emails/auditorias/tarea_de_campo/descargo', $emails, 'id_auditoria');
                } else {
                    $datos_emails = $this->model_mail_auditoria->getInfoNewDescargoAudVehicular($id_hallazgo, $id_descargo);
                    $url = base_url('/auditorias/view_aud_vehicular/') . '/' . $datos_emails['id_hallazgo'];
                    $emails[] = $datos_emails['correo_carga'];
                    $helper->sendMail($datos_emails, 'Nuevo Descargo | CheckList Vehicular #', $url, 'emails/auditorias/vehicular/descargo', $emails, 'id_auditoria');
                }
            }
            # En caso de que sea una Auditoría de CheckList Vehicular

        }

        # Se cargan adjuntos si es que realmente existen
        if ($this->request->getPost('adj_descargo-description')) {
            $bd_info = array(
                'table' => 'obs_descargos_adjuntos',
                'file' => 'adjunto',
                'description' => 'desc_adjunto',
                'optional_ids' => ['id_descargo' => $id_descargo]
            );
            $helper->cargarArchivos('adj_descargo', 'uploads/auditorias/descargos/', 'adj_descargo', $bd_info, $this->request->getPost('adj_descargo-description'));
        }
        return $results;
    }

    /**
     * Dar una respuesta a un descargo
     */
    public function submitRtaDescargo()
    {
        $helper = new Helper();
        $id_descargo = $this->request->getPost('inp_id_descargo');
        $tipo_obs = $this->request->getPost('tipo_obs');

        /* == Si es 1 se aceptó el descargo | Si es 2 se rechazó == */
        $estado = ($this->request->getPost('tipo_rta_descargo') == 1) ? 1 : 2;

        $datos_rta_descargo = [
            'estado' => $estado,
            'respuesta' => $this->request->getPost('rta_descargo'),
            'id_usuario_rta' => session()->get('id_usuario'),
            'fecha_hora_respuesta' => date('Y-m-d H:i:s'),
        ];

        $this->model_auditorias->editDescargo($datos_rta_descargo, $id_descargo);

        # Envío de correo

        if ($tipo_obs == 1) { # En caso de que sea una Auditoría de Control
            $datos_emails = $this->model_mail_auditoria->getRespuestaDescargoAudControl($id_descargo);
            $url = base_url('/auditorias/view_aud_control/') . '/' . $datos_emails['id_auditoria'];
            $emails[] = $datos_emails['correo_responsable'];
            $helper->sendMail($datos_emails, 'Nueva Respuesta | Auditoría Control #', $url, 'emails/auditorias/control/respuesta', $emails, 'id_auditoria');
        } else {
            if ($tipo_obs == 3) {

                $datos_emails = $this->model_mail_auditoria->getRespuestaDescargoAudTarea_campo($id_descargo);
                $url = base_url('/auditorias/view_aud_tarea_campo/') . '/' . $datos_emails['id_auditoria'];
                $emails[] = $datos_emails['correo_responsable'];
                $helper->sendMail($datos_emails, 'Nueva Respuesta | CheckList Tarea de Campo #', $url, 'emails/auditorias/tarea_de_campo/respuesta', $emails, 'id_auditoria');
            } else {
                if ($tipo_obs == 4) {
                    $datos_emails = $this->model_mail_auditoria->getRespuestaDescargoAudAuditoria($id_descargo);
                    $url = base_url('/auditorias/view_aud_auditoria/') . '/' . $datos_emails['id_auditoria'];
                    $emails[] = $datos_emails['correo_responsable'];
                    $helper->sendMail($datos_emails, 'Nueva Respuesta | CheckList Auditoria #', $url, 'emails/auditorias/auditoria/respuesta', $emails, 'id_auditoria');
                } else {
                    $datos_emails = $this->model_mail_auditoria->getRespuestaDescargoAudVehicular($id_descargo);
                    $url = base_url('/auditorias/view_aud_vehicular/') . '/' . $datos_emails['id_auditoria'];
                    $emails[] = $datos_emails['correo_responsable'];
                    $helper->sendMail($datos_emails, 'Nueva Respuesta | CheckList Vehicular #', $url, 'emails/auditorias/vehicular/respuesta', $emails, 'id_auditoria');
                }
            }
        }
    }

    public function submitCerrarHallazgo()
    {
        $id_hallazgo = $this->request->getPost('id_hallazgo');
        $cierre_forzado = $this->request->getPost('cierre_forzado');
        $descargos = $this->model_aud_control->getDescargosHallazgo($id_hallazgo);

        /* Cierra la observación */
        $datos_actualizar = [
            'situacion' => 0
        ];
        $this->model_general->updateG('obs_hallazgos', $id_hallazgo, $datos_actualizar);

        /* Cierra los descargos sin rta */
        if ($cierre_forzado == 1) {
            foreach ($descargos as $d) {
                if ($d['estado_descargo'] == 0 && is_null($d['respuesta'])) {
                    $datos_actualizar_hallazgo = [
                        'estado' => '-1',
                    ];
                    $this->model_aud_control->cerrarDescargo($datos_actualizar_hallazgo, $d['id_descargo']);
                }
            }
        }

        /* Cierre con el Motivo */
        $datos_motivo_cierre = [
            'motivo' => $this->request->getPost('motivo_cierre_obs'),
            'cierre_manual' => $cierre_forzado,
            'id_hallazgo_obs' => $id_hallazgo,
            'id_usuario_cierre' => session()->get('id_usuario'),
        ];

        $results_cierre = $this->model_aud_control->addMotivoCierre($datos_motivo_cierre);
    }

    /**
     * Vista para poder agregar una nueva auditoría
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
            $data['auditorias_tarea_de_campo'] = $this->model_auditorias->getAllTitlesAuditoria(3);
            $data['auditorias_auditoria'] = $this->model_auditorias->getAllTitlesAuditoria(4);
            $data['usuarios'] = $this->model_general->getAllActivo('usuario');
            $data['contratistas'] = $this->model_general->getAllEstadoActivo('empresas');
            $data['efectos_impactos'] = $this->model_general->getAllEstadoActivo('efectos_impactos');
            $data['proyectos'] = $this->model_general->getAllEstadoActivo('proyectos');
            $data['modulos'] = $this->model_general->getAllEstadoActivo('modulos');
            $data['estaciones'] = $this->model_general->getAllEstadoActivo('estaciones_bombeo');
            $data['sistemas'] = $this->model_general->getAllEstadoActivo('sistemas_oleoductos');
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

            foreach ($contenedor as $key => $c) {
                $data_subt = [
                    'nombre' => $c['subtitle'],
                    'id_titulo' => $id_title
                ];
                $id_subtitle = $this->model_general->insertG('auditorias_subtitulos', $data_subt);

                foreach ($c['preguntas'] as $llave => $p) {
                    $data_ask = [
                        'pregunta' => $p,
                        'subtitulo' => $id_subtitle,
                        'orden' => $aux,
                        'titulo' => $id_title
                    ];
                    $this->model_general->insertG('auditorias_preguntas', $data_ask);
                    $aux++;
                }
            }
            exit;
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

                if (!$result['exito'])
                    break;
            }

            if ($result['exito']) {

                $data_preguntas = [];
                for ($i = 0; $i < count($contenedor); $i++) {
                    if (isset($contenedor[$i])) {
                        foreach ($contenedor[$i]['preguntas'] as $d) {
                            $data_preguntas['preguntas'] = $d;
                            $result = $this->verificacion($data_preguntas, 'validation_preguntas_aud');
                            if (!$result['exito'])
                                break;
                        }
                    }
                }
            }
        }

        return $result;
    }

    /**
     * Carga un plan de acción dependiendo la auditoría que se envía por parámetro
     */
    public function submitUploadPlanAccion($datos, $efectos_parametros)
    {
        $helper = new Helper();
        $efectos_separados = [];
        $efectos = join(",", $efectos_parametros);

        if ($efectos)
            $efectos_separados = explode(',', $efectos);

        $id_hallazgo = $this->model_general->insertG('obs_hallazgos', $datos);

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

        # Se cargan adjuntos si es que realmente existen
        if ($this->request->getPost('adj_observacion-description[]')) {
            $bd_info = array(
                'table' => 'obs_hallazgos_adj',
                'file' => 'adjunto',
                'description' => 'desc_adjunto',
                'optional_ids' => ['id_hallazgo' => $id_hallazgo]
            );
            $helper->cargarArchivos('adj_observacion', 'uploads/auditorias/hallazgos/', 'adj_auditoria', $bd_info, $this->request->getPost('adj_observacion-description'));
        }

        return $id_hallazgo;
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

                // if ($aud_tipo == 1) {
                //     $this->model_general->insertG('aud_rtas_control', $respuestas);
                //     $tabla = 'aud_rtas_control';
                // } else {
                //     if ($aud_tipo == 0) {
                //         $this->model_general->insertG('aud_rtas_vehicular', $respuestas);
                //         $tabla = 'aud_rtas_vehicular';
                //     } else {
                //         if ($aud_tipo == 3) {
                //             $this->model_general->insertG('aud_rtas_tarea_de_campo', $respuestas);
                //             $tabla = 'aud_rtas_tarea_de_campo';
                //         } else {
                //             $this->model_general->insertG('aud_rtas_auditorias', $respuestas);
                //             $tabla = 'aud_rtas_auditorias';
                //         }
                //     }
                // }

                if ($aud_tipo == 1) {
                    $this->model_general->insertG('aud_rtas_control', $respuestas);
                    $tabla = 'aud_rtas_control';
                } else if ($aud_tipo == 0) {
                    $this->model_general->insertG('aud_rtas_vehicular', $respuestas);
                    $tabla = 'aud_rtas_vehicular';
                } else if ($aud_tipo == 3) {
                    $this->model_general->insertG('aud_rtas_tarea_de_campo', $respuestas);
                    $tabla = 'aud_rtas_tarea_de_campo';
                } else {
                    $this->model_general->insertG('aud_rtas_auditorias', $respuestas);
                    $tabla = 'aud_rtas_auditorias';
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

    public function testing()
    {
        $helper = new Helper();
        # Para el usuario que carga.

        // $datos = $this->model_aud_control->getDataHallazgoEmail(1, 2, 1);
        // $url = base_url('/auditorias/view_aud_control/') . '/' . $datos['id'];
        // $emails[]= $datos['correo_responsable'];
        // $datos['url'] = $url;
        // $emails = [];
        // $emails[]= $datos['correo_usuario_carga'];
        // $helper->sendMail($datos, 'Nueva Auditoría Control #', $url, 'emails/auditorias/control/nueva', $emails);
        // echo view('emails/auditorias/control/nueva', $datos);

        # Para el responsable
        $datos_emails = $this->model_mail_auditoria->getRespuestaDescargoAudControl(11);
        $emails[] = $datos_emails['correo_responsable'];
        $url = base_url('/auditorias/view_aud_control/') . '/' . $datos_emails['id'];
        $datos_emails['url'] = $url;
        $helper->sendMail($datos_emails, 'Nueva Respuesta | Auditoría Control #', $url, 'emails/auditorias/control/respuesta', $emails);
        // $url = base_url('/auditorias/view_aud_vehicular/') . '/' . $datos['id'];
        // $emails = [];
        // $emails[]= $datos['correo_relevo'];
        // $helper->sendMail($datos, 'Nuevo CheckList Vehicular #', $url, 'emails/auditorias/vehicular/nueva', $emails);
        echo view('emails/auditorias/vehicular/respuesta', $datos_emails);
    }


    ############################# EDICIÓN AUDITORIAS #############################

    /**
     * Trae todas las auditorías para poder editar y/o eliminar las que sean necesarias
     */
    public function getAllAuds()
    {
        if (!$this->session->get('isLogin')) {
            return redirect()->to('login');
        }
        $data['auditorias_control'] = $this->model_auditorias->getAllTitlesAuditoria(1, true);
        $data['auditorias_checklist'] = $this->model_auditorias->getAllTitlesAuditoria(0, true);
        $data['auditorias_tarea_de_campo'] = $this->model_auditorias->getAllTitlesAuditoria(3, true);
        $data['auditorias_auditoria'] = $this->model_auditorias->getAllTitlesAuditoria(4, true);

        return template('auditoria_edicion/index', $data);
    }

    /**
     * Muestra la la Auditoría completa con sus preguntas para modificarlo
     */
    public function getAudEdition($id)
    {
        $data['auditoria'] = $this->model_auditorias->getBloqueAud($id);
        return template('auditoria_edicion/view', $data);
    }

    /**
     * Crea una nueva Auditoría la cual sería como una edición con una nueva revisión
     */
    public function submitEdicionPlanilla()
    {

        $model = new Model_general();

        $data_title = [
            'nombre' => $this->request->getPost('titulo_auditoria'),
            'tipo_aud' => $this->request->getPost('tipo_aud'),
            'revision' => intval($this->request->getPost('revision')) + 1,
            'usuario_carga' => session()->get('id_usuario'),
        ];

        try {

            // Inicia la transacción
            $model->db->transBegin();

            // Validamos el título de la auditoría
            $result = $this->verificacion($data_title, 'validation_title_aud');
            if (!$result['exito']) {
                $model->db->transRollback();
                return json_encode($result);
            }

            # Agregar nuevo título de la Auditoria
            $id_auditoria = $this->model_general->insertG('auditorias_titulos', $data_title);
            $aux = 1;

            # Agregar subtítulos a la Auditoría creada y también las preguntas
            $bloque = $this->request->getPost('bloque');

            foreach ($bloque as $b) {

                $data_subt = [
                    'nombre' => $b['subtitulo'],
                    'id_titulo' => $id_auditoria,
                ];

                // Validamos los subtítulos de cada bloque
                $validate_subt = $this->verificacion($data_subt, 'validation_subtitulo');
                if (!$validate_subt['exito']) {
                    $model->db->transRollback();
                    return json_encode($validate_subt);
                }

                $id_subtitle = $this->model_general->insertG('auditorias_subtitulos', $data_subt);

                foreach ($b['preguntas_editadas'] as $p) {

                    $data_pregunta = [
                        'pregunta' => $p,
                        'subtitulo' => $id_subtitle,
                        'orden' => $aux,
                        'titulo' => $id_auditoria
                    ];

                    // Validamos cada pregunta
                    $validate_question = $this->verificacion($data_pregunta, 'validation_pregunta');
                    if (!$validate_question['exito']) {
                        $model->db->transRollback();
                        return json_encode($validate_question);
                    }

                    $this->model_general->insertG('auditorias_preguntas', $data_pregunta);
                    $aux++;
                }
            }

            // La auditoría anterior la actualizamos para que quede obsoleta
            $id_auditoria_old = $this->request->getPost('id_auditoria');

            $data_auditoria_old = [
                'obsoleto' => 1,
                'usuario_edicion' => session()->get('id_usuario'),
                'fecha_hora_edicion' => date('Y-m-d H:i:s')
            ];

            $this->model_general->updateG('auditorias_titulos', $id_auditoria_old, $data_auditoria_old);

            # Registrar los movimientos
            newMov(9, 3, $id_auditoria_old, 'Inspección: Auditoría Obsoleta'); //Movimiento (Registra el ID de la auditoría obsoleta)
            newMov(9, 1, $id_auditoria, 'Inspección: Nueva Revisión'); //Movimiento (Registra el ID de la auditoría con nueva revisión)

            // Finaliza la transacción
            $model->db->transCommit();

            echo json_encode($result);
        } catch (\Exception $error) {
            $model->db->transRollback();
            return 'Ha ocurrido un error en el registro de la auditoría: ' . $error->getMessage();
        }
    }
}
