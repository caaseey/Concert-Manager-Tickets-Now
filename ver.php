<?php
require_once("BaseXClient/Session.php");

try {
    $session = new BaseXClient\Session("localhost", 1984, "admin", "123");

    $xquery = <<<XQ
xquery for \$a in collection('registros_db')/conciertos/artista
return concat(
    "<div class='card'>",
    "<h2>", \$a/nombre, "</h2>",
    "<p><strong>Género:</strong> ", \$a/genero, "</p>",
    "<p><strong>Miembros:</strong> ", \$a/miembros, "</p>",
    "<p><strong>Fecha:</strong> ", \$a/fecha_concierto, "</p>",
    "<p><strong>País:</strong> ", \$a/pais, "</p>",
    "<p><strong>---------------------------------------</strong></p>",
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
</head>
<body>
    <header>
        <h1>Todos los conciertos</h1>
    </header>
    <div class="results">
        <?php echo $resultado; ?>
    </div>
</body>
</html>
