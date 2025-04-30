<?php
require_once("BaseXClient/Session.php"); // Importamos la clase Session de BaseX

// Inicializamos variable de mensaje
$mensaje = "";

// Comprobamos si se ha enviado el formulario y que todos los campos estén rellenos
if (
    $_SERVER['REQUEST_METHOD'] === 'POST'
    && isset($_POST['nombre'], $_POST['nuevo_genero'], $_POST['nuevos_miembros'], $_POST['nueva_fecha'], $_POST['nuevo_pais'])
    && $_POST['nombre'] !== ""
    && $_POST['nuevo_genero'] !== ""
    && $_POST['nuevos_miembros'] !== ""
    && $_POST['nueva_fecha'] !== ""
    && $_POST['nuevo_pais'] !== ""
) {

    // Sanitizamos entradas para evitar inyección de código o errores
    $nombre = htmlspecialchars(trim($_POST['nombre']));
    $nuevo_genero = htmlspecialchars(trim($_POST['nuevo_genero']));
    $nuevos_miembros = htmlspecialchars(trim($_POST['nuevos_miembros']));
    $nueva_fecha = htmlspecialchars(trim($_POST['nueva_fecha']));
    $nuevo_pais = htmlspecialchars(trim($_POST['nuevo_pais']));

    // Validamos que la fecha no sea anterior a hoy
    $fecha_actual = date("Y-m-d");
    if ($nueva_fecha < $fecha_actual) {
        $mensaje = "La fecha del concierto no puede ser anterior a la fecha actual ($fecha_actual).";
    } else {
        try {
            // Establecemos conexión con BaseX
            $session = new BaseXClient\Session("localhost", 1984, "admin", "123");

            // Comprobamos si el artista existe en la base de datos
            $checkQuery = "xquery count(collection('registros_db')/conciertos/artista[nombre = '$nombre'])";
            $existe = $session->execute($checkQuery);

            // Si no existe ningún artista con ese nombre, mostramos error
            if ((int)$existe === 0) {
                $mensaje = "No existe ningún artista con el nombre '$nombre'.";
            } else {
                // Creamos una consulta XQuery para actualizar los valores del artista
                $xquery = <<<XQ
xquery (
  replace value of node collection('registros_db')/conciertos/artista[nombre = '$nombre']/genero with '$nuevo_genero',
  replace value of node collection('registros_db')/conciertos/artista[nombre = '$nombre']/miembros with '$nuevos_miembros',
  replace value of node collection('registros_db')/conciertos/artista[nombre = '$nombre']/fecha_concierto with '$nueva_fecha',
  replace value of node collection('registros_db')/conciertos/artista[nombre = '$nombre']/pais with '$nuevo_pais'
)
XQ;

                // Ejecutamos la consulta
                $session->execute($xquery);
                $mensaje = "Concierto actualizado correctamente.";
            }

            // Cerramos la sesión con la base de datos
            $session->close();
        } catch (Exception $e) {
            // Capturamos cualquier error relacionado con BaseX
            $mensaje = "Error al actualizar: " . $e->getMessage();
        }
    }
} else if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Si se envió el formulario pero hay campos vacíos
    $mensaje = "Todos los campos son obligatorios. Por favor, rellena el formulario completo.";
}
?>
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Actualizar concierto - TicketsNow</title>
</head>

<body>
    <header>
        <h1>Actualizar concierto</h1>
    </header>
    <form action="" method="post">
        <label for="nombre">Nombre del artista a actualizar:</label>
        <input type="text" name="nombre" id="nombre" required><br>

        <label for="nuevo_genero">Nuevo género:</label>
        <input type="text" name="nuevo_genero" id="nuevo_genero" required><br>

        <label for="nuevos_miembros">Nuevos miembros:</label>
        <input type="text" name="nuevos_miembros" id="nuevos_miembros" required><br>

        <label for="nueva_fecha">Nueva fecha del concierto:</label>
        <input type="date" name="nueva_fecha" id="nueva_fecha" required><br>

        <label for="nuevo_pais">Nuevo país:</label>
        <input type="text" name="nuevo_pais" id="nuevo_pais" required><br>

        <button type="submit">Actualizar</button>
    </form>
    <?php if (!empty($mensaje)) echo "<div class='mensaje'>" . $mensaje . "</div>"; ?>
</body>

</html>