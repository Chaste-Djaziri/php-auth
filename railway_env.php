<?php
/**
 * Railway-specific environment variable loader
 * Railway automatically provides environment variables
 */

function loadRailwayEnv() {
    // Railway automatically provides environment variables
    // No need to load from file - they're already available via getenv()
    
    // Set default values if not provided
    if (!getenv('APP_NAME')) {
        putenv('APP_NAME=PHP Auth App');
    }
    
    if (!getenv('APP_ENV')) {
        putenv('APP_ENV=production');
    }
    
    if (!getenv('APP_DEBUG')) {
        putenv('APP_DEBUG=false');
    }
    
    // Log environment status (for debugging)
    if (getenv('APP_DEBUG') === 'true') {
        error_log("Railway Environment Loaded - SUPABASE_URL: " . (getenv('SUPABASE_URL') ? 'SET' : 'NOT SET'));
        error_log("Railway Environment Loaded - SUPABASE_ANON_KEY: " . (getenv('SUPABASE_ANON_KEY') ? 'SET' : 'NOT SET'));
    }
}

// Load Railway environment
loadRailwayEnv();
?>
