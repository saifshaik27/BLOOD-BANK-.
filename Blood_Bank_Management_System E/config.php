<?php
// Database connection
$host = "localhost";
$dbname = "blood_bank_db";
$username = "root";
$password = "";

try {
    $pdo = new PDO(
        "mysql:host=$host;dbname=$dbname;charset=utf8mb4",
        $username,
        $password,
        [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
         PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC]
    );
} catch (PDOException $e) {
    die("Database connection failed. Check XAMPP and config.php.");
}
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
function h($value) {
    return htmlspecialchars((string)$value, ENT_QUOTES, "UTF-8");
}
