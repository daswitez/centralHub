<?php

$host = "127.0.0.1";
$port = "5432";
$dbname = "agropapas";
$user = "postgres";
$password = "Slv6001313.";

echo "Intentando conectar a PostgreSQL...\n";
echo "Host: $host\n";
echo "Puerto: $port\n";
echo "Base de datos: $dbname\n";
echo "Usuario: $user\n\n";

try {
    $dsn = "pgsql:host=$host;port=$port;dbname=$dbname";
    $pdo = new PDO($dsn, $user, $password);
    echo "✓ CONEXIÓN EXITOSA!\n";
    
    // Probar una query simple
    $result = $pdo->query("SELECT version()");
    $version = $result->fetch(PDO::FETCH_ASSOC);
    echo "Versión de PostgreSQL: " . $version['version'] . "\n";
    
} catch (PDOException $e) {
    echo "✗ ERROR DE CONEXIÓN:\n";
    echo "Mensaje: " . $e->getMessage() . "\n";
    echo "Código: " . $e->getCode() . "\n";
}
