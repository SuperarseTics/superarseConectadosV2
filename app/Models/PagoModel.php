<?php
// app/Models/PagoModel.php
require_once 'Database.php';

class PagoModel extends Database
{

    public function getPagosByIdentificacion(string $identificacion)
    {
        $db = $this->getConnection();

        // Consulta simplificada para obtener solo los campos de resumen solicitados.
        $query = "SELECT 
                    abono_total, 
                    saldo_total, 
                    observacion
                  FROM 
                    pagos_estudiantes 
                  WHERE 
                    -- Usamos 'cedula' como columna de filtro, según tu CREATE TABLE
                    cedula = :identificacion 
                  LIMIT 1";

        $stmt = $db->prepare($query);
        try {
            $stmt->execute([':identificacion' => $identificacion]);
            // Devolvemos la fila única o null si no existe.
            $result = $stmt->fetch(PDO::FETCH_ASSOC);

            if (!$result) {
                return [
                    'abono_total' => null,
                    'saldo_total' => null,
                    'observacion' => null,
                ];
            }

            // Devolvemos los campos directamente, añadiendo un array 'cuotas' vacío
            return [
                'cuotas' => [], // Campo requerido por la estructura del EstudianteController
                'abono_total' => $result['abono_total'],
                'saldo_total' => $result['saldo_total'],
                'observacion' => $result['observacion'],
            ];
        } catch (PDOException $e) {
            error_log("Error de SQL en PagoModel: " . $e->getMessage());
            // En caso de error de BD, devolvemos una estructura segura
            return [
                'cuotas' => [],
                'abono_total' => 'Error de BD',
                'saldo_total' => 'Error de BD',
                'observacion' => 'Error de BD',
            ];
        }
    }
}
