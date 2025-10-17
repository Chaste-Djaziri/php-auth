<?php
// Load environment variables
require_once 'load_env.php';

session_start();
header('Content-Type: application/json');

// Supabase configuration
define('SUPABASE_URL', getenv('SUPABASE_URL'));
define('SUPABASE_KEY', getenv('SUPABASE_ANON_KEY'));
define('DEBUG_MODE', getenv('APP_DEBUG') === 'true');

// Validate required environment variables
if (!SUPABASE_URL || !SUPABASE_KEY) {
    echo json_encode(['success' => false, 'message' => 'Missing Supabase configuration. Please check your environment variables.']);
    exit;
}

// Debug logging function
function debugLog($message, $data = null) {
    if (DEBUG_MODE) {
        $logMessage = date('Y-m-d H:i:s') . " - " . $message;
        if ($data !== null) {
            $logMessage .= " - Data: " . json_encode($data);
        }
        error_log($logMessage);
    }
}

// Log request details
debugLog("Auth request", [
    'action' => $_GET['action'] ?? 'none',
    'method' => $_SERVER['REQUEST_METHOD'],
    'user_agent' => $_SERVER['HTTP_USER_AGENT'] ?? 'unknown'
]);

// Get action from query parameter
$action = $_GET['action'] ?? '';

// Helper function to make Supabase API calls
function supabaseRequest($endpoint, $method = 'GET', $data = null) {
    $url = SUPABASE_URL . '/rest/v1/' . $endpoint;
    
    $headers = [
        'apikey: ' . SUPABASE_KEY,
        'Authorization: Bearer ' . SUPABASE_KEY,
        'Content-Type: application/json',
        'Prefer: return=representation'
    ];
    
    debugLog("Supabase REST API call", [
        'url' => $url,
        'method' => $method,
        'data' => $data
    ]);
    
    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
    curl_setopt($ch, CURLOPT_TIMEOUT, 30);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, true);
    
    if ($method === 'POST') {
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
    } elseif ($method === 'PATCH') {
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'PATCH');
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
    }
    
    $response = curl_exec($ch);
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    $curlError = curl_error($ch);
    curl_close($ch);
    
    if ($curlError) {
        debugLog("CURL Error", $curlError);
        return [
            'code' => 0,
            'data' => ['error' => 'Network error: ' . $curlError]
        ];
    }
    
    $decodedResponse = json_decode($response, true);
    
    debugLog("Supabase REST API response", [
        'http_code' => $httpCode,
        'response' => $decodedResponse
    ]);
    
    return [
        'code' => $httpCode,
        'data' => $decodedResponse
    ];
}

// Helper function for Supabase Auth
function supabaseAuth($endpoint, $data) {
    $url = SUPABASE_URL . '/auth/v1/' . $endpoint;
    
    $headers = [
        'apikey: ' . SUPABASE_KEY,
        'Content-Type: application/json'
    ];
    
    debugLog("Supabase Auth API call", [
        'url' => $url,
        'endpoint' => $endpoint,
        'data' => $data
    ]);
    
    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
    curl_setopt($ch, CURLOPT_TIMEOUT, 30);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, true);
    
    $response = curl_exec($ch);
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    $curlError = curl_error($ch);
    curl_close($ch);
    
    if ($curlError) {
        debugLog("CURL Error in Auth", $curlError);
        return [
            'code' => 0,
            'data' => ['error' => 'Network error: ' . $curlError]
        ];
    }
    
    $decodedResponse = json_decode($response, true);
    
    debugLog("Supabase Auth API response", [
        'http_code' => $httpCode,
        'response' => $decodedResponse
    ]);
    
    return [
        'code' => $httpCode,
        'data' => $decodedResponse
    ];
}

