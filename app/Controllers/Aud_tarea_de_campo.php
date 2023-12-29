<?php

namespace App\Controllers;

use Dompdf\Dompdf;
use Dompdf\Options;
use DateTime;

class Aud_tarea_de_campo extends Auditorias
{
    public function __construct()
    {
        parent::__construct();
        $this->model_aud_control = model('model_aud_control');
    }

    /**
     * Carga la planilla para una Auditoría de Control
     */
    public function submitPlanilla()
    {
        $helper = new Helper();
        $bloque_respuestas_t = $this->request->getPost('bloque_respuestas_t');
        $comentarios_preguntas_t = $this->request->getPost('comentarios_preguntas_t');
        $oportunidad_mejora_t = $this->request->getPost('oportunidad_mejora_t');

        $datos = [
            'modelo_tipo' => $this->request->getPost('tipo_auditoria_t'),
            'contratista' => $this->request->getPost('contratista_t'),
            'supervisor_responsable' => $this->request->getPost('supervisor_responsable_t'),
            'fecha_carga' => $this->request->getPost('fecha_hoy_tarea'),
            'cant_personal' => $this->request->getPost('cant_personal_t'),
            'num_informe' => $this->request->getPost('num_informe_t'),
            'proyecto' => $this->request->getPost('proyecto_t'),
            'modulo' => $this->request->getPost('modulo_tarea_campo'),
            'estacion' => $this->request->getPost('estacion_bombeo_t'),
            'tipo_auditoria' => $this->request->getPost('tipo_auditoria_t'),
            'sistema' => $this->request->getPost('sistema_t'),
            'usuario_carga' => session()->get('id_usuario'),
        ];

       
        $result = parent::verificacion($datos, 'validation_aud_tarea_de_campo');

        if ($result['exito']) {

            # Remuevo el tipo de auditoría ya que es solamente para validar
            unset($datos['tipo_auditoria']);

            $require_plan = [
                'oportunidad_mejora' => $oportunidad_mejora_t,
            ];

            $result_requiere = parent::verificacion($require_plan, 'validation_requiere_plan');

            if ($result_requiere['exito']):

                # Significa que hay plan de acción
                if ($oportunidad_mejora_t == 1) {

                    $datos_plan_accion_t = [
                        'tipo_auditoria' => 3,
                        'hallazgo' => $this->request->getPost('hallazgo_t'),
                        'plan_accion' => $this->request->getPost('plan_accion_t'),
                        'contratista' => $this->request->getPost('contratista_t'),
                        'responsable' => $this->request->getPost('responsable_plan_t'),
                        'relevo_responsable' => $this->request->getPost('relevo_responsable_plan_t'),
                        'significancia' => $this->request->getPost('significancia_t'),
                        'fecha_cierre' => $this->request->getPost('fecha_cierre_t'),
                        'usuario_carga' => session()->get('id_usuario'),
                    ];

                    $result_plan = $this->verificacion($datos_plan_accion_t, 'validation_aud_plan');

                    if ($result_plan['exito']) {
                        $id_aud = $this->model_general->insertG('auditoria_tarea_de_campo', $datos);
                        parent::submitRtaPreguntasAud($id_aud, 3, 3, $bloque_respuestas_t, $comentarios_preguntas_t);
                        $datos_plan_accion_t['id_auditoria'] = $id_aud;
                        $efectos_t = $this->request->getPost('efecto_impacto_t');

                        $id_hallazgo = parent::submitUploadPlanAccion($datos_plan_accion_t, $efectos_t);

                        // # Se envía los E-Mails
                        $datos_emails = $this->model_aud_control->getDataHallazgoEmail_Tarea_campo($id_aud, $id_hallazgo, 3);
                        $url = base_url('/auditorias/view_aud_tarea_campo/') . '/' . $datos_emails['id'];

                        $datos_emails['url'] = $url;
                        $emails = [];

                        # _ Usuario quien carga
                        $emails[] = $datos_emails['correo_usuario_carga'];
                        $helper->sendMail($datos_emails, 'Nueva Auditoría Tarea De Campo #', $url, 'emails/auditorias/tarea_de_campo/nueva', $emails);

                        # _ Responsable
                        $emails = [];
                        $emails[] = $datos_emails['correo_responsable'];
                        $helper->sendMail($datos_emails, 'Nueva Auditoría Tarea de Campo #', $url, 'emails/auditorias/tarea_de_campo/responsable', $emails);

                        # _ Relevo Responsable (¡Si existe!)
                        if (isset($datos_emails['correo_relevo'])) {
                            $emails = [];
                            $emails[] = $datos_emails['correo_relevo'];
                            $helper->sendMail($datos_emails, 'Nueva Auditoría Tarea de Campo #', $url, 'emails/auditorias/tarea_de_campo/relevo', $emails);
                        }
                        newMov(9, 1, $id_aud, 'Inspección de Tarea de Campo'); //Movimiento (Registra el ID de la Inspección de Tarea de Campo creada)

                    } else {
                        echo json_encode($result_plan['errores']);
                    }
                } else {
                    
                    $id_aud = $this->model_general->insertG('auditoria_tarea_de_campo', $datos);
                    parent::submitRtaPreguntasAud($id_aud, 3, 3, $bloque_respuestas_t, $comentarios_preguntas_t);
                    newMov(9, 1, $id_aud, 'Inspección de Tarea de Campo'); //Movimiento (Registra el ID de la Inspección de Tarea de Campo creada)
                }
            else:
                echo json_encode($result_requiere['errores']);
            endif;

        } else {
            echo json_encode($result['errores']);
        }
    }

