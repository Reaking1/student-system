<?php
// backend/api/auth/login.php

header("Content-Type: application/json");

require_once __DIR__ . '/../db.php';
require_once __DIR__ . '/../../middleware/validation.php';
require_once __DIR__ . '/../../middleware/auth_middleware.php';

$data = json_decode(file_get_contents("php://input"), true);

// Validate inputs
$username = trim($data['username'] ?? '');
$password = trim($data['password'] ?? '');

if ($username === '' || $password === '') {
    http_response_code(400);
    echo json_encode(['status'=>'error','message'=>'Username and password required']);
    exit;
}

// Check user
$stmt = $pdo->prepare("SELECT * FROM admins WHERE username = :username LIMIT 1");
$stmt->execute([':username' => $username]);
$user = $stmt->fetch();

if (!$user || !password_verify($password, $user['password'])) {
    http_response_code(401);
    echo json_encode(['status'=>'error','message'=>'Invalid credentials']);
    exit;
}

// Generate token (simplified version)
$token = base64_encode(random_bytes(32));

// Store token in session or database if needed
// For now, return it to frontend
echo json_encode([
    'status' => 'success',
    'message' => 'Login successful',
    'token' => $token,
    'user' => [
        'id' => $user['id'],
        'username' => $user['username']
    ]
]);
