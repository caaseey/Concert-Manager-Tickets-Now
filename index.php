<!--
index.php

Este archivo actúa como página principal del sistema de gestión de conciertos "TicketsNow".
Proporciona enlaces a las distintas funcionalidades disponibles:

- Ver todos los conciertos registrados (lectura.php)
- Filtrar un concierto por su ID (filtrar.php)
- Añadir un nuevo concierto (insertar.php)
- Actualizar información de un concierto existente (actualizar.php)
- Eliminar un concierto por nombre o ID (borrar.php)

La estructura es simple y sirve como punto de entrada para la navegación del sistema.
-->
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Conciertos en TicketsNow</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <header>
        <h1>Conciertos en TicketsNow</h1>
    </header>
    <div class="menu">
        <a href="lectura.php">Ver todos los conciertos</a><br>
        <a href="filtrar.php">Filtrar concierto por ID</a><br>
        <a href="insertar.php">Añadir un concierto</a><br>
        <a href="actualizar.php">Actualizar un concierto</a><br>
        <a href="borrar.php">Eliminar un concierto</a><br>
    </div>
</body>
</html>