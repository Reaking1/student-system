<?php
// backend/config/cors.php

// === CORS CONFIGURATION ===

// Frontend origin (adjust if needed)
$allowedOrigin = "http://localhost:5173";

// Always send these headers
header("Access-Control-Allow-Origin: $allowedOrigin");
header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type, Authorization");
header("Access-Control-Allow-Credentials: true"); // needed if using cookies or auth headers

// Handle preflight OPTIONS request
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    // Optional: specify allowed headers and methods again
    header("Access-Control-Max-Age: 86400"); // cache preflight for 1 day
    http_response_code(200);
    exit;
}
