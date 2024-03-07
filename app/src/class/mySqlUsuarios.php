<?php

class MySQLUsuarios {

    private $db;

    public function __construct($db) {
        $this->db = $db;
    }

    public function getUsuarios() {

    }

    public function postUsuario($nombre) {
        $sql="INSERT INTO usuarios (nombre) VALUES (:nombre)";
        $stmt = $this->db->getConexion()->prepare($sql);
        $stmt->bindParam(":nombre", $nombre);
        return $stmt->execute();
    }
}