<?php

class MySQLTareas {

    private $db;

    public function __construct($db) {
        $this->db = $db;
    }

    public function getTareasPendientes() {
        $sql = "SELECT * FROM tareas WHERE active = false";
        $stmt = $this->db->getConexion()->query($sql);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getTareasAsignadas() {
        $sql = "SELECT tareas.id, tareas.descripcion, usuarios.nombre 
            FROM tareas 
            LEFT JOIN usuarios ON tareas.usuario_id = usuarios.id 
            WHERE tareas.active = true";
        $stmt = $this->db->getConexion()->query($sql);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function postTarea($descripcion, $asignar) {
        $usuario_id = !empty($asignar) ? $asignar : null;
        $sql="INSERT INTO tareas (descripcion, usuario_id, active) VALUES (:descripcion, :usuario_id, CASE WHEN :usuario_id IS NOT NULL THEN true ELSE false END)";
        $stmt = $this->db->getConexion()->prepare($sql);
        $stmt->bindParam(":descripcion", $descripcion);
        $stmt->bindParam(":usuario_id", $usuario_id);
        return $stmt->execute();
    }

    public function putTarea($tarea_id, $usuario_id) {
        $sql = "UPDATE tareas SET usuario_id = :usuario_id, active = true WHERE id = :tarea_id";
        $stmt = $this->db->getConexion()->prepare($sql);
        $stmt->bindParam(":usuario_id", $usuario_id);
        $stmt->bindParam(":tarea_id", $tarea_id);
        $stmt->execute();
    }
}