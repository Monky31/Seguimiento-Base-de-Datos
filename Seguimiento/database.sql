-- =====================================================================
-- Base de datos: universidad_db
-- Sistema de 3 Tablas Relacionadas: programas, estudiantes, matriculas
-- Asignatura: Bases de Datos - Universidad de La Salle
-- =====================================================================

-- 1. Crear base de datos
CREATE DATABASE IF NOT EXISTS universidad_db
  CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

USE universidad_db;

-- 2. Tabla 1: programas (Catálogo de programas académicos)
CREATE TABLE IF NOT EXISTS programas (
  id INT AUTO_INCREMENT PRIMARY KEY,
  nombre VARCHAR(100) NOT NULL UNIQUE,
  facultad VARCHAR(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 3. Tabla 2: estudiantes (Información del estudiante vinculada al programa)
CREATE TABLE IF NOT EXISTS estudiantes (
  id INT AUTO_INCREMENT PRIMARY KEY,
  nombre VARCHAR(100) NOT NULL,
  correo VARCHAR(150) NOT NULL UNIQUE,
  programa_id INT NOT NULL,
  creado_el TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  CONSTRAINT fk_estudiantes_programa
    FOREIGN KEY (programa_id) REFERENCES programas(id)
    ON DELETE RESTRICT ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 4. Tabla 3: matriculas (Asignaturas inscritas por cada estudiante)
CREATE TABLE IF NOT EXISTS matriculas (
  id INT AUTO_INCREMENT PRIMARY KEY,
  estudiante_id INT NOT NULL,
  asignatura VARCHAR(100) NOT NULL,
  semestre VARCHAR(20) NOT NULL,
  fecha_registro TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  CONSTRAINT fk_matriculas_estudiante
    FOREIGN KEY (estudiante_id) REFERENCES estudiantes(id)
    ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 5. Usuario específico de la aplicación con mínimo privilegio
CREATE USER IF NOT EXISTS 'app_lasalle'@'localhost' IDENTIFIED BY 'Cambiar_Esta_Clave_2026!';
GRANT SELECT, INSERT ON universidad_db.* TO 'app_lasalle'@'localhost';
FLUSH PRIVILEGES;

-- 6. Inserción de datos iniciales para pruebas
INSERT INTO programas (id, nombre, facultad) VALUES
(1, 'Ingeniería de Sistemas', 'Facultad de Ingeniería'),
(2, 'Ingeniería Industrial', 'Facultad de Ingeniería'),
(3, 'Administración de Empresas', 'Ciencias Económicas y Sociales')
ON DUPLICATE KEY UPDATE nombre = VALUES(nombre);

INSERT INTO estudiantes (id, nombre, correo, programa_id) VALUES
(1, 'Ana Torres', 'ana@example.com', 1),
(2, 'Luis Pérez', 'luis@example.com', 2)
ON DUPLICATE KEY UPDATE correo = VALUES(correo);

INSERT INTO matriculas (estudiante_id, asignatura, semestre) VALUES
(1, 'Bases de Datos', '2026-1'),
(1, 'Programación Web', '2026-1'),
(2, 'Investigación de Operaciones', '2026-1');

