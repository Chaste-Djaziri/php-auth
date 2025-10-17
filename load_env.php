<?php
/**
 * Environment variable loader for PHP
 * Works with both local config.env files and Vercel environment variables
 */

function loadEnv($filePath) {
    if (!file_exists($filePath)) {
        // In production (Vercel), environment variables are already set
        return;
    }
    
    $lines = file($filePath, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
    
    foreach ($lines as $line) {
        // Skip comments
        if (strpos(trim($line), '#') === 0) {
            continue;
        }
        
        // Parse key=value pairs
        if (strpos($line, '=') !== false) {
            list($key, $value) = explode('=', $line, 2);
            $key = trim($key);
            $value = trim($value);
            
            // Remove quotes if present
            if ((substr($value, 0, 1) === '"' && substr($value, -1) === '"') ||
                (substr($value, 0, 1) === "'" && substr($value, -1) === "'")) {
                $value = substr($value, 1, -1);
            }
            
            // Set environment variable if not already set
            if (!getenv($key)) {
                putenv("{$key}={$value}");
                $_ENV[$key] = $value;
            }
        }
    }
}

// Load environment variables from config.env (for local development)
// In production (Railway/Vercel), environment variables are automatically available
try {
    // Check if we're in Railway (Railway sets RAILWAY_ENVIRONMENT)
    if (getenv('RAILWAY_ENVIRONMENT')) {
        // Railway environment - variables are already set
        error_log("Railway environment detected - using system environment variables");
    } else {
        // Local development - load from config.env
        loadEnv(__DIR__ . '/config.env');
    }
} catch (Exception $e) {
    error_log("Failed to load environment variables: " . $e->getMessage());
}
?>
