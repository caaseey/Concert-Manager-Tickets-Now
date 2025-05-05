<?php
/**
 * actualizar.php
 *
 * Este script permite actualizar los datos de un concierto almacenado en una base de datos XML gestionada con BaseX.
 * A través de un formulario HTML, el usuario puede modificar distintos campos de un artista/concierto (nombre, género,
 * número de miembros, fecha del concierto y país), siempre que proporcione un ID válido.
 *
 * Validaciones implementadas:
 * - El campo ID es obligatorio.
 * - La fecha no puede ser anterior a la actual.
 * - Solo se ejecutan actualizaciones para los campos no vacíos.
 *
 * Si el ID proporcionado no existe en la base XML 'registros_db', se muestra un mensaje de error.
 *
 * Requiere la clase BaseXClient\Session.php para conectarse al servidor BaseX.
 */
require_once("BaseXClient/Session.php");
$mensaje = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST' && !empty($_POST['id'])) {
    $id = htmlspecialchars(trim($_POST['id']));
    $updates = [];

    if (!empty($_POST['nombre'])) {
        $updates[] = "replace value of node collection('registros_db')/conciertos/artista[@id='$id']/nombre with '" . htmlspecialchars(trim($_POST['nombre'])) . "'";
    }
    if (!empty($_POST['genero'])) {
        $updates[] = "replace value of node collection('registros_db')/conciertos/artista[@id='$id']/genero with '" . htmlspecialchars(trim($_POST['genero'])) . "'";
    }
    if (!empty($_POST['miembros'])) {
        $updates[] = "replace value of node collection('registros_db')/conciertos/artista[@id='$id']/miembros with '" . htmlspecialchars(trim($_POST['miembros'])) . "'";
    }
    if (!empty($_POST['fecha'])) {
        $fecha = htmlspecialchars(trim($_POST['fecha']));
        if ($fecha < date("Y-m-d")) {
            $mensaje = "La fecha no puede ser anterior a hoy.";
        } else {
            $updates[] = "replace value of node collection('registros_db')/conciertos/artista[@id='$id']/fecha_concierto with '$fecha'";
        }
    }
    if (!empty($_POST['pais'])) {
        $updates[] = "replace value of node collection('registros_db')/conciertos/artista[@id='$id']/pais with '" . htmlspecialchars(trim($_POST['pais'])) . "'";
    }

    if (empty($mensaje) && !empty($updates)) {
        try {
            $session = new BaseXClient\Session("localhost", 1984, "admin", "1234");
            $checkQuery = "xquery count(collection('registros_db')/conciertos/artista[@id='$id'])";
            $existe = $session->execute($checkQuery);
            if ((int)$existe === 0) {
                $mensaje = "No existe ningún concierto con ID $id.";
            } else {
                $session->execute("xquery (" . implode(', ', $updates) . ")");
                $mensaje = "Concierto actualizado correctamente.";
            }
            $session->close();
        } catch (Exception $e) {
            $mensaje = "Error: " . $e->getMessage();
        }
    } elseif (empty($updates)) {
        $mensaje = "No se introdujo ningún cambio.";
    }
} elseif ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $mensaje = "El campo ID es obligatorio.";
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Actualizar concierto</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <header><h1>Actualizar concierto</h1></header>

    <form method="post">
        <label>ID del concierto:</label>
        <input type="number" name="id" required><br>

        <label>Nuevo nombre:</label>
        <input type="text" name="nombre"><br>

        <label>Nuevo género:</label>
        <input type="text" name="genero"><br>

        <label>Nuevos miembros:</label>
        <input type="number" name="miembros" min="1"><br>

        <label>Nueva fecha:</label>
        <input type="date" name="fecha"><br>

        <label>Nuevo país:</label>
        <input type="text" name="pais"><br>

        <button type="submit">Actualizar</button>
    </form>

    <?php if ($mensaje) echo "<div class='mensaje'>$mensaje</div>"; ?>

    <div class="volver">
        <a href="index.php">Volver al inicio</a>
    </div>
</body>
</html>
