<?php
require_once 'Database.php';
class PasantiaModel extends Database
{
    private $db;

    public function __construct()
    {
        $this->db = $this->getConnection();
        $this->db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    }
    public function getActiveDocentes()
    {
        $query = "SELECT id_docente, nombre_completo, estado FROM docentes WHERE estado = 'Activo' ORDER BY nombre_completo ASC";
        try {
            $stmt = $this->db->query($query);
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Error al obtener docentes: " . $e->getMessage());
            return [];
        }
    }
    public function getActivePracticaByUserId(int $userId)
    {
        $query = "SELECT
	pe.id_practica,
	pe.modalidad,
	pe.estado_fase_uno_completado,
    pe.afiliacion_iess,
	ent.ruc,
	pmod.id_practica_modalidad,
	ent.nombre_empresa,
	ent.razon_social,
	ent.persona_contacto,
	ent.telefono_contacto,
	ent.email_contacto,
	ent.direccion,
    ent.plazas_disponibles,
	tutemp.nombre_completo,
	tutemp.cedula,
	tutemp.funcion,
	tutemp.email,
	tutemp.telefono,
	tutemp.departamento
FROM
	practicas_estudiantes pe
INNER JOIN practica_modalidad pmod ON pmod.modalidad = pe.modalidad
INNER JOIN entidades ent ON ent.id_entidad = pe.entidad_id
LEFT JOIN tutores_empresariales tutemp ON tutemp.id_tutor_empresa = pe.tutor_empresarial_id
WHERE user_id = :userId LIMIT 1";
        $stmt = $this->db->prepare($query);
        $stmt->execute([':userId' => $userId]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
    private function getTutorAcademicoId(string $programa): ?int
    {
        $query = "SELECT docentes.id_docente FROM docentes INNER JOIN programas ON programas.id = docentes.id_programa WHERE programas.programa = :programa LIMIT 1";
        try {
            $stmt = $this->db->prepare($query);
            $stmt->execute([':programa' => $programa]);
            $tutor = $stmt->fetch(PDO::FETCH_ASSOC);
            return $tutor ? (int)$tutor['id_docente'] : null;
        } catch (PDOException $e) {
            error_log("Error al obtener el Id del Tutor: " . $e->getMessage());
            return null;
        }
    }
    public function savePasantiaPhaseOne(array $data)
    {
        if (empty($data['user_id']) || empty($data['programa']) || empty($data['modalidad']) || empty($data['entidad_ruc'])) {
            error_log("Datos incompletos para Fase 1.");
            return false;
        }

        $this->db->beginTransaction();

        try {
            $user_id = $data['user_id'];
            $programa = $data['programa'];

            $docente_asignado_id = $this->getTutorAcademicoId($programa);
            if (!$docente_asignado_id) {
                throw new Exception("No se pudo asignar un docente para la carrera: " . $programa);
            }
            $entidad_id = null;
            $stmt = $this->db->prepare("SELECT id_entidad FROM entidades WHERE ruc = :ruc");
            $stmt->execute([':ruc' => $data['entidad_ruc']]);
            $existing_entidad = $stmt->fetch();

            $entidad_data = [
                ':nombre_empresa' => $data['entidad_nombre_empresa'] ?? 'N/A',
                ':ruc' => $data['entidad_ruc'],
                ':razon_social' => $data['entidad_razon_social'] ?? null,
                ':persona_contacto' => $data['entidad_persona_contacto'] ?? null,
                ':telefono_contacto' => $data['entidad_telefono_contacto'] ?? null,
                ':email_contacto' => $data['entidad_email_contacto'] ?? null,
                ':direccion' => $data['entidad_direccion'] ?? null,
                ':id_programa' => $data['id_programa'] ?? null
            ];

            if ($existing_entidad) {
                $entidad_id = $existing_entidad['id_entidad'];
                $update_query = "UPDATE entidades 
                SET nombre_empresa = :nombre_empresa, razon_social = :razon_social, persona_contacto = :persona_contacto, 
                    telefono_contacto = :telefono_contacto, email_contacto = :email_contacto, direccion = :direccion 
                WHERE id_entidad = :id_entidad";

                $update_data = [
                    ':nombre_empresa' => $entidad_data[':nombre_empresa'],
                    ':razon_social' => $entidad_data[':razon_social'],
                    ':persona_contacto' => $entidad_data[':persona_contacto'],
                    ':telefono_contacto' => $entidad_data[':telefono_contacto'],
                    ':email_contacto' => $entidad_data[':email_contacto'],
                    ':id_entidad' => $entidad_id,
                    ':direccion' => $entidad_data[':direccion']
                ];

                $stmt = $this->db->prepare($update_query);
                $stmt->execute($update_data);
            } else {
                $insert_query = "INSERT INTO entidades 
                (nombre_empresa, ruc, razon_social, persona_contacto, telefono_contacto, email_contacto, direccion, id_programa)
                VALUES (:nombre_empresa, :ruc, :razon_social, :persona_contacto, :telefono_contacto, :email_contacto, :direccion, :id_programa)";
                $stmt = $this->db->prepare($insert_query);
                $stmt->execute($entidad_data);
                $entidad_id = $this->db->lastInsertId();
            }

            $tutor_emp_id = null;

            $tutor_data = [
                ':cedula' => trim($data['tutor_emp_cedula'] ?? ''),
                ':nombre_completo' => trim($data['tutor_emp_nombre_completo'] ?? ''),
                ':funcion' => trim($data['tutor_emp_funcion'] ?? ''),
                ':telefono' => trim($data['tutor_emp_telefono'] ?? ''),
                ':email' => trim($data['tutor_emp_email'] ?? ''),
                ':departamento' => trim($data['tutor_emp_departamento'] ?? ''),
            ];

            $allEmpty = empty($tutor_data[':cedula'])
                && empty($tutor_data[':nombre_completo'])
                && empty($tutor_data[':funcion'])
                && empty($tutor_data[':telefono'])
                && empty($tutor_data[':email'])
                && empty($tutor_data[':departamento']);

            if (!$allEmpty) {
                $stmt = $this->db->prepare("SELECT id_tutor_empresa FROM tutores_empresariales WHERE cedula = :cedula");
                $stmt->execute([':cedula' => $tutor_data[':cedula']]);
                $existing_tutor = $stmt->fetch();

                if ($existing_tutor) {
                    $tutor_emp_id = $existing_tutor['id_tutor_empresa'];
                    $update_query = "UPDATE tutores_empresariales 
                    SET nombre_completo = :nombre_completo, funcion = :funcion, telefono = :telefono, 
                        email = :email, departamento = :departamento 
                    WHERE id_tutor_empresa = :id_tutor_empresa";

                    $update_data = [
                        ':nombre_completo' => $tutor_data[':nombre_completo'] ?: 'N/A',
                        ':funcion' => $tutor_data[':funcion'] ?: null,
                        ':telefono' => $tutor_data[':telefono'] ?: null,
                        ':email' => $tutor_data[':email'] ?: null,
                        ':departamento' => $tutor_data[':departamento'] ?: null,
                        ':id_tutor_empresa' => $tutor_emp_id,
                    ];

                    $stmt = $this->db->prepare($update_query);
                    $stmt->execute($update_data);
                } else {
                    $insert_query = "INSERT INTO tutores_empresariales 
                    (cedula, nombre_completo, funcion, telefono, email, departamento)
                    VALUES (:cedula, :nombre_completo, :funcion, :telefono, :email, :departamento)";
                    $stmt = $this->db->prepare($insert_query);
                    $stmt->execute($tutor_data);
                    $tutor_emp_id = $this->db->lastInsertId();
                }
            }

            $insert_practica_query = "
            INSERT INTO practicas_estudiantes (
                user_id, modalidad, docente_asignado_id, entidad_id, tutor_empresarial_id, 
                estado_fase_uno_completado, fecha_registro, proyecto_id, afiliacion_iess
            ) VALUES (
                :user_id, :modalidad, :docente_asignado_id, :entidad_id, :tutor_empresarial_id, 
                :estado_fase_uno_completado, :fecha_registro, :proyecto_id, :afiliacion_iess
            )
        ";

            $stmt = $this->db->prepare($insert_practica_query);
            $stmt->execute([
                ':user_id' => $user_id,
                ':modalidad' => $data['modalidad'],
                ':docente_asignado_id' => $docente_asignado_id,
                ':entidad_id' => $entidad_id,
                ':tutor_empresarial_id' => $tutor_emp_id ?? null,
                ':estado_fase_uno_completado' => 0,
                ':fecha_registro' => date('Y-m-d H:i:s'),
                ':proyecto_id' => $data['idProyecto'] ?? null,
                ':afiliacion_iess' => $data['afiliacion_iees'] ?? null
            ]);

            $practica_id = $this->db->lastInsertId();
            $this->db->commit();

            return (int)$practica_id;
        } catch (Exception $e) {
            if ($this->db->inTransaction()) {
                $this->db->rollBack();
            }
            error_log("Error al guardar Pasantia Fase 1: " . $e->getMessage() . " Linea: " . $e->getLine());
            return $e->getMessage();
        }
    }
    public function getProgramaTrabajo(int $practicaId, int $limit = 100, int $offset = 0)
    {
        $query = "SELECT * FROM programa_trabajo 
              WHERE practica_id = :practica_id 
              ORDER BY fecha_planificada ASC 
              LIMIT :limit OFFSET :offset";
        try {
            $stmt = $this->db->prepare($query);
            $stmt->bindValue(':practica_id', $practicaId, PDO::PARAM_INT);
            $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
            $stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Error al obtener Programa de Trabajo: " . $e->getMessage());
            return [];
        }
    }
    public function addProgramaTrabajo(array $data)
    {
        $query = "
            INSERT INTO programa_trabajo (practica_id, actividad_planificada, departamento_area, funcion_asignada, fecha_planificada)
            VALUES (:practica_id, :actividad_planificada, :departamento_area, :funcion_asignada, :fecha_planificada)
        ";
        try {
            $stmt = $this->db->prepare($query);
            return $stmt->execute([
                ':practica_id' => $data['practica_id'],
                ':actividad_planificada' => $data['actividad_planificada'],
                ':departamento_area' => $data['departamento_area'] ?? null,
                ':funcion_asignada' => $data['funcion_asignada'] ?? null,
                ':fecha_planificada' => $data['fecha_planificada']
            ]);
        } catch (PDOException $e) {
            error_log("Error al insertar Programa de Trabajo: " . $e->getMessage());
            return false;
        }
    }

    public function updateProgramaTrabajo(array $data)
    {
        $query = "
        UPDATE programa_trabajo 
        SET actividad_planificada = :actividad_planificada,
            departamento_area = :departamento_area,
            funcion_asignada = :funcion_asignada,
            fecha_planificada = :fecha_planificada
        WHERE id_programa = :id
    ";

        try {
            $stmt = $this->db->prepare($query);
            return $stmt->execute([
                ':actividad_planificada' => $data['actividad_planificada'],
                ':departamento_area' => $data['departamento_area'] ?? null,
                ':funcion_asignada' => $data['funcion_asignada'] ?? null,
                ':fecha_planificada' => $data['fecha_planificada'],
                ':id' => $data['id']
            ]);
        } catch (PDOException $e) {
            error_log("Error al actualizar Programa de Trabajo: " . $e->getMessage());
            return false;
        }
    }

    public function deleteProgramaTrabajo(int $id)
    {
        $query = "DELETE FROM programa_trabajo WHERE id_programa = :id";

        try {
            $stmt = $this->db->prepare($query);
            return $stmt->execute([':id' => $id]);
        } catch (PDOException $e) {
            error_log("Error al eliminar Programa de Trabajo: " . $e->getMessage());
            return false;
        }
    }

    public function getTotalProgramaTrabajo(int $practicaId)
    {
        $query = "SELECT COUNT(*) as total FROM programa_trabajo WHERE practica_id = :practica_id";

        try {
            $stmt = $this->db->prepare($query);
            $stmt->execute([':practica_id' => $practicaId]);
            $result = $stmt->fetch(PDO::FETCH_ASSOC);
            return $result['total'] ?? 0;
        } catch (PDOException $e) {
            error_log("Error al contar Programa de Trabajo: " . $e->getMessage());
            return 0;
        }
    }
    public function getActividadesDiarias(int $practicaId)
    {
        $query = "SELECT * FROM actividades_diarias WHERE practica_id = :practica_id ORDER BY fecha_actividad DESC";
        try {
            $stmt = $this->db->prepare($query);
            $stmt->execute([':practica_id' => $practicaId]);
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Error al obtener Actividades Diarias: " . $e->getMessage());
            return [];
        }
    }

    public function getActividadesDiariasPaginated(int $practicaId, int $offset, int $limit, ?string $search = null, string $sortBy = 'fecha_actividad', string $sortDir = 'DESC')
    {
        $allowedSort = ['fecha_actividad', 'horas_invertidas', 'actividad_realizada'];
        if (!in_array($sortBy, $allowedSort)) {
            $sortBy = 'fecha_actividad';
        }
        $sortDir = strtoupper($sortDir) === 'ASC' ? 'ASC' : 'DESC';

        $sql = "SELECT * FROM actividades_diarias WHERE practica_id = :practica_id";
        $params = [':practica_id' => $practicaId];

        if ($search) {
            $sql .= " AND (actividad_realizada LIKE :search OR observaciones LIKE :search OR fecha_actividad LIKE :search)";
            $params[':search'] = '%' . $search . '%';
        }

        $sql .= " ORDER BY {$sortBy} {$sortDir} LIMIT :offset, :limit";

        try {
            $stmt = $this->db->prepare($sql);
            // bind params
            $stmt->bindValue(':practica_id', $practicaId, PDO::PARAM_INT);
            if (isset($params[':search'])) {
                $stmt->bindValue(':search', $params[':search'], PDO::PARAM_STR);
            }
            $stmt->bindValue(':offset', (int)$offset, PDO::PARAM_INT);
            $stmt->bindValue(':limit', (int)$limit, PDO::PARAM_INT);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Error en paginación Actividades Diarias: " . $e->getMessage());
            return [];
        }
    }

    public function countActividadesDiarias(int $practicaId, ?string $search = null)
    {
        $sql = "SELECT COUNT(*) as cnt FROM actividades_diarias WHERE practica_id = :practica_id";
        $params = [':practica_id' => $practicaId];
        if ($search) {
            $sql .= " AND (actividad_realizada LIKE :search OR observaciones LIKE :search OR fecha_actividad LIKE :search)";
            $params[':search'] = '%' . $search . '%';
        }

        try {
            $stmt = $this->db->prepare($sql);
            $stmt->execute($params);
            $row = $stmt->fetch(PDO::FETCH_ASSOC);
            return (int)($row['cnt'] ?? 0);
        } catch (PDOException $e) {
            error_log("Error al contar Actividades Diarias: " . $e->getMessage());
            return 0;
        }
    }
    public function getActividadDiaria(int $id, int $practicaId)
    {
        $query = "SELECT * FROM actividades_diarias WHERE id = :id AND practica_id = :practica_id";
        try {
            $stmt = $this->db->prepare($query);
            $stmt->execute([':id' => $id, ':practica_id' => $practicaId]);
            return $stmt->fetch();
        } catch (PDOException $e) {
            error_log("Error al obtener Actividad Diaria: " . $e->getMessage());
            return null;
        }
    }

    public function updateActividadDiaria(array $data)
    {
        if (empty($data['id']) || empty($data['practica_id'])) {
            error_log("Validación fallida: Faltan id o practica_id para la actualización.");
            return false;
        }

        $horas_invertidas = (float) $data['horas_invertidas'];
        if ($horas_invertidas <= 0 || $horas_invertidas > 12.00) {
            error_log("Validación fallida: Horas invertidas fuera de rango al actualizar.");
            return false;
        }

        $query = "
        UPDATE actividades_diarias 
        SET 
            actividad_realizada = :actividad_realizada,
            horas_invertidas = :horas_invertidas,
            fecha_actividad = :fecha_actividad,
            hora_inicio = :hora_inicio,
            hora_fin = :hora_fin
        WHERE id_actividad_diaria = :id 
          AND practica_id = :practica_id
    ";

        try {
            $stmt = $this->db->prepare($query);
            $resultado = $stmt->execute([
                ':actividad_realizada' => $data['actividad_realizada'],
                ':horas_invertidas'   => $data['horas_invertidas'],
                ':fecha_actividad'    => $data['fecha_actividad'],
                ':hora_inicio'        => $data['hora_inicio'],
                ':hora_fin'           => $data['hora_fin'],
                ':id'                 => $data['id'],
                ':practica_id'        => $data['practica_id']
            ]);

            if ($resultado && $stmt->rowCount() === 0) {
                error_log("ADVERTENCIA: No se actualizó ningún registro (ID: {$data['id']}, Práctica: {$data['practica_id']})");
            }

            return $resultado;
        } catch (PDOException $e) {
            error_log("Error al actualizar Actividad Diaria: " . $e->getMessage());
            return false;
        }
    }

    public function deleteActividadDiaria(int $id, int $practicaId)
    {
        $query = "DELETE FROM actividades_diarias WHERE id_actividad_diaria = :id AND practica_id = :practica_id";

        try {
            $stmt = $this->db->prepare($query);
            return $stmt->execute([
                ':id' => $id,
                ':practica_id' => $practicaId
            ]);
        } catch (PDOException $e) {
            error_log("Error al eliminar Actividad Diaria: " . $e->getMessage());
            return false;
        }
    }

    public function addActividadDiaria(array $data)
    {
        $horas_invertidas = (float) $data['horas_invertidas'];
        if ($horas_invertidas <= 0 || $horas_invertidas > 12.00) {
            error_log("Validación fallida: Horas invertidas fuera del rango permitido.");
            return false;
        }

        $query = "
        INSERT INTO actividades_diarias (
            practica_id, 
            actividad_realizada, 
            horas_invertidas, 
            fecha_actividad, 
            hora_inicio, 
            hora_fin
        ) VALUES (
            :practica_id, 
            :actividad_realizada, 
            :horas_invertidas, 
            :fecha_actividad, 
            :hora_inicio, 
            :hora_fin
        )
    ";

        try {
            $stmt = $this->db->prepare($query);
            return $stmt->execute([
                ':practica_id' => $data['practica_id'],
                ':actividad_realizada' => $data['actividad_realizada'],
                ':horas_invertidas' => $data['horas_invertidas'],
                ':fecha_actividad' => $data['fecha_actividad'],
                ':hora_inicio' => $data['hora_inicio'],
                ':hora_fin' => $data['hora_fin']
            ]);
        } catch (PDOException $e) {
            error_log("Error al insertar Actividad Diaria: " . $e->getMessage());
            return false;
        }
    }

    public function getEntidadByRUC($ruc, $idPrograma)
    {
        try {
            if ($ruc === '1702051704001') {
                $stmt = $this->db->prepare("
            SELECT * FROM entidades 
            INNER JOIN tutores_empresariales te ON entidades.id_tutor_empresarial = te.id_tutor_empresa
            WHERE ruc = :ruc
            LIMIT 1
            ");
                $stmt->execute([':ruc' => $ruc]);
                $entidad = $stmt->fetch(PDO::FETCH_ASSOC);
            } else {
                $stmt = $this->db->prepare("
            SELECT * FROM entidades 
            INNER JOIN tutores_empresariales te ON entidades.id_tutor_empresarial = te.id_tutor_empresa
            WHERE ruc = :ruc AND entidades.id_programa = :idPrograma
            LIMIT 1
            ");
                $stmt->execute([':ruc' => $ruc, ':idPrograma' => $idPrograma]);
                $entidad = $stmt->fetch(PDO::FETCH_ASSOC);
            }
            return $entidad ?: null;
        } catch (Exception $e) {
            error_log("Error al buscar entidad por RUC: " . $e->getMessage());
            return null;
        }
    }

    public function getProyectos()
    {
        try {
            $stmt = $this->db->prepare("SELECT * FROM proyectos");
            $stmt->execute();
            $entidad = $stmt->fetchAll(PDO::FETCH_ASSOC);
            return $entidad ?: null;
        } catch (Exception $e) {
            error_log("Error al buscar entidad por RUC: " . $e->getMessage());
            return null;
        }
    }

    public function getPracticaModalidad()
    {
        try {
            $stmt = $this->db->prepare("
            SELECT * FROM practica_modalidad 
            WHERE estado = 'Activo'
            ");
            $stmt->execute();
            $modalidades = $stmt->fetchAll(PDO::FETCH_ASSOC);
            return $modalidades ?: null;
        } catch (Exception $e) {
            error_log("Error al buscar las modalidades: " . $e->getMessage());
            return null;
        }
    }

    public function getStatusPracticaByUserId(int $userId)
    {
        $query = "SELECT
	pe.estado_fase_uno_completado
    FROM
	practicas_estudiantes pe 
    WHERE user_id = :userId LIMIT 1";
        $stmt = $this->db->prepare($query);
        $stmt->execute([':userId' => $userId]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function marcarFaseUnoComoCompletada($idPractica)
    {
        $query = "UPDATE practicas_estudiantes
              SET estado_fase_uno_completado = 1
              WHERE id_practica = :id";

        $db = $this->getConnection();
        $stmt = $db->prepare($query);

        try {
            $stmt->execute([':id' => $idPractica]);
            return $stmt->rowCount();
        } catch (PDOException $e) {
            error_log("Error de SQL en PracticasModel: " . $e->getMessage());
            return false;
        }
    }

    public function obtenerDatosPracticaEstudiante($id_practica)
    {
        $sql = "SELECT * FROM practicas_estudiantes WHERE id_practica = :id_practica";

        try {
            $stmt = $this->db->prepare($sql);
            $stmt->bindParam(':id_practica', $id_practica, PDO::PARAM_INT);
            $stmt->execute();
            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            return false;
        }
    }
}
