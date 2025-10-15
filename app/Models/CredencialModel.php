<?php
// app/Models/CredencialModel.php
require_once 'Database.php';

class CredencialModel extends Database
{

  public function getCredencialesByUserId(int $userId)
  {
    $db = $this->getConnection();

    try {
      $userQuery = "SELECT usuario FROM users WHERE id = :userId LIMIT 1";
      $userStmt = $db->prepare($userQuery);
      $userStmt->execute([':userId' => $userId]);
      $userInfo = $userStmt->fetch(PDO::FETCH_ASSOC);

      if (!$userInfo) {
        return [];
      }
      $plataformaUsuario = $userInfo['usuario'];
      $plataformaQuery = "SELECT 
                                plataforma, 
                                url AS link_acceso,
                                password_quemada AS clave_acceso 
                              FROM 
                                credenciales_plataforma 
                              ORDER BY 
                                plataforma ASC";

      $plataformaStmt = $db->prepare($plataformaQuery);
      $plataformaStmt->execute();
      $plataformas = $plataformaStmt->fetchAll(PDO::FETCH_ASSOC);
      $credencialesFinal = [];
      foreach ($plataformas as $p) {
        $credencialesFinal[] = [
          'plataforma' => $p['plataforma'],
          'usuario_acceso' => $plataformaUsuario,
          'clave_acceso' => $p['clave_acceso'],
          'link_acceso' => $p['link_acceso']
        ];
      }

      return $credencialesFinal;
    } catch (PDOException $e) {
      error_log("Error de SQL en CredencialModel: " . $e->getMessage());
      return [];
    }
  }
}