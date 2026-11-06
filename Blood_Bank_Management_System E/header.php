<?php require_once __DIR__ . "/auth.php"; ?>
<!DOCTYPE html>
<html lang="en"><head><meta charset="UTF-8"><meta name="viewport" content="width=device-width, initial-scale=1">
<title><?= h($page_title ?? "Dashboard") ?> | LifeLine Blood Bank</title><link rel="stylesheet" href="style.css"></head>
<body>
<header class="topbar"><a class="brand" href="index.php"><span class="brand-mark small">✚</span> LifeLine <b>Blood Bank</b></a>
<div class="top-right"><span>Welcome, <?= h($_SESSION["admin_name"] ?? "Admin") ?></span><a class="logout" href="logout.php">Logout</a></div></header>
<div class="layout"><aside class="sidebar">
<a href="index.php" class="<?= ($active ?? '')==='dashboard'?'active':'' ?>">▦ &nbsp; Dashboard</a>
<a href="stock.php" class="<?= ($active ?? '')==='stock'?'active':'' ?>">🩸 &nbsp; Blood Stock</a>
<a href="requests.php" class="<?= ($active ?? '')==='requests'?'active':'' ?>">＋ &nbsp; Blood Requests</a>
</aside><main class="content">
