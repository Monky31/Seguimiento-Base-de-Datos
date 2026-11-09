# Seguimiento-Base-de-Datos Sofia Lopez-Mateo Lastra
# Proyecto Académico: Solución en 3 Tablas (MySQL + PHP + PDO)
**Universidad de La Salle** · Asignatura: Bases de Datos

Este proyecto amplía el laboratorio base creando una aplicación web completa y normalizada en **3 tablas relacionales**:
1. `programas` (Catálogo maestro de carreras)
2. `estudiantes` (Información de alumnos con clave foránea a `programas`)
3. `matriculas` (Asignaturas inscritas por estudiante con clave foránea a `estudiantes`)

---

## 📁 Archivos del Proyecto
* [`database.sql`](database.sql): Script SQL para crear la base de datos `universidad_db`, las 3 tablas relacionales con llaves foráneas, el usuario con mínimo privilegio `app_lasalle` y datos de prueba.
* [`conexion.php`](conexion.php): Conexión PDO con manejo seguro de excepciones (`PDOException`), `utf8mb4` y control de errores.
* [`index.php`](index.php): Interfaz web con consulta `INNER JOIN` / `LEFT JOIN` a las 3 tablas, tabla de resultados y formulario dinámico (menú desplegable de programas).
* [`guardar.php`](guardar.php): Controlador de inserción que implementa una transacción atómica (`beginTransaction()`, `commit()`, `rollBack()`) y sentencias preparadas contra inyección SQL.

---

## 🚀 Pasos para Poner en Marcha en XAMPP

### Paso 1: Copiar los archivos a `htdocs`
Copia la carpeta de este proyecto (`Seguimiento`) dentro de:
`C:\xampp\htdocs\lasalle_estudiantes`

### Paso 2: Iniciar XAMPP
1. Abre **XAMPP Control Panel**.
2. Presiona **Start** en **Apache** y en **MySQL**.

### Paso 3: Importar la Base de Datos
1. Ingresa a `http://localhost/phpmyadmin/` en tu navegador.
2. Haz clic en la pestaña **SQL** (o en **Importar**).
3. Pega y ejecuta el contenido del archivo [`database.sql`](database.sql).

### Paso 4: Abrir la Aplicación
Ingresa a tu navegador en:
👉 `http://localhost/lasalle_estudiantes/` (o `http://localhost:8080/lasalle_estudiantes/` si tu Apache usa el puerto 8080).

