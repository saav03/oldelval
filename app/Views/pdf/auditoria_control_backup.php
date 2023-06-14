<style>
    body {
        font-family: 'Quicksand', sans-serif !important;
    }

    table {
        border-spacing: 0;
    }

    .td_oldelval {
        width: 20%;
        color: #686868;
        border-right: 2px solid lightgray;
        text-align: center;
        font-weight: bold;
    }

    .td_usuario_carga {
        width: 40%;
        color: #686868;
        border-right: 2px solid lightgray;
        border-bottom: 1px solid lightgray;
        text-align: center;
        font-size: 13px;
        font-weight: bold;
    }

    .td_fecha_carga {
        width: 40%;
        color: #686868;
        border-bottom: 1px solid lightgray;
        text-align: center;
        font-size: 13px;
        font-weight: bold;
    }

    .th_subtitle {
        border-right: 2px solid lightgray;
        border-bottom: 1px solid lightgray;
        font-weight: bold;
        padding: 5px 0;
    }

    .th_subtitle:last-child {
        border-right: none;
    }

    .td_content {
        border-right: 2px solid lightgray;
    }

    .td_content:last-child {
        border-right: none;
    }

    .checkmark {
        font-family: DejaVu Sans;
        /* Necesario para mostrar símbolos especiales */
    }
</style>
<html>

<head>
    <meta charset="gb18030">
</head>

