<?php

use Illuminate\Support\Str;

require __DIR__ . '/vendor/autoload.php';

// Map of models to their likely PKs based on convention or inspection
// We can scan the model files to find the first element of $fillable.

$modelsDir = __DIR__ . '/app/Models';
$iterator = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($modelsDir));

foreach ($iterator as $file) {
    if ($file->isDir()) continue;
    if ($file->getExtension() !== 'php') continue;
    
    $content = file_get_contents($file->getPathname());
    
    // Skip if not a model (e.g. User.php might be different, or base classes)
    if (strpos($content, 'extends Model') === false) continue;
    
    // Find $fillable
    if (preg_match('/protected \$fillable = \[\'([^\']+)\'/', $content, $matches)) {
        $pk = $matches[1];
        
        // Skip if PK is 'id' (default)
        if ($pk === 'id') continue;
        
        echo "Found PK '$pk' for " . $file->getFilename() . "\n";
        
        // Add protected $primaryKey = '$pk';
        if (strpos($content, 'protected $primaryKey') === false) {
            $replacement = "protected \$table = '"; // We know we added this line
            // Insert before or after $table?
            // Let's insert after $table definition.
            $content = preg_replace(
                "/(protected \\\$table = '[^']+';)/", 
                "$1\n    protected \$primaryKey = '$pk';", 
                $content
            );
            file_put_contents($file->getPathname(), $content);
            echo "Updated Model: " . $file->getFilename() . "\n";
        }
    }
}

// Now fix the Views
$viewsDir = __DIR__ . '/resources/views';
$viewIterator = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($viewsDir));

foreach ($viewIterator as $file) {
    if ($file->isDir()) continue;
    if ($file->getExtension() !== 'php') continue; // .blade.php
    
    $content = file_get_contents($file->getPathname());
    
    // Replace ->id with ->getKey()
    // Be careful not to replace things like `department_id` (string literal) or `->id` if it's not on a model.
    // But in these generated views, `->id` is almost exclusively used on the model instance.
    // e.g. $departamento->id
    // e.g. route(..., $departamento->id)
    
    // Regex: ->id\b (word boundary)
    // We want to replace `$var->id` with `$var->getKey()`
    
    $newContent = preg_replace('/->id\b/', '->getKey()', $content);
    
    if ($content !== $newContent) {
        file_put_contents($file->getPathname(), $newContent);
        echo "Updated View: " . $file->getFilename() . "\n";
    }
}
