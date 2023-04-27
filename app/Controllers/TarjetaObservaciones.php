<?php

namespace App\Controllers;

use Config\Validation;

class TarjetaObservaciones extends BaseController
{
    public function __construct()
    {
        $this->session = \Config\Services::session();
        $this->validation = \Config\Services::validation();
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
        return template('tarjetas_obs/view_obs', $data);
    }

    public function view_add_obs()
    {
        $data['tabla'] = $this->model_tarjeta->getMatrizRiesgo();
        $data['proyectos'] =  $this->model_general->getAllEstadoActivo('proyectos');
        $data['estaciones'] =  $this->model_general->getAllEstadoActivo('estaciones_bombeo');
        $data['sistemas'] =  $this->model_general->getAllEstadoActivo('sistemas_oleoductos');
        $data['indicadores'] =  $this->model_general->getAllEstadoActivo('tarjeta_indicadores');
        $data['clasificaciones'] =  $this->model_general->getAllEstadoActivo(' tarjeta_clasificaciones');
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


        $datos_tarjeta  = [
            'fecha_deteccion'    => $this->request->getPost('fecha_deteccion'),
            'tipo_obs'     => $this->request->getPost('tipo_obs'),
            'situacion'     => $this->request->getPost('situacion'),
            'observador'     => $this->request->getPost('observador'),
            'descripcion'    => $this->request->getPost('descripcion'),
            'proyecto'    => $this->request->getPost('proyecto'),
            'modulo'    => $this->request->getPost('modulo'),
            'estacion_bombeo'    => empty($this->request->getPost('estacion_bombeo')) ? null : $this->request->getPost('estacion_bombeo'),
            'sistema_oleoducto'    => empty($this->request->getPost('sistema_oleoducto')) ? null : $this->request->getPost('sistema_oleoducto'),
        ];

        $verificacion_tarjeta = $this->verificacion($datos_tarjeta, 'validation_tarjeta');

        if ($verificacion_tarjeta['exito']) {

            $posee_obs = $this->request->getPost('posee_obs');

            /** == Se insertan los datos ==  **/
            $datos_tarjeta['fecha_hora_carga'] = date('Y-m-d H:i:s');
            $datos_tarjeta['usuario_carga'] = session()->get('id_usuario');

            $datos_obs = [];

            if ($posee_obs == 1) {

                /* == Hay plan de acción == */

                $datos_obs = [
                    'hallazgo'    => $this->request->getPost('hallazgo'),
                    'accion_recomendacion'    => $this->request->getPost('accion_recomendacion'),
                    'clasificacion'    => $this->request->getPost('clasificacion'),
                    'tipo'    => $this->request->getPost('tipo'),
                    'riesgo_fila'    => $this->request->getPost('riesgo_fila'),
                    'riesgo'    => $this->request->getPost('riesgo_tab'),
                    'contratista'    => $this->request->getPost('contratista'),
                    'responsable'    => $this->request->getPost('responsable'),
                    'fecha_cierre'    => $this->request->getPost('fecha_cierre'),
                ];

                $verificacion_obs_negativa = $this->verificacion($datos_obs, 'validation_hallazgo');

                if (!$verificacion_obs_negativa['exito']) {
                    $datos_obs = [];
                    $errores = $verificacion_obs_negativa['errores'];
                    echo json_encode($errores);
                }
            } else if ($posee_obs == 2) {

                /* == Hay observación positiva == */

                $datos_obs = [
                    'hallazgo'    => $this->request->getPost('desc_positivo'),
                    'clasificacion'    => $this->request->getPost('clasificacion'),
                    'contratista'    => $this->request->getPost('contratista'),
                ];

                $verificacion_obs_positiva = $this->verificacion($datos_obs, 'validation_obs_positiva');

                if (!$verificacion_obs_positiva['exito']) {
                    $datos_obs = [];
                    $errores = $verificacion_obs_positiva['errores'];
                    echo json_encode($errores);
                }
            } else {
                $datos_obs = [];
            }

            if (count($datos_obs) > 0) {
                $results = $this->model_tarjeta->addSubmit($datos_tarjeta);

                if ($results['status']) {

                    $id_tarjeta_obs = $results['last_id'];

                    /* == Inserto los Indicadores (Si es que existen) == */
                    $indicadores = $this->request->getPost('btn_indicador');
                    foreach ($indicadores as $key => $indicador) {
                        $data = [
                            'id_tarjeta' => $id_tarjeta_obs,
                            'id_indicador' => $key,
                            'rta' => $indicador,
                        ];
                        $this->model_general->insertG('tarjeta_rel_indicadores', $data);
                    }

                    $datos_obs['id_tarjeta'] = $id_tarjeta_obs;
                    $datos_obs['fecha_hora_carga'] = date('Y-m-d H:i:s');
                    $datos_obs['usuario_carga'] = session()->get('id_usuario');

                    $results_hallazgo = $this->model_tarjeta->addSubmitHallazgo($datos_obs);

                    if ($results_hallazgo['status']) {
                        $id_hallazgo = $results_hallazgo['last_id'];
                    }

                    /* == Se cargan adjuntos si es que realmente existen == */
                    if ($this->request->getPost('files-description')) {
                        $bd_info = array(
                            'table' => 'tarjeta_hallazgos_adjuntos',
                            'file' => 'adjunto',
                            'description' => 'desc_adjunto',
                            'optional_ids' => ['id_hallazgo' => $id_hallazgo['id']]
                        );
                        $this->cargarArchivos('files', 'uploads/tarjetaObs/', 'obs_tarjeta', $bd_info, $this->request->getPost('files-description'));
                    }

                    // $datos['datos'] = $this->model_mail_tarjeta->getInfoTarjetaCreada($id_tarjeta_obs);

                    /* == Envío un correo al responsable asignado en la tarjeta == */
                    /* switch ($posee_obs) {
                        case '1':
                            $datos['datos'] = $this->model_mail_tarjeta->getInfoTarjetaCreada($id_tarjeta_obs, $id_hallazgo['id']);
                            $this->sendMail($datos, 1);
                            $this->sendMail($datos, 2);
                            break;
                        case '2':
                            $datos['datos'] = $this->model_mail_tarjeta->getInfoTarjetaCreada($id_tarjeta_obs);
                            $this->sendMail($datos, 1);
                            break;
                        default:
                            $datos['datos'] = $this->model_mail_tarjeta->getInfoTarjetaCreada($id_tarjeta_obs);
                            $this->sendMail($datos, 1);
                            break;
                    } */
                }
                newMov(6, 1, $id_tarjeta_obs); //Movimiento

            } else if ($posee_obs != 1 && $posee_obs != 2 && empty($datos_obs)) {
                $results = $this->model_tarjeta->addSubmit($datos_tarjeta);
                $id_tarjeta_obs = $results['last_id'];

                newMov(6, 1, $id_tarjeta_obs); //Movimiento
                // $datos['datos'] = $this->model_mail_tarjeta->getInfoTarjetaCreada($id_tarjeta_obs);
                /* == Envío un correo a quien creó la tarjeta sin plan de acción == */
                // $this->sendMail($datos, 1);
            }
        } else {
            $errores = $verificacion_tarjeta['errores'];
            echo json_encode($errores);
        }
    }

