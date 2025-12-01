<?php
try {
    $db = new PDO('pgsql:host=127.0.0.1;port=5433;dbname=AgroPapas', 'postgres', 'Slv6001313.');
    
    echo "=== cat.variedadpapa columns ===\n";
    $result = $db->query("
        SELECT column_name, data_type
        FROM information_schema.columns 
        WHERE table_schema = 'cat' AND table_name = 'variedadpapa'
        ORDER BY ordinal_position
    ");
    
    foreach($result as $row) {
        echo $row['column_name'] . " (" . $row['data_type'] . ")\n";
    }
    
} catch(Exception $e) {
    echo 'Error: ' . $e->getMessage();
}
