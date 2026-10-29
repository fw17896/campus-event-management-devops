<?php

$host = getenv('DB_HOST');
$db   = getenv('DB_NAME') ?: 'campus_events';
$user = getenv('DB_USERNAME');
$pass = getenv('DB_PASSWORD');
$port = (int) (getenv('DB_PORT') ?: 3306);

if (!$host || !$user || !$pass) {
    error_log("Database environment variables are missing.");
    http_response_code(500);
    die("Database configuration is incomplete.");
}

$conn = new mysqli($host, $user, $pass, $db, $port);

if ($conn->connect_error) {
    error_log("Database connection failed: " . $conn->connect_error);
    http_response_code(500);
    die("Database connection failed. Please try again later.");
}

$conn->set_charset("utf8mb4");

?>