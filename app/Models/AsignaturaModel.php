<?php
// app/Models/AsignaturaModel.php
require_once 'Database.php';

class AsignaturaModel extends Database
{

  public function getAsignaturasByIdentificacion(string $identificacion)
  {
    $db = $this->getConnection();

    // 1. OBTENER EL ID DEL PROGRAMA DEL ESTUDIANTE A PARTIR DE LA CÉDULA
    // Necesitamos usar 4 tablas: users -> programas (por nombre) -> asignaturas
    $query = "SELECT DISTINCT
                    a.codigo AS codigo,
                    a.nombre AS nombre,
                    a.estado AS estado, 
                    -- Si tienes un campo de créditos en asignaturas, añádelo aquí
                    -- a.creditos AS creditos 
                    'N/D' AS creditos -- Usar N/D si no tienes la columna 'creditos'
                  FROM 
                    users u
                  JOIN 
                    programas p ON u.programa = p.programa -- UNIÓN 1: users.nombre_programa = programas.programa (Busca el ID)
                  JOIN 
                    asignaturas a ON p.id = a.programa_id -- UNIÓN 2: programas.id = asignaturas.programa_id (Encuentra las asignaturas)
                  WHERE 
                    u.numero_identificacion = :identificacion
                  ORDER BY 
                    a.nombre ASC";

    $stmt = $db->prepare($query);

    try {
      $stmt->execute([':identificacion' => $identificacion]);
      $rawAsignaturas = $stmt->fetchAll(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
      error_log("Error de SQL en AsignaturaModel: " . $e->getMessage());
      // Si hay un error de SQL (ej: columna no existe), devuelve error.
      return ['SQL_ERROR' => $e->getMessage()];
    }

    // 2. AGRUPAR LAS ASIGNATURAS POR NIVEL (N1 a N5, OTRAS)
    $groupedAsignaturas = [
      'N1' => [],
      'N2' => [],
      'N3' => [],
      'N4' => [],
      'N5' => [],
      'Otras' => []
    ];

    foreach ($rawAsignaturas as $asignatura) {
      $nombre = $asignatura['nombre'];
      $nivelEncontrado = false;

      // Búsqueda del patrón "-N[número]" en el nombre (ej: "Anatomía animal-PA-N1")
      if (preg_match('/-N(\d+)/', $nombre, $matches)) {
        $nivel = 'N' . $matches[1];

        // Solo considera N1 a N5
        if (in_array($nivel, array_keys($groupedAsignaturas))) {
          $groupedAsignaturas[$nivel][] = $asignatura;
          $nivelEncontrado = true;
        }
      }

      // Si no tiene el patrón o el nivel es mayor a N5/inválido, va a "Otras"
      if (!$nivelEncontrado) {
        $groupedAsignaturas['Prácticas y Vinculación'][] = $asignatura;
      }
    }

    // El controlador debe verificar si hay una clave 'SQL_ERROR' o si está vacío.
    return $groupedAsignaturas;
  }
}
