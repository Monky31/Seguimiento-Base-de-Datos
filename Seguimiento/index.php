<?php
require __DIR__ . '/conexion.php';

// Obtener la lista de programas para el formulario (Tabla 1)
$consultaProgramas = $conexion->query('SELECT id, nombre, facultad FROM programas ORDER BY nombre ASC');
$programas = $consultaProgramas->fetchAll();

// Consulta relacional con JOIN entre las 3 tablas (estudiantes, programas y matriculas)
$consultaRegistros = $conexion->query(
    'SELECT 
        e.id AS estudiante_id,
        e.nombre AS estudiante_nombre,
        e.correo AS estudiante_correo,
        p.nombre AS programa_nombre,
        p.facultad AS programa_facultad,
        m.asignatura AS matricula_asignatura,
        m.semestre AS matricula_semestre,
        m.fecha_registro
     FROM estudiantes e
     INNER JOIN programas p ON e.programa_id = p.id
     LEFT JOIN matriculas m ON m.estudiante_id = e.id
     ORDER BY e.id DESC, m.id DESC'
);
$registros = $consultaRegistros->fetchAll();

$mensaje = $_GET['mensaje'] ?? '';
$tipoMensaje = $_GET['tipo'] ?? '';
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sistema de Gestión Académica - La Salle</title>
    <style>
        * { box-sizing: border-box; margin: 0; padding: 0; }
        body {
            font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, Helvetica, Arial, sans-serif;
            background-color: #f4f6f9;
            color: #1f2937;
            padding: 24px;
            line-height: 1.5;
        }
        .container {
            max-width: 1100px;
            margin: 0 auto;
        }
        header {
            background: linear-gradient(135deg, #1e3a8a, #2563eb);
            color: white;
            padding: 24px 32px;
            border-radius: 12px;
            box-shadow: 0 4px 6px -1px rgba(0,0,0,0.1);
            margin-bottom: 24px;
        }
        header h1 { font-size: 24px; margin-bottom: 6px; }
        header p { color: #bfdbfe; font-size: 14px; }
        
        .alert {
            padding: 12px 16px;
            border-radius: 8px;
            margin-bottom: 20px;
            font-size: 14px;
            font-weight: 500;
        }
        .alert-success { background-color: #def7ec; color: #03543f; border: 1px solid #bcf0da; }
        .alert-danger { background-color: #fde8e8; color: #9b1c1c; border: 1px solid #fbd5d5; }
        
        .grid {
            display: grid;
            grid-template-columns: 1fr;
            gap: 24px;
        }
        @media (min-width: 900px) {
            .grid {
                grid-template-columns: 360px 1fr;
            }
        }
        
        .card {
            background: white;
            border-radius: 10px;
            padding: 24px;
            box-shadow: 0 1px 3px rgba(0,0,0,0.1);
            border: 1px solid #e5e7eb;
        }
        .card h2 {
            font-size: 18px;
            margin-bottom: 16px;
            color: #111827;
            border-bottom: 2px solid #e5e7eb;
            padding-bottom: 8px;
        }
        
        .form-group {
            margin-bottom: 16px;
        }
        label {
            display: block;
            font-size: 13px;
            font-weight: 600;
            color: #374151;
            margin-bottom: 6px;
        }
        input, select {
            width: 100%;
            padding: 10px 12px;
            border: 1px solid #d1d5db;
            border-radius: 6px;
            font-size: 14px;
            transition: border-color 0.15s ease-in-out;
        }
        input:focus, select:focus {
            outline: none;
            border-color: #2563eb;
            box-shadow: 0 0 0 3px rgba(37, 99, 235, 0.15);
        }
        
        button.btn-primary {
            background-color: #2563eb;
            color: white;
            border: none;
            padding: 12px 20px;
            border-radius: 6px;
            font-size: 14px;
            font-weight: 600;
            cursor: pointer;
            width: 100%;
            transition: background-color 0.15s ease;
        }
        button.btn-primary:hover {
            background-color: #1d4ed8;
        }

        .table-responsive {
            overflow-x: auto;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            font-size: 14px;
            text-align: left;
        }
        th {
            background-color: #f9fafb;
            color: #4b5563;
            font-weight: 600;
            padding: 12px 14px;
            border-bottom: 1px solid #e5e7eb;
        }
        td {
            padding: 12px 14px;
            border-bottom: 1px solid #f3f4f6;
            vertical-align: middle;
        }
        tr:hover {
            background-color: #f8fafc;
        }
        
        .badge {
            display: inline-block;
            padding: 4px 8px;
            font-size: 12px;
            font-weight: 600;
            border-radius: 9999px;
            background-color: #e0e7ff;
            color: #3730a3;
        }
        .badge-subject {
            background-color: #ecfdf5;
            color: #065f46;
        }
        .badge-none {
            background-color: #f3f4f6;
            color: #6b7280;
        }
    </style>
</head>
<body>

<div class="container">
    <header>
        <h1>Universidad de La Salle · Portal Académico</h1>
        <p>Práctica de Bases de Datos con PHP, MySQL y PDO (Integración de 3 Tablas: Programas, Estudiantes y Matrículas)</p>
    </header>

    <?php if ($mensaje): ?>
        <div class="alert <?= $tipoMensaje === 'error' ? 'alert-danger' : 'alert-success' ?>">
            <?= htmlspecialchars($mensaje, ENT_QUOTES, 'UTF-8') ?>
        </div>
    <?php endif; ?>

    <div class="grid">
        <!-- Formulario para agregar estudiante y matricular asignatura -->
        <div class="card">
            <h2>Nuevo Registro Académico</h2>
            <form action="guardar.php" method="POST">
                <div class="form-group">
                    <label for="nombre">Nombre Completo *</label>
                    <input type="text" id="nombre" name="nombre" placeholder="Ej: Camilo Sánchez" required maxlength="100">
                </div>

                <div class="form-group">
                    <label for="correo">Correo Institucional *</label>
                    <input type="email" id="correo" name="correo" placeholder="ejemplo@unisalle.edu.co" required maxlength="150">
                </div>

                <div class="form-group">
                    <label for="programa_id">Programa Académico (Tabla 1) *</label>
                    <select id="programa_id" name="programa_id" required>
                        <option value="">-- Seleccione una carrera --</option>
                        <?php foreach ($programas as $prog): ?>
                            <option value="<?= (int) $prog['id'] ?>">
                                <?= htmlspecialchars($prog['nombre'], ENT_QUOTES, 'UTF-8') ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div class="form-group">
                    <label for="asignatura">Asignatura a Matricular (Tabla 3) *</label>
                    <input type="text" id="asignatura" name="asignatura" placeholder="Ej: Bases de Datos II" required maxlength="100">
                </div>

                <div class="form-group">
                    <label for="semestre">Semestre / Periodo *</label>
                    <input type="text" id="semestre" name="semestre" value="2026-1" required maxlength="20">
                </div>

                <button type="submit" class="btn-primary">Registrar en las 3 Tablas</button>
            </form>
        </div>

        <!-- Tabla con resultados del JOIN -->
        <div class="card">
            <h2>Estudiantes Matriculados (Vista Relacional)</h2>
            <div class="table-responsive">
                <table>
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Estudiante</th>
                            <th>Correo</th>
                            <th>Programa</th>
                            <th>Materia Matriculada</th>
                            <th>Semestre</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (empty($registros)): ?>
                            <tr>
                                <td colspan="6" style="text-align: center; color: #6b7280; padding: 24px;">
                                    No hay registros almacenados. Ingresa uno mediante el formulario.
                                </td>
                            </tr>
                        <?php else: ?>
                            <?php foreach ($registros as $fila): ?>
                                <tr>
                                    <td><strong>#<?= (int) $fila['estudiante_id'] ?></strong></td>
                                    <td><?= htmlspecialchars($fila['estudiante_nombre'], ENT_QUOTES, 'UTF-8') ?></td>
                                    <td><?= htmlspecialchars($fila['estudiante_correo'], ENT_QUOTES, 'UTF-8') ?></td>
                                    <td>
                                        <span class="badge">
                                            <?= htmlspecialchars($fila['programa_nombre'], ENT_QUOTES, 'UTF-8') ?>
                                        </span>
                                    </td>
                                    <td>
                                        <?php if (!empty($fila['matricula_asignatura'])): ?>
                                            <span class="badge badge-subject">
                                                <?= htmlspecialchars($fila['matricula_asignatura'], ENT_QUOTES, 'UTF-8') ?>
                                            </span>
                                        <?php else: ?>
                                            <span class="badge badge-none">Sin materias</span>
                                        <?php endif; ?>
                                    </td>
                                    <td><?= htmlspecialchars($fila['matricula_semestre'] ?? 'N/A', ENT_QUOTES, 'UTF-8') ?></td>
                                </tr>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

</body>
</html>

