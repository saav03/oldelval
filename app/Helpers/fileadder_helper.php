<?php if (!defined('BASEPATH')) exit('No direct script access allowed');


function comprimir($source, $destination, $quality){
    // Get image info 
    $imgInfo = getimagesize($source); 
    $mime = $imgInfo['mime']; 
    $puede_comprimir = true;
    // Create a new image from file 
    switch($mime){ 
        case 'image/jpeg': 
            $image = imagecreatefromjpeg($source); 
            break; 
        case 'image/png': 
            $image = imagecreatefrompng($source); 
            break; 
        case 'image/gif': 
            $image = imagecreatefromgif($source); 
            break;
        case 'image/bmp':
            $image = imagecreatefrombmp($source);
            break;  
        default: 
            $puede_comprimir = false;
            break; 
    } 
    if($puede_comprimir)
        imagejpeg($image, $destination, $quality);
    return $puede_comprimir;
}

function cargarArchivos($FILES_name, $path, $name_prefix, $bd_info, $quality = 65, $bd_info_multiple = false){
	/*
	bd_info espera:
	 'table' => nombre de la tabla en la bd donde van los adjuntos /*Necesario
	 'file' => nombre de la columna donde se colocara el nombre del archivo /*Necesario
	 'description' => nombre de la columna donde se colocara la descripcion 
	 'optional_ids' => arreglo con posibles columnas extras de la tabla que son mas alla de las necesarias
	*/ 
	$CI = get_instance();
    $archivos_post = $_FILES[$FILES_name];
    $descripciones_post = $CI->input->post("$FILES_name-description");

    $arreglo_bd = []; //Para la carga a la BD
    if(!empty($archivos_post["name"])){
        //Existen archivos
        foreach($archivos_post["name"] as $num => $name){
            if(!empty($name)){
                $extension = pathinfo($name, PATHINFO_EXTENSION);
                $nombre = "$name_prefix-$num-".date('Y-m-d-H-i-s').".".$extension; //este es el nombre del archivo que se guardara en el servidor y se referenciara a la BD
                $uploadPath = $path.$nombre;
                $archivoTemporal = $archivos_post["tmp_name"][$num]; //ESTO es lo que se cargara
                $archivoCargado = false;
                if(in_array(strtolower($extension), ['jpeg', 'png', 'gif', 'jpg', 'bmp'])){
                    //Se esperan imagenes a este punto
                    $archivoCargado = comprimir($archivoTemporal, $uploadPath, $quality);
                }else{
                    //Se esperan PDFS
                    if(in_array(strtolower($extension), ["pdf","doc","docx","docm","xls","xlsx","xlsb"])){
                        if(move_uploaded_file($archivoTemporal, $uploadPath)){
                            //Se carga correctamente
                            $archivoCargado = true;
                        }
                    }
                }
                if($archivoCargado){
                    //se puede referenciar en la base de datos
                    if($bd_info_multiple){
                        $registro[$bd_info[$num]['file']] = $nombre;
                        if(isset($bd_info[$num]['description'])){
                            $registro[$bd_info[$num]['description']] = isset($descripciones_post[$num]) ? $descripciones_post[$num] : "";
                        }
                        if(isset($bd_info[$num]['optional_ids'])){
                            foreach ($bd_info[$num]['optional_ids'] as $column => $val) {
                                $registro[$column] = $val;
                            }
                        }
                        $arreglo_bd[] = $registro;
                    }else{
                        $registro[$bd_info['file']] = $nombre;
                        if(isset($bd_info['description'])){
                            $registro[$bd_info['description']] = isset($descripciones_post[$num]) ? $descripciones_post[$num] : "";
                        }
                        if(isset($bd_info['optional_ids'])){
                            foreach ($bd_info['optional_ids'] as $column => $val) {
                                $registro[$column] = $val;
                            }
                        }
                        $arreglo_bd[] = $registro;
                    }
                }
            }
        } // fin foreach
    }
    if($arreglo_bd){
        $tabla = $bd_info['table'];
        if($bd_info_multiple){
            $tabla = $bd_info[$num]['table'];
        }
        //hay algo
        $CI->load->model('model_general');
        $CI->model_general->insertMultiple($tabla, $arreglo_bd);
    }
}//agregar un retrun

function existenAdjuntos($FILES_name){
    //Retorna booleano, verifica que haya al menos un input de tipo File.
    if(isset($_FILES[$FILES_name])){
        $adjuntos = $_FILES[$FILES_name];
        $cantidad = count($adjuntos["name"]);
        return $cantidad > 1; //con el script de fileadder, si hay n adjuntos hay n+1 inputs. 
    }
    return false;
}

function existenTantosAdjuntos($FILES_name, $cantidad_deseada){ //existen una x cantidad de adjuntos
    //Devuelve true si hay $cantidad_deseada de archivos adjuntados
    if(existenAdjuntos($FILES_name)){
        $adjuntos = $_FILES[$FILES_name];
        $cantidad_presente = 0;
        foreach ($adjuntos['error'] as $error) {
            //$error == 4 => Archivo Vacio
            if($error != 4){
                $cantidad_presente++;
            }
        }
        return $cantidad_presente==$cantidad_deseada;
    }
    return false;
}
function existenMenorIgualAdjuntos($FILES_name, $cantidad_deseada){//existen una 1 < x < $cantidad_deseada de adjuntos
    //Devuelve true si hay $cantidad_deseada de archivos adjuntados
    if(existenAdjuntos($FILES_name)){
        $adjuntos = $_FILES[$FILES_name];
        $cantidad_presente = 0;
        foreach ($adjuntos['error'] as $error) {
            //$error == 4 => Archivo Vacio
            if($error != 4){
                $cantidad_presente++;
            }
        }
        if(1 <= $cantidad_presente){
            return $cantidad_presente<=$cantidad_deseada;
        }else{
        return false;
        }
    }
    return false;
}