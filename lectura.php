<?php
/**
 * lectura.php
 *
 * Este script recupera y muestra todos los conciertos (nodos <artista>) almacenados en la base de datos XML 'registros_db'
 * mediante una consulta XQuery, mostrando cada uno como una tarjeta con formato HTML.
 *
 * Funcionalidad:
 * - Se conecta al servidor BaseX y accede a la colección 'registros_db'.
 * - Recorre todos los elementos <artista> dentro de <conciertos>.
 * - Muestra su información: nombre, ID, género, número de miembros, fecha del concierto y país.
 * - Cada concierto se presenta visualmente en una tarjeta HTML (div con clase 'card').
 *
 * Requisitos:
 * - El servidor BaseX debe estar ejecutándose en localhost:1984.
 * - Es necesaria la clase BaseXClient\Session.php para establecer la conexión con la base.
 */
require_once("BaseXClient/Session.php");

try {
    $session = new BaseXClient\Session("localhost", 1984, "admin", "1234");

    $xquery = <<<XQ
xquery for \$a in collection('registros_db')/conciertos/artista
return concat(
    "<div class='card'>",
    "<h2>", \$a/nombre, "</h2>",
    "<p><strong>ID:</strong> ", \$a/@id, "</p>",
    "<p><strong>Género:</strong> ", \$a/genero, "</p>",
    "<p><strong>Miembros:</strong> ", \$a/miembros, "</p>",
    "<p><strong>Fecha:</strong> ", \$a/fecha_concierto, "</p>",
    "<p><strong>País:</strong> ", \$a/pais, "</p>",
    "</div>"
)
XQ;

    $resultado = $session->execute($xquery);
    $session->close();
} catch (Exception $e) {
    $resultado = "<p>Error: " . $e->getMessage() . "</p>";
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Todos los conciertos - TicketsNow</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <header>
        <h1>Todos los conciertos</h1>
    </header>
    <div class="results">
        <?php echo $resultado; ?>
    </div>

    <div class="volver">
        <a href="index.php">Volver al inicio</a>
    </div>
</body>
</html>
