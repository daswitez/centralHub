<?php

use Illuminate\Support\Str;

require __DIR__ . '/vendor/autoload.php';

$schema = $argv[1] ?? null;
$table = $argv[2] ?? null;

if (!$schema || !$table) {
    echo "Usage: php fix_model_class_names.php <schema> <table>\n";
    exit(1);
}

$studlyTable = Str::studly($table);
$studlySchema = Str::studly($schema);

// Handle singularization exceptions
$replacements = [
    'plantum' => 'planta',
    'transportistum' => 'transportista',
    'loteplantum' => 'loteplanta',
    'rutum' => 'ruta',
    'certificadoloteplantum' => 'certificadoloteplanta',
    'certificadoevidencium' => 'certificadoevidencia',
];

$modelName = $studlyTable;
// Check if table name needs fixing for class name
// If I pass 'cat planta', $table is 'planta', $studlyTable is 'Planta'. Correct.
// If I pass 'cat plantum', I should pass 'cat planta'.

$modelPath = __DIR__ . "/app/Models/$studlySchema/$modelName.php";

if (!file_exists($modelPath)) {
    echo "Model not found: $modelPath\n";
    exit(1);
}

$content = file_get_contents($modelPath);

// 1. Fix Class Name
// Ibex generated: class Schema.table extends Model
// Regex to match class definition
// It might be `class Cat.plantum` or `class Cat.departamento`
// We want `class Planta` or `class Departamento`
$content = preg_replace('/class [\w\.]+\s+extends Model/', "class $modelName extends Model", $content);

// 2. Fix Relationships
// We need to replace `\App\Models\SomeTable::class` with `\App\Models\Schema\SomeTable::class`
// We need a map of all tables to their schemas.
$tableMap = [
    'Departamento' => 'Cat',
    'Municipio' => 'Cat',
    'VariedadPapa' => 'Cat',
    'Planta' => 'Cat', // Wait, Cat\Planta? Yes.
    'Cliente' => 'Cat',
    'Transportista' => 'Cat',
    'Almacen' => 'Cat', // Cat\Almacen
    'Productor' => 'Campo',
    'LoteCampo' => 'Campo',
    'SensorLectura' => 'Campo',
    'LotePlanta' => 'Planta',
    'LotePlantaEntradaCampo' => 'Planta',
    'ControlProceso' => 'Planta',
    'LoteSalida' => 'Planta',
    'Ruta' => 'Logistica',
    'RutaPunto' => 'Logistica',
    'Envio' => 'Logistica',
    'EnvioDetalle' => 'Logistica',
    'EnvioDetalleAlmacen' => 'Logistica',
    'Pedido' => 'Comercial', // Conflict! Comercial\Pedido and Almacen\Pedido
    'PedidoDetalle' => 'Comercial', // Conflict!
    'Certificado' => 'Certificacion',
    'CertificadoLoteCampo' => 'Certificacion',
    'CertificadoLotePlanta' => 'Certificacion',
    'CertificadoLoteSalida' => 'Certificacion',
    'CertificadoEnvio' => 'Certificacion',
    'CertificadoEvidencia' => 'Certificacion',
    'CertificadoCadena' => 'Certificacion',
    'Recepcion' => 'Almacen',
    'Inventario' => 'Almacen',
    'Movimiento' => 'Almacen',
];

// Handle conflicts manually or contextually?
// Ibex likely generated `\App\Models\Pedido::class`.
// If we are in `Comercial` schema, maybe it refers to `Comercial\Pedido`?
// Or if it's a relationship, it depends on the foreign key.
// For now, let's replace unique ones.
foreach ($tableMap as $cls => $sch) {
    if ($cls === 'Pedido' || $cls === 'PedidoDetalle') continue; // Skip ambiguous ones for now
    
    $search = "\\App\\Models\\$cls::class";
    $replace = "\\App\\Models\\$sch\\$cls::class";
    $content = str_replace($search, $replace, $content);
}

// Handle ambiguous ones if possible
// If the file contains `\App\Models\Pedido::class`, we might need to check the context.
// But Ibex generates relationships based on FKs.
// If `almacen_id` -> `Almacen`. `Cat\Almacen`.
// If `pedido_id` -> `Pedido`. Could be `Comercial\Pedido` or `Almacen\Pedido`.
// This is tricky.
// However, `Almacen\Pedido` is likely related to `Almacen` stuff.
// `Comercial\Pedido` is related to `Comercial` stuff.
// Let's assume for now we don't fix ambiguous ones automatically, or we guess based on current schema?
// No, that's dangerous.
// Let's just fix the unique ones which covers most cases (like Municipio).

file_put_contents($modelPath, $content);
echo "Fixed class name and relationships for $modelName\n";
