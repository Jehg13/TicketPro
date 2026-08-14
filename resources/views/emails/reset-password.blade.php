<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Restablece tu contraseña | TicketPro</title>
</head>

<body style="margin:0; padding:0; background:#070b19; font-family:Arial, Helvetica, sans-serif; color:#ffffff;">

    <table width="100%" cellpadding="0" cellspacing="0" border="0"
        style="width:100%; background:#070b19; padding:45px 15px;">

        <tr>
            <td align="center">

                <!-- CONTENEDOR PRINCIPAL -->
                <table width="600" cellpadding="0" cellspacing="0" border="0"
                    style="
                        width:100%;
                        max-width:600px;
                        background:#0f1535;
                        border:1px solid #1e295d;
                        border-radius:18px;
                        overflow:hidden;
                    ">

                    <!-- HEADER -->
                    <tr>
                        <td
                            style="
                                padding:32px 35px;
                                background:#0b102b;
                                border-bottom:1px solid #1e295d;
                            ">

                            <table width="100%" cellpadding="0" cellspacing="0" border="0">
                                <tr>

                                    <td align="left">

                                        <div style="
                                            display:inline-block;
                                            font-size:21px;
                                            font-weight:800;
                                            letter-spacing:2px;
                                            color:#ffffff;
                                        ">
                                            TICKET<span style="color:#60a5fa;">PRO</span>
                                        </div>

                                        <div style="
                                            margin-top:5px;
                                            color:#64748b;
                                            font-size:11px;
                                            letter-spacing:1px;
                                            text-transform:uppercase;
                                        ">
                                            Sistema de soporte
                                        </div>

                                    </td>

                                    <td align="right">

                                        <div style="
                                            display:inline-block;
                                            padding:8px 12px;
                                            border-radius:8px;
                                            background:#111a3d;
                                            border:1px solid #263568;
                                            color:#60a5fa;
                                            font-size:11px;
                                            font-weight:bold;
                                        ">
                                            SEGURIDAD
                                        </div>

                                    </td>

                                </tr>
                            </table>

                        </td>
                    </tr>


                    <!-- CONTENIDO -->
                    <tr>
                        <td style="padding:42px 40px;">

                            <!-- ICONO -->
                            <table width="100%" cellpadding="0" cellspacing="0" border="0">
                                <tr>
                                    <td>

                                        <div style="
                                            width:52px;
                                            height:52px;
                                            line-height:52px;
                                            text-align:center;
                                            border-radius:14px;
                                            background:#111a3d;
                                            border:1px solid #263568;
                                            color:#60a5fa;
                                            font-size:24px;
                                        ">
                                            🔐
                                        </div>

                                    </td>
                                </tr>
                            </table>


                            <!-- TITULO -->
                            <h1 style="
                                margin:25px 0 10px;
                                color:#ffffff;
                                font-size:28px;
                                line-height:1.25;
                                font-weight:800;
                            ">
                                Restablece tu contraseña
                            </h1>

                            <p style="
                                margin:0 0 28px;
                                color:#64748b;
                                font-size:13px;
                                line-height:1.6;
                            ">
                                Recuperación segura de acceso a tu cuenta de TicketPro
                            </p>


                            <!-- SALUDO -->
                            <p style="
                                margin:0 0 15px;
                                color:#cbd5e1;
                                font-size:15px;
                                line-height:1.7;
                            ">
                                Hola
                                <strong style="color:#ffffff;">
                                    {{ $user->name ?? 'usuario' }}
                                </strong>,
                            </p>

                            <p style="
                                margin:0 0 16px;
                                color:#94a3b8;
                                font-size:14px;
                                line-height:1.8;
                            ">
                                Recibimos una solicitud para restablecer la contraseña
                                asociada a tu cuenta de TicketPro.
                            </p>

                            <p style="
                                margin:0 0 30px;
                                color:#94a3b8;
                                font-size:14px;
                                line-height:1.8;
                            ">
                                Si realizaste esta solicitud, utiliza el botón de abajo
                                para crear una nueva contraseña y recuperar el acceso
                                a tu cuenta.
                            </p>


                            <!-- BOTON -->
                            <table width="100%" cellpadding="0" cellspacing="0" border="0">
                                <tr>
                                    <td align="center">

                                        <a href="{{ $url }}"
                                            style="
                                                display:inline-block;
                                                padding:15px 30px;
                                                background:#2563eb;
                                                color:#ffffff;
                                                text-decoration:none;
                                                border-radius:9px;
                                                font-size:14px;
                                                font-weight:bold;
                                                box-shadow:0 8px 20px rgba(37,99,235,.25);
                                            ">
                                            Restablecer mi contraseña
                                        </a>

                                    </td>
                                </tr>
                            </table>


                            <!-- SEPARADOR -->
                            <div style="
                                height:1px;
                                background:#1e295d;
                                margin:35px 0;
                            "></div>


                            <!-- INFORMACION DE SEGURIDAD -->
                            <table width="100%" cellpadding="0" cellspacing="0" border="0"
                                style="
                                    background:#0b102b;
                                    border:1px solid #1e295d;
                                    border-radius:12px;
                                ">

                                <tr>
                                    <td style="padding:20px;">

                                        <p style="
                                            margin:0 0 8px;
                                            color:#60a5fa;
                                            font-size:13px;
                                            font-weight:bold;
                                        ">
                                            🛡️ Información de seguridad
                                        </p>

                                        <p style="
                                            margin:0;
                                            color:#94a3b8;
                                            font-size:12px;
                                            line-height:1.7;
                                        ">
                                            Este enlace es temporal y solo debe utilizarse
                                            para cambiar la contraseña de tu cuenta.
                                            Si tú no realizaste esta solicitud, puedes
                                            ignorar este correo.
                                        </p>

                                    </td>
                                </tr>

                            </table>


                            <!-- LINK MANUAL -->
                            <p style="
                                margin:30px 0 8px;
                                color:#64748b;
                                font-size:11px;
                                line-height:1.5;
                            ">
                                ¿El botón no funciona?
                                Copia y pega este enlace en tu navegador:
                            </p>

                            <p style="
                                margin:0;
                                padding:12px;
                                background:#0b102b;
                                border:1px solid #1e295d;
                                border-radius:8px;
                                color:#60a5fa;
                                font-size:10px;
                                line-height:1.5;
                                word-break:break-all;
                            ">
                                {{ $url }}
                            </p>

                        </td>
                    </tr>


                    <!-- FOOTER -->
                    <tr>
                        <td
                            style="
                                padding:25px 35px;
                                background:#0b102b;
                                border-top:1px solid #1e295d;
                            ">

                            <table width="100%" cellpadding="0" cellspacing="0" border="0">

                                <tr>

                                    <td align="left">

                                        <p style="
                                            margin:0 0 5px;
                                            color:#94a3b8;
                                            font-size:12px;
                                            font-weight:bold;
                                        ">
                                            TICKET<span style="color:#60a5fa;">PRO</span>
                                        </p>

                                        <p style="
                                            margin:0;
                                            color:#475569;
                                            font-size:10px;
                                        ">
                                            Sistema de soporte
                                        </p>

                                    </td>

                                    <td align="right">

                                        <p style="
                                            margin:0;
                                            color:#475569;
                                            font-size:10px;
                                        ">
                                            © {{ date('Y') }} TicketPro
                                        </p>

                                    </td>

                                </tr>

                            </table>

                        </td>
                    </tr>

                </table>


                <!-- TEXTO FUERA DE LA TARJETA -->
                <p style="
                    margin:18px 0 0;
                    color:#334155;
                    font-size:10px;
                    text-align:center;
                ">
                    Este correo fue generado automáticamente.
                    Por favor, no respondas a este mensaje.
                </p>

            </td>
        </tr>

    </table>

</body>

</html>
