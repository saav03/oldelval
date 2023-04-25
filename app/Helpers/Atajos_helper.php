<?php
function setValue($valor, $arreglo = NULL)
{
    //Util para alternar entre edit/view
    $final = "";
    if (!is_null($arreglo)) {
        if (isset($arreglo[$valor])) {
            $final = $arreglo[$valor];
        }
    }
    return $final;
}

/**
 * Se ve mucho más lindo si las col son de 6
 */
function campoFlotante($col, $negrita, $contenido, $type_inp)
{
    if ($contenido == null) {
        $contenido = "No se especificó.";
    }

    $html = '';
    $html .= '<div class="col-xs-12 col-md-' . $col . ' text-center">';
    $html .= '<div class="divBox">';
    $html .= '<div class="divBox_title">';
    $html .= '<p>' . $negrita . '</p>';
    $html .= '</div>';
    $html .= '<div class="divBox_contain">';
    $html .= '<input type="hidden" value="' . $contenido . '">';
    $html .= '<input type="' . $type_inp . '" class="form-control inp_estilos" value="' . $contenido . '" readonly>';
    $html .= '</div>';
    $html .= '</div>';
    $html .= '</div>';

    echo $html;
}

/**
 * 
 */
function generarIconUpload($adjunto)
{
    $extension = strtolower(pathinfo($adjunto, PATHINFO_EXTENSION));
    
    $ruta = '';
    $html = '';
    if ($extension == 'doc') {
        $ruta = base_url('assets/img/Word.svg'); 
    } else if ($extension == 'pdf') {
        $ruta = base_url('assets/img/PDF.svg'); 
    } else if ($extension == 'xls' || $extension == 'xlsx') {
        $ruta = base_url('assets/img/Excel.svg'); 
    }
    $html .= '<img class="img_clip" src="' . $ruta . '" style="margin-right: 20px;" alt="Clip cargar archivo">';
    $html .= '<p class="m-0">' . $adjunto . '</p>';
    return $html;
    /* <img class="img_clip" src="<?= base_url('assets/img/Word.svg') ?>" style="margin-right: 20px;" alt="Clip cargar archivo">
                            <p class="m-0">Nombre archivo.doc</p> */
}
