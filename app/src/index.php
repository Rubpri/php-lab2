<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestión de tareas y usuarios</title>
</head>
<body>
    <h1>Agregar usuario</h1>
    <form action="index.php" method="post">
        <label for="nombre">Nombre:</label>
        <input type="text" id="nombre" name="nombre" required>
        <br>
        <br>
        <button type="submit" name="registrar">Registar usuario</button>
    </form>
</body>
</html>

<?php

require_once("./services/conexionDb.php");
require_once("./class/mySqlUsuarios.php");
require_once("./class/usuario.php");

$conexionDB = new ConexionDB();
$usuariosMySql = new MySQLUsuarios($conexionDB);

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if(isset($_POST["registrar"])) {
        $nombre = isset($_POST["nombre"]) ? $_POST["nombre"] : "";
        $usuario = new Usuario($nombre);

        if ($usuariosMySql->postUsuario($usuario->getNombre())) {
            echo "Usuario agregado";
        } else {
            echo "Error al agregar usuario";
        }
    }    
}


// $cliente = new ConexionDB();
// $clientes = $cliente->getInfo();

// foreach ($clientes as $key => $value) {
//     echo json_encode($value);
// }

