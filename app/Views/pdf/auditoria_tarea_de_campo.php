<style>
    body {
        font-family: 'Quicksand', sans-serif !important;
    }

    table {
        border-spacing: 0;
    }

    .td_oldelval {
        width: 20%;
        color: #343434;
        border-right: 1px solid #494949;
        text-align: center;
        font-weight: bold;
    }

    .td_usuario_carga {
        width: 40%;
        color: #343434;
        background-color: #f1f1f1;
        border-right: 1px solid #494949;
        border-bottom: 1px solid #494949;
        text-align: center;
        font-size: 13px;
        font-weight: bold;
    }

    .td_fecha_carga {
        width: 40%;
        color: #343434;
        background-color: #f1f1f1;
        border-radius: 0 5px 0 0;
        border-bottom: 1px solid #494949;
        text-align: center;
        font-size: 13px;
        font-weight: bold;
    }

    .th_subtitle,
    .th_subtitle_dos {
        color: #343434;
        background-color: #f1f1f1;
        border-right: 1px solid #494949;
        border-bottom: 1px solid #494949;
        font-weight: bold;
        padding: 5px 0;
    }

    .th_subtitle_dos {
        border-top: 1px solid #494949;
    }

    .th_subtitle:first-child {
        border-radius: 5px 0 0 0;
    }

    .th_subtitle:last-child {
        border-right: none;
        border-radius: 0 5px 0 0;
    }

    .th_subtitle_dos:last-child {
        border-right: none;
    }

    .td_content {
        border-right: 1px solid #494949;
    }

    .td_content_dos {
        border-right: 1px solid #494949;
    }


    .td_content:last-child,
    .td_content_dos:last-child {
        border-right: none;
    }

    .checkmark {
        font-family: DejaVu Sans;
        /* Necesario para mostrar símbolos especiales */
    }

    /*  */
    @page {
        @top-left {
            content: url("<!?= base_url('assets/img/favicon.png') ?>");
        }
    }

    @page {
        /* Aumenta el margen inferior para dejar espacio para el pie de página */
        margin-top: 40px;
    }

    .footer {
        position: fixed;
        font-size: 14px;
        bottom: 0;
        left: 0;
        right: 0;
        height: 30px;
        text-align: center;
        line-height: 50px;
        color: #000;
        font-style: italic;
        color: gray;
    }

    .pagenum:before {
        content: counter(page);
    }
</style>

<html>

<head>
    <meta charset="gb18030">
    <title>Oldelval - Inspección de Obra</title>
    <link href="<?= base_url() ?>/assets/fontawesome/css/fontawesome.css" rel="stylesheet">
    <link href="<?= base_url() ?>/assets/fontawesome/css/solid.css" rel="stylesheet">
    <!-- Our project just needs Font Awesome Solid + Brands -->
    <script defer src="<?= base_url() ?>/assets/fontawesome/js/solid.js"></script>
    <script defer src="<?= base_url() ?>/assets/fontawesome/js/fontawesome.js"></script>
</head>

