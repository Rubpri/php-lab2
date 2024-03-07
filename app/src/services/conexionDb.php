<?php
class ConexionDB {
    private $dbhost;
    private $usuario;
    private $contrasena;
    private $database;
    private $conexion;
    
    public function __construct() {
        $this->dbhost = getenv("MYSQL_HOST");
        $this->usuario  = getenv("MYSQL_USER");
        $this->contrasena = getenv("MYSQL_PASSWORD");
        $this->database = getenv("MYSQL_DATABASE");
        try{
            $this->conexion = new PDO("mysql:host=$this->dbhost;dbname=$this->database", $this->usuario, $this->contrasena);
            $this->conexion->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch (PDOException $e) {
            echo "Error de conexión: " . $e->getMessage();
            echo "<br>" . "Host: " . $this->dbhost . "<br>";
            echo "User: " . $this->usuario . "<br>";
            echo "Password: " . $this->contrasena . "<br>";
            echo "Database: " . $this->database . "<br>";
            die();
        }
    }
    
    public function desconectar() {
        $this->conexion = null;
    }

    public function getConexion() {
        return $this->conexion;
    }

    // public function getInfo() {
    //     $sql = 'SELECT * FROM cliente ORDER BY cliente.id DESC';
    //     $stmt = $this->conexion->prepare($sql);
    //     $stmt->execute();
    //     $clientes = $stmt->fetchAll(PDO::FETCH_ASSOC);
    //     return $clientes;
    // }
}