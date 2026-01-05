<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <title>Registro de Práctica Pre-Profesional (Fase 1)</title>

    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 25px;
            font-size: 10pt;
            color: #333;
        }

        /* Estilos del Encabezado */
        .header-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 25px;
        }

        .header-table td {
            padding: 0;
            vertical-align: top;
        }

        .header-logo {
            width: 30%;
            text-align: left;
        }

        .header-logo img {
            max-width: 150px;
            height: auto;
            margin-top: 10px;
        }

        /* Ajuste de tamaño */

        .header-title-container {
            width: 70%;
            text-align: right;
        }

        .title {
            color: #5B21B6;
            font-size: 14pt;
            margin: 0 0 5px 0;
            font-weight: bold;
        }

        .subtitle {
            font-size: 18pt;
            margin: 0;
            padding: 0;
            line-height: 1.2;
            font-weight: 900;
        }

        .line-divider {
            border-bottom: 3px solid #5B21B6;
            width: 100%;
            margin: 5px 0 10px 0;
            display: block;
        }

        .header-info {
            font-size: 10pt;
            font-weight: bold;
            text-align: right;
            margin-top: 5px;
        }

        .header-info span {
            font-weight: normal;
            margin-left: 10px;
        }

        /* Estilos de Contenido General */
        .section {
            margin-bottom: 20px;
            border: 1px solid #ddd;
            padding: 15px;
            border-radius: 4px;
            background-color: #f9f9f9;
        }

        .section-title {
            color: #5B21B6;
            font-size: 13pt;
            margin-top: 0;
            border-bottom: 1px solid #ddd;
            padding-bottom: 5px;
            margin-bottom: 10px;
            font-weight: bold;
        }

        .data-row {
            margin-bottom: 5px;
            display: block;
            overflow: auto;
            line-height: 1.4;
        }

        .data-label {
            font-weight: bold;
            width: 160px;
            display: inline-block;
            float: left;
            color: #444;
        }

        .data-value {
            display: block;
            overflow: hidden;
        }

        /* Estilos de Firmas */
        .signature-area {
            margin-top: 60px;
            text-align: center;
        }

        .signature-table {
            width: 90%;
            margin: 0 auto;
            border: none;
        }

        .signature-table td {
            width: 33%;
            text-align: center;
            border-top: 1px solid #000;
            padding-top: 15px;
            /* Espacio para la firma */
            font-size: 9pt;
        }

        .clear {
            clear: both;
        }
    </style>
</head>

