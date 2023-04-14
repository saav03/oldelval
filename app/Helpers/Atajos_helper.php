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
    $html .= '<input type="' . $type_inp .'" class="form-control inp_estilos" value="' . $contenido . '" readonly>';
    $html .= '</div>';
    $html .= '</div>';
    $html .= '</div>';

    echo $html;
}

