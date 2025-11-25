<?php

use Illuminate\Support\Str;

require __DIR__ . '/vendor/autoload.php';

$schema = $argv[1] ?? null;
$table = $argv[2] ?? null;

if (!$schema || !$table) {
    echo "Usage: php fix_model_tables.php <schema> <table>\n";
    exit(1);
}

$studlyTable = Str::studly($table);
$studlySchema = Str::studly($schema);

// Handle singularization exceptions if any (reuse from previous script if needed)
$replacements = [
    'plantum' => 'planta',
    'transportistum' => 'transportista',
    'loteplantum' => 'loteplanta',
    'rutum' => 'ruta',
    'certificadoloteplantum' => 'certificadoloteplanta',
    'certificadoevidencium' => 'certificadoevidencia',
];

$modelName = $studlyTable;
// Check if table name needs fixing for class name (already fixed in file system)
// But here we need to find the file.
// The file is at app/Models/$studlySchema/$studlyTable.php
// Wait, if I passed 'cat.planta', $table is 'planta'. $studlyTable is 'Planta'.
// If I passed 'cat.plantum' (which I shouldn't have, but Ibex did), I fixed it to 'Planta'.
// So I should pass the CORRECT table name to this script.

$modelPath = __DIR__ . "/app/Models/$studlySchema/$modelName.php";

if (!file_exists($modelPath)) {
    echo "Model not found: $modelPath\n";
    // Try singular/plural variations if needed?
    // For now, assume I pass the correct name.
    exit(1);
}

$content = file_get_contents($modelPath);

// Check if $table is already defined
if (strpos($content, 'protected $table') !== false) {
    echo "Table already defined in $modelName\n";
    // Optional: overwrite it to be sure?
    // Let's overwrite it to ensure it has the schema.
    $content = preg_replace('/protected \$table = .*?;/', "protected \$table = '$schema.$table';", $content);
} else {
    // Add it after class declaration
    // class ModelName extends Model {
    $content = preg_replace('/class ' . $modelName . ' extends Model\s*\{/', "class $modelName extends Model\n{\n    protected \$table = '$schema.$table';", $content);
}

// Also fix primary key if needed? Ibex usually detects it.
// But let's stick to table name first.

file_put_contents($modelPath, $content);
echo "Fixed table property for $modelName: $schema.$table\n";