    /**
     * Generar un nuevo descargo
     */
    public function submitDescargo()
    {

        $id_hallazgo = $this->request->getPost('id_hallazgo');

        $datos_descargo  = [
            'id_hallazgo'     => $id_hallazgo,
            'motivo'    => $this->request->getPost('new_descargo'),
            'id_usuario' => session()->get('id_usuario'),
            'fecha_hora_motivo' => date('Y-m-d H:i:s'),
        ];

        /* ¿Necesita que sea requerido? */
        // $verificacion_obs_negativa = $this->verificacion($datos_descargo, 'validation_hallazgo');

        $results_descargo = $this->model_tarjeta->addDescargo($datos_descargo);

        // $datos['datos'] = $this->model_mail_tarjeta->getInfoNewDescargo($id_hallazgo);
        // $this->sendMail($datos, 3);
        /* == Se cargan adjuntos si es que realmente existen == */
        if ($this->request->getPost('files-description')) {
            $bd_info = array(
                'table' => 'tarjeta_descargos_adj',
                'file' => 'adjunto',
                'description' => 'desc_adjunto',
                'optional_ids' => ['id_descargo' => $results_descargo['last_id']['id']]
            );
            $this->cargarArchivos('files', 'uploads/tarjetaObs/descargos/', 'obs_descargo', $bd_info, $this->request->getPost('files-description'));
        }

        return $results_descargo;
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
            'fecha_hora_respuesta' => date('Y-m-d H:i:s'),
        ];