// Handle different actions
switch ($action) {
    case 'signup':
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            echo json_encode(['success' => false, 'message' => 'Invalid request method']);
            exit;
        }
        
        $email = $_POST['email'] ?? '';
        $password = $_POST['password'] ?? '';
        $fullname = $_POST['fullname'] ?? '';
        
        debugLog("Signup attempt", [
            'email' => $email,
            'fullname' => $fullname,
            'password_length' => strlen($password)
        ]);
        
        if (empty($email) || empty($password) || empty($fullname)) {
            echo json_encode(['success' => false, 'message' => 'All fields are required']);
            exit;
        }
        
        // Sign up with Supabase Auth
        $result = supabaseAuth('signup', [
            'email' => $email,
            'password' => $password,
            'data' => [
                'fullname' => $fullname
            ]
        ]);
        
        debugLog("Signup result", $result);
        
        // Handle different response codes
        if ($result['code'] === 200 || $result['code'] === 201) {
            if (isset($result['data']['user'])) {
                echo json_encode([
                    'success' => true,
                    'message' => 'Account created successfully! Please login.',
                    'debug' => DEBUG_MODE ? $result : null
                ]);
            } else {
                echo json_encode([
                    'success' => false, 
                    'message' => 'User created but no user data returned. Please try logging in.',
                    'debug' => DEBUG_MODE ? $result : null
                ]);
            }
        } elseif ($result['code'] === 422) {
            // User already exists or validation error
            $errorMsg = $result['data']['msg'] ?? $result['data']['error_description'] ?? 'User already exists or validation failed';
            echo json_encode([
                'success' => false, 
                'message' => $errorMsg,
                'debug' => DEBUG_MODE ? $result : null
            ]);
        } else {
            $errorMsg = $result['data']['msg'] ?? $result['data']['error_description'] ?? $result['data']['error'] ?? 'Signup failed';
            echo json_encode([
                'success' => false, 
                'message' => 'Signup failed: ' . $errorMsg,
                'debug' => DEBUG_MODE ? $result : null
            ]);
        }
        break;
        
    case 'login':
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            echo json_encode(['success' => false, 'message' => 'Invalid request method']);
            exit;
        }
        
        $email = $_POST['email'] ?? '';
        $password = $_POST['password'] ?? '';
        
        debugLog("Login attempt", [
            'email' => $email,
            'password_length' => strlen($password)
        ]);
        
        if (empty($email) || empty($password)) {
            echo json_encode(['success' => false, 'message' => 'Email and password are required']);
            exit;
        }
        
        // Login with Supabase Auth
        $result = supabaseAuth('token?grant_type=password', [
            'email' => $email,
            'password' => $password
        ]);
        
        debugLog("Login result", $result);
        
        if ($result['code'] === 200 && isset($result['data']['access_token'])) {
            $_SESSION['user_id'] = $result['data']['user']['id'];
            $_SESSION['user_email'] = $result['data']['user']['email'];
            $_SESSION['user_fullname'] = $result['data']['user']['user_metadata']['fullname'] ?? '';
            $_SESSION['access_token'] = $result['data']['access_token'];
            $_SESSION['created_at'] = $result['data']['user']['created_at'];
            
            debugLog("Login successful", [
                'user_id' => $_SESSION['user_id'],
                'user_email' => $_SESSION['user_email']
            ]);
            
            echo json_encode([
                'success' => true,
                'message' => 'Login successful',
                'debug' => DEBUG_MODE ? $result : null
            ]);
        } else {
            $errorMsg = 'Invalid email or password';
            if (isset($result['data']['error_description'])) {
                $errorMsg = $result['data']['error_description'];
            } elseif (isset($result['data']['msg'])) {
                $errorMsg = $result['data']['msg'];
            } elseif (isset($result['data']['error'])) {
                $errorMsg = $result['data']['error'];
            }
            
            echo json_encode([
                'success' => false, 
                'message' => $errorMsg,
                'debug' => DEBUG_MODE ? $result : null
            ]);
        }
        break;
        
    case 'check':
        if (isset($_SESSION['user_id'])) {
            echo json_encode([
                'success' => true,
                'user' => [
                    'id' => $_SESSION['user_id'],
                    'email' => $_SESSION['user_email'],
                    'fullname' => $_SESSION['user_fullname'],
                    'created_at' => $_SESSION['created_at']
                ]
            ]);
        } else {
            echo json_encode(['success' => false, 'message' => 'Not authenticated']);
        }
        break;
        
    case 'logout':
        session_destroy();
        echo json_encode(['success' => true, 'message' => 'Logged out successfully']);
        break;
        
    default:
        echo json_encode(['success' => false, 'message' => 'Invalid action']);
        break;
}
?>