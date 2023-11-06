<?php

namespace App\Controllers;

use Dompdf\Dompdf;
use Dompdf\Options;
use DateTime;

class Aud_Control extends Auditorias
{
    public function __construct()
    {
        parent::__construct();
        $this->model_aud_control = model('Model_aud_control');
    }

    /**
     * Carga la planilla para una Auditoría de Control
     */
    public function submitPlanilla()
    {
        $helper = new Helper();
        $bloque_respuestas = $this->request->getPost('bloque_respuestas');
        $comentarios_preguntas = $this->request->getPost('comentarios_preguntas');
        $oportunidad_mejora = $this->request->getPost('oportunidad_mejora');

        $datos = [
            'modelo_tipo' => $this->request->getPost('tipo_auditoria'),
            'contratista' => $this->request->getPost('contratista'),
            'supervisor_responsable' => $this->request->getPost('supervisor_responsable'),
            'cant_personal' => $this->request->getPost('cant_personal'),
            'num_informe' => $this->request->getPost('num_informe'),
            'proyecto' => $this->request->getPost('proyecto'),
            'modulo' => $this->request->getPost('modulo'),
            'estacion' => $this->request->getPost('estacion_bombeo'),
            'tipo_auditoria' => $this->request->getPost('tipo_auditoria'),
            'sistema' => $this->request->getPost('sistema'),
            'usuario_carga' => session()->get('id_usuario'),
        ];

        $result = parent::verificacion($datos, 'validation_aud_control');

        if ($result['exito']) {

            # Remuevo el tipo de auditoría ya que es solamente para validar
            unset($datos['tipo_auditoria']);

            $require_plan = [
                'oportunidad_mejora' => $oportunidad_mejora,
            ];

            $result_requiere = parent::verificacion($require_plan, 'validation_requiere_plan');

            if ($result_requiere['exito']) :

                # Significa que hay plan de acción
                if ($oportunidad_mejora == 1) {

                    $datos_plan_accion = [
                        'tipo_auditoria' => 1,
                        'hallazgo' => $this->request->getPost('hallazgo'),
                        'plan_accion' => $this->request->getPost('plan_accion'),
                        'contratista' => $this->request->getPost('contratista'),
                        'responsable' => $this->request->getPost('responsable_plan'),
                        'relevo_responsable' => $this->request->getPost('relevo_responsable_plan'),
                        'significancia' => $this->request->getPost('significancia'),
                        'fecha_cierre' => $this->request->getPost('fecha_cierre'),
                        'usuario_carga' => session()->get('id_usuario'),
                    ];

                    $result_plan = $this->verificacion($datos_plan_accion, 'validation_aud_plan');

                    if ($result_plan['exito']) {
                        $id_aud = $this->model_general->insertG('auditoria_control', $datos);
                        parent::submitRtaPreguntasAud($id_aud, 1, 1, $bloque_respuestas, $comentarios_preguntas);

                        $datos_plan_accion['id_auditoria'] = $id_aud;
                        $efectos = $this->request->getPost('efecto_impacto');

                        $datos_plan_accion['id_auditoria'] = $id_aud;
                        $id_hallazgo = parent::submitUploadPlanAccion($datos_plan_accion, $efectos);

                        # Se envía los E-Mails
                        $datos_emails = $this->model_aud_control->getDataHallazgoEmail($id_aud, $id_hallazgo, 1);
                        $url = base_url('/auditorias/view_aud_control/') . '/' . $datos_emails['id'];
                        $datos_emails['url'] = $url;
                        $emails = [];

                        # _ Usuario quien carga
                        $emails[] = $datos_emails['correo_usuario_carga'];
                        $helper->sendMail($datos_emails, 'Nueva Auditoría Control #', $url, 'emails/auditorias/control/nueva', $emails);

                        # _ Responsable
                        $emails = [];
                        $emails[] = $datos_emails['correo_responsable'];
                        $helper->sendMail($datos_emails, 'Nueva Auditoría Control #', $url, 'emails/auditorias/control/responsable', $emails);

                        # _ Relevo Responsable (¡Si existe!)
                        if (isset($datos_emails['correo_relevo'])) {
                            $emails = [];
                            $emails[] = $datos_emails['correo_relevo'];
                            $helper->sendMail($datos_emails, 'Nueva Auditoría Control #', $url, 'emails/auditorias/control/relevo', $emails);
                        }

                        newMov(9, 1, $id_aud, 'Inspección de Control'); //Movimiento (Registra el ID de la Inspección de Control creada)
                        
                    } else {
                        echo json_encode($result_plan['errores']);
                    }
                } else {
                    $id_aud = $this->model_general->insertG('auditoria_control', $datos);
                    parent::submitRtaPreguntasAud($id_aud, 1, 1, $bloque_respuestas, $comentarios_preguntas);
                    newMov(9, 1, $id_aud, 'Inspección de Control'); //Movimiento (Registra el ID de la Inspección de Control creada)
                }
            else :
                echo json_encode($result_requiere['errores']);
            endif;

        } else {
            echo json_encode($result['errores']);
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
            $data['hallazgo'] =  $this->model_auditorias->getHallazgoAud($id_aud, 1);

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


            return template('auditoria/view_control', $data);
        }
    }

    public function view_aud_control_pdf($id_auditoria)
    {
        // Cargar la biblioteca Dompdf
        $dompdf = new Dompdf();
        $data['auditoria'] = $this->getBloqueForViewControl($id_auditoria);
        $data['hallazgo'] = $this->model_auditorias->getHallazgoAud($id_auditoria, 1);

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

        $html = view('pdf/auditoria_control', $data);
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
     * Dynamic Table para la edición de las Auditorías de Control
     */
    public function getPagedEdicionControl($offset = NULL, $tamanioPagina = NULL)
    {

        if ((is_numeric($offset) && $offset >= 0) && (is_numeric($tamanioPagina) && $tamanioPagina > 0)) {
            $response = $this->model_auditorias->getAllPagedControlEdicion($offset, $tamanioPagina);
        } else {
            if (is_null($offset) && is_null($tamanioPagina)) {
                $response = $this->model_auditorias->getAllPagedControlEdicion($offset, $tamanioPagina, true);
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
}
