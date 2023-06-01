<?php

namespace App\Controllers;

class Aud_Control extends Auditorias
{
    public function __construct()
    {
        parent::__construct();
        $this->model_aud_control = model('Model_aud_control');
    }

    /**
     * Generar un nuevo descargo
     */
    public function submitDescargo()
    {

        $id_hallazgo = $this->request->getPost('id_hallazgo');

        $datos_descargo  = [
            'id_hallazgo' => $id_hallazgo,
            'motivo'    => $this->request->getPost('new_descargo'),
            'id_usuario' => session()->get('id_usuario'),
        ];

        $results = $this->model_auditorias->addDescargo($datos_descargo);

        /* == ¡DESCOMENTAR DESPUÉS PARA ENVIAR CORREOS! == */
        // $datos['datos'] = $this->model_mail_tarjeta->getInfoNewDescargo($id_hallazgo);
        // $this->sendMail($datos, 3);

        /** == Cargar Adjuntos del plan de Acción (Si Corresponde) en BD == **/
        $upload_ruta = "uploads/auditorias/descargos/";
        $desc_adjuntos = $this->request->getPost('adj_descargo-description');

        for ($i = 0; $i < count($_FILES["adj_descargo"]["name"]); $i++) {

            /* == Si no está vacio.. == */
            if (!empty($_FILES["adj_descargo"]["name"][$i])) {
                $target_file = $upload_ruta . basename($_FILES["adj_descargo"]["name"][$i]);
                $archivoTemporal = $_FILES["adj_descargo"]["tmp_name"][$i];

                $extension = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));
                $nombre = "adj_descargo_" . $i . "_" . date('Y-m-d-H-i-s') . "." . $extension;
                $uploadPath = $upload_ruta . $nombre;
                $archivoCargado = false;

                if (in_array(strtolower($extension), ["pdf", "jpg", "jpeg", "png", "doc", "docx", "docm", "xls", "xlsx", "xlsb"])) {
                    if (move_uploaded_file($archivoTemporal, $uploadPath)) {
                        $archivoCargado = true;
                    }
                }

                if ($archivoCargado) {
                    $dato_bd = [
                        'id_descargo' => $results['last_id'],
                        'adjunto' => $nombre,
                        'desc_adjunto' => $desc_adjuntos[$i],
                    ];
                    $this->model_general->insertG('obs_descargos_adjuntos', $dato_bd);
                }
            }
        }

        return $results;
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
        $datos_motivo_cierre  = [
            'motivo'    => $this->request->getPost('motivo_cierre_obs'),
            'cierre_manual'    => $cierre_forzado,
            'id_hallazgo_obs' => $id_hallazgo,
            'id_usuario_cierre' => session()->get('id_usuario'),
        ];

        $results_cierre = $this->model_aud_control->addMotivoCierre($datos_motivo_cierre);
    }

    /**
     * Dar una respuesta a un descargo
     */
    public function submitRtaDescargo()
    {

        $id_descargo = $this->request->getPost('inp_id_descargo');

        /* == Si es 1 se aceptó el descargo | Si es 2 se rechazó == */
        $estado = ($this->request->getPost('tipo_rta_descargo') == 1) ? 1 : 2;

        $datos_rta_descargo  = [
            'estado'    => $estado,
            'respuesta'    => $this->request->getPost('rta_descargo'),
            'id_usuario_rta' => session()->get('id_usuario'),
        ];

        $this->model_auditorias->editDescargo($datos_rta_descargo, $id_descargo);

        /* == ¡DESCOMENTAR DESPUÉS PARA ENVIAR CORREOS! == */
        // $datos_mail['datos'] = $this->model_mail_tarjeta->getRespuestaDescargo($id_descargo);
        // $this->sendMail($datos_mail, 4);
    }
}
