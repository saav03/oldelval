<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use CodeIgniter\Controller;
use Dompdf\Dompdf;
use Dompdf\Options;
use DateTime;
use App\Models;
use App\Models\Model_general;
use App\Models\Model_auditorias;
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

    /**
     * Vista del histórico de Inspecciones
     * Acá se van a mostrar los 4 Tipos de Inspecciones que existen
     * Se utiliza dynamic table para mostrar dichas inspecciones
     */
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
     * (No está en uso) => No está en funcionamiento de desactivar una Inspección
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
     * Crea un descargo perteneciente al hallazgo
     * El mismo va a tener adjuntos en caso de que se desee
     * También envía correos con el método _sendEmailNewDescargo($param)
     */
    public function createDescargo()
    {
        $helper = new Helper();
        $id_hallazgo = $this->request->getPost('id_hallazgo');
        $datos_descargo  = [
            'id_hallazgo'     => $id_hallazgo,
            'motivo'    => $this->request->getPost('new_descargo'),
            'id_usuario' => session()->get('id_usuario'),
            'fecha_hora_motivo' => date('Y-m-d H:i:s'),
        ];
        $results_descargo = $this->model_auditorias->addDescargo($datos_descargo);

        # Se cargan adjuntos si es que realmente existen
        if ($this->request->getPost('adj_descargo-description[]')) {
            $bd_info = array(
                'table' => 'auditoria_descargos_adj',
                'file' => 'adjunto',
                'description' => 'desc_adjunto',
                'optional_ids' => ['id_descargo' => $results_descargo['last_id']['id']]
            );
            $helper->cargarArchivos('adj_descargo', 'uploads/auditorias/descargos/', 'obs_descargo', $bd_info, $this->request->getPost('adj_descargo-description'));
        }

        

        # Envío de Correos 
        $this->_sendEmailNewDescargo($results_descargo['last_id']['id']);

        newMov(10, 1, $results_descargo['last_id']['id'], 'Descargo Hallazgo Inspección'); //Movimiento (Registra el ID del Descargo Creado)

        return $results_descargo;
    }
    /**
     * [Envío de Correos]
     * Envía los correos a los siguientes usuarios =>
     * - El usuario quien carga el hallazgo
     */
    protected function _sendEmailNewDescargo($id_descargo)
    {
        $helper = new Helper();
        $data = $this->model_mail_auditoria->getDataNewDescargo($id_descargo);
        $data['url'] = base_url('/auditorias/view/') . '/' . $data['id_inspeccion'];

        $emails = [];
        $emails[] = $data['correo_usuario_carga'];

        switch ($data['auditoria']) {
            case '1':
                $type_aud = 'Inspección de Control';
                break;
            case '2':
                $type_aud = 'Inspección Vehicular';
                break;
            case '3':
                $type_aud = 'Inspección de Obra';
                break;
            case '4':
                $type_aud = 'Inspección de Auditoría';
                break;
        }

        // Correo en copia
        $emails[] = 'mdinamarca@blister.com.ar';

        # Notificación (Para el responsable)
        $notificacion = [
            'titulo' => 'Se ha generado un nuevo descargo para la Inspección #' . $data['id_inspeccion'],
            'tipo' => 1,
            'referencia' => 2, // Inspección
            'descripcion' => '',
            'url' => 'auditorias/view/',
            'id_opcionales' => $data['id_inspeccion'],
            'usuario_notificado' => $data['usuario_carga_id']
        ];
        setNotificacion($notificacion);
        #-#-#-#-#-#-#

        $helper->sendMail($data, 'Nuevo Descargo #' . $id_descargo . ' - ' . $type_aud . ' #' . $data['id_inspeccion'], 'emails/auditorias/new_descargo', $emails);
    }

    /**
     * Dar una respuesta a un descargo
     */
    public function createRtaDescargo()
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

        $result = $this->model_auditorias->editDescargo($datos_rta_descargo, $id_descargo);

        # Envío de Correos
        $this->_sendEmailRtaDescargo($id_descargo, $estado);

        if ($estado == 1) {
            newMov(11, 3, $result['last_id']['id'], 'Acepta Descargo | Inspección'); //Movimiento (Registra el ID del Descargo Creado)
        } else {
            newMov(14, 3, $result['last_id']['id'], 'Rechaza Descargo | Inspección'); //Movimiento (Registra el ID del Descargo Creado)
        }
    }
    /**
     * [Envío de Correos]
     * Envía el correo a los siguientes usuario:
     * => El usuario quien cargó el descargo (ya que se lo respondieron si aceptaron/rechazaron el mismo)
     */
    protected function _sendEmailRtaDescargo($id_descargo, $estado)
    {
        $helper = new Helper();
        $data = $this->model_mail_auditoria->getDataRtaDescargo($id_descargo);
        $data['url'] = base_url('/auditorias/view/') . '/' . $data['id_inspeccion'];

        $emails = [];
        $emails[] = $data['correo_usuario_carga_descargo'];
        switch ($data['auditoria']) {
            case '1':
                $type_aud = 'Inspección de Control';
                break;
            case '2':
                $type_aud = 'Inspección Vehicular';
                break;
            case '3':
                $type_aud = 'Inspección de Obra';
                break;
            case '4':
                $type_aud = 'Inspección de Auditoría';
                break;
        }

        # Correo en copia
        $emails[] = 'mdinamarca@blister.com.ar';

        if ($estado == 1) {
            # Notificación
            $notificacion = [
                'titulo' => '¡Han aceptado su descargo para la Inspección #' . $data['id_inspeccion'] . '!',
                'tipo' => 2,
                'referencia' => 2, // Inspección
                'descripcion' => 'Le informamos que se ha aceptado el descargo correctamente',
                'url' => 'auditorias/view/',
                'id_opcionales' => $data['id_inspeccion'],
                'usuario_notificado' => $data['user_carga_descargo_id']
            ];
            setNotificacion($notificacion);
            #-#-#-#-#-#-#
        } else {
            # Notificación
            $notificacion = [
                'titulo' => '¡Han rechazado su descargo para la Inspección #' . $data['id_inspeccion'] . '!',
                'tipo' => 3,
                'referencia' => 2, // Inspección
                'descripcion' => 'Le informamos lamentablemente que se ha rechazado el descargo',
                'url' => 'auditorias/view/',
                'id_opcionales' => $data['id_inspeccion'],
                'usuario_notificado' => $data['user_carga_descargo_id']
            ];
            setNotificacion($notificacion);
            #-#-#-#-#-#-#
        }

        $helper->sendMail($data, 'Nueva Respuesta | Descargo #' . $id_descargo . ' - ' . $type_aud . ' #' . $data['id_inspeccion'], 'emails/auditorias/rta_descargo', $emails);
    }

    /**
     * ! (SIN USO POR EL MOMENTO)
     */
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

            # Tipos de Inspecciones/Auditorías
            $data['auditorias_control'] = $this->model_auditorias->getAllTitlesAuditoria();
            $data['auditorias_checklist'] = $this->model_auditorias->getAllTitlesAuditoria(0);
            $data['auditorias_tarea_de_campo'] = $this->model_auditorias->getAllTitlesAuditoria(3);
            $data['auditorias_auditoria'] = $this->model_auditorias->getAllTitlesAuditoria(4);

            # Datos generales
            $data['usuarios'] = $this->model_general->getAllActivo('usuario');
            $data['contratistas'] = $this->model_general->getAllEstadoActivo('empresas');
            $data['efectos_impactos'] = $this->model_general->getAllEstadoActivo('efectos_impactos');
            $data['proyectos'] = $this->model_general->getAllEstadoActivo('proyectos');
            $data['modulos'] = $this->model_general->getAllEstadoActivo('modulos');
            $data['estaciones'] = $this->model_general->getAllEstadoActivo('estaciones_bombeo');
            $data['sistemas'] = $this->model_general->getAllEstadoActivo('sistemas_oleoductos');
            $data['indicadores'] =  $this->model_general->getAllEstadoActivo('tarjeta_indicadores');
            $data['clasificaciones'] =  $this->model_general->getAllEstadoActivo(' tarjeta_clasificaciones');
            $data['efectos'] =  $this->model_general->getAllEstadoActivo('efectos_impactos');
            $data['significancia'] =  $this->model_general->getAllEstadoActivo('significancia');
            $data['tipo_hallazgo'] =  $this->model_general->getAllEstadoActivo(' tarjeta_tipo_hallazgo');
            $data['contratistas'] =  $this->model_general->getAllEstadoActivo(' empresas');
            $data['responsables'] =  $this->model_general->getAllActivo(' usuario');

            return template('auditoria/add', $data);
        }
    }

    /**
     * Trae el bloque de preguntas completo de esa auditoría
     * La retorna en forma de JSON
     * TODO | Verificar si esto sigue en funcionamiento
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
     * ! (OBSOLETO | No está en funcionamiento)
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
     * Carga todas las respuestas de las preguntas pertenecientes a la Inspección
     */
    protected function submitRtaPreguntasAud($id_aud, $auditoria, $modelo_tipo, $bloque_respuestas, $comentarios_preguntas, $tipo_obs = [])
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
                $this->model_general->insertG('auditoria_respuestas', $respuestas);
            }
        }

        /* == Tipo de OBS Checklist Vehicular == */
        /*
        Esto solamente pertenece a la Inspección de CheckList Vehicular, cambiando el tipo de observación
        */
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
        /*
        Una vez cargado las preguntas, va una por una a ver si alguna posee comentario
        */
        foreach ($comentarios_preguntas as $pregunta => $comentario) {
            $comentarios = [
                'id_auditoria' => $id_aud,
                'id_pregunta' => $pregunta,
                'comentario' => $comentario,
            ];
            $this->model_auditorias->updateComentarioRta($comentarios, 'auditoria_respuestas');
        }
    }

    //-----------------------------------------------------------------------------------------------------------
    /**
     * Método genérico lo cual permite validar los datos de un formulario que se esté cargando en BD
     */
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
        $data['auditorias_checklist'] = $this->model_auditorias->getAllTitlesAuditoria(2, true);
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
     * (Se crea con transacciones)
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

    ############################# NUEVO APARTADO DE AUDITORIAS (ACTUALIZADO MENOS CÓDIGO) #############################

    /**
     * Carga una nueva inspección, todas las inspecciones van a una misma tabla ahora
     * (Se realiza con transacciones)
     * Tiene envío de correos
     */
    public function submitInspeccion()
    {
        $model = new Model_auditorias();
        $helper = new Helper();
        $bloque_respuestas = $this->request->getPost('bloque_respuestas');
        $comentarios_preguntas = $this->request->getPost('comentarios_preguntas');

        # Datos generales de la Auditoría
        $auditoria = $this->request->getPost('auditoria');
        $modelo_tipo = $this->request->getPost('tipo_auditoria');
        $datos = [
            'auditoria' => $auditoria,
            'modelo_tipo' => $modelo_tipo,
            'contratista' => $this->request->getPost('contratista'),
            'equipo' => $this->request->getPost('equipo') ?? null,
            'conductor' => $this->request->getPost('conductor') ?? null,
            'num_interno' => $this->request->getPost('num_interno') ?? null,
            'supervisor_responsable' => $this->request->getPost('supervisor_responsable'),
            'cant_personal' => $this->request->getPost('cant_personal') ?? null,
            'proyecto' => $this->request->getPost('proyecto'),
            'marca' => $this->request->getPost('marca') ?? null,
            'modelo' => $this->request->getPost('modelo') ?? null,
            'patente' => $this->request->getPost('patente') ?? null,
            'hora' => $this->request->getPost('hora') ?? null,
            'modulo' => $this->request->getPost('modulo') ?? null,
            'estacion' => $this->request->getPost('estacion_bombeo') ?? null,
            'sistema' => $this->request->getPost('sistema') ?? null,
            'tarea_que_realiza' => $this->request->getPost('tarea_realiza') ?? null,
            'resultado_inspeccion' => $this->request->getPost('resultado_inspeccion') ?? null,
            'medidas_implementar' => $this->request->getPost('medidas_implementar') ?? null,
            'usuario_carga' => session()->get('id_usuario'),
        ];

        # Inicia la transacción
        $model->db->transStart();

        try {
            $result = $this->verificacion($datos, 'validation_auditoria');

            if (!$result['exito']) {
                $model->db->transRollback();
                return json_encode($result['errores']);
            }

            $insert_auditoria = $this->model_auditorias->createInspection($datos);

            if (!$insert_auditoria) {
                $errores = [];
                $errores['errores'] = 'Ha ocurrido un error al enviar la Auditoría';
                return json_encode($errores);
            }

            $this->submitRtaPreguntasAud($insert_auditoria['last_id']['id'], $auditoria, $modelo_tipo, $bloque_respuestas, $comentarios_preguntas);

            # Observaciones con Mejora
            if ($this->request->getPost('hallazgos_mejoras')) {

                $hallazgos_mejoras = $this->request->getPost('hallazgos_mejoras');
                if (!is_null($hallazgos_mejoras) && count($hallazgos_mejoras) > 0) {

                    foreach ($hallazgos_mejoras as $key => $h) {
                        $data = [
                            'id_auditoria' => $insert_auditoria['last_id']['id'],
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

                        $hallazgo_creado = $this->model_auditorias->createHallazgoAuditoria($data);
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
                                        'auditoria_id' => $insert_auditoria['last_id']['id'],
                                    ];
                                    $this->model_general->insertG('auditoria_rel_efecto', $data);
                                }
                            }
                        }
                        // * ---

                        // * Inserta los adjuntos de cada hallazgo creado
                        if ($this->request->getPost('adjuntos_hallazgos_' . $key . '-description')) {
                            $bd_info = array(
                                'table' => 'auditoria_hallazgos_adjuntos',
                                'file' => 'adjunto',
                                'description' => 'desc_adjunto',
                                'optional_ids' => ['id_hallazgo' => $id_hallazgo['id']]
                            );
                            $helper->cargarArchivos('adjuntos_hallazgos_' . $key, 'uploads/auditorias/hallazgos/', 'obs_auditoria', $bd_info, $this->request->getPost('adjuntos_hallazgos_' . $key . '-description'));
                        }
                        // * ---

                        # Notificación (Para el responsable)
                        $notificacion = [
                            'titulo' => 'Ha sido asignado como responsable en la Inspección #' . $insert_auditoria['last_id']['id'],
                            'tipo' => 1,
                            'referencia' => 2, // Inspección
                            'descripcion' => 'Se le notifica que ha sido asignado como responsable de dar tratamiento a una Observación de Mejora en la Inspección #' . $insert_auditoria['last_id']['id'],
                            'url' => 'auditorias/view/',
                            'id_opcionales' => $insert_auditoria['last_id']['id'],
                            'usuario_notificado' => $h['responsable']
                        ];
                        setNotificacion($notificacion);
                        #-#-#-#-#-#-#

                        # Notificación (Para el relevo del responsable)
                        $notificacion = [
                            'titulo' => 'Ha sido asignado como segundo responsable en la Inspección #' . $insert_auditoria['last_id']['id'],
                            'tipo' => 1,
                            'referencia' => 2, // Inspección
                            'descripcion' => 'Se le notifica que ha sido asignado como segundo responsable de dar tratamiento a una Observación de Mejora en la Inspección #' . $insert_auditoria['last_id']['id'],
                            'url' => 'auditorias/view/',
                            'id_opcionales' => $insert_auditoria['last_id']['id'],
                            'usuario_notificado' => $h['relevo_responsable']
                        ];
                        setNotificacion($notificacion);
                        #-#-#-#-#-#-#

                        # Envío de E-Mails - Responsable
                        if ($h['responsable'] != '')
                            $this->_sendEmailObservacion($auditoria, $insert_auditoria['last_id']['id'], $id_hallazgo['id']);

                        # Envío de E-Mails - Segundo Responsable
                        if ($h['relevo_responsable'] != '') {
                            $this->_sendEmailObservacion($auditoria, $insert_auditoria['last_id']['id'], $id_hallazgo['id'], true);
                        }
                    }
                }
            }

            # Observación Positiva
            if ($this->request->getPost('hallazgos_positivos')) {
                $hallazgos_positivos = $this->request->getPost('hallazgos_positivos');
                if (!is_null($hallazgos_positivos) && count($hallazgos_positivos) > 0) {

                    foreach ($hallazgos_positivos as $key => $h) {
                        $data = [
                            'id_auditoria' => $insert_auditoria['last_id']['id'],
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

                        $hallazgo_creado = $this->model_auditorias->createHallazgoAuditoria($data);
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
                                        'auditoria_id' => $insert_auditoria['last_id']['id'],
                                    ];
                                    $this->model_general->insertG('auditoria_rel_efecto', $data);
                                }
                            }
                        }
                        // * ---

                        // * Inserta los adjuntos de cada hallazgo creado
                        if ($this->request->getPost('adjuntos_hallazgos_positivos_' . $key . '-description')) {
                            $bd_info = array(
                                'table' => 'auditoria_hallazgos_adjuntos',
                                'file' => 'adjunto',
                                'description' => 'desc_adjunto',
                                'optional_ids' => ['id_hallazgo' => $id_hallazgo['id']]
                            );
                            $helper->cargarArchivos('adjuntos_hallazgos_positivos_' . $key, 'uploads/auditorias/hallazgos/', 'obs_auditoria', $bd_info, $this->request->getPost('adjuntos_hallazgos_positivos_' . $key . '-description'));
                        }
                        // * ---

                        # Notificación Positiva (Para el responsable)
                        $notificacion = [
                            'titulo' => 'Ha recibido un reconocimiento en la Inspección #' . $insert_auditoria['last_id']['id'],
                            'tipo' => 2,
                            'referencia' => 2, // Inspección
                            'descripcion' => 'Se le notifica que ha sido reconocido positivamente en la observación de la Inspección #' . $insert_auditoria['last_id']['id'],
                            'url' => 'auditorias/view/',
                            'id_opcionales' => $insert_auditoria['last_id']['id'],
                            'usuario_notificado' => $h['responsable']
                        ];
                        setNotificacion($notificacion);
                        #-#-#-#-#-#-#

                        # Envío de E-Mails - Observación Positiva
                        if ($h['responsable'] != '')
                            $this->_sendEmailObservacion($auditoria, $insert_auditoria['last_id']['id'], $id_hallazgo['id'], false, true);
                    }
                }
            }

            # Envío de correos
            if ($insert_auditoria['last_id']['id'])
                $this->_sendEmailNewInspection($insert_auditoria['last_id']['id'], $auditoria);

            switch ($auditoria) {
                case '1':
                    $type_aud = 'Inspección de Control';
                    break;
                case '2':
                    $type_aud = 'Inspección Vehicular';
                    break;
                case '3':
                    $type_aud = 'Inspección de Obra';
                    break;
                case '4':
                    $type_aud = 'Inspección de Auditoría';
                    break;
            }

            newMov(9, 1, $insert_auditoria['last_id']['id'], $type_aud); //Movimiento (Registra el ID de la Inspección creada)

            // Finaliza la transacción
            $model->db->transComplete();
        } catch (\Throwable $error) {
            $model->db->transRollback();
            return 'Ha ocurrido un error en el registro de la Inspección: ' . $error->getMessage();
        }
    }

    /**
     * [Envío de Correos]
     * Envía los correos a los siguientes usuarios =>
     * - El usuario quien carga
     */
    protected function _sendEmailNewInspection($id_inspection, $auditoria)
    {
        $helper = new Helper();
        $datos = $this->model_mail_auditoria->getDataNewInspection($id_inspection, $auditoria);
        $datos['url'] = base_url('/auditorias/view/') . '/' . $id_inspection;

        $emails = [];
        $emails[] = $datos['correo_usuario_carga'];
        // $emails[] = $datos['correo_supervisor']; // No existe más el ID del supervisor responsable, ahora es un inp text

        // Correo en copia
        $emails[] = 'mdinamarca@blister.com.ar';
        switch ($auditoria) {
            case '1':
                $title = 'Nueva Inspección de Control';
                break;
            case '2':
                $title = 'Nueva Inspección Vehicular';
                break;
            case '3':
                $title = 'Nueva Inspección de Obra';
                break;
            case '4':
                $title = 'Nueva Inspección de Auditoría';
                break;
        }
        $helper->sendMail($datos, $title . ' #' . $id_inspection, 'emails/auditorias/new', $emails);
    }

    /**
     * [Envío de Correos]
     * Dependienedo si $positiva es 'false', significa que va a enviar un correo como 'Observación con Mejora', caso contrario, envía un correo como 'Observación Positiva'
     * Envía los correos a los siguientes usuarios =>
     * - El usuario quien carga
     * - El usuario responsable del hallazgo
     * - El usuario segundo responsable (Si es que $segundo_responsable es 'true')
     */
    protected function _sendEmailObservacion($auditoria, $id_inspection, $id_hallazgo, $segundo_responsable = false, $positiva = false)
    {
        $helper = new Helper();

        $datos = $this->model_mail_auditoria->getDataNewObservacion($auditoria, $id_hallazgo, $segundo_responsable);
        $datos['url'] = base_url('/auditorias/view/') . '/' . $id_inspection;

        $emails = [];
        $emails[] = $datos['correo_usuario_carga'];
        $emails[] = $datos['correo_usuario_responsable'];

        $ruta = 'emails/auditorias/new_obs_mejora';
        // En caso de que el segundo responsable sea 'true' se cambia la ruta
        // Caso contrario, va el correo para el responsable
        if ($segundo_responsable) {
            $ruta = 'emails/auditorias/new_obs_mejora_seg_responsable';
        }

        if ($positiva) {
            $ruta = 'emails/auditorias/new_obs_positiva';
        }

        switch ($auditoria) {
            case '1':
                $type_aud = 'Inspección de Control';
                break;
            case '2':
                $type_aud = 'Inspección Vehicular';
                break;
            case '3':
                $type_aud = 'Inspección de Obra';
                break;
            case '4':
                $type_aud = 'Inspección de Auditoría';
                break;
        }

        // Correo en copia
        $emails[] = 'mdinamarca@blister.com.ar';
        $title = 'Nueva Observación #' . $id_hallazgo . ' - ' . $type_aud . ' #' . $id_inspection;
        $helper->sendMail($datos, $title, $ruta, $emails);
    }

    /**
     * Se obtiene el tipo de auditoría que se requiera que se pase por parámetros
     */
    public function getAudType($type_aud)
    {
        $auditorias = $this->model_auditorias->getAllTitlesAuditoria($type_aud);
        echo json_encode($auditorias);
    }

    /**
     * Dynamic Table para las Auditorías
     */
    public function getPaged($auditoria, $offset = NULL, $tamanioPagina = NULL)
    {
        $params = [
            'auditoria' => $auditoria,
            'modelo_tipo' => $this->request->getPost("modelo_tipo"),
            'contratista' => $this->request->getPost("contratista"),
            'equipo' => $this->request->getPost("equipo") ?? null,
            'conductor' => $this->request->getPost("conductor") ?? null,
            'num_interno' => $this->request->getPost("num_interno") ?? null,
            'resultado' => $this->request->getPost("resultado") ?? null,
            'supervisor' => $this->request->getPost("supervisor"),
            'proyecto' => $this->request->getPost("proyecto"),
            'usuario_carga' => $this->request->getPost("usuario_carga"),
            'fecha_desde' => $this->request->getPost("fecha_desde"),
            'fecha_hasta' => $this->request->getPost("fecha_hasta")
        ];

        if ((is_numeric($offset) && $offset >= 0) && (is_numeric($tamanioPagina) && $tamanioPagina > 0)) {
            $response = $this->model_auditorias->getAllPaged($offset, $tamanioPagina, $params);
        } else {
            if (is_null($offset) && is_null($tamanioPagina)) {
                $response = $this->model_auditorias->getAllPaged($offset, $tamanioPagina, $params, true);
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
     * Carga la vista del la Inspección
     */
    public function view($id_inspection)
    {
        if (!$this->session->get('isLogin')) {
            return redirect()->to('login');
        }

        $data['auditoria'] =  $this->getCompleteInspection($id_inspection);

        // Esto agrega el nombre de la Inspección que se está agregando
        // Por el momento son 4
        switch ($data['auditoria']['auditoria']) {
            case '1':
                $data['auditoria']['inspeccion'] = 'Inspección de Control';
                break;
            case '2':
                $data['auditoria']['inspeccion'] = 'Inspección Vehicular';
                break;
            case '3':
                $data['auditoria']['inspeccion'] = 'Inspección de Obra';
                break;
            case '4':
                $data['auditoria']['inspeccion'] = 'Inspección de Auditoría';
                break;
        }

        return template('auditoria/view', $data);
    }
    /**
     * Genera todo el bloque completo de la auditoría/inspección con las preguntas y rtas
     * También, carga los hallazgos (Adjuntos, efectos y descargos)
     */
    protected function getCompleteInspection($id_inspection)
    {
        $auditoria = $this->model_auditorias->getBloqueInspectionComplete($id_inspection);

        if ($auditoria) {
            $id_titulo_aud = $auditoria['modelo_tipo'];
            $auditoria['bloque'] = $this->model_auditorias->getSubtitulosAuditorias($id_titulo_aud);

            foreach ($auditoria['bloque'] as $key => $subtitulo) {
                /* == Carga las preguntas == */
                $auditoria['bloque'][$key]['preguntas_rtas'] = $this->model_auditorias->getIdPreguntasAuditorias($subtitulo['id'], $id_titulo_aud);

                $preguntas = $auditoria['bloque'][$key]['preguntas_rtas'];

                /* == Busca por id pregunta su correspondiente respuesta == */
                foreach ($preguntas as $llave => $p) {
                    $auditoria['bloque'][$key]['preguntas_rtas'][$llave]['respuesta'] = $this->model_auditorias->getRespuestasAuditoria($id_inspection, $p['id_pregunta']);
                }
            }

            # Hallazgos de la Inspección
            $hallazgos = $this->model_auditorias->getHallazgosInspeccion($id_inspection);

            # Obtiene los adjuntos, efectos y descargos de los Hallazgos pertenecientes a la Inspección
            foreach ($hallazgos as $key => $h) {
                $adjuntos = $this->model_auditorias->getAdjHallazgoInspection($h['id_hallazgo']);
                $efectos = $this->model_auditorias->getEfectosRelHallazgo($h['id_hallazgo']);
                $hallazgos[$key]['adjuntos'] = $adjuntos;
                $hallazgos[$key]['efectos'] = $efectos;

                $descargos = $this->model_auditorias->getDescargoHallazgoInspection($h['id_hallazgo']);
                foreach ($descargos as $key_d => $d) {
                    $adjuntos_descargo = $this->model_auditorias->getAdjDescargosHallazgo($d['id']);
                    $descargos[$key_d]['descargos_adj'] = $adjuntos_descargo;
                }
                $hallazgos[$key]['descargos'] = $descargos;
            }
            $auditoria['hallazgos'] = $hallazgos;
        } else {
            $auditoria = [];
        }

        return $auditoria;
    }

    /**
     * Elimina una Inspección/Auditoría
     * (Sólo lo ve el administrador, pero también es un permiso que se le puede asignar a cualquier usuario)
     * Este método se encarga de eliminar tanto la Inspección como todo lo relacionado a la misma, es decir,
     * Hallazgos, Descargos, Adjuntos, Significancias, etc.
     */
    public function destroy()
    {
        $id_inspeccion = $this->request->getPost('id_inspeccion');

        # Adjuntos de los Hallazgos
        $id_hallazgos = $this->model_auditorias->getIdHallazgos($id_inspeccion);
        $adjuntos = [];
        foreach ($id_hallazgos as $id) {
            $adjuntos[] = $this->model_auditorias->getNameAdjuntos($id['id']);
        }
        $names = [];
        foreach ($adjuntos as $adj_name) {
            foreach ($adj_name as $n) {
                $names[] = $n['adjunto'];
            }
        }
        foreach ($names as $name) {
            if (is_file('uploads/auditorias/hallazgos/' . $name))
                unlink('uploads/auditorias/hallazgos/' . $name);
        }

        # Adjuntos de los descargos
        $id_descargos = [];
        $adjuntos = [];
        foreach ($id_hallazgos as $id) {
            $id_descargos[] = $this->model_auditorias->getIdDescargos($id['id']);
        }
        foreach ($id_descargos as $id) {
            $adjuntos[] = $this->model_auditorias->getNameAdjuntosDescargos($id);
        }
        $names = [];
        foreach ($adjuntos as $adj_name) {
            foreach ($adj_name as $n) {
                $names[] = $n['adjunto'];
            }
        }
        foreach ($names as $name) {
            if (is_file('uploads/auditorias/descargos/' . $name))
                unlink('uploads/auditorias/descargos/' . $name);
        }

        // Esto se ejecuta después de que eliminó los adjuntos del servidor
        # Eliminar Inspección
        $this->model_auditorias->delete($id_inspeccion);
    }

    /**
     * Elimina una Inspección (Planilla más que nada) con sus subtítulos y preguntas
     * (Recomendable no eliminar porque si solamente, ver bien antes de eliminar una Inspección)
     */
    public function destroy_inspection()
    {
        $id_inspeccion = $this->request->getPost('id_inspeccion');
        $titulo = $this->model_general->get('auditorias_titulos', $id_inspeccion);

        # Si no hay coincidencia con el ID a eliminar, entonces no hace nada
        if (is_null($titulo)) {
            $response = ['error' => 400];
            echo json_encode($response);
            return;
        }

        # Caso contrario, eliminamos los subtítulos perteneciente a ese título de la Inspección
        $this->model_general->deleteModified('auditorias_subtitulos', $titulo['id'], 'id_titulo');

        # También, eliminamos las preguntas pertenecientes a ese título de la Inspección
        $this->model_general->deleteModified('auditorias_preguntas', $titulo['id'], 'titulo');

        # Por último, eliminamos el título de la Inspección
        $this->model_general->deleteModified('auditorias_titulos', $titulo['id']);

        echo json_encode($titulo);
    }

    /**
     * Se creó con el fin de visualizar como queda un PDF
     * O también vistas de los correos
     * (Se puede eliminar tranquilamente)
     */
    public function testing()
    {
        $data = $this->model_mail_auditoria->getDataNewDescargo(2);
        echo '<pre>';
        var_dump($data);
        echo '</pre>';
        exit;
    }


    public function visualizePDF($id_inspeccion)
    {
        # Cargar la biblioteca Dompdf
        $dompdf = new Dompdf();
        $data['auditoria'] =  $this->getCompleteInspection($id_inspeccion);

        switch ($data['auditoria']['auditoria']) {
            case '1':
                $data['auditoria']['inspeccion'] = 'Inspección de Control';
                break;
            case '2':
                $data['auditoria']['inspeccion'] = 'Inspección Vehicular';
                break;
            case '3':
                $data['auditoria']['inspeccion'] = 'Inspección de Obra';
                break;
            case '4':
                $data['auditoria']['inspeccion'] = 'Inspección de Auditoría';
                break;
        }

        // echo '<pre>';
        // var_dump($data);
        // echo '</pre>';
        // exit;

        # Obtener la ruta del directorio que contiene el favicon
        $basePath = realpath(base_url('assets/img/'));

        # Establecer la ruta del directorio como base path para los recursos
        $dompdf->setBasePath($basePath);

        $html = view('pdf/auditorias/inspeccion', $data);
        $options = new Options();
        $options->set('isRemoteEnabled', true);
        $options->set('isPhpEnabled', true);
        $dompdf = new Dompdf($options);
        $dompdf->loadHtml($html);
        $dompdf->setPaper('A4', 'portrait');
        $dompdf->render();
        $dompdf->stream('Auditoria.pdf', array("Attachment" => false));
        exit;
    }

    /**
     * [AUXILIAR]
     * Esto se ejecuta colocanco la URL desde el navegador
     */
    public function getAudToInspection($id_tipo_inspeccion)
    {
        switch ($id_tipo_inspeccion) {
            case '1': // Inspección de Control

                # Hacer consulta
                $inspecciones = $this->model_general->getAll('auditoria_control');
                $rtas = [];
                foreach ($inspecciones as $inspeccion) {
                    $rtas = $this->model_auditorias->getRespuestasInspeccionAux($inspeccion['id'], 'aud_rtas_control');

                    $data_inspeccion = [
                        'auditoria' => 1,
                        'modelo_tipo' => $inspeccion['modelo_tipo'],
                        'contratista' => $inspeccion['contratista'],
                        'supervisor_responsable' => $inspeccion['supervisor_responsable'],
                        'cant_personal' => $inspeccion['cant_personal'],
                        'proyecto' => $inspeccion['proyecto'],
                        'proyecto' => $inspeccion['proyecto'],
                        'modulo' => $inspeccion['modulo'],
                        'estacion' => $inspeccion['estacion'],
                        'sistema' => $inspeccion['sistema'],
                        'fecha_hora_carga' => $inspeccion['fecha_carga'],
                        'usuario_carga' => $inspeccion['usuario_carga'],
                    ];

                    $result = $this->model_general->insertG('auditoria', $data_inspeccion);

                    if ($result) {
                        foreach ($rtas as $r) {
                            $rta = [
                                'id_auditoria' => $result,
                                'modelo_tipo' => $r['modelo_tipo'],
                                'id_subtitulo' => $r['id_subtitulo'],
                                'id_pregunta' => $r['id_pregunta'],
                                'pregunta' => $r['pregunta'],
                                'rta' => $r['rta'],
                                'comentario' => $r['comentario'],
                            ];
                            $this->model_general->insertG('auditoria_respuestas', $rta);
                        }
                    }
                }
                echo '<pre>';
                echo 'Todo bien!';
                echo '</pre>';
                exit;
                break;
            case '2':
                # Hacer consulta
                $inspecciones = $this->model_general->getAll('auditoria_vehicular');
                $rtas = [];
                foreach ($inspecciones as $inspeccion) {
                    $rtas = $this->model_auditorias->getRespuestasInspeccionAux($inspeccion['id'], 'aud_rtas_vehicular');
                    $conductor = $this->model_general->get('usuario', $inspeccion['conductor']);
                    $data_inspeccion = [
                        'auditoria' => 2,
                        'modelo_tipo' => $inspeccion['modelo_tipo'],
                        'contratista' => $inspeccion['contratista'],
                        'equipo' => $inspeccion['equipo'],
                        'conductor' => $conductor['nombre'] . ' ' . $conductor['apellido'],
                        'num_interno' => $inspeccion['num_interno'],
                        'marca' => $inspeccion['marca'],
                        'modelo' => $inspeccion['modelo'],
                        'patente' => $inspeccion['patente'],
                        'hora' => $inspeccion['hora'],
                        'tarea_que_realiza' => $inspeccion['tarea_que_realiza'],
                        'resultado_inspeccion' => $inspeccion['resultado_inspeccion'],
                        'medidas_implementar' => $inspeccion['medidas_implementar'],
                        'supervisor_responsable' => 6,
                        'proyecto' => $inspeccion['proyecto'],
                        'fecha_hora_carga' => $inspeccion['fecha_hora_carga'],
                        'usuario_carga' => $inspeccion['usuario_carga'],
                    ];

                    $result = $this->model_general->insertG('auditoria', $data_inspeccion);
                    if ($result) {
                        foreach ($rtas as $r) {
                            $rta = [
                                'id_auditoria' => $result,
                                'modelo_tipo' => $r['modelo_tipo'],
                                'id_subtitulo' => $r['id_subtitulo'],
                                'id_pregunta' => $r['id_pregunta'],
                                'pregunta' => $r['pregunta'],
                                'rta' => $r['rta'],
                                'comentario' => $r['comentario'],
                            ];
                            $this->model_general->insertG('auditoria_respuestas', $rta);
                        }
                    }
                }
                echo '<pre>';
                echo 'Todo bien!';
                echo '</pre>';
                exit;
                break;
            case '3':
                # Hacer consulta
                $inspecciones = $this->model_general->getAll('auditoria_tarea_de_campo');
                $rtas = [];
                foreach ($inspecciones as $inspeccion) {
                    $rtas = $this->model_auditorias->getRespuestasInspeccionAux($inspeccion['id'], 'aud_rtas_tarea_de_campo');

                    $data_inspeccion = [
                        'auditoria' => 3,
                        'modelo_tipo' => $inspeccion['modelo_tipo'],
                        'contratista' => $inspeccion['contratista'],
                        'supervisor_responsable' => $inspeccion['supervisor_responsable'],
                        'cant_personal' => $inspeccion['cant_personal'],
                        'proyecto' => $inspeccion['proyecto'],
                        'proyecto' => $inspeccion['proyecto'],
                        'modulo' => $inspeccion['modulo'],
                        'estacion' => $inspeccion['estacion'],
                        'sistema' => $inspeccion['sistema'],
                        'fecha_hora_carga' => $inspeccion['fecha_carga'],
                        'usuario_carga' => $inspeccion['usuario_carga'],
                    ];

                    $result = $this->model_general->insertG('auditoria', $data_inspeccion);

                    if ($result) {
                        foreach ($rtas as $r) {
                            $rta = [
                                'id_auditoria' => $result,
                                'modelo_tipo' => $r['modelo_tipo'],
                                'id_subtitulo' => $r['id_subtitulo'],
                                'id_pregunta' => $r['id_pregunta'],
                                'pregunta' => $r['pregunta'],
                                'rta' => $r['rta'],
                                'comentario' => $r['comentario'],
                            ];
                            $this->model_general->insertG('auditoria_respuestas', $rta);
                        }
                    }
                }
                echo '<pre>';
                echo 'Todo bien!';
                echo '</pre>';
                exit;
                break;
            case '4':
                # Hacer consulta
                $inspecciones = $this->model_general->getAll('auditoria_tarea_de_campo');
                $rtas = [];
                foreach ($inspecciones as $inspeccion) {
                    $rtas = $this->model_auditorias->getRespuestasInspeccionAux($inspeccion['id'], 'aud_rtas_tarea_de_campo');

                    $data_inspeccion = [
                        'auditoria' => 4,
                        'modelo_tipo' => $inspeccion['modelo_tipo'],
                        'contratista' => $inspeccion['contratista'],
                        'supervisor_responsable' => $inspeccion['supervisor_responsable'],
                        'cant_personal' => $inspeccion['cant_personal'],
                        'proyecto' => $inspeccion['proyecto'],
                        'proyecto' => $inspeccion['proyecto'],
                        'modulo' => $inspeccion['modulo'],
                        'estacion' => $inspeccion['estacion'],
                        'sistema' => $inspeccion['sistema'],
                        'fecha_hora_carga' => $inspeccion['fecha_carga'],
                        'usuario_carga' => $inspeccion['usuario_carga'],
                    ];

                    $result = $this->model_general->insertG('auditoria', $data_inspeccion);

                    if ($result) {
                        foreach ($rtas as $r) {
                            $rta = [
                                'id_auditoria' => $result,
                                'modelo_tipo' => $r['modelo_tipo'],
                                'id_subtitulo' => $r['id_subtitulo'],
                                'id_pregunta' => $r['id_pregunta'],
                                'pregunta' => $r['pregunta'],
                                'rta' => $r['rta'],
                                'comentario' => $r['comentario'],
                            ];
                            $this->model_general->insertG('auditoria_respuestas', $rta);
                        }
                    }
                }
                echo '<pre>';
                echo 'Todo bien!';
                echo '</pre>';
                exit;
                break;
        }
    }
}