<body>
    <div class="footer">
        Oldelval | Inspección de Obra N°<?= $auditoria['id_auditoria']; ?> <span class="pagenum"></span>
    </div>
    <!-- Encabezado -->
    <table width='100%' style="border: 1px solid #494949; border-radius: 5px;">
        <th style="width: 20%; border-right: 1px solid #494949;">
            <img src="<?= base_url('assets/img/logo.png') ?>" style="width: 30px; height: 30px;" alt="Logo Oldelval">
        </th>
        <th style="width: 80%; text-align: center; color: #494949; padding-top: 5px; letter-spacing: 1px;">
            Inspección de Obra - N°<?= $auditoria['id_auditoria']; ?>
        </th>
    </table>

    <table width='100%' style="border: 1px solid #494949; border-radius: 5px; margin-top: 3px;">
        <tbody>
            <tr>
                <td rowspan="2" class="td_oldelval">
                    OLDELVAL
                </td>
                <td class="td_usuario_carga">
                    Usuario Carga
                </td>
                <td class="td_fecha_carga">
                    Fecha Carga
                </td>
            </tr>
            <tr>
                <td style="width: 40%;color: #686868; text-align: center; font-size: 13px; border-right: 1px solid #494949;">
                    <?= $auditoria['usuario_carga'] ? $auditoria['usuario_carga'] : ''; ?>
                </td>
                <td style="width: 40%;color: #686868; text-align: center; font-size: 13px;">
                    <?= $auditoria['fecha_carga_format'] ? $auditoria['fecha_carga_format'] : ''; ?>
                </td>
            </tr>
        </tbody>
    </table>

    <!-- Datos Principales -->

    <table width="100%;" style="border: 1px solid #494949; border-radius: 5px; margin-top: 15px;">
        <th style="font-size: 12px; color: #64748B; padding: 4px 0; background-color: #f5f5f5; border-radius: 5px;">Datos Principales</th>
    </table>

    <table width="100%;" style="border: 1px solid #494949; border-radius: 5px; margin-top: 6px; font-size: 12px; text-align: center; color: #686868;">
        <thead>
            <tr>
                <th class="th_subtitle">Contratista</th>
                <th class="th_subtitle">Supervisor Responsable</th>
                <th class="th_subtitle">Cantidad del Personal</th>
                <th class="th_subtitle">N° Informe</th>
            </tr>
        </thead>

        <tbody style="border-bottom: 1px solid #494949;">
            <tr>
                <td class="td_content"><?= $auditoria['contratista'] ? $auditoria['contratista'] : ''; ?></td>
                <td class="td_content"><?= $auditoria['supervisor_responsable'] ? $auditoria['supervisor_responsable'] : ''; ?></td>
                <td class="td_content"><?= $auditoria['cant_personal'] ? $auditoria['cant_personal'] : ''; ?></td>
                <td class="td_content"><?= $auditoria['num_informe'] ? $auditoria['num_informe'] : '---'; ?></td>
            </tr>
        </tbody>

        <thead>
            <tr>
                <th class="th_subtitle_dos">Proyecto</th>
                <th class="th_subtitle_dos">Módulo</th>
                <th class="th_subtitle_dos">Estación de Bombeo</th>
                <th class="th_subtitle_dos">Sistema de Oleoducto</th>
            </tr>
        </thead>

        <tbody>
            <tr>
                <td class="td_content_dos"><?= $auditoria['proyecto'] ? $auditoria['proyecto'] : ''; ?></td>
                <td class="td_content_dos"><?= $auditoria['modulo'] ? $auditoria['modulo'] : 'No Aplica'; ?></td>
                <td class="td_content_dos"><?= $auditoria['estacion'] ? $auditoria['estacion'] : 'No Aplica'; ?></td>
                <td class="td_content_dos"><?= $auditoria['sistema'] ? $auditoria['sistema'] : 'No Aplica'; ?></td>
            </tr>
        </tbody>

    </table>

    <!-- Preguntas y Respuestas -->

    <table width="100%;" style="border: 1px solid #494949; border-radius: 5px; margin-top: 15px;">
        <th style="font-size: 12px; color: #64748B; padding: 4px 0; background-color: #f5f5f5; border-radius: 5px;">Respuestas y Comentarios</th>
    </table>

    <div style="border: 1px solid #494949; border-radius: 5px; margin-top: 6px;">
        <!-- Subtítulo Bloque -->
        <?php $i = 0;
        foreach ($auditoria['bloque'] as $bloque) : ?>
            <div style="background-color: #ebeef4; margin: 3px; border-radius: 5px; padding: 2px 0; color: black; font-size: 12px; text-align: center; font-weight: bold;">
                <?= $bloque['nombre']; ?>
            </div>
            <!-- Preguntas Bloque -->
            <table width="100%;" style="margin-top: 6px; text-align: center; font-size: 12px;">
                <tbody>
                    <?php foreach ($bloque['preguntas_rtas'] as $rtas) : $i++; ?>
                        <tr>
                            <td style="text-align: left; width: 82%;">
                                <div style="margin: 1px; border-radius: 5px; padding: 2px 0 2px 5px; font-size: 12px; color: #686868;">
                                    <small><b>( <?= $i; ?> )</b></small> <?= $rtas['respuesta'][0]['pregunta']; ?>
                                </div>
                            </td>
                            <td style="width:18%;">
                                <?php if ($rtas['respuesta'][0]['rta'] == -1) { ?>
                                    <div style="color: #ECB675; border-radius: 5px; color: white; font-weight: bold; font-size: 15px; margin: 0px 20px; padding: -5px 5px;">
                                        <i class="fa-solid fa-square-minus" style="color: #ECB675;"></i>
                                    </div>
                                <?php } else if ($rtas['respuesta'][0]['rta'] == 0) { ?>
                                    <div style="border-radius: 5px; color: white; font-weight: bold; font-size: 12px; margin: 0px 20px; padding: 0 10px;">
                                        <i class="fa-solid fa-circle-xmark" style="color: #C70039;"></i>
                                    </div>
                                <?php } else if ($rtas['respuesta'][0]['rta'] == 1) { ?>
                                    <div style="border-radius: 5px; color: white; font-weight: bold; font-size: 12px; margin: 0px 20px; padding: 0 10px;">
                                        <i class="fa-solid fa-circle-check" style="color: #62965E;"></i>
                                    </div>
                                <?php }  ?>
                            </td>
                        </tr>
                        <?php if ($rtas['respuesta'][0]['comentario'] != '') : ?>
                            <tr>
                                <td colspan="4" style="font-weight: bold; letter-spacing: .3px; color: #686868;">
                                    Comentario
                                </td>
                            </tr>
                            <tr>
                                <td colspan="4" style="padding-bottom: 10px; color: #686868;">
                                    <?= $rtas['respuesta'][0]['comentario']; ?>
                                </td>
                            </tr>
                        <?php endif; ?>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php endforeach; ?>
    </div>

    <div style="page-break-after: always;"></div>

    <!-- Observación -->
    <?php if (!is_null($hallazgo)) : ?>
        <table width="100%;" style="border: 1px solid #494949; border-radius: 5px; margin-top: 15px;">
            <th style="font-size: 12px; color: #64748B; padding: 4px 0; background-color: #f5f5f5; border-radius: 5px;">Observación</th>
        </table>


        <table width="100%;" style="color: #343434;border: 1px solid #494949; border-radius: 5px; margin-top: 15px; font-size: 12px; text-align: center; color: #686868; ">
            <thead style="border-bottom: 1px solid #494949;">
                <tr>
                    <th style="width: 50%; border-right: 1px solid #494949; background-color: #f1f1f1; border-radius: 5px 0 0 0; color: #343434;">
                        Hallazgo
                    </th>
                    <th style="width: 50%; background-color: #f1f1f1; border-radius: 0 5px 0 0; color: #343434;">
                        Plan de Acción
                    </th>
                </tr>
            </thead>

            <tbody>
                <tr>
                    <td style="border-right: 1px solid #494949; padding: 3px; text-align: left;">
                        <?= $hallazgo['hallazgo'] ? $hallazgo['hallazgo'] : ''; ?>
                    </td>
                    <td style="padding: 3px; text-align: left;">
                        <?= $hallazgo['plan_accion'] ? $hallazgo['plan_accion'] : ''; ?>
                    </td>
                </tr>
            </tbody>
        </table>

        <table width="100%;" style="border: 1px solid #494949; border-radius: 5px; margin-top: 15px; font-size: 12px; text-align: center; color: #686868; ">
            <thead style="border-bottom: 1px solid #494949;">
                <tr>
                    <th colspan="4" style="padding: 3px 0; background-color: #f1f1f1; border-radius: 5px 5px 0 0; color: #343434;">Significancia</th>
                </tr>
            </thead>

            <tbody>
                <tr>
                    <td
                        style="width: 20%; border-right: 1px solid #494949; padding: 3px 0; <?= $hallazgo['significancia'] == 1 ? 'background-color: #f1f1f1; color: #343434; border-top: 1px solid #494949;' : '' ?>">
                        Aceptable</td>
                    <td
                        style="width: 20%; border-right: 1px solid #494949; padding: 3px 0; <?= $hallazgo['significancia'] == 2 ? 'background-color: #ACFF8A; color: #343434; border-top: 1px solid #494949;' : '' ?>">
                        Moderado</td>
                    <td
                        style="width: 20%; border-right: 1px solid #494949; padding: 3px 0; <?= $hallazgo['significancia'] == 3 ? 'background-color: #FFD68A; color: #343434; border-top: 1px solid #494949;' : '' ?>">
                        Significativo</td>
                    <td style="width: 20%; <?= $hallazgo['significancia'] == 4 ? 'background-color: #FF978A; color: #343434; border-top: 1px solid #494949;' : '' ?>">Intolerable</td>
                </tr>
            </tbody>
        </table>

        <table width="100%;" style="border: 1px solid #494949; border-radius: 5px; margin-top: 15px; font-size: 12px; text-align: center; color: #686868;">
            <thead style="border-bottom: 1px solid #494949;">
                <tr>
                    <th colspan="2" style="padding: 3px 0; background-color: #f1f1f1; border-radius: 5px 5px 0 0; color: #343434;">Efectos / Impactos</th>
                </tr>
            </thead>
            <tbody>
                <?php if (count($hallazgo['efectos']) > 0) : ?>
                    <?php foreach ($hallazgo['efectos'] as $efecto) : ?>
                        <tr>
                            <td style="width: 100%;"><span class="checkmark">&#10003;</span> <?= $efecto['nombre_efecto']; ?></td>
                        </tr>
                    <?php endforeach; ?>
                <?php else : ?>
                    <tr>
                        <td colspan="2" style="font-style: italic; color: #686868;">No se agregaron efectos</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>

        <table width="100%;" style="border: 1px solid #494949!important; border-radius: 5px; margin-top: 15px; font-size: 12px; text-align: center; color: #686868; ">
            <thead style="border-bottom: 1px solid #494949;">
                <tr>
                    <th style="width: 50%; padding: 3px 0; border-right: 1px solid #494949; background-color: #f1f1f1; border-radius: 5px 0 0 0; color: #343434;">Contratista</th>
                    <th style="width: 50%; padding: 3px 0; background-color: #f1f1f1; border-radius: 0 5px 0 0; color: #343434;">Responsable</th>
                </tr>
            </thead>

            <tbody>
                <tr>
                    <td style="border-right: 1px solid #494949; border-bottom: 1px solid #494949;"><?= $hallazgo['contratista'] ? $hallazgo['contratista'] : ''; ?></td>
                    <td style="border-bottom: 1px solid #494949;"><?= $hallazgo['responsable'] ? $hallazgo['responsable'] : ''; ?></td>
                </tr>
            </tbody>

            <thead style="border-top: 1px solid #494949; border-bottom: 1px solid #494949;">
                <tr>
                    <th style="width: 50%; padding: 3px 0; background-color: #f1f1f1;border-right: 1px solid #494949; color: #343434;">Segundo Responsable</th>
                    <th style="width: 50%; padding: 3px 0; background-color: #f1f1f1; color: #343434;">Fecha de Cierre</th>
                </tr>
            </thead>

            <tbody>
                <tr>
                    <td><?= $hallazgo['relevo'] ? $hallazgo['relevo'] : ''; ?></td>
                    <td><?= $hallazgo['fecha_cierre_format'] ? $hallazgo['fecha_cierre_format'] : ''; ?></td>
                </tr>
            </tbody>
        </table>


        <table width="100%;" style="border: 1px solid #494949; border-radius: 5px; margin-top: 15px; font-size: 12px; text-align: center; color: #686868; ">
            <thead>
                <tr>
                    <th colspan="3" style="padding: 3px 0; border-bottom: 1px solid #494949; background-color: #f1f1f1; border-radius: 5px 5px 0 0; color: #343434;">Adjuntos</th>
                </tr>
            </thead>
            <tbody style="padding: 10px;">
                <?php if (count($hallazgo['adjuntos']) > 0) : ?>
                    <?php foreach ($hallazgo['adjuntos'] as $adj) : ?>
                        <tr>
                            <td style="padding: 10px; text-align: left; width: 25%;">
                                <img style="width: 150px; height: 100px; border-radius: 10px;" src="<?= base_url('uploads/auditorias/hallazgos/') . '/' . $adj['adjunto'] ?>" alt="">
                            </td>
                            <td style="text-align: left; width: 75%;">
                                " <?= $adj['desc_adjunto']; ?> "
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php else : ?>
                    <tr>
                        <td colspan="3" style="font-style: italic;">No se agregaron adjuntos</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    <?php else : ?>
        <table width="100%;" style="border: 1px solid #494949; border-radius: 5px; margin-top: 15px;">
            <thead>
                <th style="font-size: 12px; color: #64748B; padding: 4px 0; background-color: #f5f5f5; border-radius: 5px;">Observación</th>
            </thead>
            <tbody style="padding: 10px;">
                <tr>
                    <td style="font-size: 12px; color: #686868; text-align: center;"><em>No se ha realizado una observación</em></td>
                </tr>
            </tbody>
        </table>
    <?php endif; ?>

</body>

</html>