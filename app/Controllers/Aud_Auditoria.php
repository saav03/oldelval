<?php

namespace App\Controllers;

use Dompdf\Dompdf;
use Dompdf\Options;
use DateTime;

class Aud_auditoria extends Auditorias
{
    public function __construct()
    {
        parent::__construct();
        $this->model_aud_auditoria = model('Model_aud_auditoria');
    }

    /**
     * Carga la planilla para una Auditoría de Control
     */
    public function submitPlanilla()
    {
        $helper = new Helper();
        $bloque_respuestas_a = $this->request->getPost('bloque_respuestas_a');
        $comentarios_preguntas_a = $this->request->getPost('comentarios_preguntas_a');
        $oportunidad_mejora_a = $this->request->getPost('oportunidad_mejora_a');

        $datos = [
            'modelo_tipo' => $this->request->getPost('tipo_auditoria_a'),
            'contratista' => $this->request->getPost('contratista_a'),
            'supervisor_responsable' => $this->request->getPost('supervisor_responsable_a'),
            'fecha_carga' => $this->request->getPost('fecha_hoy_a'),
            'cant_personal' => $this->request->getPost('cant_personal_a'),
            'num_informe' => $this->request->getPost('num_informe_a'),
            'proyecto' => $this->request->getPost('proyecto_a'),
            'modulo' => $this->request->getPost('modulo_a'),
            'estacion' => $this->request->getPost('estacion_bombeo_a'),
            'tipo_auditoria' => $this->request->getPost('tipo_auditoria_a'),
            'sistema' => $this->request->getPost('sistema_a'),
            'usuario_carga' => session()->get('id_usuario'),
        ];

        $result = parent::verificacion($datos, 'validation_aud_auditoria');

        if ($result['exito']) {

            # Remuevo el tipo de auditoría ya que es solamente para validar
            unset($datos['tipo_auditoria']);

            $require_plan = [
                'oportunidad_mejora' => $oportunidad_mejora_a,
            ];

            $result_requiere = parent::verificacion($require_plan, 'validation_requiere_plan');

            if ($result_requiere['exito']):

                # Significa que hay plan de acción
                if ($oportunidad_mejora_a == 1) {

                    $datos_plan_accion_a = [
                        'tipo_auditoria' => 4,
                        'hallazgo' => $this->request->getPost('hallazgo_a'),
                        'plan_accion' => $this->request->getPost('plan_accion_a'),
                        'contratista' => $this->request->getPost('contratista_a'),
                        'responsable' => $this->request->getPost('responsable_plan_a'),
                        'relevo_responsable' => $this->request->getPost('relevo_responsable_plan_a'),
                        'significancia' => $this->request->getPost('significancia_a'),
                        'fecha_cierre' => $this->request->getPost('fecha_cierre_a'),
                        'usuario_carga' => session()->get('id_usuario'),
                    ];

                    $result_plan = $this->verificacion($datos_plan_accion_a, 'validation_aud_plan');

                    if ($result_plan['exito']) {
                        $id_aud = $this->model_general->insertG('auditoria_auditoria', $datos);
                        parent::submitRtaPreguntasAud($id_aud, 4, 4, $bloque_respuestas_a, $comentarios_preguntas_a);
                        $datos_plan_accion_a['id_auditoria'] = $id_aud;
                        $datos_plan_accion_a['id_auditoria'] = $id_aud;
                        $efectos = $this->request->getPost('efecto_impacto_a');

                        $id_hallazgo = parent::submitUploadPlanAccion($datos_plan_accion_a, $efectos);

                        # Se envía los E-Mails
                        $datos_emails = $this->model_aud_control->getDataHallazgoEmail_Auditoria($id_aud, $id_hallazgo, 4);
                        $url = base_url('/auditorias/view_aud_auditoria/') . '/' . $datos_emails['id'];
                        $datos_emails['url'] = $url;
                        $emails = [];

                        # _ Usuario quien carga
                        $emails[] = $datos_emails['correo_usuario_carga'];
                        // $helper->sendMail($datos_emails, 'Nueva Auditoría de Tipo Auditoria #', $url, 'emails/auditorias/auditoria/nueva', $emails);

                        # _ Responsable
                        $emails = [];
                        $emails[] = $datos_emails['correo_responsable'];
                        // $helper->sendMail($datos_emails, 'Nueva Auditoría de Tipo Auditoria #', $url, 'emails/auditorias/auditoria/responsable', $emails);

                        # _ Relevo Responsable (¡Si existe!)
                        if (isset($datos_emails['correo_relevo'])) {
                            $emails = [];
                            $emails[] = $datos_emails['correo_relevo'];
                            // $helper->sendMail($datos_emails, 'Nueva Auditoría de Tipo Auditoria #', $url, 'emails/auditorias/auditoria/relevo', $emails);
                        }
                    } else {
                        echo json_encode($result_plan['errores']);
                    }
                } else {
                    $id_aud = $this->model_general->insertG('auditoria_auditoria', $datos);
                    parent::submitRtaPreguntasAud($id_aud, 4, 4, $bloque_respuestas_a, $comentarios_preguntas_a);
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
    public function view_aud_auditoria($id_aud)
    {
        if (!$this->session->get('isLogin')) {
            return redirect()->to('login');
        } else {
            $data['auditoria'] = $this->getBloqueForViewAuditoria($id_aud);

            $data['hallazgo'] = $this->model_auditorias->getHallazgoAud($id_aud, 4);

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


            return template('auditoria/view_aud_auditoria', $data);
        }
    }

    public function view_aud_auditoria_pdf($id_auditoria)
    {
        // Cargar la biblioteca Dompdf
        $dompdf = new Dompdf();
        $data['auditoria'] = $this->getBloqueForViewAuditoria($id_auditoria);
        $data['hallazgo'] = $this->model_auditorias->getHallazgoAud($id_auditoria, 4);

        // return view('pdf/auditoria_control', $data);
        // exit;
        // Renderizar el contenido como PDF
        // $dompdf->loadHtml(view('pdf/auditoria_control', [], [], true));
        // $dompdf->render();

        // Enviar el PDF al navegador
        // $dompdf->stream('Auditoria.pdf', ['Attachment' => false]);

        // Obtener la ruta del directorio que contiene el favicon
        $basePath = realpath(base_url('assets/img/'));

        // Establecer la ruta del directorio como base path para los recursos
        $dompdf->setBasePath($basePath);

        $html = view('pdf/auditoria_auditoria', $data);
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
    protected function getBloqueForViewAuditoria($id_aud)
    {
        $auditoria = $this->model_auditorias->getBloqueCompletoAud_auditoria($id_aud);

        if ($auditoria) {
            $id_titulo_aud = $auditoria['modelo_tipo'];
            $auditoria['bloque'] = $this->model_auditorias->getSubtitulosAuditorias($id_titulo_aud);

            foreach ($auditoria['bloque'] as $key => $subtitulo) {
                /* == Carga las preguntas == */
                $auditoria['bloque'][$key]['preguntas_rtas'] = $this->model_auditorias->getIdPreguntasAuditorias($subtitulo['id'], $id_titulo_aud);

                $preguntas = $auditoria['bloque'][$key]['preguntas_rtas'];

                /* == Busca por id pregunta su correspondiente respuesta == */
                foreach ($preguntas as $llave => $p) {
                    $auditoria['bloque'][$key]['preguntas_rtas'][$llave]['respuesta'] = $this->model_auditorias->getRtasFromPreguntaAud_auditoria($id_aud, $p['id_pregunta']);
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
    public function getPaged_auditoria($offset = NULL, $tamanioPagina = NULL)
    {

        $params = [
            'modelo_tipo_auditoria_a' => $this->request->getPost("modelo_tipo_auditoria_a"),
            'contratista_auditoria_a' => $this->request->getPost("contratista_auditoria_a"),
            'supervisor_auditoria_a' => $this->request->getPost("supervisor_auditoria_a"),
            'proyecto_aud_auditoria_a' => $this->request->getPost("proyecto_aud_auditoria_a"),
            'usuario_carga_auditoria_a' => $this->request->getPost("usuario_carga_auditoria_a"),
            'fecha_desde_auditoria_a' => $this->request->getPost("fecha_desde_auditoria_a"),
            'fecha_hasta_auditoria_a' => $this->request->getPost("fecha_hasta_auditoria_a")
        ];

        if ((is_numeric($offset) && $offset >= 0) && (is_numeric($tamanioPagina) && $tamanioPagina > 0)) {
            $response = $this->model_auditorias->getAllPaged_auditoria($offset, $tamanioPagina, $params);
        } else {
            if (is_null($offset) && is_null($tamanioPagina)) {
                $response = $this->model_auditorias->getAllPaged_auditoria($offset, $tamanioPagina, $params, true);
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