<?php
/**
 * Railway Startup Script
 * This script handles PHP path detection and starts the server
 */

// Function to find PHP executable
function findPhpExecutable() {
    $possiblePaths = [
        '/usr/bin/php',
        '/usr/local/bin/php',
        '/opt/php/bin/php',
        'php' // fallback to PATH
    ];
    
    foreach ($possiblePaths as $path) {
        if (is_executable($path) || $path === 'php') {
            $output = [];
            $returnCode = 0;
            exec("$path --version 2>&1", $output, $returnCode);
            if ($returnCode === 0) {
                return $path;
            }
        }
    }
    
    return 'php'; // fallback
}

// Get PHP executable path
$phpPath = findPhpExecutable();
$port = getenv('PORT') ?: '8000';

echo "🚀 Starting PHP Server...\n";
echo "📍 PHP Path: $phpPath\n";
echo "🌐 Port: $port\n";
echo "📁 Document Root: " . __DIR__ . "\n\n";

// Start the server
$command = "$phpPath -S 0.0.0.0:$port -t " . __DIR__;
echo "🔧 Running: $command\n\n";

// Execute the command
passthru($command);
?>
