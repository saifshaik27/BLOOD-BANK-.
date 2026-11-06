<?php
// Run this file once after importing database.sql, then delete it.
require_once __DIR__ . "/config.php";
$name = "Administrator";
$email = "admin@bloodbank.com";
$hash = password_hash("admin123", PASSWORD_DEFAULT);
$stmt = $pdo->prepare("INSERT INTO admins (name, email, password) VALUES (?, ?, ?) ON DUPLICATE KEY UPDATE name=VALUES(name)");
$stmt->execute([$name, $email, $hash]);
echo "Admin account ready.<br>Email: admin@bloodbank.com<br>Password: admin123<br><b>Delete setup_admin.php after setup.</b>";
