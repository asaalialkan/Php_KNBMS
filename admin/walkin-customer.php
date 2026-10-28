<?php
session_start();

include('includes/dbconnection.php');

date_default_timezone_set('Asia/Manila');

if (!isset($_SESSION['bpmsaid'])) {
    header('location:logout.php');
    exit;
}

$toast_msg = "";

/* =========================
   SAVE WALK-IN (SECURE)
========================= */
if (isset($_POST['save_walkin'])) {

    $name    = trim($_POST['customer_name'] ?? '');
    $phone   = trim($_POST['phone'] ?? '');
    $service = trim($_POST['service'] ?? '');

    $date = date('Y-m-d');
    $time = date('H:i:s');
    $price = 0;

    if ($service !== '') {
        $stmt = $con->prepare("SELECT Cost FROM tblservices WHERE ServiceName = ? LIMIT 1");
        $stmt->bind_param("s", $service);
        $stmt->execute();
        $res = $stmt->get_result();

        if ($row = $res->fetch_assoc()) {
            $price = (float)$row['Cost'];
        }
        $stmt->close();
    }

    $stmt = $con->prepare("
        INSERT INTO walkin_customers
        (customer_name, phone, service, amount, appointment_date, appointment_time, invoice_status)
        VALUES (?, ?, ?, ?, ?, ?, 'Pending')
    ");

    $stmt->bind_param("sssiss", $name, $phone, $service, $price, $date, $time);
    $stmt->execute();
    $stmt->close();

    $toast_msg = "Walk-in saved successfully!";
}

/* =========================
   UPDATE STATUS (SECURE)
========================= */
if (isset($_POST['update_status'])) {

    $id = (int)($_POST['walkin_id'] ?? 0);
    $status = trim($_POST['status'] ?? '');

    $allowed = ['Accepted', 'Not Accepted', 'Pending'];

    if (in_array($status, $allowed, true)) {

        $stmt = $con->prepare("
            UPDATE walkin_customers
            SET invoice_status = ?
            WHERE id = ?
        ");

        $stmt->bind_param("si", $status, $id);
        $stmt->execute();
        $stmt->close();

        $toast_msg = "Status updated.";
    }
}

/* =========================
   STATS
========================= */
$total_walkins = $con->query("SELECT COUNT(*) AS c FROM walkin_customers")->fetch_assoc()['c'] ?? 0;

$today_walkins = $con->query("
    SELECT COUNT(*) AS c
    FROM walkin_customers
    WHERE appointment_date = CURDATE()
")->fetch_assoc()['c'] ?? 0;

$total_services = $con->query("SELECT COUNT(*) AS c FROM tblservices")->fetch_assoc()['c'] ?? 0;

$total_revenue = $con->query("
    SELECT COALESCE(SUM(amount),0) AS rev
    FROM walkin_customers
    WHERE invoice_status = 'Accepted'
")->fetch_assoc()['rev'] ?? 0;

/* =========================
   DATA
========================= */
$walkins = $con->query("
    SELECT * FROM walkin_customers
    ORDER BY id DESC
");

?>

<!DOCTYPE html>
<html>
<head>
<title>Walk-in Dashboard</title>

<link rel="stylesheet" href="css/bootstrap.css">
<link rel="stylesheet" href="css/font-awesome.css">

<style>
body{background:#f4f6f9;margin:0;font-family:Arial}
.wrapper{display:flex;justify-content:center;padding:30px 15px}
.box{width:100%;max-width:1100px;background:#fff;border-radius:20px;padding:25px;box-shadow:0 10px 30px rgba(0,0,0,0.08)}
.top-header{background:#007bff;color:#fff;padding:15px;border-radius:12px;display:flex;justify-content:space-between;margin-bottom:20px}
.dashboard-header{background:#111;color:#fff;padding:20px;border-radius:15px;margin-bottom:20px}
.stats-grid{display:grid;grid-template-columns:repeat(auto-fit,minmax(150px,1fr));gap:15px}
.stat-card{background:rgba(255,255,255,0.1);padding:15px;border-radius:12px}
.form-grid{display:grid;grid-template-columns:repeat(auto-fit,minmax(200px,1fr));gap:10px;margin-bottom:15px}
input,select{width:100%;padding:12px;border:1px solid #ddd;border-radius:10px}
.button-group button{padding:10px 20px;border:none;border-radius:8px;background:#28a745;color:#fff}
table{width:100%;border-collapse:collapse;margin-top:10px}
th{background:#111;color:#fff;padding:10px}
td{padding:10px;border-bottom:1px solid #eee}
.status{padding:4px 10px;border-radius:20px;color:#fff;font-size:12px}
.accepted{background:#28a745}
.pending{background:#007bff}
.notaccepted{background:#dc3545}
.toast{position:fixed;bottom:20px;right:20px;background:#111;color:#fff;padding:12px 15px;border-radius:10px;display:none}
</style>

</head>

<body>

<?php include('includes/sidebar.php'); ?>

<div class="wrapper">
<div class="box">

<div class="top-header">
    <h3><i class="fa fa-users"></i> Walk-in Dashboard</h3>
    <span><?= date('F d, Y') ?></span>
</div>

<div class="dashboard-header">
<div class="stats-grid">

<div class="stat-card">
<small>Total Walk-ins</small>
<h3><?= $total_walkins ?></h3>
</div>

<div class="stat-card">
<small>Today</small>
<h3><?= $today_walkins ?></h3>
</div>

<div class="stat-card">
<small>Services</small>
<h3><?= $total_services ?></h3>
</div>

<div class="stat-card">
<small>Revenue</small>
<h3>₱<?= number_format($total_revenue,2) ?></h3>
</div>

</div>
</div>

<!-- FORM -->
<form method="post">

<div class="form-grid">

<input type="text" name="customer_name" placeholder="Customer Name" required>

<input type="text" name="phone" placeholder="Phone">

<select name="service" required>
<option value="">Select Service</option>
<?php
$s = $con->query("SELECT * FROM tblservices");
while($r = $s->fetch_assoc()):
?>
<option value="<?= $r['ServiceName'] ?>">
<?= $r['ServiceName'] ?> - ₱<?= number_format($r['Cost'],2) ?>
</option>
<?php endwhile; ?>
</select>

</div>

<div class="button-group">
<button type="submit" name="save_walkin">Save Walk-in</button>
</div>

</form>

<!-- TABLE -->
<table>
<tr>
<th>#</th>
<th>Name</th>
<th>Service</th>
<th>Amount</th>
<th>Status</th>
<th>Action</th>
</tr>

<?php
$i = 1;
while($row = $walkins->fetch_assoc()):

$map = [
    'Accepted' => 'accepted',
    'Not Accepted' => 'notaccepted',
    'Pending' => 'pending'
];

$cls = $map[$row['invoice_status']] ?? 'pending';
?>

<tr>
<td>#<?= $i++ ?></td>
<td><?= htmlspecialchars($row['customer_name']) ?></td>
<td><?= htmlspecialchars($row['service']) ?></td>
<td>₱<?= number_format($row['amount'],2) ?></td>

<td><span class="status <?= $cls ?>">
<?= $row['invoice_status'] ?>
</span></td>

<td>

<form method="post">
<input type="hidden" name="walkin_id" value="<?= $row['id'] ?>">

<select name="status">
<?php
foreach(['Accepted','Not Accepted','Pending'] as $st):
?>
<option value="<?= $st ?>" <?= $row['invoice_status']==$st?'selected':'' ?>>
<?= $st ?>
</option>
<?php endforeach; ?>
</select>

<button name="update_status">Update</button>
</form>

</td>
</tr>

<?php endwhile; ?>

</table>

</div>
</div>

<div class="toast" id="toast"><?= $toast_msg ?></div>

<script>
let msg = "<?= $toast_msg ?>";
if(msg){
    let t = document.getElementById('toast');
    t.style.display = 'block';
    setTimeout(()=> t.style.display='none', 3000);
}
</script>

</body>
</html>