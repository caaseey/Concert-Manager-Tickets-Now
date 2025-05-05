<?php
require_once(__DIR__ . "/BaseXClient/Session.php"); // Importamos la clase de conexión con BaseX

// Inicializamos mensaje vacío
$mensaje = "";

// Verificamos que se haya enviado el formulario y que todos los campos tengan datos
if (
    $_SERVER['REQUEST_METHOD'] === 'POST'
    && isset($_POST['nombre'], $_POST['genero'], $_POST['miembros'], $_POST['fecha'], $_POST['pais'])
    && trim($_POST['nombre']) !== ""
    && trim($_POST['genero']) !== ""
    && trim($_POST['miembros']) !== ""
    && trim($_POST['fecha']) !== ""
    && trim($_POST['pais']) !== ""
) {

    try {
        // Creamos sesión con BaseX
        $session = new BaseXClient\Session("localhost", 1984, "admin", "1234");

        // Sanitizamos y limpiamos los datos de entrada
        $nombre = htmlspecialchars(trim($_POST['nombre']));
        $genero = htmlspecialchars(trim($_POST['genero']));
        $miembros = htmlspecialchars(trim($_POST['miembros']));
        $fecha = htmlspecialchars(trim($_POST['fecha']));
        $pais = htmlspecialchars(trim($_POST['pais']));

        // Validamos que la fecha no sea anterior a hoy
        $fecha_actual = date("Y-m-d");
        if ($fecha < $fecha_actual) {
            $mensaje = "La fecha del concierto no puede ser anterior a hoy ($fecha_actual).";
        } else {
            // Consultamos si ya existe un artista con ese nombre
            $checkQuery = "count(collection('registros_db')/conciertos/artista[nombre = '$nombre'])";
            $existe = $session->execute("xquery " . $checkQuery);

            $xquery = <<<XQ
xquery insert node <artista>
    <nombre>{$nombre}</nombre>
    <genero>{$genero}</genero>
    <miembros>{$miembros}</miembros>
    <fecha_concierto>{$fecha}</fecha_concierto>
    <pais>{$pais}</pais>
    </artista> into collection('registros_db')/conciertos
XQ;
            $session->execute($xquery);
            $mensaje = "Concierto añadido correctamente.";
        }

        // Cerramos la sesión con la base de datos
        $session->close();
    } catch (Exception $e) {
        // Capturamos errores si falla algo en la conexión o ejecución
        $mensaje = "Error al añadir: " . $e->getMessage();
    }
} else if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Si se ha enviado el formulario pero algún campo está vacío
    $mensaje = "Todos los campos son obligatorios. Por favor, completa el formulario.";
}
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Añadir concierto - TicketsNow</title>
    <link rel="stylesheet" href="styles.css">
</head>

<body>
    <header>
        <h1>Añadir concierto</h1>
    </header>
    <form action="" method="post">
        <label for="nombre">Nombre del artista:</label>
        <input type="text" name="nombre" id="nombre" required><br>

        <label for="genero">Género:</label>
        <input type="text" name="genero" id="genero" required><br>

        <label for="miembros">Miembros:</label>
        <input type="number" name="miembros" id="miembros" min="1" step="1" required><br>

        <label for="fecha">Fecha del concierto:</label>
        <input type="date" name="fecha" id="fecha" required><br>

        <label for="pais">País:</label>
        <input type="text" name="pais" id="pais" required><br>

        <button type="submit">Añadir</button>
    </form>
    
    <?php if (!empty($mensaje)) echo "<div class='mensaje'>" . $mensaje . "</div>"; ?>
    
    <div class="volver">
        <a href="index.php">Volver al inicio</a>
    </div>
</body>
</html>