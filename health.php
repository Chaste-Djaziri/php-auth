<?php
/**
 * Health check endpoint for Railway
 * This endpoint helps Railway monitor your application
 */

// Load environment variables
require_once 'load_env.php';

// Set JSON response header
header('Content-Type: application/json');

// Basic health check
$health = [
    'status' => 'healthy',
    'timestamp' => date('Y-m-d H:i:s'),
    'environment' => getenv('APP_ENV') ?: 'unknown',
    'debug_mode' => getenv('APP_DEBUG') === 'true',
    'supabase_configured' => !empty(getenv('SUPABASE_URL')) && !empty(getenv('SUPABASE_ANON_KEY')),
    'php_version' => PHP_VERSION,
    'server_time' => time()
];

// Check if we're in Railway
if (getenv('RAILWAY_ENVIRONMENT')) {
    $health['platform'] = 'railway';
    $health['railway_environment'] = getenv('RAILWAY_ENVIRONMENT');
}

// Return health status
http_response_code(200);
echo json_encode($health, JSON_PRETTY_PRINT);
?>
