<?php
/**
 * filtrar.php
 *
 * Este script permite buscar y visualizar información de un concierto (artista) almacenado en la base de datos XML
 * 'registros_db' gestionada por BaseX, filtrando por su atributo 'id'.
 *
 * Funcionalidad:
 * - El usuario introduce un ID a través de un formulario.
 * - Si se encuentra un artista con ese ID, se muestra su información (nombre, género, miembros, fecha, país)
 *   en formato HTML con diseño de tarjeta.
 * - Si no se encuentra, se muestra un mensaje de advertencia.
 *
 * Seguridad:
 * - El ID es sanitizado usando htmlspecialchars y trim.
 *
 * Requisitos:
 * - El servidor BaseX debe estar activo en localhost:1984.
 * - Se requiere la clase BaseXClient\Session.php para conectarse al servidor.
 */
require_once("BaseXClient/Session.php");

$resultado = "";
$mensaje = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['id']) && trim($_POST['id']) !== "") {
    try {
        $id = htmlspecialchars(trim($_POST['id']));
        $session = new BaseXClient\Session("localhost", 1984, "admin", "1234");

        $xquery = <<<XQ
xquery let \$a := collection('registros_db')/conciertos/artista[@id = '$id']
return
  if (\$a)
  then concat(
      "<div class='card'>",
      "<h2>", \$a/nombre, "</h2>",
      "<p><strong>Género:</strong> ", \$a/genero, "</p>",
      "<p><strong>Miembros:</strong> ", \$a/miembros, "</p>",
      "<p><strong>Fecha:</strong> ", \$a/fecha_concierto, "</p>",
      "<p><strong>País:</strong> ", \$a/pais, "</p>",
      "</div>"
  )
  else ""
XQ;

        $resultado = $session->execute($xquery);
        if (trim($resultado) === "") {
            $mensaje = "No se encontró ningún artista con el ID '$id'.";
        }

        $session->close();
    } catch (Exception $e) {
        $mensaje = "Error: " . $e->getMessage();
    }
} else if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $mensaje = "Por favor, introduce un ID para buscar.";
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Filtrar concierto por ID - TicketsNow</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <header>
        <h1>Filtrar concierto por ID</h1>
    </header>

    <form method="post" action="">
        <label for="id">ID de artista:</label>
        <input type="number" name="id" id="id" min="1" required>
        <button type="submit">Buscar</button>
    </form>

    <?php
    if (!empty($mensaje)) echo "<p><strong>$mensaje</strong></p>";
    if (!empty($resultado)) echo "<div class='results'>$resultado</div>";
    ?>
    
    <div class="volver">
        <a href="index.php">Volver al inicio</a>
    </div>
</body>
</html>
