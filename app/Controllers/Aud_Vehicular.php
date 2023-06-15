<?php

namespace App\Controllers;

use Dompdf\Dompdf;
use Dompdf\Options;
use DateTime;

class Aud_Vehicular extends Auditorias
{
    public function __construct()
    {
        parent::__construct();
        $this->model_aud_vehicular = model('Model_aud_vehicular');
    }

    /**
     * Carga la planilla de una Auditoria Vehicular
     */
    public function submitPlanilla()
    {
        $helper = new Helper();
        $bloque_respuestas = $this->request->getPost('bloque_respuestas');
        $comentarios_preguntas = $this->request->getPost('comentarios_preguntas');
        $oportunidad_mejora = $this->request->getPost('oportunidad_mejora_v');
        $modelo_tipo = $this->request->getPost('tipo_auditoria');

        $datos = [
            'contratista' => $this->request->getPost('contratista_v'),
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
            'tipo_auditoria' => $this->request->getPost('tipo_auditoria'),
            'usuario_carga' => session()->get('id_usuario'),
        ];

        $tipo_obs = $this->request->getPost('tipo_obs');

        $result = parent::verificacion($datos, 'validation_aud_vehicular');

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
                        'tipo_auditoria' => 0,
                        'hallazgo' => $this->request->getPost('hallazgo_v'),
                        'plan_accion' => $this->request->getPost('plan_accion_v'),
                        'contratista' => $this->request->getPost('contratista_v'),
                        'responsable' => $this->request->getPost('responsable_plan_v'),
                        'relevo_responsable' => $this->request->getPost('relevo_responsable_plan_v'),
                        'fecha_cierre' => $this->request->getPost('fecha_cierre_v'),
                        'usuario_carga' => session()->get('id_usuario'),
                    ];

                    $result_plan = $this->verificacion($datos_plan_accion, 'validation_aud_plan');
                    if ($result_plan['exito']) {
                        $id_aud = $this->model_general->insertG('auditoria_vehicular', $datos);
                        parent::submitRtaPreguntasAud($id_aud, 0, 0, $bloque_respuestas, $comentarios_preguntas, $tipo_obs);

                        $datos_plan_accion['id_auditoria'] = $id_aud;
                        $significancia = $this->request->getPost('significancia_v');
                        $efectos = $this->request->getPost('efecto_impacto_v');

                        $datos_plan_accion['id_auditoria'] = $id_aud;
                        $id_hallazgo = parent::submitUploadPlanAccion($datos_plan_accion, $significancia, $efectos);

                        # Se envía los E-Mails
                        $datos_emails = $this->model_aud_vehicular->getDataHallazgoEmail($id_aud, $id_hallazgo, 0);
                        $url = base_url('/auditorias/view_aud_vehicular/') . '/' . $datos_emails['id'];
                        $datos_emails['url'] = $url;
                        $emails = [];

                        # _ Usuario quien carga
                        $emails[] = $datos_emails['correo_usuario_carga'];
                        $helper->sendMail($datos_emails, 'Nuevo CheckList Vehicular #', $url, 'emails/auditorias/vehicular/nueva', $emails);

                        # _ Responsable
                        $emails = [];
                        $emails[] = $datos_emails['correo_responsable'];
                        $helper->sendMail($datos_emails, 'Nuevo CheckList Vehicular #', $url, 'emails/auditorias/vehicular/responsable', $emails);

                        # _ Relevo Responsable (¡Si existe!)
                        if (isset($datos_emails['correo_relevo'])) {
                            $emails = [];
                            $emails[] = $datos_emails['correo_relevo'];
                            $helper->sendMail($datos_emails, 'Nuevo CheckList Vehicular #', $url, 'emails/auditorias/vehicular/relevo', $emails);
                        }
                    } else {
                        echo json_encode($result_plan['errores']);
                    }
                } else {
                    $id_aud = $this->model_general->insertG('auditoria_vehicular', $datos);
                    parent::submitRtaPreguntasAud($id_aud, 0, $modelo_tipo, $bloque_respuestas, $comentarios_preguntas, $tipo_obs);
                }
            else :
                echo json_encode($result_requiere['errores']);
            endif;
        } else {
            echo json_encode($result['errores']);
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
            $data['hallazgo'] =  $this->model_auditorias->getHallazgoAud($id_aud, 0);

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

            return template('auditoria/view_vehicular', $data);
        }
    }

    public function view_aud_vehicular_pdf($id_auditoria)
    {
        // Cargar la biblioteca Dompdf
        $dompdf = new Dompdf();
        $data['auditoria'] = $this->getBloqueForViewVehicular($id_auditoria);
        $data['hallazgo'] = $this->model_auditorias->getHallazgoAud($id_auditoria, 0);
        $data['tipo_obs_vehicular'] = $this->model_general->getAllEstadoActivo('tipo_obs_vehicular');

        // return view('pdf/auditoria_control', $data);
        // exit;

        // Obtener la ruta del directorio que contiene el favicon
        $basePath = realpath(base_url('assets/img/'));

        // Establecer la ruta del directorio como base path para los recursos
        $dompdf->setBasePath($basePath);

        $html = view('pdf/auditoria_vehicular', $data);
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
}
