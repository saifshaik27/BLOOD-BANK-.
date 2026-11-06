<?php
require_once __DIR__ . "/config.php";
if (!empty($_SESSION["admin_id"])) {
    header("Location: index.php"); exit;
}
$error = "";
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $email = trim($_POST["email"] ?? "");
    $password = $_POST["password"] ?? "";
    $stmt = $pdo->prepare("SELECT * FROM admins WHERE email = ?");
    $stmt->execute([$email]);
    $admin = $stmt->fetch();
    if ($admin && password_verify($password, $admin["password"])) {
        session_regenerate_id(true);
        $_SESSION["admin_id"] = $admin["admin_id"];
        $_SESSION["admin_name"] = $admin["name"];
        header("Location: index.php"); exit;
    }
    $error = "Invalid email or password.";
}
?>
<!DOCTYPE html>
<html lang="en"><head><meta charset="UTF-8"><meta name="viewport" content="width=device-width, initial-scale=1">
<title>Admin Login | LifeLine Blood Bank</title><link rel="stylesheet" href="style.css"></head>
<body class="login-page">
<div class="login-card">
  <div class="brand-mark">✚</div><h1>LifeLine Blood Bank</h1><p class="muted">Administrator Login</p>
  <?php if ($error): ?><div class="alert error"><?= h($error) ?></div><?php endif; ?>
  <form method="post">
    <label>Email address</label><input type="email" name="email" required placeholder="admin@bloodbank.com">
    <label>Password</label><input type="password" name="password" required placeholder="Enter password">
    <button class="btn primary full" type="submit">Login</button>
  </form>
  <p class="hint">Demo: admin@bloodbank.com / admin123</p>
</div></body></html>
