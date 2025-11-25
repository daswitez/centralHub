<?php

use Illuminate\Support\Str;

require __DIR__ . '/vendor/autoload.php';

$schema = $argv[1] ?? null;
$table = $argv[2] ?? null;

if (!$schema || !$table) {
    echo "Usage: php fix_ibex_generation.php <schema> <table>\n";
    exit(1);
}

$studlyTable = Str::studly($table);
$studlySchema = Str::studly($schema);

$oldModelName = "{$studlySchema}.{$table}";
$newModelName = $studlyTable;

$oldControllerName = "{$studlySchema}.{$table}Controller";
$newControllerName = "{$studlyTable}Controller";

$oldRequestName = "{$studlySchema}.{$table}Request";
$newRequestName = "{$studlyTable}Request";

// Paths
$basePath = __DIR__;
$appPath = $basePath . '/app';
$resourcePath = $basePath . '/resources';

// 1. Fix Model
$oldModelPath = "$appPath/Models/$oldModelName.php";
$newModelDir = "$appPath/Models/$studlySchema";
$newModelPath = "$newModelDir/$newModelName.php";

if (file_exists($oldModelPath)) {
    if (!is_dir($newModelDir)) mkdir($newModelDir, 0755, true);
    
    $content = file_get_contents($oldModelPath);
    $content = str_replace("namespace App\Models;", "namespace App\Models\\$studlySchema;", $content);
    $content = str_replace("class $oldModelName", "class $newModelName", $content);
    // Fix table name if needed, but Ibex usually puts protected $table = 'schema.table'; which is correct.
    
    file_put_contents($newModelPath, $content);
    unlink($oldModelPath);
    echo "Fixed Model: $newModelPath\n";
} else {
    echo "Model not found: $oldModelPath\n";
}

// 2. Fix Controller
$oldControllerPath = "$appPath/Http/Controllers/$oldControllerName.php";
$newControllerDir = "$appPath/Http/Controllers/$studlySchema";
$newControllerPath = "$newControllerDir/$newControllerName.php";

if (file_exists($oldControllerPath)) {
    if (!is_dir($newControllerDir)) mkdir($newControllerDir, 0755, true);
    
    $content = file_get_contents($oldControllerPath);
    $content = str_replace("namespace App\Http\Controllers;", "namespace App\Http\Controllers\\$studlySchema;", $content);
    $content = str_replace("class $oldControllerName", "class $newControllerName", $content);
    $content = str_replace("use App\Models\\$oldModelName;", "use App\Models\\$studlySchema\\$newModelName;", $content);
    $content = str_replace("use App\Http\Requests\\$oldRequestName;", "use App\Http\Requests\\$studlySchema\\$newRequestName;", $content);
    
    // Fix view paths: 'cat.departamento.index' -> 'cat.departamento.index' (already correct usually)
    // But if it generated 'Cat.departamento.index', we might want 'cat.departamento.index'.
    // Ibex uses the name passed. If we passed 'cat.departamento', it uses 'cat.departamento'.
    
    file_put_contents($newControllerPath, $content);
    unlink($oldControllerPath);
    echo "Fixed Controller: $newControllerPath\n";
} else {
    echo "Controller not found: $oldControllerPath\n";
}

// 3. Fix Request
$oldRequestPath = "$appPath/Http/Requests/$oldRequestName.php";
$newRequestDir = "$appPath/Http/Requests/$studlySchema";
$newRequestPath = "$newRequestDir/$newRequestName.php";

if (file_exists($oldRequestPath)) {
    if (!is_dir($newRequestDir)) mkdir($newRequestDir, 0755, true);
    
    $content = file_get_contents($oldRequestPath);
    $content = str_replace("namespace App\Http\Requests;", "namespace App\Http\Requests\\$studlySchema;", $content);
    $content = str_replace("class $oldRequestName", "class $newRequestName", $content);
    
    file_put_contents($newRequestPath, $content);
    unlink($oldRequestPath);
    echo "Fixed Request: $newRequestPath\n";
} else {
    echo "Request not found: $oldRequestPath\n";
}

// 4. Fix Views
$oldViewDir = "$resourcePath/views/$schema.$table";
$newViewDir = "$resourcePath/views/$schema/$table";

if (is_dir($oldViewDir)) {
    if (!is_dir(dirname($newViewDir))) mkdir(dirname($newViewDir), 0755, true);
    rename($oldViewDir, $newViewDir);
    echo "Fixed Views: $newViewDir\n";
} else {
    echo "Views not found: $oldViewDir\n";
}
