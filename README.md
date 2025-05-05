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

| Archivo                      | Descripción                                     |
|-----------------------------|-------------------------------------------------|
| /BaseXClient/Session.php    | Clase de conexión a BaseX                       |
| /styles.css                 | Hoja de estilos CSS compartida                 |
| /index.php                  | Página de menú principal                        |
| /lectura.php                | Muestra todos los conciertos                    |
| /insertar.php               | Añadir nuevo concierto                          |
| /filtrar.php                | Buscar concierto por ID                         |
| /actualizar.php             | Actualizar campos de un concierto por ID        |
| /borrar.php                 | Eliminar concierto por ID                       |


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

### 🔎 Filtrar (`filtrar.php`)
Busca y muestra un concierto por su **ID** exacto.

### 📝 Actualizar (`actualizar.php`)
Permite modificar los datos de un artista indicando su ID.  
- Solo actualiza los campos que se hayan rellenado.

### ❌ Borrar (`borrar.php`)
Elimina un concierto de la base según el ID.

---

## ⚙️ Configuración de la Base de Datos en BaseX

Sigue estos pasos para importar tu base de datos XML y permitir que las páginas PHP se conecten correctamente:

### 1. Iniciar el servidor BaseX
Asegúrate de que el servidor esté activo. Puedes abrirlo desde terminal o menú:

```bash
basexserver
```

Esto habilita conexiones en `localhost:1984`, necesario para que los scripts PHP funcionen.

---

### 2. Crear la base de datos desde el archivo XML

Ejecuta este comando en terminal para crear la base `registros_db` a partir del archivo XML:

```bash
basex -c "CREATE DB registros_db C:\xampp\htdocs\TU_PROYECTO\ruta\registros.xml"
```

📌 Cambia la ruta al archivo XML según la ubicación real en tu equipo.

---

### 3. Verificar la base creada

Puedes abrir la GUI de BaseX (`basexgui`) para comprobar que `registros_db` aparece en el listado y contiene los datos correctamente.

---

### 4. Exportar la base de datos (opcional)

Si deseas exportar la base completa como XML a una carpeta:

```bash
basex -c "EXPORT registros_db C:\ruta\de\destino"
```

---

✅ **Listo**: Ahora puedes usar los scripts PHP (`insertar.php`, `filtrar.php`, etc.) para interactuar con la base de datos desde tu navegador.


basex -c "EXPORT registros_db /ruta/de/destino"
```

> Asegúrate de que BaseX esté ejecutándose en modo servidor (`basexhttp` o `basexserver`) si usas conexiones desde PHP.

