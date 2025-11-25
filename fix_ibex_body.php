<?php

use Illuminate\Support\Str;

require __DIR__ . '/vendor/autoload.php';

$schema = $argv[1] ?? null;
$table = $argv[2] ?? null;

if (!$schema || !$table) {
    echo "Usage: php fix_ibex_body.php <schema> <table>\n";
    exit(1);
}

$studlyTable = Str::studly($table);
$studlySchema = Str::studly($schema);
$camelTable = Str::camel($studlyTable);
$camelTablePlural = Str::plural($camelTable);

$controllerName = "{$studlyTable}Controller";
$controllerPath = __DIR__ . "/app/Http/Controllers/$studlySchema/$controllerName.php";

if (!file_exists($controllerPath)) {
    echo "Controller not found: $controllerPath\n";
    exit(1);
}

$content = file_get_contents($controllerPath);

// Fix variables with dots: $cat.departamento -> $departamento
$content = preg_replace('/\$' . $schema . '\.(' . $table . 's?)/', '$$1', $content);
// Fix camelCase variables: $planta.loteplantaEntradacampo -> $loteplantaEntradacampo
$content = preg_replace('/\$' . $schema . '\.(' . $camelTable . 's?)/', '$$1', $content);

// Add base Controller import
if (!str_contains($content, 'use App\Http\Controllers\Controller;')) {
    $content = preg_replace('/namespace App\\\\Http\\\\Controllers\\\\[\w]+;/', '$0' . "\n\nuse App\Http\Controllers\Controller;", $content);
}
// Also handle plural if Ibex used plural table name in variable
// Ibex uses Str::plural($table) for list variable.
// If table is 'departamento', list is 'departamentos'.
// Variable was $cat.departamentos.
// Regex above matches $cat.departamento and $cat.departamentos if $table is 'departamento'.
// Wait, $table is 'departamento'. $1 will be 'departamento' or 'departamentos' if I use \w+.
// But I want to be specific.
// $cat.departamento -> $departamento
// $cat.departamentos -> $departamentos

// Fix class usage: Cat.departamento:: -> Departamento::
$content = str_replace("$studlySchema.$table::", "$studlyTable::", $content);
$content = str_replace("new $studlySchema.$table", "new $studlyTable", $content);

// Fix type hints: Cat.departamento $var -> Departamento $var
$content = str_replace("$studlySchema.$table ", "$studlyTable ", $content);
$content = str_replace("$studlySchema.$table\n", "$studlyTable\n", $content);
$content = str_replace("$studlySchema.$table)", "$studlyTable)", $content);
$content = str_replace("$studlySchema.$table,", "$studlyTable,", $content);

// Fix known singularization issues
$replacements = [
    'Cat.plantum' => 'Cat\\Planta',
    'Cat.transportistum' => 'Cat\\Transportista',
    'Planta.loteplantum' => 'Planta\\LotePlanta',
    'Logistica.rutum' => 'Logistica\\Ruta',
    'Certificacion.certificadoloteplantum' => 'Certificacion\\CertificadoLotePlanta',
    'Certificacion.certificadoevidencium' => 'Certificacion\\CertificadoEvidencia',
    'Cat.plantumRequest' => 'Cat\\PlantaRequest',
    'Cat.transportistumRequest' => 'Cat\\TransportistaRequest',
    'Planta.loteplantumRequest' => 'Planta\\LotePlantaRequest',
    'Logistica.rutumRequest' => 'Logistica\\RutaRequest',
    'Certificacion.certificadoloteplantumRequest' => 'Certificacion\\CertificadoLotePlantaRequest',
    'Certificacion.certificadoevidenciumRequest' => 'Certificacion\\CertificadoEvidenciaRequest',
    'Planta.loteplantaEntradacampo' => 'Planta\\LoteplantaEntradacampo',
    'Planta.loteplantaEntradacampoRequest' => 'Planta\\LoteplantaEntradacampoRequest',
];

foreach ($replacements as $search => $replace) {
    $content = str_replace("App\\Models\\$search", "App\\Models\\$replace", $content);
    $content = str_replace("App\\Http\\Requests\\$search", "App\\Http\\Requests\\$replace", $content);
    
    // Fix class names in controllers
    // Search: Cat.plantum -> Replace: Cat\Planta (we need just Planta)
    // $replace is Cat\Planta. explode('\\', $replace)[1] is Planta.
    $shortReplace = explode('\\', $replace)[1];
    $shortSearch = explode('.', $search)[1]; // plantum
    // Ibex generated class Schema.tableController
    // So class Cat.plantumController
    $content = str_replace("class $studlySchema.{$shortSearch}Controller", "class {$shortReplace}Controller", $content);

    // Fix variables: $cat.plantum -> $planta
    // $search is Cat.plantum. Lowercase is cat.plantum.
    $lowerSearch = strtolower($search); // cat.plantum
    $lowerReplace = strtolower($shortReplace); // planta
    // Regex to match $cat.plantum
    $content = str_replace("\$$lowerSearch", "\$$lowerReplace", $content);
    // Also plural? $cat.plantums? Ibex might use Str::plural('plantum') -> plantums?
    // Let's assume singular for now as per error.

    // Fix instantiation: new Cat.plantum() -> new Planta()
    $content = str_replace("new $search", "new $shortReplace", $content);
    $content = str_replace("$search::", "$shortReplace::", $content);

    // Fix request type hint in method signature: Cat.plantumRequest -> PlantaRequest
    // $search is Cat.plantumRequest.
    // $shortSearch is plantumRequest.
    // $shortReplace is PlantaRequest.
    // Code has Cat.plantumRequest (StudlySchema.shortSearch).
    $content = str_replace("$studlySchema.$shortSearch", "$shortReplace", $content);
}
// Ibex generated store(Cat.departamentoRequest $request)
// My previous script renamed the class to DepartamentoRequest and imported it.
// But the type hint in method signature might still be Cat.departamentoRequest if I didn't fix it.
// My previous script fixed `use ...` but did it fix method signature?
// `str_replace("class $oldControllerName", ...)`
// It didn't fix the body content!
$content = str_replace("$studlySchema.{$table}Request", "{$studlyTable}Request", $content);

// Fix compact: compact('cat.departamento') -> compact('departamento')
$content = str_replace("'$schema.$table'", "'$camelTable'", $content);
$content = str_replace("'$schema." . Str::plural($table) . "'", "'$camelTablePlural'", $content);

// Fix route names in Redirect: Redirect::route('cat.departamentos.index')
// These are strings, so they are fine! 'cat.departamentos.index' is a valid route name.
// But I need to ensure my route definitions match.
// I defined `Route::resource('departamentos', ...)` inside `cat` prefix.
// So route name is `cat.departamentos.index`.
// Ibex generated `Redirect::route('cat.departamentos.index')`.
// So this is actually correct! I don't need to change route names if they match.

// Fix view names: view('cat.departamento.index')
// I moved views to `resources/views/cat/departamento`.
// Laravel view finder handles `cat.departamento.index` as `cat/departamento/index`.
// So this is also correct!

// Fix variable usage in view()
// view(..., compact('cat.departamentos')) -> compact('departamentos')
// Handled by compact fix above.

// Fix $cat.departamento->update(...) -> $departamento->update(...)
// Handled by variable fix.

// Fix type hint in update(): update(Request $request, Cat.departamento $cat.departamento)
// -> update(Request $request, Departamento $departamento)
// Class type hint handled by Class fix.
// Variable handled by Variable fix.

file_put_contents($controllerPath, $content);
echo "Fixed body of $controllerPath\n";
