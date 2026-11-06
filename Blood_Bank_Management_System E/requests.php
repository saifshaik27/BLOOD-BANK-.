<?php
$page_title = "Blood Requests"; $active = "requests";
require_once "config.php"; require_once "auth.php";
$groups = ["A+","A-","B+","B-","AB+","AB-","O+","O-"];
$message = ""; $error = "";
if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST["add_request"])) {
    $hospital = trim($_POST["hospital_name"] ?? "");
    $group = $_POST["blood_group"] ?? "";
    $units = filter_var($_POST["units_required"] ?? null, FILTER_VALIDATE_INT);
    if ($hospital === "" || strlen($hospital)>150 || !in_array($group,$groups,true) || $units===false || $units<1) {
        $error = "Enter a valid hospital name, blood group and number of units.";
    } else {
        $stmt=$pdo->prepare("INSERT INTO blood_requests (hospital_name,blood_group,units_required,request_date,status) VALUES (?,?,?,CURDATE(),'Pending')");
        $stmt->execute([$hospital,$group,$units]); $message="Blood request added successfully.";
    }
}
if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST["decision"])) {
    $id = filter_var($_POST["request_id"] ?? null, FILTER_VALIDATE_INT);
    $decision = $_POST["decision"];
    if ($id && in_array($decision,["Approved","Rejected"],true)) {
        try {
            $pdo->beginTransaction();
            $stmt=$pdo->prepare("SELECT * FROM blood_requests WHERE request_id=? FOR UPDATE");
            $stmt->execute([$id]); $request=$stmt->fetch();
            if (!$request || $request["status"] !== "Pending") {
                throw new RuntimeException("This request has already been processed.");
            }
            if ($decision === "Approved") {
                $stmt=$pdo->prepare("SELECT units_available FROM blood_stock WHERE blood_group=? FOR UPDATE");
                $stmt->execute([$request["blood_group"]]); $available=$stmt->fetchColumn();
                if ($available === false || (int)$available < (int)$request["units_required"]) {
                    throw new RuntimeException("Not enough stock to approve this request.");
                }
                $stmt=$pdo->prepare("UPDATE blood_stock SET units_available=units_available-?, last_updated=CURDATE() WHERE blood_group=?");
                $stmt->execute([$request["units_required"],$request["blood_group"]]);
            }
            $stmt=$pdo->prepare("UPDATE blood_requests SET status=? WHERE request_id=?");
            $stmt->execute([$decision,$id]); $pdo->commit(); $message="Request ".$decision." successfully.";
        } catch (Throwable $e) {
            if ($pdo->inTransaction()) $pdo->rollBack();
            $error=$e->getMessage();
        }
    } else { $error="Invalid request action."; }
}
$requests=$pdo->query("SELECT * FROM blood_requests ORDER BY request_date DESC, request_id DESC")->fetchAll();
require "header.php";
?>
<div class="page-heading"><div><p class="eyebrow">HOSPITALS</p><h1>Blood Requests</h1><p class="muted">Record hospital requests and process pending applications.</p></div></div>
<?php if($message): ?><div class="alert success"><?= h($message) ?></div><?php endif; ?><?php if($error): ?><div class="alert error"><?= h($error) ?></div><?php endif; ?>
<section class="panel"><h2>Add New Request</h2><form method="post" class="request-form">
<input type="hidden" name="add_request" value="1">
<div><label>Hospital Name</label><input type="text" name="hospital_name" maxlength="150" required placeholder="Enter hospital name"></div>
<div><label>Blood Group</label><select name="blood_group" required><?php foreach($groups as $g): ?><option value="<?= h($g) ?>"><?= h($g) ?></option><?php endforeach; ?></select></div>
<div><label>Units Required</label><input type="number" name="units_required" min="1" required placeholder="Units"></div>
<button class="btn primary" type="submit">Add Request</button></form></section>
<section class="panel"><div class="panel-head"><h2>All Requests</h2><span class="muted"><?= count($requests) ?> total</span></div>
<?php if(!$requests): ?><p class="empty">No hospital requests yet.</p><?php else: ?>
<div class="table-wrap"><table><thead><tr><th>Hospital</th><th>Blood Group</th><th>Units</th><th>Date</th><th>Status</th><th>Action</th></tr></thead><tbody>
<?php foreach($requests as $r): ?><tr><td><?= h($r["hospital_name"]) ?></td><td><span class="blood-tag"><?= h($r["blood_group"]) ?></span></td><td><?= (int)$r["units_required"] ?></td><td><?= h($r["request_date"]) ?></td><td><span class="status <?= strtolower($r["status"]) ?>"><?= h($r["status"]) ?></span></td><td>
<?php if($r["status"]==="Pending"): ?><form method="post" class="actions"><input type="hidden" name="request_id" value="<?= (int)$r["request_id"] ?>"><button class="btn tiny approve" name="decision" value="Approved" type="submit">Approve</button><button class="btn tiny reject" name="decision" value="Rejected" type="submit">Reject</button></form><?php else: ?><span class="muted">Processed</span><?php endif; ?>
</td></tr><?php endforeach; ?>
</tbody></table></div><?php endif; ?></section>
<?php require "footer.php"; ?>
