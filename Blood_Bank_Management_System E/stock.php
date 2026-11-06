<?php
$page_title = "Blood Stock"; $active = "stock";
require_once "config.php"; require_once "auth.php";
$groups = ["A+","A-","B+","B-","AB+","AB-","O+","O-"];
$message = ""; $error = "";
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $group = $_POST["blood_group"] ?? "";
    $units = filter_var($_POST["units_available"] ?? null, FILTER_VALIDATE_INT);
    if (!in_array($group, $groups, true) || $units === false || $units < 0) {
        $error = "Please enter a valid blood group and non-negative unit count.";
    } else {
        $stmt = $pdo->prepare("INSERT INTO blood_stock (blood_group, units_available, last_updated) VALUES (?, ?, CURDATE()) ON DUPLICATE KEY UPDATE units_available=VALUES(units_available), last_updated=CURDATE()");
        $stmt->execute([$group, $units]);
        $message = "Blood stock saved successfully.";
    }
}
$stock = $pdo->query("SELECT * FROM blood_stock ORDER BY FIELD(blood_group,'A+','A-','B+','B-','AB+','AB-','O+','O-')")->fetchAll();
require "header.php";
?>
<div class="page-heading"><div><p class="eyebrow">INVENTORY</p><h1>Blood Stock</h1><p class="muted">Add or update the available units for each blood group.</p></div></div>
<?php if ($message): ?><div class="alert success"><?= h($message) ?></div><?php endif; ?><?php if ($error): ?><div class="alert error"><?= h($error) ?></div><?php endif; ?>
<section class="panel"><h2>Update Blood Stock</h2><form method="post" class="inline-form">
<div><label>Blood Group</label><select name="blood_group" required><?php foreach($groups as $g): ?><option value="<?= h($g) ?>"><?= h($g) ?></option><?php endforeach; ?></select></div>
<div><label>Available Units</label><input type="number" name="units_available" min="0" required placeholder="Example: 25"></div>
<button class="btn primary" type="submit">Save Stock</button></form><p class="hint">Saving an existing blood group updates its available units.</p></section>
<section class="panel"><div class="panel-head"><h2>Current Stock</h2></div>
<?php if (!$stock): ?><p class="empty">No stock added. Use the form above to add blood groups.</p><?php else: ?>
<div class="table-wrap"><table><thead><tr><th>Blood Group</th><th>Units Available</th><th>Last Updated</th></tr></thead><tbody>
<?php foreach($stock as $s): ?><tr><td><span class="blood-tag"><?= h($s["blood_group"]) ?></span></td><td><b><?= (int)$s["units_available"] ?></b></td><td><?= h($s["last_updated"]) ?></td></tr><?php endforeach; ?>
</tbody></table></div><?php endif; ?></section>
<?php require "footer.php"; ?>
