<?php

use Illuminate\Support\Str;

require __DIR__ . '/vendor/autoload.php';

$schema = $argv[1] ?? null;
$table = $argv[2] ?? null;

if (!$schema || !$table) {
    echo "Usage: php fix_blade_views.php <schema> <table>\n";
    exit(1);
}

// Logic to match fix_ibex_body.php replacements
// In fix_ibex_body.php, we used:
// $shortReplace = explode('\\', $replace)[1]; // e.g. Planta
// $lowerReplace = strtolower($shortReplace); // e.g. planta
// So we want to replace $schema.table with $table (lowercase)

// We need the "short replace" name.
// Since we don't have the map here, we can derive it or use the same map.
// Let's replicate the map logic or just use the table name if it matches.

// Map from fix_ibex_body.php
$replacements = [
    'Cat.plantum' => 'Cat\\Planta',
    'Cat.transportistum' => 'Cat\\Transportista',
    'Planta.loteplantum' => 'Planta\\LotePlanta',
    'Logistica.rutum' => 'Logistica\\Ruta',
    'Certificacion.certificadoloteplantum' => 'Certificacion\\CertificadoLotePlanta',
    'Certificacion.certificadoevidencium' => 'Certificacion\\CertificadoEvidencia',
    'Planta.loteplantaEntradacampo' => 'Planta\\LoteplantaEntradacampo',
    // Add others if needed, but mostly it's just StudlyCase of table
];

$studlyTable = Str::studly($table);
$studlySchema = Str::studly($schema);
$searchKey = "$studlySchema.$table"; // e.g. Cat.departamento

// Check if we have a special replacement
$targetClass = $studlyTable; // Default
foreach ($replacements as $key => $val) {
    // Check for match. $key might be 'Cat.plantum'. $searchKey might be 'Cat.planta' if passed 'planta'.
    // Wait, Ibex generated 'plantum'. If I pass 'planta', I need to know Ibex used 'plantum'.
    // But the view file is in `resources/views/cat/planta` (renamed by me).
    // The CONTENT has `$cat.plantum`?
    // Let's check the error: `index.blade.php:46 ... $cat.departamentos`
    // So it uses the schema.table format.
    
    // If I pass 'planta', $table is 'planta'.
    // If Ibex used 'plantum', the variable might be `$cat.plantum`.
    // But I renamed the view folder to `planta`.
    
    // Let's assume the variable name matches what was in the controller BEFORE I fixed it.
    // In controller it was `$cat.plantum`.
    // So in view it is `$cat.plantum`.
    
    // But I don't know "plantum" from "planta" easily without the map.
    // However, I can just regex replace `$schema.[\w]+` with something? No, too dangerous.
    
    // Let's use the map to find the "Ibex name" if possible, or just handle the standard case.
    // Standard case: table 'departamento' -> variable '$cat.departamento'.
    // Replacement: '$departamento'.
}

// Let's try to handle the standard case first.
$variableName = strtolower($studlyTable); // departamento
$pluralVariableName = Str::plural($variableName); // departamentos

$viewDir = __DIR__ . "/resources/views/$schema/$table";

if (!is_dir($viewDir)) {
    echo "View directory not found: $viewDir\n";
    exit(1);
}

$files = glob("$viewDir/*.blade.php");

foreach ($files as $file) {
    $content = file_get_contents($file);
    
    // Replace plural first: $cat.departamentos -> $departamentos
    // Regex to match $schema.tablePlural
    // We don't know exactly what Ibex used for plural if it's irregular.
    // But usually Str::plural($table).
    
    // Strategy:
    // 1. Replace `$schema.table` with `$variableName`
    // 2. Replace `$schema.plural(table)` with `$pluralVariableName`
    
    // We need to be careful about matching.
    // $cat.departamento should match.
    // $cat.departamentos should match.
    
    // Let's try a generic regex for this schema?
    // \$cat\.([a-zA-Z0-9_]+)
    // And replace with $1 (lowercased?)
    
    // If I have `$cat.departamentos`, $1 is `departamentos`.
    // I want `$departamentos`.
    // If I have `$cat.departamento`, $1 is `departamento`.
    // I want `$departamento`.
    
    // What about `$cat.plantum`?
    // $1 is `plantum`.
    // I want `$planta`.
    // This requires the mapping.
    
    // Let's use the replacements map to fix specific singularization issues first.
    // 'Cat.plantum' => 'Cat\Planta'
    // Variable: $cat.plantum -> $planta
    
    // Special handling for known issues
    $specialVars = [
        'cat.plantum' => 'planta',
        'cat.transportistum' => 'transportista',
        'planta.loteplantum' => 'loteplanta',
        'logistica.rutum' => 'ruta',
        'certificacion.certificadoloteplantum' => 'certificadoloteplanta',
        'certificacion.certificadoevidencium' => 'certificadoevidencia',
        'planta.loteplantaEntradacampo' => 'loteplantaentradacampo', // camelCase issue
    ];
    
    foreach ($specialVars as $bad => $good) {
        // Singular
        $content = str_replace("\$$bad", "\$$good", $content);
        // Plural?
        // Ibex might have used Str::plural('plantum') -> 'plantums'?
        // Or 'plantas'?
        // If it used 'plantums', we want 'plantas'.
        // Let's guess standard pluralization of the bad name.
        $badPlural = Str::plural(explode('.', $bad)[1]);
        $goodPlural = Str::plural($good);
        $schemaPrefix = explode('.', $bad)[0];
        
        $content = str_replace("\$$schemaPrefix.$badPlural", "\$$goodPlural", $content);
    }
    
    // Generic replacement for the rest
    // Match $schema.something
    // Replace with $something (lowercased)
    // We use a callback to ensure we lowercase it.
    $content = preg_replace_callback('/\$' . $schema . '\.([a-zA-Z0-9_]+)/', function($matches) {
        // $matches[1] is the table name part (e.g. 'departamentos')
        // We want to return '$' + strtolower($matches[1])
        return '$' . strtolower($matches[1]);
    }, $content);
    
    // Also fix property access on the variable?
    // $cat.departamento->id -> $departamento->id
    // The regex above handles the variable part. The ->id part remains untouched.
    // So $cat.departamento becomes $departamento.
    // $departamento->id is valid.
    
    file_put_contents($file, $content);
    echo "Fixed $file\n";
}
