<?php
// backend/api/auth/login.php


// === CORS Headers ===
header("Access-Control-Allow-Origin: http://localhost:5173");
header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type, Authorization");

// Handle preflight OPTIONS request
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit;
}

// Set JSON response header
header("Content-Type: application/json");

require_once __DIR__ . '/../db.php';
require_once __DIR__ . '/../../middleware/validation.php';

$data = json_decode(file_get_contents("php://input"), true);

// Validate inputs
$username = trim($data['username'] ?? '');
$password = trim($data['password'] ?? '');

if ($username === '' || $password === '') {
    http_response_code(400);
    echo json_encode(['status'=>'error','message'=>'Username and password required']);
    exit;
}

// Check user in the users table
$stmt = $pdo->prepare("SELECT * FROM users WHERE username = :username LIMIT 1");
$stmt->execute([':username' => $username]);
$user = $stmt->fetch();

if (!$user || !password_verify($password, $user['password'])) {
    http_response_code(401);
    echo json_encode(['status'=>'error','message'=>'Invalid credentials']);
    exit;
}

// Optional: Only allow login if admin
if ($user['role'] !== 'admin') {
    http_response_code(403);
    echo json_encode(['status'=>'error','message'=>'Access denied: not an admin']);
    exit;
}

// Generate a simple token (can store in sessions table if needed)
$token = base64_encode(random_bytes(32));

// Store token in sessions table (optional)
$stmt = $pdo->prepare("INSERT INTO sessions (user_id, token) VALUES (:user_id, :token)");
$stmt->execute([
    ':user_id' => $user['id'],
    ':token' => $token
]);

// Return response
echo json_encode([
    'status' => 'success',
    'message' => 'Login successful',
    'token' => $token,
    'user' => [
        'id' => $user['id'],
        'username' => $user['username'],
        'role' => $user['role']
    ]
]);
