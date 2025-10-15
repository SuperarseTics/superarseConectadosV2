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
        $query = "SELECT id_practica, modalidad, estado_fase_uno_completado FROM practicas_estudiantes WHERE user_id = :userId LIMIT 1";
        $stmt = $this->db->prepare($query);
        $stmt->execute([':userId' => $userId]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
    private function mapDocenteByPrograma(string $programa): ?int
    {
        $mapping = [
            'Producción animal' => 1,
            'Ingeniería de Minas' => 2,
            'Agronomía' => 3,
            'Contabilidad' => 4,
            'Sistemas' => 5,
            'Derecho' => 6,
        ];
        return $mapping[trim($programa)] ?? null;
    }
    public function savePasantiaPhaseOne(array $data)
    {
        if (empty($data['user_id']) || empty($data['programa']) || empty($data['modalidad']) || empty($data['entidad_ruc']) || empty($data['tutor_emp_cedula'])) {
            error_log("Datos incompletos para Fase 1.");
            return false;
        }

        $this->db->beginTransaction();

        try {
            $user_id = $data['user_id'];
            $programa = $data['programa'];

            $docente_asignado_id = $this->mapDocenteByPrograma($programa);
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
            ];

            if ($existing_entidad) {
                $entidad_id = $existing_entidad['id_entidad'];
                $update_query = "UPDATE entidades SET nombre_empresa = :nombre_empresa, razon_social = :razon_social, persona_contacto = :persona_contacto, telefono_contacto = :telefono_contacto, email_contacto = :email_contacto WHERE id_entidad = :id_entidad";
                $entidad_data[':id_entidad'] = $entidad_id;
                $stmt = $this->db->prepare($update_query);
                $stmt->execute($entidad_data);
            } else {
                $insert_query = "INSERT INTO entidades (nombre_empresa, ruc, razon_social, persona_contacto, telefono_contacto, email_contacto) VALUES (:nombre_empresa, :ruc, :razon_social, :persona_contacto, :telefono_contacto, :email_contacto)";
                $stmt = $this->db->prepare($insert_query);
                $stmt->execute($entidad_data);
                $entidad_id = $this->db->lastInsertId();
            }

            $tutor_emp_id = null;
            $stmt = $this->db->prepare("SELECT id_tutor_empresa FROM tutores_empresariales WHERE cedula = :cedula");
            $stmt->execute([':cedula' => $data['tutor_emp_cedula']]);
            $existing_tutor = $stmt->fetch();

            $tutor_data = [
                ':cedula' => $data['tutor_emp_cedula'],
                ':nombre_completo' => $data['tutor_emp_nombre_completo'] ?? 'N/A',
                ':funcion' => $data['tutor_emp_funcion'] ?? null,
                ':telefono' => $data['tutor_emp_telefono'] ?? null,
                ':email' => $data['tutor_emp_email'] ?? null,
                ':departamento' => $data['tutor_emp_departamento'] ?? null,
            ];

            if ($existing_tutor) {
                $tutor_emp_id = $existing_tutor['id_tutor_empresa'];
                $update_query = "UPDATE tutores_empresariales SET nombre_completo = :nombre_completo, funcion = :funcion, telefono = :telefono, email = :email, departamento = :departamento WHERE id_tutor_empresa = :id_tutor_empresa";
                $tutor_data[':id_tutor_empresa'] = $tutor_emp_id;
                $stmt = $this->db->prepare($update_query);
                $stmt->execute($tutor_data);
            } else {
                $insert_query = "INSERT INTO tutores_empresariales (cedula, nombre_completo, funcion, telefono, email, departamento) VALUES (:cedula, :nombre_completo, :funcion, :telefono, :email, :departamento)";
                $stmt = $this->db->prepare($insert_query);
                $stmt->execute($tutor_data);
                $tutor_emp_id = $this->db->lastInsertId();
            }

            $insert_practica_query = "
                INSERT INTO practicas_estudiantes (
                    user_id, modalidad, docente_asignado_id, entidad_id, tutor_empresarial_id, estado_fase_uno_completado
                ) VALUES (
                    :user_id, :modalidad, :docente_asignado_id, :entidad_id, :tutor_empresarial_id, :estado
                )
            ";

            $stmt = $this->db->prepare($insert_practica_query);
            $stmt->execute([
                ':user_id' => $user_id,
                ':modalidad' => $data['modalidad'],
                ':docente_asignado_id' => $docente_asignado_id,
                ':entidad_id' => $entidad_id,
                ':tutor_empresarial_id' => $tutor_emp_id,
                ':estado' => 0 
            ]);

            $practica_id = $this->db->lastInsertId();
            $this->db->commit();

            return (int)$practica_id;

        } catch (Exception $e) {
            if ($this->db->inTransaction()) {
                $this->db->rollBack();
            }
            error_log("Error al guardar Pasantia Fase 1: " . $e->getMessage());
            return false;
        }
    }
    public function getProgramaTrabajo(int $practicaId)
    {
        $query = "SELECT * FROM programa_trabajo WHERE practica_id = :practica_id ORDER BY fecha_planificada ASC";
        try {
            $stmt = $this->db->prepare($query);
            $stmt->execute([':practica_id' => $practicaId]);
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
    public function addActividadDiaria(array $data)
    {
        $horas_invertidas = (float)($data['horas_invertidas']);
        if ($horas_invertidas <= 0 || $horas_invertidas > 6.00) {
            error_log("Validación fallida: Horas invertidas fuera del rango permitido.");
            return false;
        }

        $query = "
            INSERT INTO actividades_diarias (practica_id, actividad_realizada, horas_invertidas, observaciones, fecha_actividad)
            VALUES (:practica_id, :actividad_realizada, :horas_invertidas, :observaciones, :fecha_actividad)
        ";

        try {
            $stmt = $this->db->prepare($query);
            return $stmt->execute([
                ':practica_id' => $data['practica_id'],
                ':actividad_realizada' => $data['actividad_realizada'],
                ':horas_invertidas' => $horas_invertidas,
                ':observaciones' => $data['observaciones'] ?? null,
                ':fecha_actividad' => $data['fecha_actividad']
            ]);
        } catch (PDOException $e) {
            error_log("Error al insertar Actividad Diaria: " . $e->getMessage());
            return false;
        }
    }
}