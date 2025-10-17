<?php
// Load environment variables
require_once 'load_env.php';

session_start();

// Check if debug mode is enabled
$debugMode = getenv('APP_DEBUG') === 'true';

if (!$debugMode) {
    die('Debug mode is disabled. Set APP_DEBUG=true in config.env to enable.');
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Debug Console</title>
    <style>
        body {
            font-family: 'Courier New', monospace;
            background: #1a1a1a;
            color: #00ff00;
            margin: 0;
            padding: 20px;
        }
        .container {
            max-width: 1200px;
            margin: 0 auto;
        }
        .section {
            background: #2a2a2a;
            border: 1px solid #444;
            border-radius: 5px;
            margin: 20px 0;
            padding: 20px;
        }
        .section h2 {
            color: #ffff00;
            margin-top: 0;
        }
        .config-item {
            display: flex;
            justify-content: space-between;
            padding: 5px 0;
            border-bottom: 1px solid #333;
        }
        .config-item:last-child {
            border-bottom: none;
        }
        .config-label {
            color: #00ffff;
        }
        .config-value {
            color: #ffffff;
            word-break: break-all;
        }
        .test-form {
            background: #333;
            padding: 15px;
            border-radius: 5px;
            margin: 10px 0;
        }
        .test-form input, .test-form button {
            padding: 8px;
            margin: 5px;
            border: 1px solid #555;
            background: #444;
            color: #fff;
            border-radius: 3px;
        }
        .test-form button {
            background: #0066cc;
            cursor: pointer;
        }
        .test-form button:hover {
            background: #0088ff;
        }
        .response {
            background: #222;
            border: 1px solid #444;
            padding: 10px;
            margin: 10px 0;
            border-radius: 3px;
            white-space: pre-wrap;
            font-size: 12px;
        }
        .success { border-color: #00aa00; }
        .error { border-color: #aa0000; }
        .log-entry {
            background: #111;
            border-left: 3px solid #666;
            padding: 5px 10px;
            margin: 2px 0;
            font-size: 11px;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>🔧 Debug Console</h1>
        
        <div class="section">
            <h2>Configuration</h2>
            <div class="config-item">
                <span class="config-label">Supabase URL:</span>
                <span class="config-value"><?php echo getenv('SUPABASE_URL') ?: 'Not set'; ?></span>
            </div>
            <div class="config-item">
                <span class="config-label">Supabase Key:</span>
                <span class="config-value"><?php echo getenv('SUPABASE_ANON_KEY') ? substr(getenv('SUPABASE_ANON_KEY'), 0, 20) . '...' : 'Not set'; ?></span>
            </div>
            <div class="config-item">
                <span class="config-label">Debug Mode:</span>
                <span class="config-value"><?php echo $debugMode ? 'Enabled' : 'Disabled'; ?></span>
            </div>
            <div class="config-item">
                <span class="config-label">Session Status:</span>
                <span class="config-value"><?php echo isset($_SESSION['user_id']) ? 'Logged in as ' . $_SESSION['user_email'] : 'Not logged in'; ?></span>
            </div>
        </div>

        <div class="section">
            <h2>Test Signup</h2>
            <div class="test-form">
                <form id="signupForm">
                    <input type="text" name="fullname" placeholder="Full Name" required>
                    <input type="email" name="email" placeholder="Email" required>
                    <input type="password" name="password" placeholder="Password" required>
                    <button type="submit">Test Signup</button>
                </form>
                <div id="signupResponse" class="response" style="display: none;"></div>
            </div>
        </div>

        <div class="section">
            <h2>Test Login</h2>
            <div class="test-form">
                <form id="loginForm">
                    <input type="email" name="email" placeholder="Email" required>
                    <input type="password" name="password" placeholder="Password" required>
                    <button type="submit">Test Login</button>
                </form>
                <div id="loginResponse" class="response" style="display: none;"></div>
            </div>
        </div>

        <div class="section">
            <h2>Test Auth Check</h2>
            <div class="test-form">
                <button onclick="testAuthCheck()">Check Authentication</button>
                <div id="authCheckResponse" class="response" style="display: none;"></div>
            </div>
        </div>

        <div class="section">
            <h2>PHP Error Logs</h2>
            <div id="errorLogs">
                <?php
                $errorLog = ini_get('error_log');
                if ($errorLog && file_exists($errorLog)) {
                    $logs = file_get_contents($errorLog);
                    $recentLogs = array_slice(explode("\n", $logs), -20);
                    foreach ($recentLogs as $log) {
                        if (trim($log)) {
                            echo '<div class="log-entry">' . htmlspecialchars($log) . '</div>';
                        }
                    }
                } else {
                    echo '<div class="log-entry">No error log found or accessible</div>';
                }
                ?>
            </div>
        </div>
    </div>

    <script>
        // Test signup
        document.getElementById('signupForm').addEventListener('submit', async function(e) {
            e.preventDefault();
            const formData = new FormData(this);
            const responseDiv = document.getElementById('signupResponse');
            
            try {
                const response = await fetch('auth.php?action=signup', {
                    method: 'POST',
                    body: formData
                });
                const result = await response.json();
                
                responseDiv.style.display = 'block';
                responseDiv.className = 'response ' + (result.success ? 'success' : 'error');
                responseDiv.textContent = JSON.stringify(result, null, 2);
            } catch (error) {
                responseDiv.style.display = 'block';
                responseDiv.className = 'response error';
                responseDiv.textContent = 'Error: ' + error.message;
            }
        });

        // Test login
        document.getElementById('loginForm').addEventListener('submit', async function(e) {
            e.preventDefault();
            const formData = new FormData(this);
            const responseDiv = document.getElementById('loginResponse');
            
            try {
                const response = await fetch('auth.php?action=login', {
                    method: 'POST',
                    body: formData
                });
                const result = await response.json();
                
                responseDiv.style.display = 'block';
                responseDiv.className = 'response ' + (result.success ? 'success' : 'error');
                responseDiv.textContent = JSON.stringify(result, null, 2);
            } catch (error) {
                responseDiv.style.display = 'block';
                responseDiv.className = 'response error';
                responseDiv.textContent = 'Error: ' + error.message;
            }
        });

        // Test auth check
        async function testAuthCheck() {
            const responseDiv = document.getElementById('authCheckResponse');
            
            try {
                const response = await fetch('auth.php?action=check');
                const result = await response.json();
                
                responseDiv.style.display = 'block';
                responseDiv.className = 'response ' + (result.success ? 'success' : 'error');
                responseDiv.textContent = JSON.stringify(result, null, 2);
            } catch (error) {
                responseDiv.style.display = 'block';
                responseDiv.className = 'response error';
                responseDiv.textContent = 'Error: ' + error.message;
            }
        }
    </script>
</body>
</html>