    /**
     * Vista para la Auditoría tarea de campo
     */
    public function view_aud_tarea_campo($id_aud)
    {
        if (!$this->session->get('isLogin')) {
            return redirect()->to('login');
        } else {
            $data['auditoria'] = $this->getBloqueForViewTareaCampo($id_aud);

            $data['hallazgo'] = $this->model_auditorias->getHallazgoAud($id_aud, 3);

            if (!empty($data['hallazgo'])) {

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
            }


            return template('auditoria/view_tarea_campo', $data);
        }
    }

    public function view_aud_tarea_de_campo_pdf($id_auditoria)
    {

        $dompdf = new Dompdf();
        $data['auditoria'] = $this->getBloqueForViewTareaCampo($id_auditoria);
        $data['hallazgo'] = $this->model_auditorias->getHallazgoAud($id_auditoria, 3);
        
        // Obtener la ruta del directorio que contiene el favicon
        $basePath = realpath(base_url('assets/img/'));

        // Establecer la ruta del directorio como base path para los recursos
        $dompdf->setBasePath($basePath);

        $html = view('pdf/auditoria_tarea_de_campo', $data);
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
     * Genera todo el bloque completo de la auditoría con las preguntas y rtas
     */
    protected function getBloqueForViewTareaCampo($id_aud)
    {
        $auditoria = $this->model_auditorias->getBloqueCompletoAudTareaCampo($id_aud);

        if ($auditoria) {

            $id_titulo_aud = $auditoria['modelo_tipo'];
            $auditoria['bloque'] = $this->model_auditorias->getSubtitulosAuditorias($id_titulo_aud);


            foreach ($auditoria['bloque'] as $key => $subtitulo) {
                /* == Carga las preguntas == */
                $auditoria['bloque'][$key]['preguntas_rtas'] = $this->model_auditorias->getIdPreguntasAuditorias($subtitulo['id'], $id_titulo_aud);

                $preguntas = $auditoria['bloque'][$key]['preguntas_rtas'];

                /* == Busca por id pregunta su correspondiente respuesta == */
                foreach ($preguntas as $llave => $p) {
                    $auditoria['bloque'][$key]['preguntas_rtas'][$llave]['respuesta'] = $this->model_auditorias->getRtasFromPreguntaAudTarea_campo($id_aud, $p['id_pregunta']);
                }
            }
        } else {
            $auditoria = [];
        }
        return $auditoria;
    }

    /**
     * Dynamic Table para las Auditorías de tarea_de_campo
     */
    public function getPaged_tarea_de_campo($offset = NULL, $tamanioPagina = NULL)
    {

        $params = [
            'modelo_tipo_tarea_de_campo' => $this->request->getPost("modelo_tipo_tarea_de_campo"),
            'contratista_tarea_de_campo' => $this->request->getPost("contratista_tarea_de_campo"),
            'supervisor_tarea_de_campo' => $this->request->getPost("supervisor_tarea_de_campo"),
            'proyecto_aud_tarea_de_campo' => $this->request->getPost("proyecto_aud_tarea_de_campo"),
            'usuario_carga_tarea_de_campo' => $this->request->getPost("usuario_carga_tarea_de_campo"),
            'fecha_desde_tarea_de_campo' => $this->request->getPost("fecha_desde_tarea_de_campo"),
            'fecha_hasta_tarea_de_campo' => $this->request->getPost("fecha_hasta_tarea_de_campo")
        ];

        if ((is_numeric($offset) && $offset >= 0) && (is_numeric($tamanioPagina) && $tamanioPagina > 0)) {
            $response = $this->model_auditorias->getAllPagedTarea_de_campo($offset, $tamanioPagina, $params);
        } else {
            if (is_null($offset) && is_null($tamanioPagina)) {
                $response = $this->model_auditorias->getAllPagedTarea_de_campo($offset, $tamanioPagina, $params, true);
                $response = (int) $response[0]['cantidad'];
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
}