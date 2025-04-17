<?php
require_once 'db_config.php'; // This loads the array $DB_CONFIG

$conn = new mysqli(
    $DB_CONFIG['host'],
    $DB_CONFIG['user'],
    $DB_CONFIG['pass'],
    $DB_CONFIG['name']
);

if ($conn->connect_error) {
    error_log("DB Connection failed: " . $conn->connect_error); // safe logging
    die("Database connection error.");
}

$conn->set_charset("utf8mb4");
?>