<?php
$page_title = "Dashboard"; $active = "dashboard";
require_once "config.php";
require_once "auth.php";
$total = (int)$pdo->query("SELECT COALESCE(SUM(units_available),0) FROM blood_stock")->fetchColumn();
$groups = (int)$pdo->query("SELECT COUNT(*) FROM blood_stock")->fetchColumn();
$pending = (int)$pdo->query("SELECT COUNT(*) FROM blood_requests WHERE status='Pending'")->fetchColumn();
$recent = $pdo->query("SELECT * FROM blood_requests ORDER BY request_date DESC, request_id DESC LIMIT 5")->fetchAll();
require "header.php";
?>
<div class="page-heading"><div><p class="eyebrow">OVERVIEW</p><h1>Dashboard</h1><p class="muted">Monitor blood availability and hospital requests.</p></div><a class="btn primary" href="requests.php">＋ New Request</a></div>
<div class="stats">
 <div class="stat-card"><span class="stat-icon red">🩸</span><div><p>Total Blood Units</p><h2><?= $total ?></h2></div></div>
 <div class="stat-card"><span class="stat-icon blue">◉</span><div><p>Blood Groups</p><h2><?= $groups ?></h2></div></div>
 <div class="stat-card"><span class="stat-icon amber">◷</span><div><p>Pending Requests</p><h2><?= $pending ?></h2></div></div>
</div>
<section class="panel"><div class="panel-head"><h2>Recent Blood Requests</h2><a href="requests.php">View all →</a></div>
<?php if (!$recent): ?><p class="empty">No requests have been added yet.</p><?php else: ?>
<div class="table-wrap"><table><thead><tr><th>Hospital</th><th>Blood Group</th><th>Units</th><th>Date</th><th>Status</th></tr></thead><tbody>
<?php foreach ($recent as $r): ?><tr><td><?= h($r["hospital_name"]) ?></td><td><span class="blood-tag"><?= h($r["blood_group"]) ?></span></td><td><?= (int)$r["units_required"] ?></td><td><?= h($r["request_date"]) ?></td><td><span class="status <?= strtolower($r["status"]) ?>"><?= h($r["status"]) ?></span></td></tr><?php endforeach; ?>
</tbody></table></div><?php endif; ?></section>
<?php require "footer.php"; ?>
