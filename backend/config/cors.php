<?php
// Allow requests from your frontend
header("Access-Control-Allow-Origin: http://localhost:5173");

// Allow common HTTP methods
header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS");

// Allow common headers
header("Access-Control-Allow-Headers: Content-Type, Authorization");

// If this is a preflight request, stop here
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit;
}
