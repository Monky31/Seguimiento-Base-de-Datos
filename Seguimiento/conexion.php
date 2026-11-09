<?php
/**
 * Conexión a MySQL mediante PDO
 * Universidad de La Salle - Laboratorio de Bases de Datos
 */

$servidor   = 'localhost';
$puerto     = '3306';
$baseDatos  = 'universidad_db';
$usuario    = 'app_lasalle';
$contrasena = 'Cambiar_Esta_Clave_2026!';

// Nota: Si aún no creaste el usuario 'app_lasalle', puedes usar temporalmente:
// $usuario = 'root';
// $contrasena = '';

$dsn = "mysql:host=$servidor;port=$puerto;dbname=$baseDatos;charset=utf8mb4";

try {
    $conexion = new PDO($dsn, $usuario, $contrasena, [
        PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        PDO::ATTR_EMULATE_PREPARES   => false
    ]);
} catch (PDOException $e) {
    error_log("Error de conexión PDO: " . $e->getMessage());
    http_response_code(500);
    ?>
    <!DOCTYPE html>
    <html lang="es">
    <head>
        <meta charset="UTF-8">
        <title>Error de conexión</title>
        <style>
            body { font-family: Arial, sans-serif; background: #fdf2f2; color: #991b1b; padding: 40px; }
            .box { background: white; border: 1px solid #f87171; border-radius: 8px; padding: 25px; max-width: 600px; margin: auto; }
            code { background: #fee2e2; padding: 2px 6px; border-radius: 4px; }
        </style>
    </head>
    <body>
        <div class="box">
            <h2>⚠️ Error al conectar con la base de datos</h2>
            <p>No fue posible conectar con MySQL mediante PDO.</p>
            <ul>
                <li>Verifica que el servicio <strong>MySQL</strong> esté encendido (Start) en el panel de XAMPP.</li>
                <li>Verifica que la base de datos <code>universidad_db</code> y el usuario <code>app_lasalle</code> existan (ejecuta <code>database.sql</code> en phpMyAdmin).</li>
                <li>Si estás usando el usuario por defecto de XAMPP, edita <code>conexion.php</code> con usuario <code>root</code> y contraseña vacía.</li>
            </ul>
        </div>
    </body>
    </html>
    <?php
    exit;
}

