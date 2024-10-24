<!DOCTYPE html>
<html lang="es" xmlns="http://www.w3.org/1999/xhtml" xmlns:o="urn:schemas-microsoft-com:office:office">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <meta name="x-apple-disable-message-reformatting">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@100;400&display=swap" rel="stylesheet">
    <title></title>
    <!--[if mso]>
	<noscript>
		<xml>
			<o:OfficeDocumentSettings>
				<o:PixelsPerInch>96</o:PixelsPerInch>
			</o:OfficeDocumentSettings>
		</xml>
	</noscript>
	<![endif]-->
    <style>
        table,
        td,
        div,
        h1,
        p {
            font-family: 'Poppins' !important;
        }

    </style>
</head>

<body style="margin:0;padding:0;">
    <table role="presentation" style="width:100%;border-collapse:collapse;border:0;border-spacing:0;background:#ffffff;">
        <tr>
            <td align="center" style="padding:0;">
                <table role="presentation" style="width:602px;border-collapse:collapse;border-spacing:0;text-align:left;">
                    <tr>
                        <td align="center" style="padding:15px 0;background:#0A5879;">
                            <img src="<?= base_url('assets/img/oldelval.png') ?>" style="height:auto;display:block; width: 46%;" />
                        </td>
                    </tr>
                    <tr>
                        <td style="padding:20px 30px 10px 30px;">
                            <table role="presentation" style="width:100%;border-collapse:collapse;border:0;border-spacing:0;">
                                <tr>
                                    <td style="padding:0 0 36px 0;color:#153643;">
                                        <h1 style="font-size:24px;margin:5px 0; text-align: center; letter-spacing: 1.2px;">Inspecciones - Descargo</h1>
                                        <h1 style="font-size:18px;text-align: center; letter-spacing: 1.2px;">N°<?= $id_descargo ?></h1>
                                        <p style="margin:0 0 12px 0;font-size:15px;line-height:24px;">Estimado <b><?= $usuario_carga ?></b>, se ha generado un descargo hacia la observación N°<?= $id_hallazgo ?></p>
                                        <p style="margin:0 0 12px 0;font-size:15px;line-height:24px; text-align: center;"><b>Descripción del Descargo</b></p>
                                        <p style="margin:0 0 12px 0;font-size:15px;line-height:24px; text-align: center; padding: 7px; border-radius: 5px; border: 1px solid #f1f1f1;"><?= $motivo ?></p>
                                        <p style="margin:20px 0 12px 0;font-size:15px;line-height:24px;"><b>Respondió: </b><?= $usuario_rta ?></p>
                                        <p style="margin:0 0 12px 0;font-size:15px;line-height:24px;"><b>Fecha Descargo: </b><?= $fecha_motivo ?></p>
                                        <p style="margin:0 0 12px 0;font-size:15px;line-height:24px;"><b>Fecha Vencimiento: </b><?= $fecha_cierre ?></p>
                                        <p style="margin:30px 0 12px 0;font-size:15px;line-height:24px; text-align: center;"><em>No olvide revisar el descargo realizado para poder consensuar un cierre adecuado del hallazgo</em></p>
                                        <p style="margin:5px 0 12px 0;font-size:15px;line-height:24px;"><small>Por favor, no responda a este correo.</small></p>
                                    </td>
                                </tr>
                            </table>
                        </td>
                    </tr>
                    <tr>
                        <td style="text-align: center; padding: 0px 0 30px 0;">
                            <a href="<?= $url ?>" style="padding: 12px 30px; border-radius: 10px; background-color: #0A5879; color: white; text-decoration: none;">Ver Inspección</a>
                        </td>
                    </tr>
                    <tr>
                        <td style="text-align: center; padding: 10px 0 10px 0;">
                            <small style="color:#153643;font-size:smaller;"></small>
                        </td>
                    </tr>
                    <tr>
                        <td style="padding:30px;background:#0A5879;">
                            <table role="presentation" style="width:100%;border-collapse:collapse;border:0;border-spacing:0;font-size:9px;font-family:Arial,sans-serif;">
                                <tr>
                                    <td style="padding:0;width:50%;" align="left">
                                        <p style="margin:0;font-size:14px;line-height:16px;font-family:Arial,sans-serif;color:#ffffff;">
                                            &reg; Blister Technologies, Neuquén - Argentina, <script>document.write(new Date().getFullYear())</script>
                                        </p>
                                    </td>
                                </tr>
                            </table>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>
</body>

</html>