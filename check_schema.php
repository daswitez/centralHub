<?php
require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use Illuminate\Support\Facades\DB;

echo "=== VERIFICACIÓN DE ESQUEMA PARA TRAZABILIDAD ===\n\n";

// Verificar tablas del esquema campo
echo "--- ESQUEMA CAMPO ---\n";
$campoTables = DB::select("
    SELECT table_name 
    FROM information_schema.tables 
    WHERE table_schema = 'campo'
    ORDER BY table_name
");

foreach ($campoTables as $table) {
    echo "  ✓ {$table->table_name}\n";
}

// Verificar si existe tabla de solicitudes
echo "\n¿Existe campo.solicitud_produccion? ";
$solicitudExists = DB::select("
    SELECT EXISTS (
        SELECT 1 FROM information_schema.tables 
        WHERE table_schema = 'campo' AND table_name = 'solicitud_produccion'
    ) as existe
");
echo $solicitudExists[0]->existe ? "SÍ\n" : "NO\n";

// Verificar columnas de transportista
echo "\n--- COLUMNAS DE cat.transportista ---\n";
$transportistaColumns = DB::select("
    SELECT column_name, data_type 
    FROM information_schema.columns 
    WHERE table_schema = 'cat' AND table_name = 'transportista'
    ORDER BY ordinal_position
");

foreach ($transportistaColumns as $col) {
    echo "  - {$col->column_name} ({$col->data_type})\n";
}

// Verificar relaciones de trazabilidad
echo "\n--- VERIFICANDO FOREIGN KEYS PARA TRAZABILIDAD ---\n";

echo "\n1. planta.loteplanta_entradacampo:\n";
$lpeCols = DB::select("
    SELECT column_name, data_type 
    FROM information_schema.columns 
    WHERE table_schema = 'planta' AND table_name = 'loteplanta_entradacampo'
");
foreach ($lpeCols as $col) {
    echo "   - {$col->column_name} ({$col->data_type})\n";
}

echo "\n2. planta.lotesalida:\n";
$lsCols = DB::select("
    SELECT column_name, data_type 
    FROM information_schema.columns 
    WHERE table_schema = 'planta' AND table_name = 'lotesalida'
");
foreach ($lsCols as $col) {
    echo "   - {$col->column_name} ({$col->data_type})\n";
}

echo "\n3. logistica.enviodetalle:\n";
$edCols = DB::select("
    SELECT column_name, data_type 
    FROM information_schema.columns 
    WHERE table_schema = 'logistica' AND table_name = 'enviodetalle'
");
foreach ($edCols as $col) {
    echo "   - {$col->column_name} ({$col->data_type})\n";
}

echo "\n4. comercial.pedidodetalle:\n";
$pdCols = DB::select("
    SELECT column_name, data_type 
    FROM information_schema.columns 
    WHERE table_schema = 'comercial' AND table_name = 'pedidodetalle'
");
foreach ($pdCols as $col) {
    echo "   - {$col->column_name} ({$col->data_type})\n";
}

echo "\n=== FIN DE VERIFICACIÓN ===\n";