<body>

    <table class="header-table">
        <tr>
            <td class="header-title-container">
                <div style="text-align: center;">
                    <p class="title">SUPERARSE TECNOLÓGICO</p>
                    <span class="line-divider"></span>
                    <h1 class="subtitle">Registro de Práctica Pre-Profesional (Fase 1)</h1>
                </div>
                <div class="header-info">
                    Nro. Práctica: <span style="color: #5B21B6;"><?php echo htmlspecialchars($infoPractica['id_practica'] ?? 'N/D'); ?></span>
                </div>
            </td>
        </tr>
    </table>

    <div class="section">
        <h3 class="section-title">1. Información del Estudiante</h3>

        <div class="data-row">
            <span class="data-label">Nombre Completo:</span>
            <span class="data-value"><?php echo htmlspecialchars($nombreCompleto ?? 'N/D'); ?></span>
        </div>
        <div class="data-row">
            <span class="data-label">Código Matrícula:</span>
            <span class="data-value"><?php echo htmlspecialchars($infoPersonal['codigo_matricula'] ?? 'N/D'); ?></span>
        </div>
        <div class="data-row">
            <span class="data-label">Identificación (Cédula):</span>
            <span class="data-value"><?php echo htmlspecialchars($infoPersonal['numero_identificacion'] ?? 'N/D'); ?></span>
        </div>
        <div class="data-row">
            <span class="data-label">Programa/Carrera:</span>
            <span class="data-value"><?php echo htmlspecialchars($infoPersonal['programa'] ?? 'N/D'); ?></span>
        </div>
        <div class="data-row">
            <span class="data-label">Nivel:</span>
            <span class="data-value"><?php echo htmlspecialchars($infoPersonal['nivel'] ?? 'N/D'); ?></span>
        </div>
        <div class="data-row">
            <span class="data-label">Nivel:</span>
            <span class="data-value"><?php echo htmlspecialchars($infoPersonal['periodo'] ?? 'N/D'); ?></span>
        </div>
        <div class="clear"></div>
    </div>

    <div class="section">
        <h3 class="section-title">2. Detalles de la Práctica y Estado</h3>

        <div class="data-row">
            <span class="data-label">Modalidad:</span>
            <span class="data-value"><?php echo htmlspecialchars($infoPractica['modalidad'] ?? 'N/D'); ?></span>
        </div>
        <div class="data-row">
            <span class="data-label">Afiliación IESS:</span>
            <span class="data-value"><?php echo htmlspecialchars(($infoPractica['afiliacion_iess'] == 1 ? 'Sí' : 'No') ?? 'N/D'); ?></span>
        </div>
        <div class="clear"></div>
    </div>

    <div class="section">
        <h3 class="section-title">3. Información de la Entidad/Empresa</h3>

        <div class="data-row">
            <span class="data-label">Razón Social:</span>
            <span class="data-value"><?php echo htmlspecialchars($infoPractica['razon_social'] ?? 'N/D'); ?></span>
        </div>
        <div class="data-row">
            <span class="data-label">RUC:</span>
            <span class="data-value"><?php echo htmlspecialchars($infoPractica['ruc'] ?? 'N/D'); ?></span>
        </div>
        <div class="data-row">
            <span class="data-label">Dirección:</span>
            <span class="data-value"><?php echo htmlspecialchars($infoPractica['direccion'] ?? 'N/D'); ?></span>
        </div>

        <br>
        <h4 style="font-size: 11pt; color: #5B21B6; margin-top: 0; border-bottom: 1px dotted #ccc; padding-bottom: 3px;">Contacto Empresarial</h4>
        <div class="data-row">
            <span class="data-label">Persona de Contacto:</span>
            <span class="data-value"><?php echo htmlspecialchars($infoPractica['persona_contacto'] ?? 'N/D'); ?></span>
        </div>
        <div class="data-row">
            <span class="data-label">Email:</span>
            <span class="data-value"><?php echo htmlspecialchars($infoPractica['email_contacto'] ?? 'N/D'); ?></span>
        </div>
        <div class="clear"></div>
    </div>

    <div class="section">
        <h3 class="section-title">4. Tutor Empresarial Asignado</h3>
        <?php
        // Asumimos que tutoresEmpresariales es un array que contiene al menos un elemento
        $tutorEmp = $tutoresEmpresariales[0] ?? null;
        ?>

        <?php if ($tutorEmp && !empty($tutorEmp['nombre_completo'])): ?>
            <div class="data-row">
                <span class="data-label">Nombre Completo:</span>
                <span class="data-value"><?php echo htmlspecialchars($tutorEmp['nombre_completo'] ?? 'N/D'); ?></span>
            </div>
            <div class="data-row">
                <span class="data-label">Cédula:</span>
                <span class="data-value"><?php echo htmlspecialchars($tutorEmp['cedula'] ?? 'N/D'); ?></span>
            </div>
            <div class="data-row">
                <span class="data-label">Función/Cargo:</span>
                <span class="data-value"><?php echo htmlspecialchars($tutorEmp['funcion'] ?? 'N/D'); ?></span>
            </div>
            <div class="data-row">
                <span class="data-label">Departamento:</span>
                <span class="data-value"><?php echo htmlspecialchars($tutorEmp['departamento'] ?? 'N/D'); ?></span>
            </div>
            <div class="data-row">
                <span class="data-label">Email:</span>
                <span class="data-value"><?php echo htmlspecialchars($tutorEmp['email'] ?? 'N/D'); ?></span>
            </div>
            <div class="data-row">
                <span class="data-label">Teléfono:</span>
                <span class="data-value"><?php echo htmlspecialchars($tutorEmp['telefono'] ?? 'N/D'); ?></span>
            </div>
        <?php else: ?>
            <p>No se ha asignado un Tutor Empresarial para esta práctica.</p>
        <?php endif; ?>
        <div class="clear"></div>
    </div>

    <div class="section">
        <h3 class="section-title">5. Tutor Académico (Docente) Asignado</h3>
        <?php
        // Asumimos que tutoresAcademicos es un array que contiene al menos un elemento
        $tutorAcademico = $tutoresAcademicos[0] ?? null;
        ?>

        <?php if ($tutorAcademico && !empty($tutorAcademico['nombre_completo'])): ?>
            <div class="data-row">
                <span class="data-label">Nombre Completo:</span>
                <span class="data-value"><?php echo htmlspecialchars($tutorAcademico['nombre_completo'] ?? 'N/D'); ?></span>
            </div>
            <div class="data-row">
                <span class="data-label">Email:</span>
                <span class="data-value"><?php echo htmlspecialchars($tutorAcademico['email'] ?? 'N/D'); ?></span>
            </div>
            <?php
            // Mostramos el teléfono solo si lo lograste incluir en la consulta del modelo
            if (!empty($tutorAcademico['telefono'])): ?>
                <div class="data-row">
                    <span class="data-label">Teléfono:</span>
                    <span class="data-value"><?php echo htmlspecialchars($tutorAcademico['telefono'] ?? 'N/D'); ?></span>
                </div>
            <?php endif; ?>
        <?php else: ?>
            <p>No se ha asignado un Tutor Académico para esta práctica.</p>
        <?php endif; ?>
        <div class="clear"></div>
    </div>

    <div class="signature-area">
        <p style="margin-bottom: 50px;">Registro Aprobado y Formalizado</p>
        
        <table class="signature-table">
            <tr>
                <td style="border-top: 1px solid #000; padding-top: 5px;">
                    Estudiante: <?php echo htmlspecialchars($nombreCompleto ?? 'N/D'); ?>
                </td>
            </tr>
        </table>
    </div>

</body>

</html>