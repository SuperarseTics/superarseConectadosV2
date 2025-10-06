<?php
// app/Models/PaymentModel.php

// Asegúrate que 'Database.php' exista y tenga la clase Database
require_once 'Database.php';

// El modelo extiende la clase Database para acceder a getConnection()
class PaymentModel extends Database
{
    protected $table_name = "payments"; // Nombre de tu tabla

    /**
     * Registra una nueva transacción como PENDING antes de ir a Payphone.
     * @param int $userId El ID del usuario que inicia el pago.
     * @param string $clientTransactionId El ID único generado por el sistema.
     * @param float $amount La cantidad total en USD (ej: 50.50).
     * @param string $currency La moneda.
     * @param string $status El estado inicial.
     * @return int|bool El ID del nuevo registro o false si falla.
     */
    public function createPendingPayment(
        $userId,
        $clientTransactionId,
        $amount,
        $currency = 'USD',
        $status = 'PENDING'
    ) {
        $db = $this->getConnection();
        $query = "INSERT INTO " . $this->table_name . " 
                  (user_id, client_transaction_id, amount, currency, status) 
                  VALUES (?, ?, ?, ?, ?)";

        $stmt = $db->prepare($query);

        try {
            $stmt->execute([$userId, $clientTransactionId, $amount, $currency, $status]);

            // ✅ CORRECCIÓN: Usamos $db->lastInsertId() del objeto PDO, que es la forma estándar.
            return $db->lastInsertId();
        } catch (PDOException $e) {
            error_log("Error al crear pago pendiente: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Actualiza el estado de una transacción existente después de la respuesta de Payphone.
     * @param string $clientTransactionId El ID único para buscar el registro.
     * @param string|null $payphoneTransactionId El ID retornado por Payphone.
     * @param string $status El nuevo estado ('SUCCESS' o 'FAILURE').
     * @return bool True si se actualiza correctamente, false si hay error.
     */
    public function updatePaymentStatus(
        $clientTransactionId,
        $payphoneTransactionId,
        $status
    ) {
        $db = $this->getConnection();
        $query = "UPDATE " . $this->table_name . " 
                  SET payphone_transaction_id = ?, status = ? 
                  WHERE client_transaction_id = ? AND status = 'PENDING'"; // Solo actualizamos si estaba PENDING

        $stmt = $db->prepare($query);

        try {
            return $stmt->execute([$payphoneTransactionId, $status, $clientTransactionId]);
        } catch (PDOException $e) {
            error_log("Error al actualizar estado de pago: " . $e->getMessage());
            return false;
        }
    }
}
