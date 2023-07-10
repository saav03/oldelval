<?php

namespace App\Controllers;

use CodeIgniter\Controller;
use App\Libraries\MiClaseCompartida;
use PhpOffice\PhpSpreadsheet\IOFactory;

class Helper extends Controller
{

    public function __construct()
    {
        date_default_timezone_set('America/Argentina/Buenos_Aires');
        $this->session = \Config\Services::session();
        $this->validation = \Config\Services::validation();
        helper('adjunto');
        $this->model_logs = model('Model_logs');
        $this->model_general = model('Model_general');
        $this->model_usuario = model('Model_usuario');
        $this->model_grupo = model('Model_grupo');
        $this->model_movimiento = model('Model_movimiento');
        $this->model_helper = model('Model_helper');
    }

    public function cargarArchivos($FILES_name, $path, $name_prefix, $bd_info, $descriptions, $quality = 65, $bd_info_multiple = false)
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

    /**
     * Envía un correo cuando se crea una tarjeta de observación
     */
    public function sendMailTarjeta($datos, $tipo_mail)
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
                $correos[] = $datos['datos']['correo_responsable'];
                break;
            case '5': // Otro Responsable
                $id = $datos['datos'][0]['id_obs'];
                $subject = 'Nueva Tarjeta de Observación #' . $id;
                $datos['url'] = base_url('/TarjetaObs/view_obs/') . '/' . $id;
                $vista = view('emails/tarjetaObs/otroResponsable', $datos);
                $correos[] = $datos['datos']['relevo_responsable']['correo_responsable'];
                break;
            case '6':
                $id = $datos['datos']['id_obs'];
                $subject = 'Nueva Tarjeta de Observación #' . $id;
                $datos['url'] = base_url('/TarjetaObs/view_obs/') . '/' . $id;
                $vista = view('emails/tarjetaObs/reconocimiento', $datos);
                $correos[] = $datos['datos']['responsable_correo'];
                break;
        }

        $message = $vista;
        $correos[] = 'mdinamarca@blister.com.ar';
        $config['protocol'] = 'smtp';
        $config["SMTPHost"] = 'oldelval-cass.com';
        $config['mailType'] = 'html';
        $config["SMTPUser"] = '_mainaccount@oldelval-cass.com';
        $config["SMTPPass"] = 'OoBlister2@2@';
        $config["SMTPPort"] = '465'; //'587';
        $config['charset'] = 'utf-8';
        $config['wordwrap'] = TRUE;
        $config['validate'] = true;
        $config['SMTPCrypto'] = 'ssl';

        // $correos[] = 'mdinamarca@blister.com.ar';
        // $correos[] = 'blistersoftware@gmail.com';

        $email->initialize($config);
        $email->setFrom('_mainaccount@oldelval-cass.com', 'OLDELVAL');
        $email->setBCC($correos);
        $email->setSubject($subject);
        $email->setMessage($message);
        $email->send();
    }

    /**
     * Envía un correo
     */
    public function sendMail($datos, $subject_param, $url = '', $view = '', $emails = [])
    {
        $correos = [];
        $email = \Config\Services::email();

        # Datos que son necesarios para un correcto envío de e-mail
        $id = isset($datos['id']) ? $datos['id'] : '';
        $subject = $subject_param . $id;
        $datos['url'] = $url;
        $vista = view($view, $datos);

        # Cargo los correos enviados por parámetros
        foreach ($emails as $e) {
            $correos[] = $e;
        }

        $correos[] = 'mdinamarca@blister.com.ar';
        $message = $vista;
        $config = [];
        $config['protocol'] = 'smtp';
        $config["SMTPHost"] = 'oldelval-cass.com';
        $config['mailType'] = 'html';
        $config["SMTPUser"] = '_mainaccount@oldelval-cass.com';
        $config["SMTPPass"] = 'OoBlister2@2@';
        $config["SMTPPort"] = '465'; //'587';
        $config['charset'] = 'utf-8';
        $config['wordwrap'] = TRUE;
        $config['validate'] = true;
        $config['SMTPCrypto'] = 'ssl';

        $email->initialize($config);
        $email->setFrom('_mainaccount@oldelval-cass.com', 'OLDELVAL');
        $email->setBCC($correos);
        $email->setSubject($subject);
        $email->setMessage($message);
        $email->send();
    }

    public function importDataExcel()
    {
        // Ruta del archivo Excel
        $filePath = 'uploads/UsuariosEstadisticas.xlsx';

        // Cargar el archivo Excel
        $spreadsheet = IOFactory::load($filePath);

        // Obtener la primera hoja del archivo
        $worksheet = $spreadsheet->getActiveSheet();
        $data = [];

        // Obtener los datos de la hoja y guardarlos en la base de datos
        $escaparPrimerFila = true; // Bandera para obvuar la primer fila de los titulos de la tabla excel
        foreach ($worksheet->getRowIterator() as $row) {
            $aux = 0;
            $rowData = [];
            if ($escaparPrimerFila) {
                $escaparPrimerFila = false;
                continue;
            }
            foreach ($row->getCellIterator() as $cell) {

                switch ($aux) {
                    case '4':
                        if (intval($cell->getValue())) {
                            $formattedDate = \PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject($cell->getValue())->format('Y-m-d');
                            $rowData[] = $formattedDate;
                        } else {
                            $rowData[] = $cell->getValue();
                        }
                        break;
                    case '7':
                        $telefono = str_replace(" ", "", $cell->getValue());
                        $rowData[] = $telefono;
                        break;
                    default:
                        $rowData[] = $cell->getValue();
                        break;
                }
                $aux++;
            }
            $data[] = $rowData;
        }

        // Insertar los datos obtenidos en una array bien formateado

        $datos_sql = [];
        $datos_perfil = [
            'fecha_creacion' => date('Y-m-d H:i:s'),
            'fecha_first_login' => '',
            'fecha_modificacion_perfil' => '',
            'panel_emergente' => 1, //1 activo, predeterminado
            'estilo' => 0   //claro-oscuro, 0 claro predeterminado
        ];

        foreach ($data as $key => $value) {

            $test = [];
            for ($i = 0; $i < count($value); $i++) {

                switch ($i) {
                    case '0':
                        $test['apellido'] = $value[$i];
                        break;
                    case '1':
                        $test['nombre'] = $value[$i];
                        break;
                    case '2':
                        $test['dni'] = $value[$i];
                        $test['clave'] = password_hash($value[$i], PASSWORD_DEFAULT);
                        break;
                    case '3':
                        $test['competencia'] = $value[$i];
                        break;
                    case '4':
                        $test['fecha_nacimiento'] = $value[$i];
                        break;
                    case '5':
                        $test['empresa'] = $value[$i];
                        break;
                    case '6':
                        $test['correo'] = $value[$i];
                        break;
                    case '7':
                        $test['telefono'] = $value[$i];
                        break;
                    case '8':
                        $test['localidad'] = $value[$i];
                        break;
                    case '9':
                        $test['id_grupo'] = $value[$i];
                        $test['id_usuario_creador'] = 3;
                        break;
                }
            }
            $datos_sql[] = $test;
        }

        // Inserto los usuarios a la BD
        for ($i = 0; $i < 7; $i++) {
            $grupos = [];
            $results = $this->model_usuario->add($datos_sql[$i]);
            $last_id = $results['last_id'];
            $datos_perfil['id_usuario'] = $last_id;
            $this->model_usuario->addDatosPerfil($datos_perfil);
            $grupos[] = $datos_sql[$i]['id_grupo'];
            $results = $this->model_grupo->vincularUsuario($grupos, $last_id);
            $this->newMov(1, 1, $last_id); //Movimiento
        }
        echo "Todo joyaa!!";
    }

    /**
     * Esto es la relación que va a tener cada usuario la primera vez que se crea
     * Agarra el ID del grupo, captura los permisos que pertenece a ese grupo
     * e inserta esos permisos a la tabla gg_rel_usuario_permiso con el id del usuario
     */
    public function relacionPermisosGrupo($id_usuario)
    {
        $data = $this->model_helper->getGrupoRelUsuario($id_usuario);

        $permisos = $this->model_helper->getPermisosFromGrupo($data['id_grupo']);
        $id_permisos = [];
        for ($i = 0; $i < count($permisos); $i++) {
            $id_permisos[] = $permisos[$i]['id_permiso'];
        }

        foreach ($id_permisos as $id) {
            $data = [
                'id_usuario' => $id_usuario,
                'id_permiso' => $id,
            ];
            $this->model_general->insertG('gg_rel_usuario_permiso', $data);
        }
    }

    public function relacionPermisosGruposMayores($usuario_mayor)
    {
        $usuarios = $this->model_helper->getGrupoRelUsuarioMayores($usuario_mayor);
        
        for ($i = 0; $i < count($usuarios); $i++) {
            $permisos = $this->model_helper->getPermisosFromGrupo($usuarios[$i]['id_grupo']);

            $id_permisos = [];
            for ($j = 0; $j < count($permisos); $j++) {
                $id_permisos[] = $permisos[$j]['id_permiso'];
            }

            foreach ($id_permisos as $id) {
                $data = [
                    'id_usuario' => $usuarios[$i]['id'],
                    'id_permiso' => $id,
                ];
                $this->model_general->insertG('gg_rel_usuario_permiso', $data);
            }
        }
        echo "¡Cargó todos los permisos de diez perruno!";

    }


    public function newMov($id_modulo, $accion, $id_afectado = null, $id_input = null) //$id_input es para la edicion, identifica que input fue modificado
    {
        $model_movimiento = model('Model_movimiento');
        $datos = [
            // 'id_usuario' => session()->get('id_usuario'),
            'id_usuario' => 3,
            'id_modulo' => $id_modulo,
            'id_accion' => $accion,
            'id_afectado' => $id_afectado,
            'comentario' => $id_input
        ];
        $model_movimiento->add($datos);
    }
}
