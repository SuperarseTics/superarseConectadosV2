<?php
// app/Models/PagoModel.php
require_once 'Database.php';

class PagoModel extends Database
{

    public function getPagosByIdentificacion(string $identificacion)
    {
        $db = $this->getConnection();
        $query = "SELECT abono_total, saldo_total, observacion FROM pagos_estudiantes WHERE cedula = :identificacion LIMIT 1";

        $stmt = $db->prepare($query);
        try {
            $stmt->execute([':identificacion' => $identificacion]);
            $result = $stmt->fetch(PDO::FETCH_ASSOC);

            if (!$result) {
                return [
                    'abono_total' => null,
                    'saldo_total' => null,
                    'observacion' => null,
                ];
            }
            return [
                'cuotas' => [],
                'abono_total' => $result['abono_total'],
                'saldo_total' => $result['saldo_total'],
                'observacion' => $result['observacion'],
            ];
        } catch (PDOException $e) {
            error_log("Error de SQL en PagoModel: " . $e->getMessage());
            return [
                'cuotas' => [],
                'abono_total' => 'Error de BD',
                'saldo_total' => 'Error de BD',
                'observacion' => 'Error de BD',
            ];
        }
    }
}
