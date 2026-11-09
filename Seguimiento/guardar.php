<?php
/**
 * Inserción relacional con PDO en 3 tablas
 * Universidad de La Salle
 */

require __DIR__ . '/conexion.php';

// 1. Validar que la solicitud sea POST
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    header('Location: index.php?mensaje=' . urlencode('Método de solicitud no permitido.') . '&tipo=error');
    exit;
}

// 2. Extraer y limpiar datos de entrada
$nombre      = trim($_POST['nombre'] ?? '');
$correo      = trim($_POST['correo'] ?? '');
$programa_id = filter_var($_POST['programa_id'] ?? '', FILTER_VALIDATE_INT);
$asignatura  = trim($_POST['asignatura'] ?? '');
$semestre    = trim($_POST['semestre'] ?? '');

// 3. Validar reglas de negocio y tipos
if (
    $nombre === '' ||
    $correo === '' ||
    !$programa_id ||
    $asignatura === '' ||
    $semestre === '' ||
    !filter_var($correo, FILTER_VALIDATE_EMAIL) ||
    strlen($nombre) > 100 ||
    strlen($correo) > 150 ||
    strlen($asignatura) > 100 ||
    strlen($semestre) > 20
) {
    header('Location: index.php?mensaje=' . urlencode('Datos de formulario inválidos o incompletos. Por favor revise los campos.') . '&tipo=error');
    exit;
}

try {
    // 4. Iniciar transacción PDO para asegurar atomicidad entre tablas
    $conexion->beginTransaction();

    // 4.1. Insertar en tabla `estudiantes` (Tabla 2) con FK a `programas` (Tabla 1)
    $sqlEstudiante = 'INSERT INTO estudiantes (nombre, correo, programa_id) 
                      VALUES (:nombre, :correo, :programa_id)';
    $stmtEstudiante = $conexion->prepare($sqlEstudiante);
    $stmtEstudiante->execute([
        ':nombre'      => $nombre,
        ':correo'      => $correo,
        ':programa_id' => $programa_id
    ]);

    // Obtener el ID recién generado para el nuevo estudiante
    $nuevoEstudianteId = $conexion->lastInsertId();

    // 4.2. Insertar en tabla `matriculas` (Tabla 3) vinculada al nuevo estudiante
    $sqlMatricula = 'INSERT INTO matriculas (estudiante_id, asignatura, semestre) 
                     VALUES (:estudiante_id, :asignatura, :semestre)';
    $stmtMatricula = $conexion->prepare($sqlMatricula);
    $stmtMatricula->execute([
        ':estudiante_id' => $nuevoEstudianteId,
        ':asignatura'    => $asignatura,
        ':semestre'      => $semestre
    ]);

    // Confirmar la transacción
    $conexion->commit();

    // Redirigir usando el patrón POST-Redirect-GET
    header('Location: index.php?mensaje=' . urlencode('¡Estudiante y matrícula registrados exitosamente en las 3 tablas!') . '&tipo=success', true, 303);
    exit;

} catch (PDOException $e) {
    // Revertir cualquier cambio si ocurre un error
    if ($conexion->inTransaction()) {
        $conexion->rollBack();
    }

    error_log("Error en inserción: " . $e->getMessage());

    // Código de error 23000 corresponde a violación de restricción UNIQUE (correo repetido) o FK
    if ($e->getCode() == 23000) {
        $errorMsg = 'El correo institucional ya se encuentra registrado o el programa seleccionado no existe.';
    } else {
        $errorMsg = 'Ocurrió un error al procesar el registro en la base de datos.';
    }

    header('Location: index.php?mensaje=' . urlencode($errorMsg) . '&tipo=error');
    exit;
}

