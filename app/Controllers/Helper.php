<?php

namespace App\Controllers;

use CodeIgniter\Controller;
use App\Libraries\MiClaseCompartida;

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
        }

        $message = $vista;
        // $correos[] = 'mdinamarca@blister.com.ar';
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
}
