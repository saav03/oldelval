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

/**
 * Modal helper Significancia
 */
function generarModalSignificancia()
{
    $hola = 'hola';
    $html = <<<HTML
    <div class="modal fade" id="modal_significancia" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header d-flex align-items-center justify-content-between">
                    <p class="p-0 m-0 fw-semibold">Descripción de Riesgos (Consecuencia)</p>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
            <div class="modal-body">
                    <table width="100%" style="border: 2px solid #dfdfdf;">
                        <thead>
                            <th style="width: 30%; border: 2px solid #dfdfdf; padding: 3px;">Consecuencia</th>
                            <th class="text-center" style="width: 70%; border: 2px solid #dfdfdf; padding: 3px;"">Descripción</th>
                        </thead>

                        <tbody>
                            <tr style=" border-bottom: 1px solid #f1f1f1;">
                                <td style="background-color: #f9f9f9; font-weight: bold; text-align: center; letter-spacing: 1px; border-radius: 0; border-right: 2px solid #dfdfdf;">Aceptable</td>
                                <td>
                                    <b>No afecta o afecta levemente. </b><br>
                                    Lesión menor resuelta a través de primeros auxilios (Ej: lesiones superficiales, cortes leves, irritación ocular por polvo, magulladuras pequeñas). Sin perdidas o daños materiales
                                </td>
                            </tr>
                            <tr style="border-bottom: 1px solid #f1f1f1;">
                                <td style="background-color: #d9ffd9; font-weight: bold; text-align: center; letter-spacing: 1px; border-radius: 0; border-right: 2px solid #dfdfdf;">Moderado</td>
                                    <td>
                                        <b>Afecta con consecuencias que son reversibles.</b><br>
                                        Lesión con pérdida de días o restricción de tareas (Ej.: esguinces, fracturas planas, quemaduras menores) Pérdidas o daños materiales bajos
                                    </td>
                            </tr>
                            <tr style="border-bottom: 1px solid #f1f1f1;">
                                <td style="background-color: #fffcd9; font-weight: bold; text-align: center; letter-spacing: 1px; border-radius: 0; border-right: 2px solid #dfdfdf;">Significativo</td>
                                <td>
                                    <b>Afecta con consecuencias que no son reversibles o son reversibles con tratamientos medicos.</b><br>
                                    Lesión severa con incapacidad laboral permanente total/parcial o que requiere de tratamiento médico de complejidad (Ej.: enfermedades profesionales, quemaduras con tratamientos complejos, fracturas expuestas, amputaciones, etc.) Pérdidas o daños materiales de escala apreciable.
                                </td>
                            </tr>
                            <tr>
                                <td style="background-color: #f5b4b4; font-weight: bold; text-align: center; letter-spacing: 1px; border-radius: 0; border-right: 2px solid #dfdfdf;">Intolerable</td>
                                <td>
                                    <b>Afecta con consecuencias muy graves o que puede desencadenar en muertes.</b><br>
                                    Muerte de una o más personas, o de algún tercero. Perdida total o reconstrucción de Instalaciones.
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    HTML;
    echo $html;
}

/**
 * Modal helper Significancia
 */
function generarModalConsecuenciaAmbiental()
{
    $hola = 'hola';
    $html = <<<HTML
    <div class="modal fade" id="modal_consecuencia_ambiental" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header d-flex align-items-center justify-content-between">
                    <p class="p-0 m-0 fw-semibold">Descripción de Riesgos (Consecuencia)</p>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
            <div class="modal-body">
                    <table width="100%" style="border: 2px solid #dfdfdf;">
                        <thead>
                            <th style="width: 30%; border: 2px solid #dfdfdf; padding: 3px;">Consecuencia</th>
                            <th class="text-center" style="width: 70%; border: 2px solid #dfdfdf; padding: 3px;"">Descripción</th>
                        </thead>

                        <tbody>
                            <tr style=" border-bottom: 1px solid #f1f1f1;">
                                <td style="background-color: #f9f9f9; font-weight: bold; text-align: center; letter-spacing: 1px; border-radius: 0; border-right: 2px solid #dfdfdf;">Baja</td>
                                <td>
                                    <b>Menor magnitud. </b><br>
                                    La recuperación es inmediata tras el cese de la actividad y no precisa prácticas protectoras o correctoras.
                                </td>
                            </tr>
                            <tr style="border-bottom: 1px solid #f1f1f1;">
                                <td style="background-color: #d9ffd9; font-weight: bold; text-align: center; letter-spacing: 1px; border-radius: 0; border-right: 2px solid #dfdfdf;">Media</td>
                                    <td>
                                        <b>Moderada magnitud.</b><br>
                                        La recuperación no precisa medidas protectoras o correctoras intensivas y la consecución de las condiciones ambientales requiere un tiempo máximo de seis meses.
                                    </td>
                            </tr>
                            <tr style="border-bottom: 1px solid #f1f1f1;">
                                <td style="background-color: #fffcd9; font-weight: bold; text-align: center; letter-spacing: 1px; border-radius: 0; border-right: 2px solid #dfdfdf;">Alta</td>
                                <td>
                                    <b>Seria magnitud.</b><br>
                                    La recuperación de las condiciones del medio o del factor impactado exige la adecuación de medidas protectoras o correctoras intensivas y, aún con esas medidas, aquella recuperación requiere un tiempo dilatado.
                                </td>
                            </tr>
                            <tr>
                                <td style="background-color: #f5b4b4; font-weight: bold; text-align: center; letter-spacing: 1px; border-radius: 0; border-right: 2px solid #dfdfdf;">Muy Alta</td>
                                <td>
                                    <b>Se produce una afectación de gran magnitud</b><br>
                                    Altos costos y difíciles medidas para la recomposición del sitio, se produce afectación a recursos de muy alta magnitud, con altos costos. Requiere importantes tiempos de recomposición del sitio.
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    HTML;
    echo $html;
}

function all_months_indices()
{
    $meses = [
        1 => "Enero",
        2 => "Febrero",
        3 => "Marzo",
        4 => "Abril",
        5 => "Mayo",
        6 => "Junio",
        7 => "Julio",
        8 => "Agosto",
        9 => "Septiembre",
        10 => "Octubre",
        11 => "Noviembre",
        12 => "Diciembre"
    ];
    return $meses;
}

function all_months_name()
{
    $meses = [
        "Enero", "Febrero", "Marzo", "Abril", "Mayo", "Junio",
        "Julio", "Agosto", "Septiembre", "Octubre", "Noviembre", "Diciembre"
    ];
    return $meses;
}
