<?php
require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use Illuminate\Support\Facades\DB;

echo "=== Verificando esquema de logistica.ruta ===\n\n";

try {
    $columns = DB::select("
        SELECT column_name, data_type, is_nullable
        FROM information_schema.columns
        WHERE table_schema = 'logistica' AND table_name = 'ruta'
        ORDER BY ordinal_position
    ");
    
    echo "Columnas de logistica.ruta:\n";
    foreach ($columns as $col) {
        echo "  - {$col->column_name} ({$col->data_type}) " . ($col->is_nullable === 'YES' ? 'NULL' : 'NOT NULL') . "\n";
    }
    
    echo "\n=== Verificando esquema de logistica.envio ===\n\n";
    
    $columns2 = DB::select("
        SELECT column_name, data_type, is_nullable
        FROM information_schema.columns
        WHERE table_schema = 'logistica' AND table_name = 'envio'
        ORDER BY ordinal_position
    ");
    
    echo "Columnas de logistica.envio:\n";
    foreach ($columns2 as $col) {
        echo "  - {$col->column_name} ({$col->data_type}) " . ($col->is_nullable === 'YES' ? 'NULL' : 'NOT NULL') . "\n";
    }
    
} catch (\Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
}
