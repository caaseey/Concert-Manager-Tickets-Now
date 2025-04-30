<?php
require_once("BaseXClient/Session.php"); // Importamos la clase Session de BaseX

// Inicializamos variable de mensaje
$mensaje = "";

// Si se ha enviado el formulario por POST y el campo 'nombre' no está vacío
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['nombre']) && trim($_POST['nombre']) !== '') {
    try {
        // Establecemos la conexión con el servidor BaseX
        $session = new BaseXClient\Session("localhost", 1984, "admin", "123");

        // Sanitizamos el nombre introducido para evitar errores o inyecciones
        $nombre = htmlspecialchars(trim($_POST['nombre']));

        // Comprobamos si el artista existe en la base de datos
        $checkQuery = "xquery count(collection('registros_db')/conciertos/artista[nombre = '$nombre'])";
        $existe = $session->execute($checkQuery);

        if ((int)$existe === 0) {
            // Si no existe, mostramos un mensaje al usuario
            $mensaje = "No existe ningún artista con el nombre '$nombre'.";
        } else {
            // Si existe, ejecutamos la consulta XQuery para eliminar el nodo
            $xquery = <<<XQ
xquery delete node collection('registros_db')/conciertos/artista[nombre = '$nombre']
XQ;
            $session->execute($xquery);
            $mensaje = "Concierto eliminado correctamente.";
        }

        // Cerramos la sesión
        $session->close();
    } catch (Exception $e) {
        // Capturamos cualquier error relacionado con BaseX y mostramos el mensaje
        $mensaje = "Error al eliminar: " . $e->getMessage();
    }
} else if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Si se ha enviado el formulario pero el campo está vacío
    $mensaje = "Por favor, introduce el nombre del artista.";
}
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Eliminar concierto - TicketsNow</title>
</head>

<body>
    <header>
        <h1>Eliminar concierto</h1>
    </header>
    <form action="" method="post">
        <label for="nombre">Nombre del artista a eliminar:</label>
        <input type="text" name="nombre" id="nombre" required>
        <button type="submit">Eliminar</button>
    </form>
    <?php if (!empty($mensaje)) echo "<div class='mensaje'>" . $mensaje . "</div>"; ?>
</body>

</html>