<?php
session_start();
error_reporting(0);
include('includes/dbconnection.php');

if (strlen($_SESSION['bpmsaid']) == 0) {
    header('location:logout.php');
    exit();
}

/* DELETE ONLINE */
if (isset($_GET['delid'])) {
    $sid = (int)$_GET['delid'];
    mysqli_query($con, "DELETE FROM tblinvoice WHERE BillingId='$sid'");
    echo "<script>alert('Invoice Deleted');window.location.href='invoices.php'</script>";
    exit();
}

/* DELETE WALK-IN */
if (isset($_GET['del_walkin'])) {
    $wid = (int)$_GET['del_walkin'];
    mysqli_query($con, "DELETE FROM walkin_customers WHERE id='$wid'");
    echo "<script>alert('Walk-In Deleted');window.location.href='invoices.php'</script>";
    exit();
}
?>

<!DOCTYPE HTML>
<html>
<head>
<title>BPMS || Invoices</title>

<link href="css/bootstrap.css" rel="stylesheet">
<link href="css/style.css" rel="stylesheet">
<link href="css/font-awesome.css" rel="stylesheet">

<style>

body{
    background:#f4f6f9;
}

/* WRAPPER */
.container-box{
    max-width:1350px;
    margin:50px auto;
}

/* HEADER */
.page-header{
    display:flex;
    justify-content:space-between;
    align-items:center;
    margin-bottom:15px;
}

.page-title{
    font-size:22px;
    font-weight:700;
    color:#222;
}

/* BUTTONS */
.btn-back{
    background:#212529;
    color:#fff;
    padding:8px 14px;
    border-radius:8px;
    text-decoration:none;
    font-size:13px;
}

.btn-print{
    background:#198754;
    color:#fff;
    border:none;
    padding:8px 14px;
    border-radius:8px;
    font-size:13px;
}

/* CARD */
.card-box{
    background:#fff;
    padding:20px;
    border-radius:12px;
    box-shadow:0 5px 15px rgba(0,0,0,0.08);
}

/* BADGES */
.badge-online{
    background:#0d6efd;
    color:#fff;
    padding:5px 10px;
    border-radius:20px;
    font-size:12px;
}

.badge-walkin{
    background:#198754;
    color:#fff;
    padding:5px 10px;
    border-radius:20px;
    font-size:12px;
}

/* TABLE */
.table thead th{
    background:#212529;
    color:#fff;
    font-size:13px;
    padding:12px;
}

.table tbody td{
    font-size:13px;
    padding:12px;
    vertical-align:middle;
}

.table tbody tr:hover{
    background:#f8f9ff;
}

/* BUTTONS */
.btn-sm{
    border-radius:8px;
    font-size:12px;
}

/* PRINT */
@media print{
    .page-header,
    .btn{
        display:none !important;
    }

    body{
        background:#fff;
    }

    .card-box{
        box-shadow:none;
    }
}

</style>
</head>

<body>

<?php include_once('includes/sidebar.php'); ?>
<?php include_once('includes/header.php'); ?>

<div id="page-wrapper">

<div class="container-box">

<!-- HEADER -->
<div class="page-header">

    <div class="page-title">🧾 Invoice Dashboard</div>

    <div>
        <a href="dashboard.php" class="btn-back">⬅ Back</a>
    </div>

</div>

<!-- CARD -->
<div class="card-box">

<div class="table-responsive">

<table class="table table-bordered">

<thead>
<tr>
<th>#</th>
<th>Invoice ID</th>
<th>Customer</th>
<th>Services</th>
<th>Date</th>
<th>Type</th>
<th>Action</th>
</tr>
</thead>

<tbody>

<?php
$cnt = 1;

/* ONLINE */
$ret = mysqli_query($con,"
SELECT BillingId, Userid, MAX(PostingDate) as invoicedate
FROM tblinvoice
GROUP BY BillingId, Userid
ORDER BY MAX(ID) DESC
");

while ($row = mysqli_fetch_array($ret)) {

$bid = $row['BillingId'];
$uid = $row['Userid'];

$cus = mysqli_query($con,"
SELECT FirstName, LastName
FROM tbluser
WHERE ID='$uid'
");
$crow = mysqli_fetch_array($cus);

$services = "";
$sq = mysqli_query($con,"
SELECT s.ServiceName
FROM tblinvoice i
JOIN tblservices s ON s.ID=i.ServiceId
WHERE i.BillingId='$bid'
");

while ($s = mysqli_fetch_array($sq)) {
    $services .= $s['ServiceName'] . ", ";
}
$services = rtrim($services, ", ");
?>

<tr>
<td><?= $cnt++ ?></td>
<td><?= $bid ?></td>
<td><?= $crow['FirstName'] ?? '' ?> <?= $crow['LastName'] ?? '' ?></td>
<td><?= $services ?: 'No Service' ?></td>
<td><?= date('Y-m-d', strtotime($row['invoicedate'])) ?></td>
<td><span class="badge-online">Online</span></td>

<td>
<a href="view-invoice.php?invoiceid=<?= $bid ?>&type=Online" class="btn btn-primary btn-sm">
<i class="fa fa-eye"></i>
</a>

<a href="invoices.php?delid=<?= $bid ?>" class="btn btn-danger btn-sm"
onclick="return confirm('Delete invoice?')">
<i class="fa fa-trash"></i>
</a>
</td>

</tr>

<?php } ?>

<?php
/* WALK-IN */
$ret2 = mysqli_query($con,"SELECT * FROM walkin_customers ORDER BY id DESC");

while ($w = mysqli_fetch_array($ret2)) {
$wid = $w['id'];
?>

<tr>
<td><?= $cnt++ ?></td>
<td>WLK-<?= str_pad($wid,4,'0',STR_PAD_LEFT) ?></td>
<td><?= $w['customer_name'] ?></td>
<td><?= $w['service'] ?></td>
<td><?= $w['appointment_date'] ?></td>
<td><span class="badge-walkin">Walk-In</span></td>

<td>
<a href="view-invoice.php?invoiceid=<?= $wid ?>&type=Walk-in" class="btn btn-primary btn-sm">
<i class="fa fa-eye"></i>
</a>

<a href="invoices.php?del_walkin=<?= $wid ?>" class="btn btn-danger btn-sm"
onclick="return confirm('Delete walk-in?')">
<i class="fa fa-trash"></i>
</a>
</td>
</tr>

<?php } ?>

</tbody>
</table>

</div>

</div>

</div>

</div>

</body>
</html>