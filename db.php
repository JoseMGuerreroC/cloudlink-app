<?php
function cargarEnv($ruta) {
    if (!file_exists($ruta)) {
        return;
    }
    $lineas = file($ruta, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
    foreach ($lineas as $linea) {
        if (strpos(trim($linea), '#') === 0) continue;
        list($nombre, $valor) = explode('=', $linea, 2);
        $nombre = trim($nombre);
        $valor = trim($valor);
        if (!getenv($nombre)) {
            putenv("$nombre=$valor");
            $_ENV[$nombre] = $valor;
        }
    }
}


cargarEnv(__DIR__ . '/.env');

$host = getenv('DB_HOST') ?: 'localhost';
$db   = getenv('DB_NAME') ?: 'cloud_db';
$user = getenv('DB_USER') ?: 'root';
$pass = getenv('DB_PASS') ?: 'root';
$port = getenv('DB_PORT') ?: '3306';
$driver = getenv('DB_DRIVER') ?: 'mysql';


if ($driver === 'pgsql') {
    $dsn = "pgsql:host=$host;port=$port;dbname=$db";
} else {
    $dsn = "mysql:host=$host;port=$port;dbname=$db;charset=utf8mb4";
}

try {
    $pdo = new PDO($dsn, $user, $pass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $sql = file_get_contents(__DIR__ . '/schema.sql');
    if($driver === 'pgsql') {
        $sql = str_replace("INT AUTO_INCREMENT", "SERIAL", $sql);
    }
    $pdo->exec($sql);
} catch (PDOException $e) {
    echo "<h1>Error de Conexión</h1>";
    echo "<p>No se pudo conectar a la base de datos.</p>";
    echo "<p>Detalles: " . $e->getMessage() . "</p>";
    exit;
}
?>