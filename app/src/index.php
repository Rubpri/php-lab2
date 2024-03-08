<?php

require_once("./services/conexionDb.php");
require_once("./class/mySqlUsuarios.php");
require_once("./class/usuario.php");
require_once("./class/mySqlTareas.php");
require_once("./class/tarea.php");

$conexionDB = new ConexionDB();
$usuariosMySql = new MySQLUsuarios($conexionDB);
$tareasMySql = new MySQLTareas($conexionDB);

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if(isset($_POST["registrar-usuario"])) {
        $nombre = isset($_POST["nombre"]) ? $_POST["nombre"] : "";
        $usuario = new Usuario($nombre);

        if ($usuariosMySql->postUsuario($usuario->getNombre())) {
            echo "Usuario añadido";
        } else {
            echo "Error al añadir usuario";
        }
    } 
    if(isset($_POST["registrar-tarea"])) {
        $descripcion = isset($_POST["tarea"]) ? $_POST["tarea"] : "";
        $tarea = new Tarea($descripcion);
        $asignar = isset($_POST["asignar"]) ? $_POST["asignar"] : "";

        if ($tareasMySql->postTarea($tarea->getDescripcion(), $asignar)) {
            echo "Tarea añadida";
        } else {
            echo "Error al añadir tarea";
        }
    } 
    if (isset($_POST["asignar-tarea-existente"])) {
        $tarea_id = isset($_POST["tarea_id"]) ? $_POST["tarea_id"] : "";
        $usuario_id = isset($_POST["usuario_id"]) ? $_POST["usuario_id"] : "";
        
        if ($tarea_id !== null && $usuario_id !== null) {
            $tareasMySql->putTarea($tarea_id, $usuario_id);
            echo "Tarea actualizada";
        } else {
            echo "Error al actualizar tarea";
        }
    }
}

$usuarios = $usuariosMySql->getUsuarios();
$tareasPendientes = $tareasMySql->getTareasPendientes();
$tareasAsignadas = $tareasMySql->getTareasAsignadas();
$conexionDB->desconectar();

?>

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
        <button type="submit" name="registrar-usuario">Registar usuario</button>
    </form>
    <table>
        <tr>
            <th>ID</th>
            <th>Nombre</th>
        </tr>
        <?php foreach ($usuarios as $usuario) { ?>
                <tr>
                    <td><?php echo $usuario['id']?></td>
                    <td><?php echo $usuario['nombre']?></td>
                </tr>
        <?php  } ?>
        
    </table>

    <h1>Crear tarea</h1>
    <form action="index.php" method="post">
        <label for="tarea">Tarea:</label>
        <input type="text" id="tarea" name="tarea" required>
        <br>
        <br>
        <label for="asignar">Asignar a:</label>
        <select type="number" id="asignar" name="asignar">
            <option value="">Asignar más tarde</option>
        <?php 
            foreach ($usuarios as $usuario) {
                echo "<option value='" . $usuario['id'] . "'>" . $usuario['nombre'] . "</option>";
            }
        ?>
        </select>
        <br>
        <br>
        <button type="submit" name="registrar-tarea">Añadir tarea</button>
    </form>
    <table>
        <h3>Tareas Asignadas</h3>
        <tr>
            <th>ID</th>
            <th>Tarea</th>
            <th>Asignada a:</th>
        </tr>
        <?php foreach ($tareasAsignadas as $tarea) { ?>
                <tr>
                    <td><?php echo $tarea['id']?></td>
                    <td><?php echo $tarea['descripcion']?></td>
                    <td><?php echo $tarea['nombre']?></td>
                </tr>
        <?php  } ?>
        
    </table>
    <table>
    <h3>Tareas Pendientes</h3>
        <tr>
            <th>ID</th>
            <th>Tarea</th>
            <th>Asignar a:</th>
        </tr>
        <?php foreach ($tareasPendientes as $tarea) { ?>
                <tr>
                    <td><?php echo $tarea['id']?></td>
                    <td><?php echo $tarea['descripcion']?></td>
                    <td>
                    <form action="index.php" method="post">
                        <input type="hidden" name="tarea_id" value="<?php echo $tarea['id']; ?>">
                        <select name="usuario_id">
                            <?php foreach ($usuarios as $usuario) { ?>
                            <option value="<?php echo $usuario['id']; ?>"><?php echo $usuario['nombre']; ?></option>
                            <?php } ?>
                        </select>
                        <button type="submit" name="asignar-tarea-existente">Asignar</button>
                    </form>
                    </td>
                </tr>
        <?php  } ?>
        
    </table>

</body>
</html>

<?php


