# 🎤 TicketsNow - Gestión de Conciertos (PHP + BaseX)

Este proyecto es una aplicación web sencilla que permite gestionar conciertos musicales usando **PHP** y **BaseX (XML DB)**. Puedes añadir, consultar, filtrar, actualizar y eliminar conciertos almacenados en una base de datos XML.

---

## 📦 Requisitos

- PHP (>= 7.x)
- BaseX Server (instalado y corriendo en `localhost:1984`)
- Navegador web
- Colección BaseX llamada `registros_db` con un XML llamado `registros.xml`

---

## 📁 Estructura del Proyecto

/BaseXClient/Session.php # Clase de conexión a BaseX
/styles.css # Hoja de estilos CSS compartida
/index.php # Página de menú principal
/lectura.php # Muestra todos los conciertos
/insertar.php # Añadir nuevo concierto
/filtrar.php # Buscar concierto por ID
/actualizar.php # Actualizar campos de un concierto por ID
/borrar.php # Eliminar concierto por ID

---


---

## 🧠 Funcionamiento

### 🔍 Lectura (`lectura.php`)
Muestra todos los conciertos en tarjetas, incluyendo:
- ID
- Nombre del artista
- Género
- Número de miembros
- Fecha del concierto
- País

### ➕ Insertar (`insertar.php`)
Formulario para crear un nuevo artista. Valida:
- Que todos los campos estén rellenos
- Que la fecha sea igual o posterior a hoy
- Que el nombre no esté duplicado

### 🔎 Filtrar (`filtrar.php`)
Busca y muestra un concierto por su **ID** exacto.

### 📝 Actualizar (`actualizar.php`)
Permite modificar los datos de un artista indicando su ID.  
⚠️ Solo actualiza los campos que se hayan rellenado.

### ❌ Borrar (`borrar.php`)
Elimina un concierto de la base según el ID.

---

## ⚙️ Comandos Útiles (BaseX)

### Crear la base:
```bash
basex -c "CREATE DB registros_db path/a/registros.xml"

Exportar la base:

basex -c "EXPORT registros_db /ruta/de/destino"
