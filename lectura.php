<?php
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
