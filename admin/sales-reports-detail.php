<?php
date_default_timezone_set('Asia/Manila');

session_start();
error_reporting(0);
include('includes/dbconnection.php');

if (strlen($_SESSION['bpmsaid']) == 0) {
    header('location:logout.php');
    exit();
}

$fdate  = mysqli_real_escape_string($con, $_POST['fromdate']);
$tdate  = mysqli_real_escape_string($con, $_POST['todate']);
$rtype  = $_POST['requesttype'];
$source = $_POST['source'] ?? 'all';

$ftotal = $ftotal_online = $ftotal_walkin = 0;
$data = [];

/* ONLINE */
if ($source === 'all' || $source === 'online') {

    if ($rtype === 'mtwise') {
        $q = mysqli_query($con,"
            SELECT MONTH(PostingDate) AS lmonth,
                   YEAR(PostingDate) AS lyear,
                   SUM(tblservices.Cost) AS total
            FROM tblinvoice
            JOIN tblservices ON tblservices.ID = tblinvoice.ServiceId
            WHERE DATE(tblinvoice.PostingDate) BETWEEN '$fdate' AND '$tdate'
            GROUP BY lyear, lmonth
        ");

        while ($r = mysqli_fetch_array($q)) {
            $key = $r['lmonth'].'/'.$r['lyear'];
            $data[$key]['label'] = date('F', mktime(0,0,0,$r['lmonth'],1)).' '.$r['lyear'];
            $data[$key]['online'] = ($data[$key]['online'] ?? 0) + $r['total'];
        }
    } else {
        $q = mysqli_query($con,"
            SELECT YEAR(PostingDate) AS lyear,
                   SUM(tblservices.Cost) AS total
            FROM tblinvoice
            JOIN tblservices ON tblservices.ID = tblinvoice.ServiceId
            WHERE DATE(tblinvoice.PostingDate) BETWEEN '$fdate' AND '$tdate'
            GROUP BY lyear
        ");

        while ($r = mysqli_fetch_array($q)) {
            $key = $r['lyear'];
            $data[$key]['label'] = $r['lyear'];
            $data[$key]['online'] = ($data[$key]['online'] ?? 0) + $r['total'];
        }
    }
}

/* WALK-IN */
if ($source === 'all' || $source === 'walkin') {

    if ($rtype === 'mtwise') {
        $q = mysqli_query($con,"
            SELECT MONTH(appointment_date) AS lmonth,
                   YEAR(appointment_date) AS lyear,
                   SUM(amount) AS total
            FROM walkin_customers
            WHERE invoice_status='Accepted'
            AND appointment_date BETWEEN '$fdate' AND '$tdate'
            GROUP BY lyear, lmonth
        ");

        while ($r = mysqli_fetch_array($q)) {
            $key = $r['lmonth'].'/'.$r['lyear'];
            $data[$key]['label'] = $data[$key]['label'] ?? (date('F', mktime(0,0,0,$r['lmonth'],1)).' '.$r['lyear']);
            $data[$key]['walkin'] = ($data[$key]['walkin'] ?? 0) + $r['total'];
        }
    } else {
        $q = mysqli_query($con,"
            SELECT YEAR(appointment_date) AS lyear,
                   SUM(amount) AS total
            FROM walkin_customers
            WHERE invoice_status='Accepted'
            AND appointment_date BETWEEN '$fdate' AND '$tdate'
            GROUP BY lyear
        ");

        while ($r = mysqli_fetch_array($q)) {
            $key = $r['lyear'];
            $data[$key]['label'] = $r['lyear'];
            $data[$key]['walkin'] = ($data[$key]['walkin'] ?? 0) + $r['total'];
        }
    }
}

ksort($data);

$source_labels = [
    'all' => 'All Channels',
    'online' => 'Online Sales',
    'walkin' => 'Walk-In Sales'
];
$source_label = $source_labels[$source] ?? 'All Channels';
?>

<!DOCTYPE html>
<html>
<head>
<title>Inventory Sales Report</title>
<link href="css/bootstrap.css" rel="stylesheet">

<style>
body {
    background:#f5f7fb;
    font-family: Arial;
}
.report-wrapper {
    max-width: 1200px;
    margin: 30px auto;
    background: #fff;
    padding: 25px;
    border-radius: 14px;
    box-shadow: 0 10px 25px rgba(0,0,0,0.08);
}
.report-header {
    display:flex;
    justify-content:space-between;
    border-bottom:2px solid #eee;
    padding-bottom:15px;
    margin-bottom:20px;
}
.report-title h2 {
    margin:0;
    font-size:22px;
    color:#222;
}
.report-meta {
    text-align:right;
    font-size:13px;
    color:#555;
}
.print-btn {
    background:#198754;
    color:#fff;
    border:none;
    padding:10px 16px;
    border-radius:8px;
    cursor:pointer;
    font-size:13px;
    margin-bottom:15px;
}
.cards {
    display:grid;
    grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
    gap:15px;
    margin-bottom:20px;
}
.card {
    background:#fff;
    padding:18px;
    border-radius:12px;
    box-shadow:0 4px 10px rgba(0,0,0,0.06);
    border-left:5px solid #ccc;
}
.card.online { border-left-color:#0d6efd; }
.card.walkin { border-left-color:#198754; }
.card.total { border-left-color:#212529; }
.card h4 {
    margin:0;
    font-size:13px;
    color:#777;
}
.card p {
    margin:6px 0 0;
    font-size:20px;
    font-weight:bold;
}
table {
    width:100%;
    border-collapse:collapse;
    margin-top:10px;
}
th {
    background:#212529;
    color:#fff;
    padding:12px;
    font-size:13px;
}
td {
    padding:10px;
    border-bottom:1px solid #eee;
    font-size:13px;
}
tr:hover {
    background:#f9fbff;
}
.total-box {
    margin-top:15px;
    background:#f1f3f5;
    padding:15px;
    border-radius:10px;
    text-align:right;
    font-size:18px;
    font-weight:bold;
}
@media print {
    .print-btn { display:none; }
    body { background:#fff; }
    .report-wrapper { box-shadow:none; margin:0; }
}
</style>
</head>

<body>

<div class="report-wrapper">

<button class="print-btn" onclick="window.print()">🖨️ Print Inventory Report</button>

<div class="report-header">
    <div class="report-title">
        <h2>INVENTORY SALES REPORT</h2>
        <small><?php echo $source_label; ?></small>
    </div>

    <div class="report-meta">
        <b>Period:</b><br>
        <?php
        echo ($rtype === 'mtwise')
            ? date('F Y', strtotime($fdate))." → ".date('F Y', strtotime($tdate))
            : date('Y', strtotime($fdate))." → ".date('Y', strtotime($tdate));
        ?>
        <br><br>
        Printed: <?php echo date("F d, Y h:i A"); ?>
    </div>
</div>

<?php
foreach ($data as $row) {
    $ftotal_online += $row['online'] ?? 0;
    $ftotal_walkin += $row['walkin'] ?? 0;
}
$ftotal = $ftotal_online + $ftotal_walkin;
?>

<div class="cards">

    <div class="card online">
        <h4>Online Sales</h4>
        <p>₱<?php echo number_format($ftotal_online,2); ?></p>
    </div>

    <div class="card walkin">
        <h4>Walk-In Sales</h4>
        <p>₱<?php echo number_format($ftotal_walkin,2); ?></p>
    </div>

    <div class="card total">
        <h4>Total Revenue</h4>
        <p>₱<?php echo number_format($ftotal,2); ?></p>
    </div>

</div>

<table>
<tr>
    <th>#</th>
    <th>Period</th>
    <th>Online</th>
    <th>Walk-In</th>
    <th>Total</th>
</tr>

<?php $cnt=1; foreach ($data as $row):
$online = $row['online'] ?? 0;
$walkin = $row['walkin'] ?? 0;
$total = $online + $walkin;
?>
<tr>
    <td><?php echo $cnt++; ?></td>
    <td><?php echo $row['label']; ?></td>
    <td>₱<?php echo number_format($online,2); ?></td>
    <td>₱<?php echo number_format($walkin,2); ?></td>
    <td><b>₱<?php echo number_format($total,2); ?></b></td>
</tr>
<?php endforeach; ?>
</table>

<div class="total-box">
GRAND TOTAL: ₱<?php echo number_format($ftotal,2); ?>
</div>

</div>

</body>
</html>