        $results_descargo = $this->model_tarjeta->editDescargo($datos_rta_descargo, $id_descargo);
        // $datos_mail['datos'] = $this->model_mail_tarjeta->getRespuestaDescargo($id_descargo);
        // $this->sendMail($datos_mail, 4);
    }

    /**
     * Cerrar la Tarjeta de Observación
     */
    public function submitCerrarObs()
    {

        $id_tarjeta = $this->request->getPost('id_tarjeta_close');

        $datos_actualizar_tarjeta = [
            'situacion' => 0
        ];

        $this->model_general->updateG('tarjeta_observaciones', $id_tarjeta, $datos_actualizar_tarjeta);

        $datos_motivo_cierre  = [
            'motivo'    => $this->request->getPost('motivo_cierre_obs'),
            'id_tarjeta_obs' => $id_tarjeta,
            'fecha_hora_cierre' => date('Y-m-d H:i:s'),
            'id_usuario_cierre' => session()->get('id_usuario'),
        ];

        $results_cierre = $this->model_tarjeta->addMotivoCierre($datos_motivo_cierre);
    }

    protected function cargarArchivos($FILES_name, $path, $name_prefix, $bd_info, $descriptions, $quality = 65, $bd_info_multiple = false)
    {
        /*
        bd_info espera:
         'table' => nombre de la tabla en la bd donde van los adjuntos /*Necesario
         'file' => nombre de la columna donde se colocara el nombre del archivo /*Necesario
         'description' => nombre de la columna donde se colocara la descripcion 
         'optional_ids' => arreglo con posibles columnas extras de la tabla que son mas alla de las necesarias
        */
        $archivos_post = $_FILES[$FILES_name];
        $descripciones_post = $descriptions;
        $arreglo_bd = []; //Para la carga a la BD

        if (!empty($archivos_post["name"])) {
            //Existen archivos
            foreach ($archivos_post["name"] as $num => $name) {
                if (!empty($name)) {
                    $extension = pathinfo($name, PATHINFO_EXTENSION);
                    $nombre = "$name_prefix-$num-" . date('Y-m-d-H-i-s') . "." . $extension; //este es el nombre del archivo que se guardara en el servidor y se referenciara a la BD
                    $uploadPath = $path . $nombre;
                    $archivoTemporal = $archivos_post["tmp_name"][$num]; //ESTO es lo que se cargara
                    $archivoCargado = false;
                    if (in_array(strtolower($extension), ['jpeg', 'png', 'gif', 'jpg', 'bmp'])) {
                        //Se esperan imagenes a este punto
                        $archivoCargado = $this->optimizar_imagen($archivoTemporal, $uploadPath, $quality);
                    } else {
                        //Se esperan PDFS
                        if (in_array(strtolower($extension), ["pdf", "doc", "docx", "docm", "xls", "xlsx", "xlsb"])) {
                            if (move_uploaded_file($archivoTemporal, $uploadPath)) {
                                //Se carga correctamente
                                $archivoCargado = true;
                            }
                        }
                    }
                    if ($archivoCargado) {
                        //se puede referenciar en la base de datos
                        $registro[$bd_info['file']] = $nombre;
                        if (isset($bd_info['description'])) {
                            $registro[$bd_info['description']] = isset($descripciones_post[$num]) ? $descripciones_post[$num] : "";
                        }
                        if (isset($bd_info['optional_ids'])) {
                            foreach ($bd_info['optional_ids'] as $column => $val) {
                                $registro[$column] = $val;
                            }
                        }
                        $arreglo_bd[] = $registro;
                    }
                }
            } // fin foreach
        }

        if ($arreglo_bd) {
            $tabla = $bd_info['table'];
            if ($bd_info_multiple) {
                $tabla = $bd_info[$num]['table'];
            }
            //hay algo
            $this->model_general->insertMultiple($tabla, $arreglo_bd);
        }
    } //agregar un retrun

    /**
     * Comprime la imagen al tamaño deseado.
     * 
     * @param source La ruta de la imagen que desea comprimir.
     * @param destination La ruta del archivo en el que desea guardar la imagen comprimida.
     * @param quality Es la calidad de la imagen. Va de 0 (peor calidad, archivo más pequeño) a 100 (mejor calidad, archivo más grande). 
     * El valor por defecto es el valor de calidad IJG por defecto (alrededor de 75).
     * 
     * @return a boolean value.
     */
    protected function optimizar_imagen($source, $destination, $quality)
    {
        // Ver la información de la imagen
        $info = getimagesize($source);
        $mime = $info['mime'];
        $puede_comprimir = true;
        // Crear una nueva imagen a partir de la ruta
        switch ($mime) {
            case 'image/jpeg':
                $image = imagecreatefromjpeg($source);
                break;
            case 'image/png':
                $image = imagecreatefrompng($source);
                break;
            default:
                $puede_comprimir = false;
                break;
        }

        if ($puede_comprimir) {
            // Comprime la imágen al tamaño que se desee
            imagejpeg($image, $destination, $quality);
        }
        return $puede_comprimir;
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

    /**
     * Envía un correo cuando se crea una tarjeta de observación
     */
    protected function sendMail($datos, $tipo_mail)
    {
        $correos = [];
        $email = \Config\Services::email();
        switch ($tipo_mail) {
            case '1': // Nueva Tarjeta Creada
                $id = $datos['datos'][0]['id_obs'];
                $subject = 'Nueva Tarjeta de Observación #' . $id;
                $datos['url'] = base_url('/TarjetaObs/view_obs/') . '/' . $id;
                $vista = view('emails/tarjetaObs/nueva', $datos);
                $correos[] = $datos['datos'][0]['correo_carga'];
                break;
            case '2': // Responsable
                $id = $datos['datos'][0]['id_obs'];
                $subject = 'Nueva Tarjeta de Observación #' . $id;
                $datos['url'] = base_url('/TarjetaObs/view_obs/') . '/' . $id;
                $vista = view('emails/tarjetaObs/responsable', $datos);
                $correos[] = $datos['datos']['responsable']['correo_responsable'];
                break;
            case '3': // Descargo
                $id = $datos['datos']['id_obs'];
                $subject = 'Nuevo Descargo | Tarjeta #' . $id;
                $datos['url'] = base_url('/TarjetaObs/view_obs/') . '/' . $id;
                $vista = view('emails/tarjetaObs/descargo', $datos);
                $correos[] = $datos['datos']['correo_carga'];
                break;
            case '4': // Acepta/Rechaza descargo
                $id = $datos['datos']['id_obs'];

                if ($datos['datos']['estado_rta'] == 1) {
                    $subject = 'Descargo Aceptado | Tarjeta #' . $id;
                } else {
                    $subject = 'Descargo Rechazado | Tarjeta #' . $id;
                }

                $datos['url'] = base_url('/TarjetaObs/view_obs/') . '/' . $id;
                $vista = view('emails/tarjetaObs/estado', $datos);
                $correos[] = $datos['datos']['correo_carga'];
                break;
        }

        // $subject = 'Nueva Tarjeta de Observación';
        // $subject = 'Nueva Descargo de la Tarjeta de Observación';
        // $subject = 'Descargo Aceptado | Tarjeta de Observaciones';
        $message = $vista;
        // $message = $vista;
        $correos[] = 'mdinamarca@blister.com.ar';
        $config['protocol'] = 'smtp';
        $config["SMTPHost"] = 'sd-1860055-l.dattaweb.com';
        $config['mailType'] = 'html';
        $config["SMTPUser"] = 'no-reply@shell-hsse.com';
        $config["SMTPPass"] = '6jKgSi9Bqh';
        $config["SMTPPort"] = '465'; //'587';
        $config['charset'] = 'utf-8';
        $config['wordwrap'] = TRUE;
        $config['validate'] = true;
        $config['SMTPCrypto'] = 'ssl';

        // $correos[] = 'mdinamarca@blister.com.ar';
        // $correos[] = 'blistersoftware@gmail.com';

        $email->initialize($config);
        $email->setFrom('no-reply@shell-hsse.com', 'OLDELVAL');
        $email->setBCC($correos);
        $email->setSubject($subject);
        $email->setMessage($message);
        $email->send();
    }

    public function testing()
    {
        // $this->sendMail();
        // echo 'Se envió todo correcto';
        $datos['datos'] = $this->model_mail_tarjeta->getInfoTarjetaCreada(1, 1);
        echo '<pre>';
        var_dump($datos);
        echo '</pre>';
        $id = $datos['datos'][0]['id_obs'];
        $datos['url'] = base_url('/TarjetaObs/view_obs/') . '/' . 2;
        // $this->sendMail(view('emails/tarjetaObs/nueva'), $datos, 1);
        return view('emails/tarjetaObs/nueva', $datos);
    }
}
