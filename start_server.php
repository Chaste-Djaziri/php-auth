<?php
/**
 * Simple PHP Development Server Startup Script
 * This script starts a local PHP development server
 */

echo "🚀 Starting PHP Development Server...\n";
echo "📁 Serving files from: " . __DIR__ . "\n";
echo "🌐 Server will be available at: http://localhost:8000\n";
echo "📝 Make sure to configure your Supabase credentials in config.env\n";
echo "⏹️  Press Ctrl+C to stop the server\n\n";

// Check if config.env exists
if (!file_exists(__DIR__ . '/config.env')) {
    echo "⚠️  WARNING: config.env file not found!\n";
    echo "📋 Please copy config.env.example to config.env and configure your Supabase credentials.\n\n";
}

// Start the PHP development server
$command = "php -S localhost:8000 -t " . __DIR__;
echo "🔧 Running command: {$command}\n\n";

// Execute the server command
passthru($command);
?>
