<div class="flex flex-col items-center justify-center bg-white border border-gray-200 rounded-lg shadow-sm p-8 mt-8 mb-8 text-base" style="font-size: 80%;">
    <h2 class="text-xl font-bold text-center text-indigo-800 mb-2">Programa de trabajo</h2>
    <p class="text-center text-gray-600 mb-5">
        Para ingresar al programa de trabajo, haz clic en el siguiente enlace. Serás redirigido al PDF.
    </p>

    <?php
    // Mapeo de programas a archivos PDF
    $programaFileMap = [
        'ADMINISTRACION' => 'ISTS-GIDIVS-05-004-PAPR-PaoNOV25ABR26-ADM',
        'EDUCACIÓN BÁSICA' => 'ISTS-GIDIVS-05-004-PAPR-PaoNOV25ABR26-EDUB',
        'ENFERMERÍA' => 'ISTS-GIDIVS-05-004-PAPR-PaoNOV25ABR26-EV',
        'MARKETING' => 'ISTS-GIDIVS-05-004-PAPR-PaoNOV25ABR26-MKT',
        'PRODUCCIÓN AGROPECUARIA' => 'ISTS-GIDIVS-05-004-PAPR-PaoNOV25ABR26-PA',
        'SEGURIDAD Y PREVENCIÓN DE RIESGOS LABORALES' => 'ISTS-GIDIVS-05-004-PAPR-PaoNOV25ABR26-SPRL',
        'SEGURIDAD Y PREVENCION DE RIESGOS LABORALES' => 'ISTS-GIDIVS-05-004-PAPR-PaoNOV25ABR26-SPRL',
        'TOPOGRAFÍA' => 'ISTS-GIDIVS-05-004-PAPR-PaoNOV25ABR26-TOP'
    ];

    // Determinar el archivo basándose en el programa del estudiante
    $programaEstudiante = $data['infoPersonal']['programa'] ?? '';
    $fileName = null;

    // Buscar coincidencia exacta o parcial
    foreach ($programaFileMap as $programa => $file) {
        if (stripos($programaEstudiante, $programa) !== false || stripos($programa, $programaEstudiante) !== false) {
            $fileName = $file;
            break;
        }
    }

    // Si no hay mapeo, intentar usar el campo file de infoPrograma
    if (!$fileName && isset($data['infoPrograma']['file']) && !empty($data['infoPrograma']['file'])) {
        $fileName = $data['infoPrograma']['file'];
    }

    if ($fileName) {
        $filePath = __DIR__ . '/../../../public/assets/files/' . $fileName . '.pdf';

        if (file_exists($filePath)) {
            $fileUrl = '/superarseconectadosv2/public/assets/files/' . $fileName . '.pdf';
    ?>
            <a href="<?= $fileUrl ?>" target="_blank"
                class="inline-flex items-center gap-2 px-8 py-3 bg-pink-600 text-white font-semibold rounded-lg hover:bg-pink-700 transition text-base"
                style="font-size: 80%;">
                Ver PDF
                <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 ml-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M18 13v6a2 2 0 0 1-2 2h-8a2 2 0 0 1-2-2v-8a2 2 0 0 1 2-2h6M15 3h6m0 0v6m0-6L10 14" />
                </svg>
            </a>
    <?php
        } else {
            echo '<p class="text-red-600 text-center">El archivo no se encontró en la carpeta "assets/files".</p>';
            echo "<p class='text-xs text-gray-500'>Programa: " . htmlspecialchars($programaEstudiante) . "</p>";
            echo "<p class='text-xs text-gray-500'>Archivo buscado: {$fileName}.pdf</p>";
            echo "<p class='text-xs text-gray-500'>Ruta: {$filePath}</p>";
        }
    } else {
        echo '<p class="text-gray-500 text-center">No se pudo determinar el archivo para el programa: <strong>' . htmlspecialchars($programaEstudiante) . '</strong></p>';
        echo '<p class="text-xs text-gray-500 text-center">Por favor, contacta al administrador.</p>';
    }
    ?>
</div>