<body>

    <footer>
        <span class="pagenum"></span>
    </footer>

    <!-- Encabezado -->
    <table width='100%' style="border: 1px solid lightgray; border-radius: 5px;">
        <th style="width: 20%; border-right: 2px solid lightgray;">
            <img src="<?= base_url('assets/img/logo.png') ?>" style="width: 30px; height: 30px;" alt="Logo Oldelval">
        </th>
        <th style="width: 80%; text-align: center; color: #686868; padding-top: 5px; letter-spacing: 1px;">
            Auditoría Control - N°1
        </th>
    </table>

    <table width='100%' style="border: 1px solid lightgray; border-radius: 5px; margin-top: 3px;">
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
                <td style="width: 40%;color: #686868; text-align: center; font-size: 13px; border-right: 2px solid lightgray;">
                    Mirko Dinamarca
                </td>
                <td style="width: 40%;color: #686868; text-align: center; font-size: 13px;">
                    10/12/1999
                </td>
            </tr>
        </tbody>
    </table>

    <!-- Datos Principales -->

    <table width="100%;" style="border: 1px solid lightgray; border-radius: 5px; margin-top: 15px;">
        <th style="font-size: 12px; color: #64748B;">Datos Principales</th>
    </table>

    <table width="100%;" style="border: 1px solid lightgray; border-radius: 5px; margin-top: 2px; font-size: 12px; text-align: center; color: #686868;">
        <thead>
            <tr>
                <th class="th_subtitle">Contratista</th>
                <th class="th_subtitle">Supervisor Responsable</th>
                <th class="th_subtitle">Cantidad del Personal</th>
                <th class="th_subtitle">N° Informe</th>
            </tr>
        </thead>

        <tbody style="border-bottom: 1px solid lightgray;">
            <tr>
                <td class="td_content">Petromark</td>
                <td class="td_content">Mirko Dinamarca</td>
                <td class="td_content">10</td>
                <td class="td_content">#12345</td>
            </tr>
        </tbody>

        <thead>
            <tr>
                <th class="th_subtitle">Proyecto</th>
                <th class="th_subtitle">Módulo</th>
                <th class="th_subtitle">Estación de Bombeo</th>
                <th class="th_subtitle">Sistema de Oleoducto</th>
            </tr>
        </thead>

        <tbody>
            <tr>
                <td class="td_content">Duplicar</td>
                <td class="td_content">Módulo II</td>
                <td class="td_content">Estación Neuquén</td>
                <td class="td_content">No Aplica</td>
            </tr>
        </tbody>

    </table>

    <!-- Preguntas y Respuestas -->

    <table width="100%;" style="border: 1px solid lightgray; border-radius: 5px; margin-top: 15px;">
        <th style="font-size: 12px; color: #64748B;">Respuestas y Comentarios</th>
    </table>

    <table width="100%;" style="border: 1px solid lightgray; border-radius: 5px; margin-top: 2px; font-size: 12px; text-align: center; color: #686868; ">
        <thead>
            <tr>
                <th colspan="4">
                    <div style="background-color: #f5e1ce; margin: 3px; border-radius: 5px; padding: 2px 0; color: black;">
                        Documentación
                    </div>
                </th>
            </tr>
        </thead>

        <tbody>
            <tr>
                <td style="text-align: left;">
                    <div style="margin: 1px; border-radius: 5px; padding: 2px 0 2px 5px; color: black;">
                        ( 1 ) El Legajo Técnico y el registro de mediciones ambientales se encuentran actualizados y accesibles?
                    </div>
                </td>
                <td>
                    <div style="background-color: #8BDA92; padding: 0px 10px; border-radius: 5px; color: white; font-weight: bold;">
                        Bien
                    </div>
                </td>
            </tr>
            <tr>
                <td colspan="4" style="font-weight: bold; letter-spacing: .3px;">
                    Comentario
                </td>
            </tr>
            <tr>
                <td colspan="4" style="padding-bottom: 10px;">
                    Lorem ipsum dolor sit amet consectetur adipisicing elit. Consequuntur, nam?
                </td>
            </tr>
            <tr>
                <td style="text-align: left;">
                    <div style="margin: 1px; border-radius: 5px; padding: 2px 0 2px 5px; color: black;">
                        ( 2 ) El Personal conoce la aplicación del legajo técnico y mediciones ambientales.
                    </div>
                </td>
                <td>
                    <div style="background-color: #F0D282; padding: 0px 10px; border-radius: 5px; color: white; font-weight: bold;">
                        N/A
                    </div>
                </td>
            </tr>
            <tr>
                <td style="text-align: left;">
                    <div style="margin: 1px; border-radius: 5px; padding: 2px 0 2px 5px; color: black;">
                        ( 3 ) Todo el personal tiene acceso a Cartelera. ¿Conocen los procedimientos e instructivos aplicables a la EB?
                    </div>
                </td>
                <td>
                    <div style="background-color: #D15C3A; padding: 0px 10px; border-radius: 5px; color: white; font-weight: bold;">
                        Mal
                    </div>
                </td>
            </tr>
        </tbody>

        <thead>
            <tr>
                <th colspan="4">
                    <div style="background-color: #f5e1ce; margin: 3px; border-radius: 5px; padding: 2px 0; color: black;">
                        Elementos de Protección Personal
                    </div>
                </th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td style="text-align: left;">
                    <div style="margin: 1px; border-radius: 5px; padding: 2px 0 2px 5px; color: black;">
                        ( 4 ) ¿Se registra su entrega/recepción?
                    </div>
                </td>
                <td>
                    <div style="background-color: #8BDA92; padding: 0px 10px; border-radius: 5px; color: white; font-weight: bold;">
                        Bien
                    </div>
                </td>
            </tr>
        </tbody>
    </table>

    <div style="page-break-after: always;"></div>

    <!-- Observación -->
    <table width="100%;" style="border: 1px solid lightgray; border-radius: 5px; margin-top: 15px;">
        <th style="font-size: 12px; color: #64748B;">Observación</th>
    </table>

    <table width="100%;" style="border: 1px solid lightgray; border-radius: 5px; margin-top: 4px; font-size: 12px; text-align: center; color: #686868; ">
        <thead style="border-bottom: 1px solid lightgray;">
            <tr>
                <th style="width: 50%; border-right: 2px solid lightgray;">
                    Hallazgo
                </th>
                <th style="width: 50%;">
                    Plan de Acción
                </th>
            </tr>
        </thead>

        <tbody>
            <tr>
                <td style="border-right: 2px solid lightgray; padding: 3px; text-align: left;">
                    Lorem ipsum dolor sit amet consectetur adipisicing elit.
                    Deserunt ullam dolore quae rem eum error fugit dolorem rerum repellat maxime necessitatibus animi fugiat a,
                    aperiam numquam quibusdam ipsum placeat velit.
                </td>
                <td style="padding: 3px; text-align: left;">
                    Lorem ipsum dolor sit amet consectetur adipisicing elit.
                    Deserunt ullam dolore quae rem eum error fugit dolorem rerum repellat maxime necessitatibus animi fugiat a,
                    aperiam numquam quibusdam ipsum placeat velit.
                </td>
            </tr>
        </tbody>
    </table>

    <table width="100%;" style="border: 1px solid lightgray; border-radius: 5px; margin-top: 4px; font-size: 12px; text-align: center; color: #686868; ">
        <thead style="border-bottom: 1px solid lightgray;">
            <tr>
                <th colspan="4" style="padding: 3px 0;">Significancia</th>
            </tr>
        </thead>

        <tbody>
            <tr>
                <td style="width: 20%; border-right: 2px solid lightgray; padding: 3px 0;">Aceptable</td>
                <td style="width: 20%; border-right: 2px solid lightgray; padding: 3px 0;">Moderado</td>
                <td style="width: 20%; border-right: 2px solid lightgray; padding: 3px 0;">Significativo</td>
                <td style="width: 20%;">Intolerable</td>
            </tr>
        </tbody>
    </table>

    <table width="100%;" style="border: 1px solid lightgray; border-radius: 5px; margin-top: 4px; font-size: 12px; text-align: center; color: #686868; ">
        <thead style="border-bottom: 1px solid lightgray;">
            <tr>
                <th colspan="2" style="padding: 3px 0;">Efectos / Impactos</th>
            </tr>
        </thead>

        <tbody>
            <tr>
                <td style="width: 30%;"><span class="checkmark">&#10003;</span>Atrapamientos</td>
                <td style="width: 30%;"><span class="checkmark">&#10003;</span>Aplastamientos o aprisionamientos</td>
            </tr>
            <tr>
                <td colspan="2" style="width: 30%;"><span class="checkmark">&#10003;</span>Aplastamientos o aprisionamientos</td>
            </tr>
        </tbody>
    </table>

    <table width="100%;" style="border: 1px solid lightgray; border-radius: 5px; margin-top: 4px; font-size: 12px; text-align: center; color: #686868; ">
        <thead style="border-bottom: 1px solid lightgray;">
            <tr>
                <th style="width: 50%; padding: 3px 0; border-right: 2px solid lightgray;">Contratista</th>
                <th style="width: 50%; padding: 3px 0; ">Responsable</th>
            </tr>
        </thead>

        <tbody>
            <tr>
                <td>Petromark</td>
                <td>Tomas Bascal</td>
            </tr>
        </tbody>

        <thead style="border-top: 1px solid lightgray; border-bottom: 1px solid lightgray;">
            <tr>
                <th style="width: 50%; padding: 3px 0; border-right: 2px solid lightgray;">Segundo Responsable</th>
                <th style="width: 50%; padding: 3px 0;">Fecha de Cierre</th>
            </tr>
        </thead>

        <tbody>
            <tr>
                <td>Giuliano Ruiz</td>
                <td>10/12/1999</td>
            </tr>
        </tbody>
    </table>

    <table width="100%;" style="border: 1px solid lightgray; border-radius: 5px; margin-top: 4px; font-size: 12px; text-align: center; color: #686868; ">
        <thead >
            <tr>
                <th colspan="3" style="padding: 3px 0; border-bottom: 1px solid lightgray;">Adjuntos</th>
            </tr>
        </thead>

        <tbody>
            <tr>
                <td style="width: 30%; padding: 5px; border-right: 2px solid lightgray;">
                    <div>
                        <img style="width: 150px; height: 100px; border-radius: 10px;" src="<?= base_url('uploads/auditorias/hallazgos/adj_auditoria-0-2023-06-06-13-50-34.jpg') ?>" alt="">
                    </div>
                </td>
                <td style="width: 30%; padding: 5px; border-right: 2px solid lightgray;">
                    <div>
                        <img style="width: 150px; height: 100px; border-radius: 10px;" src="<?= base_url('uploads/auditorias/hallazgos/adj_auditoria-0-2023-06-06-13-50-34.jpg') ?>" alt="">
                    </div>
                </td>
                <td style="width: 30%;">
                    <div>
                        <img style="width: 150px; height: 100px; border-radius: 10px;" src="<?= base_url('uploads/auditorias/hallazgos/adj_auditoria-0-2023-06-06-13-50-34.jpg') ?>" alt="">
                    </div>
                </td>
            </tr>
            <tr>
                <td style="border-right: 2px solid lightgray;">
                    Lorem ipsum dolor sit amet consectetur adipisicing elit. Eveniet, accusantium autem nesciunt mollitia sapiente odit porro obcaecati ratione suscipit dolores?
                </td>
                <td style="border-right: 2px solid lightgray;">

                    Lorem ipsum dolor sit amet consectetur adipisicing elit. Eveniet, 
                </td>
                <td style="width: 30%;">
                    Eccusantium autem nesciunt mollitia sapiente odit porro obcaecati ratione suscipit dolores?
                </td>
            </tr>
        </tbody>
    </table>

</body>

</html>